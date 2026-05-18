<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse{
    //Success Response
    protected function successResponse(
            mixed $data = null,
            string $message = 'Pesan sukses',
            int $code = 200
        ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
    //Success Response

    //Error Response
    protected function errorResponse(
        string $message = 'Error',
        int $code = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => $errors,
        ],$code);
    }
    //Error Response

    //Created Response
    protected function createdResponse(
        mixed $data =  null,
        string $message = 'Data berhasil dibuat'
    ): JsonResponse{
        return $this-> successResponse($data, $message, 201);
    }
    //Created Response

    //Not Found
    protected function notFoundResponse(
        string $message = 'Data tidak ditemukan'
    ): JsonResponse{
        return $this->errorResponse($message, 404);
    }
    //Not Found

    //Validation error response
    protected function validationErrorResponse(
        mixed $errors,
        string $message = 'Validasi gagal'
    ): JsonResponse{
        return $this->errorResponse($message, 422, $errors);
    }
    //Validation error response

    protected function notLoggedInResponse(
        string $message = 'Anda belum login'
    ): JsonResponse{
        return $this->errorResponse($message, 401);
    }
    //Not Logged In

    //Unauthorized response
    protected function unauthorizedResponse(
        string $message = 'Anda tidak memiliki akses untuk mengubah data ini'
    ): JsonResponse{
        return $this->errorResponse($message, 403);
    }
    //Unauthorized response
}
