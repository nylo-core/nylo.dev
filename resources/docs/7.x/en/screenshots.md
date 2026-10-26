# Screenshots

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction to Screenshots")
- [Capturing the Screen](#capturing-the-screen "Capturing the whole screen")
- [Capturing an Area](#capturing-an-area "Capturing one part of the screen with ScreenshotArea")
- [Saving a Screenshot](#saving-a-screenshot "Saving a screenshot to a disk")
- [Store Listings](#store-listings "Sizing screenshots for App Store and Google Play")
  - [Store Sizes](#store-sizes "ScreenshotStore sizes")
- [Hiding Content](#hiding-content "Hiding content from screenshots")
- [Capturing a Widget](#capturing-a-widget "Capturing a widget without showing it")
- [Locales and Themes](#locales-and-themes "Capturing in another locale or theme")
- [From Metro](#from-metro "Taking screenshots from Metro")

<div id="introduction"></div>

## Introduction

<x-doc-strip label="Requires" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` takes a PNG of your app's screen, or of one part of it:

``` dart
// The whole screen, dialogs and sheets included
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// One part of the screen
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

Captures work in every build mode, so they can power features in your app, like sharing a receipt or attaching the screen to a bug report. [Metro](#from-metro) and [playbooks](/docs/{{ $version }}/playbooks#screenshots) use the same code.

A `Screenshot` holds the PNG as `bytes`, with its `width` and `height` in physical pixels.

<div id="capturing-the-screen"></div>

## Capturing the Screen

`Screenshot.capture()` reads the app's whole screen as the user sees it:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parameter | Description |
|-----------|-------------|
| `pixelRatio` | The size relative to logical pixels. Defaults to the device's, so the PNG has the screen's native resolution |
| `settle` | Wait for the next frame before capturing (default `true`). Pass `false` when a frame has just been drawn |
| `locale` | Show the screen in this language for the capture, e.g. `'es'` |
| `theme` | Show the screen in this theme id for the capture, e.g. `'dark'` |

An app built with `NyApp.materialApp` captures exactly its pages. If you build your own `MaterialApp`, add a `ScreenshotRoot` to its `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Capturing an Area

Wrap part of a screen in a `ScreenshotArea` and give it a name:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Then capture it by that name:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` takes the same `pixelRatio`, `settle`, `locale` and `theme` parameters as `capture`. `Screenshot.areas` lists the names of the areas on screen.

<div id="saving-a-screenshot"></div>

## Saving a Screenshot

`store` saves the PNG to a file storage disk and returns the path:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Without `disk`, it saves to the default disk. To share the image or upload it, read `shot.bytes`.

<div id="store-listings"></div>

## Store Listings

`forStore` returns a new screenshot at the exact size an app store asks for:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

A screen of nearly the same shape fills the size, and any other is fitted inside it. With `frame: true`, the screen is drawn in a phone outline under the `caption`.

| Parameter | Description |
|-----------|-------------|
| `frame` | Draw the screen inside a phone outline |
| `caption` | Text above the phone, used with `frame` |
| `background` | The `Color` around the screen |

Use `framed()` to draw the outline at the screenshot's own size:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Store Sizes

| Size | Id | Pixels |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` lists every size, and `ScreenshotStore.named('ios-6.9')` finds one by its id.

<div id="hiding-content"></div>

## Hiding Content

Wrap anything private in `ScreenshotHidden`. It shows as usual on screen, and a grey block covers it in every screenshot:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Pass `replacement` to draw your own placeholder instead of the grey block.

<div id="capturing-a-widget"></div>

## Capturing a Widget

`Screenshot.widget` captures a widget without showing it, like a share card built just for the image:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

The PNG is as big as the widget, so wrap it in a `SizedBox` for an exact size. Pass `context` to give the widget that context's theme and localizations, `size` to set the space it's laid out in, and `delay` to give images time to load.

<div id="locales-and-themes"></div>

## Locales and Themes

Pass `locale` or `theme` to show the screen another way for one capture:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

The app switches in place, keeping the page, its state and the navigation stack, and goes back straight after. Nothing is saved, so the user's own choices are kept. A locale needs a language file in your assets, and a theme needs a registered theme id.

<div id="from-metro"></div>

## From Metro

Save a screenshot of your running app from the terminal:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

See [Metro Live](/docs/{{ $version }}/metro-live#screenshots) for every option. A [playbook](/docs/{{ $version }}/playbooks#screenshots) can take screenshots of a whole journey, in every language and theme.
