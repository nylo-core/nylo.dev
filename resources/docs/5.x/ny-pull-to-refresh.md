# NyPullToRefresh

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Usage](#usage "Usage")
- [Parameters](#parameters "Parameters")
- [Updating The State](#updating-the-state "Updating The State")


<div id="introduction"></div>

## Introduction

In this section, we will learn about the `NyPullToRefresh` widget.

The `NyPullToRefresh` widget is a helpful widget for handling 'pull to refresh' in your Flutter projects.

If you're not familiar with 'pull to refresh', it's essentially a `ListView` that can fetch more data when a user scrolls to the bottom of the list.

This makes it a great option for those with big data because you'll be able to paginate through the data in chunks.

Let's dive into some code.

<div id="usage"></div>

## Usage

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
        stateName: "todo_list_view",
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

<div id="parameters"></div>

## Parameters

Here are some important parameters you should know about before using the `NyPullToRefresh` widget.

| Property | Type | Description |
| --- | --- | --- |
| child | Widget Function(BuildContext context, dynamic data) {} | The child widget that will be displayed when the data is available. |
| data | Future Function(int iteration) data | The list of data you want the list view to use. |
| stateName | String? stateName | You can name the state using `stateName`, later you will need this key to update the state. |

If you would like to know all the parameters available, visit this link [here](https://github.com/nylo-core/support/blob/{{$version}}/lib/widgets/ny_pull_to_refresh.dart). 

<div id="updating-the-state"></div>

## Updating the State

You can update the state of a `NyPullToRefresh` widget by referencing the `stateName` parameter.

``` dart
_updateListView() {
    updateState("todo_list_view");
}
```

This will trigger the State to reboot and load fresh data from the `data` parameter.
