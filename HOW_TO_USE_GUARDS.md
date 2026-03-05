# HOW_TO_USE — Guards Integration

This guide explains **how Guards should emit errors**  
and **how they integrate with the unified error system**  
without knowing anything about HTTP, JSON, or Validation internals.

---

## 🎯 Purpose

- Standardize how Guards signal access denial
- Keep Guards **pure decision-makers**
- Delegate response shaping to ErrorMapper
- Maintain strict separation of concerns

---

## 🧠 Core Rule (LOCKED)

> **Guards decide *whether* access is allowed.  
> They do NOT decide *how* the error is returned.**

---

## 🧱 Responsibilities Split

| Layer                   | Responsibility                      |
|-------------------------|-------------------------------------|
| Guard                   | Decide allow / deny                 |
| Guard                   | Emit *reason* (Enum)                |
| ErrorMapper             | Decide HTTP status + response shape |
| Controller / Middleware | Send response                       |

---

## 🚫 What Guards Must NOT Do

- ❌ No HTTP status codes
- ❌ No JSON responses
- ❌ No Validation logic
- ❌ No ErrorMapper usage
- ❌ No strings

---

## ✅ What Guards MUST Do

- ✔ Use **AuthErrorCodeEnum**
- ✔ Throw a domain-level exception
- ✔ Express *reason only*

---

## 1️⃣ AuthErrorCodeEnum

All guard-related denial reasons are expressed using:

```php
Maatify\Validation\Enum\AuthErrorCodeEnum
````

Examples:

* `AUTH_REQUIRED`
* `STEP_UP_REQUIRED`
* `NOT_AUTHORIZED`

---

## 2️⃣ Guard Exception Pattern

Guards signal denial by throwing a typed exception.

### 📄 Example Exception

```php
use Maatify\Validation\Enum\AuthErrorCodeEnum;
use RuntimeException;

final class AuthFailedException extends RuntimeException
{
    public function __construct(
        private AuthErrorCodeEnum $errorCode
    ) {
        parent::__construct($errorCode->value);
    }

    public function getErrorCode(): AuthErrorCodeEnum
    {
        return $this->errorCode;
    }
}
```

📌

* Exception carries **Enum**
* No HTTP knowledge
* No response formatting

---

## 3️⃣ Guard Implementation Example

### 📄 Example Guard

```php
use Maatify\Validation\Enum\AuthErrorCodeEnum;

final class AuthorizationGuard
{
    public function assertAllowed(bool $allowed): void
    {
        if (!$allowed) {
            throw new AuthFailedException(
                AuthErrorCodeEnum::NOT_AUTHORIZED
            );
        }
    }
}
```

📌

* Guard only decides
* Emits reason via Enum
* Stops execution

---

## 4️⃣ Handling Guard Errors (Middleware / Controller)

Guards are typically executed inside middleware or controller flow.

### 📄 Example Middleware Handler

```php
use Maatify\Validation\ErrorMapper\SystemApiErrorMapper;

try {
    $guard->assertAllowed($permissionGranted);
} catch (AuthFailedException $e) {
    $errorMapper = new SystemApiErrorMapper();

    $errorResponse = $errorMapper->mapAuthError(
        $e->getErrorCode()
    );

    return $response
        ->withStatus($errorResponse->getStatus())
        ->withJson($errorResponse->toArray());
}
```

📌

* Mapping happens **once**
* Same response format everywhere
* Guards stay framework-agnostic

---

## 5️⃣ HTTP Mapping Rules (LOCKED)

| AuthErrorCodeEnum | HTTP Status |
|-------------------|-------------|
| AUTH_REQUIRED     | 401         |
| STEP_UP_REQUIRED  | 403         |
| NOT_AUTHORIZED    | 403         |

📌
Mapping lives **only** in `SystemApiErrorMapper`.

---

## 6️⃣ Validation vs Guards (Clear Line)

| Concern      | Validation              | Guards             |
|--------------|-------------------------|--------------------|
| Purpose      | Input correctness       | Access control     |
| Error Enum   | ValidationErrorCodeEnum | AuthErrorCodeEnum  |
| HTTP Code    | 400                     | 401 / 403          |
| Location     | Controller entry        | Middleware / Guard |
| Side effects | None                    | None               |

❗ **Never mix the two.**

---

## 7️⃣ Common Anti-Patterns ❌

| Anti-Pattern                 | Why It’s Wrong    |
|------------------------------|-------------------|
| Guard returns JSON           | Breaks separation |
| Guard sets HTTP code         | Transport leak    |
| Guard throws string          | No type-safety    |
| Guard uses ErrorMapper       | Wrong layer       |
| Validation throws Auth error | Conceptual bug    |

---

## 🧪 Testing Guards

When testing Guards:

* Assert exception type
* Assert `AuthErrorCodeEnum`
* Do NOT assert HTTP response

Example:

```php
$this->expectException(AuthFailedException::class);
$this->expectExceptionMessage(
    AuthErrorCodeEnum::NOT_AUTHORIZED->value
);
```

---

## 🔒 Final Rules (LOCKED)

> * Guards emit **AuthErrorCodeEnum only**
> * Guards never format responses
> * Guards never know HTTP
> * ErrorMapper is the single response authority

---

## ✅ Status

* Guard integration pattern: **LOCKED**
* Error semantics: **STABLE**
* Ready for system-wide use

---
