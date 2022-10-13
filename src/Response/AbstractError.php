<?php

namespace IvankoTut\ExceptionJsonResponse\Response;

use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractError
{
    public const STATUS_CODE = '500';

    #[Groups(['response'])]
    public string $type = '';

    #[Groups(['response'])]
    public ?string $message = null;

    #[Groups(['debug'])]
    public array $trace = [];

    public function __construct(string $message = null, array $trace = [])
    {
        $this->message = $message;
        $this->trace = $trace;
    }

    abstract public static function isSupport(\Throwable $exception): bool;
    abstract public static function create(?string $message = null, array $trace = []): self;
}
