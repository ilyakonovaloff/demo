<?php

namespace Domain\Points;

use Domain\Transactions\TransactionRequestResultDTO;

interface WithdrawalServiceInterface
{
    public function execute(WithdrawalDTO $withdrawalDTO): TransactionRequestResultDTO;
}