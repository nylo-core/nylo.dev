# インストール

---

<a name="section-1"></a>
- [インストール](#install "インストール")
- [プロジェクトの実行](#running-the-project "プロジェクトの実行")
- [Metro CLI](#metro-cli "Metro CLI")
- [Nylo CLI](#nylo-cli "Nylo CLI")
  - [プロジェクトのクリーンアップ](#nylo-clean "プロジェクトのクリーンアップ")
  - [iOS Pod のリフレッシュ](#nylo-ios-pod-refresh "iOS Pod のリフレッシュ")
  - [エイリアスなしで Metro を実行](#nylo-metro "エイリアスなしで Metro を実行")
  - [CLI の更新](#nylo-self-update "CLI の更新")

<div id="install"></div>

3つのコマンドで、空のフォルダーから、ルーティング、ネットワーク、テーマ、コード生成が設定済みの Flutter アプリを起動できます。

<x-doc-strip label="始める前に" items="Flutter SDK をインストール済み, Dart 3" linkText="すべての要件" linkHref="/ja/docs/{{ $version }}/requirements" />

## インストール

次のコマンドを順番に実行してください。どのコマンドも安全に再実行できます。

<x-doc-steps>
<x-doc-step number="1" title="Nylo CLI をグローバルにインストール">
`nylo` コマンドをシステムに追加します。

``` bash
dart pub global activate nylo_installer
```
</x-doc-step>

<x-doc-step number="2" title="新しいプロジェクトを作成">
このコマンドは {{ config('app.name') }} テンプレートをクローンし、アプリ名でプロジェクトを設定し、すべての依存関係を自動的にインストールします。

``` bash
nylo new my_app
```
</x-doc-step>

<x-doc-step number="3" title="Metro エイリアスの設定">
プロジェクト用の `metro` コマンドを設定します。これにより、`dart run` の完全な構文を使わずに Metro CLI コマンドを使用できるようになります。

``` bash
cd my_app
nylo init
```

その後、ターミナルを再起動するか、`nylo init` が出力する `source` コマンドを実行してください。
</x-doc-step>
</x-doc-steps>

<x-doc-panel title="用意されるもの" items="ルーティングとナビゲーションの事前設定
API サービスのボイラープレート
テーマとローカライゼーションのセットアップ
コード生成用の Metro CLI" />


<div id="running-the-project"></div>

## プロジェクトの実行

{{ config('app.name') }} プロジェクトは標準的な Flutter アプリと同じように実行できます。

<x-doc-tabs tabs="ターミナル, Android Studio, VS Code">
<x-doc-tab label="ターミナル">

``` bash
flutter run
```

ビルドが成功すると、{{ config('app.name') }} のデフォルトランディング画面が表示されます。
</x-doc-tab>

<x-doc-tab label="Android Studio">
プロジェクトフォルダーを開き、ターゲットセレクターからデバイスを選択して、**Run** を押します。

<a href="https://docs.flutter.dev/tools/android-studio#running-and-debugging" target="_BLANK">Flutter ドキュメント：実行とデバッグ ↗</a>
</x-doc-tab>

<x-doc-tab label="VS Code">
プロジェクトフォルダーを開き、コマンドパレットから **Debug: Start Without Debugging** を実行します。

<a href="https://docs.flutter.dev/tools/vs-code#run-app-without-breakpoints" target="_BLANK">Flutter ドキュメント：ブレークポイントなしで実行 ↗</a>
</x-doc-tab>
</x-doc-tabs>


<div id="metro-cli"></div>

## Metro CLI

Metro がプロジェクトファイルを生成します。引数なしで実行するとメニューが表示され、コマンドを直接呼び出すこともできます。

``` plaintext
$ metro

Metro - Nylo's Companion to Build Flutter apps by Anthony Gordon

Usage:
    command [options] [arguments]

Options
    -h

All commands:

[Widget Commands]
  make:page
  make:stateful_widget
  make:stateless_widget
  make:state_managed_widget
  make:navigation_hub
  make:journey_widget
  make:bottom_sheet_modal
  make:button
  make:form

[Helper Commands]
  make:model
  make:provider
  make:api_service
  make:controller
  make:event
  make:route_guard
  make:config
  make:interceptor
  make:command
  make:env
```

### コマンドリファレンス

各コマンドには名前を指定します。例：`metro make:page settings_page`

<x-doc-commands title="ウィジェットコマンド" rows="
metro make:page | 新しいページを作成
metro make:stateful_widget | Stateful Widget を作成
metro make:stateless_widget | Stateless Widget を作成
metro make:state_managed_widget | State 管理 Widget を作成
metro make:navigation_hub | Navigation Hub（ボトムナビ）を作成
metro make:journey_widget | Navigation Hub 用の Journey Widget を作成
metro make:bottom_sheet_modal | ボトムシートモーダルを作成
metro make:button | カスタムボタン Widget を作成
metro make:form | バリデーション付きフォームを作成
" />

<x-doc-commands title="ヘルパーコマンド" rows="
metro make:model | Model クラスを作成
metro make:provider | Provider を作成
metro make:api_service | API サービスを作成
metro make:controller | Controller を作成
metro make:event | イベントを作成
metro make:route_guard | Route Guard を作成
metro make:config | 設定ファイルを作成
metro make:interceptor | ネットワークインターセプターを作成
metro make:command | カスタム Metro コマンドを作成
metro make:env | .env から環境設定を生成
" />

### 使用例

``` bash
# 新しいページを作成
metro make:page settings_page

# Model を作成
metro make:model User

# API サービスを作成
metro make:api_service user_api_service
```


<div id="nylo-cli"></div>

## Nylo CLI

<!-- uncertain: strip label "Requires" — no established translation in this locale's existing files -->
<x-doc-strip label="必須" items="nylo_installer 1.8.0+" linkText="CLI の更新" linkHref="#nylo-self-update" />

ステップ 1 の `nylo` コマンドは、プロジェクトの作成以外にも使えます。プロジェクトディレクトリから実行することで、ビルドのクリーンアップ、iOS Pod のリフレッシュ、テストの実行、翻訳のチェックができます。

<x-doc-commands title="プロジェクトコマンド" rows="
nylo new | 新しいプロジェクトを作成
nylo init | シェルに metro エイリアスを設定
nylo clean | flutter clean と flutter pub get を実行
nylo ios:pod-refresh | iOS Pod を再インストール
nylo metro | エイリアスなしで Metro コマンドを実行
" />

<x-doc-commands title="テストと翻訳" rows="
nylo test | テストをフォーマットして実行
nylo locale:find-untranslated | .tr() でラップされていないテキストを検索
nylo locale:check-missing-keys | lang ファイルに不足しているキーを検索
" />

<x-doc-commands title="CLI 自体" rows="
nylo self-update | nylo を最新バージョンに更新
nylo --version | インストール済みのバージョンを表示
" />

オプションを受け取るコマンドは `-h` で一覧表示できます。例えば `nylo test -h` のようにします。テストについては [テストの実行](/docs/{{ $version }}/testing#running-tests) を、`locale:` コマンドについては [翻訳の監査](/docs/{{ $version }}/localization#auditing-translations) を参照してください。

<div id="nylo-clean"></div>

### プロジェクトのクリーンアップ

`nylo clean` は `flutter clean` を実行した後、`flutter pub get` を実行します。通常のクリーンでは不十分な場合は、プラットフォームフラグを追加してください:

``` bash
# flutter clean を実行後、flutter pub get を実行
nylo clean

# ios/Pods、ios/.symlinks、ios/Podfile.lock も削除し、pod install --repo-update を実行
nylo clean --ios

# android/ で gradlew clean も実行
nylo clean --android

# 上記の両方
nylo clean --all
```

プロジェクトに `ios/` または `android/` フォルダーがない場合、そのプラットフォームは警告とともにスキップされます。`pod install` は macOS でのみ実行されます。

<div id="nylo-ios-pod-refresh"></div>

### iOS Pod のリフレッシュ

CocoaPods が同期しなくなった場合、`nylo ios:pod-refresh` は `ios/Pods`、`ios/.symlinks`、`ios/Podfile.lock` を削除し、`ios/` フォルダーで `pod install --repo-update` を実行します:

``` bash
nylo ios:pod-refresh
```

macOS 上でプロジェクトのルートから実行してください。`nylo clean --ios` とは異なり、`flutter clean` や `flutter pub get` は実行しません。

<div id="nylo-metro"></div>

### エイリアスなしで Metro を実行

`nylo metro` は、それ以降に指定したものをすべて Metro（`dart run nylo_framework:main`）に渡します。`nylo init` を実行する前や、`nylo init` が `metro` エイリアスを追加できない Windows で使用してください:

``` bash
nylo metro make:page settings_page
nylo metro make:model User
```

<div id="nylo-self-update"></div>

### CLI の更新

``` bash
nylo self-update
```

これは pub.dev を確認し、新しいバージョンが利用可能な場合は `nylo_installer` を再インストールします。コマンドの実行後、`nylo` はアップデートが利用可能な場合にも通知します。出力先がターミナルでない場合は静かになるため、この通知が CI ログやパイプ出力に混ざることはありません。

インストール済みのバージョンを確認するには:

``` bash
nylo --version
```
