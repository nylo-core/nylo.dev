# Validation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to validation")
- [Validating Text Fields](#validating-text-fields "Validating Text Field")
- [Validation Rules](#validation-rules "Validation Rules")
- [Custom Validation Rules](#custom-validation-rules "Custom Validation Rules")


<a name="introduction"></a>
<br>

## Introduction

In {{ config('app.name') }}, you can handle validating data using the `validate` helper. It contains some useful [validation rules](#validation-rules) you can use in your project. If you need to add custom validation rules, you can do that too. In this section, we'll give an overview of how validation works in {{ config('app.name') }}.

> When an error is thrown from the `validate` helper, you can either display a toast notification to your user or handle it manually.

#### Let's take a look at how validation works in {{ config('app.name') }}.

- Example validating an email

``` dart 
class _ExampleState extends NyState<ExamplePage> {
  ...

  handleFormPass() {
    String textFieldPass = 'agordon@web.com';

    validate(rules: {
      "email address": "email" // validation rule 'email'
    }, data: {
      "email address": textFieldPass
    }, onSuccess: () {
      print('looks good');
      // do something...
    });
  }

  handleFormFail() {
    String textFieldFail = 'agordon.dodgy.data';

    validate(rules: {
      "email address": "email" // validation rule 'email'
    }, data: {
      "email address": textFieldFail
    }, onSuccess: () {
      // onSuccess would not be called
      print("success...");
    }, onFailure: (Exception exception) {
      /// handle the validation error
      print("failed validation");
    }, showAlert: false);
  }
}
```

- Example validating a phone number

``` dart
class _ExampleState extends NyState<ExamplePage> {
  TextEditingController _textFieldController = TextEditingController();
  ...

  handleForm() {
    String textFieldValue = _textFieldController.text;

    validate(rules: {
      "phone number": "phone_uk" // validation rule 'phone_uk'
    }, data: {
      "phone number": textFieldValue
    }, onSuccess: () {
      print('looks good');
      // do something...
    });
  }
}
```

- Example validating a field contains a value

``` dart 
class _ExampleState extends NyState<ExamplePage> {
  ...

  handleForm() {
    String textEmail = 'ferrari';

    validate(rules: {
      "car model": "contains:lamborghini,ferrari" // validation rule 'contains'
    }, data: {
      "car model": textEmail
    }, onSuccess: () {
      print("Success! It's a ferrari");
      // do something...
    }, onFailure: (Exception exception) {
        print('No match found');
    });
  }
}
```

#### Options:
``` dart 
validate(
  rules: {
  "email address": "email|max:10" // checks data is an email and maximum of 10 characters
  }, data: {
    "email address": textEmail // data to be validated
  }, message: {
    "email address": "oops|it failed" // first section is title, then add a " | " and then provide the description
  },
  showAlert: true, // if you want {{ config('app.name') }} to display the alert, default : true
  alertStyle: ToastNotificationStyleType.DANGER // choose from SUCCESS, INFO, WARNING and DANGER
);
```

This method is handy if you want to quickly validate the user's data and display some feedback to the user.

<a name="validating-text-fields"></a>
<br>

## Validating Text Fields

You can validate Text Fields by using the `NyTextField` widget. You can pass validation rules into the widget which will be used for the validation.




<a name="validation-rules"></a>
<br>

## Validation Rules

Here are the available validation rules that you can use in {{ config('app.name') }}.

| Rule Name   | Usage | Info |
|---|---|---|
| <a href="#validation-rule-email">Email</a> | email  | Checks if the data is a valid email |
| <a href="#validation-rule-contains">Contains</a>   | contains:jeff,cup,example  | Checks if the data contains a value |
| <a href="#validation-rule-url">URL</a>  | url  | Checks if the data is a valid url |
| <a href="#validation-rule-boolean">Boolean</a>  | boolean  | Checks if the data is a valid boolean |
| <a href="#validation-rule-min">Min</a>  | min:5  | Checks if the data is a minimum of x characters |
| <a href="#validation-rule-max">Max</a>  | max:11  | Checks if the data is a maximum of x characters |
| <a href="#validation-rule-not">Not empty</a>  | not_empty  | Checks if the data is not empty |
| <a href="#validation-rule-regex">Regex</a>  | r'regex:([0-9]+)'  | Checks if the data matches a regex pattern |
| <a href="#validation-rule-numeric">Numeric</a>  | numeric  | Checks if the data is numeric |
| <a href="#validation-rule-date">Date</a>  | date  | Checks if the data is a date |
| <a href="#validation-rule-capitalized">Capitalized</a>  | capitalized  | Checks if the data is capitalized |
| <a href="#validation-rule-lowercase">Lowercase</a>  | lowercase  | Checks if the data is lowercase |
| <a href="#validation-rule-uppercase">Uppercase</a>  | uppercase  | Checks if the data is uppercase |
| <a href="#validation-rule-us-phone-number">US Phone Number</a>  | phone_number_us  | Checks if the data is a valid phone US phone number |
| <a href="#validation-rule-uk-phone-number">UK Phone Number</a>  | phone_number_uk  | Checks if the data is a valid phone UK phone number |
| <a href="#validation-rule-us-zipcode">US Zipcode</a>  | zipcode_us  | Checks if the data is a valid zipcode for the US |
| <a href="#validation-rule-uk-postcode">UK Postcode</a>  | postcode_uk  | Checks if the data is a valid postcode for the UK |

<br>

---

<a name="validation-rule-email"></a>
<br>

### email

This allows you to validate if the input is an email.

Usage: `email`


<a name="validation-rule-boolean"></a>
<br>

### boolean

This allows you to validate if the input is a boolean.

Usage: `boolean`


<a name="validation-rule-contains"></a>
<br>

### contains

Check if the input contains a particular value.

Usage: `contains:dog,cat`


<a name="validation-rule-url"></a>
<br>

### url

Check if the input is a URL.

Usage: `url`


<a name="validation-rule-min"></a>
<br>

### min

Check if the input is a minimum of characters.

Usage: `min:7` - will fail if the user's input is less than 7 characters.


<a name="validation-rule-max"></a>
<br>

### max

Check if the input is a maximum of characters.

Usage: `max:10` - will fail if the user's input is more than 10 characters.


<a name="validation-rule-not-empty"></a>
<br>

### Not Empty

Check if the input is not empty.

Usage: `not_empty` - will fail if the user's input is empty.


<a name="validation-rule-regex"></a>
<br>

### Regex

Check the input against a regex pattern.

Usage: `r'regex:([0-9]+)'` - will fail if the user's input does not match the regex pattern.


<a name="validation-rule-numeric"></a>
<br>

### numeric

Check if the input is a numeric match.

Usage: `numeric` - will fail if the user's input is not numeric.


<a name="validation-rule-date"></a>
<br>

### date

Check if the input is a date, e.g. 2020-02-29.

Usage: `date` - will fail if the user's input is not date.


<a name="validation-rule-capitalized"></a>
<br>

### capitalized

Check if the input is capitalized, e.g. "Hello world".

Usage: `capitalized` - will fail if the user's input is not capitalized.


<a name="validation-rule-lowercase"></a>
<br>

### lowercase

Check if the input is lowercase, e.g. "hello world".

Usage: `lowercase` - will fail if the user's input is not lowercased.


<a name="validation-rule-uppercase"></a>
<br>

### uppercase

Check if the input is uppercase, e.g. "HELLO WORLD".

Usage: `uppercase` - will fail if the user's input is not uppercase.

<a name="validation-rule-us-phone-number"></a>
<br>

### US Phone Number

Check if the input is a valid US Phone Number, e.g. "123-456-7890".

Usage: `phone_number_us` - will fail if the user's input is not a US phone number.

<a name="validation-rule-uk-phone-number"></a>
<br>

### UK Phone Number

Check if the input is a valid UK Phone Number, e.g. "07123456789".

Usage: `phone_number_uk` - will fail if the user's input is not a UK phone number.

<a name="validation-rule-us-zipcode"></a>
<br>

### US Zipcode

Check if the input is a valid US Zipcode, e.g. "33125".

Usage: `zipcode_us` - will fail if the user's input is not a US Zipcode.

<a name="validation-rule-uk-postcode"></a>
<br>

### UK Postcode

Check if the input is a valid UK Postcode, e.g. "B3 1JJ".

Usage: `postcode_uk` - will fail if the user's input is not a UK Postcode.


<a name="custom-validation-rules"></a>
<br>

## Custom Validation Rules

You can add custom validation rules for your project by opening the `config/valdiation_rules.dart` file. 

The `validationRules` variable contains all your custom validation rules. 

``` dart
final Map<String, dynamic> validationRules = {
  /// Example
  // "simple_password": (attribute) => SimplePassword(attribute)
};
```

To define a new validation rule, first create a new class that extends the `ValidationRule` class. Your validation class should implement the `handle` method like in the below example.

``` dart
class SimplePassword extends ValidationRule {
  SimplePassword(String attribute)
      : super(
      attribute: attribute,
      signature: "simple_password", // Signature for the validator
      description: "The $attribute field must be between 4 and 8 digits long and include at least one numeric digit", // Toast description when an error occurs
      textFieldMessage: "Must be between 4 and 8 digits long with one numeric digit"); // TextField validator description when an error occurs

  @override
  handle(Map<String, dynamic> info) {
    super.handle(info);

    RegExp regExp = RegExp(r'^(?=.*\d).{4,8}$');
    return regExp.hasMatch(info['data']);
  }
}
```

The `Map<String, dynamic> info` object:
``` dart
/// info['rule'] = Validation rule i.e "max:12".
/// info['data'] = Data the user has passed into the validation rule.
/// info['message'] = Overriding message to be displayed for validation (optional).
```

The `handle` method expects a `boolean` return type, if the data passes validation return `true` and `false` if it doesn't.
