# Button

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [利用可能なボタンタイプ](#button-types "利用可能なボタンタイプ")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [非同期ローディング状態](#async-loading "非同期ローディング状態")
- [アニメーションスタイル](#animation-styles "アニメーションスタイル")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [スプラッシュスタイル](#splash-styles "スプラッシュスタイル")
- [ローディングスタイル](#loading-styles "ローディングスタイル")
- [フォーム送信](#form-submission "フォーム送信")
- [ボタンのカスタマイズ](#customizing-buttons "ボタンのカスタマイズ")
- [パラメータリファレンス](#parameters "パラメータリファレンス")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は、8つのプリビルトボタンスタイルを備えた `Button` クラスを提供します。各ボタンには以下の機能が組み込まれています:

- **非同期ローディング状態** — `onPressed` から `Future` を返すと、ボタンが自動的にローディングインジケーターを表示します
- **アニメーションスタイル** — clickable、bounce、pulse、squeeze、jelly、shine、ripple、morph、shake エフェクトから選択できます
- **スプラッシュスタイル** — ripple、highlight、glow、ink のタッチフィードバックを追加できます
- **フォーム送信** — ボタンを `NyFormData` インスタンスに直接接続できます

アプリのボタン定義は `lib/resources/widgets/buttons/buttons.dart` にあります。このファイルには各ボタンタイプのスタティックメソッドを持つ `Button` クラスが含まれており、プロジェクトのデフォルトを簡単にカスタマイズできます。

<div id="basic-usage"></div>

## 基本的な使い方

ウィジェット内のどこでも `Button` クラスを使用できます。ページ内でのシンプルな例:

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

すべてのボタンタイプは同じパターンに従います — `text` ラベルと `onPressed` コールバックを渡します。

<div id="button-types"></div>

## 利用可能なボタンタイプ

すべてのボタンはスタティックメソッドを使用して `Button` クラスからアクセスします。

<div id="primary"></div>

### Primary

テーマのプライマリカラーを使用した、シャドウ付きの塗りつぶしボタン。メインのCTAエレメントに最適です。

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

柔らかいサーフェスカラーと控えめなシャドウを持つ塗りつぶしボタン。プライマリボタンに付随するセカンダリアクションに適しています。

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

ボーダーストローク付きの透明なボタン。目立たないアクションやキャンセルボタンに便利です。

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

ボーダーとテキストの色をカスタマイズできます:

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

背景やボーダーのないミニマルなボタン。インラインアクションやリンクに最適です。

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

テキストの色をカスタマイズできます:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

テキストの横にアイコンを表示する塗りつぶしボタン。デフォルトではアイコンがテキストの前に表示されます。

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

背景色をカスタマイズできます:

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

リニアグラデーション背景を持つボタン。デフォルトではテーマのプライマリカラーとターシャリカラーを使用します。

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

カスタムグラデーションカラーを指定できます:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

完全に丸みを帯びたピル型ボタン。ボーダーラディウスはデフォルトでボタンの高さの半分になります。

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

背景色とボーダーラディウスをカスタマイズできます:

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

バックドロップブラーエフェクト付きのすりガラススタイルボタン。画像やカラー背景の上に配置する場合に効果的です。

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

テキストの色をカスタマイズできます:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## 非同期ローディング状態

{{ config('app.name') }} ボタンの最も強力な機能の1つは、**自動ローディング状態管理**です。`onPressed` コールバックが `Future` を返すと、ボタンは自動的にローディングインジケーターを表示し、操作が完了するまでインタラクションを無効にします。

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

非同期操作の実行中、ボタンはスケルトンローディングエフェクト（デフォルト）を表示します。`Future` が完了すると、ボタンは通常の状態に戻ります。

これはあらゆる非同期操作に対応します — API 呼び出し、データベース書き込み、ファイルアップロード、または `Future` を返すあらゆるもの:

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

`isLoading` 状態変数の管理、`setState` の呼び出し、`StatefulWidget` でのラッピングは不要です — {{ config('app.name') }} がすべてを処理します。

### 仕組み

ボタンが `onPressed` が `Future` を返すことを検出すると、`lockRelease` メカニズムを使用して以下を行います:

1. ローディングインジケーターを表示（`LoadingStyle` で制御）
2. 重複タップを防ぐためにボタンを無効化
3. `Future` の完了を待機
4. ボタンを通常の状態に復元

<div id="animation-styles"></div>

## アニメーションスタイル

ボタンは `ButtonAnimationStyle` を通じてプレスアニメーションをサポートします。これらのアニメーションは、ユーザーがボタンを操作する際に視覚的なフィードバックを提供します。`lib/resources/widgets/buttons/buttons.dart` でボタンをカスタマイズする際にアニメーションスタイルを設定できます。

<div id="anim-clickable"></div>

### Clickable

Duolingo スタイルの 3D プレスエフェクト。ボタンはプレス時に下方向に移動し、リリース時にバネのように戻ります。プライマリアクションやゲーム風の UX に最適です。

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

エフェクトの微調整:

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

プレス時にボタンを縮小し、リリース時にバネのように戻ります。カートに追加、いいね、お気に入りボタンに最適です。

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

エフェクトの微調整:

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

ボタンを押し続けている間の微妙な連続スケールパルス。長押しアクションや注目を集めたい場合に最適です。

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

エフェクトの微調整:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

プレス時にボタンを水平方向に圧縮し、垂直方向に拡大します。遊び心のあるインタラクティブな UI に最適です。

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

エフェクトの微調整:

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

揺れるような弾性変形エフェクト。楽しいカジュアルなアプリやエンターテインメントアプリに最適です。

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

エフェクトの微調整:

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

プレス時にボタン上を光沢のあるハイライトが横切るエフェクト。プレミアム機能や注目を集めたい CTA に最適です。

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

エフェクトの微調整:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

タッチポイントから広がる強化されたリップルエフェクト。Material Design の強調に最適です。

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

エフェクトの微調整:

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

プレス時にボタンのボーダーラディウスが増加し、形状が変化するエフェクトを作成します。繊細でエレガントなフィードバックに最適です。

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

エフェクトの微調整:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

水平方向のシェイクアニメーション。エラー状態や無効なアクションに最適です — ボタンを振って何かが間違っていることを伝えます。

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

エフェクトの微調整:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### アニメーションの無効化

アニメーションなしでボタンを使用する場合:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### デフォルトアニメーションの変更

ボタンタイプのデフォルトアニメーションを変更するには、`lib/resources/widgets/buttons/buttons.dart` ファイルを修正します:

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

## スプラッシュスタイル

スプラッシュエフェクトはボタンに視覚的なタッチフィードバックを提供します。`ButtonSplashStyle` で設定します。スプラッシュスタイルはアニメーションスタイルと組み合わせてレイヤードフィードバックを実現できます。

### 利用可能なスプラッシュスタイル

| スプラッシュ | ファクトリ | 説明 |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | タッチポイントからの標準的な Material リップル |
| Highlight | `ButtonSplashStyle.highlight()` | リップルアニメーションなしの控えめなハイライト |
| Glow | `ButtonSplashStyle.glow()` | タッチポイントから放射するソフトグロー |
| Ink | `ButtonSplashStyle.ink()` | 素早いインクスプラッシュ、より高速でレスポンシブ |
| None | `ButtonSplashStyle.none()` | スプラッシュエフェクトなし |
| Custom | `ButtonSplashStyle.custom()` | スプラッシュファクトリの完全な制御 |

### 例

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

スプラッシュの色と不透明度をカスタマイズできます:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## ローディングスタイル

非同期操作中に表示されるローディングインジケーターは `LoadingStyle` で制御されます。ボタンファイルでボタンタイプごとに設定できます。

### Skeletonizer（デフォルト）

ボタン上にシマースケルトンエフェクトを表示します:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

ローディングウィジェットを表示します（デフォルトはアプリローダー）:

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

ローディング中もボタンを表示したまま、インタラクションを無効にします:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## フォーム送信

すべてのボタンは `submitForm` パラメータをサポートしており、ボタンを `NyForm` に接続します。タップすると、ボタンはフォームをバリデーションし、フォームデータで成功ハンドラーを呼び出します。

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

`submitForm` パラメータは2つの値を持つレコードを受け取ります:
1. `NyFormData` インスタンス（またはフォーム名を `String` として）
2. バリデーション済みデータを受け取るコールバック

デフォルトでは `showToastError` は `true` で、フォームバリデーション失敗時にトースト通知を表示します。エラーをサイレントに処理するには `false` に設定します:

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

`submitForm` コールバックが `Future` を返す場合、ボタンは非同期操作が完了するまで自動的にローディング状態を表示します。

<div id="customizing-buttons"></div>

## ボタンのカスタマイズ

すべてのボタンデフォルトはプロジェクトの `lib/resources/widgets/buttons/buttons.dart` で定義されています。各ボタンタイプには `lib/resources/widgets/buttons/partials/` に対応するウィジェットクラスがあります。

### デフォルトスタイルの変更

ボタンのデフォルトの外観を変更するには、`Button` クラスを編集します:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
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

### ボタンウィジェットのカスタマイズ

ボタンタイプの視覚的な外観を変更するには、`lib/resources/widgets/buttons/partials/` 内の対応するウィジェットを編集します。例えば、プライマリボタンのボーダーラディウスやシャドウを変更する場合:

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

### 新しいボタンタイプの作成

新しいボタンタイプを追加するには:

1. `lib/resources/widgets/buttons/partials/` に `StatefulAppButton` を継承した新しいウィジェットファイルを作成します。
2. `buildButton` メソッドを実装します。
3. `Button` クラスにスタティックメソッドを追加します。

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

次に `Button` クラスに登録します:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
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

## パラメータリファレンス

### 共通パラメータ（全ボタンタイプ）

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `text` | `String` | 必須 | ボタンのラベルテキスト |
| `onPressed` | `VoidCallback?` | `null` | ボタンタップ時のコールバック。自動ローディング状態には `Future` を返す |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | フォーム送信レコード（フォームインスタンス、成功コールバック） |
| `onFailure` | `Function(dynamic)?` | `null` | フォームバリデーション失敗時に呼び出される |
| `showToastError` | `bool` | `true` | フォームバリデーションエラー時にトースト通知を表示 |
| `width` | `double?` | `null` | ボタンの幅（デフォルトは全幅） |

### タイプ固有のパラメータ

#### Button.outlined

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | テーマのアウトラインカラー | ボーダーストロークの色 |
| `textColor` | `Color?` | テーマのプライマリカラー | テキストの色 |

#### Button.textOnly

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | テーマのプライマリカラー | テキストの色 |

#### Button.icon

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `icon` | `Widget` | 必須 | 表示するアイコンウィジェット |
| `color` | `Color?` | テーマのプライマリカラー | 背景色 |

#### Button.gradient

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | プライマリとターシャリカラー | グラデーションカラーストップ |

#### Button.rounded

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | テーマのプライマリコンテナカラー | 背景色 |
| `borderRadius` | `BorderRadius?` | ピル型（高さ / 2） | 角丸 |

#### Button.transparency

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `color` | `Color?` | テーマ適応型 | テキストの色 |

### ButtonAnimationStyle パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `duration` | `Duration` | タイプにより異なる | アニメーション時間 |
| `curve` | `Curve` | タイプにより異なる | アニメーションカーブ |
| `enableHapticFeedback` | `bool` | タイプにより異なる | プレス時に触覚フィードバックをトリガー |
| `translateY` | `double` | `4.0` | Clickable: 垂直方向のプレス距離 |
| `shadowOffset` | `double` | `4.0` | Clickable: シャドウの深さ |
| `scaleMin` | `double` | `0.92` | Bounce: プレス時の最小スケール |
| `pulseScale` | `double` | `1.05` | Pulse: パルス中の最大スケール |
| `squeezeX` | `double` | `0.95` | Squeeze: 水平方向の圧縮 |
| `squeezeY` | `double` | `1.05` | Squeeze: 垂直方向の拡大 |
| `jellyStrength` | `double` | `0.15` | Jelly: 揺れの強度 |
| `shineColor` | `Color` | `Colors.white` | Shine: ハイライトの色 |
| `shineWidth` | `double` | `0.3` | Shine: シャインバンドの幅 |
| `rippleScale` | `double` | `2.0` | Ripple: 拡大スケール |
| `morphRadius` | `double` | `24.0` | Morph: ターゲットのボーダーラディウス |
| `shakeOffset` | `double` | `8.0` | Shake: 水平方向の変位 |
| `shakeCount` | `int` | `3` | Shake: 振動回数 |

### ButtonSplashStyle パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | テーマのサーフェスカラー | スプラッシュエフェクトの色 |
| `highlightColor` | `Color?` | テーマのサーフェスカラー | ハイライトエフェクトの色 |
| `splashOpacity` | `double` | `0.12` | スプラッシュの不透明度 |
| `highlightOpacity` | `double` | `0.06` | ハイライトの不透明度 |
| `borderRadius` | `BorderRadius?` | `null` | スプラッシュクリップの角丸 |
