<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Http\Request;

trait PaginatorHelperTrait
{
    /**
     * @param Request|null $request
     * @param $paginator
     * @param string $perPage
     * @param string $page
     * @param string $key
     * @param int|null $defaultPerPage
     * @return void
     */
    public function getRecordNumForFrontEnd(Request &$request = null, &$paginator, string $perPage = 'per_page', string $page = 'page', string $key = 'no', int $defaultPerPage = null): void
    {
        $request = $request ?? request();
        $numStarter = $request->query($perPage, $defaultPerPage ?? 10) * ($request->query($page, 1) - 1);
        $paginator->getCollection()->transform(function ($model) use (&$numStarter, &$key) {
            $model->{$key} = ++$numStarter;
            return $model;
        });
    }
}
