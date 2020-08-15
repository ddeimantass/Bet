<?php

declare(strict_types=1);

namespace App\Constant;

class ErrorConstant
{
    public const MIN_STAKE_AMOUNT = 0.3;
    public const MAX_STAKE_AMOUNT = 10000;
    public const MIN_ODDS = 1;
    public const MAX_ODDS = 10000;
    public const MAX_WIN_AMOUNT = 20000;
    public const MIN_SELECTIONS_NUMBER = 1;
    public const MAX_SELECTIONS_NUMBER = 20;
    public const ERRORS = [
        0 => 'Unknown error',
        1 => 'Betslip structure mismatch',
        2 => 'Minimum stake amount is ' . self::MIN_STAKE_AMOUNT,
        3 => 'Maximum stake amount is ' . self::MAX_STAKE_AMOUNT,
        4 => 'Minimum number of selections is ' . self::MIN_SELECTIONS_NUMBER,
        5 => 'Maximum number of selections is ' . self::MAX_SELECTIONS_NUMBER,
        6 => 'Minimum odds are ' . self::MIN_ODDS,
        7 => 'Maximum odds are ' . self::MAX_ODDS,
        8 => 'Duplicate selection found',
        9 => 'Maximum win amount is ' . self::MAX_WIN_AMOUNT,
        10 => 'Your previous action is not finished yet',
        11 => 'Insufficient balance'
    ];

    public static function getErrorByCode(int $code)
    {
        return ['code' => $code, 'message' => self::ERRORS[$code]];
    }
}