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
  - [Renk stillerini genişletme](#extending-color-styles "Renk stillerini genişletme")
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

Her tema, `lib/resources/themes/` altındaki kendi alt dizininde bulunur:

- Açık tema – `lib/resources/themes/light/light_theme.dart`
- Açık renkler – `lib/resources/themes/light/light_theme_colors.dart`
- Koyu tema – `lib/resources/themes/dark/dark_theme.dart`
- Koyu renkler – `lib/resources/themes/dark/dark_theme_colors.dart`

Her iki tema da `lib/resources/themes/base_theme.dart` konumundaki ortak bir oluşturucuyu ve `lib/resources/themes/color_styles.dart` konumundaki `ColorStyles` arayüzünü paylaşır.



<div id="creating-a-theme"></div>

## Tema oluşturma

Uygulamanız için birden fazla tema istiyorsanız, `lib/resources/themes/` altında tema dosyalarını manuel olarak oluşturun. Aşağıdaki adımlar örnek olarak `bright` kullanır — bunu kendi tema adınızla değiştirin.

**Adım 1:** `lib/resources/themes/bright/bright_theme.dart` konumunda tema dosyasını oluşturun:

``` dart
import 'package:flutter/material.dart';
import '/resources/themes/base_theme.dart';
import '/resources/themes/color_styles.dart';

ThemeData brightTheme(ColorStyles color) =>
    buildAppTheme(color, brightness: Brightness.light);
```

**Adım 2:** `lib/resources/themes/bright/bright_theme_colors.dart` konumunda renk dosyasını oluşturun:

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

**Adım 3:** Yeni temayı `lib/bootstrap/theme.dart` dosyasına kaydedin.

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

`bright_theme_colors.dart` dosyasındaki renkleri tasarımınıza uyacak şekilde ayarlayabilirsiniz.

<div id="theme-colors"></div>

## Tema Renkleri

Projenizdeki tema renklerini yönetmek için `lib/resources/themes/light/` ve `lib/resources/themes/dark/` dizinlerine bakın. Her biri kendi teması için renk dosyasını içerir — `light_theme_colors.dart` ve `dark_theme_colors.dart`.

Renk değerleri, framework tarafından tanımlanan gruplara (`general`, `appBar`, `bottomTabBar`) göre düzenlenir. Temanızın renk sınıfı `ColorStyles` sınıfını genişletir ve her grubun bir örneğini sağlar:

``` dart
// lib/resources/themes/light/light_theme_colors.dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';

class LightThemeColors extends ColorStyles {
  /// Genel kullanım için renkler.
  @override
  GeneralColors get general => const GeneralColors(
        background: Color(0xFFFFFFFF),
        content: Color(0xFF000000),
        primaryAccent: Color(0xFF0045a0),
        surface: Colors.white,
        surfaceContent: Colors.black,
      );

  /// Uygulama çubuğu için renkler.
  @override
  AppBarColors get appBar => const AppBarColors(
        background: Colors.white,
        content: Colors.black,
      );

  /// Alt sekme çubuğu için renkler.
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

## Widget'larda renkleri kullanma

Aktif temanın renklerini okumak için `nyColorStyle<T>(context)` yardımcısını kullanın. Çağrının tam olarak türlendirilmesi için projenizin `ColorStyles` türünü geçirin:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/themes/color_styles.dart';
...

// bir widget build metodu içinde:
final colors = nyColorStyle<ColorStyles>(context);

// aktif temanın arka plan rengi
colors.general.background

Text(
  "Hello World",
  style: TextStyle(
    color: colors.general.content,
  ),
),

// Belirli bir temadan renkleri okuma (hangisi aktif olursa olsun):
final dark = nyColorStyle<ColorStyles>(context, themeId: 'dark_theme');
Container(color: dark.general.background);
```

<div id="base-styles"></div>

## Temel stiller

Temel stiller, her temayı tek bir arayüz aracılığıyla tanımlamanıza olanak tanır. {{ config('app.name') }}, hem `light_theme_colors.dart` hem de `dark_theme_colors.dart` tarafından uygulanan sözleşme olan `lib/resources/themes/color_styles.dart` dosyasıyla birlikte gelir.

`ColorStyles`, frameworkten `ThemeColor` sınıfını genişletir; bu sınıf üç önceden tanımlanmış renk grubu sunar: `GeneralColors`, `AppBarColors` ve `BottomTabBarColors`. Temel tema oluşturucusu (`lib/resources/themes/base_theme.dart`), `ThemeData` oluşturulurken bu grupları okur; bu nedenle bunlara koyduğunuz her şey otomatik olarak eşleşen widget'lara bağlanır.

<br>

Dosya `lib/resources/themes/color_styles.dart`

``` dart
import 'package:nylo_framework/nylo_framework.dart';

abstract class ColorStyles extends ThemeColor {
  /// Genel kullanım için renkler.
  @override
  GeneralColors get general;

  /// Uygulama çubuğu için renkler.
  @override
  AppBarColors get appBar;

  /// Alt sekme çubuğu için renkler.
  @override
  BottomTabBarColors get bottomTabBar;
}
```

Üç grup şu alanları sunar:

- `GeneralColors` – `background`, `content`, `primaryAccent`, `surface`, `surfaceContent`
- `AppBarColors` – `background`, `content`
- `BottomTabBarColors` – `background`, `iconSelected`, `iconUnselected`, `labelSelected`, `labelUnselected`

Bu varsayılanların ötesinde alanlar eklemek için — kendi düğmeleriniz, simgeleriniz, rozetleriniz vb. — bkz. [Renk stillerini genişletme](#extending-color-styles).

<div id="extending-color-styles"></div>

## Renk stillerini genişletme

<!-- uncertain: new section "extending-color-styles" — no prior locale paragraphs in this file to draw register from -->
Üç varsayılan grup (`general`, `appBar`, `bottomTabBar`) bir başlangıç noktasıdır, katı bir sınır değildir. `lib/resources/themes/color_styles.dart` dosyası sizindir — varsayılanların üzerine yeni renk grupları (veya tek alanlar) ekleyin, ardından her temanın renk sınıfında bunları uygulayın.

**1. Özel bir renk grubu tanımlayın**

İlgili renkleri küçük, değişmez bir sınıfta gruplandırın:

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

**2. `ColorStyles`'a ekleyin**

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

  // Özel gruplar
  IconColors get icons;
}
```

**3. Her temanın renk sınıfında uygulayın**

``` dart
// lib/resources/themes/light/light_theme_colors.dart
class LightThemeColors extends ColorStyles {
  // ...mevcut geçersiz kılmalar...

  @override
  IconColors get icons => const IconColors(
        iconBackground: Color(0xFFEFEFEF),
        iconBackground1: Color(0xFFDADADA),
      );
}
```

Aynı `icons` geçersiz kılmasını koyu mod değerleriyle `dark_theme_colors.dart` dosyasında tekrarlayın.

**4. Widget'larınızda kullanın**

``` dart
final colors = nyColorStyle<ColorStyles>(context);
Container(color: colors.icons.iconBackground);
```

<div id="switching-theme"></div>

## Tema değiştirme

{{ config('app.name') }}, anında tema değiştirme özelliğini destekler.

Örn. Kullanıcı "koyu temayı" etkinleştirmek için bir düğmeye dokunursa temayı değiştirmeniz gerekiyorsa.

Aşağıdakileri yaparak bunu destekleyebilirsiniz:

``` dart
import 'package:nylo_framework/nylo_framework.dart';
...

TextButton(onPressed: () {

    // "koyu tema"yı kullanmak için temayı ayarla
    NyTheme.set(context, id: "dark_theme");
    setState(() { });

  }, child: Text("Dark Theme")
),

// veya

TextButton(onPressed: () {

    // "açık tema"yı kullanmak için temayı ayarla
    NyTheme.set(context, id: "light_theme");
    setState(() { });

  }, child: Text("Light Theme")
),
```


<div id="fonts"></div>

## Yazı tipleri

{{ config('app.name') }}'da uygulama genelinde birincil yazı tipinizi güncellemek kolaydır. `lib/config/design.dart` dosyasını açın ve `DesignConfig.appFont`'u güncelleyin.

``` dart
// lib/config/design.dart
final class DesignConfig {
  static final TextStyle appFont = GoogleFonts.outfit();
  // ...
}
```

Depoda <a href="https://pub.dev/packages/google_fonts" target="_BLANK">GoogleFonts</a> kütüphanesini dahil ediyoruz, böylece tüm yazı tiplerini az çabayla kullanmaya başlayabilirsiniz. Farklı bir Google Yazı Tipine geçmek için çağrıyı değiştirin:

``` dart
static final TextStyle appFont = GoogleFonts.montserrat();
```

Daha fazlasını anlamak için resmi <a href="https://pub.dev/packages/google_fonts" target="_BLANK">Google Fonts</a> kütüphanesindeki yazı tiplerine göz atın.

Özel bir yazı tipi mi kullanmanız gerekiyor? Bu kılavuza bakın - https://flutter.dev/docs/cookbook/design/fonts

Yazı tipinizi ekledikten sonra, değişkeni aşağıdaki örnekteki gibi değiştirin.

``` dart
static final TextStyle appFont = TextStyle(fontFamily: "ZenTokyoZoo"); // ZenTokyoZoo özel yazı tipi için örnek olarak kullanılmıştır
```

<div id="design"></div>

## Tasarım

**lib/config/design.dart** dosyası, uygulamanız için tasarım öğelerini yönetmek için kullanılır. Her şey `DesignConfig` sınıfı aracılığıyla sunulur:

`DesignConfig.appFont` uygulamanızın yazı tipini içerir.

`DesignConfig.logo` uygulamanızın Logosunu görüntülemek için kullanılır.

Logonuzu nasıl görüntülemek istediğinizi özelleştirmek için **lib/resources/widgets/logo_widget.dart** dosyasını değiştirebilirsiniz.

`DesignConfig.loader` bir yükleyici görüntülemek için kullanılır. {{ config('app.name') }}, bazı yardımcı metotlarda bu değişkeni varsayılan yükleyici widget'ı olarak kullanacaktır.

Yükleyicinizi nasıl görüntülemek istediğinizi özelleştirmek için **lib/resources/widgets/loader_widget.dart** dosyasını değiştirebilirsiniz.

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
// colorStyles'ınızdan renk
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
