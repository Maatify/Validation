<?php

declare(strict_types=1);

namespace Tests\Rules;

use Maatify\Validation\Rules\RequiredStringRule;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

#[CoversClass(ValidationException::class)]
#[CoversClass(RequiredStringRule::class)]
final class RequiredStringRuleTest extends TestCase
{
    public function testValidStringPasses(): void
    {
        $this->expectNotToPerformAssertions();
        RequiredStringRule::rule(3, 10)->assert('valid');
    }

    public function testTooShortStringFails(): void
    {
        $this->expectException(ValidationException::class);
        RequiredStringRule::rule(3, 10)->assert('ab');
    }
}
