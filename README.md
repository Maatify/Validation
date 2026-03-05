# Validation Module

[![Latest Version](https://img.shields.io/packagist/v/maatify/validation.svg?style=for-the-badge)](https://packagist.org/packages/maatify/validation)
[![PHP Version](https://img.shields.io/packagist/php-v/maatify/validation.svg?style=for-the-badge)](https://packagist.org/packages/maatify/validation)
[![License](https://img.shields.io/packagist/l/maatify/validation.svg?style=for-the-badge)](LICENSE)

![PHPStan](https://img.shields.io/badge/PHPStan-Level%20Max-4E8CAE)

[![Changelog](https://img.shields.io/badge/Changelog-View-blue)](CHANGELOG.md)
[![Security](https://img.shields.io/badge/Security-Policy-important)](SECURITY.md)

![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/validation?label=Monthly%20Downloads&color=00A8E8)
![Total Downloads](https://img.shields.io/packagist/dt/maatify/validation?label=Total%20Downloads&color=2AA9E0)


A **type-safe, framework-agnostic input validation module** built on top of  
**Respect/Validation**, designed for clean architecture, strict static analysis,
and future extraction as a standalone library.

This module is used inside the Admin Control Panel but is **not coupled** to:
- Authentication
- Authorization (Guards)
- Domain Logic
- HTTP Frameworks (Slim, PSR-7)
- UI / Templates

---

## 🎯 Goals

- Centralize **input validation** in a clean, reusable layer
- Eliminate duplicated validation logic in controllers
- Enforce **type-safety** using DTOs and Enums
- Pass **PHPStan level max** with zero suppressions
- Prepare the module for future extraction as a standalone package

---

## 🧱 Architectural Principles

### 1. Validation is a Cross-Cutting Concern
Validation:
- Touches Controllers and Requests
- Does **not** belong to Domain, Auth, or Guards
- Produces **no side effects** (no audit, no security events)

### 2. Validation ≠ Authorization
- Validation checks **data correctness**
- Authorization checks **permissions**
- They are strictly separated

### 3. No Strings, No Magic
- All error codes are **Enums**
- All responses are **DTOs**
- No hard-coded strings in schemas

---

## 📁 Directory Structure

```

src/
├── Contracts/
│   ├── SchemaInterface.php
│   ├── ValidatorInterface.php
│   ├── ErrorMapperInterface.php
│   └── SystemErrorMapperInterface.php
│
├── DTO/
│   ├── ValidationResultDTO.php
│   └── ApiErrorResponseDTO.php
│
├── Enum/
│   ├── ValidationErrorCodeEnum.php
│   ├── AuthErrorCodeEnum.php
│   └── HttpStatusCodeEnum.php
│
├── ErrorMapper/
│   ├── ApiErrorMapper.php
│   └── SystemApiErrorMapper.php
│
├── Rules/
│   ├── EmailRule.php
│   ├── PasswordRule.php
│   └── RequiredStringRule.php
│
├── Schemas/
│   ├── AbstractSchema.php
│   ├── AuthLoginSchema.php
│   └── AdminCreateSchema.php
│
└── Validator/
└── RespectValidator.php

````

---

## 📦 Dependency

This module relies on:

```bash
composer require respect/validation
````

No other external dependencies are required.

---

## 🧩 Core Concepts

### 1️⃣ Rules

Rules are **pure validation units** built on Respect/Validation.

* One rule = one responsibility
* No HTTP, no DTOs, no Domain logic
* Return `Validatable` via docblocks for PHPStan compatibility

Example:

```php
EmailRule::rule()
```

---

### 2️⃣ Schemas

Schemas describe **request-level validation**.

* One schema per endpoint / use-case
* Declarative rules
* No try/catch duplication
* All schemas extend `AbstractSchema`

Example:

```php
final class AuthLoginSchema extends AbstractSchema
{
    protected function rules(): array
    {
        return [
            'email' => [v::email(), ValidationErrorCodeEnum::INVALID_EMAIL],
            'password' => [CredentialInputRule::rule(), ValidationErrorCodeEnum::INVALID_PASSWORD],
        ];
    }
}
```

---

### 3️⃣ ValidationResultDTO

Schemas always return a `ValidationResultDTO`:

* `isValid(): bool`
* `getErrors(): array<string, list<ValidationErrorCodeEnum>>`

No exceptions are thrown for invalid input.

---

### 4️⃣ Error Mapping

Errors are mapped **once** at the system boundary.

* Validation → `ValidationErrorCodeEnum`
* Auth / Guards → `AuthErrorCodeEnum`
* Transport → `HttpStatusCodeEnum`

All errors are converted into a single response shape via:

```php
SystemApiErrorMapper
```

---

### 5️⃣ ApiErrorResponseDTO

All API error responses are represented as a DTO:

```php
ApiErrorResponseDTO
```

* Contains HTTP status
* Contains error code
* Contains structured field errors
* No arrays returned directly from mappers

---

## 🌐 Typical Flow (API)

1. Controller receives input
2. Schema validates input
3. `ValidationResultDTO` is returned
4. If invalid:

    * Errors mapped via `SystemApiErrorMapper`
    * Controller sends HTTP response
5. If valid:

    * Controller proceeds to Service layer

---

## ❌ What This Module Does NOT Do

* ❌ No authentication logic
* ❌ No authorization checks
* ❌ No audit logging
* ❌ No database access
* ❌ No localization (i18n)
* ❌ No HTTP framework coupling

---

## 🧪 Static Analysis

* Designed to pass **PHPStan level max**
* No suppressions
* No dynamic magic exposed to type system
* Respect/Validation handled via docblocks where needed

---

## 🔮 Future Extensions (Planned)

* Localization mapping (Enum → i18n keys)
* Composite schemas
* Context-aware validation (create vs update)
* Standalone package extraction (`maatify/validation`)
* Shared SuccessResponseDTO for APIs

---

## 🧠 Architectural Decision (LOCKED)

> **All input validation must be expressed as Schemas
> using Rules + Enums, and mapped through a single system-level ErrorMapper.
> No strings, no duplication, no side effects.**

---

## ✅ Status

* Architecture: **LOCKED**
* Implementation: **STABLE**
* PHPStan: **PASS (level max)**
* Ready for reuse and extraction

---

## 🪪 License

This library is licensed under the **MIT License**.
See the [LICENSE](LICENSE) file for details.

---

## 👤 Author

Engineered by **Mohamed Abdulalim** ([@megyptm](https://github.com/megyptm))
Backend Lead & Technical Architect
https://www.maatify.dev

---

## 🤝 Contributors

Special thanks to the Maatify.dev engineering team and all open-source contributors.
Contributions are welcome.

---

<p align="center">
  <sub>Built with ❤️ by <a href="https://www.maatify.dev">Maatify.dev</a> — Unified Ecosystem for Modern PHP Libraries</sub>
</p>