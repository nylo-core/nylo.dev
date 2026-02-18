# Navigation Hub

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
  - [Navigation Hub の作成](#creating-a-navigation-hub "Navigation Hub の作成")
  - [ナビゲーションタブの作成](#creating-navigation-tabs "ナビゲーションタブの作成")
  - [ボトムナビゲーション](#bottom-navigation "ボトムナビゲーション")
    - [カスタムナビバービルダー](#custom-nav-bar-builder "カスタムナビバービルダー")
  - [トップナビゲーション](#top-navigation "トップナビゲーション")
  - [ジャーニーナビゲーション](#journey-navigation "ジャーニーナビゲーション")
    - [プログレススタイル](#journey-progress-styles "プログレススタイル")
    - [JourneyState](#journey-state "JourneyState")
    - [JourneyState ヘルパーメソッド](#journey-state-helper-methods "JourneyState ヘルパーメソッド")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [タブ内でのナビゲーション](#navigating-within-a-tab "タブ内でのナビゲーション")
- [タブ](#tabs "タブ")
  - [タブにバッジを追加](#adding-badges-to-tabs "タブにバッジを追加")
  - [タブにアラートを追加](#adding-alerts-to-tabs "タブにアラートを追加")
- [初期インデックス](#initial-index "初期インデックス")
- [状態の維持](#maintaining-state "状態の維持")
- [onTap](#on-tap "onTap")
- [State Actions](#state-actions "State Actions")
- [ローディングスタイル](#loading-style "ローディングスタイル")

<div id="introduction"></div>

## はじめに

Navigation Hub は、すべてのウィジェットのナビゲーションを**管理**するための中心的な場所です。
標準でボトム、トップ、ジャーニーナビゲーションレイアウトを数秒で作成できます。

アプリにボトムナビゲーションバーを追加し、ユーザーがアプリ内の異なるタブ間を移動できるようにしたいと**想像**してみましょう。

Navigation Hub を使用してこれを構築できます。

アプリで Navigation Hub を使用する方法を見ていきましょう。

<div id="basic-usage"></div>

## 基本的な使い方

以下のコマンドで Navigation Hub を作成できます。

``` bash
metro make:navigation_hub base
```

コマンドはインタラクティブなセットアップを案内します:

1. **レイアウトタイプの選択** - `navigation_tabs`（ボトムナビゲーション）または `journey_states`（シーケンシャルフロー）から選択します。
2. **タブ/ステート名の入力** - タブまたはジャーニーステートのカンマ区切りの名前を入力します。

これにより `resources/pages/navigation_hubs/base/` ディレクトリにファイルが作成されます:
- `base_navigation_hub.dart` - メインのハブウィジェット
- `tabs/` または `states/` - 各タブまたはジャーニーステートの子ウィジェットを含むディレクトリ

生成された Navigation Hub は以下のようになります:

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

  /// レイアウトビルダー
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// 状態を維持するかどうか
  @override
  bool get maintainState => true;

  /// 初期インデックス
  @override
  int get initialIndex => 0;

  /// ナビゲーションページ
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// タップイベントの処理
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Navigation Hub には **2 つ**のタブ、Home と Settings があることがわかります。

`layout` メソッドはハブのレイアウトタイプを返します。レイアウトを設定する際にテーマデータやメディアクエリにアクセスできるよう、`BuildContext` を受け取ります。

Navigation Hub に `NavigationTab` を追加することで、より多くのタブを作成できます。

まず、Metro を使用して新しいウィジェットを作成する必要があります。

``` bash
metro make:stateful_widget news_tab
```

複数のウィジェットを一度に作成することもできます。

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

次に、新しいウィジェットを Navigation Hub に追加できます。

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Navigation Hub を使用するには、ルーターに初期ルートとして追加します:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// またはアプリ内のどこからでも Navigation Hub にナビゲートできます

routeTo(BaseNavigationHub.path);
```

Navigation Hub でできることは**まだまだ**あります。いくつかの機能を見ていきましょう。

<div id="bottom-navigation"></div>

### ボトムナビゲーション

`layout` メソッドから `NavigationHubLayout.bottomNav` を返すことで、レイアウトをボトムナビゲーションバーに設定できます。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

以下のようなプロパティを設定してボトムナビゲーションバーをカスタマイズできます:

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

`style` パラメータを使用して、ボトムナビゲーションバーにプリセットスタイルを適用できます。

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // デフォルトの Flutter マテリアルスタイル
);
```

<div id="custom-nav-bar-builder"></div>

### カスタムナビバービルダー

ナビゲーションバーを完全に制御するには、`navBarBuilder` パラメータを使用します。

これにより、ナビゲーションデータを受け取りながら任意のカスタムウィジェットを構築できます。

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

`NavBarData` オブジェクトには以下が含まれます:

| プロパティ | 型 | 説明 |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | ナビゲーションバーアイテム |
| `currentIndex` | `int` | 現在選択されているインデックス |
| `onTap` | `ValueChanged<int>` | タブがタップされた時のコールバック |

完全にカスタムなガラスナビバーの例:

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

> **注意:** `navBarBuilder` を使用する場合、`style` パラメータは無視されます。

<div id="top-navigation"></div>

### トップナビゲーション

`layout` メソッドから `NavigationHubLayout.topNav` を返すことで、レイアウトをトップナビゲーションバーに変更できます。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

以下のようなプロパティを設定してトップナビゲーションバーをカスタマイズできます:

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

### ジャーニーナビゲーション

`layout` メソッドから `NavigationHubLayout.journey` を返すことで、レイアウトをジャーニーナビゲーションに変更できます。

これはオンボーディングフローやマルチステップフォームに最適です。

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

ジャーニーレイアウトに `backgroundGradient` を設定することもできます:

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

> **注意:** `backgroundGradient` が設定されている場合、`backgroundColor` より優先されます。

ジャーニーナビゲーションレイアウトを使用する場合、**ウィジェット**には `JourneyState` を使用する必要があります。ジャーニーの管理に役立つ多くのヘルパーメソッドが含まれています。

`make:navigation_hub` コマンドで `journey_states` レイアウトを選択すると、ジャーニー全体を作成できます:

``` bash
metro make:navigation_hub onboarding
# 選択: journey_states
# 入力: welcome, personal_info, add_photos
```

これにより、ハブとすべてのジャーニーステートウィジェットが `resources/pages/navigation_hubs/onboarding/states/` に作成されます。

または、個別のジャーニーウィジェットを以下のコマンドで作成できます:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

次に、新しいウィジェットを Navigation Hub に追加できます。

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

### プログレススタイル

`JourneyProgressStyle` クラスを使用してプログレスインジケーターのスタイルをカスタマイズできます。

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

以下のプログレスインジケーターを使用できます:

- `JourneyProgressIndicator.none()`: 何も表示しません - 特定のタブでインジケーターを非表示にする場合に便利です。
- `JourneyProgressIndicator.linear()`: リニアプログレスバー。
- `JourneyProgressIndicator.dots()`: ドットベースのプログレスインジケーター。
- `JourneyProgressIndicator.numbered()`: 番号付きステッププログレスインジケーター。
- `JourneyProgressIndicator.segments()`: セグメント型プログレスバースタイル。
- `JourneyProgressIndicator.circular()`: サーキュラープログレスインジケーター。
- `JourneyProgressIndicator.timeline()`: タイムラインスタイルのプログレスインジケーター。
- `JourneyProgressIndicator.custom()`: ビルダー関数を使用したカスタムプログレスインジケーター。

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

`JourneyProgressStyle` 内でプログレスインジケーターの位置とパディングをカスタマイズできます:

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

以下のプログレスインジケーター位置を使用できます:

- `ProgressIndicatorPosition.top`: 画面上部のプログレスインジケーター。
- `ProgressIndicatorPosition.bottom`: 画面下部のプログレスインジケーター。

#### タブごとのプログレススタイルオーバーライド

`NavigationTab.journey(progressStyle: ...)` を使用して、個々のタブでレイアウトレベルの `progressStyle` をオーバーライドできます。独自の `progressStyle` を持たないタブはレイアウトのデフォルトを継承します。レイアウトデフォルトもタブごとのスタイルも設定されていないタブは、プログレスインジケーターを表示しません。

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // このタブのみレイアウトデフォルトをオーバーライド
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

`JourneyState` クラスは `NyState` をジャーニー固有の機能で拡張し、オンボーディングフローやマルチステップジャーニーの作成を容易にします。

新しい `JourneyState` を作成するには、以下のコマンドを使用します。

``` bash
metro make:journey_widget onboard_user_dob
```

複数のウィジェットを一度に作成したい場合は、以下のコマンドを使用します。

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

生成された JourneyState ウィジェットは以下のようになります:

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
    // 初期化ロジックをここに記述
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

          // ナビゲーションボタン
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

  /// ジャーニーが次のステップに進めるかチェック
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// 次のステップに遷移する前に呼び出される
  @override
  Future<void> onBeforeNext() async {
    // 例: セッションにデータを保存
  }

  /// ジャーニーが完了した時に呼び出される（最後のステップで）
  @override
  Future<void> onComplete() async {}
}
```

**JourneyState** クラスは前に進むために `nextStep` を、戻るために `onBackPressed` を使用していることに気付くでしょう。

`nextStep` メソッドは完全なバリデーションライフサイクルを実行します: `canContinue()` -> `onBeforeNext()` -> ナビゲート（または最後のステップの場合は `onComplete()`） -> `onAfterNext()`。

`buildJourneyContent` を使用して、オプションのナビゲーションボタン付きの構造化されたレイアウトを構築することもできます:

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

`buildJourneyContent` メソッドで使用できるプロパティは以下の通りです。

| プロパティ | 型 | 説明 |
| --- | --- | --- |
| `content` | `Widget` | ページのメインコンテンツ。 |
| `nextButton` | `Widget?` | 次へボタンウィジェット。 |
| `backButton` | `Widget?` | 戻るボタンウィジェット。 |
| `contentPadding` | `EdgeInsetsGeometry` | コンテンツのパディング。 |
| `header` | `Widget?` | ヘッダーウィジェット。 |
| `footer` | `Widget?` | フッターウィジェット。 |
| `crossAxisAlignment` | `CrossAxisAlignment` | コンテンツのクロス軸アラインメント。 |

<div id="journey-state-helper-methods"></div>

### JourneyState ヘルパーメソッド

`JourneyState` クラスには、ジャーニーの動作をカスタマイズするためのヘルパーメソッドとプロパティがあります。

| メソッド / プロパティ | 説明 |
| --- | --- |
| [`nextStep()`](#next-step) | バリデーション付きで次のステップに遷移します。`Future<bool>` を返します。 |
| [`previousStep()`](#previous-step) | 前のステップに遷移します。`Future<bool>` を返します。 |
| [`onBackPressed()`](#on-back-pressed) | 前のステップに遷移するためのシンプルなヘルパー。 |
| [`onComplete()`](#on-complete) | ジャーニーが完了した時に呼び出されます（最後のステップで）。 |
| [`onBeforeNext()`](#on-before-next) | 次のステップに遷移する前に呼び出されます。 |
| [`onAfterNext()`](#on-after-next) | 次のステップに遷移した後に呼び出されます。 |
| [`canContinue()`](#can-continue) | 次のステップへのナビゲーション前のバリデーションチェック。 |
| [`isFirstStep`](#is-first-step) | ジャーニーの最初のステップの場合 true を返します。 |
| [`isLastStep`](#is-last-step) | ジャーニーの最後のステップの場合 true を返します。 |
| [`currentStep`](#current-step) | 現在のステップインデックスを返します（0ベース）。 |
| [`totalSteps`](#total-steps) | 合計ステップ数を返します。 |
| [`completionPercentage`](#completion-percentage) | 完了率を返します（0.0 から 1.0）。 |
| [`goToStep(int index)`](#go-to-step) | インデックスで指定したステップにジャンプします。 |
| [`goToNextStep()`](#go-to-next-step) | 次のステップにジャンプします（バリデーションなし）。 |
| [`goToPreviousStep()`](#go-to-previous-step) | 前のステップにジャンプします（バリデーションなし）。 |
| [`goToFirstStep()`](#go-to-first-step) | 最初のステップにジャンプします。 |
| [`goToLastStep()`](#go-to-last-step) | 最後のステップにジャンプします。 |
| [`exitJourney()`](#exit-journey) | ルートナビゲーターをポップしてジャーニーを終了します。 |
| [`resetCurrentStep()`](#reset-current-step) | 現在のステップの状態をリセットします。 |
| [`onJourneyComplete`](#on-journey-complete) | ジャーニーが完了した時のコールバック（最後のステップでオーバーライド）。 |
| [`buildJourneyPage()`](#build-journey-page) | Scaffold 付きのフルスクリーンジャーニーページを構築します。 |


<div id="next-step"></div>

#### nextStep

`nextStep` メソッドは完全なバリデーション付きで次のステップに遷移します。ライフサイクルを実行します: `canContinue()` -> `onBeforeNext()` -> ナビゲートまたは `onComplete()` -> `onAfterNext()`。

`force: true` を渡すとバリデーションをバイパスして直接遷移できます。

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
            onPressed: nextStep, // バリデーションを実行してから遷移
        ),
    );
}
```

バリデーションをスキップする場合:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

`previousStep` メソッドは前のステップに遷移します。成功した場合は `true`、既に最初のステップにいる場合は `false` を返します。

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // 既に最初のステップです
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

`onBackPressed` メソッドは内部で `previousStep()` を呼び出すシンプルなヘルパーです。

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

`onComplete` メソッドは、最後のステップで `nextStep()` がトリガーされた時に呼び出されます（バリデーション通過後）。

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

`onBeforeNext` メソッドは次のステップに遷移する前に呼び出されます。

例えば、次のステップに遷移する前にデータを保存したい場合、ここで行うことができます。

``` dart
@override
Future<void> onBeforeNext() async {
    // 例: セッションにデータを保存
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

`onAfterNext` メソッドは次のステップに遷移した後に呼び出されます。

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

`canContinue` メソッドは `nextStep()` がトリガーされた時に呼び出されます。ナビゲーションを防ぐには `false` を返します。

``` dart
@override
Future<bool> canContinue() async {
    // バリデーションロジックをここに記述
    // ジャーニーが続行できる場合は true、そうでない場合は false を返す
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

`isFirstStep` プロパティはジャーニーの最初のステップの場合 true を返します。

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

`isLastStep` プロパティはジャーニーの最後のステップの場合 true を返します。

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

`currentStep` プロパティは現在のステップインデックスを返します（0ベース）。

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

`totalSteps` プロパティはジャーニーの合計ステップ数を返します。

<div id="completion-percentage"></div>

#### completionPercentage

`completionPercentage` プロパティは完了率を 0.0 から 1.0 の値で返します。

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

`goToStep` メソッドはインデックスで指定したステップに直接ジャンプします。バリデーションは**トリガーされません**。

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // ステップインデックス 2 にジャンプ
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

`goToNextStep` メソッドはバリデーションなしで次のステップにジャンプします。既に最後のステップにいる場合は何もしません。

``` dart
onPressed: () {
    goToNextStep(); // バリデーションをスキップして次のステップへ
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

`goToPreviousStep` メソッドはバリデーションなしで前のステップにジャンプします。既に最初のステップにいる場合は何もしません。

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

`goToFirstStep` メソッドは最初のステップにジャンプします。

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

`goToLastStep` メソッドは最後のステップにジャンプします。

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

`exitJourney` メソッドはルートナビゲーターをポップしてジャーニーを終了します。

``` dart
onPressed: () {
    exitJourney(); // ルートナビゲーターをポップ
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

`resetCurrentStep` メソッドは現在のステップの状態をリセットします。

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

`onJourneyComplete` ゲッターは、ジャーニーの**最後のステップ**でオーバーライドして、ユーザーがフローを完了した時の動作を定義できます。

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// ジャーニー完了時のコールバック
  @override
  void Function()? get onJourneyComplete => () {
    // ホームページまたは次の遷移先にナビゲート
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
            onPressed: onJourneyComplete, // 完了コールバックをトリガー
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

`buildJourneyPage` メソッドは `Scaffold` と `SafeArea` でラップされたフルスクリーンジャーニーページを構築します。

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

| プロパティ | 型 | 説明 |
| --- | --- | --- |
| `content` | `Widget` | ページのメインコンテンツ。 |
| `nextButton` | `Widget?` | 次へボタンウィジェット。 |
| `backButton` | `Widget?` | 戻るボタンウィジェット。 |
| `contentPadding` | `EdgeInsetsGeometry` | コンテンツのパディング。 |
| `header` | `Widget?` | ヘッダーウィジェット。 |
| `footer` | `Widget?` | フッターウィジェット。 |
| `backgroundColor` | `Color?` | Scaffold の背景色。 |
| `appBar` | `Widget?` | オプションの AppBar ウィジェット。 |
| `crossAxisAlignment` | `CrossAxisAlignment` | コンテンツのクロス軸アラインメント。 |

<div id="navigating-within-a-tab"></div>

## タブ内のウィジェットへのナビゲーション

`pushTo` ヘルパーを使用してタブ内のウィジェットにナビゲートできます。

タブ内で `pushTo` ヘルパーを使用して別のウィジェットにナビゲートできます。

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

ナビゲート先のウィジェットにデータを渡すこともできます。

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

## タブ

タブは Navigation Hub の主要な構成要素です。

`NavigationTab` クラスとその名前付きコンストラクタを使用して、Navigation Hub にタブを追加できます。

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

上記の例では、Navigation Hub に Home と Settings の 2 つのタブを追加しています。

さまざまな種類のタブを使用できます:

- `NavigationTab.tab()` - 標準のナビゲーションタブ。
- `NavigationTab.badge()` - バッジカウント付きのタブ。
- `NavigationTab.alert()` - アラートインジケーター付きのタブ。
- `NavigationTab.journey()` - ジャーニーナビゲーションレイアウト用のタブ。

<div id="adding-badges-to-tabs"></div>

## タブにバッジを追加

タブにバッジを簡単に追加できるようにしました。

バッジは、タブに新しい情報があることをユーザーに示す優れた方法です。

例えば、チャットアプリの場合、チャットタブに未読メッセージ数を表示できます。

タブにバッジを追加するには、`NavigationTab.badge` コンストラクタを使用します。

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

上記の例では、Chat タブに初期カウント 10 のバッジを追加しています。

プログラムでバッジカウントを更新することもできます。

``` dart
/// バッジカウントをインクリメント
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// バッジカウントを更新
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// バッジカウントをクリア
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

デフォルトでは、バッジカウントは記憶されます。セッションごとにバッジカウントを**クリア**したい場合は、`rememberCount` を `false` に設定できます。

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

## タブにアラートを追加

タブにアラートを追加できます。

バッジカウントを表示したくないが、ユーザーにアラートインジケーターを表示したい場合があります。

タブにアラートを追加するには、`NavigationTab.alert` コンストラクタを使用します。

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

これにより、Chat タブに赤色のアラートが追加されます。

プログラムでアラートを更新することもできます。

``` dart
/// アラートを有効化
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// アラートを無効化
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## 初期インデックス

デフォルトでは、Navigation Hub は最初のタブ（インデックス 0）で開始します。`initialIndex` ゲッターをオーバーライドすることで変更できます。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // 2 番目のタブから開始
    ...
}
```

<div id="maintaining-state"></div>

## 状態の維持

デフォルトでは、Navigation Hub の状態は維持されます。

これは、タブに遷移した時にタブの状態が保持されることを意味します。

タブに遷移するたびにタブの状態をクリアしたい場合は、`maintainState` を `false` に設定できます。

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

`onTap` メソッドをオーバーライドして、タブがタップされた時にカスタムロジックを追加できます。

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // カスタムロジックをここに追加
        // 例: アナリティクスのトラッキング、確認ダイアログの表示など
        super.onTap(index); // タブ切り替えを処理するために必ず super を呼び出す
    }
}
```

<div id="state-actions"></div>

## State Actions

State Actions は、アプリ内のどこからでも Navigation Hub と対話するための方法です。

使用できる State Actions は以下の通りです:

``` dart
/// 指定したインデックスのタブをリセット
/// 例: MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// プログラムで現在のタブを変更
/// 例: MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// バッジカウントを更新
/// 例: MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// バッジカウントをインクリメント
/// 例: MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// バッジカウントをクリア
/// 例: MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// タブのアラートを有効化
/// 例: MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// タブのアラートを無効化
/// 例: MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// ジャーニーレイアウトで次のページに遷移
/// 例: await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// ジャーニーレイアウトで前のページに遷移
/// 例: await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

State Action を使用するには、以下のようにします:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // タブ 2 に切り替え

await MyNavigationHub.stateActions.nextPage(); // ジャーニー: 次のページへ
```

<div id="loading-style"></div>

## ローディングスタイル

標準では、Navigation Hub はタブのロード中に**デフォルト**のローディングウィジェット（resources/widgets/loader_widget.dart）を表示します。

`loadingStyle` をカスタマイズしてローディングスタイルを変更できます。

| スタイル | 説明 |
| --- | --- |
| normal | デフォルトのローディングスタイル |
| skeletonizer | スケルトンローディングスタイル |
| none | ローディングスタイルなし |

ローディングスタイルは以下のように変更できます:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// または
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

スタイルのローディングウィジェットを変更したい場合は、`LoadingStyle` に `child` を渡すことができます。

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

これにより、タブのロード中に「Loading...」というテキストが表示されるようになります。

以下は使用例です:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // 3 秒間のローディングをシミュレート

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

## Navigation Hub の作成

Navigation Hub を作成するには、[Metro](/docs/{{$version}}/metro) を使用して、以下のコマンドを実行します。

``` bash
metro make:navigation_hub base
```

コマンドはインタラクティブなセットアップを案内し、レイアウトタイプの選択やタブまたはジャーニーステートの定義ができます。

これにより、`resources/pages/navigation_hubs/base/` ディレクトリに `base_navigation_hub.dart` ファイルが作成され、子ウィジェットは `tabs/` または `states/` サブフォルダに整理されます。
