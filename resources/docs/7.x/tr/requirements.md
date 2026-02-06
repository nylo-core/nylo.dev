# Gereksinimler

---

<a name="section-1"></a>
- [Sistem Gereksinimleri](#system-requirements "Sistem Gereksinimleri")
- [Flutter Kurulumu](#installing-flutter "Flutter Kurulumu")
- [Kurulumunuzu Do&#287;rulama](#verifying-installation "Kurulumunuzu Do&#287;rulama")
- [Edit&#246;r Kurulumu](#set-up-an-editor "Edit&#246;r Kurulumu")


<div id="system-requirements"></div>

## Sistem Gereksinimleri

{{ config('app.name') }} v7 a&#351;a&#287;&#305;daki minimum s&#252;r&#252;mleri gerektirir:

| Gereksinim | Minimum S&#252;r&#252;m |
|-------------|-----------------|
| **Flutter** | 3.24.0 veya &#252;zeri |
| **Dart SDK** | 3.10.7 veya &#252;zeri |

### Platform Deste&#287;i

{{ config('app.name') }}, Flutter'&#305;n destekledi&#287;i t&#252;m platformlar&#305; destekler:

| Platform | Destek |
|----------|---------|
| iOS | &#10003; Tam destek |
| Android | &#10003; Tam destek |
| Web | &#10003; Tam destek |
| macOS | &#10003; Tam destek |
| Windows | &#10003; Tam destek |
| Linux | &#10003; Tam destek |

<div id="installing-flutter"></div>

## Flutter Kurulumu

Flutter kurulu de&#287;ilse, i&#351;letim sisteminize uygun resmi kurulum k&#305;lavuzunu takip edin:

- <a href="https://docs.flutter.dev/get-started/install" target="_BLANK">Flutter Kurulum Kilavuzu</a>

<div id="verifying-installation"></div>

## Kurulumunuzu Do&#287;rulama

Flutter'&#305; kurduktan sonra kurulumunuzu do&#287;rulay&#305;n:

### Flutter S&#252;r&#252;m&#252;n&#252; Kontrol Etme

``` bash
flutter --version
```

&#350;una benzer bir &#231;&#305;kt&#305; g&#246;rmelisiniz:

```
Flutter 3.24.0 • channel stable
Dart SDK version: 3.10.7
```

### Flutter'&#305; G&#252;ncelleme (gerekirse)

Flutter s&#252;r&#252;m&#252;n&#252;z 3.24.0'&#305;n alt&#305;ndaysa, en son kararl&#305; s&#252;r&#252;me y&#252;kseltin:

``` bash
flutter channel stable
flutter upgrade
```

### Flutter Doctor &#199;al&#305;&#351;t&#305;rma

Geli&#351;tirme ortam&#305;n&#305;z&#305;n d&#252;zg&#252;n yap&#305;land&#305;r&#305;ld&#305;&#287;&#305;n&#305; do&#287;rulay&#305;n:

``` bash
flutter doctor -v
```

Bu komut &#351;unlar&#305; kontrol eder:
- Flutter SDK kurulumu
- Android ara&#231; zinciri (Android geli&#351;tirme i&#231;in)
- Xcode (iOS/macOS geli&#351;tirme i&#231;in)
- Ba&#287;l&#305; cihazlar
- IDE eklentileri

{{ config('app.name') }} kurulumuna ge&#231;meden &#246;nce bildirilen sorunlar&#305; d&#252;zeltin.

<div id="set-up-an-editor"></div>

## Edit&#246;r Kurulumu

Flutter deste&#287;i olan bir IDE se&#231;in:

### Visual Studio Code (&#214;nerilen)

<a href="https://code.visualstudio.com" target="_BLANK">VS Code</a> hafiftir ve m&#252;kemmel Flutter deste&#287;ine sahiptir.

1. <a href="https://code.visualstudio.com" target="_BLANK">VS Code</a>'u kurun
2. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.flutter" target="_BLANK">Flutter eklentisini</a> kurun
3. <a href="https://marketplace.visualstudio.com/items?itemName=Dart-Code.dart-code" target="_BLANK">Dart eklentisini</a> kurun

Kurulum k&#305;lavuzu: <a href="https://docs.flutter.dev/get-started/editor?tab=vscode" target="_BLANK">VS Code Flutter Kurulumu</a>

### Android Studio

<a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>, yerle&#351;ik em&#252;lat&#246;r deste&#287;i ile tam &#246;zellikli bir IDE sunar.

1. <a href="https://developer.android.com/studio" target="_BLANK">Android Studio</a>'yu kurun
2. Flutter eklentisini kurun (Tercihler &#8594; Eklentiler &#8594; Flutter)
3. Dart eklentisini kurun

Kurulum k&#305;lavuzu: <a href="https://docs.flutter.dev/get-started/editor?tab=androidstudio" target="_BLANK">Android Studio Flutter Kurulumu</a>

### IntelliJ IDEA

<a href="https://www.jetbrains.com/idea/" target="_BLANK">IntelliJ IDEA</a> (Community veya Ultimate) da Flutter geli&#351;tirmeyi destekler.

1. IntelliJ IDEA'y&#305; kurun
2. Flutter eklentisini kurun (Tercihler &#8594; Eklentiler &#8594; Flutter)
3. Dart eklentisini kurun

Edit&#246;r&#252;n&#252;z yap&#305;land&#305;r&#305;ld&#305;&#287;&#305;nda, [{{ config('app.name') }} kurulumuna](/docs/7.x/installation) haz&#305;rs&#305;n&#305;z.
