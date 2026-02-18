# App Usage

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Thiết lập](#setup "Thiết lập theo dõi sử dụng ứng dụng")
- Giám sát
    - [Số lần khởi chạy ứng dụng](#monitoring-app-launches "Giám sát số lần khởi chạy ứng dụng")
    - [Ngày khởi chạy lần đầu](#monitoring-app-first-launch-date "Giám sát ngày khởi chạy lần đầu")
    - [Tổng số ngày kể từ lần khởi chạy đầu tiên](#monitoring-app-total-days-since-first-launch "Giám sát tổng số ngày kể từ lần khởi chạy đầu tiên")

<div id="introduction"></div>

## Giới thiệu

Nylo cho phép bạn theo dõi việc sử dụng ứng dụng ngay lập tức nhưng trước tiên bạn cần bật tính năng này trong một trong các provider ứng dụng của bạn.

Hiện tại, Nylo có thể theo dõi những thông tin sau:

- Số lần khởi chạy ứng dụng
- Ngày khởi chạy lần đầu

Sau khi đọc tài liệu này, bạn sẽ biết cách theo dõi việc sử dụng ứng dụng của mình.

<div id="setup"></div>

## Thiết lập

Mở file `app/providers/app_provider.dart` của bạn.

Sau đó, thêm đoạn mã sau vào phương thức `boot`.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Điều này sẽ bật tính năng theo dõi sử dụng ứng dụng. Nếu bạn cần kiểm tra xem tính năng theo dõi đã được bật hay chưa, bạn có thể sử dụng phương thức `Nylo.instance.shouldMonitorAppUsage()`.

<div id="monitoring-app-launches"></div>

## Giám sát số lần khởi chạy ứng dụng

Bạn có thể theo dõi số lần ứng dụng đã được khởi chạy bằng phương thức `Nylo.appLaunchCount`.

> Số lần khởi chạy được tính mỗi khi ứng dụng được mở từ trạng thái đóng.

Một ví dụ đơn giản về cách sử dụng phương thức này:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Giám sát ngày khởi chạy lần đầu

Bạn có thể theo dõi ngày ứng dụng được khởi chạy lần đầu tiên bằng phương thức `Nylo.appFirstLaunchDate`.

Đây là ví dụ cách sử dụng phương thức này:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## Giám sát tổng số ngày kể từ lần khởi chạy đầu tiên

Bạn có thể theo dõi tổng số ngày kể từ lần đầu tiên ứng dụng được khởi chạy bằng phương thức `Nylo.appTotalDaysSinceFirstLaunch`.

Đây là ví dụ cách sử dụng phương thức này:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
