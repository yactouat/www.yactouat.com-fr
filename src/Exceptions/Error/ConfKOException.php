<?php

namespace App\Exceptions\Error;

use App\Constants;
use ErrorException;

/**
 * this class represents the error that is thrown when the app' conf is not OK
 */
final class ConfKOException extends ErrorException
{
    /**
     * constructor
     *
     * retrieves its error message from the app's constants
     */
    public function __construct()
    {
        $this->message = Constants::ERR_EXP_CONFKO;
    }
}
