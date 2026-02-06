# 导航中心

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
  - [创建导航中心](#creating-a-navigation-hub "创建导航中心")
  - [创建导航标签](#creating-navigation-tabs "创建导航标签")
  - [底部导航](#bottom-navigation "底部导航")
    - [底部导航样式](#bottom-nav-styles "底部导航样式")
    - [自定义导航栏构建器](#custom-nav-bar-builder "自定义导航栏构建器")
  - [顶部导航](#top-navigation "顶部导航")
  - [旅程导航](#journey-navigation "旅程导航")
    - [进度样式](#journey-progress-styles "旅程进度样式")
    - [按钮样式](#journey-button-styles "按钮样式")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState 辅助方法](#journey-state-helper-methods "JourneyState 辅助方法")
- [在标签内导航](#navigating-within-a-tab "在标签内导航")
- [标签](#tabs "标签")
  - [为标签添加徽章](#adding-badges-to-tabs "为标签添加徽章")
  - [为标签添加提醒](#adding-alerts-to-tabs "为标签添加提醒")
- [维持状态](#maintaining-state "维持状态")
- [状态操作](#state-actions "状态操作")
- [加载样式](#loading-style "加载样式")
- [创建导航中心](#creating-a-navigation-hub "创建导航中心")

<div id="introduction"></div>

## 简介

导航中心是一个集中的地方，您可以在这里**管理**所有组件的导航。
开箱即用，您可以在几秒钟内创建底部、顶部和旅程导航布局。

让我们**想象**您有一个应用，想要添加底部导航栏并允许用户在应用的不同标签之间导航。

您可以使用导航中心来构建这个功能。

让我们深入了解如何在应用中使用导航中心。

<div id="basic-usage"></div>

## 基本用法

您可以使用以下命令创建导航中心。

``` bash
metro make:navigation_hub base
```

这将在 `resources/pages/` 目录中创建一个 **base_navigation_hub.dart** 文件。

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layouts:
  /// - [NavigationHubLayout.bottomNav] Bottom navigation
  /// - [NavigationHubLayout.topNav] Top navigation
  /// - [NavigationHubLayout.journey] Journey navigation
  NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
    // backgroundColor: Colors.white,
  );

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// Navigation pages
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
    };
  });
}
```

您可以看到导航中心有**两个**标签，Home 和 Settings。

您可以通过向导航中心添加 NavigationTab 来创建更多标签。

首先，您需要使用 Metro 创建一个新组件。

``` bash
metro make:stateful_widget create_advert_tab
```

您也可以一次创建多个组件。

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

然后，您可以将新组件添加到导航中心。

``` dart
  _BaseNavigationHubState() : super(() async {
    return {
      0: NavigationTab(
        title: "Home",
        page: HomeTab(),
        icon: Icon(Icons.home),
        activeIcon: Icon(Icons.home),
      ),
      1: NavigationTab(
         title: "Settings",
         page: SettingsTab(),
         icon: Icon(Icons.settings),
         activeIcon: Icon(Icons.settings),
      ),
      2: NavigationTab(
         title: "News",
         page: NewsTab(),
         icon: Icon(Icons.newspaper),
         activeIcon: Icon(Icons.newspaper),
      ),
    };
  });

import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initalRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

导航中心还有**更多**功能，让我们深入了解一些特性。

<div id="bottom-navigation"></div>

### 底部导航

您可以通过将 **layout** 设置为使用 `NavigationHubLayout.bottomNav` 来更改为底部导航栏布局。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
```

您可以通过设置如下属性来自定义底部导航栏：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        // customize the bottomNav layout properties
    );
```

<div id="bottom-nav-styles"></div>

### 底部导航样式

您可以使用 `style` 参数为底部导航栏应用预设样式。

Nylo 提供了几种内置样式：

- `BottomNavStyle.material()` - 默认 Flutter material 样式
- `BottomNavStyle.glass()` - iOS 26 风格的毛玻璃模糊效果
- `BottomNavStyle.floating()` - 圆角悬浮胶囊式导航栏

#### Glass 样式

Glass 样式创建一种美丽的毛玻璃效果，非常适合现代 iOS 26 风格的设计。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.glass(),
    );
```

您可以自定义 Glass 效果：

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.glass(
        blur: 15.0,                              // Blur intensity
        opacity: 0.7,                            // Background opacity
        borderRadius: BorderRadius.circular(20), // Rounded corners
        margin: EdgeInsets.all(16),              // Float above the edge
        backgroundColor: Colors.white.withValues(alpha: 0.8),
    ),
)
```

#### Floating 样式

Floating 样式创建一种悬浮在底部边缘上方的胶囊形导航栏。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        style: BottomNavStyle.floating(),
    );
```

您可以自定义 Floating 样式：

``` dart
NavigationHubLayout.bottomNav(
    style: BottomNavStyle.floating(
        borderRadius: BorderRadius.circular(30),
        margin: EdgeInsets.symmetric(horizontal: 24, vertical: 16),
        shadow: BoxShadow(
            color: Colors.black.withValues(alpha: 0.1),
            blurRadius: 10,
        ),
        backgroundColor: Colors.white,
    ),
)
```

<div id="custom-nav-bar-builder"></div>

### 自定义导航栏构建器

要完全控制您的导航栏，您可以使用 `navBarBuilder` 参数。

这允许您构建任何自定义组件，同时仍然接收导航数据。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` 对象包含：

| 属性 | 类型 | 描述 |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | 导航栏项目 |
| `currentIndex` | `int` | 当前选中的索引 |
| `onTap` | `ValueChanged<int>` | 点击标签时的回调 |

以下是一个完全自定义的 Glass 导航栏示例：

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **注意：** 使用 `navBarBuilder` 时，`style` 参数将被忽略。

<div id="top-navigation"></div>

### 顶部导航

您可以通过将 **layout** 设置为使用 `NavigationHubLayout.topNav` 来更改为顶部导航栏布局。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav();
```

您可以通过设置如下属性来自定义顶部导航栏：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.topNav(
        // customize the topNav layout properties
    );
```

<div id="journey-navigation"></div>

### 旅程导航

您可以通过将 **layout** 设置为使用 `NavigationHubLayout.journey` 来更改为旅程导航布局。

这非常适合引导流程或多步表单。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        // customize the journey layout properties
    );
```

如果您想使用旅程导航布局，您的**组件**应该使用 `JourneyState`，因为它包含许多辅助方法来帮助您管理旅程。

您可以使用以下命令创建 JourneyState。

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```
这将在 **resources/widgets/** 目录中创建以下文件：`welcome.dart`、`phone_number_step.dart` 和 `add_photos_step.dart`。

然后您可以将新组件添加到导航中心。

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

如果您定义了 `buttonStyle`，旅程导航布局将自动为您处理后退和下一步按钮。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

您也可以在组件中自定义逻辑。

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class WelcomeStep extends StatefulWidget {
  const WelcomeStep({super.key});

  @override
  createState() => _WelcomeStepState();
}

class _WelcomeStepState extends JourneyState<WelcomeStep> {
  _WelcomeStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeStep', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: onNextPressed,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
  }

  /// Check if the journey can continue to the next step
  /// Override this method to add validation logic
  Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
  }

  /// Called when unable to continue (canContinue returns false)
  /// Override this method to handle validation failures
  Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
  }

  /// Called before navigating to the next step
  /// Override this method to perform actions before continuing
  Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
  }

  /// Called after navigating to the next step
  /// Override this method to perform actions after continuing
  Future<void> onAfterNext() async {
    // print('Navigated to the next step');
  }

  /// Called when the journey is complete (at the last step)
  /// Override this method to perform completion actions
  Future<void> onComplete() async {}
}
```

您可以重写 `JourneyState` 类中的任何方法。

<div id="journey-progress-styles"></div>

### 旅程进度样式

您可以使用 `JourneyProgressStyle` 类自定义进度指示器样式。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(
            activeColor: Colors.blue,
            inactiveColor: Colors.grey,
            thickness: 4.0,
        ),
    );
```

您可以使用以下进度样式：

- `JourneyProgressIndicator.none`：不渲染任何内容 - 适用于在特定标签上隐藏指示器。
- `JourneyProgressIndicator.linear`：线性进度指示器。
- `JourneyProgressIndicator.dots`：基于圆点的进度指示器。
- `JourneyProgressIndicator.numbered`：编号步骤进度指示器。
- `JourneyProgressIndicator.segments`：分段进度条样式。
- `JourneyProgressIndicator.circular`：圆形进度指示器。
- `JourneyProgressIndicator.timeline`：时间线样式进度指示器。
- `JourneyProgressIndicator.custom`：使用构建函数的自定义进度指示器。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    );
```

您可以在 `JourneyProgressStyle` 内自定义进度指示器的位置和内边距：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.dots(),
            position: ProgressIndicatorPosition.bottom,
            padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
        ),
    );
```

您可以使用以下进度指示器位置：

- `ProgressIndicatorPosition.top`：进度指示器在屏幕顶部。
- `ProgressIndicatorPosition.bottom`：进度指示器在屏幕底部。

#### 单标签进度样式覆盖

您可以使用 `NavigationTab.journey(progressStyle: ...)` 来覆盖布局级别的 `progressStyle` 对单个标签的设置。没有自己 `progressStyle` 的标签将继承布局默认值。没有布局默认值且没有单标签样式的标签将不显示进度指示器。

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
            progressStyle: JourneyProgressStyle(
                indicator: JourneyProgressIndicator.numbered(),
            ), // overrides the layout default for this tab only
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

<div id="journey-button-styles">
<br>

### 旅程按钮样式

如果您想构建引导流程，可以在 `NavigationHubLayout.journey` 类中设置 `buttonStyle` 属性。

开箱即用，您可以使用以下按钮样式：

- `JourneyButtonStyle.standard`：标准按钮样式，具有可自定义的属性。
- `JourneyButtonStyle.minimal`：仅图标的极简按钮样式。
- `JourneyButtonStyle.outlined`：轮廓按钮样式。
- `JourneyButtonStyle.contained`：填充按钮样式。
- `JourneyButtonStyle.custom`：使用构建函数的自定义按钮样式。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle.linear(),
        buttonStyle: JourneyButtonStyle.standard(
            // Customize button properties
        ),
    );
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` 类包含许多辅助方法来帮助您管理旅程。

要创建新的 `JourneyState`，您可以使用以下命令。

``` bash
metro make:journey_widget onboard_user_dob
```

或者如果您想一次创建多个组件，可以使用以下命令。

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

这将在 **resources/widgets/** 目录中创建以下文件：`welcome.dart`、`phone_number_step.dart` 和 `add_photos_step.dart`。

然后您可以将新组件添加到导航中心。

``` dart
_MyNavigationHubState() : super(() async {
    return {
        0: NavigationTab.journey(
            page: Welcome(),
        ),
        1: NavigationTab.journey(
            page: PhoneNumberStep(),
        ),
        2: NavigationTab.journey(
            page: AddPhotosStep(),
        ),
    };
});
```

如果我们查看 `WelcomeStep` 类，可以看到它继承了 `JourneyState` 类。

``` dart
...
class _WelcomeTabState extends JourneyState<WelcomeTab> {
  _WelcomeTabState() : super(
      navigationHubState: BaseNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('WelcomeTab', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
    );
  }
```

您会注意到 **JourneyState** 类将使用 `buildJourneyContent` 来构建页面的内容。

以下是您可以在 `buildJourneyContent` 方法中使用的属性列表。

| 属性 | 类型 | 描述 |
| --- | --- | --- |
| `content` | `Widget` | 页面的主要内容。 |
| `nextButton` | `Widget?` | 下一步按钮组件。 |
| `backButton` | `Widget?` | 返回按钮组件。 |
| `contentPadding` | `EdgeInsetsGeometry` | 内容的内边距。 |
| `header` | `Widget?` | 头部组件。 |
| `footer` | `Widget?` | 底部组件。 |
| `crossAxisAlignment` | `CrossAxisAlignment` | 内容的交叉轴对齐方式。 |


<div id="journey-state-helper-methods"></div>

### JourneyState 辅助方法

`JourneyState` 类有一些辅助方法，您可以使用它们来自定义旅程的行为。

| 方法 | 描述 |
| --- | --- |
| [`onNextPressed()`](#on-next-pressed) | 当下一步按钮被按下时调用。 |
| [`onBackPressed()`](#on-back-pressed) | 当返回按钮被按下时调用。 |
| [`onComplete()`](#on-complete) | 旅程完成时调用（在最后一步）。 |
| [`onBeforeNext()`](#on-before-next) | 导航到下一步之前调用。 |
| [`onAfterNext()`](#on-after-next) | 导航到下一步之后调用。 |
| [`onCannotContinue()`](#on-cannot-continue) | 旅程无法继续时调用（canContinue 返回 false）。 |
| [`canContinue()`](#can-continue) | 用户尝试导航到下一步时调用。 |
| [`isFirstStep`](#is-first-step) | 如果这是旅程的第一步则返回 true。 |
| [`isLastStep`](#is-last-step) | 如果这是旅程的最后一步则返回 true。 |
| [`goToStep(int index)`](#go-to-step) | 导航到指定步骤索引。 |
| [`goToNextStep()`](#go-to-next-step) | 导航到下一步。 |
| [`goToPreviousStep()`](#go-to-previous-step) | 导航到上一步。 |
| [`goToFirstStep()`](#go-to-first-step) | 导航到第一步。 |
| [`goToLastStep()`](#go-to-last-step) | 导航到最后一步。 |


<div id="on-next-pressed"></div>

#### onNextPressed

`onNextPressed` 方法在下一步按钮被按下时调用。

例如，您可以使用此方法触发旅程中的下一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: onNextPressed, // this will attempt to navigate to the next step
        ),
    );
}
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` 方法在返回按钮被按下时调用。

例如，您可以使用此方法触发旅程中的上一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed, // this will attempt to navigate to the previous step
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` 方法在旅程完成时调用（在最后一步）。

例如，如果此组件是旅程中的最后一步，则会调用此方法。

``` dart
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` 方法在导航到下一步之前调用。

例如，如果您想在导航到下一步之前保存数据，可以在这里执行。

``` dart
Future<void> onBeforeNext() async {
    // E.g. save data here before navigating
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` 方法在这是旅程的第一步时返回 true。

例如，如果这是第一步，您可以使用此方法禁用返回按钮。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly( // Example of disabling the back button
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` 方法在这是旅程的最后一步时返回 true。

例如，如果这是最后一步，您可以使用此方法禁用下一步按钮。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue", // Example updating the next button text
            onPressed: onNextPressed,
        ),
    );
}
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` 方法用于导航到旅程中的特定步骤。

例如，您可以使用此方法导航到旅程中的特定步骤。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Add photos"
            onPressed: () {
                goToStep(2); // this will navigate to the step with index 2
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` 方法用于导航到旅程中的下一步。

例如，您可以使用此方法导航到旅程中的下一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToNextStep(); // this will navigate to the next step
                // Note: this will not trigger the onNextPressed method
            },
        ),
    );
}
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` 方法用于导航到旅程中的上一步。

例如，您可以使用此方法导航到旅程中的上一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: () {
                goToPreviousStep(); // this will navigate to the previous step
            },
        ),
    );
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` 方法在导航到下一步之后调用。


例如，如果您想在导航到下一步之后执行某些操作，可以在这里执行。

``` dart
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="on-cannot-continue"></div>

#### onCannotContinue

`onCannotContinue` 方法在旅程无法继续时调用（canContinue 返回 false）。

例如，如果您想在用户尝试导航到下一步但未填写必填字段时显示错误消息，可以在这里执行。

``` dart
Future<void> onCannotContinue() async {
    showToastSorry(description: "You cannot continue");
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` 方法在用户尝试导航到下一步时调用。

例如，如果您想在导航到下一步之前执行一些验证，可以在这里执行。

``` dart
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    return true;
}
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` 方法用于导航到旅程中的第一步。


例如，您可以使用此方法导航到旅程中的第一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToFirstStep(); // this will navigate to the first step
            },
        ),
    );
}
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` 方法用于导航到旅程中的最后一步。

例如，您可以使用此方法导航到旅程中的最后一步。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: "Continue",
            onPressed: () {
                goToLastStep(); // this will navigate to the last step
            },
        ),
    );
}
```

<div id="navigating-within-a-tab"></div>

## 在标签内导航到组件

您可以使用 `pushTo` 辅助方法在标签内导航到其他组件。

在标签内，您可以使用 `pushTo` 辅助方法导航到另一个组件。

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

您也可以向要导航到的组件传递数据。

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## 标签

标签是导航中心的主要构建模块。

您可以使用 `NavigationTab` 类向导航中心添加标签。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab(
                title: "Home",
                page: HomeTab(),
                icon: Icon(Icons.home),
                activeIcon: Icon(Icons.home),
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

在上面的示例中，我们向导航中心添加了两个标签，Home 和 Settings。

您可以使用不同类型的标签，如 `NavigationTab`、`NavigationTab.badge` 和 `NavigationTab.alert`。

- `NavigationTab.badge` 类用于向标签添加徽章。
- `NavigationTab.alert` 类用于向标签添加提醒。
- `NavigationTab` 类用于添加普通标签。

<div id="adding-badges-to-tabs"></div>

## 为标签添加徽章

我们使向标签添加徽章变得简单。

徽章是向用户显示标签中有新内容的好方法。

例如，如果您有一个聊天应用，您可以在聊天标签中显示未读消息的数量。

要向标签添加徽章，您可以使用 `NavigationTab.badge` 类。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

在上面的示例中，我们向 Chat 标签添加了初始计数为 10 的徽章。

您也可以以编程方式更新徽章计数。

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

默认情况下，徽章计数将被记住。如果您想在每个会话中**清除**徽章计数，可以将 `rememberCount` 设置为 `false`。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.badge(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                initialCount: 10,
                rememberCount: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

<div id="adding-alerts-to-tabs"></div>

## 为标签添加提醒

您可以向标签添加提醒。

有时您可能不想显示徽章计数，但想向用户显示提醒。

要向标签添加提醒，您可以使用 `NavigationTab.alert` 类。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    NavigationHubLayout? layout = NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() async {
        return {
            0: NavigationTab.alert(
                title: "Chats",
                page: ChatTab(),
                icon: Icon(Icons.message),
                activeIcon: Icon(Icons.message),
                alertColor: Colors.red,
                alertEnabled: true,
                rememberAlert: false,
            ),
            1: NavigationTab(
                title: "Settings",
                page: SettingsTab(),
                icon: Icon(Icons.settings),
                activeIcon: Icon(Icons.settings),
            ),
        };
    });
```

这将向 Chat 标签添加红色提醒。

您也可以以编程方式更新提醒。

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="maintaining-state"></div>

## 维持状态

默认情况下，导航中心的状态是被维持的。

这意味着当您导航到一个标签时，该标签的状态会被保留。

如果您想在每次导航到标签时清除其状态，可以将 `maintainState` 设置为 `false`。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="state-actions"></div>

## 状态操作

状态操作是一种从应用中的任何地方与导航中心交互的方式。

以下是一些您可以使用的状态操作：

``` dart
  /// Reset the tab
  /// E.g. MyNavigationHub.stateActions.resetTabState(tab: 0);
  resetTabState({required tab});

  /// Update the badge count
  /// E.g. MyNavigationHub.updateBadgeCount(tab: 0, count: 2);
  updateBadgeCount({required int tab, required int count});

  /// Increment the badge count
  /// E.g. MyNavigationHub.incrementBadgeCount(tab: 0);
  incrementBadgeCount({required int tab});

  /// Clear the badge count
  /// E.g. MyNavigationHub.clearBadgeCount(tab: 0);
  clearBadgeCount({required int tab});
```

要使用状态操作，您可以执行以下操作：

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
// or
MyNavigationHub.stateActions.resetTabState(tab: 0);
```

<div id="loading-style"></div>

## 加载样式

开箱即用，导航中心在标签加载时将显示您的**默认**加载组件（resources/widgets/loader_widget.dart）。

您可以自定义 `loadingStyle` 来更新加载样式。

以下是您可以使用的不同加载样式表：
// normal, skeletonizer, none

| 样式 | 描述 |
| --- | --- |
| normal | 默认加载样式 |
| skeletonizer | 骨架屏加载样式 |
| none | 无加载样式 |

您可以这样更改加载样式：

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

如果您想更新某个样式中的加载组件，可以传递一个 `child` 给 `LoadingStyle`。

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

现在，当标签加载时，将显示文本"Loading..."。

以下是示例：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
     _BaseNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab(
          title: "Home",
          page: HomeTab(),
          icon: Icon(Icons.home),
          activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab(
          title: "Settings",
          page: SettingsTab(),
          icon: Icon(Icons.settings),
          activeIcon: Icon(Icons.settings),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## 创建导航中心

要创建导航中心，您可以使用 [Metro](/docs/{{$version}}/metro)，使用以下命令。

``` bash
metro make:navigation_hub base
```

这将在 `resources/pages/` 目录中创建一个 base_navigation_hub.dart 文件，并将导航中心添加到 `routes/router.dart` 文件中。
