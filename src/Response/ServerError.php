<?php

namespace IvankoTut\ExceptionJsonResponse\Response;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ServerError extends AbstractError
{
    public const DEFAULT_MESSAGE = 'Ошибка сервера при обработке запроса';

    public string $type = 'ServerError';

    public static function isSupport(\Throwable $exception): bool
    {
        return $exception instanceof HttpExceptionInterface;
    }

    public static function create(?string $message = null, array $trace = []): self
    {
        return new self($message ?? self::DEFAULT_MESSAGE, $trace);
    }
}
