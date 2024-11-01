<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SearchableTrait
{
    /**
     * @param Builder $q
     * @param $columnsArray
     * @param string $table
     * @param string $search
     * @return void
     */
    private function searchBuilder(Builder &$q, $columnsArray, string $table, string $search): void
    {
        $i = 0;
        foreach ($columnsArray as $col) {
            $method = !$i++ ? 'where' : 'orWhere';
            $tableColName = $table . '.' . $col;
            $q->{$method}($tableColName, 'LIKE', "%$search%");
        }
    }

    /**
     * @param Builder $q
     * @param array $columns
     * @param $search
     * @param $relation
     * @return void
     */
    public function searchInSameRelation(Builder &$q, array &$columns, $search, $relation): void
    {
        $q->where(function ($q) use (&$columns, &$search, &$relation) {
            $this->searchBuilder($q, $columns, $relation, $search);
        });
    }

    /**
     * @param Builder $q
     * @param array $columns
     * @param $search
     * @param $relation
     * @param $methodName
     * @return void
     */
    public function searchInAnotherRelation(Builder &$q, array &$columns, $search, $relation, $methodName): void
    {
        $q->orWhereHas($methodName, function ($q2) use (&$search, &$columns, &$relation) {
            $q2->where(function ($q3) use (&$search, &$columns, &$relation) {
                $this->searchBuilder($q3, $columns, $relation, $search);
            });
        });
    }

    /**
     * @param Builder $q
     * @param string $searchKey
     * @return Builder
     */
    public function scopeSearch(Builder $q, string $searchKey = 'search'): Builder
    {
        #TODO: handle case that you have to explode string if space exists like "ahmed arafat", it will be like "%ahmed arafat%" and for first/second/last name it will fails
        if (request()->query($searchKey) == null || !isset($this->searchableColumns)) return $q;
        $search = request()->query($searchKey);
        foreach ($this->searchableColumns as $relation => $keys) {
            if ($relation == $this->getTable()) $this->searchInSameRelation($q, $keys['columns'], $search, $relation);
            else $this->searchInAnotherRelation($q, $keys['columns'], $search, $relation, $keys['methodName']);
        }
        //$q->dd();
        return $q;
    }
}
