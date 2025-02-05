<?php

namespace AhmedArafat\AllInOne\Helpers;


use AhmedArafat\AllInOne\Exceptions\ExceptionWithApiResponser;
use AhmedArafat\AllInOne\Exceptions\ValidationErrorsAsArrayException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ExceptionHelper
{
    public static function apiExceptionThrower(Exceptions &$exceptions): void
    {
        $exceptions->render(function (Throwable $e) {
            if (request()->is('api/*')) {
                $apiResponse = (new ExceptionWithApiResponser());
                if ($e instanceof ModelNotFoundException)
                    return $apiResponse->errorResponse($e->getMessage(), 404);
                if ($e instanceof ValidationException)
                    return $apiResponse->errorResponse($e->errors(), 422);
                if ($e instanceof MethodNotAllowedHttpException)
                    return $apiResponse->errorResponse($e->getMessage(), 404);
                if ($e instanceof NotFoundHttpException)
                    return $apiResponse->errorResponse($e->getMessage(), 404);
                if ($e instanceof AuthenticationException)
                    return $apiResponse->errorResponse('Unauthenticated User GLOBAL', 401);// 401: Unauthenticated
                if ($e instanceof AuthorizationException)
                    return $apiResponse->errorResponse($e->getMessage(), 403);// 403: Unauthorized
                if ($e instanceof QueryException) {
                    $errorCode = $e->errorInfo[1];
                    $errorMsg = $e->errorInfo[2]; // danger: it is a bad practice to include error msg of MySQL as it is a security thread
                    if ($errorCode == 1451)
                        return $apiResponse->errorResponse('Cannot delete this resource as it is related to another resource', 409);
                    return $apiResponse->errorResponse(['DBmsg' => $errorMsg], 409);
                }
                if ($e instanceof ValidationErrorsAsArrayException)
                    return $apiResponse->errorResponseAsArray(json_decode($e->getMessage(), true), 422);
                if ($e instanceof Exception)
                    return $apiResponse->errorResponse($e->getMessage(), $e->getCode() > 10 ? $e->getCode() : 404);
                return $apiResponse->errorResponse($e->getMessage(), $e->getCode() > 10 ? $e->getCode() : 404);
            }
        });
    }
}
