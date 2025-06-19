<?php

declare(strict_types=1);

namespace App\UI\Http\Exception;

use Exception;

class ApiException extends Exception implements ApiExceptionInterface
{
    /**
     * @var array[]
     */
    private array $result;

    public function __construct(
        int $code,
        string $message,
        ?array $fields = null
    ) {
        // Если это не модель BaseDtoRequest, то проверяем на наличие сообщения об ошибки
        // Если его нет, то присваиваем сообщение из обязательной переменной
        if ($fields) {
            foreach ($fields as $field => $msg) {
                if ($msg === '') {
                    $fields[$field] = $message;
                }
            }
        }

        $this->result = [
            'error' => [
                'code' => $code,
                'message' => $message,
                'fields' => $fields, // Имена полей с сообщениями об ошибках из моделей BaseDtoRequest после валидации.
            ],
        ];

        parent::__construct($message, $code);
    }

    /**
     * @return array[]
     */
    public function getResponse(): array
    {
        return $this->result;
    }
}
