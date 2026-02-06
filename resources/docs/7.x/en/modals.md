# Modals

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Creating a Modal](#creating-a-modal "Creating a Modal")
- [Basic Usage](#basic-usage "Basic Usage")
- [Creating a Modal](#creating-a-modal "Creating a Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parameters](#parameters "Parameters")
  - [Actions](#actions "Actions")
  - [Header](#header "Header")
  - [Close Button](#close-button "Close Button")
  - [Custom Decoration](#custom-decoration "Custom Decoration")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} provides a modal system built around **bottom sheet modals**. 

The `BottomSheetModal` class gives you a flexible API for displaying content overlays with actions, headers, close buttons, and custom styling.

Modals are useful for:
- Confirmation dialogs (e.g., logout, delete)
- Quick input forms
- Action sheets with multiple options
- Informational overlays

<div id="creating-a-modal"></div>

## Creating a Modal

You can create a new modal using the Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

This generates two things:

1. **A modal content widget** at `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **A static method** added to your `BottomSheetModal` class in `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

You can then display the modal from anywhere:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

If a modal with the same name already exists, use the `--force` flag to overwrite it:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Basic Usage

Display a modal using `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Creating a Modal

The recommended pattern is to create a `BottomSheetModal` class with static methods for each modal type. The boilerplate provides this structure:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Call it from anywhere:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` is the core method for displaying modals.

<div id="parameters"></div>

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | required | Build context for the modal |
| `child` | `Widget` | required | Main content widget |
| `actionsRow` | `List<Widget>` | `[]` | Action widgets displayed in a horizontal row |
| `actionsColumn` | `List<Widget>` | `[]` | Action widgets displayed vertically |
| `height` | `double?` | null | Fixed height for the modal |
| `header` | `Widget?` | null | Header widget at the top |
| `useSafeArea` | `bool` | `true` | Wrap content in SafeArea |
| `isScrollControlled` | `bool` | `false` | Allow modal to be scrollable |
| `showCloseButton` | `bool` | `false` | Show an X close button |
| `headerPadding` | `EdgeInsets?` | null | Padding when header is present |
| `backgroundColor` | `Color?` | null | Modal background color |
| `showHandle` | `bool` | `true` | Show the drag handle at the top |
| `closeButtonColor` | `Color?` | null | Close button background color |
| `closeButtonIconColor` | `Color?` | null | Close button icon color |
| `modalDecoration` | `BoxDecoration?` | null | Custom decoration for the modal container |
| `handleColor` | `Color?` | null | Color of the drag handle |

<div id="actions"></div>

### Actions

Actions are buttons displayed at the bottom of the modal.

**Row actions** are placed side by side, each taking equal space:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Column actions** are stacked vertically:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Header

Add a header that sits above the main content:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Close Button

Display a close button in the top-right corner:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Custom Decoration

Customize the modal container's appearance:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` configures the appearance of bottom sheet modals used by form pickers and other components:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Property | Type | Description |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | Background color of the modal |
| `barrierColor` | `NyColor?` | Color of the overlay behind the modal |
| `useRootNavigator` | `bool` | Use root navigator (default: `false`) |
| `routeSettings` | `RouteSettings?` | Route settings for the modal |
| `titleStyle` | `TextStyle?` | Style for title text |
| `itemStyle` | `TextStyle?` | Style for list item text |
| `clearButtonStyle` | `TextStyle?` | Style for clear button text |

<div id="examples"></div>

## Examples

### Confirmation Modal

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### Scrollable Content Modal

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Action Sheet

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
