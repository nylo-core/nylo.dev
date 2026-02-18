# {{ config('app.name') }} とは？

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- アプリ開発
    - [Flutter が初めての方へ](#new-to-flutter "Flutter が初めての方へ")
    - [メンテナンスとリリーススケジュール](#maintenance-and-release-schedule "メンテナンスとリリーススケジュール")
- クレジット
    - [フレームワークの依存関係](#framework-dependencies "フレームワークの依存関係")
    - [コントリビューター](#contributors "コントリビューター")


<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は、アプリ開発をシンプルにするために設計された Flutter 向けマイクロフレームワークです。事前設定済みの基本機能を備えた構造化されたボイラープレートを提供し、インフラの構築ではなくアプリの機能開発に集中できます。

{{ config('app.name') }} には以下の機能が標準で含まれています:

- **ルーティング** - ガードとディープリンクに対応したシンプルで宣言的なルート管理
- **ネットワーキング** - Dio、インターセプター、レスポンスモーフィングを備えた API サービス
- **状態管理** - NyState とグローバルな状態更新によるリアクティブな状態管理
- **ローカライゼーション** - JSON 翻訳ファイルによる多言語サポート
- **テーマ** - テーマ切り替えに対応したライト/ダークモード
- **ローカルストレージ** - Backpack と NyStorage によるセキュアなストレージ
- **フォーム** - バリデーションとフィールドタイプに対応したフォーム処理
- **プッシュ通知** - ローカルおよびリモート通知のサポート
- **CLI ツール (Metro)** - ページ、Controller、Model などの生成

<div id="new-to-flutter"></div>

## Flutter が初めての方へ

Flutter を初めて使う方は、公式リソースから始めてください:

- <a href="https://flutter.dev" target="_BLANK">Flutter ドキュメント</a> - 包括的なガイドと API リファレンス
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube チャンネル</a> - チュートリアルと最新情報
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - 一般的なタスクの実践的なレシピ

Flutter の基本に慣れれば、{{ config('app.name') }} は標準的な Flutter パターンの上に構築されているため、直感的に使えるでしょう。


<div id="maintenance-and-release-schedule"></div>

## メンテナンスとリリーススケジュール

{{ config('app.name') }} は<a href="https://semver.org" target="_BLANK">セマンティックバージョニング</a>に従っています:

- **メジャーリリース** (7.x → 8.x) - 破壊的変更を含む年1回のリリース
- **マイナーリリース** (7.0 → 7.1) - 後方互換性のある新機能
- **パッチリリース** (7.0.0 → 7.0.1) - バグ修正と軽微な改善

バグ修正とセキュリティパッチは GitHub リポジトリで迅速に対応されます。


<div id="framework-dependencies"></div>

## フレームワークの依存関係

{{ config('app.name') }} v7 は以下のオープンソースパッケージで構築されています:

### コア依存関係

| パッケージ | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | API リクエスト用の HTTP クライアント |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | セキュアなローカルストレージ |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | 国際化とフォーマット |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | ストリーム向けリアクティブ拡張 |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | オブジェクトの値の等価性 |

### UI & ウィジェット

| パッケージ | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | スケルトンローディングエフェクト |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | トースト通知 |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | プルトゥリフレッシュ機能 |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | スタッガードグリッドレイアウト |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | 日付ピッカーフィールド |

### 通知 & 接続

| パッケージ | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | ローカルプッシュ通知 |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | ネットワーク接続状態 |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | アプリアイコンバッジ |

### ユーティリティ

| パッケージ | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | URL やアプリを開く |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | 文字列のケース変換 |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID の生成 |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | ファイルシステムパス |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | 入力マスキング |


<div id="contributors"></div>

## コントリビューター

{{ config('app.name') }} に貢献してくださった皆様に感謝します！貢献された方は、<a href="mailto:support@nylo.dev">support@nylo.dev</a> までご連絡いただければ、こちらに追加いたします。

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Creator)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
