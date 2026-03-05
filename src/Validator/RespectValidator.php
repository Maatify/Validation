<?php

/**
 * @copyright   ©2026 Maatify.dev
 * @Library     maatify/validation
 * @Project     maatify:validation
 * @author      Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since       2026-01-09 01:26
 * @see         https://www.maatify.dev Maatify.dev
 * @link        https://github.com/maatify/validation view Project on GitHub
 * @note        Distributed in the hope that it will be useful - WITHOUT WARRANTY.
 */

declare(strict_types=1);

namespace Maatify\Validation\Validator;

use Maatify\Validation\Contracts\SchemaInterface;
use Maatify\Validation\Contracts\ValidatorInterface;
use Maatify\Validation\DTO\ValidationResultDTO;

final class RespectValidator implements ValidatorInterface
{
    /**
     * @param   array<string, mixed>  $input
     */
    public function validate(SchemaInterface $schema, array $input): ValidationResultDTO
    {
        return $schema->validate($input);
    }
}
