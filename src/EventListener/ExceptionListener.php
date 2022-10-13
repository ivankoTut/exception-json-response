<?php

namespace IvankoTut\ExceptionJsonResponse\EventListener;

use IvankoTut\ExceptionJsonResponse\Response\AbstractError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Throwable;
use IvankoTut\ExceptionJsonResponse\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ExceptionListener
{
    /*
     * массив ошибок в порядке их обработки из exceptions
     * первый класс, который смог обработать исключение создаст объект ошибки и прервёт очередь.
     */
    static protected array $responseQueue = [
        Response\AccessDeniedError::class,
        Response\NotFoundError::class,
        Response\ServerError::class,
    ];

    public function __construct(
        private readonly array $config,
        private readonly SerializerInterface $serializer
    ) { }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request   = $event->getRequest();

        if ($this->config['enable_only_application_json'] && $request->headers->get('Content-Type') !== 'application/json') {
            return;
        }

        if ($this->isExcludeException($exception)) {
            return;
        }

        $response = $this->getResponse($exception);
        if (!$response && !$this->config['listen_all_exception']) {
            return;
        }

        // создаем дефолтный ответ если не было найдено подходящего
        if (!$response && $this->config['listen_all_exception']) {
            $response = Response\ServerError::create($this->config['debug_mode'] ? $exception->getMessage() : null, $exception->getTrace());
        }

        $serializeGroup = $this->getSerializeGroups();
        $json = $this->serializer->serialize($response, 'json', ['groups' => $serializeGroup]);

        $event->setResponse(JsonResponse::fromJsonString($json, $response::STATUS_CODE));
        $event->stopPropagation();
    }

    private function getSerializeGroups(): array
    {
        $serializeGroup = ['response'];
        if ($this->config['debug_mode']) {
            $serializeGroup[] = 'debug';
        }

        return $serializeGroup;
    }

    private function getResponse(Throwable $exception): ?AbstractError
    {
        foreach (self::$responseQueue as $queue) {
            if ($queue::isSupport($exception)) {
                return $queue::create($this->getMessageForError($exception, $queue), $exception->getTrace());
            }
        }

        return null;
    }

    private function getMessageForError(Throwable $exception, string $errorClass): ?string
    {
        if ($this->config['debug_mode']) {
            return $exception->getMessage();
        }

        foreach ($this->config['replace_messages'] as $replaceMessage) {
            if ($errorClass === $replaceMessage['errorClass']) {
                return $replaceMessage['message'];
            }
        }

        return null;
    }


    private function isExcludeException(Throwable $exception): bool
    {
        foreach ($this->config['exclude_exceptions'] as $exceptionClass) {
            if ($exception instanceof $exceptionClass) {
                return true;
            }
        }

        return false;
    }
}
