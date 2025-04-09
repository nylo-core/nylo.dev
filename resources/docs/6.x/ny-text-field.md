# NyTextField

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Validation](#validation "Validation")
  - [Validation error message](#validation-error-message "Validation error message")
- [Faking data](#faking-data "Faking data")
- Usage
    - [NyTextField.compact](#usage-nytextfield-compact "Usage NyTextField Compact")
    - [NyTextField.emailAddress](#usage-nytextfield-email-address "Usage NyTextField Email Address")
    - [NyTextField.password](#usage-nytextfield-password "Usage NyTextField Password")


<div id="introduction"></div>
<br>

## Introduction to NyTextField

The `NyTextField` class is a text field widget that provides extra utility.

It provides the additional features:
- Validation
- Handling fake data (e.g. development)

The NyTextField widget behaves like the TextField, but it features the above additional utilities to make handing text fields easier.

<div id="validation"></div>
<br>

## Validation

You can handle validation for your text fields by providing the `validationRules` parameter like in the below example.

``` dart
TextEditingController _textEditingController = TextEditingController();
  
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Container(
         child: NyTextField(
             controller: _textEditingController, 
             validationRules: "not_empty|postcode_uk"
         ),
       ),
    ),
  );
}
```

You can pass your validation rules into the `validationRules` parameter.
See all the available validation rules [here](/docs/6.x/validation#custom-validation-rules).

<div id="validation-error-message"></div>
<br>

### Validation Error Messages

Error messages will be thrown when the validation fails on the text field. 
You can update the error message by setting the `validationErrorMessage` parameter. All you need to do is pass the message you want to display when an error occurs.

``` dart
TextEditingController _textEditingController = TextEditingController();
  
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Container(
         child: NyTextField(
             controller: _textEditingController, 
             validationRules: "not_empty|postcode_uk",
             validationErrorMessage: "Data is not valid"
         ),
       ),
    ),
  );
}
```

<div id="faking-data"></div>
<br>

## Faking data

When testing/developing your application, you may want to display some fake dummy data inside your text fields to speed up development. 

First make sure your `.env` file is set to 'developing' mode.

``` dart
// .env
APP_ENV="developing"
...
```

You can set the `dummyData` parameter to populate fake data.

``` dart
TextEditingController _textEditingController = TextEditingController();
  
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Container(
         child: NyTextField(
             controller: _textEditingController, 
             validationRules: "not_empty|postcode_uk",
             dummyData: "B3 1JJ" // This value will be displayed
         ),
       ),
    ),
  );
}
```

If you need to dynamically set **dummyData**, try a package like <a target="_BLANK" href="https://pub.dev/packages/faker">faker</a>.

<div id="usage-nytextfield-compact"></div>
<br>

## Usage NyTextField Compact

The `NyTextField.compact` widget is a helpful widget for handling text fields in your Flutter projects.

It will display a compact text field, styled by the Nylo team.

Here's how you can start using the `NyTextField.compact` widget.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
... 
final TextEditingController myTextField = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Column(
         children: [
            NyTextField.compact(controller: myTextField)
         ],
       ),
    ),
  );
}
```

<div id="usage-nytextfield-email-address"></div>
<br>

## Usage NyTextField Email Address

The `NyTextField.emailAddress` widget is a helpful widget for handling email address text fields in your Flutter projects.

Here's how you can start using the `NyTextField.emailAddress` widget.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
... 
final TextEditingController myTextField = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Column(
         children: [
            NyTextField.emailAddress(controller: myTextField)
         ],
       ),
    ),
  );
}
```

<div id="usage-nytextfield-password"></div>
<br>

## Usage NyTextField Password

The `NyTextField.password` widget is a helpful widget for handling password text fields in your Flutter projects.

Here's how you can start using the `NyTextField.password` widget.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...
final TextEditingController myTextField = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Column(
         children: [
            NyTextField.password(controller: myTextField)
         ],
       ),
    ),
  );
}
```
