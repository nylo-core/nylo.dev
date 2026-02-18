# Assets

---

<a name="section-1"></a>
- [Введение](#introduction "Введение в ресурсы")
- Файлы
  - [Отображение изображений](#displaying-images "Отображение изображений")
  - [Пользовательские пути к ресурсам](#custom-asset-paths "Пользовательские пути к ресурсам")
  - [Получение путей к ресурсам](#returning-asset-paths "Получение путей к ресурсам")
- Управление ресурсами
  - [Добавление новых файлов](#adding-new-files "Добавление новых файлов")
  - [Конфигурация ресурсов](#asset-configuration "Конфигурация ресурсов")

<div id="introduction"></div>

## Введение

{{ config('app.name') }} v7 предоставляет вспомогательные методы для управления ресурсами в вашем Flutter-приложении. Ресурсы хранятся в директории `assets/` и включают изображения, видео, шрифты и другие файлы.

Структура ресурсов по умолчанию:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## Отображение изображений

Используйте виджет `LocalAsset()` для отображения изображений из ресурсов:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Basic usage
LocalAsset.image("nylo_logo.png")

// Using the `getImageAsset` helper
Image.asset(getImageAsset("nylo_logo.png"))
```

Оба метода возвращают полный путь к ресурсу, включая настроенную директорию ресурсов.

<div id="custom-asset-paths"></div>

## Пользовательские пути к ресурсам

Для поддержки различных поддиректорий ресурсов вы можете добавить пользовательские конструкторы в виджет `LocalAsset`.

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

## Получение путей к ресурсам

Используйте `getAsset()` для любого типа файлов в директории `assets/`:

``` dart
// Video file
String videoPath = getAsset("videos/welcome.mp4");

// JSON data file
String jsonPath = getAsset("data/config.json");

// Font file
String fontPath = getAsset("fonts/custom_font.ttf");
```

### Использование с различными виджетами

``` dart
// Video player
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// Loading JSON
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## Добавление новых файлов

1. Поместите файлы в соответствующую поддиректорию `assets/`:
   - Изображения: `assets/images/`
   - Видео: `assets/videos/`
   - Шрифты: `assets/fonts/`
   - Прочее: `assets/data/` или пользовательские папки

2. Убедитесь, что папка указана в `pubspec.yaml`:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## Конфигурация ресурсов

{{ config('app.name') }} v7 настраивает путь к ресурсам через переменную окружения `ASSET_PATH` в вашем файле `.env`:

``` bash
ASSET_PATH="assets"
```

Вспомогательные функции автоматически добавляют этот путь в начало, поэтому вам не нужно включать `assets/` в ваши вызовы:

``` dart
// These are equivalent:
getAsset("videos/intro.mp4")
// Returns: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// Returns: "assets/images/logo.png"
```

### Изменение базового пути

Если вам нужна другая структура ресурсов, обновите `ASSET_PATH` в вашем `.env`:

``` bash
# Use a different base folder
ASSET_PATH="res"
```

После изменения перегенерируйте конфигурацию окружения:

``` bash
metro make:env --force
```
