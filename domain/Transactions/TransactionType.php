<?php

namespace Domain\Transactions;

class TransactionType
{
//  other constants
    const TYPE_CASH = 6;
    const TYPE_QIWI = 24;
    const TYPE_QIWI_PHONE = 25;

    /*
        Other stuff
    */

//    public static function getNames(): array
//    {
//        return [...];
//    }

//    public static function getIncomingTypes(): array
//    {
//        return [...];
//    }

    public static function getWithdrawalTypes(): array
    {
        return [
            self::TYPE_CASH,
            self::TYPE_QIWI,
            self::TYPE_QIWI_PHONE,
        ];
    }

    public static function isValidType(string $textType): bool
    {
        return isset(self::getNames()[$textType]);
    }
}
