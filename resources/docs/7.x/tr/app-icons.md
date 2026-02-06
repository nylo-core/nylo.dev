# Uygulama Simgeleri

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Uygulama Simgeleri Olu&#351;turma](#generating-app-icons "Uygulama simgeleri olu&#351;turma")
- [Uygulama Simgenizi Ekleme](#adding-your-app-icon "Uygulama simgenizi ekleme")
- [Uygulama Simgesi Gereksinimleri](#app-icon-requirements "Uygulama simgesi gereksinimleri")
- [Yap&#305;land&#305;rma](#configuration "Yap&#305;land&#305;rma")
- [Rozet Say&#305;s&#305;](#badge-count "Rozet say&#305;s&#305;")

<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }} v7, tek bir kaynak g&#246;rselden iOS ve Android i&#231;in uygulama simgeleri olu&#351;turmak &#252;zere <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons</a> kullan&#305;r.

Uygulama simgeniz `assets/app_icon/` dizinine **1024x1024 piksel** boyutunda yerle&#351;tirilmelidir.

<div id="generating-app-icons"></div>

## Uygulama Simgeleri Olu&#351;turma

T&#252;m platformlar i&#231;in simge olu&#351;turmak &#252;zere a&#351;a&#287;&#305;daki komutu &#231;al&#305;&#351;t&#305;r&#305;n:

``` bash
dart run flutter_launcher_icons
```

Bu, kaynak simgenizi `assets/app_icon/` dizininden okur ve &#351;unlar&#305; olu&#351;turur:
- iOS simgeleri `ios/Runner/Assets.xcassets/AppIcon.appiconset/` dizininde
- Android simgeleri `android/app/src/main/res/mipmap-*/` dizininde

<div id="adding-your-app-icon"></div>

## Uygulama Simgenizi Ekleme

1. Simgenizi **1024x1024 PNG** dosyas&#305; olarak olu&#351;turun
2. `assets/app_icon/` dizinine yerle&#351;tirin (&#246;rn., `assets/app_icon/icon.png`)
3. Gerekirse `pubspec.yaml` dosyan&#305;zdaki `image_path` de&#287;erini g&#252;ncelleyin:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"
```

4. Simge olu&#351;turma komutunu &#231;al&#305;&#351;t&#305;r&#305;n

<div id="app-icon-requirements"></div>

## Uygulama Simgesi Gereksinimleri

| &#214;zellik | De&#287;er |
|-----------|-------|
| Format | PNG |
| Boyut | 1024x1024 piksel |
| Katmanlar | Saydaml&#305;k olmadan d&#252;zle&#351;tirilmi&#351; |

### Dosya Adland&#305;rma

Dosya adlar&#305;n&#305; &#246;zel karakterler olmadan basit tutun:
- `app_icon.png`
- `icon.png`

### Platform K&#305;lavuzlar&#305;

Ayr&#305;nt&#305;l&#305; gereksinimler i&#231;in resmi platform k&#305;lavuzlar&#305;na ba&#351;vurun:
- <a href="https://developer.apple.com/design/human-interface-guidelines/app-icons" target="_BLANK">Apple &#304;nsan Aray&#252;z&#252; K&#305;lavuzlar&#305; - Uygulama Simgeleri</a>
- <a href="https://developer.android.com/distribute/google-play/resources/icon-design-specifications" target="_BLANK">Google Play Simge Tasar&#305;m Spesifikasyonlar&#305;</a>

<div id="configuration"></div>

## Yap&#305;land&#305;rma

`pubspec.yaml` dosyan&#305;zda simge olu&#351;turmay&#305; &#246;zelle&#351;tirin:

``` yaml
flutter_launcher_icons:
  android: true
  ios: true
  image_path: "assets/app_icon/icon.png"

  # Optional: Use different icons per platform
  # image_path_android: "assets/app_icon/android_icon.png"
  # image_path_ios: "assets/app_icon/ios_icon.png"

  # Optional: Adaptive icons for Android
  # adaptive_icon_background: "#ffffff"
  # adaptive_icon_foreground: "assets/app_icon/foreground.png"

  # Optional: Remove alpha channel for iOS
  # remove_alpha_ios: true
```

T&#252;m mevcut se&#231;enekler i&#231;in <a href="https://pub.dev/packages/flutter_launcher_icons" target="_BLANK">flutter_launcher_icons dok&#252;mantasyonuna</a> bak&#305;n.

<div id="badge-count"></div>

## Rozet Say&#305;s&#305;

{{ config('app.name') }}, uygulama rozet say&#305;lar&#305;n&#305; (uygulama simgesinde g&#246;sterilen say&#305;) y&#246;netmek i&#231;in yard&#305;mc&#305; fonksiyonlar sa&#287;lar:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Set badge count to 5
await setBadgeNumber(5);

// Clear the badge count
await clearBadgeNumber();
```

### Platform Deste&#287;i

Rozet say&#305;lar&#305; &#351;u platformlarda desteklenir:
- **iOS**: Yerel destek
- **Android**: Ba&#351;lat&#305;c&#305; deste&#287;i gerektirir (&#231;o&#287;u ba&#351;lat&#305;c&#305; destekler)
- **Web**: Desteklenmez

### Kullan&#305;m Senaryolar&#305;

Rozet say&#305;lar&#305; i&#231;in yayg&#305;n senaryolar:
- Okunmam&#305;&#351; bildirimler
- Bekleyen mesajlar
- Sepetteki &#252;r&#252;nler
- Tamamlanmam&#305;&#351; g&#246;revler

``` dart
// Example: Update badge when new messages arrive
void onNewMessage() async {
  int unreadCount = await MessageService.getUnreadCount();
  await setBadgeNumber(unreadCount);
}

// Example: Clear badge when user views messages
void onMessagesViewed() async {
  await clearBadgeNumber();
}
```

