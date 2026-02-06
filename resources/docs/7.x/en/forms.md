# Forms

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to forms")
- Getting Started
  - [Creating a Form](#creating-forms "Creating forms")
  - [Displaying a Form](#displaying-a-form "Displaying a form")
  - [Submitting a Form](#submitting-a-form "Submitting a form")
- Field Types
  - [Text Fields](#text-fields "Text Fields")
  - [Number Fields](#number-fields "Number Fields")
  - [Password Fields](#password-fields "Password Fields")
  - [Email Fields](#email-fields "Email Fields")
  - [URL Fields](#url-fields "URL Fields")
  - [Text Area Fields](#text-area-fields "Text Area Fields")
  - [Phone Number Fields](#phone-number-fields "Phone Number Fields")
  - [Capitalize Words](#capitalize-words-fields "Capitalize Words Fields")
  - [Capitalize Sentences](#capitalize-sentences-fields "Capitalize Sentences Fields")
  - [Date Fields](#date-fields "Date Fields")
  - [DateTime Fields](#datetime-fields "DateTime Fields")
  - [Masked Input Fields](#masked-input-fields "Masked Input Fields")
  - [Currency Fields](#currency-fields "Currency Fields")
  - [Checkbox Fields](#checkbox-fields "Checkbox Fields")
  - [Switch Box Fields](#switch-box-fields "Switch Box Fields")
  - [Picker Fields](#picker-fields "Picker Fields")
  - [Radio Fields](#radio-fields "Radio Fields")
  - [Chip Fields](#chip-fields "Chip Fields")
  - [Slider Fields](#slider-fields "Slider Fields")
  - [Range Slider Fields](#range-slider-fields "Range Slider Fields")
  - [Custom Fields](#custom-fields "Custom Fields")
  - [Widget Fields](#widget-fields "Widget Fields")
- [FormCollection](#form-collection "FormCollection")
- [Form Validation](#form-validation "Form Validation")
- [Managing Form Data](#managing-form-data "Managing Form Data")
  - [Initial Data](#initial-data "Initial Data")
  - [Setting Field Values](#setting-field-values "Setting Field Values")
  - [Setting Field Options](#setting-field-options "Setting Field Options")
  - [Reading Form Data](#reading-form-data "Reading Form Data")
  - [Clearing Data](#clearing-data "Clearing Data")
  - [Updating Fields](#finding-and-updating-fields "Updating Fields")
- [Submit Button](#submit-button "Submit Button")
- [Form Layout](#form-layout "Form Layout")
- [Field Visibility](#field-visibility "Field Visibility")
- [Field Styling](#field-styling "Field Styling")
- [NyFormWidget Static Methods](#ny-form-widget-static-methods "NyFormWidget Static Methods")
- [NyFormWidget Constructor Reference](#ny-form-widget-constructor-reference "NyFormWidget Constructor Reference")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [All Field Types Reference](#all-field-types-reference "All Field Types Reference")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 provides a form system built around `NyFormWidget`. Your form class extends `NyFormWidget` and **is** the widget — no separate wrapper needed. Forms support built-in validation, many field types, styling, and data management.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Creating a Form

Use the Metro CLI to create a new form:

``` bash
metro make:form LoginForm
```

This creates `lib/app/forms/login_form.dart`:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Forms extend `NyFormWidget` and override the `fields()` method to define form fields. Each field uses a named constructor like `Field.text()`, `Field.email()`, or `Field.password()`. The `static NyFormActions get actions` getter provides a convenient way to interact with the form from anywhere in your app.


<div id="displaying-a-form"></div>

## Displaying a Form

Since your form class extends `NyFormWidget`, it **is** the widget. Use it directly in your widget tree:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Submitting a Form

There are three ways to submit a form:

### Using onSubmit and submitButton

Pass `onSubmit` and a `submitButton` when constructing the form. {{ config('app.name') }} provides pre-built buttons that work as submit buttons:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Available button styles: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Using NyFormActions

Use the `actions` getter to submit from anywhere:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### Using NyFormWidget.submit() static method

Submit a form by its name from anywhere:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

When submitted, the form validates all fields. If valid, `onSuccess` is called with a `Map<String, dynamic>` of field data (keys are snake_case versions of field names). If invalid, a toast error is shown by default and `onFailure` is called if provided.


<div id="field-types"></div>

## Field Types

{{ config('app.name') }} v7 provides 22 field types via named constructors on the `Field` class. All field constructors share these common parameters:

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `key` | `String` | Required | The field identifier (positional) |
| `label` | `String?` | `null` | Custom display label (defaults to key in title case) |
| `value` | `dynamic` | `null` | Initial value |
| `validator` | `FormValidator?` | `null` | Validation rules |
| `autofocus` | `bool` | `false` | Auto-focus on load |
| `dummyData` | `String?` | `null` | Test/development data |
| `header` | `Widget?` | `null` | Widget displayed above the field |
| `footer` | `Widget?` | `null` | Widget displayed below the field |
| `titleStyle` | `TextStyle?` | `null` | Custom label text style |
| `hidden` | `bool` | `false` | Hide the field |
| `readOnly` | `bool?` | `null` | Make field read-only |
| `style` | `FieldStyle?` | Varies | Field-specific style configuration |
| `onChanged` | `Function(dynamic)?` | `null` | Value change callback |

<div id="text-fields"></div>

### Text Fields

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Style type: `FieldStyleTextField`

<div id="number-fields"></div>

### Number Fields

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

The `decimal` parameter controls whether decimal input is allowed. Style type: `FieldStyleTextField`

<div id="password-fields"></div>

### Password Fields

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

The `viewable` parameter adds a show/hide toggle. Style type: `FieldStyleTextField`

<div id="email-fields"></div>

### Email Fields

``` dart
Field.email("Email", validator: FormValidator.email())
```

Automatically sets the email keyboard type and filters whitespace. Style type: `FieldStyleTextField`

<div id="url-fields"></div>

### URL Fields

``` dart
Field.url("Website", validator: FormValidator.url())
```

Sets URL keyboard type. Style type: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Text Area Fields

``` dart
Field.textArea("Description")
```

Multi-line text input. Style type: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Phone Number Fields

``` dart
Field.phoneNumber("Mobile Phone")
```

Automatically formats phone number input. Style type: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Capitalize Words

``` dart
Field.capitalizeWords("Full Name")
```

Capitalizes the first letter of each word. Style type: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Capitalize Sentences

``` dart
Field.capitalizeSentences("Bio")
```

Capitalizes the first letter of each sentence. Style type: `FieldStyleTextField`

<div id="date-fields"></div>

### Date Fields

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)
```

Opens a date picker. Style type: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### DateTime Fields

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment", dummyData: "2025-01-01 10:00")
```

Opens a date and time picker. Style type: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Masked Input Fields

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

The `#` character in the mask is replaced by user input. Use `match` to control the allowed characters. When `maskReturnValue` is `true`, the returned value includes the mask formatting.

<div id="currency-fields"></div>

### Currency Fields

``` dart
Field.currency("Price", currency: "usd")
```

The `currency` parameter is required and determines the currency format. Style type: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Checkbox Fields

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Style type: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Switch Box Fields

``` dart
Field.switchBox("Enable Notifications")
```

Style type: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Picker Fields

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

The `options` parameter requires a `FormCollection` (not a raw list). See [FormCollection](#form-collection) for details. Style type: `FieldStylePicker`

<div id="radio-fields"></div>

### Radio Fields

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

The `options` parameter requires a `FormCollection`. Style type: `FieldStyleRadio`

<div id="chip-fields"></div>

### Chip Fields

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Allows multi-selection via chip widgets. The `options` parameter requires a `FormCollection`. Style type: `FieldStyleChip`

<div id="slider-fields"></div>

### Slider Fields

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Style type: `FieldStyleSlider` — configure `min`, `max`, `divisions`, colors, value display, and more.

<div id="range-slider-fields"></div>

### Range Slider Fields

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Returns a `RangeValues` object. Style type: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Custom Fields

Use `Field.custom()` to provide your own stateful widget:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

The `child` parameter requires a widget that extends `NyFieldStatefulWidget`. This gives you full control over the field's rendering and behavior.

<div id="widget-fields"></div>

### Widget Fields

Use `Field.widget()` to embed any widget inside the form without it being a form field:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Widget fields don't participate in validation or data collection. They are purely for layout.


<div id="form-collection"></div>

## FormCollection

Picker, radio, and chip fields require a `FormCollection` for their options. `FormCollection` provides a unified interface for handling different option formats.

### Creating a FormCollection

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` auto-detects the data format and delegates to the appropriate constructor.

### FormOption

Each option in a `FormCollection` is a `FormOption` with `value` and `label` properties:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Querying Options

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Form Validation

Add validation to any field using the `validator` parameter with `FormValidator`:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

When a form is submitted, all validators are checked. If any fail, a toast error shows the first error message and the `onFailure` callback is called.

**See also:** [Validation](/docs/7.x/validation#validation-rules) for a full list of available validators.


<div id="managing-form-data"></div>

## Managing Form Data

<div id="initial-data"></div>

### Initial Data

There are two ways to set initial data on a form.

**Option 1: Override the `init` getter in your form class**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

The `init` getter can return either a synchronous `Map` or an async `Future<Map>`. Keys are matched to field names using snake_case normalization, so `"First Name"` maps to a field with key `"First Name"`.

**Option 2: Pass `initialData` to the form widget**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Setting Field Values

Use `NyFormActions` to set field values from anywhere:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Setting Field Options

Update options on picker, chip, or radio fields dynamically:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Reading Form Data

Form data is accessed through the `onSubmit` callback when the form is submitted, or through the `onChanged` callback for real-time updates:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Clearing Data

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Updating Fields

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Submit Button

Pass a `submitButton` and `onSubmit` callback when constructing the form:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

The `submitButton` is automatically displayed below the form fields. You can use any of the built-in button styles or a custom widget.

You can also use any widget as a submit button by passing it as a `footer`:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Form Layout

Place fields side-by-side by wrapping them in a `List`:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Fields in a `List` are rendered in a `Row` with equal `Expanded` widths. The spacing between fields is controlled by the `crossAxisSpacing` parameter on `NyFormWidget`.


<div id="field-visibility"></div>

## Field Visibility

Show or hide fields programmatically using the `hide()` and `show()` methods on `Field`. You can access fields inside your form class or through the `onChanged` callback:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Hidden fields are not rendered in the UI but still exist in the form's field list.


<div id="field-styling"></div>

## Field Styling

Each field type has a corresponding `FieldStyle` subclass for styling:

| Field Type | Style Class |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Pass a style object to the `style` parameter of any field:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## NyFormWidget Static Methods

`NyFormWidget` provides static methods to interact with forms by name from anywhere in your app:

| Method | Description |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Submit a form by its name |
| `NyFormWidget.stateRefresh(name)` | Refresh the form's UI state |
| `NyFormWidget.stateSetValue(name, key, value)` | Set a field value by form name |
| `NyFormWidget.stateSetOptions(name, key, options)` | Set field options by form name |
| `NyFormWidget.stateClearData(name)` | Clear all fields by form name |
| `NyFormWidget.stateRefreshForm(name)` | Refresh form fields (re-calls `fields()`) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **Tip:** Prefer using `NyFormActions` (see below) instead of calling these static methods directly — it's more concise and less error-prone.


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget Constructor Reference

When extending `NyFormWidget`, these are the constructor parameters you can pass:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

The `onChanged` callback receives the `Field` that changed and its new value:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` provides a convenient way to interact with a form from anywhere in your app. Define it as a static getter on your form class:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Available Actions

| Method | Description |
|--------|-------------|
| `actions.updateField(key, value)` | Set a field's value |
| `actions.clearField(key)` | Clear a specific field |
| `actions.clear()` | Clear all fields |
| `actions.refresh()` | Refresh the form's UI state |
| `actions.refreshForm()` | Re-call `fields()` and rebuild |
| `actions.setOptions(key, options)` | Set options on picker/chip/radio fields |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Submit with validation |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget Overrides

Methods you can override in your `NyFormWidget` subclass:

| Override | Description |
|----------|-------------|
| `fields()` | Define the form fields (required) |
| `init` | Provide initial data (sync or async) |
| `onChange(field, data)` | Handle field changes internally |


<div id="all-field-types-reference"></div>

## All Field Types Reference

| Constructor | Key Parameters | Description |
|-------------|----------------|-------------|
| `Field.text()` | — | Standard text input |
| `Field.email()` | — | Email input with keyboard type |
| `Field.password()` | `viewable` | Password with optional visibility toggle |
| `Field.number()` | `decimal` | Numeric input, optional decimal |
| `Field.currency()` | `currency` (required) | Currency-formatted input |
| `Field.capitalizeWords()` | — | Title case text input |
| `Field.capitalizeSentences()` | — | Sentence case text input |
| `Field.textArea()` | — | Multi-line text input |
| `Field.phoneNumber()` | — | Auto-formatted phone number |
| `Field.url()` | — | URL input with keyboard type |
| `Field.mask()` | `mask` (required), `match`, `maskReturnValue` | Masked text input |
| `Field.date()` | — | Date picker |
| `Field.datetime()` | — | Date and time picker |
| `Field.checkbox()` | — | Boolean checkbox |
| `Field.switchBox()` | — | Boolean toggle switch |
| `Field.picker()` | `options` (required `FormCollection`) | Single selection from list |
| `Field.radio()` | `options` (required `FormCollection`) | Radio button group |
| `Field.chips()` | `options` (required `FormCollection`) | Multi-select chips |
| `Field.slider()` | — | Single value slider |
| `Field.rangeSlider()` | — | Range value slider |
| `Field.custom()` | `child` (required `NyFieldStatefulWidget`) | Custom stateful widget |
| `Field.widget()` | `child` (required `Widget`) | Embed any widget (non-field) |

