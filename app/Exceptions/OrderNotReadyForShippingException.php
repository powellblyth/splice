<?php

namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * exception for when an order is not ready for shipping
 * Class OrderNotReadyForShippingException
 * @package App\Exceptions
 */
class OrderNotReadyForShippingException extends Exception
{
    private $orderId;

    public function __construct(int $orderId, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->orderId = $orderId;
        parent::__construct($message, $code, $previous);
    }

    public function getOrderId()
    {
        return $this->orderId;
    }
    //
}
