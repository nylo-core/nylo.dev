# NyPullToRefresh

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Usage
    - [NyPullToRefresh](#usage-nypulltorefresh "Usage NyPullToRefresh")
    - [NyPullToRefresh.separated](#usage-nypulltorefresh-separated "Usage NyPullToRefresh Separated")
    - [NyPullToRefresh.grid](#usage-nypulltorefresh-grid "Usage NyPullToRefresh Grid")
- [Parameters](#parameters "Parameters")
- [Updating The State](#updating-the-state "Updating The State")


<a name="introduction"></a>
<br>

## Introduction

In this section, we will learn about the `NyPullToRefresh` widget.

The `NyPullToRefresh` widget is a helpful widget for handling 'pull to refresh' in your Flutter projects.

If you're not familiar with 'pull to refresh', it's essentially a `ListView` that can fetch more data when a user scrolls to the bottom of the list.

This makes it a great option for those with big data because you'll be able to paginate through the data in chunks.

Let's dive into some code.

<a name="usage-nypulltorefresh"></a>
<br>

## Usage NyPullToRefresh

The `NyPullToRefresh` widget is a helpful widget for handling 'pull to refresh' lists in your Flutter projects.

Here's how you can start using the `NyPullToRefresh` widget.

``` dart
@override
Widget build(BuildContext context) {
 return NyPullToRefresh(
    child: (context, data) {
        return ListTile(title: Text(data['title']));
    },
    data: (int iteration) async {
        return [
            {"title": "Clean Room"},
            {"title": "Go to the airport"},
            {"title": "Buy new shoes"},
            {"title": "Go shopping"},
            {"title": "Find my keys"},
            {"title": "Clear the garden"}
        ].paginate(itemsPerPage: 2, page: iteration).toList();
    },
 );
}

// or from an API Service
// this example uses the Separated ListView, it will add a divider between each item
@override
  Widget build(BuildContext context) {
    return NyPullToRefresh.separated(
        child: (context, data) {
            return ListTile(title: Text(data.title));
        },
        data: (int iteration) async {
            // Example: List<Todo> returned from an APIService
            // the iteration parameter can be used for pagination
            // each time the user pulls to refresh, the iteration will increase by 1
            return api<ApiService>((request) => request.getListOfTodos(), page: iteration);
        },
        separatorBuilder: (context, index) {
            return Divider();
        },
        stateName: "todo_list_view",
    );
  }
```

When the returned data is an empty array, it will stop the pagination.

<a name="usage-nypulltorefresh-separated"></a>
<br>

## Usage NyPullToRefresh Separated

The `NyPullToRefresh.separated` widget is a helpful widget for handling 'pull to refresh' lists with dividers in your Flutter projects.

Here's how you can start using the `NyPullToRefresh.separated` widget.

``` dart
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyPullToRefresh.separated(
            child: (BuildContext context, dynamic data) {
                return ListTile(title: Text(data['title']));
            },
            data: (int iteration) async {
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
        child: NyPullToRefresh.separated(
            child: (BuildContext context, dynamic data) {
                return ListTile(
                    title: Text(data['title']),
                    subtitle: Text(data['completed'].toString())
                );
            },
            data: (int iteration) async {
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

The `NyPullToRefresh.separated` widget requires three parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.
- **separatorBuilder** - This is the widget that will be displayed between each item in the list.

<a name="usage-nypulltorefresh-grid"></a>
<br>

## Usage NyPullToRefresh Grid

The `NyPullToRefresh.grid` widget is a helpful widget for handling 'pull to refresh' lists in a grid format in your Flutter projects.

Here's how you can start using the `NyPullToRefresh.grid` widget.

``` dart
@override
Widget build(BuildContext context) {
return Scaffold(
    body: SafeArea(
        child: NyPullToRefresh.grid(
            child: (BuildContext context, dynamic data) {
                return ListTile(title: Text(data['title']));
            },
            data: (int iteration) async {
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
        child: NyPullToRefresh.grid(
            child: (BuildContext context, dynamic data) {
                return ListTile(
                    title: Text(data['title']),
                    subtitle: Text(data['completed'].toString())
                );
            },
            data: (int iteration) async {
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

The `NyPullToRefresh.grid` widget requires two parameters:
- **child** - This is the widget that will be displayed for each item in the list.
- **data** - This is the data that will be displayed in the list.

The `NyPullToRefresh.grid` widget also has some optional parameters:
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
| data | Future Function(int iteration) data | The list of data you want the list view to use. |
| stateName | String? stateName | You can name the state using `stateName`, later you will need this key to update the state. |
| useSkeletonizer | bool useSkeletonizer | Enable loading using the skeletonizer effect |

If you would like to know all the parameters available, visit this link [here](https://github.com/nylo-core/support/blob/5.x/lib/widgets/ny_pull_to_refresh.dart). 

<a name="updating-the-state"></a>
<br>

## Updating the State

You can update the state of a `NyPullToRefresh` widget by referencing the `stateName` parameter.

``` dart
_updateListView() {
    updateState("todo_list_view");
}
```

This will trigger the State to reboot and load fresh data from the `data` parameter.
