<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorMessages;
use App\Services\Transaction\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends AbstractController
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }

    public function getAll(): JsonResponse
    {
        $result = $this->transactionService->getAll();
        return new JsonResponse($result, JsonResponse::HTTP_OK);
    }

    public function create(Request $request): JsonResponse
    {
        if ($request->attributes->has('validation_errors')) {
            $validationErrors = $request->attributes->get('validation_errors');
            return new JsonResponse([
                'error' => ErrorMessages::VALIDATION_FAILED,
                'violations' => $validationErrors
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $data = $request->attributes->get('validated_data');
        $this->transactionService->create($data);
        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
