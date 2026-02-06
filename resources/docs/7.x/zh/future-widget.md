# FutureWidget

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [自定义加载状态](#customizing-loading "自定义加载状态")
    - [普通加载样式](#normal-loading "普通加载样式")
    - [骨架屏加载样式](#skeletonizer-loading "骨架屏加载样式")
    - [无加载样式](#no-loading "无加载样式")
- [错误处理](#error-handling "错误处理")


<div id="introduction"></div>

## 简介

**FutureWidget** 是在您的 {{ config('app.name') }} 项目中渲染 `Future` 的简便方式。它包装了 Flutter 的 `FutureBuilder` 并提供了更简洁的 API 以及内置的加载状态。

当 Future 正在处理中时，它会显示一个加载器。一旦 Future 完成，数据将通过 `child` 回调返回。

<div id="basic-usage"></div>

## 基本用法

以下是使用 `FutureWidget` 的简单示例：

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
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

该组件会自动为用户处理加载状态，直到 Future 完成。

<div id="customizing-loading"></div>

## 自定义加载状态

您可以使用 `loadingStyle` 参数自定义加载状态的显示方式。

<div id="normal-loading"></div>

### 普通加载样式

使用 `LoadingStyle.normal()` 显示标准加载组件。您可以选择提供自定义子组件：

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

如果未提供子组件，将显示默认的 {{ config('app.name') }} 应用加载器。

<div id="skeletonizer-loading"></div>

### 骨架屏加载样式

使用 `LoadingStyle.skeletonizer()` 显示骨架屏加载效果。这非常适合显示与内容布局匹配的占位 UI：

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

可用的骨架屏效果：
- `SkeletonizerEffect.shimmer` - 动画闪光效果（默认）
- `SkeletonizerEffect.pulse` - 脉冲动画效果
- `SkeletonizerEffect.solid` - 纯色效果

<div id="no-loading"></div>

### 无加载样式

使用 `LoadingStyle.none()` 完全隐藏加载指示器：

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

## 错误处理

您可以使用 `onError` 回调处理 Future 中的错误：

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

如果未提供 `onError` 回调且发生错误，将显示一个空的 `SizedBox.shrink()`。

### 参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `future` | `Future<T>?` | 要等待的 Future |
| `child` | `Widget Function(BuildContext, T?)` | Future 完成时调用的构建函数 |
| `loadingStyle` | `LoadingStyle?` | 自定义加载指示器 |
| `onError` | `Widget Function(AsyncSnapshot)?` | Future 出错时调用的构建函数 |
