<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

trait ResponseTrait
{
    /**
     * Return data for response.
     *
     * @param  array|Collection|LengthAwarePaginator|Paginator  $data
     * @return array
     */
    private function setData($data)
    {
        if ($data instanceof Collection) {
            return $data->toArray();
        }

        return $data;
    }

    /**
     * @param  array|Collection|LengthAwarePaginator|Paginator  $data
     * @return JsonResponse
     */
    public function sendResponse($data): JsonResponse
    {
        $return = [
            'success' => true,
            'data' => $this->setData($data),
        ];

        return response()->json($return, 200);
    }

    /**
     * @param  array|Collection  $data
     * @param  int  $status
     * @return JsonResponse
     */
    public function sendErrorResponse($data, int $status = 500): JsonResponse
    {
        $return = [
            'success' => false,
            'data' => $this->setData($data),
        ];

        return response()->json($return, $status);
    }

    /**
     * @param  string  $message
     * @param  \Throwable  $th
     * @return JsonResponse
     */
    private function logAndSendErrorResponse(string $message, \Throwable $th): JsonResponse
    {
        Log::error($message, [
            'exception' => $th,
            'message' => $th->getMessage(),
            'code' => $th->getCode(),
            'trace' => $th->getTraceAsString(),
        ]);

        $errCode = 500;
        if (is_int($th->getCode()) && $th->getCode() !== 0) {
            $errCode = $th->getCode();
        }

        if ($th instanceof ValidationException) {
            $errCode = 422;
        }

        return $this->sendErrorResponse([
            'message' => $message,
        ], $errCode);
    }
}
