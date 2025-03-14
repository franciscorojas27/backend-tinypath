<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ShortLinkException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message, $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'error' => $this->message
        ], $this->code);
    }
}
