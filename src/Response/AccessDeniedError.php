<?php

namespace IvankoTut\ExceptionJsonResponse\Response;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Finder\Exception\AccessDeniedException as FinderAccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException as FileAccessDeniedException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AccessDeniedError extends AbstractError
{
    public const STATUS_CODE = 403;
    public const DEFAULT_MESSAGE = 'Запрошенное действие не разрешено';

    public string $type = 'AccessDeniedError';

    public static function isSupport(\Throwable $exception): bool
    {
        return $exception instanceof AccessDeniedException ||
            $exception instanceof FinderAccessDeniedException ||
            $exception instanceof FileAccessDeniedException ||
            $exception instanceof AccessDeniedHttpException;
    }

    public static function create(?string $message = null, array $trace = []): self
    {
        return new self($message ?? self::DEFAULT_MESSAGE, $trace);
    }
}
