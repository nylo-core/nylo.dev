# Formlar

---

<a name="section-1"></a>
- [Giris](#introduction "Giris")
- Baslangic
  - [Form Olusturma](#creating-forms "Form Olusturma")
  - [Form Goruntuleme](#displaying-a-form "Form Goruntuleme")
  - [Form Gonderme](#submitting-a-form "Form Gonderme")
- Alan Turleri
  - [Metin Alanlari](#text-fields "Metin Alanlari")
  - [Sayi Alanlari](#number-fields "Sayi Alanlari")
  - [Sifre Alanlari](#password-fields "Sifre Alanlari")
  - [E-posta Alanlari](#email-fields "E-posta Alanlari")
  - [URL Alanlari](#url-fields "URL Alanlari")
  - [Metin Alani (Cok Satirli)](#text-area-fields "Metin Alani (Cok Satirli)")
  - [Telefon Numarasi Alanlari](#phone-number-fields "Telefon Numarasi Alanlari")
  - [Kelimeleri Buyuk Harfle Baslatma](#capitalize-words-fields "Kelimeleri Buyuk Harfle Baslatma")
  - [Cumleleri Buyuk Harfle Baslatma](#capitalize-sentences-fields "Cumleleri Buyuk Harfle Baslatma")
  - [Tarih Alanlari](#date-fields "Tarih Alanlari")
  - [TarihSaat Alanlari](#datetime-fields "TarihSaat Alanlari")
  - [Maskeli Giris Alanlari](#masked-input-fields "Maskeli Giris Alanlari")
  - [Para Birimi Alanlari](#currency-fields "Para Birimi Alanlari")
  - [Onay Kutusu Alanlari](#checkbox-fields "Onay Kutusu Alanlari")
  - [Anahtar Kutusu Alanlari](#switch-box-fields "Anahtar Kutusu Alanlari")
  - [Secici Alanlari](#picker-fields "Secici Alanlari")
  - [Radyo Alanlari](#radio-fields "Radyo Alanlari")
  - [Cip Alanlari](#chip-fields "Cip Alanlari")
  - [Kaydirici Alanlari](#slider-fields "Kaydirici Alanlari")
  - [Aralik Kaydirici Alanlari](#range-slider-fields "Aralik Kaydirici Alanlari")
  - [Ozel Alanlar](#custom-fields "Ozel Alanlar")
  - [Widget Alanlari](#widget-fields "Widget Alanlari")
- [FormCollection](#form-collection "FormCollection")
- [Form Dogrulama](#form-validation "Form Dogrulama")
- [Form Verilerini Yonetme](#managing-form-data "Form Verilerini Yonetme")
  - [Baslangic Verileri](#initial-data "Baslangic Verileri")
  - [Alan Degerlerini Ayarlama](#setting-field-values "Alan Degerlerini Ayarlama")
  - [Alan Seceneklerini Ayarlama](#setting-field-options "Alan Seceneklerini Ayarlama")
  - [Form Verilerini Okuma](#reading-form-data "Form Verilerini Okuma")
  - [Verileri Temizleme](#clearing-data "Verileri Temizleme")
  - [Alanlari Guncelleme](#finding-and-updating-fields "Alanlari Guncelleme")
- [Gonder Dugmesi](#submit-button "Gonder Dugmesi")
- [Form Duzeni](#form-layout "Form Duzeni")
- [Alan Gorunurlugu](#field-visibility "Alan Gorunurlugu")
- [Alan Stilleri](#field-styling "Alan Stilleri")
- [NyFormWidget Statik Metotlari](#ny-form-widget-static-methods "NyFormWidget Statik Metotlari")
- [NyFormWidget Kurucu Referansi](#ny-form-widget-constructor-reference "NyFormWidget Kurucu Referansi")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Tum Alan Turleri Referansi](#all-field-types-reference "Tum Alan Turleri Referansi")

<div id="introduction"></div>

## Giris

{{ config('app.name') }} v7, `NyFormWidget` etrafinda olusturulmus bir form sistemi sunar. Form sinfiniz `NyFormWidget` sinifini genisletir ve **kendisi** widget'tir -- ayri bir wrapper gerekmez. Formlar yerlesik dogrulama, bircok alan turu, stilleme ve veri yonetimini destekler.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Form Olusturma

Yeni bir form olusturmak icin Metro CLI kullanin:

``` bash
metro make:form LoginForm
```

Bu, `lib/app/forms/login_form.dart` dosyasini olusturur:

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

Formlar `NyFormWidget` sinifini genisletir ve form alanlarini tanimlamak icin `fields()` metodunu gecersiz kilar. Her alan `Field.text()`, `Field.email()` veya `Field.password()` gibi adlandirilmis bir kurucu kullanir. `static NyFormActions get actions` getter'i, uygulamanizin herhangi bir yerinden formla etkilesim kurmak icin uygun bir yol saglar.


<div id="displaying-a-form"></div>

## Form Goruntuleme

Form sinfiniz `NyFormWidget` sinifini genislettigi icin, **kendisi** widget'tir. Dogrudan widget agacinizda kullanin:

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

## Form Gonderme

Bir formu gondermenin uc yolu vardir:

### onSubmit ve submitButton kullanma

Formu olustururken `onSubmit` ve bir `submitButton` gecirin. {{ config('app.name') }}, gonder dugmesi olarak calisan onceden olusturulmus dugmeler saglar:

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

Mevcut dugme stilleri: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### NyFormActions kullanma

Herhangi bir yerden gondermek icin `actions` getter'ini kullanin:

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

Bir formu adiyla herhangi bir yerden gonderin:

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

Gonderildiginde, form tum alanlari dogrular. Gecerliyse, `onSuccess` alan verilerinin `Map<String, dynamic>` degeriyle cagrilir (anahtarlar alan adlarinin snake_case versiyonlaridir). Gecersizse, varsayilan olarak bir toast hatasi gosterilir ve saglanmissa `onFailure` cagrilir.


<div id="field-types"></div>

## Alan Turleri

{{ config('app.name') }} v7, `Field` sinifinda adlandirilmis kurucular araciligiyla 22 alan turu saglar. Tum alan kuruculari su ortak parametreleri paylasir:

| Parametre | Tur | Varsayilan | Aciklama |
|-----------|------|---------|-------------|
| `key` | `String` | Zorunlu | Alan tanimlayicisi (konumsal) |
| `label` | `String?` | `null` | Ozel goruntuleme etiketi (varsayilan olarak anahtar baslik buyuklugunde) |
| `value` | `dynamic` | `null` | Baslangic degeri |
| `validator` | `FormValidator?` | `null` | Dogrulama kurallari |
| `autofocus` | `bool` | `false` | Yuklemede otomatik odaklanma |
| `dummyData` | `String?` | `null` | Test/gelistirme verileri |
| `header` | `Widget?` | `null` | Alanin ustunde goruntulenen widget |
| `footer` | `Widget?` | `null` | Alanin altinda goruntulenen widget |
| `titleStyle` | `TextStyle?` | `null` | Ozel etiket metin stili |
| `hidden` | `bool` | `false` | Alani gizle |
| `readOnly` | `bool?` | `null` | Alani salt okunur yap |
| `style` | `FieldStyle?` | Degisir | Alana ozgu stil yapilandirmasi |
| `onChanged` | `Function(dynamic)?` | `null` | Deger degisikligi callback'i |

<div id="text-fields"></div>

### Metin Alanlari

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Stil turu: `FieldStyleTextField`

<div id="number-fields"></div>

### Sayi Alanlari

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

`decimal` parametresi ondalik giris izin verilip verilmeyecegini kontrol eder. Stil turu: `FieldStyleTextField`

<div id="password-fields"></div>

### Sifre Alanlari

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

`viewable` parametresi bir goster/gizle gecis dugmesi ekler. Stil turu: `FieldStyleTextField`

<div id="email-fields"></div>

### E-posta Alanlari

``` dart
Field.email("Email", validator: FormValidator.email())
```

Otomatik olarak e-posta klavye turunu ayarlar ve bosluklari filtreler. Stil turu: `FieldStyleTextField`

<div id="url-fields"></div>

### URL Alanlari

``` dart
Field.url("Website", validator: FormValidator.url())
```

URL klavye turunu ayarlar. Stil turu: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Metin Alani (Cok Satirli)

``` dart
Field.textArea("Description")
```

Cok satirli metin girisi. Stil turu: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Telefon Numarasi Alanlari

``` dart
Field.phoneNumber("Mobile Phone")
```

Telefon numarasi girisini otomatik olarak bicimlendirir. Stil turu: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Kelimeleri Buyuk Harfle Baslatma

``` dart
Field.capitalizeWords("Full Name")
```

Her kelimenin ilk harfini buyuk yapar. Stil turu: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Cumleleri Buyuk Harfle Baslatma

``` dart
Field.capitalizeSentences("Bio")
```

Her cumlenin ilk harfini buyuk yapar. Stil turu: `FieldStyleTextField`

<div id="date-fields"></div>

### Tarih Alanlari

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Bir tarih secici acar. Varsayilan olarak, alan kullanicilarin degeri sifirlamasina izin veren bir temizleme dugmesi gosterir. Gizlemek icin `canClear: false` ayarlayin veya simgeyi degistirmek icin `clearIconData` kullanin. Stil turu: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### TarihSaat Alanlari

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Bir tarih ve saat secici acar. `firstDate`, `lastDate`, `dateFormat` ve `initialPickerDateTime` degerlerini dogrudan ust duzey parametreler olarak ayarlayabilirsiniz. Stil turu: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Maskeli Giris Alanlari

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Maskedeki `#` karakteri kullanici girisiyle degistirilir. Izin verilen karakterleri kontrol etmek icin `match` kullanin. `maskReturnValue` `true` oldugunda, dondurulen deger maske bicimlendirmesini icerir.

<div id="currency-fields"></div>

### Para Birimi Alanlari

``` dart
Field.currency("Price", currency: "usd")
```

`currency` parametresi zorunludur ve para birimi bicimini belirler. Stil turu: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Onay Kutusu Alanlari

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Stil turu: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Anahtar Kutusu Alanlari

``` dart
Field.switchBox("Enable Notifications")
```

Stil turu: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Secici Alanlari

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

`options` parametresi bir `FormCollection` gerektirir (ham liste degil). Ayrintilar icin [FormCollection](#form-collection) bolumune bakin. Stil turu: `FieldStylePicker`

#### Liste Kutucugu Stilleri

`PickerListTileStyle` kullanarak picker'in alt sayfasinda ogelerin gorunumunu ozellestirebilirsiniz. Varsayilan olarak, alt sayfa duz metin kutucukleri gosterir. Secim gostergelerini eklemek icin yerlesik sablonlari kullanin veya tamamen ozel bir builder saglayin.

**Radyo stili** — basta widget olarak bir radyo dugmesi simgesi gosterir:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Onay isareti stili** — secildiginde sonda widget olarak bir onay simgesi gosterir:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Ozel builder** — her kutucugun widget'i uzerinde tam kontrol:

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

Her iki onayarli stil ayrica `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` ve `selectedTileColor` destekler:

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

### Radyo Alanlari

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` parametresi bir `FormCollection` gerektirir. Stil turu: `FieldStyleRadio`

<div id="chip-fields"></div>

### Cip Alanlari

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Cip widget'lari araciligiyla coklu secim yapilmasini saglar. `options` parametresi bir `FormCollection` gerektirir. Stil turu: `FieldStyleChip`

<div id="slider-fields"></div>

### Kaydirici Alanlari

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

Stil turu: `FieldStyleSlider` -- `min`, `max`, `divisions`, renkler, deger gosterimi ve daha fazlasini yapilandirin.

<div id="range-slider-fields"></div>

### Aralik Kaydirici Alanlari

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

Bir `RangeValues` nesnesi dondurur. Stil turu: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Ozel Alanlar

Kendi durum bilgili widget'inizi saglamak icin `Field.custom()` kullanin:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` parametresi `NyFieldStatefulWidget` sinifini genisleten bir widget gerektirir. Bu, alanin render edilmesi ve davranisi uzerinde tam kontrol saglar.

<div id="widget-fields"></div>

### Widget Alanlari

Form alani olmadan form icine herhangi bir widget yerlestirmek icin `Field.widget()` kullanin:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Widget alanlari dogrulama veya veri toplamaya katilmaz. Yalnizca duzen amaclidir.


<div id="form-collection"></div>

## FormCollection

Secici, radyo ve cip alanlari, secenekleri icin bir `FormCollection` gerektirir. `FormCollection`, farkli secenek bicimlerini islemek icin birlesik bir arayuz saglar.

### FormCollection Olusturma

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()`, veri bicimini otomatik olarak algilar ve uygun kurucuya yonlendirir.

### FormOption

Bir `FormCollection` icindeki her secenek, `value` ve `label` ozelliklerine sahip bir `FormOption`'dir:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Secenekleri Sorgulama

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

## Form Dogrulama

`FormValidator` ile `validator` parametresini kullanarak herhangi bir alana dogrulama ekleyin:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

Bir form gonderildiginde, tum dogrulayicilar kontrol edilir. Herhangi biri basarisiz olursa, ilk hata mesajini gosteren bir toast hatasi goruntulenir ve `onFailure` callback'i cagrilir.

**Ayrica bakin:** Kullanilabilir dogrulayicilarin tam listesi icin [Dogrulama](/docs/7.x/validation#validation-rules) sayfasina bakin.


<div id="managing-form-data"></div>

## Form Verilerini Yonetme

<div id="initial-data"></div>

### Baslangic Verileri

Bir forma baslangic verileri ayarlamanin iki yolu vardir.

**Secenek 1: Form sinifinizda `init` getter'ini gecersiz kilin**

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

`init` getter'i senkron bir `Map` veya asenkron bir `Future<Map>` dondurebilir. Anahtarlar, snake_case normalizasyonu kullanilarak alan adlariyla eslestirilir, bu nedenle `"First Name"` anahtari `"First Name"` adli bir alanla eslesir.

#### init icinde `define()` kullanimi

`init` icerisinde bir alan icin **secenekler** (veya hem bir deger hem de secenekler) ayarlamaniz gerektiginde `define()` yardimcisini kullanin. Bu, seceneklerin bir API veya baska bir asenkron kaynaktan geldigi picker, chip ve radio alanlari icin kullanislidir.

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

`define()` iki adlandirilmis parametre kabul eder:

| Parametre | Aciklama |
|-----------|-------------|
| `value` | Alan icin baslangic degeri |
| `options` | Picker, chip veya radio alanlari icin secenekler |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

`define()`'a gonderilen secenekler bir `List`, `Map` veya `FormCollection` olabilir. Uygulandiginda otomatik olarak bir `FormCollection`'a donusturulur.

**Secenek 2: Form widget'ina `initialData` gecirin**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Alan Degerlerini Ayarlama

Herhangi bir yerden alan degerlerini ayarlamak icin `NyFormActions` kullanin:

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Alan Seceneklerini Ayarlama

Secici, cip veya radyo alanlarindaki secenekleri dinamik olarak guncelleyin:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Form Verilerini Okuma

Form verileri, form gonderildiginde `onSubmit` callback'i araciligiyla veya gercek zamanli guncellemeler icin `onChanged` callback'i araciligiyla erisilir:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
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
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Alanlari Guncelleme

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Gonder Dugmesi

Formu olustururken bir `submitButton` ve `onSubmit` callback'i gecirin:

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

`submitButton` otomatik olarak form alanlarinin altinda goruntulenir. Yerlesik dugme stillerinden herhangi birini veya ozel bir widget kullanabilirsiniz.

Herhangi bir widget'i `footer` olarak gecirerek gonder dugmesi olarak da kullanabilirsiniz:

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

## Form Duzeni

Alanlari yan yana yerlestirmek icin bir `List` icine sarin:

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Bir `List` icindeki alanlar, esit `Expanded` genisliklere sahip bir `Row` icinde render edilir. Alanlar arasindaki bosluk, `NyFormWidget` uzerindeki `crossAxisSpacing` parametresiyle kontrol edilir.


<div id="field-visibility"></div>

## Alan Gorunurlugu

`Field` uzerindeki `hide()` ve `show()` metotlarini kullanarak alanlari programatik olarak gosterin veya gizleyin. Alanlara form sinifinizin icinden veya `onChanged` callback'i araciligiyla erisebilirsiniz:

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Gizli alanlar arayuzde render edilmez ancak formun alan listesinde var olmaya devam eder.


<div id="field-styling"></div>

## Alan Stilleri

Her alan turunun stilleme icin karsilik gelen bir `FieldStyle` alt sinifi vardir:

| Alan Turu | Stil Sinifi |
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

Herhangi bir alanin `style` parametresine bir stil nesnesi gecirin:

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

## NyFormWidget Statik Metotlari

`NyFormWidget`, uygulamanizin herhangi bir yerinden formlarla ada gore etkilesim kurmak icin statik metotlar saglar:

| Metot | Aciklama |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Bir formu adiyla gonder |
| `NyFormWidget.stateRefresh(name)` | Formun arayuz durumunu yenile |
| `NyFormWidget.stateSetValue(name, key, value)` | Form adiyla alan degerini ayarla |
| `NyFormWidget.stateSetOptions(name, key, options)` | Form adiyla alan seceneklerini ayarla |
| `NyFormWidget.stateClearData(name)` | Form adiyla tum alanlari temizle |
| `NyFormWidget.stateRefreshForm(name)` | Form alanlarini yenile (`fields()` yeniden cagirir) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **Ipucu:** Bu statik metotlari dogrudan cagirmak yerine `NyFormActions` (asagiya bakin) kullanmayi tercih edin -- daha kisa ve daha az hataya aciktir.


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget Kurucu Referansi

`NyFormWidget` sinifini genisletirken, gecebileceginiz kurucu parametreleri sunlardir:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

`onChanged` callback'i, degisen `Field` ve yeni degerini alir:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions`, uygulamanizin herhangi bir yerinden bir formla etkilesim kurmak icin uygun bir yol saglar. Form sinifinizda statik getter olarak tanimlayin:

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

| Metot | Aciklama |
|--------|-------------|
| `actions.updateField(key, value)` | Bir alanin degerini ayarla |
| `actions.clearField(key)` | Belirli bir alani temizle |
| `actions.clear()` | Tum alanlari temizle |
| `actions.refresh()` | Formun arayuz durumunu yenile |
| `actions.refreshForm()` | `fields()` yeniden cagir ve yeniden olustur |
| `actions.setOptions(key, options)` | Secici/cip/radyo alanlarinda secenekleri ayarla |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Dogrulama ile gonder |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget Gecersiz Kilmalari

`NyFormWidget` alt sinifinizdaki gecersiz kilabilecek metotlar:

| Gecersiz Kilma | Aciklama |
|----------|-------------|
| `fields()` | Form alanlarini tanimlayin (zorunlu) |
| `init` | Baslangic verileri saglayin (senkron veya asenkron) |
| `onChange(field, data)` | Alan degisikliklerini dahili olarak yonetin |


<div id="all-field-types-reference"></div>

## Tum Alan Turleri Referansi

| Kurucu | Anahtar Parametreler | Aciklama |
|-------------|----------------|-------------|
| `Field.text()` | -- | Standart metin girisi |
| `Field.email()` | -- | Klavye turlu e-posta girisi |
| `Field.password()` | `viewable` | Istege bagli gorunurluk gecisli sifre |
| `Field.number()` | `decimal` | Sayisal giris, istege bagli ondalik |
| `Field.currency()` | `currency` (zorunlu) | Para birimi bicimli giris |
| `Field.capitalizeWords()` | -- | Baslik buyuklugunde metin girisi |
| `Field.capitalizeSentences()` | -- | Cumle buyuklugunde metin girisi |
| `Field.textArea()` | -- | Cok satirli metin girisi |
| `Field.phoneNumber()` | -- | Otomatik bicimli telefon numarasi |
| `Field.url()` | -- | Klavye turlu URL girisi |
| `Field.mask()` | `mask` (zorunlu), `match`, `maskReturnValue` | Maskeli metin girisi |
| `Field.date()` | -- | Tarih secici |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Tarih ve saat secici |
| `Field.checkbox()` | -- | Boolean onay kutusu |
| `Field.switchBox()` | -- | Boolean gecis anahtari |
| `Field.picker()` | `options` (zorunlu `FormCollection`) | Listeden tekli secim |
| `Field.radio()` | `options` (zorunlu `FormCollection`) | Radyo dugme grubu |
| `Field.chips()` | `options` (zorunlu `FormCollection`) | Coklu secim cipleri |
| `Field.slider()` | -- | Tekli deger kaydiricisi |
| `Field.rangeSlider()` | -- | Aralik deger kaydiricisi |
| `Field.custom()` | `child` (zorunlu `NyFieldStatefulWidget`) | Ozel durum bilgili widget |
| `Field.widget()` | `child` (zorunlu `Widget`) | Herhangi bir widget yerlestir (alan disi) |

