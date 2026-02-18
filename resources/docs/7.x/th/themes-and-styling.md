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

- Light theme - `lib/resources/themes/light_theme.dart`
- Dark theme - `lib/resources/themes/dark_theme.dart`

ภายในไฟล์เหล่านี้ คุณจะพบ ThemeData และ ThemeStyle ที่กำหนดค่าไว้ล่วงหน้า



<div id="creating-a-theme"></div>

## การสร้างธีม

หากคุณต้องการมีหลายธีมสำหรับแอป เรามีวิธีง่ายๆ ให้คุณทำ หากคุณเป็นมือใหม่กับธีม ทำตามขั้นตอนนี้

ก่อนอื่น รันคำสั่งด้านล่างจาก terminal

``` bash
metro make:theme bright_theme
```

<b>หมายเหตุ:</b> แทนที่ **bright_theme** ด้วยชื่อธีมใหม่ของคุณ

สิ่งนี้จะสร้างธีมใหม่ในไดเรกทอรี `/resources/themes/` และสร้างไฟล์สีของธีมใน `/resources/themes/styles/` ด้วย

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

  BaseThemeConfig<ColorStyles>( // new theme automatically added
    id: 'Bright Theme',
    description: "Bright Theme",
    theme: brightTheme,
    colors: BrightThemeColors(),
  ),
];
```

คุณสามารถแก้ไขสีสำหรับธีมใหม่ของคุณในไฟล์ **/resources/themes/styles/bright_theme_colors.dart**

<div id="theme-colors"></div>

## สีของธีม

ในการจัดการสีของธีมในโปรเจกต์ ให้ดูที่ไดเรกทอรี `lib/resources/themes/styles`
ไดเรกทอรีนี้ประกอบด้วยสีของสไตล์สำหรับ light_theme_colors.dart และ dark_theme_colors.dart

ในไฟล์นี้ คุณควรมีสิ่งที่คล้ายกับด้านล่าง

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

## การใช้สีใน widget

``` dart
import 'package:flutter_app/config/theme.dart';
...

// ดึงสีพื้นหลัง light/dark ขึ้นอยู่กับธีม
ThemeColor.get(context).background

// ตัวอย่างการใช้คลาส "ThemeColor"
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// หรือ

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## สไตล์พื้นฐาน

สไตล์พื้นฐานช่วยให้คุณปรับแต่งสีของ widget ต่างๆ ได้จากจุดเดียวในโค้ดของคุณ

{{ config('app.name') }} มาพร้อมสไตล์พื้นฐานที่กำหนดค่าไว้ล่วงหน้าสำหรับโปรเจกต์ของคุณที่ `lib/resources/themes/styles/color_styles.dart`

สไตล์เหล่านี้เป็น interface สำหรับสีของธีมใน `light_theme_colors.dart` และ `dart_theme_colors.dart`

<br>

ไฟล์ `lib/resources/themes/styles/color_styles.dart`

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

คุณสามารถเพิ่มสไตล์เพิ่มเติมที่นี่ แล้ว implement สีในธีมของคุณ

<div id="switching-theme"></div>

## การเปลี่ยนธีม

{{ config('app.name') }} รองรับความสามารถในการเปลี่ยนธีมแบบทันที

ตัวอย่างเช่น หากคุณต้องการเปลี่ยนธีมเมื่อผู้ใช้แตะปุ่มเพื่อเปิดใช้ "dark theme"

คุณสามารถรองรับสิ่งนี้ได้โดยทำตามด้านล่าง:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
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

การอัปเดตฟอนต์หลักทั่วทั้งแอปทำได้ง่ายใน {{ config('app.name') }} เปิดไฟล์ `lib/config/design.dart` แล้วอัปเดตตามด้านล่าง

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

เรารวมไลบรารี <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> ไว้ใน repository ดังนั้นคุณจึงสามารถเริ่มใช้ฟอนต์ทั้งหมดได้อย่างง่ายดาย
ในการอัปเดตฟอนต์เป็นแบบอื่น คุณสามารถทำดังนี้:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

ดูฟอนต์ได้ที่ไลบรารี <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> อย่างเป็นทางการเพื่อเรียนรู้เพิ่มเติม

ต้องการใช้ฟอนต์กำหนดเอง? ดูคู่มือนี้ - https://flutter.dev/docs/cookbook/design/fonts

เมื่อคุณเพิ่มฟอนต์แล้ว เปลี่ยนตัวแปรตามตัวอย่างด้านล่าง

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo ใช้เป็นตัวอย่างสำหรับฟอนต์กำหนดเอง
```

<div id="design"></div>

## การออกแบบ

ไฟล์ **config/design.dart** ใช้สำหรับจัดการองค์ประกอบการออกแบบสำหรับแอปของคุณ

ตัวแปร `appFont` มีฟอนต์สำหรับแอปของคุณ

ตัวแปร `logo` ใช้สำหรับแสดงโลโก้ของแอป

คุณสามารถแก้ไข **resources/widgets/logo_widget.dart** เพื่อปรับแต่งวิธีแสดงโลโก้ของคุณ

ตัวแปร `loader` ใช้สำหรับแสดงตัวโหลด {{ config('app.name') }} จะใช้ตัวแปรนี้ในเมธอดช่วยเหลือบางตัวเป็น loader widget เริ่มต้น

คุณสามารถแก้ไข **resources/widgets/loader_widget.dart** เพื่อปรับแต่งวิธีแสดงตัวโหลดของคุณ

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
