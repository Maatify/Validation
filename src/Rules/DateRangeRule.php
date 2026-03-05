<?php

/**
 * @copyright   ©2026 Maatify.dev
 * @Library     maatify/validation
 * @Project     maatify:validation
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2026-01-10 02:00
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/maatify/validation view Project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Validation\Rules;

use Respect\Validation\Validatable;
use Respect\Validation\Validator as v;

final class DateRangeRule
{
    public static function rule(): Validatable
    {
        return v::optional(
            v::arrayType()->keySet(
                v::key('from', v::date('Y-m-d'), false),
                v::key('to', v::date('Y-m-d'), false)
            )
        );
    }
}
