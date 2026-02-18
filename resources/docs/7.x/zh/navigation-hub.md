# Navigation Hub

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
  - [创建 Navigation Hub](#creating-a-navigation-hub "创建 Navigation Hub")
  - [创建导航标签页](#creating-navigation-tabs "创建导航标签页")
  - [底部导航](#bottom-navigation "底部导航")
    - [自定义导航栏构建器](#custom-nav-bar-builder "自定义导航栏构建器")
  - [顶部导航](#top-navigation "顶部导航")
  - [Journey 导航](#journey-navigation "Journey 导航")
    - [进度样式](#journey-progress-styles "进度样式")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState 辅助方法](#journey-state-helper-methods "JourneyState 辅助方法")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [在标签页内导航](#navigating-within-a-tab "在标签页内导航")
- [标签页](#tabs "标签页")
  - [为标签页添加 Badge](#adding-badges-to-tabs "为标签页添加 Badge")
  - [为标签页添加 Alert](#adding-alerts-to-tabs "为标签页添加 Alert")
- [初始索引](#initial-index "初始索引")
- [状态保持](#maintaining-state "状态保持")
- [onTap](#on-tap "onTap")
- [State Actions](#state-actions "State Actions")
- [加载样式](#loading-style "加载样式")

<div id="introduction"></div>

## 简介

Navigation Hub 是一个集中管理所有组件导航的中心枢纽。开箱即用，你可以在几秒钟内创建底部导航、顶部导航以及 Journey 导航布局。

假设你有一个应用，想要添加一个底部导航栏，让用户可以在应用中的不同标签页之间切换。

你可以使用 Navigation Hub 来构建这个功能。

接下来让我们深入了解如何在你的应用中使用 Navigation Hub。

<div id="basic-usage"></div>

## 基本用法

你可以使用以下命令创建一个 Navigation Hub。

``` bash
metro make:navigation_hub base
```

该命令会引导你完成一个交互式设置流程：

1. **选择布局类型** - 在 `navigation_tabs`（底部导航）和 `journey_states`（顺序流程）之间选择。
2. **输入标签页/状态名称** - 提供逗号分隔的标签页或 Journey 状态名称。

这将在你的 `resources/pages/navigation_hubs/base/` 目录下创建文件：
- `base_navigation_hub.dart` - 主 Hub 组件
- `tabs/` 或 `states/` - 包含每个标签页或 Journey 状态的子组件

以下是生成的 Navigation Hub 的示例：

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

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

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

可以看到，这个 Navigation Hub 有**两个**标签页：Home 和 Settings。

`layout` 方法返回 Hub 的布局类型。它接收一个 `BuildContext`，因此你可以在配置布局时访问主题数据和媒体查询信息。

你可以通过向 Navigation Hub 添加更多 `NavigationTab` 来创建更多标签页。

首先，你需要使用 Metro 创建一个新组件。

``` bash
metro make:stateful_widget news_tab
```

你也可以一次创建多个组件。

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

然后，将新组件添加到 Navigation Hub 中。

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

要使用 Navigation Hub，请将其作为初始路由添加到路由器中：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// 或者从应用中的任何位置导航到 Navigation Hub

routeTo(BaseNavigationHub.path);
```

Navigation Hub 还有**更多**功能，让我们一起来了解一些特性。

<div id="bottom-navigation"></div>

### 底部导航

你可以在 `layout` 方法中返回 `NavigationHubLayout.bottomNav` 来设置底部导航栏布局。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

你可以通过设置以下属性来自定义底部导航栏：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

你可以使用 `style` 参数为底部导航栏应用预设样式。

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // 默认的 Flutter Material 样式
);
```

<div id="custom-nav-bar-builder"></div>

### 自定义导航栏构建器

如果你想完全掌控导航栏，可以使用 `navBarBuilder` 参数。

它允许你构建任何自定义组件，同时仍然可以接收导航数据。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

`NavBarData` 对象包含以下内容：

| 属性 | 类型 | 描述 |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | 导航栏项目 |
| `currentIndex` | `int` | 当前选中的索引 |
| `onTap` | `ValueChanged<int>` | 标签页被点击时的回调 |

以下是一个完全自定义的毛玻璃导航栏示例：

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

你可以在 `layout` 方法中返回 `NavigationHubLayout.topNav` 来将布局更改为顶部导航栏。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

你可以通过设置以下属性来自定义顶部导航栏：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Journey 导航

你可以在 `layout` 方法中返回 `NavigationHubLayout.journey` 来将布局更改为 Journey 导航。

这非常适合用于引导流程或多步表单。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

你也可以为 Journey 布局设置 `backgroundGradient`：

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **注意：** 当设置了 `backgroundGradient` 时，它优先于 `backgroundColor`。

如果你想使用 Journey 导航布局，你的**组件**应该使用 `JourneyState`，因为它包含了大量辅助方法来帮助你管理 Journey 流程。

你可以使用 `make:navigation_hub` 命令并选择 `journey_states` 布局来创建整个 Journey：

``` bash
metro make:navigation_hub onboarding
# 选择：journey_states
# 输入：welcome, personal_info, add_photos
```

这将在 `resources/pages/navigation_hubs/onboarding/states/` 下创建 Hub 和所有 Journey 状态组件。

你也可以使用以下命令单独创建 Journey 组件：

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

然后将新组件添加到 Navigation Hub 中。

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Journey 进度样式

你可以使用 `JourneyProgressStyle` 类来自定义进度指示器样式。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

你可以使用以下进度指示器：

- `JourneyProgressIndicator.none()`：不渲染任何内容 - 适合在特定标签页上隐藏指示器。
- `JourneyProgressIndicator.linear()`：线性进度条。
- `JourneyProgressIndicator.dots()`：基于圆点的进度指示器。
- `JourneyProgressIndicator.numbered()`：数字步骤进度指示器。
- `JourneyProgressIndicator.segments()`：分段式进度条样式。
- `JourneyProgressIndicator.circular()`：圆形进度指示器。
- `JourneyProgressIndicator.timeline()`：时间线样式进度指示器。
- `JourneyProgressIndicator.custom()`：使用构建器函数的自定义进度指示器。

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

你可以在 `JourneyProgressStyle` 中自定义进度指示器的位置和内边距：

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

你可以使用以下进度指示器位置：

- `ProgressIndicatorPosition.top`：进度指示器位于屏幕顶部。
- `ProgressIndicatorPosition.bottom`：进度指示器位于屏幕底部。

#### 单个标签页的进度样式覆盖

你可以使用 `NavigationTab.journey(progressStyle: ...)` 为单个标签页覆盖布局级别的 `progressStyle`。未设置自身 `progressStyle` 的标签页将继承布局的默认设置。既没有布局默认值也没有单独设置的标签页将不会显示进度指示器。

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // 仅为此标签页覆盖布局默认值
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` 类扩展了 `NyState`，添加了 Journey 专属功能，使创建引导流程和多步骤 Journey 变得更加容易。

你可以使用以下命令创建一个新的 `JourneyState`。

``` bash
metro make:journey_widget onboard_user_dob
```

如果你想一次创建多个组件，可以使用以下命令。

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

以下是生成的 JourneyState 组件示例：

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

你会注意到 **JourneyState** 类使用 `nextStep` 来向前导航，使用 `onBackPressed` 来返回。

`nextStep` 方法会经历完整的验证生命周期：`canContinue()` -> `onBeforeNext()` -> 导航（或在最后一步时调用 `onComplete()`）-> `onAfterNext()`。

你也可以使用 `buildJourneyContent` 来构建包含可选导航按钮的结构化布局：

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

以下是 `buildJourneyContent` 方法中可用的属性。

| 属性 | 类型 | 描述 |
| --- | --- | --- |
| `content` | `Widget` | 页面的主要内容。 |
| `nextButton` | `Widget?` | "下一步"按钮组件。 |
| `backButton` | `Widget?` | "返回"按钮组件。 |
| `contentPadding` | `EdgeInsetsGeometry` | 内容的内边距。 |
| `header` | `Widget?` | 头部组件。 |
| `footer` | `Widget?` | 底部组件。 |
| `crossAxisAlignment` | `CrossAxisAlignment` | 内容的交叉轴对齐方式。 |

<div id="journey-state-helper-methods"></div>

### JourneyState 辅助方法

`JourneyState` 类提供了辅助方法和属性，你可以用它们来自定义 Journey 的行为。

| 方法 / 属性 | 描述 |
| --- | --- |
| [`nextStep()`](#next-step) | 带验证地导航到下一步。返回 `Future<bool>`。 |
| [`previousStep()`](#previous-step) | 导航到上一步。返回 `Future<bool>`。 |
| [`onBackPressed()`](#on-back-pressed) | 导航到上一步的简便方法。 |
| [`onComplete()`](#on-complete) | 在 Journey 完成时调用（在最后一步）。 |
| [`onBeforeNext()`](#on-before-next) | 在导航到下一步之前调用。 |
| [`onAfterNext()`](#on-after-next) | 在导航到下一步之后调用。 |
| [`canContinue()`](#can-continue) | 导航到下一步之前的验证检查。 |
| [`isFirstStep`](#is-first-step) | 如果是 Journey 的第一步则返回 true。 |
| [`isLastStep`](#is-last-step) | 如果是 Journey 的最后一步则返回 true。 |
| [`currentStep`](#current-step) | 返回当前步骤索引（从 0 开始）。 |
| [`totalSteps`](#total-steps) | 返回总步骤数。 |
| [`completionPercentage`](#completion-percentage) | 返回完成百分比（0.0 到 1.0）。 |
| [`goToStep(int index)`](#go-to-step) | 按索引跳转到指定步骤。 |
| [`goToNextStep()`](#go-to-next-step) | 跳转到下一步（不验证）。 |
| [`goToPreviousStep()`](#go-to-previous-step) | 跳转到上一步（不验证）。 |
| [`goToFirstStep()`](#go-to-first-step) | 跳转到第一步。 |
| [`goToLastStep()`](#go-to-last-step) | 跳转到最后一步。 |
| [`exitJourney()`](#exit-journey) | 通过弹出根导航器退出 Journey。 |
| [`resetCurrentStep()`](#reset-current-step) | 重置当前步骤的状态。 |
| [`onJourneyComplete`](#on-journey-complete) | Journey 完成时的回调（在最后一步中重写）。 |
| [`buildJourneyPage()`](#build-journey-page) | 构建带有 Scaffold 的全屏 Journey 页面。 |


<div id="next-step"></div>

#### nextStep

`nextStep` 方法带完整验证地导航到下一步。它经历以下生命周期：`canContinue()` -> `onBeforeNext()` -> 导航或 `onComplete()` -> `onAfterNext()`。

你可以传入 `force: true` 来跳过验证直接导航。

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
            onPressed: nextStep, // 先验证再导航
        ),
    );
}
```

跳过验证：

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

`previousStep` 方法导航到上一步。成功返回 `true`，如果已在第一步则返回 `false`。

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // 已经在第一步了
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` 方法是一个简便辅助方法，内部调用 `previousStep()`。

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
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

`onComplete` 方法在最后一步触发 `nextStep()` 时调用（验证通过后）。

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` 方法在导航到下一步之前调用。

例如，如果你想在导航到下一步之前保存数据，可以在这里操作。

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` 方法在导航到下一步之后调用。

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` 方法在触发 `nextStep()` 时调用。返回 `false` 可以阻止导航。

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` 属性在当前是 Journey 的第一步时返回 true。

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` 属性在当前是 Journey 的最后一步时返回 true。

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

`currentStep` 属性返回当前步骤索引（从 0 开始）。

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

`totalSteps` 属性返回 Journey 中的总步骤数。

<div id="completion-percentage"></div>

#### completionPercentage

`completionPercentage` 属性返回完成百分比，值范围为 0.0 到 1.0。

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` 方法按索引直接跳转到指定步骤。此操作**不会**触发验证。

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // 跳转到步骤索引 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` 方法不经过验证直接跳转到下一步。如果已经在最后一步，则不执行任何操作。

``` dart
onPressed: () {
    goToNextStep(); // 跳过验证直接到下一步
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` 方法不经过验证直接跳转到上一步。如果已经在第一步，则不执行任何操作。

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` 方法跳转到第一步。

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` 方法跳转到最后一步。

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

`exitJourney` 方法通过弹出根导航器来退出 Journey。

``` dart
onPressed: () {
    exitJourney(); // 弹出根导航器
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

`resetCurrentStep` 方法重置当前步骤的状态。

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

`onJourneyComplete` getter 可以在 Journey 的**最后一步**中被重写，用于定义用户完成流程后的行为。

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // 触发完成回调
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

`buildJourneyPage` 方法构建一个包裹在 `Scaffold` 和 `SafeArea` 中的全屏 Journey 页面。

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| 属性 | 类型 | 描述 |
| --- | --- | --- |
| `content` | `Widget` | 页面的主要内容。 |
| `nextButton` | `Widget?` | "下一步"按钮组件。 |
| `backButton` | `Widget?` | "返回"按钮组件。 |
| `contentPadding` | `EdgeInsetsGeometry` | 内容的内边距。 |
| `header` | `Widget?` | 头部组件。 |
| `footer` | `Widget?` | 底部组件。 |
| `backgroundColor` | `Color?` | Scaffold 的背景颜色。 |
| `appBar` | `Widget?` | 可选的 AppBar 组件。 |
| `crossAxisAlignment` | `CrossAxisAlignment` | 内容的交叉轴对齐方式。 |

<div id="navigating-within-a-tab"></div>

## 在标签页内导航到组件

你可以使用 `pushTo` 辅助方法在标签页内导航到其他组件。

在你的标签页内部，可以使用 `pushTo` 辅助方法导航到另一个组件。

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

你也可以向目标组件传递数据。

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

## 标签页

标签页是 Navigation Hub 的核心构建块。

你可以使用 `NavigationTab` 类及其命名构造函数来向 Navigation Hub 添加标签页。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

在上面的示例中，我们向 Navigation Hub 添加了两个标签页：Home 和 Settings。

你可以使用不同类型的标签页：

- `NavigationTab.tab()` - 标准导航标签页。
- `NavigationTab.badge()` - 带有 Badge 计数的标签页。
- `NavigationTab.alert()` - 带有 Alert 指示器的标签页。
- `NavigationTab.journey()` - 用于 Journey 导航布局的标签页。

<div id="adding-badges-to-tabs"></div>

## 为标签页添加 Badge

我们让为标签页添加 Badge 变得非常简单。

Badge 是向用户展示标签页中有新内容的好方式。

例如，如果你有一个聊天应用，可以在聊天标签页中显示未读消息数量。

要为标签页添加 Badge，可以使用 `NavigationTab.badge` 构造函数。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

在上面的示例中，我们为 Chat 标签页添加了一个初始计数为 10 的 Badge。

你也可以通过编程方式更新 Badge 计数。

``` dart
/// 增加 Badge 计数
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// 更新 Badge 计数
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// 清除 Badge 计数
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

默认情况下，Badge 计数会被记住。如果你想在每次会话时**清除** Badge 计数，可以将 `rememberCount` 设置为 `false`。

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## 为标签页添加 Alert

你可以为标签页添加 Alert。

有时候你可能不想显示 Badge 计数，但想向用户显示一个 Alert 指示器。

要为标签页添加 Alert，可以使用 `NavigationTab.alert` 构造函数。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

这将为 Chat 标签页添加一个红色的 Alert。

你也可以通过编程方式更新 Alert。

``` dart
/// 启用 Alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// 禁用 Alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## 初始索引

默认情况下，Navigation Hub 从第一个标签页（索引 0）开始。你可以通过重写 `initialIndex` getter 来更改此设置。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // 从第二个标签页开始
    ...
}
```

<div id="maintaining-state"></div>

## 状态保持

默认情况下，Navigation Hub 的状态是保持的。

这意味着当你导航到一个标签页时，该标签页的状态会被保留。

如果你想在每次导航到标签页时清除其状态，可以将 `maintainState` 设置为 `false`。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

你可以重写 `onTap` 方法，在标签页被点击时添加自定义逻辑。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // 务必调用 super 来处理标签页切换
    }
}
```

<div id="state-actions"></div>

## State Actions

State Actions 是一种从应用中任何位置与 Navigation Hub 交互的方式。

以下是你可以使用的 State Actions：

``` dart
/// 重置给定索引处的标签页
/// 例如：MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// 以编程方式切换当前标签页
/// 例如：MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// 更新 Badge 计数
/// 例如：MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// 增加 Badge 计数
/// 例如：MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// 清除 Badge 计数
/// 例如：MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// 启用标签页的 Alert
/// 例如：MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// 禁用标签页的 Alert
/// 例如：MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// 在 Journey 布局中导航到下一页
/// 例如：await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// 在 Journey 布局中导航到上一页
/// 例如：await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

要使用 State Action，你可以按以下方式操作：

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // 切换到标签页 2

await MyNavigationHub.stateActions.nextPage(); // Journey：跳转到下一页
```

<div id="loading-style"></div>

## 加载样式

开箱即用，Navigation Hub 在标签页加载时会显示你的**默认**加载组件（resources/widgets/loader_widget.dart）。

你可以自定义 `loadingStyle` 来更新加载样式。

| 样式 | 描述 |
| --- | --- |
| normal | 默认加载样式 |
| skeletonizer | 骨架屏加载样式 |
| none | 无加载样式 |

你可以这样更改加载样式：

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// 或
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

如果你想在某个样式中更新加载组件，可以向 `LoadingStyle` 传递一个 `child`。

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

现在，当标签页加载时，将显示 "Loading..." 文本。

示例如下：

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
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

## 创建 Navigation Hub

要创建 Navigation Hub，你可以使用 [Metro](/docs/{{$version}}/metro)，运行以下命令。

``` bash
metro make:navigation_hub base
```

该命令会引导你完成一个交互式设置流程，你可以选择布局类型并定义标签页或 Journey 状态。

这将在你的 `resources/pages/navigation_hubs/base/` 目录下创建 `base_navigation_hub.dart` 文件，子组件按照 `tabs/` 或 `states/` 子文件夹组织。
