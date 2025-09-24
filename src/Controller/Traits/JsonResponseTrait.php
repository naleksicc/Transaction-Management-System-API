<?php

namespace App\Controller\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonResponseTrait
{
    public function jsonResponse(
        mixed $data = null,
        int $status = JsonResponse::HTTP_OK
    ): JsonResponse
    {
        return new JsonResponse($data, $status);
    }

    public function jsonResponseOnPost(string $guid): JsonResponse
    {
        return $this->jsonResponse(['guid' => $guid], JsonResponse::HTTP_CREATED);
    }

    public function jsonResponseNoContent(): JsonResponse
    {
        return $this->jsonResponse("NO CONTENT", JsonResponse::HTTP_NO_CONTENT);
    }
}
