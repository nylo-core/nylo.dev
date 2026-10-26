# 截图

<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [简介](#introduction "截图简介")
- [截取屏幕](#capturing-the-screen "截取整个屏幕")
- [截取区域](#capturing-an-area "使用 ScreenshotArea 截取屏幕的一部分")
- [保存截图](#saving-a-screenshot "将截图保存到磁盘")
- [应用商店列表](#store-listings "为 App Store 和 Google Play 调整截图尺寸")
  - [商店尺寸](#store-sizes "ScreenshotStore 尺寸")
- [隐藏内容](#hiding-content "在截图中隐藏内容")
- [截取组件](#capturing-a-widget "不显示组件而直接截取")
- [区域设置与主题](#locales-and-themes "在另一种区域设置或主题下截取")
- [通过 Metro](#from-metro "通过 Metro 截图")

<div id="introduction"></div>

## 简介

<x-doc-strip label="需要" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` 会截取应用屏幕或其中一部分的 PNG：

``` dart
// 整个屏幕，包括对话框和底部弹窗
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// 屏幕的某一部分
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

截取在所有构建模式下都可使用，因此可以为应用中的功能提供支持，例如分享收据或将屏幕附加到错误报告。[Metro](#from-metro) 和[剧本](/docs/{{ $version }}/playbooks#screenshots)使用的是同一套代码。

`Screenshot` 以 `bytes` 的形式保存 PNG，其 `width` 和 `height` 以物理像素为单位。

<div id="capturing-the-screen"></div>

## 截取屏幕

`Screenshot.capture()` 会按用户所看到的样子读取应用的整个屏幕：

``` dart
final Screenshot shot = await Screenshot.capture();
```

| 参数 | 描述 |
|-----------|-------------|
| `pixelRatio` | 相对于逻辑像素的大小。默认为设备自身的值，因此 PNG 具有屏幕的原生分辨率 |
| `settle` | 截取前等待下一帧（默认为 `true`）。刚绘制完一帧时请传入 `false` |
| `locale` | 在截取期间以此语言显示屏幕，例如 `'es'` |
| `theme` | 在截取期间以此主题 id 显示屏幕，例如 `'dark'` |

使用 `NyApp.materialApp` 构建的应用恰好截取其页面。如果您构建自己的 `MaterialApp`，请在其 `builder` 中添加 `ScreenshotRoot`：

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## 截取区域

将屏幕的一部分包裹在 `ScreenshotArea` 中，并为其命名：

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

然后按该名称截取：

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` 接受与 `capture` 相同的 `pixelRatio`、`settle`、`locale` 和 `theme` 参数。`Screenshot.areas` 会列出屏幕上各区域的名称。

<div id="saving-a-screenshot"></div>

## 保存截图

`store` 会将 PNG 保存到文件存储磁盘，并返回路径：

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

不指定 `disk` 时，会保存到默认磁盘。若要分享或上传图片，请读取 `shot.bytes`。

<div id="store-listings"></div>

## 应用商店列表

`forStore` 会返回一张尺寸恰好符合应用商店要求的新截图：

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

形状几乎相同的屏幕会填满该尺寸，其他屏幕则会被适配到其中。使用 `frame: true` 时，屏幕会绘制在手机轮廓内，`caption` 位于其上方。

| 参数 | 描述 |
|-----------|-------------|
| `frame` | 将屏幕绘制在手机轮廓内 |
| `caption` | 手机上方的文字，与 `frame` 一起使用 |
| `background` | 屏幕周围的 `Color` |

使用 `framed()` 可按截图自身的尺寸绘制轮廓：

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### 商店尺寸

| 尺寸 | Id | 像素 |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` 会列出所有尺寸，`ScreenshotStore.named('ios-6.9')` 可按 id 查找某个尺寸。

<div id="hiding-content"></div>

## 隐藏内容

将任何私密内容包裹在 `ScreenshotHidden` 中。它在屏幕上照常显示，而在每张截图中都会被一个灰色块覆盖：

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

传入 `replacement` 可绘制您自己的占位符，而不是灰色块。

<div id="capturing-a-widget"></div>

## 截取组件

`Screenshot.widget` 可以不显示组件而直接截取它，例如专为图片构建的分享卡片：

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

PNG 的大小与组件相同，因此请将其包裹在 `SizedBox` 中以获得精确的尺寸。传入 `context` 可使组件采用该 context 的主题和本地化，传入 `size` 可设置其布局所在的空间，传入 `delay` 可给图片留出加载时间。

<div id="locales-and-themes"></div>

## 区域设置与主题

传入 `locale` 或 `theme`，即可在一次截取中以另一种方式显示屏幕：

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

应用会就地切换，保留页面、其状态和导航堆栈，并在随后立即切换回来。不会保存任何内容，因此用户自己的选择会被保留。区域设置需要在资源中有语言文件，主题需要已注册的主题 id。

<div id="from-metro"></div>

## 通过 Metro

在终端中保存运行中应用的截图：

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

所有选项请参阅 [Metro Live](/docs/{{ $version }}/metro-live#screenshots)。[剧本](/docs/{{ $version }}/playbooks#screenshots)可以对整个流程在每种语言和主题下截图。
