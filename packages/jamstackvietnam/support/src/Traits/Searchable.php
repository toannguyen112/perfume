<?php

namespace Jamstackvietnam\Support\Traits;

use Nicolaslopezj\Searchable\SearchableTrait;

trait Searchable
{
    use SearchableTrait;

    public function scopeWhereLike($query, $column, $search)
    {
        if (empty($search)) return $query;

        $words = $this->splitToWords($search);
        $queries = $this->getSearchQueriesForColumn($column, 1, $words);

        $query->selectRaw(
            'max(' . implode(' + ', $queries) . ') as relevance',
            $this->search_bindings
        );

        return $query->orderBy('relevance', 'DESC')
            ->groupBy($this->getTable() . '.' . $this->primaryKey);
    }

    private function splitToWords($search)
    {
        $search = mb_strtolower(trim($search));
        preg_match_all('/(?:")((?:\\\\.|[^\\\\"])*)(?:")|(\S+)/', $search, $matches);
        $words = $matches[1];
        for ($i = 2; $i < count($matches); $i++) {
            $words = array_filter($words) + $matches[$i];
        }
        return $words;
    }
}
