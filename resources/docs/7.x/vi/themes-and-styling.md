# Themes & Styling

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về theme")
- Theme
  - [Theme sáng & tối](#light-and-dark-themes "Theme sáng và tối")
  - [Tạo theme](#creating-a-theme "Tạo theme")
- Cấu hình
  - [Màu theme](#theme-colors "Màu theme")
  - [Sử dụng màu](#using-colors "Sử dụng màu")
  - [Kiểu cơ sở](#base-styles "Kiểu cơ sở")
  - [Chuyển đổi theme](#switching-theme "Chuyển đổi theme")
  - [Font](#fonts "Font")
  - [Thiết kế](#design "Thiết kế")
- [Text Extensions](#text-extensions "Text extensions")


<div id="introduction"></div>

## Giới thiệu

Bạn có thể quản lý kiểu UI của ứng dụng bằng theme. Theme cho phép chúng ta thay đổi ví dụ cỡ chữ văn bản, cách nút hiển thị và giao diện tổng thể của ứng dụng.

Nếu bạn mới làm quen với theme, các ví dụ trên trang web Flutter sẽ giúp bạn bắt đầu <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">tại đây</a>.

Ngay từ đầu, {{ config('app.name') }} bao gồm theme được cấu hình sẵn cho `Chế độ sáng` và `Chế độ tối`.

Theme cũng sẽ cập nhật nếu thiết bị vào chế độ <b>'sáng/tối'</b>.

<div id="light-and-dark-themes"></div>

## Theme sáng & tối

- Theme sáng - `lib/resources/themes/light_theme.dart`
- Theme tối - `lib/resources/themes/dark_theme.dart`

Bên trong các tệp này, bạn sẽ tìm thấy ThemeData và ThemeStyle được định nghĩa sẵn.



<div id="creating-a-theme"></div>

## Tạo theme

Nếu bạn muốn có nhiều theme cho ứng dụng, chúng tôi có cách dễ dàng để bạn thực hiện. Nếu bạn mới làm quen với theme, hãy theo dõi.

Đầu tiên, chạy lệnh dưới đây từ terminal

``` bash
metro make:theme bright_theme
```

<b>Lưu ý:</b> thay thế **bright_theme** bằng tên theme mới của bạn.

Lệnh này tạo theme mới trong thư mục `/resources/themes/` và tệp màu theme trong `/resources/themes/styles/`.

``` dart
// App Themes
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: getEnv('LIGHT_THEME_ID'),
    description: "Light theme",
    theme: lightTheme,
    colors: LightThemeColors(),
  ),
  BaseThemeConfig<ColorStyles>(
    id: getEnv('DARK_THEME_ID'),
    description: "Dark theme",
    theme: darkTheme,
    colors: DarkThemeColors(),
  ),

  BaseThemeConfig<ColorStyles>( // theme mới được thêm tự động
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

Bạn có thể sửa đổi màu cho theme mới trong tệp **/resources/themes/styles/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Màu theme

Để quản lý màu theme trong dự án, hãy xem thư mục `lib/resources/themes/styles`.
Thư mục này chứa các màu kiểu cho light_theme_colors.dart và dark_theme_colors.dart.

Trong tệp này, bạn sẽ thấy nội dung tương tự như dưới đây.

``` dart
// e.g Light Theme colors
class LightThemeColors implements ColorStyles {
  // general
  @override
  Color get background => const Color(0xFFFFFFFF);

  @override
  Color get content => const Color(0xFF000000);
  @override
  Color get primaryAccent => const Color(0xFF0045a0);

  @override
  Color get surfaceBackground => Colors.white;
  @override
  Color get surfaceContent => Colors.black;

  // app bar
  @override
  Color get appBarBackground => Colors.blue;
  @override
  Color get appBarPrimaryContent => Colors.white;

  // buttons
  @override
  Color get buttonBackground => Colors.blue;
  @override
  Color get buttonContent => Colors.white;

  @override
  Color get buttonSecondaryBackground => const Color(0xff151925);
  @override
  Color get buttonSecondaryContent => Colors.white.withAlpha((255.0 * 0.9).round());

  // bottom tab bar
  @override
  Color get bottomTabBarBackground => Colors.white;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected => Colors.blue;
  @override
  Color get bottomTabBarIconUnselected => Colors.black54;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected => Colors.black45;
  @override
  Color get bottomTabBarLabelSelected => Colors.black;

  // toast notification
  @override
  Color get toastNotificationBackground => Colors.white;
}
```

<div id="using-colors"></div>

## Sử dụng màu trong widget

``` dart
import 'package:flutter_app/config/theme.dart';
...

// lấy màu nền sáng/tối tùy thuộc vào theme
ThemeColor.get(context).background

// ví dụ sử dụng class "ThemeColor"
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// hoặc

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Màu theme sáng - primary content
  ),
),
```

<div id="base-styles"></div>

## Kiểu cơ sở

Kiểu cơ sở cho phép bạn tùy chỉnh màu của nhiều widget từ một nơi trong mã.

{{ config('app.name') }} đi kèm với kiểu cơ sở được cấu hình sẵn cho dự án tại `lib/resources/themes/styles/color_styles.dart`.

Các kiểu này cung cấp giao diện cho màu theme trong `light_theme_colors.dart` và `dart_theme_colors.dart`.

<br>

Tệp `lib/resources/themes/styles/color_styles.dart`

``` dart
abstract class ColorStyles {

  // general
  @override
  Color get background;
  @override
  Color get content;
  @override
  Color get primaryAccent;

  @override
  Color get surfaceBackground;
  @override
  Color get surfaceContent;

  // app bar
  @override
  Color get appBarBackground;
  @override
  Color get appBarPrimaryContent;

  @override
  Color get buttonBackground;
  @override
  Color get buttonContent;

  @override
  Color get buttonSecondaryBackground;
  @override
  Color get buttonSecondaryContent;

  // bottom tab bar
  @override
  Color get bottomTabBarBackground;

  // bottom tab bar - icons
  @override
  Color get bottomTabBarIconSelected;
  @override
  Color get bottomTabBarIconUnselected;

  // bottom tab bar - label
  @override
  Color get bottomTabBarLabelUnselected;
  @override
  Color get bottomTabBarLabelSelected;

  // toast notification
  Color get toastNotificationBackground;
}
```

Bạn có thể thêm kiểu bổ sung ở đây và sau đó triển khai màu trong theme.

<div id="switching-theme"></div>

## Chuyển đổi theme

{{ config('app.name') }} hỗ trợ khả năng chuyển đổi theme ngay lập tức.

Ví dụ: Nếu bạn cần chuyển theme khi người dùng nhấn nút để kích hoạt "theme tối".

Bạn có thể hỗ trợ điều đó bằng cách làm như sau:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // đặt theme sử dụng "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// hoặc

TextButton(onPressed: () {

    // đặt theme sử dụng "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Font

Cập nhật font chính trong toàn bộ ứng dụng rất dễ trong {{ config('app.name') }}. Mở tệp `lib/config/design.dart` và cập nhật phần dưới đây.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Chúng tôi bao gồm thư viện <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> trong repository, vì vậy bạn có thể bắt đầu sử dụng tất cả font với ít nỗ lực.
Để cập nhật font sang loại khác, bạn có thể làm như sau:
``` dart
// CŨ
// final TextStyle appThemeFont = GoogleFonts.lato();

// MỚI
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Xem các font trên thư viện <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> chính thức để hiểu thêm

Cần sử dụng font tùy chỉnh? Xem hướng dẫn này - https://flutter.dev/docs/cookbook/design/fonts

Sau khi thêm font, hãy thay đổi biến như ví dụ dưới đây.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo được dùng làm ví dụ cho font tùy chỉnh
```

<div id="design"></div>

## Thiết kế

Tệp **config/design.dart** được sử dụng để quản lý các phần tử thiết kế cho ứng dụng.

Biến `appFont` chứa font cho ứng dụng.

Biến `logo` được sử dụng để hiển thị Logo ứng dụng.

Bạn có thể sửa đổi **resources/widgets/logo_widget.dart** để tùy chỉnh cách hiển thị Logo.

Biến `loader` được sử dụng để hiển thị trình tải. {{ config('app.name') }} sẽ sử dụng biến này trong một số phương thức trợ giúp làm widget tải mặc định.

Bạn có thể sửa đổi **resources/widgets/loader_widget.dart** để tùy chỉnh cách hiển thị Trình tải.

<div id="text-extensions"></div>

## Text Extensions

Dưới đây là các text extension có sẵn mà bạn có thể sử dụng trong {{ config('app.name') }}.

| Tên quy tắc   | Cách dùng | Thông tin |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | Áp dụng textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | Áp dụng textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | Áp dụng textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | Áp dụng textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | Áp dụng textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | Áp dụng textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | Áp dụng textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | Áp dụng textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | Áp dụng textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | Áp dụng textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | Áp dụng textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | Áp dụng textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | Áp dụng textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | Áp dụng textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | Áp dụng textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Áp dụng font weight bold cho widget Text |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Áp dụng font weight light cho widget Text |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Đặt màu văn bản khác cho widget Text |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Căn chỉnh font sang trái |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Căn chỉnh font sang phải |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Căn chỉnh font ở giữa |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Đặt số dòng tối đa cho widget text |

<br>


<div id="text-extension-display-large"></div>

#### Display large

``` dart
Text("Hello World").displayLarge()
```

<div id="text-extension-display-medium"></div>

#### Display medium

``` dart
Text("Hello World").displayMedium()
```

<div id="text-extension-display-small"></div>

#### Display small

``` dart
Text("Hello World").displaySmall()
```

<div id="text-extension-heading-large"></div>

#### Heading large

``` dart
Text("Hello World").headingLarge()
```

<div id="text-extension-heading-medium"></div>

#### Heading medium

``` dart
Text("Hello World").headingMedium()
```

<div id="text-extension-heading-small"></div>

#### Heading small

``` dart
Text("Hello World").headingSmall()
```

<div id="text-extension-title-large"></div>

#### Title large

``` dart
Text("Hello World").titleLarge()
```

<div id="text-extension-title-medium"></div>

#### Title medium

``` dart
Text("Hello World").titleMedium()
```

<div id="text-extension-title-small"></div>

#### Title small

``` dart
Text("Hello World").titleSmall()
```

<div id="text-extension-body-large"></div>

#### Body large

``` dart
Text("Hello World").bodyLarge()
```

<div id="text-extension-body-medium"></div>

#### Body medium

``` dart
Text("Hello World").bodyMedium()
```

<div id="text-extension-body-small"></div>

#### Body small

``` dart
Text("Hello World").bodySmall()
```

<div id="text-extension-label-large"></div>

#### Label large

``` dart
Text("Hello World").labelLarge()
```

<div id="text-extension-label-medium"></div>

#### Label medium

``` dart
Text("Hello World").labelMedium()
```

<div id="text-extension-label-small"></div>

#### Label small

``` dart
Text("Hello World").labelSmall()
```

<div id="text-extension-font-weight-bold"></div>

#### Font weight bold

``` dart
Text("Hello World").fontWeightBold()
```

<div id="text-extension-font-weight-light"></div>

#### Font weight light

``` dart
Text("Hello World").fontWeightLight()
```

<div id="text-extension-set-color"></div>

#### Đặt màu

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Màu từ colorStyles của bạn
```

<div id="text-extension-align-left"></div>

#### Căn trái

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Căn phải

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Căn giữa

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Đặt số dòng tối đa

``` dart
Text("Hello World").setMaxLines(5)
```
