# FutureWidget

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Customizing the Loading State](#customizing-loading "Customizing the Loading State")
    - [Normal Loading Style](#normal-loading "Normal Loading Style")
    - [Skeletonizer Loading Style](#skeletonizer-loading "Skeletonizer Loading Style")
    - [No Loading Style](#no-loading "No Loading Style")
- [Error Handling](#error-handling "Error Handling")


<div id="introduction"></div>

## Introduction

The **FutureWidget** is a simple way to render `Future`'s in your {{ config('app.name') }} projects. It wraps Flutter's `FutureBuilder` and provides a cleaner API with built-in loading states.

When your Future is in progress, it will display a loader. Once the Future completes, the data is returned via the `child` callback.

<div id="basic-usage"></div>

## Basic Usage

Here's a simple example of using `FutureWidget`:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

The widget will automatically handle the loading state for your users until the Future completes.

<div id="customizing-loading"></div>

## Customizing the Loading State

You can customize how the loading state appears using the `loadingStyle` parameter.

<div id="normal-loading"></div>

### Normal Loading Style

Use `LoadingStyle.normal()` to display a standard loading widget. You can optionally provide a custom child widget:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

If no child is provided, the default {{ config('app.name') }} app loader will be displayed.

<div id="skeletonizer-loading"></div>

### Skeletonizer Loading Style

Use `LoadingStyle.skeletonizer()` to display a skeleton loading effect. This is great for showing placeholder UI that matches your content layout:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Available skeleton effects:
- `SkeletonizerEffect.shimmer` - Animated shimmer effect (default)
- `SkeletonizerEffect.pulse` - Pulsing animation effect
- `SkeletonizerEffect.solid` - Solid color effect

<div id="no-loading"></div>

### No Loading Style

Use `LoadingStyle.none()` to hide the loading indicator entirely:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Error Handling

You can handle errors from your Future using the `onError` callback:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

If no `onError` callback is provided and an error occurs, an empty `SizedBox.shrink()` will be displayed.

### Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `future` | `Future<T>?` | The Future to await |
| `child` | `Widget Function(BuildContext, T?)` | Builder function called when Future completes |
| `loadingStyle` | `LoadingStyle?` | Customize the loading indicator |
| `onError` | `Widget Function(AsyncSnapshot)?` | Builder function called when Future has an error |
