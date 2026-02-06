# TextTr

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Interpolasi String](#string-interpolation "Interpolasi String")
- [Konstruktor Bergaya](#styled-constructors "Konstruktor Bergaya")
- [Parameter](#parameters "Parameter")


<div id="introduction"></div>

## Pengantar

Widget **TextTr** adalah pembungkus praktis dari widget `Text` Flutter yang secara otomatis menerjemahkan kontennya menggunakan sistem lokalisasi {{ config('app.name') }}.

Alih-alih menulis:

``` dart
Text("hello_world".tr())
```

Anda dapat menulis:

``` dart
TextTr("hello_world")
```

Ini membuat kode Anda lebih bersih dan lebih mudah dibaca, terutama saat berurusan dengan banyak string yang diterjemahkan.

<div id="basic-usage"></div>

## Penggunaan Dasar

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

Widget akan mencari kunci terjemahan di file bahasa Anda (misalnya, `/lang/en.json`):

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Interpolasi String

Gunakan parameter `arguments` untuk menyisipkan nilai dinamis ke dalam terjemahan Anda:

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

Di file bahasa Anda:

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Output: **Hello, John!**

### Beberapa Argumen

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Output: **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Konstruktor Bergaya

`TextTr` menyediakan konstruktor bernama yang secara otomatis menerapkan gaya teks dari tema Anda:

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Menggunakan gaya `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Menggunakan gaya `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Menggunakan gaya `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Menggunakan gaya `Theme.of(context).textTheme.labelLarge`.

### Contoh dengan Konstruktor Bergaya

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Parameter

`TextTr` mendukung semua parameter widget `Text` standar:

| Parameter | Tipe | Deskripsi |
|-----------|------|-------------|
| `data` | `String` | Kunci terjemahan yang akan dicari |
| `arguments` | `Map<String, String>?` | Pasangan key-value untuk interpolasi string |
| `style` | `TextStyle?` | Gaya teks |
| `textAlign` | `TextAlign?` | Cara teks harus disejajarkan |
| `maxLines` | `int?` | Jumlah baris maksimum |
| `overflow` | `TextOverflow?` | Cara menangani overflow |
| `softWrap` | `bool?` | Apakah membungkus teks pada soft break |
| `textDirection` | `TextDirection?` | Arah teks |
| `locale` | `Locale?` | Locale untuk rendering teks |
| `semanticsLabel` | `String?` | Label aksesibilitas |

## Perbandingan

| Pendekatan | Kode |
|----------|------|
| Tradisional | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Dengan argumen | `TextTr("hello", arguments: {"name": "John"})` |
| Bergaya | `TextTr.headlineLarge("title")` |
