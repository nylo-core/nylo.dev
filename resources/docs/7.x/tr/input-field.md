# InputField

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [Temel Kullanım](#basic-usage "Temel Kullanım")
- [Doğrulama](#validation "Doğrulama")
- Varyantlar
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Giriş Maskeleme](#masking "Giriş Maskeleme")
- [Başlık ve Alt Bilgi](#header-footer "Başlık ve Alt Bilgi")
- [Temizlenebilir Giriş](#clearable "Temizlenebilir Giriş")
- [Durum Yönetimi](#state-management "Durum Yönetimi")
- [Parametreler](#parameters "Parametreler")


<div id="introduction"></div>

## Giriş

**InputField** widget'ı, {{ config('app.name') }}'nun aşağıdaki özelliklere yerleşik destekli geliştirilmiş metin alanıdır:

- Özelleştirilebilir hata mesajlarıyla doğrulama
- Şifre görünürlük geçişi
- Giriş maskeleme (telefon numaraları, kredi kartları vb.)
- Başlık ve alt bilgi widget'ları
- Temizlenebilir giriş
- Durum yönetimi entegrasyonu
- Geliştirme için sahte veriler

<div id="basic-usage"></div>

## Temel Kullanım

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Doğrulama

Doğrulama kuralları eklemek için `formValidator` parametresini kullanın:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Kullanıcı odağı alandan çektiğinde alan doğrulanacaktır.

### Özel Doğrulama İşleyicisi

Doğrulama hatalarını programatik olarak yönetin:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

Tüm kullanılabilir doğrulama kurallarını [Doğrulama](/docs/7.x/validation) dokümantasyonunda bulabilirsiniz.

<div id="password"></div>

## InputField.password

Gizli metin ve görünürlük geçişi ile önceden yapılandırılmış şifre alanı:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Şifre Görünürlüğünü Özelleştirme

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

E-posta klavyesi ve otomatik odaklanma ile önceden yapılandırılmış e-posta alanı:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Her kelimenin ilk harfini otomatik olarak büyük yapar:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Giriş Maskeleme

Telefon numaraları veya kredi kartları gibi biçimlendirilmiş veriler için giriş maskeleri uygulayın:

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parametre | Açıklama |
|-----------|-------------|
| `mask` | `#` yer tutucu olarak kullanılan maske deseni |
| `maskMatch` | Geçerli giriş karakterleri için regex deseni |
| `maskedReturnValue` | Doğruysa biçimlendirilmiş değeri döndürür; yanlışsa ham girişi döndürür |

<div id="header-footer"></div>

## Başlık ve Alt Bilgi

Giriş alanının üstüne veya altına widget'lar ekleyin:

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Temizlenebilir Giriş

Alanı hızlıca boşaltmak için bir temizleme düğmesi ekleyin:

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Durum Yönetimi

Programatik olarak kontrol etmek için giriş alanınıza bir durum adı verin:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Durum Eylemleri

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parametreler

### Ortak Parametreler

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | zorunlu | Düzenlenen metni kontrol eder |
| `labelText` | `String?` | - | Alanın üstünde görüntülenen etiket |
| `hintText` | `String?` | - | Yer tutucu metin |
| `formValidator` | `FormValidator?` | - | Doğrulama kuralları |
| `validateOnFocusChange` | `bool` | `true` | Odak değiştiğinde doğrula |
| `obscureText` | `bool` | `false` | Girişi gizle (şifreler için) |
| `keyboardType` | `TextInputType` | `text` | Klavye türü |
| `autoFocus` | `bool` | `false` | Oluşturulduğunda otomatik odaklan |
| `readOnly` | `bool` | `false` | Alanı salt okunur yap |
| `enabled` | `bool?` | - | Alanı etkinleştir/devre dışı bırak |
| `maxLines` | `int?` | `1` | Maksimum satır sayısı |
| `maxLength` | `int?` | - | Maksimum karakter sayısı |

### Stil Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | Alan arka plan rengi |
| `borderRadius` | `BorderRadius?` | Kenarlık yarıçapı |
| `border` | `InputBorder?` | Varsayılan kenarlık |
| `focusedBorder` | `InputBorder?` | Odaklandığında kenarlık |
| `enabledBorder` | `InputBorder?` | Etkinleştirildiğinde kenarlık |
| `contentPadding` | `EdgeInsetsGeometry?` | İç dolgu |
| `style` | `TextStyle?` | Metin stili |
| `labelStyle` | `TextStyle?` | Etiket metin stili |
| `hintStyle` | `TextStyle?` | İpucu metin stili |
| `prefixIcon` | `Widget?` | Girişten önceki simge |

### Maskeleme Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `mask` | `String?` | Maske deseni (örn. "###-####") |
| `maskMatch` | `String?` | Geçerli karakterler için regex |
| `maskedReturnValue` | `bool?` | Maskeli veya ham değer döndür |

### Özellik Parametreleri

| Parametre | Tür | Açıklama |
|-----------|------|-------------|
| `header` | `Widget?` | Alanın üstündeki widget |
| `footer` | `Widget?` | Alanın altındaki widget |
| `clearable` | `bool?` | Temizleme düğmesini göster |
| `clearIcon` | `Widget?` | Özel temizleme simgesi |
| `passwordVisible` | `bool?` | Şifre geçiş düğmesini göster |
| `passwordViewable` | `bool?` | Şifre görünürlük geçişine izin ver |
| `dummyData` | `String?` | Geliştirme için sahte veri |
| `stateName` | `String?` | Durum yönetimi için ad |
| `onChanged` | `Function(String)?` | Değer değiştiğinde çağrılır |
