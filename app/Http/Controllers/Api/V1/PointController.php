<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\NotValidTransactionTypeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Point\CashRequest;
use Auth;
use Domain\Points\WithdrawalDTO;
use Domain\Points\WithdrawalServiceInterface;
use Domain\Transactions\TransactionType;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PointController extends Controller
{
    /**
     * Detects type withdrawal and sends request
     * @param string $withdrawalType
     * @param CashRequest $cashRequest
     * @param WithdrawalServiceInterface $withdrawalService
     * @return JsonResponse
     */
    public function withdrawal(
        string            $withdrawalType,
        CashRequest       $cashRequest,
        WithdrawalServiceInterface $withdrawalService
    ): JsonResponse
    {
        try {
            if (!TransactionType::isValidType($withdrawalType)) {
                throw new NotValidTransactionTypeException(trans('messages.api.not_found'), Response::HTTP_NOT_FOUND);
            }
        } catch (NotValidTransactionTypeException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        try {
//          In PHP 8 we can use named arguments for DTO's construction, it will be more useful
            $withdrawalDTO = new WithdrawalDTO(
                $cashRequest->get('amount'),
                TransactionType::TYPE_CASH,
                Auth::user()
            );

            // Bonded in AppServiceProvider with proxy Domain/Proxies/WithdrawalServiceLogger
            $transactionRequestResultDTO = $withdrawalService->execute($withdrawalDTO);

//          Here we use some events tracking
//          try {
//              app('tracker')->trackEvent('user-withdrawal:cash', [...]);
//          } catch (\Exception $e) {
//              \Log::error(trans('messages.tracker.error'), $e->getTrace());
//          }

            return response()->json($transactionRequestResultDTO, Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    /*
        Other actions with points
    */
}
