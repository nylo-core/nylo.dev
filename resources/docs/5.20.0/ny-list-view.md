# NyListView

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
    - [NyListView](#usage-nylistview "Usage NyListView")
    - [NyListView.separated](#usage-nylistview-separated "Usage NyListView Separated")
    - [NyListView.grid](#usage-nylistview-grid "Usage NyListView Grid")
- [Parameters](#parameters "Parameters")
- [Updating The State](#updating-the-state "Updating The State")


<a name="introduction"></a>
<br>

## Introduction

In this section, we will learn about the `NyListView` widget.

The `NyListView` widget is a helpful widget for handling List Views in your Flutter projects.

It works in the same way as the regular `ListView` widget, but it has some extra features that make it easier to use.

Let's take a look at some code.

<a name="usage-nylistview"></a>
<br>

## Usage NyListView

The `NyListView` widget is a helpful widget for handling List Views in your Flutter projects.

Here's how you can start using the `NyListView` widget.

``` dart
@override
Widget build(BuildContext context) {
    return Scaffold(
        body: SafeArea(
        child: NyListView(child: (BuildContext context, dynamic data) {
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
        }))
    );
}
// or from an API
@override
Widget build(BuildContext context) {
    return Scaffold(
        body: SafeArea(
        child: NyListView(child: (BuildContext context, dynamic data) {
            return ListTile(
                title: Text(data['title']),
                subtitle: Text(data['completed'])
            );
        }, data: () async {
            return await api<ApiService>((request) =>
                request.get('https://jsonplaceholder.typicode.com/todos'));
        }))
    );
}
```

The `NyListView` widget requires two parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.


<a name="usage-nylistview-separated"></a>
<br>

## Usage NyListView Separated

The `NyListView.separated` widget is a helpful widget for handling List Views with dividers in your Flutter projects.

Here's how you can start using the `NyListView.separated` widget.

``` dart
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyListView.separated(
            child: (BuildContext context, dynamic data) {
                return ListTile(title: Text(data['title']));
            },
            data: () async {
                return [
                    {"title": "Clean Room"},
                    {"title": "Go to the airport"},
                    {"title": "Buy new shoes"},
                    {"title": "Go shopping"},
                    {"title": "Find my keys"}
                ];
            },
            separatorBuilder: (BuildContext context, int index) {
                return Divider();
            },
        )
    )
);
}
// or from an API
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyListView.separated(
            child: (BuildContext context, dynamic data) {
                return ListTile(
                    title: Text(data['title']),
                    subtitle: Text(data['completed'].toString())
                );
            },
            data: () async {
                return await api<ApiService>((request) =>
                        request.get('https://jsonplaceholder.typicode.com/todos'));
            },
            separatorBuilder: (BuildContext context, int index) {
                return Divider();
            },
        )
    )
);
}
```

The `NyListView.separated` widget requires three parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.
- **separatorBuilder** - This is the widget that will be displayed between each item in the list.

<a name="usage-nylistview-grid"></a>
<br>

## Usage NyListView Grid

The `NyListView.grid` widget is a helpful widget for handling Grid Views in your Flutter projects.

Here's how you can start using the `NyListView.grid` widget.

``` dart
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyListView.grid(
            child: (BuildContext context, dynamic data) {
                return ListTile(title: Text(data['title']));
            },
            data: () async {
                return [
                    {"title": "Clean Room"},
                    {"title": "Go to the airport"},
                    {"title": "Buy new shoes"},
                    {"title": "Go shopping"},
                    {"title": "Find my keys"}
                ];
            },
            crossAxisCount: 2, // The number of rows in the grid
            // mainAxisSpacing: 1.0, // The mainAxis spacing
            // crossAxisSpacing: 1.0, // The crossAxisSpacing
        )
    )
);
}
// or from an API
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyListView.grid(
            child: (BuildContext context, dynamic data) {
                return ListTile(
                    title: Text(data['title']),
                    subtitle: Text(data['completed'].toString())
                );
            },
            data: () async {
                return await api<ApiService>((request) =>
                        request.get('https://jsonplaceholder.typicode.com/todos'));
            },
            crossAxisCount: 2, // The number of rows in the grid
            // mainAxisSpacing: 1.0, // The mainAxis spacing
            // crossAxisSpacing: 1.0, // The crossAxisSpacing
        )
    )
);
}
```

The `NyListView.grid` widget requires two parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.

The `NyListView.grid` widget also has some optional parameters:
- **crossAxisCount** - The number of rows in the grid.
- **mainAxisSpacing** - The mainAxis spacing.
- **crossAxisSpacing** - The crossAxisSpacing.

<a name="parameters"></a>
<br>

## Parameters

Here are some important parameters you should know about before using the `NyPullToRefresh` widget.

| Property | Type | Description |
| --- | --- | --- |
| child | Widget Function(BuildContext context, dynamic data) {} | The child widget that will be displayed when the data is available. |
| data | Future Function() data | The list of data you want the list view to use. |
| stateName | String? stateName | You can name the state using `stateName`, later you will need this key to update the state. |
| useSkeletonizer | bool useSkeletonizer | Enable loading using the skeletonizer effect |

If you would like to know all the parameters available, visit this link [here](https://github.com/nylo-core/support/blob/5.x/lib/widgets/ny_list_view.dart). 

<a name="updating-the-state"></a>
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
