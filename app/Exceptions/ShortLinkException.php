<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ShortLinkException extends Exception
{
    protected $message;
    protected $code;

    public function __construct($message, $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render(Request $request): Response
    {
        return $request()->json([
            'error' => $this->message
        ], $this->code);
    }
}