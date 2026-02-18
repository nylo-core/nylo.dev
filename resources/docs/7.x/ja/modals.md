# モーダル

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [モーダルの作成](#creating-a-modal "モーダルの作成")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [モーダルの作成](#creating-a-modal "モーダルの作成")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [パラメータ](#parameters "パラメータ")
  - [アクション](#actions "アクション")
  - [ヘッダー](#header "ヘッダー")
  - [閉じるボタン](#close-button "閉じるボタン")
  - [カスタムデコレーション](#custom-decoration "カスタムデコレーション")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [使用例](#examples "使用例")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} は **ボトムシートモーダル** を中心としたモーダルシステムを提供します。

`BottomSheetModal` クラスは、アクション、ヘッダー、閉じるボタン、カスタムスタイリングを備えたコンテンツオーバーレイを表示するための柔軟な API を提供します。

モーダルは以下に便利です:
- 確認ダイアログ（例: ログアウト、削除）
- 簡易入力フォーム
- 複数のオプションを持つアクションシート
- 情報オーバーレイ

<div id="creating-a-modal"></div>

## モーダルの作成

Metro CLI を使用して新しいモーダルを作成できます:

``` bash
metro make:bottom_sheet_modal payment_options
```

これにより 2 つのものが生成されます:

1. **モーダルコンテンツウィジェット** `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **静的メソッド** `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart` の `BottomSheetModal` クラスに追加:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

どこからでもモーダルを表示できます:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

同じ名前のモーダルが既に存在する場合は、`--force` フラグを使用して上書きします:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## 基本的な使い方

`BottomSheetModal` を使用してモーダルを表示します:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## モーダルの作成

推奨されるパターンは、各モーダルタイプの静的メソッドを持つ `BottomSheetModal` クラスを作成することです。ボイラープレートはこの構造を提供します:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// ログアウトモーダルを表示
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

どこからでも呼び出します:

``` dart
BottomSheetModal.showLogout(context);

// カスタムコールバック付き
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // カスタムログアウトロジック
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` はモーダルを表示するためのコアメソッドです。

<div id="parameters"></div>

### パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 必須 | モーダルのビルドコンテキスト |
| `child` | `Widget` | 必須 | メインコンテンツウィジェット |
| `actionsRow` | `List<Widget>` | `[]` | 水平方向に表示されるアクションウィジェット |
| `actionsColumn` | `List<Widget>` | `[]` | 垂直方向に表示されるアクションウィジェット |
| `height` | `double?` | null | モーダルの固定高さ |
| `header` | `Widget?` | null | 上部のヘッダーウィジェット |
| `useSafeArea` | `bool` | `true` | コンテンツを SafeArea でラップ |
| `isScrollControlled` | `bool` | `false` | モーダルをスクロール可能に |
| `showCloseButton` | `bool` | `false` | X 閉じるボタンを表示 |
| `headerPadding` | `EdgeInsets?` | null | ヘッダー存在時のパディング |
| `backgroundColor` | `Color?` | null | モーダルの背景色 |
| `showHandle` | `bool` | `true` | 上部のドラッグハンドルを表示 |
| `closeButtonColor` | `Color?` | null | 閉じるボタンの背景色 |
| `closeButtonIconColor` | `Color?` | null | 閉じるボタンのアイコン色 |
| `modalDecoration` | `BoxDecoration?` | null | モーダルコンテナのカスタムデコレーション |
| `handleColor` | `Color?` | null | ドラッグハンドルの色 |

<div id="actions"></div>

### アクション

アクションはモーダルの下部に表示されるボタンです。

**行アクション** は横並びに配置され、各ボタンが等しいスペースを取ります:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**列アクション** は垂直に積み重ねられます:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### ヘッダー

メインコンテンツの上にヘッダーを追加します:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### 閉じるボタン

右上隅に閉じるボタンを表示します:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### カスタムデコレーション

モーダルコンテナの外観をカスタマイズします:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` はフォームピッカーやその他のコンポーネントで使用されるボトムシートモーダルの外観を設定します:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| プロパティ | 型 | 説明 |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | モーダルの背景色 |
| `barrierColor` | `NyColor?` | モーダル背後のオーバーレイ色 |
| `useRootNavigator` | `bool` | ルートナビゲーターを使用（デフォルト: `false`） |
| `routeSettings` | `RouteSettings?` | モーダルのルート設定 |
| `titleStyle` | `TextStyle?` | タイトルテキストのスタイル |
| `itemStyle` | `TextStyle?` | リストアイテムテキストのスタイル |
| `clearButtonStyle` | `TextStyle?` | クリアボタンテキストのスタイル |

<div id="examples"></div>

## 使用例

### 確認モーダル

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// 使用方法
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // アイテムを削除
}
```

### スクロール可能なコンテンツモーダル

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### アクションシート

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
