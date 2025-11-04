<?php

namespace core\validators;

use yii\validators\Validator;

class InnValidator extends Validator
{
    public static function isValid(string $value, ?string &$error = null): bool
    {
        $length = mb_strlen($value);

        if ($length !== 10 && $length !== 12) {
            $error = 'Длина строки должна быть 10 или 12 символов';
            return false;
        }

        $chars = mb_str_split($value);
        $numbers = [];

        foreach ($chars as $char) {
            $number = filter_var($char, FILTER_VALIDATE_INT);

            if ($number === false) {
                $error = 'Строка должна содержать только цифры';
                return false;
            }

            $numbers[] = $number;
        }

        $calculate = static function(array $coefficients) use($numbers) {
            $result = 0;

            foreach ($coefficients as $index => $coefficient) {
                $result += $coefficient * $numbers[$index];
            }

            return $result % 11 % 10;
        };

        if ($length === 10 && $calculate([2, 4, 10, 3, 5, 9, 4, 6, 8]) === $numbers[9]) {
            return true;
        }

        if ($length === 12 && $calculate([7, 2, 4, 10, 3, 5, 9, 4, 6, 8]) === $numbers[10] && $calculate([3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]) === $numbers[12]) {
            return true;
        }

        $error = 'Неправильное контрольное число';
        return false;
    }

    public function validateValue($value): ?array
    {
        if (!static::isValid($value, $error)) {
            return [$error];
        }

        return null;
    }
}
