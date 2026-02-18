# {{ config('app.name') }} への貢献

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [始め方](#getting-started "始め方")
- [開発環境](#development-environment "開発環境")
- [開発ガイドライン](#development-guidelines "開発ガイドライン")
- [変更の提出](#submitting-changes "変更の提出")
- [Issue の報告](#reporting-issues "Issue の報告")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} への貢献をご検討いただきありがとうございます！

このガイドでは、マイクロフレームワークへの貢献方法を理解するのに役立ちます。バグ修正、機能追加、ドキュメントの改善など、あなたの貢献は {{ config('app.name') }} コミュニティにとって貴重です。

{{ config('app.name') }} は 3 つのリポジトリに分かれています:

| リポジトリ | 目的 |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | ボイラープレートアプリケーション |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | コアフレームワーククラス (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | ウィジェット、ヘルパー、ユーティリティを含むサポートライブラリ (nylo_support) |

<div id="getting-started"></div>

## 始め方

### リポジトリのフォーク

貢献したいリポジトリをフォークしてください:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Nylo ボイラープレートをフォーク</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Framework をフォーク</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Support をフォーク</a>

### フォークのクローン

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## 開発環境

### 必要条件

以下がインストールされていることを確認してください:

| 必要条件 | 最低バージョン |
|-------------|-----------------|
| Flutter | 3.24.0 以上 |
| Dart SDK | 3.10.7 以上 |

### ローカルパッケージのリンク

エディタで Nylo ボイラープレートを開き、ローカルの framework と support リポジトリを使用するために依存関係のオーバーライドを追加します:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # ローカルの framework リポジトリへのパス
  nylo_support:
    path: ../support    # ローカルの support リポジトリへのパス
```

`flutter pub get` を実行して依存関係をインストールします。

これで framework または support リポジトリに加えた変更が Nylo ボイラープレートに反映されます。

### 変更のテスト

ボイラープレートアプリを実行して変更をテストします:

``` bash
flutter run
```

ウィジェットやヘルパーの変更については、適切なリポジトリにテストを追加することを検討してください。

<div id="development-guidelines"></div>

## 開発ガイドライン

### コードスタイル

- 公式の <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">Dart スタイルガイド</a>に従ってください
- 意味のある変数名と関数名を使用してください
- 複雑なロジックには明確なコメントを書いてください
- 公開 API にはドキュメントを含めてください
- コードをモジュラーで保守可能な状態に保ってください

### ドキュメント

新機能を追加する場合:

- 公開クラスとメソッドに dartdoc コメントを追加してください
- 必要に応じて関連するドキュメントファイルを更新してください
- ドキュメントにコード例を含めてください

### テスト

変更を提出する前に:

- iOS と Android の両方のデバイス/シミュレーターでテストしてください
- 可能な限り後方互換性を確認してください
- 破壊的変更は明確にドキュメント化してください
- 既存のテストを実行して何も壊れていないことを確認してください

<div id="submitting-changes"></div>

## 変更の提出

### まず議論する

新機能については、まずコミュニティと議論することをお勧めします:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### ブランチの作成

``` bash
git checkout -b feature/your-feature-name
```

説明的なブランチ名を使用してください:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### 変更のコミット

``` bash
git add .
git commit -m "Add: Your feature description"
```

明確なコミットメッセージを使用してください:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### プッシュとプルリクエストの作成

``` bash
git push origin feature/your-feature-name
```

その後、GitHub でプルリクエストを作成してください。

### プルリクエストのガイドライン

- 変更内容の明確な説明を提供してください
- 関連する Issue を参照してください
- 該当する場合はスクリーンショットやコード例を含めてください
- PR は一つの問題のみに対処するようにしてください
- 変更は集中的かつアトミックに保ってください

<div id="reporting-issues"></div>

## Issue の報告

### 報告前に

1. GitHub で同じ Issue が既に存在しないか確認してください
2. 最新バージョンを使用していることを確認してください
3. 新しいプロジェクトで Issue を再現してみてください

### 報告先

適切なリポジトリに Issue を報告してください:

- **ボイラープレートの Issue**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **フレームワークの Issue**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **サポートライブラリの Issue**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Issue テンプレート

詳細な情報を提供してください:

``` markdown
### 説明
Issue の簡潔な説明

### 再現手順
1. ステップ 1
2. ステップ 2
3. ステップ 3

### 期待される動作
期待される挙動

### 実際の動作
実際に起こること

### 環境
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- デバイス: iPhone 15/Pixel 8（該当する場合）

### コード例
```dart
// Issue を再現する最小限のコード
```
```

### バージョン情報の取得

``` bash
# Flutter と Dart のバージョン
flutter --version

# Nylo バージョンは pubspec.yaml を確認
# nylo_framework: ^7.0.0
```
