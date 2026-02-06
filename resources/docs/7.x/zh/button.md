# Button

---

<a name="section-1"></a>
- [介绍](#introduction "介绍")
- [基本用法](#basic-usage "基本用法")
- [可用按钮类型](#button-types "可用按钮类型")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [异步加载状态](#async-loading "异步加载状态")
- [动画样式](#animation-styles "动画样式")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [水波纹样式](#splash-styles "水波纹样式")
- [加载样式](#loading-styles "加载样式")
- [表单提交](#form-submission "表单提交")
- [自定义按钮](#customizing-buttons "自定义按钮")
- [参数参考](#parameters "参数参考")


<div id="introduction"></div>

## 介绍

{{ config('app.name') }} 提供了一个 `Button` 类，包含八种开箱即用的按钮样式。每个按钮都内置支持：

- **异步加载状态** -- 从 `onPressed` 返回一个 `Future`，按钮会自动显示加载指示器
- **动画样式** -- 从 clickable、bounce、pulse、squeeze、jelly、shine、ripple、morph 和 shake 效果中选择
- **水波纹样式** -- 添加 ripple、highlight、glow 或 ink 触摸反馈
- **表单提交** -- 将按钮直接连接到 `NyFormData` 实例

您可以在 `lib/resources/widgets/buttons/buttons.dart` 中找到应用的按钮定义。该文件包含一个 `Button` 类，其中包含每种按钮类型的静态方法，方便您自定义项目的默认值。

<div id="basic-usage"></div>

## 基本用法

在您的 widget 中任意位置使用 `Button` 类。以下是页面中的简单示例：

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

每种按钮类型都遵循相同的模式 -- 传递一个 `text` 标签和一个 `onPressed` 回调。

<div id="button-types"></div>

## 可用按钮类型

所有按钮都通过 `Button` 类使用静态方法访问。

<div id="primary"></div>

### Primary

带有阴影的填充按钮，使用您主题的主色。最适合主要的行动号召元素。

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

带有较柔和表面颜色和微妙阴影的填充按钮。适合与主按钮并列的次要操作。

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

带有边框的透明按钮。适用于不太突出的操作或取消按钮。

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

您可以自定义边框和文字颜色：

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

没有背景或边框的极简按钮。适合内联操作或链接。

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

您可以自定义文字颜色：

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

在文字旁边显示图标的填充按钮。图标默认显示在文字之前。

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

您可以自定义背景颜色：

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

具有线性渐变背景的按钮。默认使用您主题的主色和第三色。

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

您可以提供自定义渐变颜色：

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

具有完全圆角的药丸形按钮。边框半径默认为按钮高度的一半。

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

您可以自定义背景颜色和边框半径：

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

具有背景模糊效果的磨砂玻璃风格按钮。放置在图片或彩色背景上方时效果很好。

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

您可以自定义文字颜色：

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## 异步加载状态

{{ config('app.name') }} 按钮最强大的功能之一是**自动加载状态管理**。当您的 `onPressed` 回调返回一个 `Future` 时，按钮将自动显示加载指示器并禁用交互，直到操作完成。

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

在异步操作运行期间，按钮将显示骨架加载效果（默认）。一旦 `Future` 完成，按钮将恢复到正常状态。

这适用于任何异步操作 -- API 调用、数据库写入、文件上传或任何返回 `Future` 的操作：

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

无需管理 `isLoading` 状态变量、调用 `setState` 或将任何内容包装在 `StatefulWidget` 中 -- {{ config('app.name') }} 为您处理一切。

### 工作原理

当按钮检测到 `onPressed` 返回一个 `Future` 时，它使用 `lockRelease` 机制来：

1. 显示加载指示器（由 `LoadingStyle` 控制）
2. 禁用按钮以防止重复点击
3. 等待 `Future` 完成
4. 将按钮恢复到正常状态

<div id="animation-styles"></div>

## 动画样式

按钮通过 `ButtonAnimationStyle` 支持按压动画。这些动画在用户与按钮交互时提供视觉反馈。您可以在 `lib/resources/widgets/buttons/buttons.dart` 中自定义按钮时设置动画样式。

<div id="anim-clickable"></div>

### Clickable

Duolingo 风格的 3D 按压效果。按钮在按下时向下移动，释放时弹回。最适合主要操作和游戏风格的用户体验。

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

微调效果：

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

按下时缩小按钮，释放时弹回。最适合添加到购物车、点赞和收藏按钮。

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

微调效果：

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

按住按钮时的微妙持续缩放脉冲。最适合长按操作或吸引注意力。

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

微调效果：

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

按下时水平压缩按钮并垂直扩展。最适合有趣和交互式的 UI。

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

微调效果：

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

摇晃的弹性变形效果。最适合有趣的、休闲的或娱乐应用。

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

微调效果：

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

按下时在按钮上扫过的光泽高光。最适合高级功能或您想要引起注意的 CTA。

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

微调效果：

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

从触摸点扩展的增强涟漪效果。最适合 Material Design 强调。

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

微调效果：

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

按下时按钮的边框半径增大，产生变形效果。最适合微妙、优雅的反馈。

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

微调效果：

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

水平抖动动画。最适合错误状态或无效操作 -- 抖动按钮以表示出了问题。

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

微调效果：

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### 禁用动画

使用不带动画的按钮：

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### 更改默认动画

要更改按钮类型的默认动画，请修改您的 `lib/resources/widgets/buttons/buttons.dart` 文件：

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## 水波纹样式

水波纹效果在按钮上提供视觉触摸反馈。通过 `ButtonSplashStyle` 进行配置。水波纹样式可以与动画样式组合以实现分层反馈。

### 可用水波纹样式

| 水波纹 | 工厂方法 | 描述 |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | 从触摸点开始的标准 Material 涟漪 |
| Highlight | `ButtonSplashStyle.highlight()` | 没有涟漪动画的微妙高光 |
| Glow | `ButtonSplashStyle.glow()` | 从触摸点辐射的柔和光晕 |
| Ink | `ButtonSplashStyle.ink()` | 快速墨水飞溅，更快更灵敏 |
| None | `ButtonSplashStyle.none()` | 无水波纹效果 |
| Custom | `ButtonSplashStyle.custom()` | 完全控制水波纹工厂方法 |

### 示例

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

您可以自定义水波纹颜色和不透明度：

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## 加载样式

异步操作期间显示的加载指示器由 `LoadingStyle` 控制。您可以在 buttons 文件中按按钮类型设置它。

### Skeletonizer（默认）

在按钮上显示闪烁骨架效果：

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

显示加载 widget（默认为应用加载器）：

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

保持按钮可见但在加载期间禁用交互：

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## 表单提交

所有按钮都支持 `submitForm` 参数，它将按钮连接到 `NyForm`。点击时，按钮将验证表单并使用表单数据调用您的成功处理程序。

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

`submitForm` 参数接受一个包含两个值的记录：
1. 一个 `NyFormData` 实例（或作为 `String` 的表单名称）
2. 一个接收验证数据的回调

默认情况下，`showToastError` 为 `true`，当表单验证失败时会显示 toast 通知。设置为 `false` 以静默处理错误：

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

当 `submitForm` 回调返回一个 `Future` 时，按钮将自动显示加载状态，直到异步操作完成。

<div id="customizing-buttons"></div>

## 自定义按钮

所有按钮默认值定义在您项目的 `lib/resources/widgets/buttons/buttons.dart` 中。每种按钮类型在 `lib/resources/widgets/buttons/partials/` 中都有对应的 widget 类。

### 更改默认样式

要修改按钮的默认外观，请编辑 `Button` 类：

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### 自定义按钮 Widget

要更改按钮类型的视觉外观，请编辑 `lib/resources/widgets/buttons/partials/` 中对应的 widget。例如，要更改主按钮的边框半径或阴影：

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### 创建新的按钮类型

要添加新的按钮类型：

1. 在 `lib/resources/widgets/buttons/partials/` 中创建一个继承 `StatefulAppButton` 的新 widget 文件。
2. 实现 `buildButton` 方法。
3. 在 `Button` 类中添加一个静态方法。

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

然后在 `Button` 类中注册它：

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## 参数参考

### 通用参数（所有按钮类型）

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `text` | `String` | 必填 | 按钮标签文字 |
| `onPressed` | `VoidCallback?` | `null` | 点击按钮时的回调。返回 `Future` 以启用自动加载状态 |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | 表单提交记录（表单实例，成功回调） |
| `onFailure` | `Function(dynamic)?` | `null` | 表单验证失败时调用 |
| `showToastError` | `bool` | `true` | 表单验证错误时显示 toast 通知 |
| `width` | `double?` | `null` | 按钮宽度（默认全宽） |

### 类型特定参数

#### Button.outlined

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | 主题轮廓颜色 | 边框颜色 |
| `textColor` | `Color?` | 主题主色 | 文字颜色 |

#### Button.textOnly

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | 主题主色 | 文字颜色 |

#### Button.icon

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `icon` | `Widget` | 必填 | 要显示的图标 widget |
| `color` | `Color?` | 主题主色 | 背景颜色 |

#### Button.gradient

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | 主色和第三色 | 渐变色节点 |

#### Button.rounded

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | 主题 primary container 颜色 | 背景颜色 |
| `borderRadius` | `BorderRadius?` | 药丸形状（高度 / 2） | 圆角半径 |

#### Button.transparency

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `color` | `Color?` | 主题自适应 | 文字颜色 |

### ButtonAnimationStyle 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `duration` | `Duration` | 因类型而异 | 动画持续时间 |
| `curve` | `Curve` | 因类型而异 | 动画曲线 |
| `enableHapticFeedback` | `bool` | 因类型而异 | 按下时触发触觉反馈 |
| `translateY` | `double` | `4.0` | Clickable：垂直按压距离 |
| `shadowOffset` | `double` | `4.0` | Clickable：阴影深度 |
| `scaleMin` | `double` | `0.92` | Bounce：按下时的最小缩放 |
| `pulseScale` | `double` | `1.05` | Pulse：脉冲期间的最大缩放 |
| `squeezeX` | `double` | `0.95` | Squeeze：水平压缩 |
| `squeezeY` | `double` | `1.05` | Squeeze：垂直扩展 |
| `jellyStrength` | `double` | `0.15` | Jelly：摇晃强度 |
| `shineColor` | `Color` | `Colors.white` | Shine：高光颜色 |
| `shineWidth` | `double` | `0.3` | Shine：光泽带宽度 |
| `rippleScale` | `double` | `2.0` | Ripple：扩展比例 |
| `morphRadius` | `double` | `24.0` | Morph：目标边框半径 |
| `shakeOffset` | `double` | `8.0` | Shake：水平位移 |
| `shakeCount` | `int` | `3` | Shake：振荡次数 |

### ButtonSplashStyle 参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | 主题 surface 颜色 | 水波纹效果颜色 |
| `highlightColor` | `Color?` | 主题 surface 颜色 | 高光效果颜色 |
| `splashOpacity` | `double` | `0.12` | 水波纹不透明度 |
| `highlightOpacity` | `double` | `0.06` | 高光不透明度 |
| `borderRadius` | `BorderRadius?` | `null` | 水波纹裁剪半径 |
