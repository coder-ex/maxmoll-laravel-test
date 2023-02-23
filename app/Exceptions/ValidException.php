<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;

class ValidException extends Exception
{
    static public function Unvalidate(ValidationException $e, int $code)
    {
        return response()->json(['success' => 'ERROR', $e->validator->messages()->toArray()], $code);
    }
}
