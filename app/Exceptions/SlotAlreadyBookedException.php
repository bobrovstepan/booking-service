<?php

namespace App\Exceptions;

use Exception;

class SlotAlreadyBookedException extends Exception
{
    protected $message = 'Этот слот уже занят';
}
