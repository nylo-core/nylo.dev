# InputField

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Validation](#validation "Validation")
- Variants
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Input Masking](#masking "Input Masking")
- [Header and Footer](#header-footer "Header and Footer")
- [Clearable Input](#clearable "Clearable Input")
- [State Management](#state-management "State Management")
- [Parameters](#parameters "Parameters")


<div id="introduction"></div>

## Introduction

The **InputField** widget is {{ config('app.name') }}'s enhanced text field with built-in support for:

- Validation with customizable error messages
- Password visibility toggle
- Input masking (phone numbers, credit cards, etc.)
- Header and footer widgets
- Clearable input
- State management integration
- Dummy data for development

<div id="basic-usage"></div>

## Basic Usage

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Validation

Use the `formValidator` parameter to add validation rules:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

The field will validate when the user moves focus away from it.

### Custom Validation Handler

Handle validation errors programmatically:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

See all available validation rules in the [Validation](/docs/7.x/validation) documentation.

<div id="password"></div>

## InputField.password

A pre-configured password field with obscured text and visibility toggle:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Customizing Password Visibility

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

A pre-configured email field with email keyboard and autofocus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Auto-capitalizes the first letter of each word:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Input Masking

Apply input masks for formatted data like phone numbers or credit cards:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parameter | Description |
|-----------|-------------|
| `mask` | The mask pattern using `#` as placeholder |
| `maskMatch` | Regex pattern for valid input characters |
| `maskedReturnValue` | If true, returns the formatted value; if false, returns raw input |

<div id="header-footer"></div>

## Header and Footer

Add widgets above or below the input field:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Clearable Input

Add a clear button to quickly empty the field:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## State Management

Give your input field a state name to control it programmatically:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### State Actions

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parameters

### Common Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | required | Controls the text being edited |
| `labelText` | `String?` | - | Label displayed above the field |
| `hintText` | `String?` | - | Placeholder text |
| `formValidator` | `FormValidator?` | - | Validation rules |
| `validateOnFocusChange` | `bool` | `true` | Validate when focus changes |
| `obscureText` | `bool` | `false` | Hide input (for passwords) |
| `keyboardType` | `TextInputType` | `text` | Keyboard type |
| `autoFocus` | `bool` | `false` | Auto-focus on build |
| `readOnly` | `bool` | `false` | Make field read-only |
| `enabled` | `bool?` | - | Enable/disable the field |
| `maxLines` | `int?` | `1` | Maximum lines |
| `maxLength` | `int?` | - | Maximum characters |

### Styling Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | Field background color |
| `borderRadius` | `BorderRadius?` | Border radius |
| `border` | `InputBorder?` | Default border |
| `focusedBorder` | `InputBorder?` | Border when focused |
| `enabledBorder` | `InputBorder?` | Border when enabled |
| `contentPadding` | `EdgeInsetsGeometry?` | Internal padding |
| `style` | `TextStyle?` | Text style |
| `labelStyle` | `TextStyle?` | Label text style |
| `hintStyle` | `TextStyle?` | Hint text style |
| `prefixIcon` | `Widget?` | Icon before input |

### Masking Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `mask` | `String?` | Mask pattern (e.g., "###-####") |
| `maskMatch` | `String?` | Regex for valid characters |
| `maskedReturnValue` | `bool?` | Return masked or raw value |

### Feature Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `header` | `Widget?` | Widget above the field |
| `footer` | `Widget?` | Widget below the field |
| `clearable` | `bool?` | Show clear button |
| `clearIcon` | `Widget?` | Custom clear icon |
| `passwordVisible` | `bool?` | Show password toggle |
| `passwordViewable` | `bool?` | Allow password visibility toggle |
| `dummyData` | `String?` | Fake data for development |
| `stateName` | `String?` | Name for state management |
| `onChanged` | `Function(String)?` | Called when value changes |
