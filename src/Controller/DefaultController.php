<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractController
{
    public function handleOptionsRequest(): JsonResponse
    {
        return new JsonResponse("OK");
    }
}
