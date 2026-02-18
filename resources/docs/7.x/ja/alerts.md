# アラート

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [組み込みスタイル](#built-in-styles "組み込みスタイル")
- [ページからアラートを表示](#from-pages "ページからアラートを表示")
- [コントローラからアラートを表示](#from-controllers "コントローラからアラートを表示")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [表示位置](#positioning "表示位置")
- [カスタムトーストスタイル](#custom-styles "カスタムトーストスタイル")
  - [スタイルの登録](#registering-styles "スタイルの登録")
  - [スタイルファクトリの作成](#creating-a-style-factory "スタイルファクトリの作成")
- [AlertTab](#alert-tab "AlertTab")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は、ユーザーにアラートを表示するためのトースト通知システムを提供します。**success**、**warning**、**info**、**danger** の 4 つの組み込みスタイルが付属しており、レジストリパターンを通じてカスタムスタイルもサポートしています。

アラートは、ページ、コントローラ、または `BuildContext` がある任意の場所からトリガーできます。

<div id="basic-usage"></div>

## 基本的な使い方

任意の `NyState` ページで便利メソッドを使用してトースト通知を表示します:

``` dart
// 成功トースト
showToastSuccess(description: "Item saved successfully");

// 警告トースト
showToastWarning(description: "Your session is about to expire");

// 情報トースト
showToastInfo(description: "New version available");

// 危険トースト
showToastDanger(description: "Failed to save item");
```

またはスタイル ID を指定してグローバル関数を使用します:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## 組み込みスタイル

{{ config('app.name') }} は 4 つのデフォルトトーストスタイルを登録しています:

| スタイル ID | アイコン | 色 | デフォルトタイトル |
|----------|------|-------|---------------|
| `success` | チェックマーク | 緑 | Success |
| `warning` | 感嘆符 | オレンジ | Warning |
| `info` | 情報アイコン | ティール | Info |
| `danger` | 警告アイコン | 赤 | Error |

これらは `lib/config/toast_notification.dart` で設定されています:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## ページからアラートを表示

`NyState` または `NyBaseState` を継承する任意のページで、以下の便利メソッドを使用します:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // 成功
    showToastSuccess(description: "Saved!");

    // カスタムタイトル付き
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // 警告
    showToastWarning(description: "Check your input");

    // 情報
    showToastInfo(description: "Tip: Swipe left to delete");

    // 危険
    showToastDanger(description: "Something went wrong");

    // Oops（danger スタイルを使用）
    showToastOops(description: "That didn't work");

    // Sorry（danger スタイルを使用）
    showToastSorry(description: "We couldn't process your request");

    // カスタムスタイル（ID 指定）
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### 汎用トーストメソッド

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## コントローラからアラートを表示

`NyController` を継承するコントローラには同じ便利メソッドがあります:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

利用可能なメソッド: `showToastSuccess`、`showToastWarning`、`showToastInfo`、`showToastDanger`、`showToastOops`、`showToastSorry`、`showToastCustom`。

<div id="show-toast-notification"></div>

## showToastNotification

グローバル関数 `showToastNotification()` は、`BuildContext` がある任意の場所からトーストを表示します:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // トーストがタップされた時に呼び出される
    routeTo("/details");
  },
  onDismiss: () {
    // トーストが閉じられた時に呼び出される
  },
  onShow: () {
    // トーストが表示された時に呼び出される
  },
);
```

### パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 必須 | ビルドコンテキスト |
| `id` | `String` | `'success'` | トーストスタイル ID |
| `title` | `String?` | null | デフォルトタイトルを上書き |
| `description` | `String?` | null | 説明テキスト |
| `duration` | `Duration?` | null | トーストの表示時間 |
| `position` | `ToastNotificationPosition?` | null | 画面上の位置 |
| `action` | `VoidCallback?` | null | タップコールバック |
| `onDismiss` | `VoidCallback?` | null | 閉じるコールバック |
| `onShow` | `VoidCallback?` | null | 表示コールバック |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` はトースト通知のすべてのデータをカプセル化します:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### プロパティ

| プロパティ | 型 | デフォルト | 説明 |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | アイコンウィジェット |
| `title` | `String` | `''` | タイトルテキスト |
| `style` | `String` | `''` | スタイル識別子 |
| `description` | `String` | `''` | 説明テキスト |
| `color` | `Color?` | null | アイコンセクションの背景色 |
| `action` | `VoidCallback?` | null | タップコールバック |
| `dismiss` | `VoidCallback?` | null | 閉じるボタンコールバック |
| `onDismiss` | `VoidCallback?` | null | 自動/手動閉じるコールバック |
| `onShow` | `VoidCallback?` | null | 表示時コールバック |
| `duration` | `Duration` | 5 秒 | 表示時間 |
| `position` | `ToastNotificationPosition` | `top` | 画面上の位置 |
| `metaData` | `Map<String, dynamic>?` | null | カスタムメタデータ |

### copyWith

`ToastMeta` の変更されたコピーを作成します:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## 表示位置

トーストが画面のどこに表示されるかを制御します:

``` dart
// 画面上部（デフォルト）
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// 画面下部
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// 画面中央
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## カスタムトーストスタイル

<div id="registering-styles"></div>

### スタイルの登録

`AppProvider` でカスタムスタイルを登録します:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

またはいつでもスタイルを追加できます:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

使用方法:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### スタイルファクトリの作成

`ToastNotification.style()` は `ToastStyleFactory` を作成します:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `icon` | `Widget` | トーストのアイコンウィジェット |
| `color` | `Color` | アイコンセクションの背景色 |
| `defaultTitle` | `String` | タイトルが指定されない場合に表示されるタイトル |
| `position` | `ToastNotificationPosition?` | デフォルトの表示位置 |
| `duration` | `Duration?` | デフォルトの表示時間 |
| `builder` | `Widget Function(ToastMeta)?` | 完全制御用のカスタムウィジェットビルダー |

### 完全カスタムビルダー

トーストウィジェットを完全に制御するには:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` は、ナビゲーションタブに通知インジケーターを追加するためのバッジウィジェットです。トグル可能で、オプションでストレージに永続化できるバッジを表示します。

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `state` | `String` | 必須 | トラッキング用の状態名 |
| `alertEnabled` | `bool?` | null | バッジを表示するかどうか |
| `rememberAlert` | `bool?` | `true` | バッジの状態をストレージに永続化 |
| `icon` | `Widget?` | null | タブアイコン |
| `backgroundColor` | `Color?` | null | タブの背景色 |
| `textColor` | `Color?` | null | バッジのテキスト色 |
| `alertColor` | `Color?` | null | バッジの背景色 |
| `smallSize` | `double?` | null | 小さいバッジのサイズ |
| `largeSize` | `double?` | null | 大きいバッジのサイズ |
| `textStyle` | `TextStyle?` | null | バッジのテキストスタイル |
| `padding` | `EdgeInsetsGeometry?` | null | バッジのパディング |
| `alignment` | `Alignment?` | null | バッジの配置 |
| `offset` | `Offset?` | null | バッジのオフセット |
| `isLabelVisible` | `bool?` | `true` | バッジラベルを表示 |

### ファクトリコンストラクタ

`NavigationTab` から作成します:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## 使用例

### 保存後の成功アラート

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### アクション付きインタラクティブトースト

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### 下部に表示される警告

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
