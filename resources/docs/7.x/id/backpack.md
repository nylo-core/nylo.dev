# Backpack

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Penggunaan Dasar](#basic-usage "Penggunaan Dasar")
- [Membaca Data](#reading-data "Membaca Data")
- [Menyimpan Data](#saving-data "Menyimpan Data")
- [Menghapus Data](#deleting-data "Menghapus Data")
- [Sesi](#sessions "Sesi")
- [Mengakses Instance Nylo](#nylo-instance "Mengakses Instance Nylo")
- [Fungsi Helper](#helper-functions "Fungsi Helper")
- [Integrasi dengan NyStorage](#integration-with-nystorage "Integrasi dengan NyStorage")
- [Contoh](#examples "Contoh Praktis")

<div id="introduction"></div>

## Pengantar

**Backpack** adalah sistem penyimpanan singleton dalam memori di {{ config('app.name') }}. Backpack menyediakan akses data yang cepat dan sinkron selama runtime aplikasi Anda. Berbeda dengan `NyStorage` yang menyimpan data secara persisten di perangkat, Backpack menyimpan data di memori dan akan dihapus saat aplikasi ditutup.

Backpack digunakan secara internal oleh framework untuk menyimpan instance penting seperti objek aplikasi `Nylo`, `EventBus`, dan data autentikasi. Anda juga dapat menggunakannya untuk menyimpan data Anda sendiri yang perlu diakses dengan cepat tanpa panggilan asinkron.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// Save a value
Backpack.instance.save("user_name", "Anthony");

// Read a value (synchronous)
String? name = Backpack.instance.read("user_name");

// Delete a value
Backpack.instance.delete("user_name");
```

<div id="basic-usage"></div>

## Penggunaan Dasar

Backpack menggunakan **pola singleton** -- akses melalui `Backpack.instance`:

``` dart
// Save data
Backpack.instance.save("theme", "dark");

// Read data
String? theme = Backpack.instance.read("theme"); // "dark"

// Check if data exists
bool hasTheme = Backpack.instance.contains("theme"); // true
```

<div id="reading-data"></div>

## Membaca Data

Baca nilai dari Backpack menggunakan metode `read<T>()`. Metode ini mendukung tipe generik dan nilai default opsional:

``` dart
// Read a String
String? name = Backpack.instance.read<String>("name");

// Read with a default value
String name = Backpack.instance.read<String>("name", defaultValue: "Guest") ?? "Guest";

// Read an int
int? score = Backpack.instance.read<int>("score");
```

Backpack secara otomatis melakukan deserialisasi string JSON ke objek model ketika tipe disediakan:

``` dart
// If a User model is stored as JSON, it will be deserialized
User? user = Backpack.instance.read<User>("current_user");
```

<div id="saving-data"></div>

## Menyimpan Data

Simpan nilai menggunakan metode `save()`:

``` dart
Backpack.instance.save("api_token", "abc123");
Backpack.instance.save("is_premium", true);
Backpack.instance.save("cart_count", 3);
```

### Menambahkan Data

Gunakan `append()` untuk menambahkan nilai ke daftar yang tersimpan pada sebuah key:

``` dart
// Append to a list
Backpack.instance.append("recent_searches", "Flutter");
Backpack.instance.append("recent_searches", "Dart");

// Append with a limit (keeps only the last N items)
Backpack.instance.append("recent_searches", "Nylo", limit: 10);
```

<div id="deleting-data"></div>

## Menghapus Data

### Menghapus Satu Key

``` dart
Backpack.instance.delete("api_token");
```

### Menghapus Semua Data

Metode `deleteAll()` menghapus semua nilai **kecuali** key framework yang dicadangkan (`nylo` dan `event_bus`):

``` dart
Backpack.instance.deleteAll();
```

<div id="sessions"></div>

## Sesi

Backpack menyediakan manajemen sesi untuk mengorganisir data ke dalam grup bernama. Ini berguna untuk menyimpan data yang saling terkait secara bersamaan.

### Memperbarui Nilai Sesi

``` dart
Backpack.instance.sessionUpdate("cart", "item_count", 3);
Backpack.instance.sessionUpdate("cart", "total", 29.99);
```

### Mendapatkan Nilai Sesi

``` dart
int? itemCount = Backpack.instance.sessionGet<int>("cart", "item_count"); // 3
double? total = Backpack.instance.sessionGet<double>("cart", "total"); // 29.99
```

### Menghapus Key Sesi

``` dart
Backpack.instance.sessionRemove("cart", "item_count");
```

### Mengosongkan Seluruh Sesi

``` dart
Backpack.instance.sessionFlush("cart");
```

### Mendapatkan Semua Data Sesi

``` dart
Map<String, dynamic>? cartData = Backpack.instance.sessionData("cart");
// {"item_count": 3, "total": 29.99}
```

<div id="nylo-instance"></div>

## Mengakses Instance Nylo

Backpack menyimpan instance aplikasi `Nylo`. Anda dapat mengambilnya menggunakan:

``` dart
Nylo nylo = Backpack.instance.nylo();
```

Memeriksa apakah instance Nylo sudah diinisialisasi:

``` dart
bool isReady = Backpack.instance.isNyloInitialized(); // true
```

<div id="helper-functions"></div>

## Fungsi Helper

{{ config('app.name') }} menyediakan fungsi helper global untuk operasi Backpack yang umum:

| Fungsi | Deskripsi |
|----------|-------------|
| `backpackRead<T>(key)` | Membaca nilai dari Backpack |
| `backpackSave(key, value)` | Menyimpan nilai ke Backpack |
| `backpackDelete(key)` | Menghapus nilai dari Backpack |
| `backpackDeleteAll()` | Menghapus semua nilai (mempertahankan key framework) |
| `backpackNylo()` | Mendapatkan instance Nylo dari Backpack |

### Contoh

``` dart
// Using helper functions
backpackSave("locale", "en");

String? locale = backpackRead<String>("locale"); // "en"

backpackDelete("locale");

// Access the Nylo instance
Nylo nylo = backpackNylo();
```

<div id="integration-with-nystorage"></div>

## Integrasi dengan NyStorage

Backpack terintegrasi dengan `NyStorage` untuk penyimpanan gabungan persisten + dalam memori:

``` dart
// Save to both NyStorage (persistent) and Backpack (in-memory)
await NyStorage.save("auth_token", "abc123", inBackpack: true);

// Now accessible synchronously via Backpack
String? token = Backpack.instance.read("auth_token");

// When deleting from NyStorage, also clear from Backpack
await NyStorage.deleteAll(andFromBackpack: true);
```

Pola ini berguna untuk data seperti token autentikasi yang membutuhkan baik persistensi maupun akses sinkron yang cepat (misalnya, di HTTP interceptor).

<div id="examples"></div>

## Contoh

### Menyimpan Token Auth untuk Permintaan API

``` dart
// In your auth interceptor
class BearerAuthInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, RequestInterceptorHandler handler) {
    String? userToken = Backpack.instance.read(StorageKeysConfig.auth);

    if (userToken != null) {
      options.headers.addAll({"Authorization": "Bearer $userToken"});
    }

    return super.onRequest(options, handler);
  }
}
```

### Manajemen Keranjang Berbasis Sesi

``` dart
// Add items to a cart session
Backpack.instance.sessionUpdate("cart", "items", ["item_1", "item_2"]);
Backpack.instance.sessionUpdate("cart", "total", 49.99);

// Read cart data
Map<String, dynamic>? cart = Backpack.instance.sessionData("cart");

// Clear the cart
Backpack.instance.sessionFlush("cart");
```

### Feature Flag Cepat

``` dart
// Store feature flags in Backpack for fast access
backpackSave("feature_dark_mode", true);
backpackSave("feature_notifications", false);

// Check a feature flag
bool darkMode = backpackRead<bool>("feature_dark_mode") ?? false;
```
