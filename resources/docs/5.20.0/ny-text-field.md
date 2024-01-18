# NyTextField

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Validation](#validation "Validation")
  - [Validation error message](#validation-error-message "Validation error message")
- [Faking data](#faking-data "Faking data")


<a name="introduction"></a>
<br>

## Introduction to NyTextField

The `NyTextField` class is a text field widget that provides extra utility.

It provides the additional features:
- Validation
- Handling fake data (e.g. development)

The NyTextField widget behaves like the TextField, but it features the above additional utilities to make handing text fields easier.

<a name="validation"></a>
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
See all the available validation rules [here](/docs/5.x/validation#custom-validation-rules).

<a name="validation-error-message"></a>
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

<a name="faking-data"></a>
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
