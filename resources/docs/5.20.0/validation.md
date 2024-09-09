# Validation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to validation")
- Basics
    - [Validating Data](#validating-data "Validating Data")
    - [Multiple Validation Rules](#multiple-validation-rules "Multiple Validation Rules")
    - [Validating Text Fields](#validating-text-fields "Validating Text Fields")
    - [Validation checks](#validation-checks "Validation checks")
- [Validation Rules](#validation-rules "Validation Rules")
- [Creating Custom Validation Rules](#creating-custom-validation-rules "Creating Custom Validation Rules")


<a name="introduction"></a>
<br>

## Introduction

In {{ config('app.name') }}, you can handle validating data using the `validate` helper. 

It contains some useful [validation rules](#validation-rules) you can use in your project. 

If you need to add custom validation rules, you can do that too. In this section, we'll give an overview of how validation works in {{ config('app.name') }}.

<a name="validating-data"></a>
<br>

## Validating Data

In your project, you will often need to validate data. For example, you may need to validate a user's email address or phone number when they sign up to your app.

You can use the `validate` helper to validate data. 

``` dart 
class _ExampleState extends NyState<ExamplePage> {
  ...

  handleFormSuccess() {
    String textFieldPass = 'agordon@web.com';

    validate(rules: {
      "email address": [textFieldPass, "email"]
    }, onSuccess: () {
      print('looks good');
      // do something...
    });
    
    // or like this
    
    validate(rules: {
      "email address": "email" // validation rule 'email'
    }, data: {
      "email address": textFieldPass
    }, onSuccess: () {
      print('looks good');
      // do something...
    });
  }
}
```

When the validation passes, the `onSuccess` callback will be called.

If the validation fails, the `onFailure` callback will be called. 

``` dart
class _ExampleState extends NyState<ExamplePage> {
  ...
   handleFormFail() {
    String textFieldFail = 'agordon.dodgy.data';

    validate(rules: {
      "email address": [textFieldFail, "email"]
    }, onSuccess: () {
      // onSuccess would not be called
      print("success...");
    }, onFailure: (Exception exception) {
      /// handle the validation error
      print("failed validation");
    }, showAlert: false);
  }
```

> When the validation fails, a toast notification will be displayed to the user. You can override this by setting the `showAlert` parameter to false.


**Example using the `phone_number_uk` validation rule**

``` dart
class _ExampleState extends NyState<ExamplePage> {
  TextEditingController _textFieldController = TextEditingController();
  ...

  handleForm() {
    String textFieldValue = _textFieldController.text;

    validate(rules: {
      "phone number": [textFieldValue, "phone_number_uk"] // validation rule 'phone_number_uk'
    }, onSuccess: () {
      print('looks good');
      // do something...
    });
  }
}
```

**Example using the `contains` validation rule**

``` dart 
class _ExampleState extends NyState<ExamplePage> {
  ...

  handleForm() {
    String carModel = 'ferrari';

    validate(rules: {
      "car model": [carModel, "contains:lamborghini,ferrari"] // validation rule 'contains'
    }, onSuccess: () {
      print("Success! It's a ferrari or lamborghini");
      // do something...
    }, onFailure: (Exception exception) {
        print('No match found');
    });
  }
}
```

**Options:**
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


<a name="multiple-validation-rules"></a>
<br>

## Multiple Validation Rules

You can pass multiple validation rules into the `validate` helper. 

``` dart
validate(rules: {
  "email address": ["john.mail@gmail.com", "email|max:10"] 
  // checks data is an email and maximum of 10 characters
}, onSuccess: () { 
    print("Success! It's a valid email and maximum of 10 characters");
});
```

You can also pass multiple validation rules into the `validate` helper like this:

``` dart
validate(rules: {
  "email address": ["anthony@mail.com", "email|max:10|lowercase"], 
  // checks data is an email, lowercased and maximum of 10 characters
  "phone number": ["123456", "phone_number_uk"] 
  // checks data is a UK phone number
}, onSuccess: () { 
    print("Success! It's a valid email, lowercased and maximum of 10 characters");
}, onFailure: (Exception exception) {
    print('No match found');
});
```

<a name="validating-text-fields"></a>
<br>

## Validating Text Fields

You can validate Text Fields by using the `NyTextField` widget. 

Use the `validationRules` parameter to pass your validation rules into the TextField.

``` dart
NyTextField(
  controller: _textEditingController, 
  validationRules: "not_empty|postcode_uk"
)
```

<a name="validation-checks"></a>
<br>

## Validation checks

If you need to perform a validation check on data, you can use the `NyValidator.isSuccessful()` helper.

``` dart
String helloWorld = "HELLO WORLD";

bool isSuccessful = NyValidator.isSuccessful(
    rules: {
        "Test": [helloWorld, "uppercase|max:12"]
    }
);

if (isSuccessful) {
    print("Success! It's a valid");
}
```

This will return a boolean value. If the validation passes, it will return `true` and `false` if it fails.

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
| <a href="#validation-rule-date-age-is-younger">Date age is younger</a>  | date_age_is_younger:18  | Checks if the date is younger than a age |
| <a href="#validation-rule-date-age-is-older">Date age is older</a>  | date_age_is_older:30  | Checks if the date is older than a age |
| <a href="#validation-rule-date-in-past">Date in past</a>  | date_in_past  | Check if a date is in the past |
| <a href="#validation-rule-date-in-future">Date in future</a>  | date_in_future  | Check if a date is in the future |
| <a href="#validation-rule-is-true">Is True</a>  | is_true  | Checks if a value is true |
| <a href="#validation-rule-is-false">Is False</a>  | is_false  | Checks if a value is false |
| <a href="#validation-rule-password-v1">Password v1</a>  | password_v1  | Checks for a password that contains:<br> - At least one upper case letter<br>- At least one digit<br>- Minimum of 8 characters  |
| <a href="#validation-rule-password-v2">Password v2</a>  | password_v2  | Checks for a password that contains:<br> - At least one upper case letter<br>- At least one digit<br>- Minimum of 8 characters<br>- At least one special character  |


---

<a name="validation-rule-email"></a>
<br>

### email

This allows you to validate if the input is an email.

Usage: `email`

``` dart
String email = "agordon@mail.com";

validate(rules: {
  "Email": [email, "email"]
}, onSuccess: () {
    print("Success! The input is an email");
});
```


<a name="validation-rule-boolean"></a>
<br>

### boolean

This allows you to validate if the input is a boolean.

Usage: `boolean`

``` dart
bool isTrue = true;

validate(rules: {
  "Is True": [isTrue, "boolean"]
}, onSuccess: () {
    print("Success! The input is a boolean");
});
```

<a name="validation-rule-contains"></a>
<br>

### contains

Check if the input contains a particular value.

Usage: `contains:dog,cat`

``` dart
String favouriteAnimal = "dog";
validate(rules: {
  "Favourite Animal": [favouriteAnimal, "contains:dog,cat"]
}, onSuccess: () {
    print("Success! The input contains dog or cat");
});
```

<a name="validation-rule-url"></a>
<br>

### url

Check if the input is a URL.

Usage: `url`

``` dart
String url = "https://www.google.com";
validate(rules: {
  "Website": [url, "url"]
}, onSuccess: () {
    print("Success! The URL is valid");
});
```

<a name="validation-rule-min"></a>
<br>

### min

Check if the input is a minimum of characters.

Usage: `min:7` - will fail if the user's input is less than 7 characters.

``` dart
// String
String password = "Password1";
validate(rules: {
  "Password": [password, "min:3"]
}, onSuccess: () {
    print("Success! The password is more than 3 characters");
});

// List
List<String> favouriteCountries = ['Spain', 'USA', 'Canada'];
validate(rules: {
  "Favourite Countries": [favouriteCountries, "min:2"]
}, onSuccess: () {
    print("Success! You have more than 2 favourite countries");
});

// Integer/Double
int age = 21;
validate(rules: {
  "Age": [age, "min:18"]
}, onSuccess: () {
    print("Success! You are more than 18 years old");
});
```

<a name="validation-rule-max"></a>
<br>

### max

Check if the input is a maximum of characters.

Usage: `max:10` - will fail if the user's input is more than 10 characters.

``` dart
// String
String password = "Password1";
validate(rules: {
  "Password": [password, "max:10"]
}, onSuccess: () {
    print("Success! The password is less than 10 characters");
});

// List
List<String> favouriteCountries = ['Spain', 'USA', 'Canada'];
validate(rules: {
  "Favourite Countries": [favouriteCountries, "max:4"]
}, onSuccess: () {
    print("Success! You have less than 4 favourite countries");
});

// Integer/Double
int age = 18;
validate(rules: {
  "Age": [age, "max:21"]
}, onSuccess: () {
    print("Success! You are less than 18 years old");
});
```

<a name="validation-rule-not-empty"></a>
<br>

### Not Empty

Check if the input is not empty.

Usage: `not_empty` - will fail if the user's input is empty.

``` dart
String score = "10";
validate(rules: {
  "Score": [score, "not_empty"]
}, onSuccess: () {
    print("Success! The input is not empty");
});
```

<a name="validation-rule-regex"></a>
<br>

### Regex

Check the input against a regex pattern.

Usage: `r'regex:([0-9]+)'` - will fail if the user's input does not match the regex pattern.

``` dart
String password = "Password1!";
validate(rules: {
  "Password": [password, r'regex:^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^\w\d\s:])([^\s]){8,16}$']
}, onSuccess: () {
    print("Success! The password is valid");   
});
```

<a name="validation-rule-numeric"></a>
<br>

### numeric

Check if the input is a numeric match.

Usage: `numeric` - will fail if the user's input is not numeric.

``` dart
String age = '18';
validate(rules: {
  "Age": [age, "numeric"]
}, onSuccess: () {
    print("Success! The age is a number");
});
```

<a name="validation-rule-date"></a>
<br>

### date

Check if the input is a date, e.g. 2020-02-29.

Usage: `date` - will fail if the user's input is not date.

``` dart
// String
String birthday = '1990-01-01';
validate(rules: {
  "Birthday": [birthday, "date"]
}, onSuccess: () {
    print("Success! The birthday is a valid date");
});

// DateTime
DateTime birthday = DateTime(1990, 1, 1);
validate(rules: {
  "Birthday": [birthday, "date"]
}, onSuccess: () {
    print("Success! The birthday is a valid date");
});
```

<a name="validation-rule-capitalized"></a>
<br>

### capitalized

Check if the input is capitalized, e.g. "Hello world".

Usage: `capitalized` - will fail if the user's input is not capitalized.

``` dart
String helloWorld = "Hello world";
validate(rules: {
  "Hello World": [helloWorld, "capitalized"]
}, onSuccess: () {
    print("Success! The input is capitalized");
});
```

<a name="validation-rule-lowercase"></a>
<br>

### lowercase

Check if the input is lowercase, e.g. "hello world".

Usage: `lowercase` - will fail if the user's input is not lowercased.

``` dart
String helloWorld = "hello world";
validate(rules: {
  "Hello World": [helloWorld, "lowercase"]
}, onSuccess: () {
    print("Success! The input is lowercase");
});
```

<a name="validation-rule-uppercase"></a>
<br>

### uppercase

Check if the input is uppercase, e.g. "HELLO WORLD".

Usage: `uppercase` - will fail if the user's input is not uppercase.

``` dart
String helloWorld = "HELLO WORLD";
validate(rules: {
  "Hello World": [helloWorld, "uppercase"]
}, onSuccess: () {
    print("Success! The input is uppercase");
});
```

<a name="validation-rule-us-phone-number"></a>
<br>

### US Phone Number

Check if the input is a valid US Phone Number, e.g. "123-456-7890".

Usage: `phone_number_us` - will fail if the user's input is not a US phone number.

``` dart
String phoneNumber = '123-456-7890';
validate(rules: {
  "Phone Number": [phoneNumber, "phone_number_us"]
}, onSuccess: () {
    print("Success! The phone number is a valid format");
});
```

<a name="validation-rule-uk-phone-number"></a>
<br>

### UK Phone Number

Check if the input is a valid UK Phone Number, e.g. "07123456789".

Usage: `phone_number_uk` - will fail if the user's input is not a UK phone number.

``` dart
String phoneNumber = '07123456789';
validate(rules: {
  "Phone Number": [phoneNumber, "phone_number_uk"]
}, onSuccess: () {
    print("Success! The phone number is a valid format");
});
```

<a name="validation-rule-us-zipcode"></a>
<br>

### US Zipcode

Check if the input is a valid US Zipcode, e.g. "33125".

Usage: `zipcode_us` - will fail if the user's input is not a US Zipcode.

``` dart
String zipcode = '33120';
validate(rules: {
  "Zipcode": [zipcode, "zipcode_us"]
}, onSuccess: () {
    print("Success! The zipcode is valid");
});
```

<a name="validation-rule-uk-postcode"></a>
<br>

### UK Postcode

Check if the input is a valid UK Postcode, e.g. "B3 1JJ".

Usage: `postcode_uk` - will fail if the user's input is not a UK Postcode.

``` dart
String postcode = 'B3 1JJ';
validate(rules: {
  "Postcode": [postcode, "postcode_uk"]
}, onSuccess: () {
    print("Success! The postcode is valid");
});
```

<a name="validation-rule-date-age-is-younger"></a>
<br>

### Date age is younger

Check if the input is a date and is younger than a certain age, e.g. "18".

Usage: `date_age_is_younger:21` - will fail if the user's input is not a date and is younger than 21.

You can validate against a `DateTime` or a `String`

``` dart
// DateTime
DateTime userBithday = DateTime(2000, 1, 1);
validate(rules: {
  "Birthday": [userBithday, "date_age_is_older:30"]
}, onSuccess: () {
  print("Success! You're younger than 30");
}, onFailure: (Exception exception) {
  print('You are not younger than 30');
});

// String
String userBithday = '2000-01-01';
validate(rules: {
  "Birthday": [userBithday, "date_age_is_older:30"]
}, onSuccess: () {
  print("Success! You're younger than 30");
}, onFailure: (Exception exception) {
  print('You are not younger than 30');
});
```

<a name="validation-rule-date-age-is-older"></a>
<br>

### Date age is older

Check if the input is a date and is older than a certain age, e.g. "18".

Usage: `date_age_is_older:40` - will fail if the user's input is not a date and is older than 40.

You can validate against a `DateTime` or a `String`

``` dart
// DateTime
DateTime userBithday = DateTime(2000, 1, 1);
validate(rules: {
  "Birthday": [userBithday, "date_age_is_older:18"]
}, onSuccess: () {
  print("Success! You're older than 18");
}, onFailure: (Exception exception) {
  print('You are not older than 18');
});

// String
String userBithday = '2000-01-01';
validate(rules: {
  "Birthday": [userBithday, "date_age_is_older:18"]
}, onSuccess: () {
  print("Success! You're older than 18");
}, onFailure: (Exception exception) {
  print('You are not older than 18');
});
```

<a name="validation-rule-date-in-past"></a>
<br>

### Date in past

Check if the input is a date and is in the past.

Usage: `date_in_past` - will fail if the user's input is not a date and is in the past.

``` dart
// String
String birthday = '1990-01-01';
validate(rules: {
  "Birthday": [birthday, "date_in_past"]
}, onSuccess: () {
    print("Success! The birthday is in the past");
});

// DateTime
DateTime birthday = DateTime(2030, 1, 1);
validate(rules: {
  "Coupon Date": [birthday, "date_in_past"]
}, onSuccess: () {
    print("Success! The birthday is in the past");
});
```

<a name="validation-rule-date-in-future"></a>
<br>

### Date in future

Check if the input is a date and is in the future.

Usage: `date_in_future` - will fail if the user's input is not a date and is in the future.

``` dart
// String
String couponDate = '2030-01-01';
validate(rules: {
  "Coupon Date": [couponDate, "date_in_future"]
}, onSuccess: () {
    print("Success! The coupon date is in the future");
});

// DateTime
DateTime couponDate = DateTime(2030, 1, 1);
validate(rules: {
  "Coupon Date": [couponDate, "date_in_future"]
}, onSuccess: () {
    print("Success! The coupon date is in the future");
});
```

<a name="validation-rule-is-true"></a>
<br>

### Is True

Check if the input is true.

Usage: `is_true` - will fail if the user's input is not true.

``` dart
bool hasAgreedToTerms = true;
validate(rules: {
  "Terms of service": [hasAgreedToTerms, "is_true"]
}, onSuccess: () {
    print("Success! You have agreed to the terms of service");
});
```

<a name="validation-rule-is-false"></a>
<br>

### Is False

Check if the input is false.

Usage: `is_false` - will fail if the user's input is not false.

``` dart
bool hasNotifications = false;

validate(rules: {
  "Phone Compatible": [hasNotifications, "is_false"]
}, onSuccess: () {
    // handle the success case
});
```

<a name="validation-rule-password-v1"></a>
<br>

### Password v1

Checks for a password that contains:<br> - At least one upper case letter<br>- At least one digit<br>- Minimum of 8 characters

Usage: `password_v1` - will fail if the user's input is not a valid password.

``` dart
String password = "PrintUp1";
validate(rules: {
  "Password": [password, "password_v1"]
}, onSuccess: () {
    print("Success! The password is valid");
});
```

<a name="validation-rule-password-v2"></a>
<br>

### Password v2

Checks for a password that contains:<br> - At least one upper case letter<br>- At least one digit<br>- Minimum of 8 characters<br>- At least one special character

Usage: `password_v2` - will fail if the user's input is not a valid password.

``` dart
String password = "BlueTab1e!";
validate(rules: {
  "Password": [password, "password_v2"]
}, onSuccess: () {
    print("Success! The password is valid");
});
```

---

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
```

The `handle` method expects a `boolean` return type, if the data passes validation return `true` and `false` if it doesn't.
