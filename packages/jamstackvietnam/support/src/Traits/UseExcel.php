<?php

namespace Jamstackvietnam\Support\Traits;

trait UseExcel
{
    protected $columns;

    public function setColumns($row)
    {
        if ($row->getIndex() > 1) return;

        $rows = $row->toArray();

        $columns = [];

        foreach ($rows as $index => $row) {
            $columns[] = (object) [
                'name' => $row,
                'index' => $index,
            ];
        }
        $this->columns = collect($columns);
    }

    public function getColumn($name)
    {
        return $this->columns->firstWhere('name', $name)?->index;
    }

    public function column($name)
    {
        $index = $this->getColumn($name);
        return !is_null($index) ? $this->currentRow[$index] : null;
    }

    public function getColumnContains($name): array
    {
        return $this->columns
                ->filter(fn ($item) => contains($item->name, $name))
                ->pluck('index')->toArray();
    }

    public function getColumnAfter($name): int
    {
        return $this->getColumn($name) + 1;
    }
}
