<?php

namespace App\Config;

use Throwable;

class ExceptionHandlerInitilizer
{
    public static function registerGlobalExceptionHandler()
    {
       // Définit un gestionnaire d'exceptions au niveau global
        set_exception_handler(function (Throwable $e) {
        http_response_code(500);
        echo json_encode([
        'error' => 'Une erreur est survenue',
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
        'file' => $e->getMessage(),
        'line'=>$e->getMessage()
    ]);
    });
    }
}