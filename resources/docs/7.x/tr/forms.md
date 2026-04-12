# Formlar

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- Başlarken
  - [Form Oluşturma](#creating-forms "Form Oluşturma")
  - [Form Görüntüleme](#displaying-a-form "Form Görüntüleme")
  - [Form Gönderme](#submitting-a-form "Form Gönderme")
- Alan Türleri
  - [Metin Alanları](#text-fields "Metin Alanları")
  - [Sayı Alanları](#number-fields "Sayı Alanları")
  - [Şifre Alanları](#password-fields "Şifre Alanları")
  - [E-posta Alanları](#email-fields "E-posta Alanları")
  - [URL Alanları](#url-fields "URL Alanları")
  - [Metin Alanı (Çok Satırlı)](#text-area-fields "Metin Alanı (Çok Satırlı)")
  - [Telefon Numarası Alanları](#phone-number-fields "Telefon Numarası Alanları")
  - [Kelimeleri Büyük Harfle Başlatma](#capitalize-words-fields "Kelimeleri Büyük Harfle Başlatma")
  - [Cümleleri Büyük Harfle Başlatma](#capitalize-sentences-fields "Cümleleri Büyük Harfle Başlatma")
  - [Tarih Alanları](#date-fields "Tarih Alanları")
  - [TarihSaat Alanları](#datetime-fields "TarihSaat Alanları")
  - [Maskeli Giriş Alanları](#masked-input-fields "Maskeli Giriş Alanları")
  - [Para Birimi Alanları](#currency-fields "Para Birimi Alanları")
  - [Onay Kutusu Alanları](#checkbox-fields "Onay Kutusu Alanları")
  - [Anahtar Kutusu Alanları](#switch-box-fields "Anahtar Kutusu Alanları")
  - [Seçici Alanları](#picker-fields "Seçici Alanları")
  - [Radyo Alanları](#radio-fields "Radyo Alanları")
  - [Çip Alanları](#chip-fields "Çip Alanları")
  - [Kaydırıcı Alanları](#slider-fields "Kaydırıcı Alanları")
  - [Aralık Kaydırıcı Alanları](#range-slider-fields "Aralık Kaydırıcı Alanları")
  - [Özel Alanlar](#custom-fields "Özel Alanlar")
  - [Builder Alanları](#builder-fields "Builder Alanları")
  - [Widget Alanları](#widget-fields "Widget Alanları")
- [FormCollection](#form-collection "FormCollection")
- [Form Doğrulama](#form-validation "Form Doğrulama")
- [Form Verilerini Yönetme](#managing-form-data "Form Verilerini Yönetme")
  - [Başlangıç Verileri](#initial-data "Başlangıç Verileri")
  - [Alan Değerlerini Ayarlama](#setting-field-values "Alan Değerlerini Ayarlama")
  - [Alan Seçeneklerini Ayarlama](#setting-field-options "Alan Seçeneklerini Ayarlama")
  - [Form Verilerini Okuma](#reading-form-data "Form Verilerini Okuma")
  - [Verileri Temizleme](#clearing-data "Verileri Temizleme")
  - [Alanları Güncelleme](#finding-and-updating-fields "Alanları Güncelleme")
- [Gönder Düğmesi](#submit-button "Gönder Düğmesi")
- [Form Düzeni](#form-layout "Form Düzeni")
- [Alan Görünürlüğü](#field-visibility "Alan Görünürlüğü")
- [Alan Stilleri](#field-styling "Alan Stilleri")
- [NyFormWidget Statik Metotları](#ny-form-widget-static-methods "NyFormWidget Statik Metotları")
- [NyFormWidget Kurucu Referansı](#ny-form-widget-constructor-reference "NyFormWidget Kurucu Referansı")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Tüm Alan Türleri Referansı](#all-field-types-reference "Tüm Alan Türleri Referansı")

<div id="introduction"></div>

## Giriş

{{ config('app.name') }} v7, `NyFormWidget` etrafında oluşturulmuş bir form sistemi sunar. Form sınıfınız `NyFormWidget` sınıfını genişletir ve **kendisi** widget'tır — ayrı bir wrapper gerekmez. Formlar yerleşik doğrulama, birçok alan türü, stilleme ve veri yönetimini destekler.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Formu tanımla
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Görüntüle ve gönder
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Form Oluşturma

Yeni bir form oluşturmak için Metro CLI kullanın:

``` bash
metro make:form LoginForm
```

Bu, `lib/app/forms/login_form.dart` dosyasını oluşturur:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Formlar `NyFormWidget` sınıfını genişletir ve form alanlarını tanımlamak için `fields()` metodunu geçersiz kılar. Her alan `Field.text()`, `Field.email()` veya `Field.password()` gibi adlandırılmış bir kurucu kullanır. `static NyFormActions get actions` getter'ı, uygulamanızın herhangi bir yerinden formla etkileşim kurmak için uygun bir yol sağlar.


<div id="displaying-a-form"></div>

## Form Görüntüleme

Form sınıfınız `NyFormWidget` sınıfını genişlettiği için, **kendisi** widget'tır. Doğrudan widget ağacınızda kullanın:

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Form Gönderme

Bir formu göndermenin üç yolu vardır:

### onSubmit ve submitButton kullanma

Formu oluştururken `onSubmit` ve bir `submitButton` geçirin. {{ config('app.name') }}, gönder düğmesi olarak çalışan önceden oluşturulmuş düğmeler sağlar:

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Mevcut düğme stilleri: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### NyFormActions kullanma

Herhangi bir yerden göndermek için `actions` getter'ını kullanın:

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### NyFormWidget.submit() statik metodu kullanma

Bir formu adıyla herhangi bir yerden gönderin:

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

Gönderildiğinde, form tüm alanları doğrular. Geçerliyse, `onSuccess` alan verilerinin `Map<String, dynamic>` değeriyle çağrılır (anahtarlar alan adlarının snake_case versiyonlarıdır). Geçersizse, varsayılan olarak bir toast hatası gösterilir ve sağlanmışsa `onFailure` çağrılır.


<div id="field-types"></div>

## Alan Türleri

{{ config('app.name') }} v7, `Field` sınıfında adlandırılmış kurucular aracılığıyla 22 alan türü sağlar. Tüm alan kurucuları şu ortak parametreleri paylaşır:

| Parametre | Tür | Varsayılan | Açıklama |
|-----------|------|---------|-------------|
| `key` | `String` | Zorunlu | Alan tanımlayıcısı (konumsal) |
| `label` | `String?` | `null` | Özel görüntüleme etiketi (varsayılan olarak anahtar başlık büyüklüğünde) |
| `value` | `dynamic` | `null` | Başlangıç değeri |
| `validator` | `FormValidator?` | `null` | Doğrulama kuralları |
| `autofocus` | `bool` | `false` | Yüklemede otomatik odaklanma |
| `dummyData` | `String?` | `null` | Test/geliştirme verileri |
| `header` | `Widget?` | `null` | Alanın üstünde görüntülenen widget |
| `footer` | `Widget?` | `null` | Alanın altında görüntülenen widget |
| `titleStyle` | `TextStyle?` | `null` | Özel etiket metin stili |
| `hidden` | `bool` | `false` | Alanı gizle |
| `readOnly` | `bool?` | `null` | Alanı salt okunur yap |
| `style` | `FieldStyle?` | Değişir | Alana özgü stil yapılandırması |
| `onChanged` | `Function(dynamic)?` | `null` | Değer değişikliği callback'i |

<div id="text-fields"></div>

### Metin Alanları

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Stil türü: `FieldStyleTextField`

<div id="number-fields"></div>

### Sayı Alanları

``` dart
Field.number("Age")

// Ondalık sayılar
Field.number("Score", decimal: true)
```

`decimal` parametresi ondalık giriş izin verilip verilmeyeceğini kontrol eder. Stil türü: `FieldStyleTextField`

<div id="password-fields"></div>

### Şifre Alanları

``` dart
Field.password("Password")

// Görünürlük geçiş düğmesiyle
Field.password("Password", viewable: true)
```

`viewable` parametresi bir göster/gizle geçiş düğmesi ekler. Stil türü: `FieldStyleTextField`

<div id="email-fields"></div>

### E-posta Alanları

``` dart
Field.email("Email", validator: FormValidator.email())
```

Otomatik olarak e-posta klavye türünü ayarlar ve boşlukları filtreler. Stil türü: `FieldStyleTextField`

<div id="url-fields"></div>

### URL Alanları

``` dart
Field.url("Website", validator: FormValidator.url())
```

URL klavye türünü ayarlar. Stil türü: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Metin Alanı (Çok Satırlı)

``` dart
Field.textArea("Description")
```

Çok satırlı metin girişi. Stil türü: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Telefon Numarası Alanları

``` dart
Field.phoneNumber("Mobile Phone")
```

Telefon numarası girişini otomatik olarak biçimlendirir. Stil türü: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Kelimeleri Büyük Harfle Başlatma

``` dart
Field.capitalizeWords("Full Name")
```

Her kelimenin ilk harfini büyük yapar. Stil türü: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Cümleleri Büyük Harfle Başlatma

``` dart
Field.capitalizeSentences("Bio")
```

Her cümlenin ilk harfini büyük yapar. Stil türü: `FieldStyleTextField`

<div id="date-fields"></div>

### Tarih Alanları

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Temizleme düğmesini devre dışı bırak
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Özel temizleme simgesi
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Bir tarih seçici açar. Varsayılan olarak, alan kullanıcıların değeri sıfırlamasına izin veren bir temizleme düğmesi gösterir. Gizlemek için `canClear: false` ayarlayın veya simgeyi değiştirmek için `clearIconData` kullanın. Stil türü: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### TarihSaat Alanları

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Bir tarih ve saat seçici açar. `firstDate`, `lastDate`, `dateFormat` ve `initialPickerDateTime` değerlerini doğrudan üst düzey parametreler olarak ayarlayabilirsiniz. Stil türü: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Maskeli Giriş Alanları

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Biçimlendirilmiş değeri döndürür
)
```

Maskedeki `#` karakteri kullanıcı girişiyle değiştirilir. İzin verilen karakterleri kontrol etmek için `match` kullanın. `maskReturnValue` `true` olduğunda, döndürülen değer maske biçimlendirmesini içerir.

<div id="currency-fields"></div>

### Para Birimi Alanları

``` dart
Field.currency("Price", currency: "usd")
```

`currency` parametresi zorunludur ve para birimi biçimini belirler. Stil türü: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Onay Kutusu Alanları

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Stil türü: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Anahtar Kutusu Alanları

``` dart
Field.switchBox("Enable Notifications")
```

Stil türü: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Seçici Alanları

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// Anahtar-değer çiftleriyle
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

`options` parametresi bir `FormCollection` gerektirir (ham liste değil). Ayrıntılar için [FormCollection](#form-collection) bölümüne bakın. Stil türü: `FieldStylePicker`

#### Liste Kutucuğu Stilleri

`PickerListTileStyle` kullanarak picker'ın alt sayfasında ögelerin görünümünü özelleştirebilirsiniz. Varsayılan olarak, alt sayfa düz metin kutucukları gösterir. Seçim göstergelerini eklemek için yerleşik şablonları kullanın veya tamamen özel bir builder sağlayın.

**Radyo stili** — başta widget olarak bir radyo düğmesi simgesi gösterir:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// Özel aktif renkle
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Onay işareti stili** — seçildiğinde sonda widget olarak bir onay simgesi gösterir:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Özel builder** — her kutucuğun widget'ı üzerinde tam kontrol:

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

Her iki ön ayarlı stil ayrıca `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` ve `selectedTileColor` destekler:

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### Radyo Alanları

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` parametresi bir `FormCollection` gerektirir. Stil türü: `FieldStyleRadio`

<div id="chip-fields"></div>

### Çip Alanları

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// Anahtar-değer çiftleriyle
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Çip widget'ları aracılığıyla çoklu seçim yapılmasını sağlar. `options` parametresi bir `FormCollection` gerektirir. Stil türü: `FieldStyleChip`

<div id="slider-fields"></div>

### Kaydırıcı Alanları

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Stil türü: `FieldStyleSlider` — `min`, `max`, `divisions`, renkler, değer gösterimi ve daha fazlasını yapılandırın.

<div id="range-slider-fields"></div>

### Aralık Kaydırıcı Alanları

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Bir `RangeValues` nesnesi döndürür. Stil türü: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Özel Alanlar

Kendi durum bilgili widget'ınızı sağlamak için `Field.custom()` kullanın:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` parametresi `NyFieldStatefulWidget` sınıfını genişleten bir widget gerektirir. Bu, alanın render edilmesi ve davranışı üzerinde tam kontrol sağlar.

<div id="builder-fields"></div>

### Builder Alanları

<!-- uncertain: new Nylo-specific term "Field.builder()" with 4-argument signature including setState — not seen in existing locale file -->
`NyFieldStatefulWidget` alt sınıfı oluşturmadan özel bir form alanı inline oluşturmak için `Field.builder()` kullanın. Builder işlevi, mevcut değeri, forma değer değişikliklerini bildiren bir `onChanged` callback'ini ve UI'yı yeniden oluşturmayı tetikleyen bir `setState` callback'ini alır.

``` dart
Field.builder(
  "Favorite Color",
  builder: (context, onChanged, value, setState) {
    return ColorPicker(
      selected: value,
      onColorChanged: (color) {
        onChanged(color);
        setState(); // alan widget'ını yeniden oluştur
      },
    );
  },
  value: Colors.blue,
)
```

Üçüncü parametre mevcut alan değeri, dördüncüsü ise `setState`'tir. Builder'ınız `setState`'e ihtiyaç duymuyorsa, hâlâ desteklenen eski 3-argümanlı imzayı (`NyFieldBuilderLegacy`) kullanabilirsiniz:

``` dart
Field.builder(
  "Rating",
  builder: (context, onChanged, value) {
    return StarRatingWidget(
      rating: value ?? 0,
      onRatingChanged: onChanged,
    );
  },
)
```

<div id="widget-fields"></div>

### Widget Alanları

Form alanı olmadan form içine herhangi bir widget yerleştirmek için `Field.widget()` kullanın:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Widget alanları doğrulama veya veri toplamaya katılmaz. Yalnızca düzen amaçlıdır.


<div id="form-collection"></div>

## FormCollection

Seçici, radyo ve çip alanları, seçenekleri için bir `FormCollection` gerektirir. `FormCollection`, farklı seçenek biçimlerini işlemek için birleşik bir arayüz sağlar.

### FormCollection Oluşturma

``` dart
// Boş koleksiyon (seçenekler yüklenmeden önce yer tutucu olarak kullanışlı)
const FormCollection.empty()

// Dizelerden oluşan listeden (değer ve etiket aynıdır)
FormCollection.from(["Red", "Green", "Blue"])

// Yukarıdakiyle aynı, açık biçimde
FormCollection.fromArray(["Red", "Green", "Blue"])

// Haritadan (anahtar = değer, değer = etiket)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// Yapılandırılmış verilerden (API yanıtları için kullanışlı)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()`, veri biçimini otomatik olarak algılar ve uygun kurucuya yönlendirir.

### FormOption

Bir `FormCollection` içindeki her seçenek, `value` ve `label` özelliklerine sahip bir `FormOption`'dır:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Seçenekleri Sorgulama

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Form Doğrulama

`FormValidator` ile `validator` parametresini kullanarak herhangi bir alana doğrulama ekleyin:

``` dart
// Adlandırılmış kurucu
Field.email("Email", validator: FormValidator.email())

// Zincirlenmiş kurallar
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Güç seviyeli şifre
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean doğrulama
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Özel inline doğrulama
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)

// Null kabul eder — alan boş olduğunda doğrulama geçer
Field.text("Nickname",
  validator: FormValidator().minLength(3).nullable(),
)
```

`nullable()`, bir doğrulayıcıyı isteğe bağlı olarak işaretler. Alan değeri null veya boş olduğunda, tüm doğrulama kuralları atlanır ve alan geçer. Alan bir değere sahip olduğunda, tüm kurallar normal şekilde uygulanır. Herhangi bir `FormValidator`'ın sonuna ekleyin.

Bir form gönderildiğinde, tüm doğrulayıcılar kontrol edilir. Herhangi biri başarısız olursa, ilk hata mesajını gösteren bir toast hatası görüntülenir ve `onFailure` callback'i çağrılır.

**Ayrıca bakın:** Kullanılabilir doğrulayıcıların tam listesi için [Doğrulama](/docs/7.x/validation#validation-rules) sayfasına bakın.


<div id="managing-form-data"></div>

## Form Verilerini Yönetme

<div id="initial-data"></div>

### Başlangıç Verileri

Bir forma başlangıç verileri ayarlamanın iki yolu vardır.

**Seçenek 1: Form sınıfınızda `init` getter'ını geçersiz kılın**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

`init` getter'ı senkron bir `Map` veya asenkron bir `Future<Map>` döndürebilir. Anahtarlar, snake_case normalizasyonu kullanılarak alan adlarıyla eşleştirilir, bu nedenle `"First Name"` anahtarı `"First Name"` adlı bir alanla eşleşir.

#### init içinde `define()` kullanımı

`init` içerisinde bir alan için **seçenekler** (veya hem bir değer hem de seçenekler) ayarlamanız gerektiğinde `define()` yardımcısını kullanın. Bu, seçeneklerin bir API veya başka bir asenkron kaynaktan geldiği picker, chip ve radio alanları için kullanışlıdır.

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` iki adlandırılmış parametre kabul eder:

| Parametre | Açıklama |
|-----------|-------------|
| `value` | Alan için başlangıç değeri |
| `options` | Picker, chip veya radio alanları için seçenekler |

``` dart
// Yalnızca seçenekleri ayarla (başlangıç değeri yok)
"Category": define(options: categories),

// Yalnızca başlangıç değerini ayarla
"Price": define(value: "100"),

// Hem değer hem de seçenekleri ayarla
"Country": define(value: "us", options: countries),

// Düz değerler hâlâ basit alanlar için çalışır
"Name": "John",
```

`define()`'a gönderilen seçenekler bir `List`, `Map` veya `FormCollection` olabilir. Uygulandığında otomatik olarak bir `FormCollection`'a dönüştürülür.

**Seçenek 2: Form widget'ına `initialData` geçirin**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Alan Değerlerini Ayarlama

Herhangi bir yerden alan değerlerini ayarlamak için `NyFormActions` kullanın:

``` dart
// Tek bir alan değerini ayarla
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Alan Seçeneklerini Ayarlama

Seçici, çip veya radyo alanlarındaki seçenekleri dinamik olarak güncelleyin:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Form Verilerini Okuma

Form verileri, form gönderildiğinde `onSubmit` callback'i aracılığıyla veya gerçek zamanlı güncellemeler için `onChanged` callback'i aracılığıyla erişilir:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data bir Map<String, dynamic>'tir
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Verileri Temizleme

``` dart
// Tüm alanları temizle
EditAccountForm.actions.clear();

// Belirli bir alanı temizle
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Alanları Güncelleme

``` dart
// Alan değerini güncelle
EditAccountForm.actions.updateField("First Name", "Jane");

// Form UI'ını yenile
EditAccountForm.actions.refresh();

// Form alanlarını yenile (fields() yeniden çağırır)
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Gönder Düğmesi

Formu oluştururken bir `submitButton` ve `onSubmit` callback'i geçirin:

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

`submitButton` otomatik olarak form alanlarının altında görüntülenir. Yerleşik düğme stillerinden herhangi birini veya özel bir widget kullanabilirsiniz.

Herhangi bir widget'ı `footer` olarak geçirerek gönder düğmesi olarak da kullanabilirsiniz:

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Form Düzeni

Alanları yan yana yerleştirmek için bir `List` içine sarın:

``` dart
@override
fields() => [
  // Tek alan (tam genişlik)
  Field.text("Title"),

  // Bir satırda iki alan
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Başka bir tek alan
  Field.textArea("Bio"),

  // Bir satırda kaydırıcı ve aralık kaydırıcı
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Alan olmayan bir widget yerleştir
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Bir `List` içindeki alanlar, eşit `Expanded` genişliklere sahip bir `Row` içinde render edilir. Alanlar arasındaki boşluk, `NyFormWidget` üzerindeki `crossAxisSpacing` parametresiyle kontrol edilir.


<div id="field-visibility"></div>

## Alan Görünürlüğü

`Field` üzerindeki `hide()` ve `show()` metotlarını kullanarak alanları programatik olarak gösterin veya gizleyin. Alanlara form sınıfınızın içinden veya `onChanged` callback'i aracılığıyla erişebilirsiniz:

``` dart
// NyFormWidget alt sınıfınızın veya onChanged callback'inin içinde
Field nameField = ...;

// Alanı gizle
nameField.hide();

// Alanı göster
nameField.show();
```

Gizli alanlar arayüzde render edilmez ancak formun alan listesinde var olmaya devam eder.


<div id="field-styling"></div>

## Alan Stilleri

Her alan türünün stilleme için karşılık gelen bir `FieldStyle` alt sınıfı vardır:

| Alan Türü | Stil Sınıfı |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Herhangi bir alanın `style` parametresine bir stil nesnesi geçirin:

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## NyFormWidget Statik Metotları

`NyFormWidget`, uygulamanızın herhangi bir yerinden formlarla ada göre etkileşim kurmak için statik metotlar sağlar:

| Metot | Açıklama |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Bir formu adıyla gönder |
| `NyFormWidget.stateRefresh(name)` | Formun arayüz durumunu yenile |
| `NyFormWidget.stateSetValue(name, key, value)` | Form adıyla alan değerini ayarla |
| `NyFormWidget.stateSetOptions(name, key, options)` | Form adıyla alan seçeneklerini ayarla |
| `NyFormWidget.stateClearData(name)` | Form adıyla tüm alanları temizle |
| `NyFormWidget.stateRefreshForm(name)` | Form alanlarını yenile (`fields()` yeniden çağırır) |

``` dart
// "LoginForm" adlı formu herhangi bir yerden gönder
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Alan değerini uzaktan güncelle
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Tüm form verilerini temizle
NyFormWidget.stateClearData("LoginForm");
```

> **İpucu:** Bu statik metotları doğrudan çağırmak yerine `NyFormActions` (aşağıya bakın) kullanmayı tercih edin — daha kısa ve daha az hataya açıktır.


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget Kurucu Referansı

`NyFormWidget` sınıfını genişletirken, geçebileceğiniz kurucu parametreleri şunlardır:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Satırdaki alanlar arasındaki yatay boşluk
  double mainAxisSpacing = 10,   // Alanlar arasındaki dikey boşluk
  Map<String, dynamic>? initialData, // Başlangıç alan değerleri
  Function(Field field, dynamic value)? onChanged, // Alan değişikliği callback'i
  Widget? header,                // Formun üstündeki widget
  Widget? submitButton,          // Gönder düğmesi widget'ı
  Widget? footer,                // Formun altındaki widget
  double headerSpacing = 10,     // Başlıktan sonraki boşluk
  double submitButtonSpacing = 10, // Gönder düğmesinden sonraki boşluk
  double footerSpacing = 10,     // Altbilgiden önceki boşluk
  LoadingStyle? loadingStyle,    // Yükleme göstergesi stili
  bool locked = false,           // Formu salt okunur yapar
  Function(dynamic data)? onSubmit,   // Başarılı doğrulamada form verileriyle çağrılır
  Function(dynamic error)? onFailure, // Başarısız doğrulamada hatalarla çağrılır
)
```

`onChanged` callback'i, değişen `Field` ve yeni değerini alır:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions`, uygulamanızın herhangi bir yerinden bir formla etkileşim kurmak için uygun bir yol sağlar. Form sınıfınızda statik getter olarak tanımlayın:

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Mevcut Aksiyonlar

| Metot | Açıklama |
|--------|-------------|
| `actions.updateField(key, value)` | Bir alanın değerini ayarla |
| `actions.clearField(key)` | Belirli bir alanı temizle |
| `actions.clear()` | Tüm alanları temizle |
| `actions.refresh()` | Formun arayüz durumunu yenile |
| `actions.refreshForm()` | `fields()` yeniden çağır ve yeniden oluştur |
| `actions.setOptions(key, options)` | Seçici/çip/radyo alanlarında seçenekleri ayarla |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Doğrulama ile gönder |

``` dart
// Alan değerini güncelle
LoginForm.actions.updateField("Email", "new@email.com");

// Tüm form verilerini temizle
LoginForm.actions.clear();

// Formu gönder
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget Geçersiz Kılmaları

`NyFormWidget` alt sınıfınızdaki geçersiz kılabileceğiniz metotlar:

| Geçersiz Kılma | Açıklama |
|----------|-------------|
| `fields()` | Form alanlarını tanımlayın (zorunlu) |
| `init` | Başlangıç verileri sağlayın (senkron veya asenkron) |
| `onChange(field, data)` | Alan değişikliklerini dahili olarak yönetin |


<div id="all-field-types-reference"></div>

## Tüm Alan Türleri Referansı

| Kurucu | Anahtar Parametreler | Açıklama |
|-------------|----------------|-------------|
| `Field.text()` | — | Standart metin girişi |
| `Field.email()` | — | Klavye türlü e-posta girişi |
| `Field.password()` | `viewable` | İsteğe bağlı görünürlük geçişli şifre |
| `Field.number()` | `decimal` | Sayısal giriş, isteğe bağlı ondalık |
| `Field.currency()` | `currency` (zorunlu) | Para birimi biçimli giriş |
| `Field.capitalizeWords()` | — | Başlık büyüklüğünde metin girişi |
| `Field.capitalizeSentences()` | — | Cümle büyüklüğünde metin girişi |
| `Field.textArea()` | — | Çok satırlı metin girişi |
| `Field.phoneNumber()` | — | Otomatik biçimli telefon numarası |
| `Field.url()` | — | Klavye türlü URL girişi |
| `Field.mask()` | `mask` (zorunlu), `match`, `maskReturnValue` | Maskeli metin girişi |
| `Field.date()` | — | Tarih seçici |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Tarih ve saat seçici |
| `Field.checkbox()` | — | Boolean onay kutusu |
| `Field.switchBox()` | — | Boolean geçiş anahtarı |
| `Field.picker()` | `options` (zorunlu `FormCollection`) | Listeden tekli seçim |
| `Field.radio()` | `options` (zorunlu `FormCollection`) | Radyo düğme grubu |
| `Field.chips()` | `options` (zorunlu `FormCollection`) | Çoklu seçim çipleri |
| `Field.slider()` | — | Tekli değer kaydırıcısı |
| `Field.rangeSlider()` | — | Aralık değer kaydırıcısı |
| `Field.custom()` | `child` (zorunlu `NyFieldStatefulWidget`) | Özel durum bilgili widget |
| `Field.builder()` | `builder` (zorunlu `NyFieldBuilder` veya `NyFieldBuilderLegacy`) | Alt sınıf oluşturmadan özel alan inline |
| `Field.widget()` | `child` (zorunlu `Widget`) | Herhangi bir widget yerleştir (alan dışı) |
