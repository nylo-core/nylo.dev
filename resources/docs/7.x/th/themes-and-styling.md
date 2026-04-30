# Themes & Styling

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับธีม")
- ธีม
  - [ธีม Light & Dark](#light-and-dark-themes "ธีม Light และ Dark")
  - [การสร้างธีม](#creating-a-theme "การสร้างธีม")
- การกำหนดค่า
  - [สีของธีม](#theme-colors "สีของธีม")
  - [การใช้สี](#using-colors "การใช้สี")
  - [สไตล์พื้นฐาน](#base-styles "สไตล์พื้นฐาน")
  - [การขยายสไตล์สี](#extending-color-styles "การขยายสไตล์สี")
  - [การเปลี่ยนธีม](#switching-theme "การเปลี่ยนธีม")
  - [ฟอนต์](#fonts "ฟอนต์")
  - [การออกแบบ](#design "การออกแบบ")
- [Text Extensions](#text-extensions "Text Extensions")


<div id="introduction"></div>

## บทนำ

คุณสามารถจัดการสไตล์ UI ของแอปพลิเคชันได้โดยใช้ธีม ธีมช่วยให้เราเปลี่ยนแปลงได้ เช่น ขนาดฟอนต์ของข้อความ, ลักษณะของปุ่ม และรูปลักษณ์โดยรวมของแอปพลิเคชัน

หากคุณเป็นมือใหม่กับธีม ตัวอย่างบนเว็บไซต์ Flutter จะช่วยให้คุณเริ่มต้นได้ <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">ที่นี่</a>

{{ config('app.name') }} มีธีมที่กำหนดค่าไว้ล่วงหน้าสำหรับ `Light mode` และ `Dark mode` พร้อมใช้งานทันที

ธีมจะอัปเดตอัตโนมัติเมื่ออุปกรณ์เข้าสู่โหมด <b>'light/dark'</b>

<div id="light-and-dark-themes"></div>

## ธีม Light & Dark

แต่ละธีมอยู่ในไดเรกทอรีย่อยของตัวเองภายใต้ `lib/resources/themes/`:

- Light theme – `lib/resources/themes/light/light_theme.dart`
- Light colors – `lib/resources/themes/light/light_theme_colors.dart`
- Dark theme – `lib/resources/themes/dark/dark_theme.dart`
- Dark colors – `lib/resources/themes/dark/dark_theme_colors.dart`

ทั้งสองธีมใช้ builder ร่วมกันที่ `lib/resources/themes/base_theme.dart` และ interface `ColorStyles` ที่ `lib/resources/themes/color_styles.dart`



<div id="creating-a-theme"></div>

## การสร้างธีม

หากคุณต้องการมีหลายธีมสำหรับแอป ให้สร้างไฟล์ธีมด้วยตนเองใต้ `lib/resources/themes/` ขั้นตอนด้านล่างใช้ `bright` เป็นตัวอย่าง — แทนที่ด้วยชื่อธีมของคุณ

**ขั้นตอนที่ 1:** สร้างไฟล์ธีมที่ `lib/resources/themes/bright/bright_theme.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**ขั้นตอนที่ 2:** สร้างไฟล์สีที่ `lib/resources/themes/bright/bright_theme_colors.dart`:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/color_styles.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BrightThemeColors extends ColorStyles {
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFDE7),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFFFBC02D),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  @override
  AppBarColors get appBar => const AppBarColors(
        background: Color(0xFFFBC02D),
        content: Colors.white,
      );

  @override
  BottomTabBarColors get bottomTabBar => const BottomTabBarColors(
        background: Colors.white,
        iconSelected: Color(0xFFFBC02D),
        iconUnselected: Colors.black54,
        labelSelected: Colors.black,
        labelUnselected: Colors.black45,
      );
}
```

**ขั้นตอนที่ 3:** ลงทะเบียนธีมใหม่ใน `lib/bootstrap/theme.dart`

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

  BaseThemeConfig<ColorStyles>(
    id: 'bright_theme',
    theme: brightTheme,
    colors: BrightThemeColors(),
    type: NyThemeType.light,
  ),
];
```

คุณสามารถปรับสีใน `bright_theme_colors.dart` ให้เข้ากับการออกแบบของคุณ

<div id="theme-colors"></div>

## สีของธีม

ในการจัดการสีของธีมในโปรเจกต์ ให้ดูที่ไดเรกทอรี `lib/resources/themes/light/` และ `lib/resources/themes/dark/` แต่ละไดเรกทอรีมีไฟล์สีสำหรับธีมของตัวเอง — `light_theme_colors.dart` และ `dark_theme_colors.dart`

ค่าสีถูกจัดระเบียบเป็นกลุ่ม (`general`, `appBar`, `bottomTabBar`) ที่กำหนดโดย framework คลาสสีของธีมของคุณขยาย `ColorStyles` และจัดหา instance ของแต่ละกลุ่ม:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// สีสำหรับการใช้งานทั่วไป
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// สีสำหรับ app bar
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// สีสำหรับ bottom tab bar
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

## การใช้สีใน widget

ใช้ helper `nyColorStyle<T>(context)` เพื่ออ่านสีของธีมที่ใช้งานอยู่ ส่งชนิด `ColorStyles` ของโปรเจกต์เพื่อให้การเรียกมี type อย่างสมบูรณ์:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// ภายใน widget build:
final colors = nyColorStyle<ColorStyles>(context);

// สีพื้นหลังของธีมที่ใช้งานอยู่
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// อ่านสีจากธีมเฉพาะ (โดยไม่คำนึงถึงธีมที่ใช้งานอยู่):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## สไตล์พื้นฐาน

สไตล์พื้นฐานช่วยให้คุณอธิบายทุกธีมผ่าน interface เดียว {{ config('app.name') }} มาพร้อม `lib/resources/themes/color_styles.dart` ซึ่งเป็นสัญญาที่ทั้ง `light_theme_colors.dart` และ `dark_theme_colors.dart` ต้องปฏิบัติตาม

`ColorStyles` ขยาย `ThemeColor` จาก framework ซึ่งเปิดเผยสามกลุ่มสีที่กำหนดไว้ล่วงหน้า: `GeneralColors`, `AppBarColors` และ `BottomTabBarColors` builder ธีมพื้นฐาน (`lib/resources/themes/base_theme.dart`) อ่านกลุ่มเหล่านี้เมื่อสร้าง `ThemeData` ดังนั้นสิ่งที่คุณใส่ลงไปจะถูกเชื่อมต่อเข้ากับ widget ที่ตรงกันโดยอัตโนมัติ

<br>

ไฟล์ `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// สีสำหรับการใช้งานทั่วไป
  @override
  GeneralColors get general;

  /// สีสำหรับ app bar
  @override
  AppBarColors get appBar;

  /// สีสำหรับ bottom tab bar
  @override
  BottomTabBarColors get bottomTabBar;
}
```

สามกลุ่มเปิดเผยฟิลด์ต่อไปนี้:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

หากต้องการเพิ่มฟิลด์นอกเหนือจากค่าเริ่มต้นเหล่านี้ — ปุ่ม, ไอคอน, ป้าย ฯลฯ ของคุณ — ดู [การขยายสไตล์สี](#extending-color-styles)

<div id="extending-color-styles"></div>

## การขยายสไตล์สี

<!-- uncertain: new section "Extending color styles" — not present in existing th locale file -->
สามกลุ่มเริ่มต้น (`general`, `appBar`, `bottomTabBar`) เป็นจุดเริ่มต้น ไม่ใช่ขีดจำกัดแบบตายตัว `lib/resources/themes/color_styles.dart` เป็นของคุณที่จะแก้ไข — เพิ่มกลุ่มสีใหม่ (หรือฟิลด์เดี่ยว) บนค่าเริ่มต้น จากนั้น implement ในคลาสสีของแต่ละธีม

**1. กำหนดกลุ่มสีที่กำหนดเอง**

จัดกลุ่มสีที่เกี่ยวข้องเป็นคลาสที่ไม่เปลี่ยนแปลงขนาดเล็ก:

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

**2. เพิ่มใน `ColorStyles`**

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

  // กลุ่มที่กำหนดเอง
  IconColors get icons;
}
```

**3. Implement ในคลาสสีของแต่ละธีม**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...override ที่มีอยู่...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

ทำซ้ำ override `icons` เดียวกันใน `dark_theme_colors.dart` ด้วยค่า dark-mode

**4. ใช้งานใน widget ของคุณ**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## การเปลี่ยนธีม

{{ config('app.name') }} รองรับความสามารถในการเปลี่ยนธีมแบบทันที

ตัวอย่างเช่น หากคุณต้องการเปลี่ยนธีมเมื่อผู้ใช้แตะปุ่มเพื่อเปิดใช้ "dark theme"

คุณสามารถรองรับสิ่งนี้ได้โดยทำตามด้านล่าง:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // ตั้งธีมให้ใช้ "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// หรือ

TextButton(onPressed: () {

    // ตั้งธีมให้ใช้ "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## ฟอนต์

การอัปเดตฟอนต์หลักทั่วทั้งแอปทำได้ง่ายใน {{ config('app.name') }} เปิดไฟล์ `lib/config/design.dart` แล้วอัปเดต `DesignConfig.appFont`

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

เรารวมไลบรารี <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> ไว้ใน repository ดังนั้นคุณจึงสามารถเริ่มใช้ฟอนต์ทั้งหมดได้อย่างง่ายดาย หากต้องการเปลี่ยนเป็น Google Font อื่น เพียงเปลี่ยน call:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

ดูฟอนต์ได้ที่ไลบรารี <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> อย่างเป็นทางการเพื่อเรียนรู้เพิ่มเติม

ต้องการใช้ฟอนต์กำหนดเอง? ดูคู่มือนี้ - https://flutter.dev/docs/cookbook/design/fonts

เมื่อคุณเพิ่มฟอนต์แล้ว เปลี่ยนตัวแปรตามตัวอย่างด้านล่าง

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo ใช้เป็นตัวอย่างสำหรับฟอนต์กำหนดเอง
```

<div id="design"></div>

## การออกแบบ

ไฟล์ **lib/config/design.dart** ใช้สำหรับจัดการองค์ประกอบการออกแบบสำหรับแอปของคุณ ทุกอย่างถูกเปิดเผยผ่านคลาส `DesignConfig`:

`DesignConfig.appFont` มีฟอนต์สำหรับแอปของคุณ

`DesignConfig.logo` ใช้สำหรับแสดงโลโก้ของแอป

คุณสามารถแก้ไข **lib/resources/widgets/logo_widget.dart** เพื่อปรับแต่งวิธีแสดงโลโก้ของคุณ

`DesignConfig.loader` ใช้สำหรับแสดงตัวโหลด {{ config('app.name') }} จะใช้ตัวแปรนี้ในเมธอดช่วยเหลือบางตัวเป็น loader widget เริ่มต้น

คุณสามารถแก้ไข **lib/resources/widgets/loader_widget.dart** เพื่อปรับแต่งวิธีแสดงตัวโหลดของคุณ

<div id="text-extensions"></div>

## Text Extensions

นี่คือ text extension ที่ใช้ได้ใน {{ config('app.name') }}

| ชื่อกฎ   | การใช้งาน | ข้อมูล |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | ใช้ textTheme **displayLarge** |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | ใช้ textTheme **displayMedium** |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | ใช้ textTheme **displaySmall** |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | ใช้ textTheme **headingLarge** |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | ใช้ textTheme **headingMedium** |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | ใช้ textTheme **headingSmall** |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | ใช้ textTheme **titleLarge** |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | ใช้ textTheme **titleMedium** |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | ใช้ textTheme **titleSmall** |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | ใช้ textTheme **bodyLarge** |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | ใช้ textTheme **bodyMedium** |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | ใช้ textTheme **bodySmall** |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | ใช้ textTheme **labelLarge** |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | ใช้ textTheme **labelMedium** |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | ใช้ textTheme **labelSmall** |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | ใช้น้ำหนักฟอนต์ bold กับ Text widget |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | ใช้น้ำหนักฟอนต์ light กับ Text widget |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | ตั้งค่าสีข้อความที่แตกต่างบน Text widget |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | จัดตำแหน่งฟอนต์ไปทางซ้าย |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | จัดตำแหน่งฟอนต์ไปทางขวา |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | จัดตำแหน่งฟอนต์ไปตรงกลาง |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | ตั้งค่าจำนวนบรรทัดสูงสุดสำหรับ text widget |

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

#### ตั้งค่าสี

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// สีจาก colorStyles ของคุณ
```

<div id="text-extension-align-left"></div>

#### จัดซ้าย

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### จัดขวา

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### จัดกลาง

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### ตั้งค่าจำนวนบรรทัดสูงสุด

``` dart
Text("Hello World").setMaxLines(5)
```
