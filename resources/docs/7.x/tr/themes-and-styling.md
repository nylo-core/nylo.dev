# Temalar ve Stil

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- Temalar
  - [Açık ve Koyu temalar](#light-and-dark-themes "Açık ve Koyu temalar")
  - [Tema oluşturma](#creating-a-theme "Tema oluşturma")
- Yapılandırma
  - [Tema renkleri](#theme-colors "Tema renkleri")
  - [Renkleri kullanma](#using-colors "Renkleri kullanma")
  - [Temel stiller](#base-styles "Temel stiller")
  - [Tema değiştirme](#switching-theme "Tema değiştirme")
  - [Yazı tipleri](#fonts "Yazı tipleri")
  - [Tasarım](#design "Tasarım")
- [Metin Uzantıları](#text-extensions "Metin Uzantıları")


<div id="introduction"></div>

## Giriş

Temalar kullanarak uygulamanızın UI stillerini yönetebilirsiniz. Temalar, örneğin metnin yazı tipi boyutunu, düğmelerin nasıl görüneceğini ve uygulamanızın genel görünümünü değiştirmemize olanak tanır.

Temalara yeniyseniz, Flutter web sitesindeki örnekler başlamanıza yardımcı olacaktır <a href="https://docs.flutter.dev/cookbook/design/themes#creating-an-app-theme" target="_BLANK">buradan</a>.

{{ config('app.name') }}, `Açık mod` ve `Koyu mod` için önceden yapılandırılmış temalarla birlikte gelir.

Cihaz <b>'açık/koyu'</b> moda girerse tema da güncellenecektir.

<div id="light-and-dark-themes"></div>

## Açık ve Koyu temalar

- Açık tema - `lib/resources/themes/light_theme.dart`
- Koyu tema - `lib/resources/themes/dark_theme.dart`

Bu dosyaların içinde, önceden tanımlanmış ThemeData ve ThemeStyle bulacaksınız.



<div id="creating-a-theme"></div>

## Tema oluşturma

Uygulamanız için birden fazla tema istiyorsanız, bunu yapmanın kolay bir yolu vardır. Temalara yeniyseniz, takip edin.

İlk olarak, terminalden aşağıdaki komutu çalıştırın

``` bash
metro make:theme bright_theme
```

<b>Not:</b> **bright_theme** yerine yeni temanızın adını yazın.

Bu, `/resources/themes/` dizininizde yeni bir tema ve ayrıca `/resources/themes/styles/` dizininde bir tema renkleri dosyası oluşturur.

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

Yeni temanızın renklerini **/resources/themes/styles/bright_theme_colors.dart** dosyasında değiştirebilirsiniz.

<div id="theme-colors"></div>

## Tema Renkleri

Projenizdeki tema renklerini yönetmek için `lib/resources/themes/styles` dizinine bakın.
Bu dizin, light_theme_colors.dart ve dark_theme_colors.dart için stil renklerini içerir.

Bu dosyada, aşağıdakine benzer bir şey bulmalısınız.

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

## Widget'larda renkleri kullanma

``` dart
import 'package:flutter_app/config/theme.dart';
...

// gets the light/dark background colour depending on the theme
ThemeColor.get(context).background

// e.g. of using the "ThemeColor" class
Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeColor.get(context).content // Color - content
  ),
),

// or

Text(
  "Hello World",
  style: TextStyle(
      color:  ThemeConfig.light().colors.content // Light theme colors - primary content
  ),
),
```

<div id="base-styles"></div>

## Temel stiller

Temel stiller, kodunuzun tek bir alanından çeşitli widget renklerini özelleştirmenize olanak tanır.

{{ config('app.name') }}, projeniz için `lib/resources/themes/styles/color_styles.dart` konumunda önceden yapılandırılmış temel stillerle birlikte gelir.

Bu stiller, `light_theme_colors.dart` ve `dart_theme_colors.dart` dosyalarındaki tema renkleriniz için bir arayüz sağlar.

<br>

Dosya `lib/resources/themes/styles/color_styles.dart`

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

Buraya ek stiller ekleyebilir ve ardından renkleri temanızda uygulayabilirsiniz.

<div id="switching-theme"></div>

## Tema değiştirme

{{ config('app.name') }}, anında tema değiştirme özelliğini destekler.

Örn. Kullanıcı "koyu temayı" etkinleştirmek için bir düğmeye dokunursa temayı değiştirmeniz gerekiyorsa.

Aşağıdakileri yaparak bunu destekleyebilirsiniz:

``` dart
import 'package:nylo_framework/theme/helper/ny_theme.dart';
...

TextButton(onPressed: () {

    // set theme to use the "dark theme"
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// or

TextButton(onPressed: () {

    // set theme to use the "light theme"
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Yazı tipleri

{{ config('app.name') }}'da uygulama genelinde birincil yazı tipinizi güncellemek kolaydır. `lib/config/design.dart` dosyasını açın ve aşağıdakini güncelleyin.

``` dart
final TextStyle appThemeFont = GoogleFonts.lato();
```

Depoda <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> kütüphanesini dahil ediyoruz, böylece tüm yazı tiplerini az çabayla kullanmaya başlayabilirsiniz.
Yazı tipini başka bir şeyle güncellemek için aşağıdakileri yapabilirsiniz:
``` dart
// OLD
// final TextStyle appThemeFont = GoogleFonts.lato();

// NEW
final TextStyle appThemeFont = GoogleFonts.montserrat();
```

Daha fazlasını anlamak için resmi <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> kütüphanesindeki yazı tiplerine göz atın

Özel bir yazı tipi mi kullanmanız gerekiyor? Bu kılavuza bakın - https://flutter.dev/docs/cookbook/design/fonts

Yazı tipinizi ekledikten sonra, değişkeni aşağıdaki örnekteki gibi değiştirin.

``` dart
final TextStyle appThemeFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo used as an example for the custom font
```

<div id="design"></div>

## Tasarım

**config/design.dart** dosyası, uygulamanız için tasarım öğelerini yönetmek için kullanılır.

`appFont` değişkeni uygulamanızın yazı tipini içerir.

`logo` değişkeni uygulamanızın Logosunu görüntülemek için kullanılır.

Logonuzu nasıl görüntülemek istediğinizi özelleştirmek için **resources/widgets/logo_widget.dart** dosyasını değiştirebilirsiniz.

`loader` değişkeni bir yükleyici görüntülemek için kullanılır. {{ config('app.name') }}, bazı yardımcı metotlarda bu değişkeni varsayılan yükleyici widget'ı olarak kullanacaktır.

Yükleyicinizi nasıl görüntülemek istediğinizi özelleştirmek için **resources/widgets/loader_widget.dart** dosyasını değiştirebilirsiniz.

<div id="text-extensions"></div>

## Metin Uzantıları

{{ config('app.name') }}'da kullanabileceğiniz mevcut metin uzantıları şunlardır.

| Kural Adı | Kullanım | Bilgi |
|---|---|---|
| <a href="#text-extension-display-large">Display Large</a> | displayLarge()  | **displayLarge** textTheme uygular |
| <a href="#text-extension-display-medium">Display Medium</a> | displayMedium()  | **displayMedium** textTheme uygular |
| <a href="#text-extension-display-small">Display Small</a> | displaySmall()  | **displaySmall** textTheme uygular |
| <a href="#text-extension-heading-large">Heading Large</a> | headingLarge()  | **headingLarge** textTheme uygular |
| <a href="#text-extension-heading-medium">Heading Medium</a> | headingMedium()  | **headingMedium** textTheme uygular |
| <a href="#text-extension-heading-small">Heading Small</a> | headingSmall()  | **headingSmall** textTheme uygular |
| <a href="#text-extension-title-large">Title Large</a> | titleLarge()  | **titleLarge** textTheme uygular |
| <a href="#text-extension-title-medium">Title Medium</a> | titleMedium()  | **titleMedium** textTheme uygular |
| <a href="#text-extension-title-small">Title Small</a> | titleSmall()  | **titleSmall** textTheme uygular |
| <a href="#text-extension-body-large">Body Large</a> | bodyLarge()  | **bodyLarge** textTheme uygular |
| <a href="#text-extension-body-medium">Body Medium</a> | bodyMedium()  | **bodyMedium** textTheme uygular |
| <a href="#text-extension-body-small">Body Small</a> | bodySmall()  | **bodySmall** textTheme uygular |
| <a href="#text-extension-label-large">Label Large</a> | labelLarge()  | **labelLarge** textTheme uygular |
| <a href="#text-extension-label-medium">Label Medium</a> | labelMedium()  | **labelMedium** textTheme uygular |
| <a href="#text-extension-label-small">Label Small</a> | labelSmall()  | **labelSmall** textTheme uygular |
| <a href="#text-extension-font-weight-bold">Font Weight Bold</a> | fontWeightBold  | Text widget'a kalın yazı tipi ağırlığı uygular |
| <a href="#text-extension-font-weight-bold">Font Weight Light</a> | fontWeightLight  | Text widget'a hafif yazı tipi ağırlığı uygular |
| <a href="#text-extension-set-color">Set Color</a> | setColor(context, (color) => colors.primaryAccent)  | Text widget'ta farklı bir metin rengi ayarla |
| <a href="#text-extension-align-left">Align Left</a> | alignLeft  | Yazı tipini sola hizala |
| <a href="#text-extension-align-right">Align Right</a> | alignRight  | Yazı tipini sağa hizala |
| <a href="#text-extension-align-center">Align Center</a> | alignCenter  | Yazı tipini ortaya hizala |
| <a href="#text-extension-set-max-lines">Set Max Lines</a> | setMaxLines(int maxLines)  | Text widget'ı için maksimum satır sayısını ayarla |

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

#### Set color

``` dart
Text("Hello World").setColor(context, (color) => colors.content)
// Color from your colorStyles
```

<div id="text-extension-align-left"></div>

#### Align left

``` dart
Text("Hello World").alignLeft()
```

<div id="text-extension-align-right"></div>

#### Align right

``` dart
Text("Hello World").alignRight()
```

<div id="text-extension-align-center"></div>

#### Align center

``` dart
Text("Hello World").alignCenter()
```

<div id="text-extension-set-max-lines"></div>

#### Set max lines

``` dart
Text("Hello World").setMaxLines(5)
```
