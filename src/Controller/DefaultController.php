<?php

namespace App\Controller;

use App\Controller\Traits\JsonResponseTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    use JsonResponseTrait;

    public function handleOptionsRequest(): JsonResponse
    {
        return $this->jsonResponse("OK");
    }
}
