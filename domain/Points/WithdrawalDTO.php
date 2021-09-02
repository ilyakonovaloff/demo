<?php

namespace Domain\Points;

use App\Models\Eloquent\User;
use Domain\Users\Role;

class WithdrawalDTO
{
    private $amount;
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $programTypeId;
    /**
     * @var int
     */
    private $transactionType;
    /**
     * @var bool
     */
    private $isOrganisation;

    public function __construct(int $amount, int $transactionType, User $user)
    {
        $this->amount = $amount;
        $this->userId = $user->id;
        $this->programTypeId = $user->program_type_id;
        $this->isOrganisation = $user->hasRole(Role::ROLE_NAME_CURATOR);
        $this->transactionType = $transactionType;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProgramTypeId(): int
    {
        return $this->programTypeId;
    }

    public function getTransactionType(): int
    {
        return $this->transactionType;
    }

    public function getIsOrganisation(): bool
    {
        return $this->isOrganisation;
    }
}