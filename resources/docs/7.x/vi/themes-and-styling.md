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
  - [Mở rộng kiểu màu](#extending-color-styles "Mở rộng kiểu màu")
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

Mỗi theme tồn tại trong thư mục con riêng của nó dưới `lib/resources/themes/`:

- Theme sáng – `lib/resources/themes/light/light_theme.dart`
- Màu sáng – `lib/resources/themes/light/light_theme_colors.dart`
- Theme tối – `lib/resources/themes/dark/dark_theme.dart`
- Màu tối – `lib/resources/themes/dark/dark_theme_colors.dart`

Cả hai theme dùng chung builder tại `lib/resources/themes/base_theme.dart` và interface `ColorStyles` tại `lib/resources/themes/color_styles.dart`.



<div id="creating-a-theme"></div>

## Tạo theme

Nếu bạn muốn có nhiều theme cho ứng dụng, chúng tôi có cách dễ dàng để bạn thực hiện. Nếu bạn mới làm quen với theme, hãy theo dõi.

Đầu tiên, chạy lệnh dưới đây từ terminal

``` bash
dart run nylo_framework:main make:theme bright_theme
```

<b>Lưu ý:</b> thay thế **bright_theme** bằng tên theme mới của bạn.

Lệnh này tạo thư mục theme mới tại `lib/resources/themes/bright/` chứa cả `bright_theme.dart` và `bright_theme_colors.dart`, sau đó đăng ký nó trong `lib/bootstrap/theme.dart`.

``` dart
// lib/bootstrap/theme.dart
final List<BaseThemeConfig<ColorStyles>> appThemes = [
  BaseThemeConfig<ColorStyles>(
    id: 'light_theme',
    theme: lightTheme,
    colors: LightThemeColors(),
    type: NyThemeType.light,
  ),
  BaseThemeConfig<ColorStyles>(
    id: 'dark_theme',
    theme: darkTheme,
    colors: DarkThemeColors(),
    type: NyThemeType.dark,
  ),

  BaseThemeConfig<ColorStyles>( // theme mới được thêm tự động
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

Bạn có thể sửa đổi màu cho theme mới trong tệp **lib/resources/themes/bright/bright_theme_colors.dart**.

<div id="theme-colors"></div>

## Màu theme

Để quản lý màu theme trong dự án, hãy xem thư mục `lib/resources/themes/light/` và `lib/resources/themes/dark/`. Mỗi thư mục chứa tệp màu cho theme của nó — `light_theme_colors.dart` và `dark_theme_colors.dart`.

Các giá trị màu được tổ chức thành các nhóm (`general`, `appBar`, `bottomTabBar`) được định nghĩa bởi framework. Lớp màu của theme kế thừa `ColorStyles` và cung cấp một instance của mỗi nhóm:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Màu cho mục đích chung.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Màu cho app bar.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Màu cho bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Colors.blue,
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

<div id="using-colors"></div>

## Sử dụng màu trong widget

Sử dụng helper `nyColorStyle<T>(context)` để đọc màu của theme đang hoạt động. Truyền kiểu `ColorStyles` của dự án để cuộc gọi được định kiểu đầy đủ:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// bên trong một widget build:
final colors = nyColorStyle<ColorStyles>(context);

// màu nền của theme đang hoạt động
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Đọc màu từ theme cụ thể (bất kể theme nào đang hoạt động):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Kiểu cơ sở

Kiểu cơ sở cho phép bạn mô tả mọi theme thông qua một interface duy nhất. {{ config('app.name') }} đi kèm với `lib/resources/themes/color_styles.dart`, đây là hợp đồng mà cả `light_theme_colors.dart` và `dark_theme_colors.dart` đều thực hiện.

`ColorStyles` kế thừa `ThemeColor` từ framework, cung cấp ba nhóm màu được định nghĩa sẵn: `GeneralColors`, `AppBarColors` và `BottomTabBarColors`. Builder theme cơ sở (`lib/resources/themes/base_theme.dart`) đọc các nhóm này khi xây dựng `ThemeData`, vì vậy bất cứ thứ gì bạn đặt vào chúng sẽ được tự động kết nối vào các widget phù hợp.

<br>

Tệp `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Màu cho mục đích chung.
  @override
  GeneralColors get general;

  /// Màu cho app bar.
  @override
  AppBarColors get appBar;

  /// Màu cho bottom tab bar.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Ba nhóm cung cấp các trường sau:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Để thêm các trường ngoài các mặc định này — nút, biểu tượng, huy hiệu, v.v. của bạn — xem [Mở rộng kiểu màu](#extending-color-styles).

<div id="extending-color-styles"></div>

## Mở rộng kiểu màu

<!-- uncertain: new section "Extending color styles" — not present in existing vi locale file -->
Ba nhóm mặc định (`general`, `appBar`, `bottomTabBar`) là điểm khởi đầu, không phải giới hạn cứng. `lib/resources/themes/color_styles.dart` là của bạn để sửa đổi — thêm các nhóm màu mới (hoặc các trường đơn lẻ) trên các mặc định, sau đó thực hiện chúng trong lớp màu của mỗi theme.

**1. Định nghĩa một nhóm màu tùy chỉnh**

Nhóm các màu liên quan vào một lớp bất biến nhỏ:

``` dart
import 'package:flutter/material.dart';

class IconColors {
  final Color iconBackground;
  final Color iconBackground1;

  const IconColors({
    required this.iconBackground,
    required this.iconBackground1,
  });
}
```

**2. Thêm vào `ColorStyles`**

``` dart
// lib/resources/themes/color_styles.dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  @override
  GeneralColors get general;
  @override
  AppBarColors get appBar;
  @override
  BottomTabBarColors get bottomTabBar;

  // Nhóm tùy chỉnh
  IconColors get icons;
}
```

**3. Thực hiện trong lớp màu của mỗi theme**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...các override hiện có...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Lặp lại cùng override `icons` trong `dark_theme_colors.dart` với các giá trị dark-mode.

**4. Sử dụng trong widget của bạn**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Chuyển đổi theme

{{ config('app.name') }} hỗ trợ khả năng chuyển đổi theme ngay lập tức.

Ví dụ: Nếu bạn cần chuyển theme khi người dùng nhấn nút để kích hoạt "theme tối".

Bạn có thể hỗ trợ điều đó bằng cách làm như sau:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
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

Cập nhật font chính trong toàn bộ ứng dụng rất dễ trong {{ config('app.name') }}. Mở tệp `lib/config/design.dart` và cập nhật `DesignConfig.appFont`.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Chúng tôi bao gồm thư viện <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> trong repository, vì vậy bạn có thể bắt đầu sử dụng tất cả font với ít nỗ lực. Để chuyển sang Google Font khác, chỉ cần thay đổi lệnh gọi:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Xem các font trên thư viện <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> chính thức để hiểu thêm.

Cần sử dụng font tùy chỉnh? Xem hướng dẫn này - https://flutter.dev/docs/cookbook/design/fonts

Sau khi thêm font, hãy thay đổi biến như ví dụ dưới đây.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo được dùng làm ví dụ cho font tùy chỉnh
```

<div id="design"></div>

## Thiết kế

Tệp **lib/config/design.dart** được sử dụng để quản lý các phần tử thiết kế cho ứng dụng. Mọi thứ được hiển thị thông qua lớp `DesignConfig`:

`DesignConfig.appFont` chứa font cho ứng dụng.

`DesignConfig.logo` được sử dụng để hiển thị Logo ứng dụng.

Bạn có thể sửa đổi **lib/resources/widgets/logo_widget.dart** để tùy chỉnh cách hiển thị Logo.

`DesignConfig.loader` được sử dụng để hiển thị trình tải. {{ config('app.name') }} sẽ sử dụng biến này trong một số phương thức trợ giúp làm widget tải mặc định.

Bạn có thể sửa đổi **lib/resources/widgets/loader_widget.dart** để tùy chỉnh cách hiển thị Trình tải.

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
