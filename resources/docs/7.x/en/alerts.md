# Alerts

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Basic Usage](#basic-usage "Basic Usage")
- [Built-in Styles](#built-in-styles "Built-in Styles")
- [Showing Alerts from Pages](#from-pages "Showing Alerts from Pages")
- [Showing Alerts from Controllers](#from-controllers "Showing Alerts from Controllers")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Positioning](#positioning "Positioning")
- [Custom Toast Styles](#custom-styles "Custom Toast Styles")
  - [Registering Styles](#registering-styles "Registering Styles")
  - [Creating a Style Factory](#creating-a-style-factory "Creating a Style Factory")
- [AlertTab](#alert-tab "AlertTab")
- [Examples](#examples "Practical Examples")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} provides a toast notification system for displaying alerts to users. It ships with four built-in styles -- **success**, **warning**, **info**, and **danger** -- and supports custom styles through a registry pattern.

Alerts can be triggered from pages, controllers, or anywhere you have a `BuildContext`.

<div id="basic-usage"></div>

## Basic Usage

Show a toast notification using convenience methods in any `NyState` page:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

Or use the global function with a style ID:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Built-in Styles

{{ config('app.name') }} registers four default toast styles:

| Style ID | Icon | Color | Default Title |
|----------|------|-------|---------------|
| `success` | Checkmark | Green | Success |
| `warning` | Exclamation | Orange | Warning |
| `info` | Info icon | Teal | Info |
| `danger` | Warning icon | Red | Error |

These are configured in `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Showing Alerts from Pages

In any page extending `NyState` or `NyBaseState`, use these convenience methods:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### General Toast Method

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Showing Alerts from Controllers

Controllers extending `NyController` have the same convenience methods:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Available methods: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

The global `showToastNotification()` function displays a toast from anywhere you have a `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | required | Build context |
| `id` | `String` | `'success'` | Toast style ID |
| `title` | `String?` | null | Override the default title |
| `description` | `String?` | null | Description text |
| `duration` | `Duration?` | null | How long the toast displays |
| `position` | `ToastNotificationPosition?` | null | Where on screen |
| `action` | `VoidCallback?` | null | Tap callback |
| `onDismiss` | `VoidCallback?` | null | Dismiss callback |
| `onShow` | `VoidCallback?` | null | Show callback |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` encapsulates all data for a toast notification:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Properties

| Property | Type | Default | Description |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Icon widget |
| `title` | `String` | `''` | Title text |
| `style` | `String` | `''` | Style identifier |
| `description` | `String` | `''` | Description text |
| `color` | `Color?` | null | Background color for icon section |
| `action` | `VoidCallback?` | null | Tap callback |
| `dismiss` | `VoidCallback?` | null | Dismiss button callback |
| `onDismiss` | `VoidCallback?` | null | Auto/manual dismiss callback |
| `onShow` | `VoidCallback?` | null | Visible callback |
| `duration` | `Duration` | 5 seconds | Display duration |
| `position` | `ToastNotificationPosition` | `top` | Screen position |
| `metaData` | `Map<String, dynamic>?` | null | Custom metadata |

### copyWith

Create a modified copy of `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Positioning

Control where toasts appear on screen:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Custom Toast Styles

<div id="registering-styles"></div>

### Registering Styles

Register custom styles in your `AppProvider`:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

Or add styles at any time:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Then use it:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Creating a Style Factory

`ToastNotification.style()` creates a `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `icon` | `Widget` | Icon widget for the toast |
| `color` | `Color` | Background color for the icon section |
| `defaultTitle` | `String` | Title shown when none is provided |
| `position` | `ToastNotificationPosition?` | Default position |
| `duration` | `Duration?` | Default duration |
| `builder` | `Widget Function(ToastMeta)?` | Custom widget builder for full control |

### Fully Custom Builder

For complete control over the toast widget:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` is a badge widget for adding notification indicators to navigation tabs. It displays a badge that can be toggled and optionally persisted to storage.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `state` | `String` | required | State name for tracking |
| `alertEnabled` | `bool?` | null | Whether badge shows |
| `rememberAlert` | `bool?` | `true` | Persist badge state to storage |
| `icon` | `Widget?` | null | Tab icon |
| `backgroundColor` | `Color?` | null | Tab background |
| `textColor` | `Color?` | null | Badge text color |
| `alertColor` | `Color?` | null | Badge background color |
| `smallSize` | `double?` | null | Small badge size |
| `largeSize` | `double?` | null | Large badge size |
| `textStyle` | `TextStyle?` | null | Badge text style |
| `padding` | `EdgeInsetsGeometry?` | null | Badge padding |
| `alignment` | `Alignment?` | null | Badge alignment |
| `offset` | `Offset?` | null | Badge offset |
| `isLabelVisible` | `bool?` | `true` | Show badge label |

### Factory Constructor

Create from a `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Examples

### Success Alert After Save

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Interactive Toast with Action

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Bottom-Positioned Warning

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
