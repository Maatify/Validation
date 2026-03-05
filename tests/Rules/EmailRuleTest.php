<?php

declare(strict_types=1);

namespace Tests\Rules;

use Maatify\Validation\Rules\EmailRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

#[CoversClass(EmailRule::class)]
#[CoversClass(ValidationException::class)]
final class EmailRuleTest extends TestCase
{
    public function testValidEmailPasses(): void
    {
        $this->expectNotToPerformAssertions();
        EmailRule::rule()->assert('test@example.com');
    }

    public function testInvalidEmailFails(): void
    {
        $this->expectException(ValidationException::class);
        EmailRule::rule()->assert('invalid-email');
    }
}
