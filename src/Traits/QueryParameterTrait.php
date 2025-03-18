<?php

namespace AhmedArafat\AllInOne\Traits;

trait QueryParameterTrait
{
    /**
     * @throws Exception
     */
    public function validateAndExtractQueryParameter($keyName, $model): string|null
    {
        $value = request()->query($keyName);
        if ($value == null) return null;
        if (!is_numeric($value)) throw new Exception("Value Of Key `$keyName` Must Be A Number");
        $modelResult = $model::query()->find((int)$value);
        if ($modelResult == null) throw new Exception("Invalid `$keyName` Value");
        return (int)$value;
    }

    /**
     * @throws Exception
     */
    public function validateAndExtractQueryParameterMultiSelect($keyName, $model, $column = 'id'): array|null
    {
        $value = request()->query($keyName);
        if ($value == null) return null;
        $ids = [];
        foreach (explode(',', $value) as $value) {
            if ($value == null) throw new Exception("Value Of Key `$keyName` Cannot Be Empty");
            if (!is_numeric($value)) throw new Exception("Values Of Key `$keyName` Must Be A Number");
            $ids[(int)$value] = 1;
        }
        $modelResult = $model::query()
            ->whereIn($column, array_keys($ids))
            ->get([$column])
            ->pluck($column, $column);
        foreach ($ids as $id => $value) {
            if (!isset($modelResult[$id]))
                throw new Exception("Value `$id` Of Key `$keyName` Is Invalid");
        }
        return array_keys($ids);
    }
}
