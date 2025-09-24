<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Traits\JsonResponseTrait;
use App\Service\CsvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TransactionController extends AbstractController
{
    use JsonResponseTrait;

    public function __construct(private readonly CsvService $csvService)
    {
    }

    public function getAll(): JsonResponse
    {
        return $this->jsonResponse($this->csvService->getAllTransactions());
    }

    public function create(Request $request): JsonResponse
    {
        return $this->jsonResponseOnPost($this->csvService->createTransactionFromRequest($request));
    }
}
