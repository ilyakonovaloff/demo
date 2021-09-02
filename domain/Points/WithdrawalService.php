<?php

namespace Domain\Points;

use App\Exceptions\BalanceBlockedException;
use App\Exceptions\RequestErrorException;
use Domain\Transactions\SendTransactionActionFactory;
use Domain\Transactions\TransactionRequestResultDTO;
use Exception;
use Throwable;

class WithdrawalService implements WithdrawalServiceInterface
{
    /**
     * @var PointsLocker
     */
    private $pointsLocker;
    /**
     * @var SendTransactionActionFactory
     */
    private $sendTransactionActionFactory;

    /**
     * @param PointsLocker $pointsLocker
     * @param SendTransactionActionFactory $sendTransactionActionFactory
     */
    public function __construct(
        PointsLocker                 $pointsLocker,
        SendTransactionActionFactory $sendTransactionActionFactory
    )
    {
        $this->pointsLocker = $pointsLocker;
        $this->sendTransactionActionFactory = $sendTransactionActionFactory;
    }

    public function execute(WithdrawalDTO $withdrawalDTO): TransactionRequestResultDTO
    {
        try {
            $userId = $withdrawalDTO->getUserId();

//          Checks that user's balance isn't locked by admin or other conditions
//          and locks balance for transaction request
            $this->pointsLocker->setUserId($userId);
            $this->pointsLocker->checkBlockedBalance();
            $this->pointsLocker->blockBalance();

//          Creates an action based on conditions and send transaction request to payment vendors
            $sendTransactionAction = $this->sendTransactionActionFactory->getAction($withdrawalDTO->getIsOrganisation());
            $transactionRequestResultDTO = $sendTransactionAction->sendRequest($withdrawalDTO);

        } catch (BalanceBlockedException | RequestErrorException | Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
//          Unblocks user's balance in any case
            $this->pointsLocker->unblockBalance();
        }

        return $transactionRequestResultDTO;
    }
}