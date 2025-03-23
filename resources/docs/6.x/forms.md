# Forms

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
  - [How it works](#how-it-works "How it works")
- [Quick Start](#quick-start "Quick Start")
- [Creating Forms](#creating-forms "Creating forms")
- [Field Types](#field-types "Field Types")
  - [Text Fields](#text-fields "Text Fields")
  - [Numeric Fields](#numeric-fields "Numeric Fields")
  - [Selection Fields](#selection-fields "Selection Fields")
  - [Boolean Fields](#boolean-fields "Boolean Fields")
  - [Date and Time Fields](#date-and-time-fields "Date and Time Fields")
  - [Password Fields](#password-fields "Password Fields")
  - [Masked Input Fields](#masked-input-fields "Masked Input Fields")
  - [Checkbox Fields](#checkbox-fields "Checkbox Fields")
  - [Picker Fields](#picker-fields "Picker Fields")
  - [Radio Fields](#radio-fields "Radio Fields")
  - [Chip Fields](#chip-fields "Chip Fields")
  - [Switch Box Fields](#switch-box-fields "Switch Box Fields")
- [Form Validation](#form-validation "Form Validation")
- [Form Casts](#form-casts "Form Casts")
- [Managing Form Data](#managing-form-data "Managing Form Data")
  - [Initializing Data](#initializing-data "Initializing Data")
- [Submit Button](#submit-button "Submit Button")
- [Form Styling](#form-styling "Form Styling")
- [Advanced Features](#advanced-features "Advanced Features")
  - [Form Layout](#form-layout "Form Layout")
  - [Conditional Fields](#conditional-fields "Conditional Fields")
  - [Form Events](#form-events "Form Events")
- [Pre-built Components](#pre-built-components "Pre-built Components")
- [API Reference for NyForm](#ny-form-api-reference "API Reference for NyForm")

<a name="introduction"></a>
<br>

## Introduction

Nylo's form system provides:
- Easy form creation and management
- Built-in validation
- Field type casting
- Form state management
- Styling customization
- Data handling utilities


<a name="how-it-works"></a>
<br>

#### Creating a form

First, run the below [metro](/docs/6.x/metro) command from your terminal.
``` bash
dart run nylo_framework:main make:form LoginForm
# or with Metro alias
metro make:form LoginForm
```

This will create a new form class `lib/app/forms/login_form.dart`

**E.g. The newly created LoginForm**
``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormData {

  LoginForm({String? name}) : super(name ?? "login");

  // Add your fields here
  @override
  fields() => [
    Field.email("Email", 
        validator: FormValidator.email()
    ),
    Field.password("Password",
        validator: FormValidator.password()
    ),
  ];
}
```

#### Displaying a form

To display a form, you can use the `NyForm` widget.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/forms/login_form.dart';
import '/resources/widgets/buttons/buttons.dart';
...

// Create your form
LoginForm form = LoginForm();

// add the form using the NyForm widget
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: NyForm(
        form: form, 
        footer: Button.primary(child: "Submit", submitForm: (form, (data) {
              printInfo(data);
          }),
        ),
      ),
    )
  );
}
```

This is all you need to create and display a form in Nylo.

The UI of this page will now contain two fields, `Email` and `Password`, and a submit button.

#### Using the `Button` widget to submit the form

Out the box, Nylo provides 8 pre-built buttons that you can use to submit a form.

Each button has a different style and color.

- `Button.primary`
- `Button.secondary`
- `Button.outlined`
- `Button.textOnly`
- `Button.icon`
- `Button.gradient`
- `Button.rounded`
- `Button.transparency`

 They all have the ability to submit a form using the `submitForm` parameter.

``` dart
Button.primary(text: "Submit", submitForm: (form, (data) {
    printInfo(data);
}));
```



#### Submitting the form via a different widget

To submit a form, you can call the `submit` method on a form.

``` dart
LoginForm form = LoginForm();

@override
Widget view(BuildContext context) {
    return Scaffold(
        body: SafeArea(
          child: NyForm(
            form: form, 
            footer: MaterialButton(
                onPressed: () {
                    form.submit(onSuccess: (data) {
                        // Do something with the data
                    });
                },
                child: Text("Submit"),
            )),
        )
    );
}
```

When you call `form.submit()`, Nylo will validate the form and if the form is valid, it will call the `onSuccess` callback with the form **data**.

That's a quick overview of how to create, display and submit a form in Nylo.

This is just scratching the surface, you can customize your forms even further by adding casts, validation rules, dummy data and global styles.

<a name="creating-forms"></a>
<br>

## Creating Forms

### Using the Metro CLI

The easiest way to create a new form is using the Metro CLI:

```bash
metro make:form LoginForm

# or
dart run nylo_framework:main make:form LoginForm
```

This creates a new form class in `lib/app/forms/login_form.dart`.

### Form Structure

Forms in Nylo extend the `NyFormData` class:

```dart
class ProductForm extends NyFormData {
  ProductForm({String? name}) : super(name ?? "product");

  @override
  fields() => [
    // Define form fields here
    Field.text("Name"),
    Field.number("Price"),
    Field.textarea("Description")
  ];
}
```

<a name="field-types"></a>
<br>

## Field Types

Nylo provides multiple ways to define fields, with the recommended approach using static methods for cleaner syntax:

<a name="text-fields"></a>
<br>

### Text Fields
```dart
// Recommended approach
Field.text("Name"),
Field.textArea("Description"),
Field.email("Email"),
Field.capitalizeWords("Title"),
Field.url("Website"),

// Alternative approach using constructor with casts
Field("Name", cast: FormCast.text()),
Field("Description", cast: FormCast.textArea())
```

<a name="numeric-fields"></a>
<br>

### Numeric Fields
```dart
// Recommended approach
Field.number("Age"),
Field.currency("Price", currency: "usd"),
Field.decimal("Score"),

// Alternative approach
Field("Age", cast: FormCast.number()),
Field("Price", cast: FormCast.currency("usd"))
```
<a name="selection-fields"></a>
<br>

### Selection Fields
```dart
// Recommended approach
Field.picker("Category", options: ["Electronics", "Clothing", "Books"]),
Field.chips("Tags", options: ["Featured", "Sale", "New"]),
Field.radio("Size", options: ["Small", "Medium", "Large"]),

// Alternative approach
Field("Category", 
  cast: FormCast.picker(
    options: ["Electronics", "Clothing", "Books"]
  )
)
```

<a name="boolean-fields"></a>
<br>

### Boolean Fields
```dart
// Recommended approach
Field.checkbox("Accept Terms"),
Field.switchBox("Enable Notifications"),

// Alternative approach
Field("Accept Terms", cast: FormCast.checkbox()),
Field("Enable Notifications", cast: FormCast.switchBox())
```

<a name="date-and-time-fields"></a>
<br>

### Date and Time Fields
```dart
// Recommended approach
Field.date("Birth Date", 
  firstDate: DateTime(1900),
  lastDate: DateTime.now()
),
Field.datetime("Appointment"),

// Alternative approach
Field("Birth Date", 
  cast: FormCast.date(
    firstDate: DateTime(1900),
    lastDate: DateTime.now()
  )
)
```

<a name="password-fields"></a>
<br>

### Password Fields
```dart
// Recommended approach
Field.password("Password", viewable: true)

// Alternative approach
Field("Password", cast: FormCast.password(viewable: true))
```

<a name="masked-input-fields"></a>
<br>

### Masked Input Fields
```dart
// Recommended approach
Field.mask("Phone", mask: "(###) ###-####"),
Field.mask("Credit Card", mask: "#### #### #### ####")

// Alternative approach
Field("Phone", 
  cast: FormCast.mask(mask: "(###) ###-####")
)
```

<a name="checkbox-fields"></a>
<br>

### Checkbox Fields

```dart
// Recommended approach
Field.checkbox("Accept Terms"),

// Alternative approach
Field("Accept Terms", cast: FormCast.checkbox())
```

<a name="picker-fields"></a>
<br>

### Picker Fields

```dart
// Recommended approach
Field.picker("Category", options: ["Electronics", "Clothing", "Books"]),

// Alternative approach
Field("Category", 
  cast: FormCast.picker(
    options: ["Electronics", "Clothing", "Books"]
  )
)
```

<a name="radio-fields"></a>
<br>

### Radio Fields

```dart
// Recommended approach
Field.radio("Size", options: ["Small", "Medium", "Large"]),

// Alternative approach
Field("Size", 
  cast: FormCast.radio(
    options: ["Small", "Medium", "Large"]
  )
)
```

<a name="chip-fields"></a>
<br>

### Chip Fields

```dart
// Recommended approach
Field.chips("Tags", options: ["Featured", "Sale", "New"]),

// Alternative approach
Field("Tags", 
  cast: FormCast.chips(
    options: ["Featured", "Sale", "New"]
  )
)
```

<a name="switch-box-fields"></a>
<br>

### Switch Box Fields

```dart
// Recommended approach
Field.switchBox("Enable Notifications"),

// Alternative approach
Field("Enable Notifications", cast: FormCast.switchBox())
```

<a name="form-validation"></a>
<br>

## Form Validation

Nylo provides extensive validation capabilities:

### Basic Validation
```dart
Field.text("Username",
  validate: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)
```

### Combined Validation
```dart
Field.password("Password",
  validate: FormValidator()
    .notEmpty()
    .minLength(8)
    .password(strength: 2)
)
```

### Custom Validation
```dart
Field.number("Age",
  validate: FormValidator.custom(
    (value) {
      if (value < 18) return false;
      if (value > 100) return false;
      return true;
    },
    message: "Age must be between 18 and 100"
  )
)
```

### Validation Examples

```dart
// Email validation
Field.email("Email", 
  validate: FormValidator.email(
    message: "Please enter a valid email address"
  )
)

// Password validation with strength levels
Field.password("Password",
  validate: FormValidator.password(strength: 2)
)

// Length validation
Field.text("Username", 
  validate: FormValidator()
    .minLength(3)
    .maxLength(20)
)

// Phone number validation
Field.phone("Phone",
  validate: FormValidator.phoneNumberUs()  // US format
  // or
  validate: FormValidator.phoneNumberUk()  // UK format
)

// URL validation
Field.url("Website",
  validate: FormValidator.url()
)

// Contains validation
Field.picker("Category",
  validate: FormValidator.contains(["Tech", "Health", "Sports"])
)

// Numeric validation
Field.number("Age",
  validate: FormValidator()
    .numeric()
    .minValue(18)
    .maxValue(100)
)

// Date validation
Field.date("EventDate",
  validate: FormValidator()
    .date()
    .dateInFuture()
)

// Boolean validation
Field.checkbox("Terms",
  validate: FormValidator.isTrue()
)

// Multiple validators
Field.text("Username",
  validate: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
    .regex(r'^[a-zA-Z0-9_]+$')
)
```

### Available Validators

| Validator | Description |
|-----------|-------------|
| `notEmpty()` | Ensures field is not empty |
| `email()` | Validates email format |
| `minLength(n)` | Minimum length check |
| `maxLength(n)` | Maximum length check |
| `numeric()` | Numbers only |
| `regex(pattern)` | Custom regex pattern |
| `contains(list)` | Must contain value from list |
| `dateInPast()` | Date must be in past |
| `dateInFuture()` | Date must be in future |
| `password(strength: 1\|2)` | Password strength validation |
| `phoneNumberUs()` | US phone format |
| `phoneNumberUk()` | UK phone format |
| `url()` | Valid URL format |
| `zipcodeUs()` | US zipcode format |
| `postcodeUk()` | UK postcode format |
| `isTrue()` | Must be true |
| `isFalse()` | Must be false |
| `dateAgeIsYounger(age)` | Age younger than specified |
| `dateAgeIsOlder(age)` | Age older than specified |

<a name="form-casts"></a>
<br>

## Form Casts

Casts transform field input into specific formats:

### Available Casts

| Cast | Description | Example |
|------|-------------|---------|
| `FormCast.email()` | Email input | user@example.com |
| `FormCast.number()` | Numeric input | 42 |
| `FormCast.currency("usd")` | Currency formatting | $42.99 |
| `FormCast.capitalizeWords()` | Title case | Hello World |
| `FormCast.date()` | Date picker | 2024-01-15 |
| `FormCast.mask()` | Custom input mask | (123) 456-7890 |
| `FormCast.picker()` | Selection list | - |
| `FormCast.chips()` | Multi-select chips | - |
| `FormCast.checkbox()` | Boolean checkbox | - |
| `FormCast.switchBox()` | Boolean switch | - |
| `FormCast.textarea()` | Multi-line text | - |
| `FormCast.password()` | Password input | - |

### Custom Casts

Create custom casts in `config/form_casts.dart`:

```dart
final Map<String, dynamic> formCasts = {
  "phone": (Field field, Function(dynamic value)? onChanged) {
    return CustomPhoneField(
      field: field,
      onChanged: onChanged
    );
  }
};
```

<a name="managing-form-data"></a>
<br>

## Managing Form Data

In this section, we'll cover how to manage form data in Nylo. Everything from setting initial data to updating and clearing form fields.

<a name="initializing-data"></a>
<br>

### Setting Initial Data
```dart
NyForm(
  form: form,
  loadData: () async {
    final userData = await api<ApiService>((request) => request.getUserData());
    
    return {
      "name": userData?.name,
      "email": userData?.email
    };
  }
)

// or 
NyForm(
  form: form,
  initialData: {
    "name": "John Doe",
    "email": "john@example.com"
  }
)
```

You can also set initial data directly in the form class:

```dart
class EditAccountForm extends NyFormData {
  EditAccountForm({String? name}) : super(name ?? "edit_account");

  @override
  get init => () async {
    final userResource = await api<ApiService>((request) => request.getUserData());

    return {
      "first_name": userResource?.firstName,
      "last_name": userResource?.lastName,
      "phone_number": userResource?.phoneNumber,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
    Field.number("Phone Number"),
  ];
}
```

### Updating Data
```dart
// Update single field
form.setField("name", "Jane Doe");

// Update multiple fields
form.setData({
  "name": "Jane Doe",
  "email": "jane@example.com"
});
```

### Clearing Data
```dart
// Clear everything
form.clear();

// Clear specific field
form.clearField("name");
```

<a name="submit-button"></a>
<br>

## Submit Button

In your Form class, you can define a submit button:

```dart
class UserInfoForm extends NyFormData {
  UserInfoForm({String? name}) : super(name ?? "user_info");

  @override
  fields() => [
        Field.text("First Name",
            style: "compact"
        ),
        Field.text("Last Name",
            style: "compact"
        ),
        Field.number("Phone Number",
            style: "compact"
        ),
      ];
  
  @override
  Widget? get submitButton => Button.primary(text: "Submit", 
                submitForm: (this, (data) {
                    printInfo(data);
                }));
}
```

The `Button` widget is a pre-built component that can be used to submit a form. 
All you need to do is pass the `submitForm` parameter to the button.

Out the box, Nylo provides 8 pre-built buttons that you can use to submit a form.
- `Button.primary`
- `Button.secondary`
- `Button.outlined`
- `Button.textOnly`
- `Button.icon`
- `Button.gradient`
- `Button.rounded`

If you want to use a different widget to submit the form, you can call the `submit` method on the form:

```dart
class UserInfoForm extends NyFormData {
  UserInfoForm({String? name}) : super(name ?? "user_info");

  @override
  fields() => [
        Field.text("First Name",
            style: "compact"
        ),
        Field.text("Last Name",
            style: "compact"
        ),
        Field.number("Phone Number",
            style: "compact"
        ),
      ];
  
  @override
  Widget? get submitButton => ElevatedButton(
    onPressed: () {
      submit(onSuccess: (data) {
        printInfo(data);
      });
    },
    child: Text("Submit"),
  );
}
```

Lastly, you can also add a submit button directly to the form widget:

```dart
NyForm(
    form: form,
    footer: Button.primary(text: "Submit", submitForm: (form, (data) {
      printInfo(data);
    })),
)
```

Or with another widget:

```dart
NyForm(
  form: form,
  footer: MaterialButton(onPressed: () {
    form.submit(onSuccess: (data) {
      printInfo(data);
    });
  }, child: Text("Submit")),
)
```

<a name="form-styling"></a>
<br>

## Form Styling

### Global Styles

Define global styles in `app/forms/style/form_style.dart`:

```dart
class FormStyle extends NyFormStyle {
  @override
  FormStyleTextField textField(BuildContext context, Field field) {
    return {
      'default': (NyTextField textField) => textField.copyWith(
        decoration: InputDecoration(
          border: OutlineInputBorder(),
          filled: true,
          fillColor: Colors.grey[100],
        ),
      ),
      'compact': (NyTextField textField) => textField.copyWith(
        decoration: InputDecoration(
          border: UnderlineInputBorder(),
          contentPadding: EdgeInsets.symmetric(
            horizontal: 8,
            vertical: 4
          ),
        ),
      ),
    };
  }
}
```

### Field-Level Styling

#### Using Style Extension
```dart
Field.email("Email",
  style: "compact".extend(
    labelText: "Email Address",
    prefixIcon: Icon(Icons.email),
    backgroundColor: Colors.grey[100],
    borderRadius: BorderRadius.circular(8),
    
    // Custom decoration states
    decoration: (data, inputDecoration) {
      return inputDecoration.copyWith(
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: BorderSide(color: Colors.blue)
        )
      );
    },
    successDecoration: (data, inputDecoration) {
      return inputDecoration.copyWith(
        border: OutlineInputBorder(
          borderSide: BorderSide(color: Colors.green)
        )
      );
    },
    errorDecoration: (data, inputDecoration) {
      return inputDecoration.copyWith(
        border: OutlineInputBorder(
          borderSide: BorderSide(color: Colors.red)
        )
      );
    }
  )
)
```

#### Direct Styling
```dart
Field.text("Name",
  style: (NyTextField textField) => textField.copyWith(
    decoration: InputDecoration(
      prefixIcon: Icon(Icons.person),
      border: OutlineInputBorder(),
    ),
  ),
)
```

<a name="advanced-features"></a>
<br>

## Advanced Features

<a name="form-layout"></a>
<br>

### Form Layout
```dart
fields() => [
  // Single field
  Field.text("Title"),
  
  // Grouped fields in row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],
  
  // Another single field
  Field.textarea("Bio")
];
```

<a name="conditional-fields"></a>
<br>

### Conditional Fields
```dart
Field.checkbox("Has Pets",
  onChange: (value) {
    if (value == true) {
      form.showField("Pet Names");
    } else {
      form.hideField("Pet Names");
    }
  }
)
```

<a name="form-events"></a>
<br>

### Form Events
```dart
NyForm(
  form: form,
  onChanged: (field, data) {
    print("$field changed: $data");
  },
  validateOnFocusChange: true
)
```

<a name="pre-built-components"></a>
<br>

## Pre-built Components

### Login Form
```dart
NyLoginForm loginForm = Forms.login(
  emailValidationMessage: "Please enter a valid email",
  passwordValidationMessage: "Password is required",
  style: "compact"
);
```

<a name="ny-form-api-reference"></a>
<br>

## API Reference for NyForm

A widget that manages form state, validation, and submission in Nylo applications.

## Constructor

```dart
NyForm({
  Key? key,
  required NyFormData form,
  double crossAxisSpacing = 10,
  double mainAxisSpacing = 10,
  Map<String, dynamic>? initialData,
  Function(String field, Map<String, dynamic> data)? onChanged,
  bool validateOnFocusChange = false,
  Widget? header,
  Widget? footer,
  Widget? loading,
  bool locked = false,
})
```

## Parameters

### Required Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `form` | `NyFormData` | The form to display and manage. Contains field definitions and validation rules. |

### Optional Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `key` | `Key?` | `null` | Controls how one widget replaces another widget in the tree. |
| `crossAxisSpacing` | `double` | `10` | Spacing between fields in the cross axis direction. |
| `mainAxisSpacing` | `double` | `10` | Spacing between fields in the main axis direction. |
| `initialData` | `Map<String, dynamic>?` | `null` | Initial values for form fields. Keys should match field names. |
| `onChanged` | `Function(String, Map<String, dynamic>)?` | `null` | Callback when any field value changes. Provides field name and complete form data. |
| `validateOnFocusChange` | `bool` | `false` | Whether to validate fields when focus changes. |
| `header` | `Widget?` | `null` | Widget to display above the form fields. |
| `footer` | `Widget?` | `null` | Widget to display below the form fields. |
| `loading` | `Widget?` | `null` | Widget to display while the form is loading. Defaults to a skeleton loader if not provided. |
| `locked` | `bool` | `false` | When true, makes the form read-only and prevents user input. |

## Example Usage

```dart
NyForm(
  form: LoginForm(),
  initialData: {
    "email": "user@example.com",
    "password": ""
  },
  header: Text("Login"),
  footer: SubmitButton(),
  onChanged: (field, data) {
    print("Field $field changed. New data: $data");
  },
  validateOnFocusChange: true,
  crossAxisSpacing: 16,
  mainAxisSpacing: 20,
)
```

## Notes

- The form parameter automatically initializes with the provided `initialData` if any.
- The `loading` widget is only shown when the form is in a loading state.
- The `onChanged` callback provides both the changed field name and the complete form data.
- When `locked` is true, the form becomes non-interactive but still displays values.
- `header` and `footer` widgets are optional and will only be displayed if provided.