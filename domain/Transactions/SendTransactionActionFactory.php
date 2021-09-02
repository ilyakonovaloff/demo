<?php

namespace Domain\Transactions;

class SendTransactionActionFactory
{
    /**
     * @param bool $isOrganisation
     * @return SendTransactionActionInterface
     */
    public function getAction(bool $isOrganisation): SendTransactionActionInterface
    {
        if ($isOrganisation) {
            return new SendOrganisationTransactionAction();
        }
        return new SendTransactionAction();
    }
}
