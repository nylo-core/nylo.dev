# アプリ使用状況

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [セットアップ](#setup "セットアップ")
- モニタリング
    - [アプリの起動回数](#monitoring-app-launches "アプリの起動回数")
    - [初回起動日](#monitoring-app-first-launch-date "初回起動日")
    - [初回起動からの経過日数](#monitoring-app-total-days-since-first-launch "初回起動からの経過日数")

<div id="introduction"></div>

## はじめに

{{ config('app.name') }} では、アプリの使用状況をすぐにモニタリングできますが、まずアプリの Provider のいずれかでこの機能を有効にする必要があります。

現在、{{ config('app.name') }} は以下をモニタリングできます:

- アプリの起動回数
- 初回起動日

このドキュメントを読むことで、アプリの使用状況をモニタリングする方法を学べます。

<div id="setup"></div>

## セットアップ

`app/providers/app_provider.dart` ファイルを開きます。

次に、`boot` メソッドに以下のコードを追加します。

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // アプリ使用状況のモニタリングを有効化
    );
```

これでアプリの使用状況モニタリングが有効になります。アプリ使用状況のモニタリングが有効かどうかを確認する必要がある場合は、`Nylo.instance.shouldMonitorAppUsage()` メソッドを使用できます。

<div id="monitoring-app-launches"></div>

## アプリの起動回数のモニタリング

`Nylo.appLaunchCount` メソッドを使用して、アプリが起動された回数をモニタリングできます。

> アプリの起動回数は、アプリが閉じた状態から開かれるたびにカウントされます。

このメソッドの簡単な使用例:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('アプリは $launchCount 回起動されました');
```

<div id="monitoring-app-first-launch-date"></div>

## アプリの初回起動日のモニタリング

`Nylo.appFirstLaunchDate` メソッドを使用して、アプリが初めて起動された日付をモニタリングできます。

このメソッドの使用例:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("アプリの初回起動日: $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## 初回起動からの合計経過日数のモニタリング

`Nylo.appTotalDaysSinceFirstLaunch` メソッドを使用して、アプリの初回起動からの合計経過日数をモニタリングできます。

このメソッドの使用例:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("アプリの初回起動から $totalDaysSinceFirstLaunch 日が経過しました");
```
