<?php

declare(strict_types=1);

namespace Tests\DTO;

use Maatify\Validation\DTO\ApiErrorResponseDTO;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ApiErrorResponseDTO::class)]
final class ApiErrorResponseDTOTest extends TestCase
{
    public function testToArray(): void
    {
        $dto = new ApiErrorResponseDTO(
            status: 400,
            code  : 'INPUT_INVALID',
            errors: [
                'email' => ['invalid_email'],
            ]
        );

        self::assertSame(400, $dto->getStatus());
        self::assertSame([
            'code'   => 'INPUT_INVALID',
            'errors' => [
                'email' => ['invalid_email'],
            ],
        ], $dto->toArray());
    }
}
