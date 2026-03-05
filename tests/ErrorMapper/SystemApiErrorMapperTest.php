<?php

declare(strict_types=1);

namespace Tests\ErrorMapper;

use Maatify\Validation\DTO\ApiErrorResponseDTO;
use Maatify\Validation\Enum\AuthErrorCodeEnum;
use Maatify\Validation\Enum\ValidationErrorCodeEnum;
use Maatify\Validation\ErrorMapper\SystemApiErrorMapper;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiErrorResponseDTO::class)]
#[CoversClass(ValidationErrorCodeEnum::class)]
#[CoversClass(SystemApiErrorMapper::class)]
final class SystemApiErrorMapperTest extends TestCase
{
    public function testValidationErrorsMapping(): void
    {
        $mapper = new SystemApiErrorMapper();

        $dto = $mapper->mapValidationErrors([
            'email' => [ValidationErrorCodeEnum::INVALID_EMAIL],
        ]);

        self::assertSame(400, $dto->getStatus());
        self::assertSame([
            'code'   => 'INPUT_INVALID',
            'errors' => [
                'email' => ['invalid_email'],
            ],
        ], $dto->toArray());
    }

    public function testAuthErrorMapping(): void
    {
        $mapper = new SystemApiErrorMapper();

        $dto = $mapper->mapAuthError(
            AuthErrorCodeEnum::NOT_AUTHORIZED
        );

        self::assertSame(403, $dto->getStatus());
        self::assertSame(
            AuthErrorCodeEnum::NOT_AUTHORIZED->value,
            $dto->toArray()['code']
        );
    }
}
