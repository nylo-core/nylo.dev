# Forms

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
    - [How it works](#how-it-works "How it works")
- [Creating a form](#creating-a-form "Creating a form")
- [Displaying a form](#displaying-a-form "Displaying a form")
- [Submitting a Form](#submitting-a-form "Submitting a Form")
- Form
    - [Fields](#form-fields "Fields")
    - [Validation](#form-validation "Validation")
    - [Casts](#form-casts "Casts")
    - [Dummy Data](#form-dummy-data "Dummy Data")
    - [Style](#form-style "Style")
- Pre-build Forms
  - [NyLoginForm](#ny-login-form "NyLoginForm")
- [Form Parameters](#form-parameters "Form Parameters")
- [Listening to Form Changes](#listening-to-form-changes "Listening to Form Changes")
- [Custom Form Field](#custom-form-fields "Custom Form Field")


<a name="introduction"></a>
<br>

## Form

Forms are a fundermental part of any modern mobile or web application.
It's important that new fields can be added, the form can be validated and submitted with ease.

Nylo features a powerful Form builder out of the box to save you time and effort.

Let's take a look at how to create a form.

<a name="how-it-works"></a>
<br>

## How it works

Nylo forms are built using the `NyForm` class.

``` dart
...
class _LoginPageState extends NyState<LoginPage> {

  LoginForm form = LoginForm();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
         child: Column(
           children: [
             NyForm(form: form),
              
              ElevatedButton(
                child: Text("Submit"),
                onPressed: () {
                  form.submit(onSuccess: (data) {
                    // Do something with the data
                  });
                },
              )
           ],
         ),
      ),
    );
  }
}
```

Now, let's take a look at the `LoginForm` class.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormData {

  LoginForm({String? name}) : super(name ?? "login");

  @override
  fields() => [
    Field("Email", value: ""),
    Field("Password", value: ""),
  ];
  
  @override
  Map<String, dynamic> cast() => {
    "Email": FormCast.email(),
    "Password": FormCast.password(),
  };
  
  @override
  Map<String, dynamic> validate() => {
    "Email": FormValidator("email"),
    "Password": FormValidator("password_v1"),
  };
}
```

In the above example, we have created a `LoginForm` class and we have added two fields `Email` and `Password`.
We have also added casts and validation rules for the fields.

If you build and run the above code, you will see a form with two fields `Email` and `Password`.

Behind the scenes, Nylo will handle the form submission and validation for you.

If you call `form.submit()`, Nylo will validate the form and if the form is valid, it will call the `onSuccess` callback like in the example above.

<a name="creating-a-form"></a>
<br>

## Creating a form

The easiest way to create a form is using `Metro`. 

You can create a form by running the following command:

``` bash
dart run nylo_framework:main make:form AdvertForm

# or with Metro alias
metro make:form AdvertForm
```

This will create a new form class in the `lib/app/forms` directory - **lib/app/forms/login_form.dart**.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class AdvertForm extends NyFormData {

  AdvertForm({String? name}) : super(name ?? "advert");

  @override
  fields() => [
    Field("Name", value: "Customize your form ⚡️"),
    [
      Field("Price"),
      Field("Favourite Color", value: ["Red", "Blue", "Green"], selected: "Green"),
    ],
  ];

  @override
  Map<String, dynamic> cast() => {
    "Name": FormCast(),
    "Price": FormCast.currency("usd"),
    "Favourite Color": FormCast.picker(),
  };

  @override
  Map<String, dynamic> validate() => {
    "Name": FormValidator("not_empty|max:20"),
    "Price": FormValidator("not_empty"),
    "Favourite Color": FormValidator("not_empty"),
  };

  @override
  Map<String, dynamic> dummyData() => {
    // "Name": "John Doe",
    // "Price": 123.45,
  };

  @override
  Map<String, dynamic> style() => {
    // "Name": "compact",
    // "Price": "compact",
  };
}
```

<a name="displaying-a-form"></a>
<br>

## Displaying a form

To display a form, you can use the `NyForm` widget.

Once you have created a form, you can display it anywhere in your application.

``` dart
AdvertForm form = AdvertForm();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: NyForm(form: form),
  );
}
```

This will display the fields associated with the form.

You'll need to provide an additional widget to submit the form.


<a name="submitting-a-form"></a>
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

<a name="form-fields"></a>

## Fields

Fields are the building blocks of a form. Nylo provides a `Field` class to create fields.

``` dart
class ProfileForm extends NyFormData {

  ProfileForm({String? name}) : super(name ?? "profile");

  @override
  fields() => [
    Field("Name", value: ""),
    Field("Age", value: ""),
    Field("Salary", value: ""),
  ];
}
```
You can also group fields together using a list.

``` dart
class JobForm extends NyFormData {

  JobForm({String? name}) : super(name ?? "profile");

  @override
  fields() => [
    Field("Name", value: ""),
    [
      Field("Age", value: ""),
      Field("Salary", value: ""),
    ],
    Field("Position", value: ["Developer", "Designer", "Manager"], selected: "Developer"),
  ];
}
```

This will create an inline **Row** with the fields `Age` and `Salary`.

Each field can have the following properties:

- `key`: The name of the field (required)
- `value`: The default value of the field (optional)
- `selected`: If you are using a picker, you can set the selected value (optional)

<a name="form-validation"></a>
<br>

## Validation

Inside your Form, you can define all the validation rules for each field.

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> validate() => {
    "Name": FormValidator("not_empty|max:20"),
    "Age": FormValidator("not_empty|numeric|min:18"),
    "Salary": FormValidator("not_empty"),
    "Position": FormValidator("contains:developer,designer,manager"),
  };
}
```

In the above example, we have defined validation rules for the fields `Name`, `Age`, `Salary`, and `Position`.
If the form is invalid, the `onFailure` callback will be called with the error message.

You can find all the available validation rules [here](https://nylo.dev/docs/5.20.0/validation#validation-rules).

You can also provide a custom error message for each field.

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> validate() => {
    "Name": FormValidator("not_empty|max:20", message: "Name is required"),
    "Age": FormValidator("not_empty|numeric|min:18", message: "Age must be a number and greater than 18"),
    "Salary": FormValidator("not_empty", message: "Salary is required"),
    "Position": FormValidator("contains:developer,designer,manager", message: "Invalid position"),
  };
}
```

<a name="form-casts"></a>

## Casts

Casts are used to cast the fields to their respective types.

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> cast() => {
    "Name": FormCast(), // Default cast
    "Age": FormCast(),
    "Salary": FormCast.currency("usd"), // Cast to currency
    "Position": FormCast.picker(), // Cast to picker
  };
}
```

### Available Casts

- `FormCast()`: Default cast
- `FormCast.email()`: Cast to email
- `FormCast.password()`: Cast to password
    - `FormCast.password(viewable: true)`: Cast to viewable password
- `FormCast.currency("usd")`: Cast to currency: "gbp", "usd", "vnd", "thb", "twd", "eur", "myr", "jpy", "aud", "cny", "cad", "inr", "idr", "sgd", "zar", "pkr"
- `FormCast.picker()`: Cast to picker
- `FormCast.textArea()`: Cast to textarea
    - `FormCast.textArea(textAreaSize: TextAreaSize.md)`: Cast to medium textarea
- `FormCast.capitalizeWords()`: Cast to capitalize words
- `FormCast.capitalizeSentences()`: Cast to capitalize sentences
- `FormCast.number()`: Cast to number
- `FormCast.phoneNumber()`: Cast to phone number
- `FormCast.datetime()`: Cast to datetime
- `FormCast.uppercase()`: Cast to uppercase
- `FormCast.lowercase()`: Cast to lowercase


<a name="form-dummy-data"></a>
<br>

## Dummy Data

You can add dummy data to your form when you are developing your application.

If your `.env` file is set to production, the dummy data will be ignored.

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> dummyData() => {
    "Name": "John Doe",
    "Age": 25,
    "Salary": 45000,
    "Position": "Developer",
  };
}
```

<a name="form-style"></a>

## Style

You can style the fields using the `style` method.

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> style() => {
    "Name": "compact",
    "Age": "text",
    "Salary": "compact",
  };
}
```

### Available Styles

- `compact`: Compact style
- `text`: Text style

If you want to override the default TextField style, you can do the following:

``` dart
class JobForm extends NyFormData {
  ...

  @override
  Map<String, dynamic> style() => {
    "Name": "compact",
    "Age": (NyTextField nyTextField) => nyTextField.copyWith(
        keyboardType: TextInputType.number,
        decoration: InputDecoration(
          labelText: "Age",
          hintText: "Enter your age",
        ),
    ),
  };
}
```


<a name="ny-login-form"></a>
<br>

## Pre-build Forms

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


<a name="form-parameters"></a>
<br>

## NyForm Parameters

- `key`: The key of the form
- `form`: The form to display
- `crossAxisSpacing`: The cross axis spacing (default: 10)
- `mainAxisSpacing`: The main axis spacing (default: 10)
- `onChanged`: The callback when the form changes
- `validateOnFocusChange`: Validate the form on focus change (default: false)
- `locked`: Lock the form (default: false)


<a name="listening-to-form-changes"></a>
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

<a name="custom-form-casts"></a>
<br>

## Custom Form Casts

If you want to use custom form casts, you can create them and resuse them in your forms.

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

Your custom form cast needs to accepts the following parameters:

- `Field field`: The field to cast
- `Function(dynamic value)? onChanged`: The callback when the field changes

Nylo will automatically register the form casts for you.

Now, you can use your custom form cast in your form.

``` dart
@override
Map<String, dynamic> cast() => {
    "Email": FormCast.email(),
    "Age": "age_picker",
}
```
