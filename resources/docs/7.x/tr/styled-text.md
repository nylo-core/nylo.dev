# Styled Text

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Children Modu](#children-mode "Children Modu")
- [Şablon Modu](#template-mode "Şablon Modu")
  - [Yer Tutucuları Stillendirme](#styling-placeholders "Yer Tutucuları Stillendirme")
  - [Dokunma Geri Çağırmaları](#tap-callbacks "Dokunma Geri Çağırmaları")
  - [Pipe ile Ayrılmış Anahtarlar](#pipe-keys "Pipe ile Ayrılmış Anahtarlar")
- [Parametreler](#parameters "Parametreler")
- [Text Extension'ları](#text-extensions "Text Extension'ları")
  - [Tipografi Stilleri](#typography-styles "Tipografi Stilleri")
  - [Yardımcı Metotlar](#utility-methods "Yardımcı Metotlar")
- [Örnekler](#examples "Örnekler")

<div id="introduction"></div>

## Giriş

**StyledText**, karma stiller, dokunma geri çağırmaları ve işaretçi olayları ile zengin metin görüntülemek için bir widget'tır. Birden fazla `TextSpan` alt öğesiyle bir `RichText` widget'ı olarak render edilir ve metnin her bir bölümü üzerinde ayrıntılı kontrol sağlar.

StyledText iki modu destekler:

1. **Children modu** -- her biri kendi stiline sahip `Text` widget'larının listesini geçirin
2. **Şablon modu** -- bir dizede `@{{yer tutucu}}` sözdizimini kullanın ve yer tutucuları stillere ve eylemlere eşleyin

<div id="basic-usage"></div>

## Temel Kullanım

``` dart
// Children mode - list of Text widgets
StyledText(
  children: [
    Text("Hello ", style: TextStyle(color: Colors.black)),
    Text("World", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
  ],
)

// Template mode - placeholder syntax
StyledText.template(
  "Welcome to @{{Nylo}}!",
  styles: {
    "Nylo": TextStyle(fontWeight: FontWeight.bold, color: Colors.blue),
  },
)
```

<div id="children-mode"></div>

## Children Modu

Stillendirilmiş metin oluşturmak için bir `Text` widget'ları listesi geçirin:

``` dart
StyledText(
  style: TextStyle(fontSize: 16, color: Colors.black),
  children: [
    Text("Your order "),
    Text("#1234", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" has been "),
    Text("confirmed", style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
    Text("."),
  ],
)
```

Temel `style`, kendi stili olmayan tüm alt öğelere uygulanır.

### İşaretçi Olayları

İşaretçinin bir metin segmentine girdiğini veya çıktığını algılayın:

``` dart
StyledText(
  onEnter: (Text text, PointerEnterEvent event) {
    print("Hovering over: ${text.data}");
  },
  onExit: (Text text, PointerExitEvent event) {
    print("Left: ${text.data}");
  },
  children: [
    Text("Hover me", style: TextStyle(color: Colors.blue)),
    Text(" or "),
    Text("me", style: TextStyle(color: Colors.red)),
  ],
)
```

<div id="template-mode"></div>

## Şablon Modu

`@{{yer tutucu}}` sözdizimi ile `StyledText.template()` kullanın:

``` dart
StyledText.template(
  "By continuing, you agree to our @{{Terms of Service}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey),
  styles: {
    "Terms of Service": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
    "Privacy Policy": TextStyle(color: Colors.blue, decoration: TextDecoration.underline),
  },
  onTap: {
    "Terms of Service": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

`@{{ }}` arasındaki metin hem **görüntülenen metin** hem de stilleri ve dokunma geri çağırmalarını aramak için kullanılan **anahtar**dır.

<div id="styling-placeholders"></div>

### Yer Tutucuları Stillendirme

Yer tutucu adlarını `TextStyle` nesnelerine eşleyin:

``` dart
StyledText.template(
  "Framework Version: @{{$version}}",
  style: textTheme.bodyMedium,
  styles: {
    version: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

<div id="tap-callbacks"></div>

### Dokunma Geri Çağırmaları

Yer tutucu adlarını dokunma işleyicilerine eşleyin:

``` dart
StyledText.template(
  "@{{Sign up}} or @{{Log in}} to continue.",
  onTap: {
    "Sign up": () => routeTo(SignUpPage.path),
    "Log in": () => routeTo(LoginPage.path),
  },
  styles: {
    "Sign up": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
    "Log in": TextStyle(color: Colors.blue, fontWeight: FontWeight.bold),
  },
)
```

<div id="pipe-keys"></div>

### Pipe ile Ayrılmış Anahtarlar

Pipe `|` ile ayrılmış anahtarları kullanarak aynı stili veya geri çağırmayı birden fazla yer tutucuya uygulayın:

``` dart
StyledText.template(
  "Available in @{{English}}, @{{French}}, and @{{German}}.",
  styles: {
    "English|French|German": TextStyle(
      fontWeight: FontWeight.bold,
      color: Colors.blue,
    ),
  },
  onTap: {
    "English|French|German": () => showLanguagePicker(),
  },
)
```

Bu, aynı stili ve geri çağırmayı her üç yer tutucuya da eşler.

<div id="parameters"></div>

## Parametreler

### StyledText (Children Modu)

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `children` | `List<Text>` | zorunlu | Text widget'larının listesi |
| `style` | `TextStyle?` | null | Tüm alt öğeler için temel stil |
| `onEnter` | `Function(Text, PointerEnterEvent)?` | null | İşaretçi giriş geri çağırması |
| `onExit` | `Function(Text, PointerExitEvent)?` | null | İşaretçi çıkış geri çağırması |
| `spellOut` | `bool?` | null | Metni karakter karakter heceleme |
| `softWrap` | `bool` | `true` | Yumuşak kaydırmayı etkinleştir |
| `textAlign` | `TextAlign` | `TextAlign.start` | Metin hizalama |
| `textDirection` | `TextDirection?` | null | Metin yönü |
| `maxLines` | `int?` | null | Maksimum satır sayısı |
| `overflow` | `TextOverflow` | `TextOverflow.clip` | Taşma davranışı |
| `locale` | `Locale?` | null | Metin yerel ayarı |
| `strutStyle` | `StrutStyle?` | null | Strut stili |
| `textScaler` | `TextScaler?` | null | Metin ölçekleyici |
| `selectionColor` | `Color?` | null | Seçim vurgulama rengi |

### StyledText.template (Şablon Modu)

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `text` | `String` | zorunlu | `@{{yer tutucu}}` sözdizimli şablon metni |
| `styles` | `Map<String, TextStyle>?` | null | Yer tutucu adlarından stillere eşleme |
| `onTap` | `Map<String, VoidCallback>?` | null | Yer tutucu adlarından dokunma geri çağırmalarına eşleme |
| `style` | `TextStyle?` | null | Yer tutucu olmayan metin için temel stil |

Diğer tüm parametreler (`softWrap`, `textAlign`, `maxLines`, vb.) children constructor ile aynıdır.

<div id="text-extensions"></div>

## Text Extension'ları

{{ config('app.name') }}, Flutter'ın `Text` widget'ını tipografi ve yardımcı metotlarla genişletir.

<div id="typography-styles"></div>

### Tipografi Stilleri

Herhangi bir `Text` widget'ına Material Design tipografi stillerini uygulayın:

``` dart
Text("Hello").displayLarge()
Text("Hello").displayMedium()
Text("Hello").displaySmall()
Text("Hello").headingLarge()
Text("Hello").headingMedium()
Text("Hello").headingSmall()
Text("Hello").titleLarge()
Text("Hello").titleMedium()
Text("Hello").titleSmall()
Text("Hello").bodyLarge()
Text("Hello").bodyMedium()
Text("Hello").bodySmall()
Text("Hello").labelLarge()
Text("Hello").labelMedium()
Text("Hello").labelSmall()
```

Her biri isteğe bağlı geçersiz kılmalar kabul eder:

``` dart
Text("Welcome").headingLarge(
  color: Colors.blue,
  fontWeight: FontWeight.w700,
  letterSpacing: 1.2,
)
```

**Kullanılabilir geçersiz kılmalar** (tüm tipografi metotları için aynı):

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `color` | `Color?` | Metin rengi |
| `fontSize` | `double?` | Yazı boyutu |
| `fontWeight` | `FontWeight?` | Yazı kalınlığı |
| `fontStyle` | `FontStyle?` | İtalik/normal |
| `letterSpacing` | `double?` | Harf aralığı |
| `wordSpacing` | `double?` | Kelime aralığı |
| `height` | `double?` | Satır yüksekliği |
| `decoration` | `TextDecoration?` | Metin dekorasyonu |
| `decorationColor` | `Color?` | Dekorasyon rengi |
| `decorationStyle` | `TextDecorationStyle?` | Dekorasyon stili |
| `decorationThickness` | `double?` | Dekorasyon kalınlığı |
| `fontFamily` | `String?` | Yazı tipi ailesi |
| `shadows` | `List<Shadow>?` | Metin gölgeleri |
| `overflow` | `TextOverflow?` | Taşma davranışı |

<div id="utility-methods"></div>

### Yardımcı Metotlar

``` dart
// Font weight
Text("Bold text").fontWeightBold()
Text("Light text").fontWeightLight()

// Alignment
Text("Left aligned").alignLeft()
Text("Center aligned").alignCenter()
Text("Right aligned").alignRight()

// Max lines
Text("Long text...").setMaxLines(2)

// Font family
Text("Custom font").setFontFamily("Roboto")

// Font size
Text("Big text").setFontSize(24)

// Custom style
Text("Styled").setStyle(TextStyle(color: Colors.red))

// Padding
Text("Padded").paddingOnly(left: 8, top: 4, right: 8, bottom: 4)

// Copy with modifications
Text("Original").copyWith(
  textAlign: TextAlign.center,
  maxLines: 2,
  style: TextStyle(fontSize: 18),
)
```

<div id="examples"></div>

## Örnekler

### Şartlar ve Koşullar Bağlantısı

``` dart
StyledText.template(
  "By signing up, you agree to our @{{Terms}} and @{{Privacy Policy}}.",
  style: TextStyle(color: Colors.grey.shade600, fontSize: 14),
  styles: {
    "Terms|Privacy Policy": TextStyle(
      color: Colors.blue,
      decoration: TextDecoration.underline,
    ),
  },
  onTap: {
    "Terms": () => routeTo("/terms"),
    "Privacy Policy": () => routeTo("/privacy"),
  },
)
```

### Sürüm Görüntüleme

``` dart
StyledText.template(
  "Framework Version: @{{$nyloVersion}}",
  style: textTheme.bodyMedium!.copyWith(
    color: NyColor(
      light: Colors.grey.shade800,
      dark: Colors.grey.shade200,
    ).toColor(context),
  ),
  styles: {
    nyloVersion: TextStyle(fontWeight: FontWeight.bold),
  },
)
```

### Karma Stil Paragraf

``` dart
StyledText(
  style: TextStyle(fontSize: 16, height: 1.5),
  children: [
    Text("Flutter "),
    Text("makes it easy", style: TextStyle(fontWeight: FontWeight.bold)),
    Text(" to build beautiful apps. With "),
    Text("Nylo", style: TextStyle(color: Colors.blue, fontWeight: FontWeight.bold)),
    Text(", it's even faster."),
  ],
)
```

### Tipografi Zinciri

``` dart
Text("Welcome Back")
    .headingLarge(color: Colors.black)
    .alignCenter()
```
