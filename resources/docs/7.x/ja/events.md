# イベント

---

<a name="section-1"></a>
- [はじめに](#introduction)
  - [イベントについて](#understanding-events)
  - [一般的なイベントの例](#common-event-examples)
- [イベントの作成](#creating-an-event)
  - [イベントの構造](#event-structure)
- [イベントのディスパッチ](#dispatching-events)
  - [基本的なイベントディスパッチ](#basic-event-dispatch)
  - [データ付きディスパッチ](#dispatching-with-data)
  - [イベントのブロードキャスト](#broadcasting-events)
- [イベントのリッスン](#listening-to-events)
  - [`listenOn` ヘルパーの使用](#using-the-listenon-helper)
  - [`listen` ヘルパーの使用](#using-the-listen-helper)
  - [イベントの購読解除](#unsubscribing-from-events)
- [リスナーの操作](#working-with-listeners)
  - [複数リスナーの追加](#adding-multiple-listeners)
  - [リスナーロジックの実装](#implementing-listener-logic)
- [グローバルイベントブロードキャスト](#global-event-broadcasting)
  - [グローバルブロードキャストの有効化](#enabling-global-broadcasting)

<div id="introduction"></div>

## はじめに

イベントは、アプリケーションで何かが発生した後にロジックを処理する必要がある場合に強力です。{{ config('app.name') }} のイベントシステムを使用すると、アプリケーションのどこからでもイベントを作成、ディスパッチ、リッスンでき、レスポンシブでイベント駆動型の Flutter アプリケーションを簡単に構築できます。

<div id="understanding-events"></div>

### イベントについて

イベント駆動プログラミングは、ユーザーアクション、センサー出力、他のプログラムやスレッドからのメッセージなどのイベントによってアプリケーションのフローが決定されるパラダイムです。このアプローチは、アプリケーションのさまざまな部分を疎結合にし、コードの保守性と理解しやすさを向上させます。

<div id="common-event-examples"></div>

### 一般的なイベントの例

アプリケーションで使用される典型的なイベントの例:
- ユーザー登録の完了
- ユーザーのログイン/ログアウト
- 商品のカート追加
- 支払い処理の成功
- データ同期の完了
- プッシュ通知の受信

<div id="creating-an-event"></div>

## イベントの作成

{{ config('app.name') }} フレームワーク CLI または Metro を使用して新しいイベントを作成できます:

```bash
metro make:event PaymentSuccessfulEvent
```

コマンドを実行すると、`app/events/` ディレクトリに新しいイベントファイルが作成されます。

<div id="event-structure"></div>

### イベントの構造

新しく作成されたイベントファイルの構造（例: `app/events/payment_successful_event.dart`）:

```dart
import 'package:nylo_framework/nylo_framework.dart';

class PaymentSuccessfulEvent implements NyEvent {
  final listeners = {
    DefaultListener: DefaultListener(),
  };
}

class DefaultListener extends NyListener {
  handle(dynamic event) async {
    // イベントからのペイロードを処理
  }
}
```

<div id="dispatching-events"></div>

## イベントのディスパッチ

イベントは `event` ヘルパーメソッドを使用してアプリケーションのどこからでもディスパッチできます。

<div id="basic-event-dispatch"></div>

### 基本的なイベントディスパッチ

データなしでイベントをディスパッチする場合:

```dart
event<PaymentSuccessfulEvent>();
```

<div id="dispatching-with-data"></div>

### データ付きディスパッチ

イベントと一緒にデータを渡す場合:

```dart
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
  'transactionId': 'txn_123456'
});
```

<div id="broadcasting-events"></div>

### イベントのブロードキャスト

デフォルトでは、{{ config('app.name') }} のイベントはイベントクラスで定義されたリスナーのみが処理します。イベントをブロードキャスト（外部リスナーに利用可能に）するには、`broadcast` パラメータを使用します:

```dart
event<PaymentSuccessfulEvent>(
  data: {'user': user, 'amount': amount},
  broadcast: true
);
```

<div id="listening-to-events"></div>

## イベントのリッスン

{{ config('app.name') }} はイベントをリッスンして応答するための複数の方法を提供しています。

<div id="using-the-listenon-helper"></div>

### `listenOn` ヘルパーの使用

`listenOn` ヘルパーはアプリケーションのどこでも使用でき、ブロードキャストされたイベントをリッスンできます:

```dart
NyEventSubscription subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // イベントデータにアクセス
  final user = data['user'];
  final amount = data['amount'];

  // イベントを処理
  showSuccessMessage("$amount の支払いを受け取りました");
});
```

<div id="using-the-listen-helper"></div>

### `listen` ヘルパーの使用

`listen` ヘルパーは `NyPage` と `NyState` クラスで使用できます。Widget が破棄されると自動的に購読を解除します:

```dart
class _CheckoutPageState extends NyPage<CheckoutPage> {
  @override
  get init => () {
    listen<PaymentSuccessfulEvent>((data) {
      // 支払い成功を処理
      routeTo(OrderConfirmationPage.path);
    });

    listen<PaymentFailedEvent>((data) {
      // 支払い失敗を処理
      displayErrorMessage(data['error']);
    });
  };

  // ページ実装の残り
}
```

<div id="unsubscribing-from-events"></div>

### イベントの購読解除

`listenOn` を使用する場合、メモリリークを防ぐために手動で購読を解除する必要があります:

```dart
// 購読を保存
final subscription = listenOn<PaymentSuccessfulEvent>((data) {
  // イベントを処理
});

// 不要になったら解除
subscription.cancel();
```

`listen` ヘルパーは Widget が破棄される際に自動的に購読解除を処理します。

<div id="working-with-listeners"></div>

## リスナーの操作

リスナーはイベントに応答するクラスです。各イベントは、イベントのさまざまな側面を処理するための複数のリスナーを持つことができます。

<div id="adding-multiple-listeners"></div>

### 複数リスナーの追加

`listeners` プロパティを更新することで、イベントに複数のリスナーを追加できます:

```dart
class PaymentSuccessfulEvent implements NyEvent {
  final listeners = {
    NotificationListener: NotificationListener(),
    AnalyticsListener: AnalyticsListener(),
    OrderProcessingListener: OrderProcessingListener(),
  };
}
```

<div id="implementing-listener-logic"></div>

### リスナーロジックの実装

各リスナーは `handle` メソッドを実装してイベントを処理する必要があります:

```dart
class NotificationListener extends NyListener {
  handle(dynamic event) async {
    // ユーザーに通知を送信
    final user = event['user'];
    await NotificationService.sendNotification(
      userId: user.id,
      title: "支払い成功",
      body: "${event['amount']} の支払いが正常に処理されました。"
    );
  }
}

class AnalyticsListener extends NyListener {
  handle(dynamic event) async {
    // アナリティクスイベントを記録
    await AnalyticsService.logEvent(
      "payment_successful",
      parameters: {
        'amount': event['amount'],
        'userId': event['user'].id,
      }
    );
  }
}
```

<div id="global-event-broadcasting"></div>

## グローバルイベントブロードキャスト

毎回 `broadcast: true` を指定せずにすべてのイベントを自動的にブロードキャストしたい場合、グローバルブロードキャストを有効にできます。

<div id="enabling-global-broadcasting"></div>

### グローバルブロードキャストの有効化

`app/providers/app_provider.dart` ファイルを編集し、Nylo インスタンスに `broadcastEvents()` メソッドを追加します:

```dart
class AppProvider implements NyProvider {
  @override
  boot(Nylo nylo) async {
    // その他の設定

    // すべてのイベントのブロードキャストを有効化
    nylo.broadcastEvents();
  }
}
```

グローバルブロードキャストを有効にすると、イベントのディスパッチとリッスンをより簡潔に行えます:

```dart
// イベントをディスパッチ（broadcast: true は不要）
event<PaymentSuccessfulEvent>(data: {
  'user': user,
  'amount': amount,
});

// どこでもイベントをリッスン
listen<PaymentSuccessfulEvent>((data) {
  // イベントデータを処理
});
```
