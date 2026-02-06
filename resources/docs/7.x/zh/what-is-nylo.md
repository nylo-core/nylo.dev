# 什么是 {{ config('app.name') }}？

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 应用开发
    - [Flutter 新手？](#new-to-flutter "Flutter 新手？")
    - [维护和发布计划](#maintenance-and-release-schedule "维护和发布计划")
- 致谢
    - [框架依赖](#framework-dependencies "框架依赖")
    - [贡献者](#contributors "贡献者")


<div id="introduction"></div>

## 简介

{{ config('app.name') }} 是一个为 Flutter 设计的微框架，旨在帮助简化应用开发。它提供了一个结构化的模板项目，预配置了必要的基础设施，让您可以专注于构建应用的功能。

{{ config('app.name') }} 开箱即用，包含以下功能：

- **路由** - 简单、声明式的路由管理，支持守卫和深度链接
- **网络** - 基于 Dio 的 API 服务，支持拦截器和响应转换
- **状态管理** - 使用 NyState 实现响应式状态和全局状态更新
- **本地化** - 使用 JSON 翻译文件的多语言支持
- **主题** - 明暗模式及主题切换
- **本地存储** - 使用 Backpack 和 NyStorage 的安全存储
- **表单** - 表单处理，支持验证和字段类型
- **推送通知** - 本地和远程通知支持
- **CLI 工具 (Metro)** - 生成页面、控制器、模型等

<div id="new-to-flutter"></div>

## Flutter 新手？

如果您是 Flutter 新手，请从官方资源开始：

- <a href="https://flutter.dev" target="_BLANK">Flutter 文档</a> - 全面的指南和 API 参考
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Flutter YouTube 频道</a> - 教程和更新
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - 常见任务的实用方案

熟悉 Flutter 基础之后，{{ config('app.name') }} 将会很直观，因为它建立在标准的 Flutter 模式之上。


<div id="maintenance-and-release-schedule"></div>

## 维护和发布计划

{{ config('app.name') }} 遵循 <a href="https://semver.org" target="_BLANK">语义化版本</a>：

- **主要版本** (7.x → 8.x) - 每年一次，包含破坏性更改
- **次要版本** (7.0 → 7.1) - 新功能，向后兼容
- **补丁版本** (7.0.0 → 7.0.1) - Bug 修复和小改进

Bug 修复和安全补丁通过 GitHub 仓库及时处理。


<div id="framework-dependencies"></div>

## 框架依赖

{{ config('app.name') }} v7 基于以下开源包构建：

### 核心依赖

| 包 | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | 用于 API 请求的 HTTP 客户端 |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | 安全本地存储 |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | 国际化和格式化 |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | 流的响应式扩展 |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | 对象值等价 |

### UI 和组件

| 包 | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | 骨架屏加载效果 |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Toast 通知 |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | 下拉刷新功能 |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | 瀑布流网格布局 |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | 日期选择器字段 |

### 通知和连接

| 包 | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | 本地推送通知 |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | 网络连接状态 |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | 应用图标角标 |

### 工具

| 包 | 用途 |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | 打开 URL 和应用 |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | 字符串大小写转换 |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | UUID 生成 |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | 文件系统路径 |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | 输入遮罩 |


<div id="contributors"></div>

## 贡献者

感谢所有为 {{ config('app.name') }} 做出贡献的人！如果您有过贡献，请通过 <a href="mailto:support@nylo.dev">support@nylo.dev</a> 联系我们以添加到此处。

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a>（创建者）
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
