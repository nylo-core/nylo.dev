# Backpack

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [读取数据](#reading-data "读取数据")
- [保存数据](#saving-data "保存数据")
- [删除数据](#deleting-data "删除数据")
- [会话](#sessions "会话")
- [访问 Nylo 实例](#nylo-instance "访问 Nylo 实例")
- [辅助函数](#helper-functions "辅助函数")
- [与 NyStorage 集成](#integration-with-nystorage "与 NyStorage 集成")
- [示例](#examples "示例")

<div id="introduction"></div>

## 简介

**Backpack** 是 {{ config('app.name') }} 中的内存单例存储系统。它在应用运行期间提供快速的同步数据访问。与将数据持久化到设备的 `NyStorage` 不同，Backpack 将数据存储在内存中，应用关闭时数据会被清除。

Backpack 被框架内部用于存储关键实例，如 `Nylo` 应用对象、`EventBus` 和身份验证数据。你也可以使用它来存储自己的数据，以便无需异步调用即可快速访问。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 保存值
Backpack.instance.save("user_name", "Anthony");

// 读取值（同步）
String? name = Backpack.instance.read("user_name");

// 删除值
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## 基本用法

Backpack 使用**单例模式**——通过 `Backpack.instance` 访问它：

``` dart
// 保存数据
Backpack.instance.save("theme", "dark");

// 读取数据
String? theme = Backpack.instance.read("theme"); // "dark"

// 检查数据是否存在
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## 读取数据

使用 `read<T>()` 方法从 Backpack 中读取值。它支持泛型类型和可选的默认值：

``` dart
// 读取 String
String? name = Backpack.instance.read<String>("name");

// 使用默认值读取
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// 读取 int
int? score = Backpack.instance.read<int>("score");
```

当提供类型参数时，Backpack 会自动将 JSON 字符串反序列化为模型对象：

``` dart
// 如果 User 模型以 JSON 字符串形式存储，将被反序列化
User? user = Backpack.instance.read<User>("current_user");

// 如果存储的是原始 Map（例如通过 NyStorage 的 syncKeys），
// 读取时也会自动反序列化为类型化模型
Backpack.instance.save("current_user", {"name": "Alice", "age": 30});
User? user = Backpack.instance.read<User>("current_user"); // 返回 User
```

<div id="saving-data"></div>

## 保存数据

使用 `save()` 方法保存值：

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### 追加数据

使用 `append()` 将值添加到存储在某个键下的列表中：

``` dart
// 追加到列表
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// 带限制地追加（仅保留最后 N 个条目）
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## 删除数据

### 删除单个键

``` dart
Backpack.instance.delete("api_token");
```

### 删除所有数据

`deleteAll()` 方法会移除所有值，**但保留**框架保留键（`nylo` 和 `event_bus`）：

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## 会话

Backpack 提供会话管理功能，用于将数据组织到命名分组中。这对于将相关数据存储在一起非常有用。

### 更新会话值

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### 获取会话值

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### 移除会话键

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### 清空整个会话

``` dart
Backpack.instance.sessionFlush("cart");
```

### 获取所有会话数据

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## 访问 Nylo 实例

Backpack 存储了 `Nylo` 应用实例。你可以使用以下方式获取它：

``` dart
Nylo nylo = Backpack.instance.nylo();
```

检查 Nylo 实例是否已初始化：

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## 辅助函数

{{ config('app.name') }} 提供了常用 Backpack 操作的全局辅助函数：

| 函数 | 描述 |
|----------|-------------|
| `backpackRead<T>(key)` | 从 Backpack 读取值 |
| `backpackSave(key, value)` | 向 Backpack 保存值 |
| `backpackDelete(key)` | 从 Backpack 删除值 |
| `backpackDeleteAll()` | 删除所有值（保留框架键） |
| `backpackNylo()` | 从 Backpack 获取 Nylo 实例 |

### 示例

``` dart
// 使用辅助函数
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// 访问 Nylo 实例
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## 与 NyStorage 集成

Backpack 与 `NyStorage` 集成，实现持久化存储与内存存储的结合：

``` dart
// 同时保存到 NyStorage（持久化）和 Backpack（内存）
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// 现在可以通过 Backpack 同步访问
String? token = Backpack.instance.read("auth_token");

// 从 NyStorage 删除时，同时从 Backpack 清除
await NyStorage.deleteAll(andFromBackpack: true);
```

这种模式对于身份验证令牌等数据非常有用，这些数据既需要持久化存储，又需要快速的同步访问（例如在 HTTP 拦截器中）。

<div id="examples"></div>

## 示例

### 为 API 请求存储身份验证令牌

``` dart
// 在你的身份验证拦截器中
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### 基于会话的购物车管理

``` dart
// 向购物车会话添加条目
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// 读取购物车数据
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// 清空购物车
Backpack.instance.sessionFlush("cart");
```

### 快速功能标志

``` dart
// 将功能标志存储在 Backpack 中以便快速访问
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// 检查功能标志
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
