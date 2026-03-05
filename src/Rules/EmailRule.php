<?php

/**
 * @copyright   ©2026 Maatify.dev
 * @Library     maatify/validation
 * @Project     maatify:validation
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2026-01-09 01:29
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/maatify/validation view Project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Validation\Rules;

use Respect\Validation\Validatable;
use Respect\Validation\Validator as v;

final class EmailRule
{
    /**
     * @return Validatable
     */
    public static function rule(): Validatable
    {
        return v::email()->length(1, 255);
    }
}
