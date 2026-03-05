<?php

declare(strict_types=1);

namespace Tests\Rules;

use Maatify\Validation\Rules\PasswordRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

#[CoversClass(PasswordRule::class)]
#[CoversClass(ValidationException::class)]
final class PasswordRuleTest extends TestCase
{
    public function testValidPasswordPasses(): void
    {
        $this->expectNotToPerformAssertions();
        PasswordRule::rule()->assert('StrongPass1');
    }

    public function testInvalidPasswordFails(): void
    {
        $this->expectException(ValidationException::class);
        PasswordRule::rule()->assert('123');
    }
}
