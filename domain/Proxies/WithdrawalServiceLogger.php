<?php

namespace Domain\Proxies;

use Domain\Loggers\ActivityLogger;
use Domain\Points\WithdrawalDTO;
use Domain\Points\WithdrawalService;
use Domain\Points\WithdrawalServiceInterface;
use Domain\Transactions\TransactionRequestResultDTO;
use Exception;
use Throwable;

class WithdrawalServiceLogger implements WithdrawalServiceInterface
{
    /**
     * @var WithdrawalService
     */
    private $withdrawalService;
    /**
     * @var ActivityLogger
     */
    private $activityLogger;

    /**
     * @param WithdrawalService $withdrawalService
     * @param ActivityLogger $activityLogger
     */
    public function __construct(WithdrawalService $withdrawalService, ActivityLogger $activityLogger)
    {

        $this->withdrawalService = $withdrawalService;
        $this->activityLogger = $activityLogger;
    }

    public function execute(WithdrawalDTO $withdrawalDTO): TransactionRequestResultDTO
    {
        try {
            $transactionRequestResultDTO = $this->withdrawalService->execute($withdrawalDTO);
            if ($transactionRequestResultDTO->getIsSuccess()) {
                $requestResult = trans('messages.points.success') . ' ' . $transactionRequestResultDTO->getAmount();
            } else {
                $requestResult = trans('messages.points.reject') . ' ' . $transactionRequestResultDTO->getAmount();
            }
        }  catch (Throwable $e) {
            $requestResult = $e->getMessage() . ' ' . $withdrawalDTO->toJson();
            throw new Exception($e->getMessage(), $e->getCode());
        } finally {
            $this->activityLogger->log($transactionRequestResultDTO->getUserId(), $requestResult);
        }

        return $transactionRequestResultDTO;
    }
}