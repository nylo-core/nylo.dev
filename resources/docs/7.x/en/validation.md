# Validation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to validation")
- Basics
  - [Validating Data with check()](#validating-data "Validating data with check")
  - [Validation Results](#validation-results "Validation results")
- FormValidator
  - [Using FormValidator](#using-form-validator "Using FormValidator")
  - [FormValidator Named Constructors](#form-validator-named-constructors "FormValidator named constructors")
  - [Chaining Validation Rules](#chaining-validation-rules "Chaining validation rules")
  - [Custom Validation](#custom-validation "Custom validation")
  - [Using FormValidator with Fields](#form-validator-with-fields "Using FormValidator with Fields")
- [Available Validation Rules](#validation-rules "Validation rules")
- [Creating Custom Validation Rules](#creating-custom-validation-rules "Creating custom validation rules")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a validation system built around the `FormValidator` class. You can validate data inside pages using the `check()` method, or use `FormValidator` directly for standalone and field-level validation.

``` dart
// Validate data in a NyPage/NyState using check()
check((validate) {
  validate.that('user@example.com').email();
  validate.that('Anthony')
              .capitalized()
              .maxLength(50);
}, onSuccess: () {
  print("All validations passed!");
});

// FormValidator with form fields
Field.text("Email", validator: FormValidator.email())
```

<div id="validating-data"></div>

## Validating Data with check()

Inside any `NyPage`, use the `check()` method to validate data. It accepts a callback that receives a list of validators. Use `.that()` to add data and chain rules:

``` dart
class _RegisterPageState extends NyPage<RegisterPage> {

  void handleForm() {
    check((validate) {
      validate.that(_emailController.text, label: "Email").email();
      validate.that(_passwordController.text, label: "Password")
          .notEmpty()
          .password(strength: 2);
    }, onSuccess: () {
      // All validations passed
      _submitForm();
    }, onValidationError: (FormValidationResponseBag bag) {
      // Validation failed
      print(bag.firstErrorMessage);
    });
  }
}
```

### How check() Works

1. `check()` creates an empty `List<FormValidator>`
2. Your callback uses `.that(data)` to add a new `FormValidator` with data to the list
3. Each `.that()` returns a `FormValidator` you can chain rules onto
4. After the callback, every validator in the list is checked
5. Results are collected into a `FormValidationResponseBag`

### Validating Multiple Fields

``` dart
check((validate) {
  validate.that(_nameController.text, label: "Name").notEmpty().capitalized();
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_phoneController.text, label: "Phone").phoneNumberUs();
  validate.that(_ageController.text, label: "Age").numeric().minValue(18);
}, onSuccess: () {
  _submitForm();
});
```

The optional `label` parameter sets a human-readable name used in error messages (e.g., "The Email must be a valid email address.").

<div id="validation-results"></div>

## Validation Results

The `check()` method returns a `FormValidationResponseBag` (a `List<FormValidationResult>`), which you can also inspect directly:

``` dart
FormValidationResponseBag bag = check((validate) {
  validate.that(_emailController.text, label: "Email").email();
  validate.that(_passwordController.text, label: "Password")
      .password(strength: 1);
});

if (bag.isValid) {
  print("All valid!");
} else {
  // Get the first error message
  String? error = bag.firstErrorMessage;
  print(error);

  // Get all failed results
  List<FormValidationResult> errors = bag.validationErrors;

  // Get all successful results
  List<FormValidationResult> successes = bag.validationSuccess;
}
```

### FormValidationResult

Each `FormValidationResult` represents the result of validating a single value:

``` dart
FormValidator validator = FormValidator(data: "test@email.com")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();

if (result.isValid) {
  print("Valid!");
} else {
  // First error message
  String? message = result.getFirstErrorMessage();

  // All error messages
  List<String> messages = result.errorMessages();

  // Error responses
  List<FormValidationError> errors = result.errorResponses;
}
```

<div id="using-form-validator"></div>

## Using FormValidator

`FormValidator` can be used standalone or with form fields.

### Standalone Usage

``` dart
// Using a named constructor
FormValidator validator = FormValidator.email();
FormValidationResult result = validator.check("user@example.com");

if (result.isValid) {
  print("Email is valid");
} else {
  String? errorMessage = result.getFirstErrorMessage();
  print("Error: $errorMessage");
}
```

### With Data in Constructor

``` dart
FormValidator validator = FormValidator(data: "user@example.com", attribute: "Email")
    .email()
    .maxLength(50);

FormValidationResult result = validator.check();
print(result.isValid); // true
```

<div id="form-validator-named-constructors"></div>

## FormValidator Named Constructors

{{ config('app.name') }} v7 provides named constructors for common validations:

``` dart
// Email validation
FormValidator.email(message: "Please enter a valid email")

// Password validation (strength 1 or 2)
FormValidator.password(strength: 1)
FormValidator.password(strength: 2, message: "Password too weak")

// Phone numbers
FormValidator.phoneNumberUk()
FormValidator.phoneNumberUs()

// URL validation
FormValidator.url()

// Length constraints
FormValidator.minLength(5, message: "Too short")
FormValidator.maxLength(100, message: "Too long")

// Value constraints
FormValidator.minValue(18, message: "Must be 18+")
FormValidator.maxValue(100)

// Size constraints (for lists/strings)
FormValidator.minSize(2, message: "Select at least 2")
FormValidator.maxSize(5)

// Not empty
FormValidator.notEmpty(message: "This field is required")

// Contains values
FormValidator.contains(['option1', 'option2'])

// Starts/ends with
FormValidator.beginsWith("https://")
FormValidator.endsWith(".com")

// Boolean checks
FormValidator.booleanTrue(message: "Must accept terms")
FormValidator.booleanFalse()

// Numeric
FormValidator.numeric()

// Date validations
FormValidator.date()
FormValidator.dateInPast()
FormValidator.dateInFuture()
FormValidator.dateAgeIsOlder(18, message: "Must be 18+")
FormValidator.dateAgeIsYounger(65)

// Text case
FormValidator.uppercase()
FormValidator.lowercase()
FormValidator.capitalized()

// Location formats
FormValidator.zipcodeUs()
FormValidator.postcodeUk()

// Regex pattern
FormValidator.regex(r'^[A-Z]{3}\d{4}$', message: "Invalid format")

// Custom validation
FormValidator.custom(
  message: "Invalid value",
  validate: (data) => data != null,
)
```

<div id="chaining-validation-rules"></div>

## Chaining Validation Rules

Chain multiple rules fluently using instance methods. Each method returns the `FormValidator`, allowing you to build up rules:

``` dart
FormValidator validator = FormValidator()
    .notEmpty()
    .email()
    .maxLength(50);

FormValidationResult result = validator.check("user@example.com");

if (!result.isValid) {
  List<String> errors = result.errorMessages();
  print(errors);
}
```

All named constructors have corresponding chainable instance methods:

``` dart
FormValidator()
    .notEmpty(message: "Required")
    .email(message: "Invalid email")
    .minLength(5, message: "Too short")
    .maxLength(100, message: "Too long")
    .beginsWith("user", message: "Must start with 'user'")
    .lowercase(message: "Must be lowercase")
```

<div id="custom-validation"></div>

## Custom Validation

### Custom Rule (Inline)

Use `.custom()` to add inline validation logic:

``` dart
FormValidator.custom(
  message: "Username already taken",
  validate: (data) {
    // Return true if valid, false if invalid
    return !_takenUsernames.contains(data);
  },
)
```

Or chain it with other rules:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```

### Equals Validation

Check if a value matches another:

``` dart
FormValidator()
    .notEmpty()
    .equals(
      _passwordController.text,
      message: "Passwords must match",
    )
```

<div id="form-validator-with-fields"></div>

## Using FormValidator with Fields

`FormValidator` integrates with `Field` widgets in forms. Pass a validator to the `validator` parameter:

``` dart
class RegisterForm extends NyFormData {
  RegisterForm({String? name}) : super(name ?? "register");

  @override
  fields() => [
        Field.text(
          "Name",
          autofocus: true,
          validator: FormValidator.notEmpty(),
        ),
        Field.email("Email", validator: FormValidator.email()),
        Field.password(
          "Password",
          validator: FormValidator.password(strength: 1),
        ),
      ];
}
```

You can also use chained validators with fields:

``` dart
Field.text(
  "Username",
  validator: FormValidator()
      .notEmpty(message: "Username is required")
      .minLength(3, message: "At least 3 characters")
      .maxLength(20, message: "At most 20 characters"),
)

Field.slider(
  "Rating",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
)
```

<div id="validation-rules"></div>

## Available Validation Rules

All available rules for `FormValidator`, as both named constructors and chainable methods:

| Rule | Named Constructor | Chainable Method | Description |
|------|-------------------|------------------|-------------|
| Email | `FormValidator.email()` | `.email()` | Validates email format |
| Password | `FormValidator.password(strength: 1)` | `.password(strength: 1)` | Strength 1: 8+ chars, 1 uppercase, 1 digit. Strength 2: + 1 special char |
| Not Empty | `FormValidator.notEmpty()` | `.notEmpty()` | Cannot be empty |
| Min Length | `FormValidator.minLength(5)` | `.minLength(5)` | Minimum string length |
| Max Length | `FormValidator.maxLength(100)` | `.maxLength(100)` | Maximum string length |
| Min Value | `FormValidator.minValue(18)` | `.minValue(18)` | Minimum numeric value (also works on string length, list length, map length) |
| Max Value | `FormValidator.maxValue(100)` | `.maxValue(100)` | Maximum numeric value |
| Min Size | `FormValidator.minSize(2)` | `.minSize(2)` | Minimum size for lists/strings |
| Max Size | `FormValidator.maxSize(5)` | `.maxSize(5)` | Maximum size for lists/strings |
| Contains | `FormValidator.contains(['a', 'b'])` | `.contains(['a', 'b'])` | Value must contain one of the given values |
| Begins With | `FormValidator.beginsWith("https://")` | `.beginsWith("https://")` | String must start with prefix |
| Ends With | `FormValidator.endsWith(".com")` | `.endsWith(".com")` | String must end with suffix |
| URL | `FormValidator.url()` | `.url()` | Validates URL format |
| Numeric | `FormValidator.numeric()` | `.numeric()` | Must be a number |
| Boolean True | `FormValidator.booleanTrue()` | `.booleanTrue()` | Must be `true` |
| Boolean False | `FormValidator.booleanFalse()` | `.booleanFalse()` | Must be `false` |
| Date | `FormValidator.date()` | `.date()` | Must be a valid date |
| Date In Past | `FormValidator.dateInPast()` | `.dateInPast()` | Date must be in the past |
| Date In Future | `FormValidator.dateInFuture()` | `.dateInFuture()` | Date must be in the future |
| Age Is Older | `FormValidator.dateAgeIsOlder(18)` | `.dateAgeIsOlder(18)` | Age must be older than N |
| Age Is Younger | `FormValidator.dateAgeIsYounger(65)` | `.dateAgeIsYounger(65)` | Age must be younger than N |
| Capitalized | `FormValidator.capitalized()` | `.capitalized()` | First letter must be uppercase |
| Lowercase | `FormValidator.lowercase()` | `.lowercase()` | All characters must be lowercase |
| Uppercase | `FormValidator.uppercase()` | `.uppercase()` | All characters must be uppercase |
| Phone US | `FormValidator.phoneNumberUs()` | `.phoneNumberUs()` | US phone number format |
| Phone UK | `FormValidator.phoneNumberUk()` | `.phoneNumberUk()` | UK phone number format |
| Zipcode US | `FormValidator.zipcodeUs()` | `.zipcodeUs()` | US zipcode format |
| Postcode UK | `FormValidator.postcodeUk()` | `.postcodeUk()` | UK postcode format |
| Regex | `FormValidator.regex(r'pattern')` | `.regex(r'pattern')` | Must match regex pattern |
| Equals | — | `.equals(otherValue)` | Must equal another value |
| Custom | `FormValidator.custom(validate: fn)` | `.custom(validate: fn)` | Custom validation function |

All rules accept an optional `message` parameter to customize the error message.

<div id="creating-custom-validation-rules"></div>

## Creating Custom Validation Rules

To create a reusable validation rule, extend the `FormRule` class:

``` dart
class FormRuleUsername extends FormRule {
  @override
  String? rule = "username";

  @override
  String? message = "The @{{attribute}} must be a valid username.";

  FormRuleUsername({String? message}) {
    if (message != null) {
      this.message = message;
    }
  }

  @override
  bool validate(data) {
    if (data is! String) return false;
    // Username: alphanumeric, underscores, 3-20 chars
    return RegExp(r'^[a-zA-Z0-9_]{3,20}$').hasMatch(data);
  }
}
```

Use `@{{attribute}}` as a placeholder in the `message` — it will be replaced with the field's label at runtime.

### Using a Custom FormRule

Add your custom rule to a `FormValidator` using `FormValidator.rule()`:

``` dart
FormValidator validator = FormValidator.rule([
  FormRuleNotEmpty(),
  FormRuleUsername(),
]);

FormValidationResult result = validator.check("my_username");
```

Or use the `.custom()` method for one-off rules without creating a class:

``` dart
FormValidator()
    .notEmpty()
    .custom(
      message: "Username must start with a letter",
      validate: (data) => RegExp(r'^[a-zA-Z]').hasMatch(data.toString()),
    )
```
