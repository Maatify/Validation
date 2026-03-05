# HOW_TO_USE — Validation Module

This guide explains **how to use the Validation module** in controllers and
application flow.

It assumes:
- The Validation module is available under the project namespace `Maatify\Validation`
- `respect/validation` is installed
- PHP 8.2+
- PHPStan level max compatibility is required

---

## 1️⃣ Basic Usage Pattern (API Controller)

### Step 1 — Choose the Schema
Each endpoint must have **one schema** representing its input.

Example:
- Login → `AuthLoginSchema`
- Create Admin → `AdminCreateSchema`

---

### Step 2 — Validate the Input

```php
use Maatify\Validation\Validator\RespectValidator;
use Maatify\Validation\Schemas\AuthLoginSchema;
use Maatify\Validation\ErrorMapper\SystemApiErrorMapper;

/** @var array<string, mixed> $input */
$input = (array) $request->getParsedBody();

$validator = new RespectValidator();
$schema = new AuthLoginSchema();

$result = $validator->validate($schema, $input);
```

📌 Notes:

* Validation **never throws** for invalid input
* All errors are structured and typed
* HTTP status is always `400` for validation errors
  (by design — `422` is reserved for non-validation semantic failures)
* ❌ Validation does **not** perform input sanitization (e.g., `trim`, `normalize`)
  – handle sanitization explicitly in the controller or input factory if needed

---

### Step 3 — Handle Validation Failure

```php
if (!$result->isValid()) {
    $errorMapper = new SystemApiErrorMapper();
    $errorResponse = $errorMapper->mapValidationErrors(
        $result->getErrors()
    );

    return $response
        ->withStatus($errorResponse->getStatus())
        ->withJson($errorResponse->toArray());
}
```

---

### Step 4 — Continue Normal Flow

```php
// Input is valid here
// Call Service / Domain layer safely
```

---

## 2️⃣ Adding a New Schema

### Step 1 — Create Schema Class

All schemas **must extend `AbstractSchema`**.

```php
use Maatify\Validation\Schemas\AbstractSchema;
use Maatify\Validation\Rules\RequiredStringRule;
use Maatify\Validation\Enum\ValidationErrorCodeEnum;

final class ExampleSchema extends AbstractSchema
{
    protected function rules(): array
    {
        return [
            'title' => [
                RequiredStringRule::rule(3, 100),
                ValidationErrorCodeEnum::REQUIRED_FIELD,
            ],
        ];
    }
}
```

📌 Rules format:

```php
'field_name' => [Validatable, ValidationErrorCodeEnum]
```

---

## 3️⃣ Adding a New Rule

Rules are **thin wrappers** around Respect validators.

Example:

```php
use Respect\Validation\Validator as v;
use Respect\Validation\Validatable;

final class SlugRule
{
    /**
     * @return Validatable
     */
    public static function rule()
    {
        return v::stringType()->regex('/^[a-z0-9-]+$/');
    }
}
```

Rules:

* Must not know about Schemas
* Must not throw custom exceptions
* Must return `Validatable` (via docblock)

---

## 4️⃣ Validation Error Codes (Enums)

### Validation Errors

All validation errors use:

```php
ValidationErrorCodeEnum
```

Example:

```php
ValidationErrorCodeEnum::INVALID_EMAIL
```

❌ Never use strings directly.

---

### Auth / Permission Errors

Used by Guards (not Validation):

```php
AuthErrorCodeEnum
```

Example:

```php
AuthErrorCodeEnum::STEP_UP_REQUIRED
```

---

## 5️⃣ Error Mapping (System-Level)

All errors are converted to API responses through:

```php
SystemApiErrorMapper
```

### Validation Mapping

```php
$errorMapper->mapValidationErrors($errors);
```

### Auth Mapping (used in exception handlers)

```php
$errorMapper->mapAuthError(AuthErrorCodeEnum::NOT_AUTHORIZED);
```

---

## 6️⃣ ApiErrorResponseDTO

All error responses are returned as:

```php
ApiErrorResponseDTO
```

### Accessors

```php
$errorResponse->getStatus(); // HTTP status code
$errorResponse->toArray();  // API-safe payload
```

Payload format:

```json
{
  "code": "INPUT_INVALID",
  "errors": {
    "email": ["invalid_email"]
  }
}
```

---

## 7️⃣ What NOT To Do ❌

* ❌ Do not validate inside Domain services
* ❌ Do not throw validation exceptions
* ❌ Do not log validation errors
* ❌ Do not return arrays from ErrorMappers
* ❌ Do not use strings instead of Enums
* ❌ Do not mix validation with authorization

---

## 8️⃣ Common Mistakes

| Mistake                  | Why It’s Wrong                    |
|--------------------------|-----------------------------------|
| Validating in Service    | Breaks separation of concerns     |
| Using strings for errors | Breaks type-safety                |
| try/catch per field      | Duplication (use AbstractSchema)  |
| HTTP logic in Schema     | Schema must be framework-agnostic |

---

## 9️⃣ Static Analysis Notes

* Return types for Respect validators are declared via **docblocks**
* This is intentional for PHPStan compatibility
* Do not add strict return types to Rule methods

---

## 🔒 Final Rule (LOCKED)

> **Every request must be validated using a Schema.
> Every validation error must be expressed as an Enum.
> Every error response must be returned as a DTO.**

---

## ✅ Status

* Usage pattern: **LOCKED**
* API contract: **STABLE**
* PHPStan: **PASS (level max)**

---
