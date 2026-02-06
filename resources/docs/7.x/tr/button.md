# Button

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Temel Kullan&#305;m](#basic-usage "Temel Kullan&#305;m")
- [Mevcut Button T&#252;rleri](#button-types "Mevcut Button T&#252;rleri")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Text Only](#text-only "Text Only")
    - [Icon](#icon "Icon")
    - [Gradient](#gradient "Gradient")
    - [Rounded](#rounded "Rounded")
    - [Transparency](#transparency "Transparency")
- [Async Y&#252;kleme Durumu](#async-loading "Async Y&#252;kleme Durumu")
- [Animasyon Stilleri](#animation-styles "Animasyon Stilleri")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Splash Stilleri](#splash-styles "Splash Stilleri")
- [Y&#252;kleme Stilleri](#loading-styles "Y&#252;kleme Stilleri")
- [Form G&#246;nderimi](#form-submission "Form G&#246;nderimi")
- [Button&#39;lar&#305; &#214;zelle&#351;tirme](#customizing-buttons "Button&#39;lar&#305; &#214;zelle&#351;tirme")
- [Parametre Referans&#305;](#parameters "Parametre Referans&#305;")


<div id="introduction"></div>

## Giri&#351;

{{ config('app.name') }} kullan&#305;ma haz&#305;r sekiz button stili ile bir `Button` s&#305;n&#305;f&#305; sa&#287;lar. Her button &#351;unlar i&#231;in yerle&#351;ik destek sunar:

- **Async y&#252;kleme durumlar&#305;** -- `onPressed`'den bir `Future` d&#246;nd&#252;r&#252;n ve button otomatik olarak bir y&#252;kleme g&#246;stergesi g&#246;sterir
- **Animasyon stilleri** -- clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph ve shake efektleri aras&#305;ndan se&#231;in
- **Splash stilleri** -- ripple, highlight, glow veya ink dokunma geri bildirimi ekleyin
- **Form g&#246;nderimi** -- bir button'&#305; do&#287;rudan bir `NyFormData` &#246;rne&#287;ine ba&#287;lay&#305;n

Uygulaman&#305;z&#305;n button tan&#305;mlar&#305;n&#305; `lib/resources/widgets/buttons/buttons.dart` dosyas&#305;nda bulabilirsiniz. Bu dosya, her button t&#252;r&#252; i&#231;in statik metotlar i&#231;eren bir `Button` s&#305;n&#305;f&#305; i&#231;erir ve projeniz i&#231;in varsay&#305;lan de&#287;erleri &#246;zelle&#351;tirmeyi kolayla&#351;t&#305;r&#305;r.

<div id="basic-usage"></div>

## Temel Kullan&#305;m

`Button` s&#305;n&#305;f&#305;n&#305; widget'lar&#305;n&#305;z&#305;n herhangi bir yerinde kullan&#305;n. &#304;&#351;te bir sayfa i&#231;indeki basit bir &#246;rnek:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Her button t&#252;r&#252; ayn&#305; kal&#305;b&#305; izler -- bir `text` etiketi ve bir `onPressed` callback'i ge&#231;irin.

<div id="button-types"></div>

## Mevcut Button T&#252;rleri

T&#252;m button'lara statik metotlar kullanarak `Button` s&#305;n&#305;f&#305; &#252;zerinden eri&#351;ilir.

<div id="primary"></div>

### Primary

Teman&#305;z&#305;n birincil rengini kullanan, g&#246;lgeli dolgulu bir button. Ana eylem &#231;a&#287;r&#305;s&#305; &#246;&#287;eleri i&#231;in en uygun.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Daha yumu&#351;ak bir y&#252;zey rengi ve hafif g&#246;lge ile dolgulu bir button. Birincil button'&#305;n yan&#305;ndaki ikincil eylemler i&#231;in uygundur.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Kenarl&#305;k &#231;izgisi olan &#351;effaf bir button. Daha az belirgin eylemler veya iptal button'lar&#305; i&#231;in kullan&#305;&#351;l&#305;d&#305;r.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Kenarl&#305;k ve metin renklerini &#246;zelle&#351;tirebilirsiniz:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Text Only

Arka plan&#305; veya kenarl&#305;&#287;&#305; olmayan minimal bir button. Sat&#305;r i&#231;i eylemler veya ba&#287;lant&#305;lar i&#231;in idealdir.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Metin rengini &#246;zelle&#351;tirebilirsiniz:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icon

Metnin yan&#305;nda bir simge g&#246;steren dolgulu bir button. Simge varsay&#305;lan olarak metnin &#246;n&#252;nde g&#246;r&#252;n&#252;r.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Arka plan rengini &#246;zelle&#351;tirebilirsiniz:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradient

Do&#287;rusal gradyan arka plana sahip bir button. Varsay&#305;lan olarak teman&#305;z&#305;n birincil ve &#252;&#231;&#252;nc&#252;l renklerini kullan&#305;r.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

&#214;zel gradyan renkleri sa&#287;layabilirsiniz:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Rounded

Tamamen yuvarlat&#305;lm&#305;&#351; k&#246;&#351;elere sahip hap &#351;eklinde bir button. Kenarl&#305;k yar&#305;&#231;ap&#305; varsay&#305;lan olarak button y&#252;ksekli&#287;inin yar&#305;s&#305;d&#305;r.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Arka plan rengini ve kenarl&#305;k yar&#305;&#231;ap&#305;n&#305; &#246;zelle&#351;tirebilirsiniz:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparency

Arka plan bulan&#305;kla&#351;t&#305;rma efektli buzlu cam tarz&#305; bir button. G&#246;r&#252;nt&#252;lerin veya renkli arka planlar&#305;n &#252;zerine yerle&#351;tirildi&#287;inde iyi &#231;al&#305;&#351;&#305;r.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Metin rengini &#246;zelle&#351;tirebilirsiniz:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Async Y&#252;kleme Durumu

{{ config('app.name') }} button'lar&#305;n&#305;n en g&#252;&#231;l&#252; &#246;zelliklerinden biri **otomatik y&#252;kleme durumu y&#246;netimidir**. `onPressed` callback'iniz bir `Future` d&#246;nd&#252;rd&#252;&#287;&#252;nde, button otomatik olarak bir y&#252;kleme g&#246;stergesi g&#246;sterir ve i&#351;lem tamamlan&#305;ncaya kadar etkile&#351;imi devre d&#305;&#351;&#305; b&#305;rak&#305;r.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Async i&#351;lem &#231;al&#305;&#351;&#305;rken, button bir iskelet y&#252;kleme efekti g&#246;sterir (varsay&#305;lan olarak). `Future` tamamland&#305;&#287;&#305;nda, button normal durumuna d&#246;ner.

Bu, herhangi bir async i&#351;lemle &#231;al&#305;&#351;&#305;r -- API &#231;a&#287;r&#305;lar&#305;, veritaban&#305; yazmalar&#305;, dosya y&#252;klemeleri veya `Future` d&#246;nd&#252;ren herhangi bir &#351;ey:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

`isLoading` durum de&#287;i&#351;kenlerini y&#246;netmeye, `setState` &#231;a&#287;&#305;rmaya veya herhangi bir &#351;eyi `StatefulWidget` ile sarmaya gerek yok -- {{ config('app.name') }} her &#351;eyi sizin i&#231;in halleder.

### Nas&#305;l &#199;al&#305;&#351;&#305;r

Button, `onPressed`'in bir `Future` d&#246;nd&#252;rd&#252;&#287;&#252;n&#252; alg&#305;lad&#305;&#287;&#305;nda, &#351;unlar&#305; yapmak i&#231;in `lockRelease` mekanizmas&#305;n&#305; kullan&#305;r:

1. Bir y&#252;kleme g&#246;stergesi g&#246;sterme (`LoadingStyle` taraf&#305;ndan kontrol edilir)
2. &#199;ift dokunmay&#305; &#246;nlemek i&#231;in button'&#305; devre d&#305;&#351;&#305; b&#305;rakma
3. `Future`'&#305;n tamamlanmas&#305;n&#305; bekleme
4. Button'&#305; normal durumuna geri y&#252;kleme

<div id="animation-styles"></div>

## Animasyon Stilleri

Button'lar `ButtonAnimationStyle` arac&#305;l&#305;&#287;&#305;yla basma animasyonlar&#305;n&#305; destekler. Bu animasyonlar, bir kullan&#305;c&#305; bir button ile etkile&#351;ime girdi&#287;inde g&#246;rsel geri bildirim sa&#287;lar. Button'lar&#305;n&#305;z&#305; `lib/resources/widgets/buttons/buttons.dart` dosyas&#305;nda &#246;zelle&#351;tirirken animasyon stilini ayarlayabilirsiniz.

<div id="anim-clickable"></div>

### Clickable

Duolingo tarz&#305; bir 3D basma efekti. Button bas&#305;ld&#305;&#287;&#305;nda a&#351;a&#287;&#305; hareket eder ve b&#305;rak&#305;ld&#305;&#287;&#305;nda geri z&#305;plar. Birincil eylemler ve oyun benzeri UX i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Button'&#305; bas&#305;ld&#305;&#287;&#305;nda k&#252;&#231;&#252;lt&#252;r ve b&#305;rak&#305;ld&#305;&#287;&#305;nda geri z&#305;plat&#305;r. Sepete ekle, be&#287;en ve favori button'lar&#305; i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Button bas&#305;l&#305; tutulurken hafif s&#252;rekli bir &#246;l&#231;ek nab&#305;z at&#305;&#351;&#305;. Uzun basma eylemleri veya dikkat &#231;ekmek i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Button'&#305; bas&#305;ld&#305;&#287;&#305;nda yatay olarak s&#305;k&#305;&#351;t&#305;r&#305;r ve dikey olarak geni&#351;letir. E&#287;lenceli ve etkile&#351;imli kullan&#305;c&#305; aray&#252;zleri i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Sallan&#305;ml&#305; elastik bir deformasyon efekti. E&#287;lenceli, rahat veya e&#287;lence uygulamalar&#305; i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Bas&#305;ld&#305;&#287;&#305;nda button &#252;zerinden ge&#231;en parlak bir vurgu. Premium &#246;zellikler veya dikkat &#231;ekmek istedi&#287;iniz CTA'lar i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Dokunma noktas&#305;ndan geni&#351;leyen geli&#351;tirilmi&#351; bir dalga efekti. Material Design vurgusu i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

Button'&#305;n kenarl&#305;k yar&#305;&#231;ap&#305; bas&#305;ld&#305;&#287;&#305;nda artar ve bir &#351;ekil de&#287;i&#351;tirme efekti olu&#351;turur. &#304;nce, zarif geri bildirim i&#231;in en uygun.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Yatay bir sallama animasyonu. Hata durumlar&#305; veya ge&#231;ersiz eylemler i&#231;in en uygun -- bir &#351;eylerin yanl&#305;&#351; gitti&#287;ini belirtmek i&#231;in button'&#305; sallay&#305;n.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Efekti ince ayarlay&#305;n:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Animasyonlar&#305; Devre D&#305;&#351;&#305; B&#305;rakma

Animasyonsuz bir button kullanmak i&#231;in:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Varsay&#305;lan Animasyonu De&#287;i&#351;tirme

Bir button t&#252;r&#252; i&#231;in varsay&#305;lan animasyonu de&#287;i&#351;tirmek i&#231;in, `lib/resources/widgets/buttons/buttons.dart` dosyan&#305;z&#305; d&#252;zenleyin:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Splash Stilleri

Splash efektleri button'larda g&#246;rsel dokunma geri bildirimi sa&#287;lar. Bunlar&#305; `ButtonSplashStyle` arac&#305;l&#305;&#287;&#305;yla yap&#305;land&#305;r&#305;n. Splash stilleri, katmanl&#305; geri bildirim i&#231;in animasyon stilleriyle birle&#351;tirilebilir.

### Mevcut Splash Stilleri

| Splash | Factory | A&#231;&#305;klama |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Dokunma noktas&#305;ndan standart Material dalgas&#305; |
| Highlight | `ButtonSplashStyle.highlight()` | Dalga animasyonu olmadan hafif vurgulama |
| Glow | `ButtonSplashStyle.glow()` | Dokunma noktas&#305;ndan yay&#305;lan yumu&#351;ak par&#305;lt&#305; |
| Ink | `ButtonSplashStyle.ink()` | H&#305;zl&#305; m&#252;rekkep s&#305;&#231;ramas&#305;, daha h&#305;zl&#305; ve daha duyarl&#305; |
| None | `ButtonSplashStyle.none()` | Splash efekti yok |
| Custom | `ButtonSplashStyle.custom()` | Splash factory &#252;zerinde tam kontrol |

### &#214;rnek

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Splash renklerini ve opaklığını &#246;zelle&#351;tirebilirsiniz:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Y&#252;kleme Stilleri

Async i&#351;lemler s&#305;ras&#305;nda g&#246;sterilen y&#252;kleme g&#246;stergesi `LoadingStyle` taraf&#305;ndan kontrol edilir. Bunu buttons dosyan&#305;zda button t&#252;r&#252; ba&#351;&#305;na ayarlayabilirsiniz.

### Skeletonizer (Varsay&#305;lan)

Button &#252;zerinde bir shimmer iskelet efekti g&#246;sterir:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Bir y&#252;kleme widget'&#305; g&#246;sterir (varsay&#305;lan olarak uygulama y&#252;kleyicisi):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Button'&#305; g&#246;r&#252;n&#252;r tutar ancak y&#252;kleme s&#305;ras&#305;nda etkile&#351;imi devre d&#305;&#351;&#305; b&#305;rak&#305;r:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Form G&#246;nderimi

T&#252;m button'lar, button'&#305; bir `NyForm`'a ba&#287;layan `submitForm` parametresini destekler. Dokunuldu&#287;unda, button formu do&#287;rulayacak ve form verileriyle ba&#351;ar&#305; i&#351;leyicinizi &#231;a&#287;&#305;racakt&#305;r.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

`submitForm` parametresi iki de&#287;erli bir record kabul eder:
1. Bir `NyFormData` &#246;rne&#287;i (veya `String` olarak form ad&#305;)
2. Do&#287;rulanm&#305;&#351; verileri alan bir callback

Varsay&#305;lan olarak, `showToastError` `true`'dur ve form do&#287;rulamas&#305; ba&#351;ar&#305;s&#305;z oldu&#287;unda bir toast bildirimi g&#246;sterir. Hatalar&#305; sessizce ele almak i&#231;in `false` olarak ayarlay&#305;n:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

`submitForm` callback'i bir `Future` d&#246;nd&#252;rd&#252;&#287;&#252;nde, button async i&#351;lem tamamlan&#305;ncaya kadar otomatik olarak bir y&#252;kleme durumu g&#246;sterecektir.

<div id="customizing-buttons"></div>

## Button'lar&#305; &#214;zelle&#351;tirme

T&#252;m button varsay&#305;lanlar&#305; projenizde `lib/resources/widgets/buttons/buttons.dart` dosyas&#305;nda tan&#305;mlan&#305;r. Her button t&#252;r&#252;n&#252;n `lib/resources/widgets/buttons/partials/` i&#231;inde kar&#351;&#305;l&#305;k gelen bir widget s&#305;n&#305;f&#305; vard&#305;r.

### Varsay&#305;lan Stilleri De&#287;i&#351;tirme

Bir button'&#305;n varsay&#305;lan g&#246;r&#252;n&#252;m&#252;n&#252; de&#287;i&#351;tirmek i&#231;in, `Button` s&#305;n&#305;f&#305;n&#305; d&#252;zenleyin:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Bir Button Widget'&#305;n&#305; &#214;zelle&#351;tirme

Bir button t&#252;r&#252;n&#252;n g&#246;rsel g&#246;r&#252;n&#252;m&#252;n&#252; de&#287;i&#351;tirmek i&#231;in, `lib/resources/widgets/buttons/partials/` i&#231;indeki ilgili widget'&#305; d&#252;zenleyin. &#214;rne&#287;in, birincil button'&#305;n kenarl&#305;k yar&#305;&#231;ap&#305;n&#305; veya g&#246;lgesini de&#287;i&#351;tirmek i&#231;in:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Yeni Bir Button T&#252;r&#252; Olu&#351;turma

Yeni bir button t&#252;r&#252; eklemek i&#231;in:

1. `lib/resources/widgets/buttons/partials/` i&#231;inde `StatefulAppButton`'&#305; geni&#351;leten yeni bir widget dosyas&#305; olu&#351;turun.
2. `buildButton` metodunu uygulay&#305;n.
3. `Button` s&#305;n&#305;f&#305;na bir statik metot ekleyin.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Ard&#305;ndan `Button` s&#305;n&#305;f&#305;na kaydedin:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    (dynamic, Function(dynamic data))? submitForm,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Parametre Referans&#305;

### Ortak Parametreler (T&#252;m Button T&#252;rleri)

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `text` | `String` | zorunlu | Button etiket metni |
| `onPressed` | `VoidCallback?` | `null` | Button'a dokunuldu&#287;unda callback. Otomatik y&#252;kleme durumu i&#231;in bir `Future` d&#246;nd&#252;r&#252;n |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Form g&#246;nderim kayd&#305; (form &#246;rne&#287;i, ba&#351;ar&#305; callback'i) |
| `onFailure` | `Function(dynamic)?` | `null` | Form do&#287;rulamas&#305; ba&#351;ar&#305;s&#305;z oldu&#287;unda &#231;a&#287;r&#305;l&#305;r |
| `showToastError` | `bool` | `true` | Form do&#287;rulama hatas&#305;nda toast bildirimi g&#246;ster |
| `width` | `double?` | `null` | Button geni&#351;li&#287;i (varsay&#305;lan tam geni&#351;lik) |

### T&#252;re &#214;zel Parametreler

#### Button.outlined

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Tema outline rengi | Kenarl&#305;k &#231;izgi rengi |
| `textColor` | `Color?` | Tema birincil rengi | Metin rengi |

#### Button.textOnly

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Tema birincil rengi | Metin rengi |

#### Button.icon

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `icon` | `Widget` | zorunlu | G&#246;sterilecek simge widget'&#305; |
| `color` | `Color?` | Tema birincil rengi | Arka plan rengi |

#### Button.gradient

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Birincil ve &#252;&#231;&#252;nc&#252;l renkler | Gradyan renk duraklar&#305; |

#### Button.rounded

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Tema primary container rengi | Arka plan rengi |
| `borderRadius` | `BorderRadius?` | Hap &#351;ekli (y&#252;kseklik / 2) | K&#246;&#351;e yar&#305;&#231;ap&#305; |

#### Button.transparency

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `color` | `Color?` | Tema uyumlu | Metin rengi |

### ButtonAnimationStyle Parametreleri

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `duration` | `Duration` | T&#252;re g&#246;re de&#287;i&#351;ir | Animasyon s&#252;resi |
| `curve` | `Curve` | T&#252;re g&#246;re de&#287;i&#351;ir | Animasyon e&#287;risi |
| `enableHapticFeedback` | `bool` | T&#252;re g&#246;re de&#287;i&#351;ir | Bas&#305;ld&#305;&#287;&#305;nda dokunsal geri bildirim tetikle |
| `translateY` | `double` | `4.0` | Clickable: dikey basma mesafesi |
| `shadowOffset` | `double` | `4.0` | Clickable: g&#246;lge derinli&#287;i |
| `scaleMin` | `double` | `0.92` | Bounce: bas&#305;ld&#305;&#287;&#305;nda minimum &#246;l&#231;ek |
| `pulseScale` | `double` | `1.05` | Pulse: nab&#305;z s&#305;ras&#305;nda maksimum &#246;l&#231;ek |
| `squeezeX` | `double` | `0.95` | Squeeze: yatay s&#305;k&#305;&#351;t&#305;rma |
| `squeezeY` | `double` | `1.05` | Squeeze: dikey geni&#351;leme |
| `jellyStrength` | `double` | `0.15` | Jelly: sallanma yo&#287;unlu&#287;u |
| `shineColor` | `Color` | `Colors.white` | Shine: vurgu rengi |
| `shineWidth` | `double` | `0.3` | Shine: parlakl&#305;k band&#305; geni&#351;li&#287;i |
| `rippleScale` | `double` | `2.0` | Ripple: geni&#351;leme &#246;l&#231;e&#287;i |
| `morphRadius` | `double` | `24.0` | Morph: hedef kenarl&#305;k yar&#305;&#231;ap&#305; |
| `shakeOffset` | `double` | `8.0` | Shake: yatay yer de&#287;i&#351;tirme |
| `shakeCount` | `int` | `3` | Shake: sallanma say&#305;s&#305; |

### ButtonSplashStyle Parametreleri

| Parametre | T&#252;r | Varsay&#305;lan | A&#231;&#305;klama |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Tema surface rengi | Splash efekt rengi |
| `highlightColor` | `Color?` | Tema surface rengi | Highlight efekt rengi |
| `splashOpacity` | `double` | `0.12` | Splash opakl&#305;&#287;&#305; |
| `highlightOpacity` | `double` | `0.06` | Highlight opakl&#305;&#287;&#305; |
| `borderRadius` | `BorderRadius?` | `null` | Splash klip yar&#305;&#231;ap&#305; |
