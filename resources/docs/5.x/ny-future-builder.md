# NyFutureBuilder

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Customizing the NyFutureBuilder](#customizing-the-nyfuturebuilder "Customizing the NyFutureBuilder")


<div id="introduction"></div>
<br>

## Introduction to NyFutureBuilder

The **NyFutureBuilder** is a helpful widget for handling `Future`'s in your Flutter projects.
It will display a loader while the future is in progress, after the Future completes, it will return the data via the `child` parameter.

Let's dive into some code.

1. We have a Future that returns a String
2. We want to display the data on the UI for our user

``` dart
// 1. Example future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await Future.delayed(Duration(seconds: 3));
  return "John Doe";
}

// 2. Use the NyFutureBuilder widget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: Container(
         child: NyFutureBuilder(future: _findUserName(), child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },),
       ),
    ),
  );
}
```

> This widget will handle the loading on the UI for your users until the future completes.

<div id="customizing-the-nyfuturebuilder"></div>
<br>

## Customizing the NyFutureBuilder

You can pass the following parameters to the `NyFutureBuilder` class to customize it for your needs.

#### Options:

``` dart 
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: NyFutureBuilder(
         future: NyStorage.read("product_name"), 
         child: (context, data) {
            return Text(data);
         },
         loading: CupertinoActivityIndicator(), // change the default loader
         onError: (AsyncSnapshot snapshot) { // handle exceptions thrown from your future.
           print(snapshot.error.toString());
           return Text("Error");
         },
       )
    ),
  );
}
```
