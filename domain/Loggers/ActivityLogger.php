<?php

namespace Domain\Loggers;

use ErrorException;
use Exception;

/**
 * Logs user's activity (login, profile edit, actions with points, etc.)
 */
class ActivityLogger
{
    private $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Пишет лог
     * @param int $userId
     * @param string $data
     * @return void
     * @throws ErrorException
     */
    public function log(int $userId, string $data): void
    {
        try {
            $this->activityRepository->create([
                'user_id' => $userId,
                'data' => $data,
            ]);
        } catch (Exception $e) {
            throw new ErrorException(trans('messages.logger.error'));
        }
    }
}
