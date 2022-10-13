### Пакет позволяет отдавать ошибки в json формате

### Установка
```
composer require "ivankotut/exception-json-response"
```
#### Настройки
Для более детальной конфигурации создайте файл настроек exception_json_response.yaml
и укажите нужные параметры

#### Пример конфигурации
```yaml
exception_json_response:
  # вывод ошибки и стека (по умолчанию false)
  debug_mode: false

  # Content-Type === application/json (по умолчанию false)
  enable_only_application_json: false

  # Обрабатывать все исключения кроме исключенных (по умолчанию true)
  listen_all_exception: true

  # Дает возможность заменить текст ошибки
  replace_messages:
    - { errorClass: IvankoTut\ExceptionJsonResponse\Response\NotFoundError, message: 'Доступ запрещен'}

  # Исключения, которые будут игнорироваться
  exclude_exceptions:
    - App\Exception\FormValidationException
```

Примеры ответов:

С режимом `debug_mode:true`
```json
{
  "type":"NotFoundError",
  "message":"No route found for \"GET http:\/\/localhost\/api\/test\"",
  "trace":[
    {
      "file":"\/var\/www\/vendor\/symfony\/event-dispatcher\/Debug\/WrappedListener.php",
      "line":115,
      "function":"onKernelRequest",
      "class":"Symfony\\Component\\HttpKernel\\EventListener\\RouterListener",
      "type":"->",
      "args":[
        [],
        "kernel.request",
        []
      ]
    },
    {"ОСТАЛЬНОЙ ТРЕЙС ОШИБКИ": ""}
  ]
}
```

С режимом `debug_mode:false`
```json
{
  "type":"NotFoundError",
  "message":"Запрошенные данные не найдены"
}
```