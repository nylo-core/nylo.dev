# Berkontribusi pada {{ config('app.name') }}

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Memulai](#getting-started "Memulai")
- [Lingkungan Pengembangan](#development-environment "Lingkungan Pengembangan")
- [Panduan Pengembangan](#development-guidelines "Panduan Pengembangan")
- [Mengirimkan Perubahan](#submitting-changes "Mengirimkan Perubahan")
- [Melaporkan Masalah](#reporting-issues "Melaporkan Masalah")


<div id="introduction"></div>

## Pengantar

Terima kasih telah mempertimbangkan untuk berkontribusi pada {{ config('app.name') }}!

Panduan ini akan membantu Anda memahami cara berkontribusi pada micro-framework ini. Baik Anda memperbaiki bug, menambahkan fitur, atau meningkatkan dokumentasi, kontribusi Anda sangat berharga bagi komunitas {{ config('app.name') }}.

{{ config('app.name') }} dibagi menjadi tiga repositori:

| Repositori | Tujuan |
|------------|--------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | Aplikasi boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | Kelas inti framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | Library pendukung dengan widget, helper, utilitas (nylo_support) |

<div id="getting-started"></div>

## Memulai

### Fork Repositori

Fork repositori yang ingin Anda kontribusikan:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clone Fork Anda

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## Lingkungan Pengembangan

### Persyaratan

Pastikan Anda telah menginstal yang berikut:

| Persyaratan | Versi Minimum |
|-------------|---------------|
| Flutter | 3.24.0 atau lebih tinggi |
| Dart SDK | 3.10.7 atau lebih tinggi |

### Hubungkan Paket Lokal

Buka boilerplate Nylo di editor Anda dan tambahkan override dependensi untuk menggunakan repositori framework dan support lokal Anda:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

Jalankan `flutter pub get` untuk menginstal dependensi.

Sekarang perubahan yang Anda buat pada repositori framework atau support akan tercermin di boilerplate Nylo.

### Menguji Perubahan Anda

Jalankan aplikasi boilerplate untuk menguji perubahan Anda:

``` bash
flutter run
```

Untuk perubahan widget atau helper, pertimbangkan untuk menambahkan tes di repositori yang sesuai.

<div id="development-guidelines"></div>

## Panduan Pengembangan

### Gaya Kode

- Ikuti <a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">panduan gaya Dart</a> resmi
- Gunakan nama variabel dan fungsi yang bermakna
- Tulis komentar yang jelas untuk logika yang kompleks
- Sertakan dokumentasi untuk API publik
- Jaga kode tetap modular dan mudah dipelihara

### Dokumentasi

Saat menambahkan fitur baru:

- Tambahkan komentar dartdoc ke kelas dan method publik
- Perbarui file dokumentasi yang relevan jika diperlukan
- Sertakan contoh kode dalam dokumentasi

### Pengujian

Sebelum mengirimkan perubahan:

- Uji di perangkat/simulator iOS dan Android
- Verifikasi kompatibilitas mundur jika memungkinkan
- Dokumentasikan setiap perubahan yang merusak dengan jelas
- Jalankan tes yang ada untuk memastikan tidak ada yang rusak

<div id="submitting-changes"></div>

## Mengirimkan Perubahan

### Diskusikan Terlebih Dahulu

Untuk fitur baru, sebaiknya diskusikan dengan komunitas terlebih dahulu:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### Buat Branch

``` bash
git checkout -b feature/your-feature-name
```

Gunakan nama branch yang deskriptif:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Commit Perubahan Anda

``` bash
git add .
git commit -m "Add: Your feature description"
```

Gunakan pesan commit yang jelas:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push dan Buat Pull Request

``` bash
git push origin feature/your-feature-name
```

Kemudian buat pull request di GitHub.

### Panduan Pull Request

- Berikan deskripsi yang jelas tentang perubahan Anda
- Referensikan issue terkait
- Sertakan screenshot atau contoh kode jika berlaku
- Pastikan PR Anda hanya menangani satu masalah
- Jaga perubahan tetap fokus dan atomik

<div id="reporting-issues"></div>

## Melaporkan Masalah

### Sebelum Melaporkan

1. Periksa apakah masalah sudah ada di GitHub
2. Pastikan Anda menggunakan versi terbaru
3. Coba reproduksi masalah di proyek baru

### Tempat Melaporkan

Laporkan masalah di repositori yang sesuai:

- **Masalah boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **Masalah framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **Masalah library support**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### Template Issue

Berikan informasi detail:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### Mendapatkan Informasi Versi

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
