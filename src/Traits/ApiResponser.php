<?php

namespace AhmedArafat\AllInOne\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * displays the json response message
 *
 */
trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    public function errorResponse($message, $code)
    {
        $res = [];
        if (is_array($message)) {
            foreach ($message as $item) {
                $res[] = is_array($item) ? $item[0] : $item;
            }
        } else $res = $message;
        return response()->json(["status" => false, "message" => $res, 'data' => [], 'code' => $code], $code);
    }

    public function errorResponseAsArray($message, $code)
    {
        return response()->json(["status" => false, "message" => $message, 'data' => [], 'code' => $code], $code);
    }

    protected function showAll($collection, $message = "", $code = 200)
    {
        return $this->successResponse(["status" => true, "message" => $message, "data" => $collection], $code);
    }

    protected function showOne($model, $message = '', $code = 200)
    {
        return $this->successResponse(["status" => true, "message" => $message, "data" => $model], $code);
    }

    protected function showMessage($data, $message = '', $code = 200)
    {
        return $this->successResponse([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function showOnlyMessage($message = '', $code = 200)
    {
        return $this->successResponse([
            'status' => true,
            'message' => $message,
        ], $code);
    }

    protected function showPaginate($paginate, $message = '', $code = 200)
    {
        $data = $paginate->getCollection();
        $paginator = $paginate->toArray();
        unset($paginator['data']);
        return $this->successResponse([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'paginator' => $paginator
        ], $code);
    }
}
