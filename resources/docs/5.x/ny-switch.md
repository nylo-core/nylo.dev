# NySwitch

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Usage](#usage "Usage")
- [Parameters](#parameters "Parameters")


<a name="introduction"></a>
<br>

## Introduction

In this section, we will learn about the `NySwitch` widget.

This widget can perform a 'switch' statement on the widgets that are passed to it.
It makes it easy to switch between different widgets based on the index.

Let's take a look at some code.

<a name="usage"></a>
<br>

## Usage

``` dart
int _currentIndex = 1;

@override
Widget build(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
            title: Text("Dashboard")
        ),
        bottomNavigationBar: BottomNavigationBar(items: [
            BottomNavigationBarItem(icon: Icon(Icons.account_circle_outlined), label: "Account"),
            BottomNavigationBarItem(icon: Icon(Icons.settings), label: "Settings"),
        ], onTap: (index) {
        setState(() {
            _currentIndex = index;
        });
        }, currentIndex: _currentIndex),
        body: SafeArea(
            child: NySwitch(widgets: [
            AccountTab(),
            SettingsTab(),
            ], indexSelected: _currentIndex),
        ),
    );
}
```

<a name="parameters"></a>
<br>

## Parameters

The `NySwitch` widget requires two parameters:
- **widgets** - This is the list of widgets that will be displayed.
- **indexSelected** - This is the index of the widget that should be displayed.

If you would like to know all the parameters available, visit this link [here](https://github.com/nylo-core/support/blob/{{$version}}/lib/widgets/ny_switch.dart). 

