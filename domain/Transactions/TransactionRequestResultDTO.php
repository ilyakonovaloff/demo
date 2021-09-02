<?php

namespace Domain\Transactions;

class TransactionRequestResultDTO
{
    private $someResult;

    /**
     * @param $someResult
     */
    public function __construct($someResult)
    {
        $this->someResult = $someResult;
    }

    /**
     * @return mixed
     */
    public function getSomeResult()
    {
        return $this->someResult;
    }
}