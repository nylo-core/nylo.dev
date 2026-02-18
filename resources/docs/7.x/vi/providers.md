# Providers

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Tạo một provider](#create-a-provider "Tạo một provider")
- [Đối tượng Provider](#provider-object "Đối tượng Provider")


<div id="introduction"></div>

## Giới thiệu về Providers

Trong {{ config('app.name') }}, các provider được khởi tạo ban đầu từ file <b>main.dart</b> khi ứng dụng chạy. Tất cả provider nằm trong `/lib/app/providers/*`, bạn có thể chỉnh sửa các file này hoặc tạo provider bằng <a href="/docs/7.x/metro#make-provider" target="_BLANK">Metro</a>.

Providers có thể được sử dụng khi bạn cần khởi tạo một lớp, package hoặc tạo thứ gì đó trước khi ứng dụng tải ban đầu. Ví dụ, lớp `route_provider.dart` chịu trách nhiệm thêm tất cả các route vào {{ config('app.name') }}.

### Tìm hiểu sâu

```dart
import 'package:nylo_framework/nylo_framework.dart';
import 'bootstrap/boot.dart';

/// Nylo - Framework for Flutter Developers
/// Docs: https://nylo.dev/docs/7.x

/// Main entry point for the application.
void main() async {
  await Nylo.init(
    setup: Boot.nylo,
    setupFinished: Boot.finished,

    // showSplashScreen: true,
    // Uncomment showSplashScreen to show the splash screen
    // File: lib/resources/widgets/splash_screen.dart
  );
}
```

### Vòng đời

- `Boot.{{ config('app.name') }}` sẽ lặp qua các provider đã đăng ký trong file <b>config/providers.dart</b> và khởi tạo chúng.

- `Boot.Finished` được gọi ngay sau khi **"Boot.{{ config('app.name') }}"** hoàn thành, phương thức này sẽ gắn instance {{ config('app.name') }} vào `Backpack` với giá trị 'nylo'.

Ví dụ: Backpack.instance.read('nylo'); // Instance {{ config('app.name') }}


<div id="create-a-provider"></div>

## Tạo Provider mới

Bạn có thể tạo provider mới bằng cách chạy lệnh bên dưới trong terminal.

```bash
metro make:provider cache_provider
```

<div id="provider-object"></div>

## Đối tượng Provider

Provider của bạn sẽ có hai phương thức, `setup(Nylo nylo)` và `boot(Nylo nylo)`.

Khi ứng dụng chạy lần đầu, bất kỳ mã nào trong phương thức **setup** sẽ được thực thi trước. Bạn cũng có thể thao tác đối tượng `Nylo` như trong ví dụ bên dưới.

Ví dụ: `lib/app/providers/app_provider.dart`

```dart
class AppProvider extends NyProvider {

  @override
  Future<Nylo?> setup(Nylo nylo) async {
    await NyLocalization.instance.init(
        localeType: localeType,
        languageCode: languageCode,
        languagesList: languagesList,
        assetsDirectory: assetsDirectory,
        valuesAsMap: valuesAsMap);

    return nylo;
  }

  @override
  Future<void> boot(Nylo nylo) async {
    User user = await Auth.user();
    if (!user.isSubscribed) {
      await Auth.remove();
    }
  }
}
```

### Vòng đời

1. `setup(Nylo nylo)` - Khởi tạo provider. Trả về instance `Nylo` hoặc `null`.
2. `boot(Nylo nylo)` - Được gọi sau khi tất cả provider hoàn thành setup. Sử dụng phương thức này cho khởi tạo phụ thuộc vào các provider khác đã sẵn sàng.

> Bên trong phương thức `setup`, bạn phải **trả về** một instance của `Nylo` hoặc `null` như trên.
