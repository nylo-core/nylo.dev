# ローカル通知

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [スケジュール通知](#scheduled-notifications "スケジュール通知")
- [ビルダーパターン](#builder-pattern "ビルダーパターン")
- [プラットフォーム設定](#platform-configuration "プラットフォーム設定")
  - [Android の設定](#android-configuration "Android の設定")
  - [iOS の設定](#ios-configuration "iOS の設定")
- [通知の管理](#managing-notifications "通知の管理")
- [パーミッション](#permissions "パーミッション")
- [プラットフォームセットアップ](#platform-setup "プラットフォームセットアップ")
- [API リファレンス](#api-reference "API リファレンス")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は `LocalNotification` クラスを通じてローカル通知システムを提供します。これにより、iOS と Android でリッチなコンテンツを持つ即時通知またはスケジュール通知を送信できます。

> ローカル通知は Web ではサポートされていません。Web で使用しようとすると `NotificationException` がスローされます。

<div id="basic-usage"></div>

## 基本的な使い方

ビルダーパターンまたは静的メソッドを使用して通知を送信します:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// ビルダーパターン
await LocalNotification(title: "Hello", body: "World").send();

// ヘルパー関数の使用
await localNotification("Hello", "World").send();

// 静的メソッドの使用
await LocalNotification.sendNotification(
  title: "Hello",
  body: "World",
);
```

<div id="scheduled-notifications"></div>

## スケジュール通知

特定の時間に配信される通知をスケジュールします:

``` dart
// 明日スケジュール
final tomorrow = DateTime.now().add(Duration(days: 1));

await LocalNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
).send(at: tomorrow);

// 静的メソッドの使用
await LocalNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow,
);
```

<div id="builder-pattern"></div>

## ビルダーパターン

`LocalNotification` クラスは流暢なビルダー API を提供します。すべてのチェーン可能なメソッドは `LocalNotification` インスタンスを返します:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### チェーン可能なメソッド

| メソッド | パラメータ | 説明 |
|--------|------------|-------------|
| `addPayload` | `String payload` | 通知のカスタムデータ文字列を設定 |
| `addId` | `int id` | 通知の一意の識別子を設定 |
| `addSubtitle` | `String subtitle` | サブタイトルテキストを設定 |
| `addBadgeNumber` | `int badgeNumber` | アプリアイコンのバッジ番号を設定 |
| `addSound` | `String sound` | カスタムサウンドファイル名を設定 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | 添付ファイルを追加（iOS のみ、URL からダウンロード） |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Android 固有の設定を指定 |
| `setIOSConfig` | `IOSNotificationConfig config` | iOS 固有の設定を指定 |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

`at` が指定された場合、その時間に通知をスケジュールします。それ以外の場合は即座に表示します。

<div id="platform-configuration"></div>

## プラットフォーム設定

v7 では、プラットフォーム固有のオプションは `AndroidNotificationConfig` と `IOSNotificationConfig` オブジェクトを通じて設定します。

<div id="android-configuration"></div>

### Android の設定

`setAndroidConfig()` に `AndroidNotificationConfig` を渡します:

``` dart
await LocalNotification(
  title: "Android Notification",
  body: "With custom configuration",
)
.setAndroidConfig(AndroidNotificationConfig(
  channelId: "custom_channel",
  channelName: "Custom Channel",
  channelDescription: "Notifications from custom channel",
  importance: Importance.max,
  priority: Priority.high,
  enableVibration: true,
  vibrationPattern: [0, 1000, 500, 1000],
  enableLights: true,
  ledColor: Color(0xFF00FF00),
))
.send();
```

#### AndroidNotificationConfig のプロパティ

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | 通知チャンネル ID |
| `channelName` | `String?` | `'Default Channel'` | 通知チャンネル名 |
| `channelDescription` | `String?` | `'Default Channel'` | チャンネルの説明 |
| `importance` | `Importance?` | `Importance.max` | 通知の重要度レベル |
| `priority` | `Priority?` | `Priority.high` | 通知の優先度 |
| `ticker` | `String?` | `'ticker'` | アクセシビリティ用ティッカーテキスト |
| `icon` | `String?` | `'app_icon'` | スモールアイコンリソース名 |
| `playSound` | `bool?` | `true` | サウンドを再生するかどうか |
| `enableVibration` | `bool?` | `true` | バイブレーションを有効にするかどうか |
| `vibrationPattern` | `List<int>?` | - | カスタムバイブレーションパターン（ms） |
| `groupKey` | `String?` | - | 通知グループ化のグループキー |
| `setAsGroupSummary` | `bool?` | `false` | グループサマリーかどうか |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | グループのアラート動作 |
| `autoCancel` | `bool?` | `true` | タップで自動消去 |
| `ongoing` | `bool?` | `false` | ユーザーが消去不可 |
| `silent` | `bool?` | `false` | サイレント通知 |
| `color` | `Color?` | - | アクセントカラー |
| `largeIcon` | `String?` | - | ラージアイコンリソースパス |
| `onlyAlertOnce` | `bool?` | `false` | 初回表示時のみアラート |
| `showWhen` | `bool?` | `true` | タイムスタンプを表示 |
| `when` | `int?` | - | カスタムタイムスタンプ（エポックからの ms） |
| `usesChronometer` | `bool?` | `false` | クロノメーター表示を使用 |
| `chronometerCountDown` | `bool?` | `false` | クロノメーターをカウントダウン |
| `channelShowBadge` | `bool?` | `true` | チャンネルにバッジを表示 |
| `showProgress` | `bool?` | `false` | プログレスインジケーターを表示 |
| `maxProgress` | `int?` | `0` | 最大プログレス値 |
| `progress` | `int?` | `0` | 現在のプログレス値 |
| `indeterminate` | `bool?` | `false` | 不確定プログレス |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | チャンネルアクション |
| `enableLights` | `bool?` | `false` | 通知 LED を有効化 |
| `ledColor` | `Color?` | - | LED カラー |
| `ledOnMs` | `int?` | - | LED 点灯時間（ms） |
| `ledOffMs` | `int?` | - | LED 消灯時間（ms） |
| `visibility` | `NotificationVisibility?` | - | ロック画面での表示 |
| `timeoutAfter` | `int?` | - | 自動消去タイムアウト（ms） |
| `fullScreenIntent` | `bool?` | `false` | フルスクリーンインテントとして起動 |
| `shortcutId` | `String?` | - | ショートカット ID |
| `additionalFlags` | `List<int>?` | - | 追加フラグ |
| `tag` | `String?` | - | 通知タグ |
| `actions` | `List<AndroidNotificationAction>?` | - | アクションボタン |
| `colorized` | `bool?` | `false` | カラー化を有効化 |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | オーディオ属性の使用 |

<div id="ios-configuration"></div>

### iOS の設定

`setIOSConfig()` に `IOSNotificationConfig` を渡します:

``` dart
await LocalNotification(
  title: "iOS Notification",
  body: "With custom configuration",
)
.setIOSConfig(IOSNotificationConfig(
  presentAlert: true,
  presentBanner: true,
  presentSound: true,
  threadIdentifier: "thread_1",
  interruptionLevel: InterruptionLevel.active,
))
.addBadgeNumber(1)
.addSound("custom_sound.wav")
.send();
```

#### IOSNotificationConfig のプロパティ

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | 通知リストに表示 |
| `presentAlert` | `bool?` | `true` | アラートを表示 |
| `presentBadge` | `bool?` | `true` | アプリバッジを更新 |
| `presentSound` | `bool?` | `true` | サウンドを再生 |
| `presentBanner` | `bool?` | `true` | バナーを表示 |
| `sound` | `String?` | - | サウンドファイル名 |
| `badgeNumber` | `int?` | - | バッジ番号 |
| `threadIdentifier` | `String?` | - | グループ化用スレッド識別子 |
| `categoryIdentifier` | `String?` | - | アクション用カテゴリ識別子 |
| `interruptionLevel` | `InterruptionLevel?` | - | 割り込みレベル |

### 添付ファイル（iOS のみ）

iOS 通知に画像、オーディオ、または動画の添付ファイルを追加します。添付ファイルは URL からダウンロードされます:

``` dart
await LocalNotification(
  title: "New Photo",
  body: "Check out this image!",
)
.addAttachment(
  "https://example.com/image.jpg",
  "photo.jpg",
  showThumbnail: true,
)
.send();
```

<div id="managing-notifications"></div>

## 通知の管理

### 特定の通知をキャンセル

``` dart
await LocalNotification.cancelNotification(42);

// タグ付き（Android）
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### すべての通知をキャンセル

``` dart
await LocalNotification.cancelAllNotifications();
```

### バッジカウントをクリア

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## パーミッション

ユーザーに通知パーミッションをリクエストします:

``` dart
// デフォルトでリクエスト
await LocalNotification.requestPermissions();

// 特定のオプション付きでリクエスト
await LocalNotification.requestPermissions(
  alert: true,
  badge: true,
  sound: true,
  provisional: false,
  critical: false,
  vibrate: true,
  enableLights: true,
  channelId: 'default_notification_channel_id',
  channelName: 'Default Notification Channel',
);
```

パーミッションは `NyScheduler.taskOnce` を通じて最初の通知送信時に自動的にリクエストされます。

<div id="platform-setup"></div>

## プラットフォームセットアップ

### iOS セットアップ

`Info.plist` に追加します:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android セットアップ

`AndroidManifest.xml` に追加します:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API リファレンス

### 静的メソッド

| メソッド | シグネチャ | 説明 |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | すべてのオプション付きで通知を送信 |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | 特定の通知をキャンセル |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | すべての通知をキャンセル |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | 通知パーミッションをリクエスト |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | iOS バッジカウントをクリア |

### インスタンスメソッド（チェーン可能）

| メソッド | パラメータ | 戻り値 | 説明 |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | ペイロードデータを設定 |
| `addId` | `int id` | `LocalNotification` | 通知 ID を設定 |
| `addSubtitle` | `String subtitle` | `LocalNotification` | サブタイトルを設定 |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | バッジ番号を設定 |
| `addSound` | `String sound` | `LocalNotification` | カスタムサウンドを設定 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | 添付ファイルを追加（iOS） |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Android 設定を指定 |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | iOS 設定を指定 |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | 通知を送信 |
