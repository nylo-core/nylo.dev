# FutureWidget

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [ローディング状態のカスタマイズ](#customizing-loading "ローディング状態のカスタマイズ")
    - [通常のローディングスタイル](#normal-loading "通常のローディングスタイル")
    - [スケルトナイザーローディングスタイル](#skeletonizer-loading "スケルトナイザーローディングスタイル")
    - [ローディングなしスタイル](#no-loading "ローディングなしスタイル")
- [エラーハンドリング](#error-handling "エラーハンドリング")


<div id="introduction"></div>

## はじめに

**FutureWidget** は、{{ config('app.name') }} プロジェクトで `Future` をレンダリングするためのシンプルな方法です。Flutter の `FutureBuilder` をラップし、組み込みのローディング状態を備えたよりクリーンな API を提供します。

Future が処理中の場合、ローダーが表示されます。Future が完了すると、`child` コールバックを通じてデータが返されます。

<div id="basic-usage"></div>

## 基本的な使い方

`FutureWidget` の簡単な使用例:

``` dart
// 完了まで 3 秒かかる Future
Future<String> _findUserName() async {
  await sleep(3); // 3 秒待機
  return "John Doe";
}

// FutureWidget の使用
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

ウィジェットは Future が完了するまで、ユーザーに対してローディング状態を自動的に処理します。

<div id="customizing-loading"></div>

## ローディング状態のカスタマイズ

`loadingStyle` パラメータを使用して、ローディング状態の表示方法をカスタマイズできます。

<div id="normal-loading"></div>

### 通常のローディングスタイル

`LoadingStyle.normal()` を使用して、標準的なローディングウィジェットを表示します。オプションでカスタム child ウィジェットを指定できます:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // カスタムローディングウィジェット
  ),
)
```

child が指定されない場合、デフォルトの {{ config('app.name') }} アプリローダーが表示されます。

<div id="skeletonizer-loading"></div>

### スケルトナイザーローディングスタイル

`LoadingStyle.skeletonizer()` を使用して、スケルトンローディングエフェクトを表示します。コンテンツレイアウトに合わせたプレースホルダー UI を表示するのに最適です:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // スケルトンプレースホルダー
    effect: SkeletonizerEffect.shimmer, // shimmer、pulse、または solid
  ),
)
```

利用可能なスケルトンエフェクト:
- `SkeletonizerEffect.shimmer` - アニメーションシマーエフェクト（デフォルト）
- `SkeletonizerEffect.pulse` - パルスアニメーションエフェクト
- `SkeletonizerEffect.solid` - ソリッドカラーエフェクト

<div id="no-loading"></div>

### ローディングなしスタイル

`LoadingStyle.none()` を使用して、ローディングインジケーターを完全に非表示にします:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## エラーハンドリング

`onError` コールバックを使用して、Future のエラーを処理できます:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

`onError` コールバックが提供されておらず、エラーが発生した場合、空の `SizedBox.shrink()` が表示されます。

### パラメータ

| パラメータ | 型 | 説明 |
|-----------|------|-------------|
| `future` | `Future<T>?` | 待機する Future |
| `child` | `Widget Function(BuildContext, T?)` | Future 完了時に呼び出されるビルダー関数 |
| `loadingStyle` | `LoadingStyle?` | ローディングインジケーターのカスタマイズ |
| `onError` | `Widget Function(AsyncSnapshot)?` | Future でエラーが発生した場合に呼び出されるビルダー関数 |
