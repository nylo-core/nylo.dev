# Forms

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
    - [How it works](#how-it-works "How it works")
- [Creating a form](#creating-a-form "Creating a form")
- [Displaying a form](#displaying-a-form "Displaying a form")
- [Submitting a Form](#submitting-a-form "Submitting a Form")
- Managing Data
    - [Setting data](#setting-data "Setting data")
    - [Clearing data](#clearing-data "Clearing data")
- Form
    - [Fields](#form-fields "Fields")
    - [Validation](#form-validation "Validation")
    - [Casts](#form-casts "Casts")
    - [Dummy Data](#form-dummy-data "Dummy Data")
    - [Style](#form-style "Style")
- Pre-built Forms
  - [NyLoginForm](#ny-login-form "NyLoginForm")
- [Form Parameters](#form-parameters "Form Parameters")
- [Listening to Form Changes](#listening-to-form-changes "Listening to Form Changes")
- [Custom Form Cast](#custom-form-casts "Custom Form Cast")


<div id="introduction"></div>
<br>

## Introduction to Forms

Forms are fundamental for any modern mobile or web application.

In Nylo you can use the `NyForm` class to manage, validate and submit data all in one place.

It's extremely easy and customizable, let's take a look at how to create a form.

<div id="how-it-works"></div>
<br>

#### Creating a form

First, run the below [metro](/docs/5.20.0/metro) command from your terminal.
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
    Field("Email", 
        cast: FormCast.email(),
        validator: FormValidator.email()
    ),
    Field("Password",
        cast: FormCast.password(),
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
...

// Create your form
LoginForm form = LoginForm();

// add the form using the NyForm widget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
        shrinkWrap: true,
        children: [
          NyForm(form: form),

          ElevatedButton(child: Text("Submit"),
            onPressed: () {
              form.submit(onSuccess: (data) {
                // data = { "Email": "example@mail.com", "Password": "password" }
              });
            },
         )
        ],
    ),
  );
}
```

This is all you need to create and display a form in Nylo.

The UI of this page will now contain two fields, `Email` and `Password`, and a submit button.

#### Submitting a form

To submit a form, you can call the `submit` method on a form.

``` dart
LoginForm form = LoginForm();

@override
Widget build(BuildContext context) {
    return Scaffold(
        body: ListView(
            shrinkWrap: true,
            children: [
                NyForm(form: form),
            ],
        ),
        floatingActionButton: FloatingActionButton(
            onPressed: () {
                form.submit(onSuccess: (data) {
                    // Do something with the data
                });
            },
            child: Icon(Icons.save),
        ),
    );
}
```

When you call `form.submit()`, Nylo will validate the form and if the form is valid, it will call the `onSuccess` callback with the form **data**.

That's a quick overview of how to create, display and submit a form in Nylo.

This is just scratching the surface, you can customize your forms even further by adding casts, validation rules, dummy data and global styles.

<div id="creating-a-form"></div>
<br>

## Creating a form

The easiest way to create a form is with `Metro`. 

You can create a form by running the following command:

``` bash
dart run nylo_framework:main make:form AdvertForm
# or with Metro alias
metro make:form AdvertForm
```

This will create a new form class in the `lib/app/forms` directory.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AdvertForm extends NyFormData {

  AdvertForm({String? name}) : super(name ?? "advert");

  // Add your fields here
  @override
  fields() => [
    Field("Name", 
      validator: FormValidator.rule("not_empty|max:20")
    ),
    Field("Price", 
      cast: FormCast.currency("usd"),
      validator: FormValidator.rule("not_empty")
    ),
    Field("Favourite Color", 
      value: "Blue",
      cast: FormCast.picker(
        options: ["Red", "Green", "Blue", "Yellow"]
      ),
      validator: FormValidator.rule("not_empty")
    ),
  ];
}
```

<div id="displaying-a-form"></div>
<br>

## Displaying a form

To display a form, you can use the `NyForm` widget.

Once you have created a form, you can display it anywhere in your application.

``` dart
AdvertForm form = AdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
        children: [
            NyForm(form: form),
        ],
    ),
  );
}

// or like this
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
        children: [
            form.create()
        ],
    ),
  );
}
```

This will display the fields associated with the form.

You'll need to provide an additional widget to submit the form using `form.submit()`.


<div id="submitting-a-form"></div>
<br>

## Submitting a Form

To submit a form, you can call the `submit` method on a form.

``` dart
AdvertForm form = AdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm(form: form),
    floatingActionButton: FloatingActionButton(
      onPressed: () {
        form.submit(onSuccess: (data) {
          // Do something with the data
        });
      },
      child: Icon(Icons.save),
    ),
  );
}
```

When you call `form.submit()`, Nylo will validate the form and if the form is valid, it will call the `onSuccess` callback with the form data.

You can also use the `onFailure: (error) {}` callback to handle form validation errors.

``` dart
AdvertForm form = AdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm(form: form),
    floatingActionButton: FloatingActionButton(
      onPressed: () {
        form.submit(
          onSuccess: (data) {
            // Do something with the data
          },
          onFailure: (error) {
            // Do something with the error
          }
        );
      },
      child: Icon(Icons.save),
    ),
  );
}
```

<div id="setting-data"></div>
<br>

## Setting data

You can set data using the `initialData` parameter.

``` dart
CarAdvertForm form = CarAdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
      children: [
        NyForm(form: form, initialData: {
            "Model": "BMW",
            "Price": 45000,
            "Wheel Size": "18",
        }),
      ],
    )
  );
}

// or like this
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
      children: [
        form.create(initialData: {
            "Model": "BMW",
            "Price": 45000,
            "Wheel Size": "18",
        }),
      ],
    )
  );
}
```

If you need to set data after it's been created, use the `setData` method or `setField` method.
- `setData` will set all the data at once.
- `setField` will set the data for a specific field.

``` dart
CarAdvertForm form = CarAdvertForm();

init() async {
    form.setData({
        "Model": "BMW",
        "Price": 45000,
        "Wheel Size": "18",
    });

    // or by field name
    form.setField("Model", "Mercedes");
}

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
      children: [
        NyForm(form: form),

        ElevatedButton(
          onPressed: () {
            form.submit(onSuccess: (data) {
              // Do something with the data
              // data = { "Model": "Mercedes", "Price": 45000, "Wheel Size": "18" }
            });
          },
          child: Text("Submit"),
        ),
      ],
    )
  );
}
```

<div id="clearing-data"></div>
<br>

## Clearing Data

If you need to clear all the data, you can use the `clear` method.

``` dart
JobForm form = JobForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: ListView(
      children: [
        NyForm(form: form),
        
        ElevatedButton(child: Text("Clear Data"),
          onPressed: () {
            form.clear();
          },
        ),
      ],
    )
  );
}
```

If you need to clear a specific field, you can use the `clearField` method.

``` dart
form.clearField("Name");
```

<div id="form-fields"></div>

## Fields

The `Field` class in Nylo is important because it defines the fields in your form.

Let's imagine we run a social media app and we have a page where users can upload a 'post'.

The data we need to collect is the **title**, **category**, **description**, and **hash tags**.

``` dart
class PostForm extends NyFormData {

  PostForm({String? name}) : super(name ?? "post");

  @override
  fields() => [
    Field("Title"),
    Field("Category"),
    Field("Description"),
    Field("Hash Tags"),
  ];
}
```

The above code will create a form with three fields: `Title`, `Category`, `Description`, and `Hash Tags`.

We can improve it by adding **casts** and **validation rules**.

``` dart
class PostForm extends NyFormData {

  PostForm({String? name}) : super(name ?? "post");

  @override
  fields() => [
    Field("Title",
        cast: FormCast.capitalizeWords(),
        validate: FormValidator()
                    .notEmpty()
                    .maxLength(50),
    ),
    Field("Category",
        cast: FormCast.picker(
            options: ["Technology", "Fashion", "Food", "Travel"],
        ),
        validate: FormValidator()
                    .contains(["Technology", "Fashion", "Food", "Travel"]),
    ),
    Field("Description",
        cast: FormCast.textArea(),
        validate: FormValidator(message: "Please write a description")
                    .notEmpty(),
    ),
    Field("Hash Tags", 
        cast: FormCast.textArea(textAreaSize: TextAreaSize.md),
        validate: FormValidator.rule("not_empty")
    ),
  ];
}
```

This new form now has casts and validation rules.

When the form is submitted, Nylo will validate the form and if the form is valid, it will call the `onSuccess` callback with the form **data**.

### Grouping Fields

If you want to group fields next to each other, you can use a `List`.

It's easy, here's an example:

``` dart
class PostForm extends NyFormData {

  PostForm({String? name}) : super(name ?? "post");

  @override
  fields() => [
    [
        Field("Title",
            cast: FormCast.capitalizeWords(),
        ),
        Field("Category",
            cast: FormCast.picker(
                options: ["Technology", "Fashion", "Food", "Travel"],
            ),
        ),
    ],
    Field("Description",
        cast: FormCast.textArea(),
    ),
    Field("Hash Tags", 
        cast: FormCast.textArea(textAreaSize: TextAreaSize.md),
    ),
  ];
}
```

If you try this, you will see that the `Title` and `Category` fields are grouped together.

Behind the scenes, Nylo will create a **Row** widget to display the fields.

You can also adjust the mainAxisSpacing and crossAxisSpacing using the `mainAxisSpacing` and `crossAxisSpacing` parameters on the `NyForm` widget.


<div id="form-validation"></div>
<br>

## Validation

Nylo is able to validate your form fields using the `FormValidator` class.

Here's all the available validation rules:
| Method | Description |
|--------|-------------|
| `FormValidator.email()` | Validates an email address |
| `FormValidator.password()` | Validates a password |
| `FormValidator().notEmpty()` | Validates that the field is not empty |
| `FormValidator().minLength(5)` | Validates that the field has a minimum length of 5 |
| `FormValidator().maxLength(10)` | Validates that the field has a maximum length of 10 |
| `FormValidator().contains(["developer", "manager"])` | Validates that the field contains one of the values in the array |
| `FormValidator().numeric()` | Validates that the field is a number |
| `FormValidator().date()` | Validates that the field is a date |
| `FormValidator().uppercase()` | Validates that the field is uppercase |
| `FormValidator().lowercase()` | Validates that the field is lowercase |
| `FormValidator().regex(r"^[a-zA-Z0-9]*$")` | Validates that the field matches a regex pattern |
| `FormValidator().dateInPast()` | Validates that the date is in the past |
| `FormValidator().dateInFuture()` | Validates that the date is in the future |
| `FormValidator().isTrue()` | Validates that the value is true |
| `FormValidator().isFalse()` | Validates that the value is false |
| `FormValidator().dateAgeIsYounger(35)` | Validates that the date is younger than 35 |
| `FormValidator().dateAgeIsOlder(35)` | Validates that the date is older than 35 |
| `FormValidator().zipcodeUs()` | Validates that the value is a valid US zipcode |
| `FormValidator().postcodeUk()` | Validates that the value is a valid UK postcode |

If you need to create a custom validation rule, you can do so by calling `FormValidator.custom` like in the example below.

``` dart
class JobForm extends NyFormData {
  
  @override
  fields() => [
    Field("Name", validation: FormValidator().notEmpty().maxLength(20)),
    Field("Age", validation: FormValidator.rule("not_empty|numeric|min:18")),
    Field("Salary", validation: FormValidator.custom((value) {
      if (value < 10000) {
        return false;
      }
      return true;
    }, message: "Salary must be greater than 10000")),
  ];
}
```

In the above example, we have defined validation rules for the fields `Name`, `Age` and `Salary`.
If the form is invalid, the `onFailure` callback will be called with the error message.

You can find all the available validation rules [here](https://nylo.dev/docs/5.20.0/validation#validation-rules).

### Validate on focus change

This is feature is disabled by default. If you want to enable it, you can do so by setting the `validateOnFocusChange` parameter to `true`.

``` dart
NyForm(form: accountForm, validateOnFocusChange: true),
```

Now, the form will only validate the field when the focus changes.

### Custom Error Messages

You can also provide a custom error message for each field.

``` dart
class JobForm extends NyFormData {

  @override
  fields() => [
    Field("Name", 
        validation: FormValidator(message: "Name is required").notEmpty().maxLength(20)
    ),
    Field("Age", 
        validation: FormValidator.rule("not_empty|numeric|min:25", message: "Age must be a number and greater than 25")
    ),
    Field("Salary", validation: FormValidator.custom((value) {
      if (value < 10000) {
        return false;
      }
      return true;
    }, message: "Salary must be greater than 10000")),
  ];
}
```

<div id="form-casts"></div>

## Casts

Casts are used to cast the fields to their respective types.

``` dart
class JobForm extends NyFormData {

  @override
  fields() => [
    Field("Age", 
        cast: FormCast.number()
    ),
    Field("Salary", 
        cast: FormCast.currency("usd"),
    ),
    Field("Position", 
        cast: FormCast.picker(
            options: ["Developer", "Manager", "Designer", "Tester"]
        ),
    ),
  ];
}
```

### Available Casts

| Method | Description |
|--------|-------------|
| `FormCast()` | Default cast |
| `FormCast.email()` | Cast to email |
| `FormCast.password()` | Cast to password |
| `FormCast.password(viewable: true)` | Cast to viewable password |
| `FormCast.currency("usd")` | Cast to currency (supports multiple [currencies](#all-currencies)) |
| `FormCast.picker()` | Cast to picker |
| `FormCast.textArea()` | Cast to textarea |
| `FormCast.textArea(textAreaSize: TextAreaSize.md)` | Cast to medium textarea |
| `FormCast.capitalizeWords()` | Cast to capitalize words |
| `FormCast.capitalizeSentences()` | Cast to capitalize sentences |
| `FormCast.number()` | Cast to number |
| `FormCast.phoneNumber()` | Cast to phone number |
| `FormCast.datetime()` | Cast to datetime |
| `FormCast.uppercase()` | Cast to uppercase |
| `FormCast.lowercase()` | Cast to lowercase |

You can use any of the above casts in your form.

If you'd like to create your own custom cast, you can do so by creating a new class in `config/form_casts.dart`.

<div id="all-currencies"></div>

### FormCast.currency - all currencies

The `FormCast.currency` cast supports multiple currencies, here's a list of all the available currencies:

| Currency Code | Currency Name |
|---------------|---------------|
| `usd` | US Dollar |
| `eur` | Euro |
| `gbp` | British Pound Sterling |
| `jpy` | Japanese Yen |
| `cny` | Chinese Yuan |
| `cad` | Canadian Dollar |
| `aud` | Australian Dollar |
| `inr` | Indian Rupee |
| `idr` | Indonesian Rupiah |
| `sgd` | Singapore Dollar |
| `myr` | Malaysian Ringgit |
| `thb` | Thai Baht |
| `twd` | New Taiwan Dollar |
| `vnd` | Vietnamese Dong |
| `zar` | South African Rand |
| `pkr` | Pakistani Rupee |


<div id="form-dummy-data"></div>
<br>

## Dummy Data

You can add dummy data to your form when you are developing your application.

If your `.env` file is set to production, the dummy data will be ignored.

**Example**

``` env
APP_ENV="developing" # or "production"
```


``` dart
class FitnessLevelForm extends NyFormData {

  @override
  fields() => [
    Field("Name", 
        cast: FormCast.number(),
        dummyData: "John Doe"
    ),
    Field("Weight", 
        dummyData: "75 kg"
    ),
    Field("Height", 
        dummyData: "180 cm"
    ),
    Field("Hash Tags", 
        dummyData: "#fitness, #health, #gym"
    ),
  ];
}
```

In the above example, the fields `Name`, `Weight`, `Height`, and `Hash Tags` will have dummy data when the form is displayed.

> **Note**: Remove to update your .env file to `APP_ENV="production"` to disable dummy data.

<div id="form-style"></div>

## Style

Nylo provides two ways to style your form fields.

The first is by defining a global style for all fields in the form. The second is by styling individual fields.

### Global Style

Global styles are the easiest way to customize your form fields. You can define the style for any field except for the `Picker` and `DateTime` field.

To get started, first navigate to your `/app/forms/style/form_style.dart` file.

This file will contain a class called `FormStyle`, you'll be able to define all your global styles here.

To update the TextField's, override the `textField` method. This function is responsible for styling the TextField's in your form.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class FormStyle extends NyFormStyle {

  /// TextField styles for the form
  @override
  FormStyleTextField textField(BuildContext context, Field field) {
    return {
     'default': (NyTextField textField) => textField.copyWith(
        decoration: InputDecoration(
          border: InputBorder.none,
          filled: true,
          fillColor: Colors.blue.shade100,
          labelText: field.name,
        ),
      ),
    };
  }
  ...
```

In the above example, we have defined a global style for all the TextField's in the form.

Note that the `default` key is required to define the default style for all the TextField's.

You can also define more styles, like in the example below:

``` dart
``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class FormStyle extends NyFormStyle {

  /// TextField styles for the form
  @override
  FormStyleTextField textField(BuildContext context, Field field) {
    return {
     'default': (NyTextField textField) => textField.copyWith(
        decoration: InputDecoration(
          border: InputBorder.none,
          filled: true,
          fillColor: Colors.blue.shade100,
          labelText: field.name,
        ),
      ),
      'minimal': (NyTextField textField) => textField.copyWith(
        decoration: InputDecoration(
          ...
        ),
      ),
    };
  }
  ...
```

Now, you can use the `minimal` style in your form.

``` dart
class ProfileForm extends NyFormData {
  
  fields() => [
    Field("Name", style: "minimal"),
    Field("Username"),
  ];
}
```

You can also use the `style` parameter, this will override the global style for the field.

Nylo contains a `compact` style that you can use in your form.

``` dart
class RegisterForm extends NyFormData {

    @override
    fields() => [
        Field("Name", 
            style: "compact"
        ),
        Field("Email", 
            style: "compact"
        ),
        Field("Password", 
            style: "compact"
        ),
        Field("Confirm Password", 
            style: "compact"
        ),
    ];
}
```

You can also use the `style` parameter to perform an inline style override.

``` dart
class RegisterForm extends NyFormData {

    @override
    fields() => [
        Field("Name", 
            style: (NyTextField nyTextField) => nyTextField.copyWith(
                decoration: InputDecoration(
                    labelText: "Name",
                    hintText: "Enter your name",
                ),
            ),
        ),
        Field("Email", 
            style: (NyTextField nyTextField) => nyTextField.copyWith(
                decoration: InputDecoration(
                    labelText: "Email",
                    hintText: "Enter your email",
                ),
            ),
        ),
        Field("Password", 
            style: (NyTextField nyTextField) => nyTextField.copyWith(
                decoration: InputDecoration(
                    labelText: "Password",
                    hintText: "Enter your password",
                ),
            ),
        ),
        Field("Confirm Password", 
            style: (NyTextField nyTextField) => nyTextField.copyWith(
                decoration: InputDecoration(
                    labelText: "Confirm Password",
                    hintText: "Confirm your password",
                ),
            ),
        ),
    ];
}
```


<div id="ny-login-form"></div>
<br>

## Pre-built Forms

Nylo comes with pre-built forms that you can use out of the box. 

This list will be updated as more forms are added.

## NyLoginForm

The `NyLoginForm` is a pre-built login form that you can use in your application.

### Fields

- Email - `FormCast.email()`
- Password - `FormCast.password()`

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm.login(name: "login"),
    floatingActionButton: FloatingActionButton(
      onPressed: () {
        NyForm.submit("login", onSuccess: (data) {
          // Do something with the data
        });
      },
      child: Icon(Icons.save),
    ),
  );
}
```


<div id="form-parameters"></div>
<br>

## NyForm Parameters

- `key`: The key of the form
- `form`: The form to display
- `initialData`: The initial data for the form
- `crossAxisSpacing`: The cross axis spacing (default: 10)
- `mainAxisSpacing`: The main axis spacing (default: 10)
- `onChanged`: The callback when the form changes
- `validateOnFocusChange`: Validate the form on focus change (default: false)
- `locked`: Lock the form (default: false)


<div id="listening-to-form-changes"></div>
<br>

## Listening to Form Changes

You can listen to form changes using the `onChanged` callback.

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm(
      form: form,
      onChanged: (data) {
        // Do something with the data
        // data = { "Name": "John Doe", "Age": 25, "Salary": 45000, "Position": "Developer" }
      },
    ),
  );
}
```

<div id="custom-form-casts"></div>
<br>

## Custom Form Casts

If you want to use custom form casts, you can create them and reuse them in your forms.

First, make sure your `AppProvider` is set up correctly.

``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    ...
    nylo.addFormCasts(formCasts); // Add the form casts
  }
```

Inside `config/form_casts.dart`, you can add your custom form casts.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

final Map<String, dynamic> formCasts = {
  /// Example
  "age_picker": (Field field, Function(dynamic value)? onChanged) {
    return MyCustomField(field, onChanged);
  },
};
```

Your custom form cast needs to accept the following parameters:

- `Field field`: The field to cast
- `Function(dynamic value)? onChanged`: The callback when the field changes

Nylo will automatically register the form casts for you.

Now, you can use your custom form cast in your form.

``` dart
class JobForm extends NyFormData {

  @override
  fields() => [
    Field("Age", 
        cast: "age_picker"
    ),
    Field("Salary", 
        cast: FormCast.currency("usd"),
    ),
    Field("Position", 
        cast: FormCast.picker(
            options: ["Developer", "Manager", "Designer", "Tester"]
        ),
    ),
  ];
}
```
