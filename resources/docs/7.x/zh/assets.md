# 资源文件

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 文件
  - [显示图片](#displaying-images "显示图片")
  - [自定义资源路径](#custom-asset-paths "自定义资源路径")
  - [返回资源路径](#returning-asset-paths "返回资源路径")
- 资源管理
  - [添加新文件](#adding-new-files "添加新文件")
  - [资源配置](#asset-configuration "资源配置")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 提供了用于管理 Flutter 应用中资源文件的辅助方法。资源文件存储在 `assets/` 目录中，包括图片、视频、字体和其他文件。

默认的资源结构：

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## 显示图片

使用 `LocalAsset()` 组件从资源中显示图片：

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

两种方法都会返回包含已配置资源目录的完整资源路径。

<div id="custom-asset-paths"></div>

## 自定义资源路径

要支持不同的资源子目录，您可以为 `LocalAsset` 组件添加自定义构造函数。

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- new constructor for icons folder
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// Usage examples
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## 返回资源路径

使用 `getAsset()` 获取 `assets/` 目录中任何类型文件的路径：

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### 与各种组件配合使用

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## 添加新文件

1. 将文件放在 `assets/` 的相应子目录中：
   - 图片：`assets/images/`
   - 视频：`assets/videos/`
   - 字体：`assets/fonts/`
   - 其他：`assets/data/` 或自定义文件夹

2. 确保文件夹已在 `pubspec.yaml` 中列出：

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## 资源配置

{{ config('app.name') }} v7 通过 `.env` 文件中的 `ASSET_PATH` 环境变量配置资源路径：

``` bash
ASSET_PATH="assets"
```

辅助函数会自动添加此路径前缀，因此您无需在调用中包含 `assets/`：

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### 更改基础路径

如果您需要不同的资源结构，请更新 `.env` 中的 `ASSET_PATH`：

``` bash
# Use a different base folder
ASSET_PATH="res"
```

更改后，重新生成您的环境配置：

``` bash
metro make:env --force
```

