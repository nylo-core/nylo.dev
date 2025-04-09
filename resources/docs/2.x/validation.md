# Validation

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to validation")
- [Validation Rules](#validation-rules "Validation Rules")


<div id="introduction"></div>
<br>
## Introduction

In Nylo, we provide a simple way to can start validating your users input and show them feedback through an alert.

We currently have some basic validation rules but we'll be adding more in the future.

Let's take a look how validation works in Nylo.

``` dart 
class ExamplePage extends NyStatefulWidget {
  final ExampleController controller = ExampleController();
  ...
}

class _ExampleState extends NyState<ExamplePage> {
  TextEditingController _textFieldControllerEmail = TextEditingController();
  ...

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    try {
      this.validator(rules: {
          "email address": "email"
        }, data: {
          "email address": textEmail
      });
      // will show alert error if fails the validation and throw exception
    } on ValidationException catch(e) {
      print(e.toString());
    }
  }
}
```

#### Options:
``` dart 
this.validator(
  rules: {
  "email address": "email|max:10" // checks data is an email and maximum of 10 characters
  }, data: {
    "email address": textEmail // data to be validated
  }, message: {
    "email address": "oops|it failed" // first section is title, then add a " | " and then provide the the description
  },
  showAlert: true, // if you want Nylo to display the alert, default : true
  alertStyle: ToastNotificationStyleType.DANGER // choose from SUCCESS, INFO, WARNING and DANGER
);
```

This method is handy if you want to quickly validate the users data and display some feedback to the user.

<div id="validation-rules"></div>
<br>

## Validation Rules

Here's the available validation rules that you can use in Nylo.

| Rule Name   | Usage | Info |
|   :-   |  :  | : |
| email | email  | Checks if the data is a valid email |
| contains   | contains:jeff,cup,example  | Checks if the data contains a value |
| url  | url  | Checks if the data is a valid url |
| boolean  | boolean  | Checks if the data is a valid boolean |
| min  | min:5  | Checks if the data is a minimum of x characters |
| max  | max:11  | Checks if the data is a maximum of x characters |

<br>

---

<a href="#validation-rule-email">email</a> 
<br>

This allows you to validate if the input a email.

Usage: `email`

<br>

<a href="#validation-rule-boolean">boolean</a>
<br>

This allows you to validate if the input is a boolean.

Usage: `boolean`

<br>

<a href="#validation-rule-contains">contains</a>
<br>

Check if the input contains a particular value.

Usage: `contains:dog,cat`

<br>

<a href="#validation-rule-url">url</a>
<br>

Check if the input is a URL.

Usage: `url`

<br>

<a href="#validation-rule-min">min</a>
<br>

Check if the input is a minimum of characters.

Usage: `min:7` - will fail if the users input is less than 7 characters.

<br>

<a href="#validation-rule-max">max</a>

Check if the input is a maximum of characters.

Usage: `max:10` - will fail if the users input is more than than 10 characters.