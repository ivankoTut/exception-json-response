<?php

namespace IvankoTut\ExceptionJsonResponse\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NotFoundError extends AbstractError
{
    public const STATUS_CODE = 404;
    public const DEFAULT_MESSAGE = 'Запрошенные данные не найдены';

    public string $type = 'NotFoundError';

    public static function isSupport(\Throwable $exception): bool
    {
        return $exception instanceof NotFoundHttpException;
    }

    public static function create(?string $message = null, array $trace = []): self
    {
        return new self($message ?? self::DEFAULT_MESSAGE, $trace);
    }
}
