<?php

namespace Jamstackvietnam\Support\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

trait HasCrudActions
{
    use ApiResponse;

    public function index()
    {
        if (request()->wantsJson()) {
            return $this->getData();
        }

        return Inertia::render($this->folder() . '/Index');
    }

    private function getData()
    {
        $query = $this->model::query();

        $query = $this->loadRelations($query, 3);
        $query = $this->filter($query);

        if (request()->has('search')) {
            $query = $this->search($query)->distinct();
        } else {
            $query = $query->orderBy('id', 'desc');
        }

        $query = $query->distinct();

        if (request()->has('paginate') && !request()->input('paginate')) {
            $items = $query->limit(request()->input('limit', 20))->get();
        } else {
            $items = $query->paginate(request()->input('limit', 20));

            if (isset($this->appends['index'])) {
                $appendAttributes = $this->appends['index'];
                $items = $items->through(function ($item) use ($appendAttributes) {
                    return $item->append($appendAttributes);
                });
            }

            $items = $items->withQueryString();
        }
        return $items;
    }

    public function form()
    {
        $item = new $this->model;

        if (request()->has('id') && !is_null(request()->input('id'))) {
            $item = $this->loadRelations($item);

            if (!is_null($item->getMacro('withTrashed'))) {
                $item = $item->withTrashed();
            }

            $item = $item->findOrFail(request()->input('id'));
            $item = $this->setAppends($item);
            $item = array_merge($this->initEmptyModel($item), $item->toArray());
        } else {
            $itemTranslation = $this->initTranslation($item);
            $item = array_merge($this->initEmptyModel($item), $itemTranslation);
        }

        if (request()->wantsJson()) {
            return $item;
        }

        return Inertia::render($this->folder() . '/Form', [
            'item' => $item,
            'rules' => $this->getModelRules(__FUNCTION__)
        ]);
    }

    public function store(Request $request)
    {
        $this->validateRequest(__FUNCTION__);
        $is_update = $request->has('id') && !is_null($request->input('id'));
        if ($is_update) {
            $resource = $this->model::query();

            if (!is_null($resource->getMacro('withTrashed'))) {
                $resource = $resource->withTrashed();
            }

            $resource = $resource->findOrFail($request->input('id'));
            $resource->update($request->all());
        } else {
            $resource = $this->model::create($request->all());
        }

        if (request()->wantsJson()) {
            return $resource;
        }

        if ($is_update || (isset($this->redirectBack) && $this->redirectBack)) {
            return $this->redirectBack('L??u ?????i t?????ng th??nh c??ng.');
        } else {
            return $this->redirectToForm($resource->id, 'L??u ?????i t?????ng th??nh c??ng.');
        }
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();
            $resource = $this->model::findOrFail(request()->input('id'));
            $resource->delete();
            DB::commit();

            if (!is_null($resource->getMacro('withTrashed'))) {
                return $this->redirectBack('Xo?? th??nh c??ng.');
            } else {
                return $this->redirectToIndex();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            if (env('APP_ENV') === 'local') throw $e;
            return back()->withError($e->getMessage());
        }
    }

    public function restore()
    {
        $resource = $this->model::withTrashed()->findOrFail(request()->input('id'));
        $resource->restore();

        return $this->redirectBack('Kh??i ph???c th??nh c??ng.');
    }

    // Private

    private function model()
    {
        return new $this->model;
    }

    private function table()
    {
        return $this->model()->getTable();
    }

    private function folder()
    {
        return isset($this->folder) ? $this->folder : Str::studly($this->table());
    }

    private function path()
    {
        return str_replace('_', '-', $this->table());
    }

    private function search($query)
    {
        if (!method_exists($this->model, 'scopeSearch')) return $query;
        return $query->search(request()->input('search'));
    }

    private function getModelRules($action)
    {
        $rules = (new $this->model)->modelRules();
        if (isset($rules[$action])) {
            return $rules[$action];
        }
        if (in_array($action, ['store', 'form'])) {
            return $rules['store'] ?? $rules['form'] ?? $rules['all'] ?? [];
        }
        return $rules['all'] ?? [];
    }

    private function validateRequest($action)
    {
        $rules = $this->getModelRules($action);
        request()->validate($rules);
    }

    private function loadRelations($query, $deepFunction = 2)
    {
        $relations = $this->relationAttributes($deepFunction);
        return $query->with($relations['with'])->without($relations['without']);
    }

    private function relationAttributes($deepFunction = 2)
    {
        $action = debug_backtrace()[$deepFunction]['function'];
        return [
            'with' => isset($this->with) && isset($this->with[$action]) ? $this->with[$action] : [],
            'without' => isset($this->without) && isset($this->without[$action]) ? $this->without[$action] : [],
        ];
    }

    private function setAppends($item)
    {
        $attributes = $this->appendAttributes();
        return $attributes ? $item->append($attributes) : $item;
    }

    private function appendAttributes($deepFunction = 2)
    {
        $action = debug_backtrace()[$deepFunction]['function'];
        $attributes = isset($this->appends) && isset($this->appends[$action]) ? $this->appends[$action] : [];

        if (in_array('image', (new $this->model)->fillable)) {
            array_push($attributes, 'image');
        }

        return array_unique($attributes);
    }

    private function redirectBack($message)
    {
        return back()->with('success', $message);
    }

    private function redirectToForm($id, $message)
    {
        $currentRoute =  request()->route()->getName();
        $currentRoutePaths = explode('.', $currentRoute);
        $currentRoutePaths[count($currentRoutePaths) - 1] = 'form';
        $formRoute = implode('.', $currentRoutePaths);

        return redirect(route($formRoute, ['id' => $id]))->with('success', $message);
    }

    private function redirectToIndex()
    {
        $currentRoute =  request()->route()->getName();
        $currentRoutePaths = explode('.', $currentRoute);
        $currentRoutePaths[count($currentRoutePaths) - 1] = 'index';
        $formRoute = implode('.', $currentRoutePaths);

        return redirect(route($formRoute));
    }

    private function initTranslation($model)
    {
        if (!$model->translatedAttributes) return $model->toArray();

        $translations = [];
        foreach (config('translatable.locales') as $locale) {
            foreach ($model->translatedAttributes as $attribute) {
                $translations[] = [
                    'locale' => $locale,
                    $attribute => null,
                ];
            }
        }

        $model = $model->toArray();
        $model['translations'] = $translations;

        return $model;
    }

    private function initEmptyModel($model)
    {
        $modelArray = $model->toArray();
        $fields = array_merge(
            $model->fillable ?? [],
            $this->toSnakeCase($this->relationAttributes()['with']),
            $this->toSnakeCase($this->appendAttributes())
        );

        foreach ($fields as $field) {
            if ($field === 'status') {
                $modelArray[$field] = head($model::STATUS_LIST);
            } else if ($this->isPlural($field)) {
                $modelArray[$field]  = [];
            } else if ($this->columnType($field) === 'boolean') {
                $modelArray[$field]  = false;
            } else {
                $modelArray[$field]  = null;
            }
        }
        return $modelArray;
    }

    private function isPlural($word)
    {
        return Str::plural($word) === $word;
    }

    private function toSnakeCase($items)
    {
        foreach ($items as $key => $value) {
            $items[$key] = Str::snake($value);
        }
        return $items;
    }

    private function columnType($column)
    {
        try {
            return DB::connection()
                ->getDoctrineColumn($this->table(), $column)
                ->getType()->getName();
        } catch (\Exception $e) {
            return $column;
        }
    }

    private function filter($query)
    {
        return $query;
    }
}
