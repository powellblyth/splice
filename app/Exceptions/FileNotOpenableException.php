<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Exceptions;
/**
 * Description of FileNotOpenableException
 *
 * @author toby
 */
class FileNotOpenableException extends \Exception
{

    protected $Errors = [];

    // Redefine the exception so message isn't optional
    public function __construct($message, $errors, $code = 0, Exception $previous = null)
    {
        $this->Errors[] = $errors;
// make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    /**
     * Custom string representation of the exception.
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message} " . implode(", ", $this->Errors) . "\n";
    }

    public function getErrors(): array
    {
        return $this->Errors;
    }
}
