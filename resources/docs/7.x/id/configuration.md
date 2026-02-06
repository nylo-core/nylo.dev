# Konfigurasi

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar tentang konfigurasi")
- Environment
  - [File .env](#env-file "File .env")
  - [Membuat Konfigurasi Environment](#generating-env "Membuat konfigurasi environment")
  - [Mengambil Nilai](#retrieving-values "Mengambil nilai environment")
  - [Membuat Kelas Config](#creating-config-classes "Membuat kelas config")
  - [Tipe Variabel](#variable-types "Tipe variabel environment")
- [Varian Environment](#environment-flavours "Varian environment")
- [Injeksi Waktu Build](#build-time-injection "Injeksi waktu build")


<div id="introduction"></div>

## Pengantar

{{ config('app.name') }} v7 menggunakan sistem konfigurasi environment yang aman. Variabel environment Anda disimpan dalam file `.env` dan kemudian dienkripsi ke dalam file Dart yang dihasilkan (`env.g.dart`) untuk digunakan di aplikasi Anda.

Pendekatan ini memberikan:
- **Keamanan**: Nilai environment dienkripsi XOR di aplikasi yang dikompilasi
- **Keamanan tipe**: Nilai secara otomatis diurai ke tipe yang sesuai
- **Fleksibilitas waktu build**: Konfigurasi berbeda untuk pengembangan, staging, dan produksi

<div id="env-file"></div>

## File .env

File `.env` di root proyek Anda berisi variabel konfigurasi Anda:

``` bash
# Konfigurasi environment
APP_KEY=your-32-character-secret-key
APP_NAME="My App"
APP_ENV="developing"
APP_DEBUG=true
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"

ASSET_PATH="assets"

DEFAULT_LOCALE="en"
```

### Variabel yang Tersedia

| Variabel | Deskripsi |
|----------|-----------|
| `APP_KEY` | **Wajib**. Kunci rahasia 32 karakter untuk enkripsi |
| `APP_NAME` | Nama aplikasi Anda |
| `APP_ENV` | Environment: `developing` atau `production` |
| `APP_DEBUG` | Aktifkan mode debug (`true`/`false`) |
| `APP_URL` | URL aplikasi Anda |
| `API_BASE_URL` | URL dasar untuk permintaan API |
| `ASSET_PATH` | Path ke folder aset |
| `DEFAULT_LOCALE` | Kode bahasa default |

<div id="generating-env"></div>

## Membuat Konfigurasi Environment

{{ config('app.name') }} v7 mengharuskan Anda membuat file environment terenkripsi sebelum aplikasi Anda dapat mengakses nilai env.

### Langkah 1: Buat APP_KEY

Pertama, buat APP_KEY yang aman:

``` bash
metro make:key
```

Ini menambahkan `APP_KEY` 32 karakter ke file `.env` Anda.

### Langkah 2: Buat env.g.dart

Buat file environment terenkripsi:

``` bash
metro make:env
```

Ini membuat `lib/bootstrap/env.g.dart` dengan variabel environment terenkripsi Anda.

Env Anda secara otomatis didaftarkan saat aplikasi Anda dimulai -- `Nylo.init(env: Env.get, ...)` di `main.dart` menangani ini untuk Anda. Tidak diperlukan pengaturan tambahan.

### Membuat Ulang Setelah Perubahan

Saat Anda mengubah file `.env`, buat ulang konfigurasinya:

``` bash
metro make:env --force
```

Flag `--force` menimpa `env.g.dart` yang ada.

<div id="retrieving-values"></div>

## Mengambil Nilai

Cara yang direkomendasikan untuk mengakses nilai environment adalah melalui **kelas config**. File `lib/config/app.dart` Anda menggunakan `getEnv()` untuk mengekspos nilai env sebagai field statis bertipe:

``` dart
// lib/config/app.dart
final class AppConfig {
  static final String appName = getEnv('APP_NAME', defaultValue: 'Nylo');
  static final String appEnv = getEnv('APP_ENV', defaultValue: 'developing');
  static final bool appDebug = getEnv('APP_DEBUG', defaultValue: false);
  static final String apiBaseUrl = getEnv('API_BASE_URL');
}
```

Kemudian di kode aplikasi Anda, akses nilai melalui kelas config:

``` dart
// Di mana saja di aplikasi Anda
String name = AppConfig.appName;
bool isDebug = AppConfig.appDebug;
String apiUrl = AppConfig.apiBaseUrl;
```

Pola ini menjaga akses env terpusat di kelas config Anda. Helper `getEnv()` sebaiknya digunakan di dalam kelas config bukan langsung di kode aplikasi.

<div id="creating-config-classes"></div>

## Membuat Kelas Config

Anda dapat membuat kelas config kustom untuk layanan pihak ketiga atau konfigurasi khusus fitur menggunakan Metro:

``` bash
metro make:config RevenueCat
```

Ini membuat file config baru di `lib/config/revenue_cat_config.dart`:

``` dart
final class RevenueCatConfig {
  // Add your config values here
}
```

### Contoh: Konfigurasi RevenueCat

**Langkah 1:** Tambahkan variabel environment ke file `.env` Anda:

``` bash
REVENUECAT_API_KEY="appl_xxxxxxxxxxxxx"
REVENUECAT_ENTITLEMENT_ID="premium"
```

**Langkah 2:** Perbarui kelas config Anda untuk mereferensikan nilai-nilai ini:

``` dart
// lib/config/revenue_cat_config.dart
import 'package:nylo_framework/nylo_framework.dart';

final class RevenueCatConfig {
  static final String apiKey = getEnv('REVENUECAT_API_KEY');
  static final String entitlementId = getEnv('REVENUECAT_ENTITLEMENT_ID', defaultValue: 'premium');
}
```

**Langkah 3:** Buat ulang konfigurasi environment Anda:

``` bash
metro make:env --force
```

**Langkah 4:** Gunakan kelas config di aplikasi Anda:

``` dart
import '/config/revenue_cat_config.dart';

// Initialize RevenueCat
await Purchases.configure(
  PurchasesConfiguration(RevenueCatConfig.apiKey),
);

// Check entitlements
if (entitlement.identifier == RevenueCatConfig.entitlementId) {
  // Grant premium access
}
```

Pendekatan ini menjaga kunci API dan nilai konfigurasi Anda tetap aman dan terpusat, sehingga memudahkan pengelolaan nilai yang berbeda di seluruh environment.

<div id="variable-types"></div>

## Tipe Variabel

Nilai di file `.env` Anda secara otomatis diurai:

| Nilai .env | Tipe Dart | Contoh |
|------------|-----------|--------|
| `APP_NAME="My App"` | `String` | `"My App"` |
| `DEBUG=true` | `bool` | `true` |
| `DEBUG=false` | `bool` | `false` |
| `VALUE=null` | `null` | `null` |
| `EMPTY=""` | `String` | `""` (string kosong) |


<div id="environment-flavours"></div>

## Varian Environment

Buat konfigurasi berbeda untuk pengembangan, staging, dan produksi.

### Langkah 1: Buat File Environment

Buat file `.env` terpisah:

``` bash
.env                  # Pengembangan (default)
.env.staging          # Staging
.env.production       # Produksi
```

Contoh `.env.production`:

``` bash
APP_KEY=production-secret-key-here
APP_NAME="My App"
APP_ENV="production"
APP_DEBUG=false
APP_URL="https://myapp.com"
API_BASE_URL="https://api.myapp.com"
```

### Langkah 2: Buat Konfigurasi Environment

Buat dari file env tertentu:

``` bash
# For production
metro make:env --file=".env.production" --force

# For staging
metro make:env --file=".env.staging" --force
```

### Langkah 3: Build Aplikasi Anda

Build dengan konfigurasi yang sesuai:

``` bash
# Pengembangan
flutter run

# Build produksi
metro make:env --file=.env.production --force
flutter build ios
flutter build appbundle
```

<div id="build-time-injection"></div>

## Injeksi Waktu Build

Untuk keamanan yang lebih baik, Anda dapat menyuntikkan APP_KEY saat waktu build alih-alih menanamkannya di kode sumber.

### Buat dengan Mode --dart-define

``` bash
metro make:env --dart-define
```

Ini membuat `env.g.dart` tanpa menanamkan APP_KEY.

### Build dengan Injeksi APP_KEY

``` bash
# iOS
flutter build ios --dart-define=APP_KEY=your-secret-key

# Android
flutter build appbundle --dart-define=APP_KEY=your-secret-key

# Run
flutter run --dart-define=APP_KEY=your-secret-key
```

Pendekatan ini menjaga APP_KEY di luar kode sumber Anda, yang berguna untuk:
- Pipeline CI/CD di mana secret disuntikkan
- Proyek open source
- Persyaratan keamanan yang lebih ketat

### Praktik Terbaik

1. **Jangan pernah commit `.env` ke version control** - Tambahkan ke `.gitignore`
2. **Gunakan `.env-example`** - Commit template tanpa nilai sensitif
3. **Buat ulang setelah perubahan** - Selalu jalankan `metro make:env --force` setelah mengubah `.env`
4. **Kunci berbeda per environment** - Gunakan APP_KEY unik untuk pengembangan, staging, dan produksi
