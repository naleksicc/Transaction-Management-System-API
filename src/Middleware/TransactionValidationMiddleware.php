<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\MiddlewareConstants;
use App\Exception\ValidationException;
use App\Services\Validation\TransactionValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class TransactionValidationMiddleware implements EventSubscriberInterface
{
    public function __construct(
        private readonly TransactionValidator $validator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['validate', MiddlewareConstants::VALIDATION_MIDDLEWARE_PRIORITY],
        ];
    }

    public function validate(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_POST) {
            return;
        }

        $data = json_decode($request->getContent(), true);

        try {
            $this->validator->validate($data);
            $request->attributes->set('validated_data', $data);

        } catch (ValidationException $e) {
            $request->attributes->set('validation_errors', $e->getViolations());
        }
    }
}
