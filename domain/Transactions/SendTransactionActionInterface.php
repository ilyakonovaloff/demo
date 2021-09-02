<?php

namespace Domain\Transactions;

use Domain\Points\WithdrawalDTO;

interface SendTransactionActionInterface
{
    public function sendRequest(WithdrawalDTO $withdrawalDTO): TransactionRequestResultDTO;
}
