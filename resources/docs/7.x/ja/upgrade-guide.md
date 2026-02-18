# アップグレードガイド

---

<a name="section-1"></a>
- [v7 の新機能](#whats-new "v7 の新機能")
- [破壊的変更の概要](#breaking-changes "破壊的変更の概要")
- [推奨される移行方法](#recommended-migration "推奨される移行方法")
- [クイック移行チェックリスト](#checklist "クイック移行チェックリスト")
- [ステップバイステップ移行ガイド](#migration-guide "ステップバイステップ移行ガイド")
  - [ステップ 1: 依存関係の更新](#step-1-dependencies "ステップ 1: 依存関係の更新")
  - [ステップ 2: 環境設定](#step-2-environment "ステップ 2: 環境設定")
  - [ステップ 3: main.dart の更新](#step-3-main "ステップ 3: main.dart の更新")
  - [ステップ 4: boot.dart の更新](#step-4-boot "ステップ 4: boot.dart の更新")
  - [ステップ 5: 設定ファイルの再編成](#step-5-config "ステップ 5: 設定ファイルの再編成")
  - [ステップ 6: AppProvider の更新](#step-6-provider "ステップ 6: AppProvider の更新")
  - [ステップ 7: テーマ設定の更新](#step-7-theme "ステップ 7: テーマ設定の更新")
  - [ステップ 10: ウィジェットの移行](#step-10-widgets "ステップ 10: ウィジェットの移行")
  - [ステップ 11: アセットパスの更新](#step-11-assets "ステップ 11: アセットパスの更新")
- [削除された機能と代替案](#removed-features "削除された機能と代替案")
- [削除されたクラス一覧](#deleted-classes "削除されたクラス一覧")
- [ウィジェット移行リファレンス](#widget-reference "ウィジェット移行リファレンス")
- [トラブルシューティング](#troubleshooting "トラブルシューティング")


<div id="whats-new"></div>

## v7 の新機能

{{ config('app.name') }} v7 は、開発者体験を大幅に改善するメジャーリリースです：

### 暗号化された環境設定
- 環境変数はセキュリティのためにビルド時に XOR 暗号化されます
- 新しい `metro make:key` で APP_KEY を生成
- 新しい `metro make:env` で暗号化された `env.g.dart` を生成
- CI/CD パイプライン用の `--dart-define` APP_KEY インジェクションをサポート

### 簡素化されたブートプロセス
- 新しい `BootConfig` パターンが個別の setup/finished コールバックを置き換え
- 暗号化された環境用の `env` パラメータによる、よりクリーンな `Nylo.init()`
- main.dart 内のアプリライフサイクルフック

### 新しい nylo.configure() API
- 単一のメソッドですべてのアプリ設定を統合
- 個別の `nylo.add*()` 呼び出しを置き換えるクリーンな構文
- プロバイダーで個別の `setup()` と `boot()` ライフサイクルメソッド

### ページ用の NyPage
- `NyPage` がページウィジェット用の `NyState` を置き換え（よりクリーンな構文）
- `view()` が `build()` メソッドを置き換え
- `get init =>` ゲッターが `init()` と `boot()` メソッドを置き換え
- `NyState` はページ以外の StatefulWidget で引き続き利用可能

### LoadingStyle システム
- 一貫したローディング状態のための新しい `LoadingStyle` 列挙型
- オプション: `LoadingStyle.normal()`、`LoadingStyle.skeletonizer()`、`LoadingStyle.none()`
- `LoadingStyle.normal(child: ...)` によるカスタムローディングウィジェット

### RouteView 型安全ルーティング
- `static RouteView path` が `static const path` を置き換え
- ウィジェットファクトリーによる型安全なルート定義

### マルチテーマサポート
- 複数のダークテーマとライトテーマを登録
- `.env` ファイルの代わりにコード内でテーマ ID を定義
- テーマ分類用の新しい `NyThemeType.dark` / `NyThemeType.light`
- 優先テーマ API: `NyTheme.setPreferredDark()`、`NyTheme.setPreferredLight()`
- テーマ列挙: `NyTheme.lightThemes()`、`NyTheme.darkThemes()`、`NyTheme.all()`

### 新しい Metro コマンド
- `make:key` - 暗号化用の APP_KEY を生成
- `make:env` - 暗号化された環境ファイルを生成
- `make:bottom_sheet_modal` - ボトムシートモーダルを作成
- `make:button` - カスタムボタンを作成

<a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">GitHub ですべての変更を表示</a>

<div id="breaking-changes"></div>

## 破壊的変更の概要

| 変更点 | v6 | v7 |
|--------|-----|-----|
| アプリルートウィジェット | `LocalizedApp(child: Main(nylo))` | `Main(nylo)`（`NyApp.materialApp()` を使用） |
| ページ状態クラス | `NyState` | ページ用の `NyPage` |
| ビューメソッド | `build()` | `view()` |
| 初期化メソッド | `init() async {}` / `boot() async {}` | `get init => () async {}` |
| ルートパス | `static const path = '/home'` | `static RouteView path = ('/home', (_) => HomePage())` |
| プロバイダーブート | `boot(Nylo nylo)` | `setup(Nylo nylo)` + `boot(Nylo nylo)` |
| 設定 | 個別の `nylo.add*()` 呼び出し | 単一の `nylo.configure()` 呼び出し |
| テーマ ID | `.env` ファイル（`LIGHT_THEME_ID`、`DARK_THEME_ID`） | コード（`type: NyThemeType.dark`） |
| ローディングウィジェット | `useSkeletonizer` + `loading()` | `LoadingStyle` ゲッター |
| 設定の場所 | `lib/config/` | `lib/bootstrap/`（decoders、events、providers、theme） |
| アセットの場所 | `public/` | `assets/` |

<div id="recommended-migration"></div>

## 推奨される移行方法

大規模なプロジェクトの場合、新しい v7 プロジェクトを作成してファイルを移行することをお勧めします：

1. 新しい v7 プロジェクトを作成: `git clone https://github.com/nylo-core/nylo.git my_app_v7 -b 7.x`
2. ページ、コントローラー、モデル、サービスをコピー
3. 上記のように構文を更新
4. 徹底的にテスト

これにより、最新のボイラープレート構造と設定がすべて含まれます。

v6 と v7 の変更差分を確認したい場合は、GitHub で比較を表示できます: <a href="https://github.com/nylo-core/nylo/compare/6.x...7.x" target="_BLANK">https://github.com/nylo-core/nylo/compare/6.x...7.x</a>

<div id="checklist"></div>

## クイック移行チェックリスト

このチェックリストを使用して移行の進捗を追跡してください：

- [ ] `pubspec.yaml` を更新（Dart >=3.10.7、Flutter >=3.24.0、nylo_framework: ^7.0.0）
- [ ] `flutter pub get` を実行
- [ ] `metro make:key` を実行して APP_KEY を生成
- [ ] `metro make:env` を実行して暗号化された環境を生成
- [ ] `main.dart` を env パラメータと BootConfig で更新
- [ ] `Boot` クラスを `BootConfig` パターンに変換
- [ ] 設定ファイルを `lib/config/` から `lib/bootstrap/` に移動
- [ ] 新しい設定ファイルを作成（`lib/config/app.dart`、`lib/config/storage_keys.dart`、`lib/config/toast_notification.dart`）
- [ ] `AppProvider` を `nylo.configure()` を使用するように更新
- [ ] `.env` から `LIGHT_THEME_ID` と `DARK_THEME_ID` を削除
- [ ] ダークテーマ設定に `type: NyThemeType.dark` を追加
- [ ] すべてのページウィジェットで `NyState` を `NyPage` に名前変更
- [ ] すべてのページで `build()` を `view()` に変更
- [ ] すべてのページで `init()/boot()` を `get init =>` に変更
- [ ] `static const path` を `static RouteView path` に更新
- [ ] ルートで `router.route()` を `router.add()` に変更
- [ ] ウィジェットの名前変更（NyListView → CollectionView など）
- [ ] アセットを `public/` から `assets/` に移動
- [ ] `pubspec.yaml` のアセットパスを更新
- [ ] Firebase のインポートを削除（使用している場合 - パッケージを直接追加）
- [ ] NyDevPanel の使用を削除（Flutter DevTools を使用）
- [ ] `flutter pub get` を実行してテスト

<div id="migration-guide"></div>

## ステップバイステップ移行ガイド

<div id="step-1-dependencies"></div>

### ステップ 1: 依存関係の更新

`pubspec.yaml` を更新します：

``` yaml
environment:
  sdk: '>=3.10.7 <4.0.0'
  flutter: ">=3.24.0"

dependencies:
  nylo_framework: ^7.0.0
  # ... その他の依存関係
```

`flutter pub get` を実行してパッケージを更新します。

<div id="step-2-environment"></div>

### ステップ 2: 環境設定

v7 ではセキュリティ向上のため、暗号化された環境変数が必要です。

**1. APP_KEY の生成:**

``` bash
metro make:key
```

これにより `.env` ファイルに `APP_KEY` が追加されます。

**2. 暗号化された env.g.dart の生成:**

``` bash
metro make:env
```

これにより、暗号化された環境変数を含む `lib/bootstrap/env.g.dart` が作成されます。

**3. .env から非推奨のテーマ変数を削除:**

``` bash
# .env ファイルから以下の行を削除:
LIGHT_THEME_ID=...
DARK_THEME_ID=...
```

<div id="step-3-main"></div>

### ステップ 3: main.dart の更新

**v6:**
``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,
  );
}
```

**v7:**
``` dart
import '/bootstrap/env.g.dart';
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

void main() async {
  await Nylo.init(
    env: Env.get,
    setup: Boot.nylo(),
    appLifecycle: {
      // オプション: アプリライフサイクルフックを追加
      // AppLifecycleState.resumed: () => print("App resumed"),
      // AppLifecycleState.paused: () => print("App paused"),
    },
  );
}
```

**主な変更点:**
- 生成された `env.g.dart` をインポート
- `env` パラメータに `Env.get` を渡す
- `Boot.nylo` が `Boot.nylo()` に変更（`BootConfig` を返す）
- `setupFinished` は削除（`BootConfig` 内で処理）
- アプリ状態変更用のオプション `appLifecycle` フック

<div id="step-4-boot"></div>

### ステップ 4: boot.dart の更新

**v6:**
``` dart
class Boot {
  static Future<Nylo> nylo() async {
    WidgetsFlutterBinding.ensureInitialized();

    if (getEnv('SHOW_SPLASH_SCREEN', defaultValue: false)) {
      runApp(SplashScreen.app());
    }

    await _setup();
    return await bootApplication(providers);
  }

  static Future<void> finished(Nylo nylo) async {
    await bootFinished(nylo, providers);
    runApp(Main(nylo));
  }
}
```

**v7:**
``` dart
class Boot {
  static BootConfig nylo() => BootConfig(
        setup: () async {
          WidgetsFlutterBinding.ensureInitialized();

          if (AppConfig.showSplashScreen) {
            runApp(SplashScreen.app());
          }

          await _init();
          return await setupApplication(providers);
        },
        boot: (Nylo nylo) async {
          await bootFinished(nylo, providers);
          runApp(Main(nylo));
        },
      );
}
```

**主な変更点:**
- `Future<Nylo>` の代わりに `BootConfig` を返す
- `setup` と `finished` が単一の `BootConfig` オブジェクトに統合
- `getEnv('SHOW_SPLASH_SCREEN')` → `AppConfig.showSplashScreen`
- `bootApplication` → `setupApplication`

<div id="step-5-config"></div>

### ステップ 5: 設定ファイルの再編成

v7 ではより良い構造のために設定ファイルが再編成されています：

| v6 の場所 | v7 の場所 | アクション |
|-------------|-------------|--------|
| `lib/config/decoders.dart` | `lib/bootstrap/decoders.dart` | 移動 |
| `lib/config/events.dart` | `lib/bootstrap/events.dart` | 移動 |
| `lib/config/providers.dart` | `lib/bootstrap/providers.dart` | 移動 |
| `lib/config/theme.dart` | `lib/bootstrap/theme.dart` | 移動 |
| `lib/config/keys.dart` | `lib/config/storage_keys.dart` | 名前変更とリファクタリング |
| （新規） | `lib/config/app.dart` | 作成 |
| （新規） | `lib/config/toast_notification.dart` | 作成 |

**lib/config/app.dart の作成:**

リファレンス: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/app.dart" target="_BLANK">App Config</a>

``` dart
class AppConfig {
  // アプリケーション名
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');

  // アプリケーションバージョン
  static final String version = getEnv('APP_VERSION', defaultValue: '1.0.0');

  // その他のアプリ設定をここに追加
}
```

**lib/config/storage_keys.dart の作成:**

リファレンス: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/storage_keys.dart" target="_BLANK">Storage Keys</a>

``` dart
final class StorageKeysConfig {
  // ブート時に同期するキーを定義
  static syncedOnBoot() => () async {
        return [
          auth,
          bearerToken,
          // coins.defaultValue(10), // ユーザーにデフォルトで10コインを付与
        ];
      };

  static StorageKey auth = 'SK_USER';

  static StorageKey bearerToken = 'SK_BEARER_TOKEN';

  // static StorageKey coins = 'SK_COINS';

  /// ストレージキーをここに追加...
}
```

**lib/config/toast_notification.dart の作成:**

リファレンス: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/config/toast_notification.dart" target="_BLANK">Toast Notification Config</a>

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class ToastNotificationConfig {
  static Map<ToastNotificationStyleMetaHelper, ToastMeta> styles = {
    // トーストスタイルをここでカスタマイズ
  };
}
```

<div id="step-6-provider"></div>

### ステップ 6: AppProvider の更新

**v6:**
``` dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    await NyLocalization.instance.init(
      localeType: localeType,
      languageCode: languageCode,
      assetsDirectory: assetsDirectory,
    );

    nylo.addLoader(loader);
    nylo.addLogo(logo);
    nylo.addThemes(appThemes);
    nylo.addToastNotification(getToastNotificationWidget);
    nylo.addValidationRules(validationRules);
    nylo.addModelDecoders(modelDecoders);
    nylo.addControllers(controllers);
    nylo.addApiDecoders(apiDecoders);
    nylo.useErrorStack();
    nylo.addAuthKey(Keys.auth);
    await nylo.syncKeys(Keys.syncedOnBoot);

    return nylo;
  }

  @override
  afterBoot(Nylo nylo) async {}
}
```

**v7:**
``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      localization: NyLocalizationConfig(
        languageCode: LocalizationConfig.languageCode,
        localeType: LocalizationConfig.localeType,
        assetsDirectory: LocalizationConfig.assetsDirectory,
      ),
      loader: DesignConfig.loader,
      logo: DesignConfig.logo,
      themes: appThemes,
      initialThemeId: 'light_theme',
      toastNotifications: ToastNotificationConfig.styles,
      modelDecoders: modelDecoders,
      controllers: controllers,
      apiDecoders: apiDecoders,
      authKey: StorageKeysConfig.auth,
      syncKeys: StorageKeysConfig.syncedOnBoot,
      useErrorStack: true,
    );

    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

**主な変更点:**
- 初期設定用の `boot()` が `setup()` に変更
- `boot()` はセットアップ後のロジック用に使用（以前の `afterBoot`）
- すべての `nylo.add*()` 呼び出しが単一の `nylo.configure()` に統合
- ローカリゼーションは `NyLocalizationConfig` オブジェクトを使用

<div id="step-7-theme"></div>

### ステップ 7: テーマ設定の更新

**v6（.env ファイル）:**
``` bash
LIGHT_THEME_ID=default_light_theme
DARK_THEME_ID=default_dark_theme
```

**v6（theme.dart）:**
``` dart
final List<BaseThemeConfig> appThemes = [
  BaseThemeConfig(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light Theme",
    theme: lightTheme(),
    colors: LightThemeColors(),
  ),
  BaseThemeConfig(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark Theme",
    theme: darkTheme(),
    colors: DarkThemeColors(),
  ),
];
```

**v7（theme.dart）:**
``` dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),
];
```

**主な変更点:**
- `.env` から `LIGHT_THEME_ID` と `DARK_THEME_ID` を削除
- テーマ ID をコード内で直接定義
- すべてのダークテーマ設定に `type: NyThemeType.dark` を追加
- ライトテーマはデフォルトで `NyThemeType.light`

**新しいテーマ API メソッド（v7）:**
``` dart
// 優先テーマを設定して記憶
NyTheme.set(context, id: 'dark_theme', remember: true);

// システム追従用の優先テーマを設定
NyTheme.setPreferredDark('dark_theme');
NyTheme.setPreferredLight('light_theme');

// 優先テーマ ID を取得
String? darkId = NyTheme.preferredDarkId();
String? lightId = NyTheme.preferredLightId();

// テーマの列挙
List<BaseThemeConfig> lights = NyTheme.lightThemes();
List<BaseThemeConfig> darks = NyTheme.darkThemes();
List<BaseThemeConfig> all = NyTheme.all();
BaseThemeConfig? theme = NyTheme.getById('dark_theme');
List<BaseThemeConfig> byType = NyTheme.getByType(NyThemeType.dark);

// 保存された設定をクリア
NyTheme.clearSavedTheme();
```

<div id="step-10-widgets"></div>

### ステップ 10: ウィジェットの移行

#### NyListView → CollectionView

**v6:**
``` dart
NyListView(
  child: (context, data) {
    return ListTile(title: Text(data.name));
  },
  data: () async => await api.getUsers(),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
CollectionView<User>(
  data: () async => await api.getUsers(),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
  loadingStyle: LoadingStyle.normal(),
)

// ページネーション付き（プルトゥリフレッシュ）:
CollectionView<User>.pullable(
  data: (page) async => await api.getUsers(page: page),
  builder: (context, item) => ListTile(
    title: Text(item.data.name),
  ),
)
```

#### NyFutureBuilder → FutureWidget

**v6:**
``` dart
NyFutureBuilder(
  future: fetchData(),
  child: (context, data) => Text(data),
  loading: CircularProgressIndicator(),
)
```

**v7:**
``` dart
FutureWidget<String>(
  future: fetchData(),
  child: (context, data) => Text(data ?? ''),
  loadingStyle: LoadingStyle.normal(),
)
```

#### NyTextField → InputField

**v6:**
``` dart
NyTextField(
  controller: _controller,
  validationRules: "not_empty|email",
)
```

**v7:**
``` dart
InputField(
  controller: _controller,
  formValidator: FormValidator
                  .notEmpty()
                  .email(),
),
```

#### NyRichText → StyledText

**v6:**
``` dart
NyRichText(children: [
	Text("Hello", style: TextStyle(color: Colors.yellow)),
	Text(" WORLD ", style: TextStyle(color: Colors.blue)),
	Text("!", style: TextStyle(color: Colors.red)),
]),
```

**v7:**
``` dart
StyledText.template(
  "@{{Hello}} @{{WORLD}}@{{!}}",
  styles: {
    "Hello": TextStyle(color: Colors.yellow),
    "WORLD": TextStyle(color: Colors.blue),
    "!": TextStyle(color: Colors.red),
  },
)
```

#### NyLanguageSwitcher → LanguageSwitcher

**v6:**
``` dart
NyLanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

**v7:**
``` dart
LanguageSwitcher(
  onLanguageChange: (locale) => print(locale),
)
```

<div id="step-11-assets"></div>

### ステップ 11: アセットパスの更新

v7 ではアセットディレクトリが `public/` から `assets/` に変更されました：

**1. アセットフォルダーを移動:**
``` bash
# ディレクトリを移動
mv public/fonts assets/fonts
mv public/images assets/images
mv public/app_icon assets/app_icon
```

**2. pubspec.yaml を更新:**

**v6:**
``` yaml
flutter:
  assets:
    - public/fonts/
    - public/images/
    - public/app_icon/
```

**v7:**
``` yaml
flutter:
  assets:
    - assets/fonts/
    - assets/images/
    - assets/app_icon/
```

**3. コード内のアセット参照を更新:**

**v6:**
``` dart
Image.asset('public/images/logo.png')
```

**v7:**
``` dart
Image.asset('assets/images/logo.png')
```

<div id="removed-features"></div>

### LocalizedApp ウィジェット - 削除

リファレンス: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**移行方法:** `Main(nylo)` を直接使用します。`NyApp.materialApp()` がローカリゼーションを内部的に処理します。

**v6:**
``` dart
runApp(LocalizedApp(child: Main(nylo)));
```

**v7:**
``` dart
runApp(Main(nylo));
```

<div id="deleted-classes"></div>

## 削除されたクラス一覧

| 削除されたクラス | 代替 |
|---------------|-------------|
| `NyTextStyle` | Flutter の `TextStyle` を直接使用 |
| `NyBaseApiService` | `DioApiService` を使用 |
| `BaseColorStyles` | `ThemeColor` を使用 |
| `LocalizedApp` | `Main(nylo)` を直接使用 |
| `NyException` | 標準的な Dart 例外を使用 |
| `PushNotification` | `flutter_local_notifications` を直接使用 |
| `PushNotificationAttachments` | `flutter_local_notifications` を直接使用 |

<div id="widget-reference"></div>

## ウィジェット移行リファレンス

### 名前変更されたウィジェット

| v6 ウィジェット | v7 ウィジェット | 備考 |
|-----------|-----------|-------|
| `NyListView` | `CollectionView` | `child` の代わりに `builder` を使用する新しい API |
| `NyFutureBuilder` | `FutureWidget` | 簡素化された非同期ウィジェット |
| `NyTextField` | `InputField` | `FormValidator` を使用 |
| `NyLanguageSwitcher` | `LanguageSwitcher` | 同じ API |
| `NyRichText` | `StyledText` | 同じ API |
| `NyFader` | `FadeOverlay` | 同じ API |

### 削除されたウィジェット（直接の代替なし）

| 削除されたウィジェット | 代替 |
|----------------|-------------|
| `NyPullToRefresh` | `CollectionView.pullable()` を使用 |

### ウィジェット移行例

**NyPullToRefresh → CollectionView.pullable():**

**v6:**
``` dart
NyPullToRefresh(
  child: (context, data) => ListTile(title: Text(data.name)),
  data: (page) async => await fetchData(page),
)
```

**v7:**
``` dart
CollectionView<MyModel>.pullable(
  data: (page) async => await fetchData(page),
  builder: (context, item) => ListTile(title: Text(item.data.name)),
)
```

**NyFader → AnimatedOpacity:**

**v6:**
``` dart
NyFader(
  child: MyWidget(),
)
```

**v7:**
``` dart
FadeOverlay.bottom(
  child: MyWidget(),
);
```

<div id="troubleshooting"></div>

## トラブルシューティング

### 「Env.get not found」または「Env is not defined」

**解決方法:** 環境生成コマンドを実行します：
``` bash
metro make:key
metro make:env
```
次に、生成されたファイルを `main.dart` にインポートします：
``` dart
import '/bootstrap/env.g.dart';
```

### 「テーマが適用されない」または「ダークテーマが動作しない」

**解決方法:** ダークテーマに `type: NyThemeType.dark` があることを確認します：
``` dart
BaseThemeConfig(
  id: 'dark_theme',
  description: "Dark Theme",
  theme: darkTheme(),
  colors: DarkThemeColors(),
  type: NyThemeType.dark, // この行を追加
),
```

### 「LocalizedApp not found」

リファレンス: <a href="https://github.com/nylo-core/nylo/blob/7.x/lib/resources/widgets/main_widget.dart" target="_BLANK">Main Widget</a>

**解決方法:** `LocalizedApp` は削除されました。以下のように変更します：
``` dart
// 変更前:
runApp(LocalizedApp(child: Main(nylo)));

// 変更後:
runApp(Main(nylo));
```

### 「router.route is not defined」

**解決方法:** 代わりに `router.add()` を使用します：
``` dart
// 変更前:
router.route(HomePage.path, (context) => HomePage());

// 変更後:
router.add(HomePage.path);
```

### 「NyListView not found」

**解決方法:** `NyListView` は `CollectionView` になりました：
``` dart
// 変更前:
NyListView(...)

// 変更後:
CollectionView<MyModel>(...)
```

### アセットが読み込まれない（画像、フォント）

**解決方法:** アセットパスを `public/` から `assets/` に更新します：
1. ファイルを移動: `mv public/* assets/`
2. `pubspec.yaml` のパスを更新
3. コードの参照を更新

### 「init() must return a value of type Future」

**解決方法:** ゲッター構文に変更します：
``` dart
// 変更前:
@override
init() async { ... }

// 変更後:
@override
get init => () async { ... };
```

---

**ヘルプが必要ですか？** [Nylo ドキュメント](https://nylo.dev/docs/7.x)を確認するか、[GitHub](https://github.com/nylo-core/nylo/issues)で Issue を作成してください。
