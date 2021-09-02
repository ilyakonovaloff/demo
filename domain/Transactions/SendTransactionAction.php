<?php

namespace Domain\Transactions;

use Domain\Points\WithdrawalDTO;

/**
 * Class for send a request to vendors and save transaction results for Operators
 */
class SendTransactionAction implements SendTransactionActionInterface
{

    public function sendRequest(WithdrawalDTO $withdrawalDTO): TransactionRequestResultDTO
    {
        // Implementation sendRequest() method.
        return new TransactionRequestResultDTO('Vendor response');
    }
}
