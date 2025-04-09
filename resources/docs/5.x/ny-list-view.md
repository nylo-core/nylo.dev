# NyListView

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Usage](#usage "Usage")
- [Parameters](#parameters "Parameters")
- [Updating The State](#updating-the-state "Updating The State")


<div id="introduction"></div>
<br>

## Introduction

In this section, we will learn about the `NyListView` widget.

The `NyListView` widget is a helpful widget for handling List Views in your Flutter projects.

It works in the same way as the regular `ListView` widget, but it has some extra features that make it easier to use.

Let's take a look at some code.

<div id="usage"></div>
<br>

## Usage

Here's how you can start using the `NyListView`.

``` dart
@override
Widget build(BuildContext context) {
return NyListView(child: (BuildContext context, dynamic data) {
    return ListTile(
        title: Text(data['title'])
    );
}, data: () async {
    return [
    {"title": "Clean Room"},
    {"title": "Go to the airport"},
    {"title": "Buy new shoes"},
    {"title": "Go shopping"},
    {"title": "Find my keys"}
    ];
});
}
```

The `NyListView` widget requires two parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.

> Tip: You can use the `NyListView.separated` widget to add a divider between each item in the list.

<div id="parameters"></div>
<br>

## Parameters

Here are some important parameters you should know about before using the `NyPullToRefresh` widget.

| Property | Type | Description |
| --- | --- | --- |
| child | Widget Function(BuildContext context, dynamic data) {} | The child widget that will be displayed when the data is available. |
| data | Future Function() data | The list of data you want the list view to use. |
| stateName | String? stateName | You can name the state using `stateName`, later you will need this key to update the state. |

If you would like to know all the parameters available, visit this link [here](https://github.com/nylo-core/support/blob/{{$version}}/lib/widgets/ny_list_view.dart). 

<div id="updating-the-state"></div>
<br>

## Updating the State

You can update the state of a `NyListView` widget by referencing the `stateName` parameter.

``` dart
// e.g.
@override
  Widget build(BuildContext context) {
    return NyListView(
        child: (BuildContext context, dynamic data) {
          return ListTile(title: Text(data['title']));
          }, 
        data: () async {
          return  [
            {"title": "Clean Room"}, 
            {"title": "Go to the airport"}, 
            {"title": "Buy new shoes"}, 
            {"title": "Go shopping"},
          ];
        },
      stateName: "my_list_of_todos",
    );
  }


_updateListView() {
    updateState("my_list_of_todos");
}
```

This will trigger the State to reboot and load fresh data from the `data` parameter.
