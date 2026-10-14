# Metro Live

---

<!-- uncertain: newly created translation; needs full human review -->

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về Nylo Live")
- Shell metro live
  - [Tùy chọn dùng chung](#shared-options "Các tùy chọn mà mọi live command đều chấp nhận")
  - [Tab Completion và Lịch sử](#tab-completion-and-history "Tab completion và lịch sử")
  - [Chạy một Script](#running-a-script "Chạy một script .live")
- [live:devices](#live-devices "Liệt kê các ứng dụng đang chạy")
- [live:status](#live-status "Hiển thị trạng thái ứng dụng")
- [live:run](#live-run "Chạy một live command dựng sẵn")
- [Playbooks](#playbooks "Chạy playbook từ Metro")
- Live Command
  - [Tạo một Live Command](#creating-a-live-command "Tạo một live command")
  - [Chạy một Live Command](#running-a-live-command "Chạy một live command")
- Seeder
  - [Tạo một Seeder](#creating-a-seeder "Tạo một seeder")
  - [Seeding và Rollback](#seeding-and-rolling-back "Seeding và rollback")
- Storage Snapshot
  - [Xuất một Snapshot](#exporting-a-snapshot "Xuất một snapshot")
  - [Nạp một Snapshot](#loading-a-snapshot "Nạp một snapshot")
- [Page Actions](#page-actions "Gọi một trang từ bên ngoài nó")

<div id="introduction"></div>

## Giới thiệu

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

**Nylo Live** cho phép Metro truy cập vào ứng dụng của bạn trong khi nó đang chạy ở chế độ debug (hoặc profile), thông qua Dart VM service. Nó tìm ứng dụng qua Dart Tooling Daemon mà không cần thiết lập, và chỉ kết nối với các ứng dụng được build từ dự án hiện tại.

Nylo Live được bật mặc định trong các bản build debug và profile; bản build release không đăng ký gì cả. Các command chỉ đọc ứng dụng (`live:status`, `live:run data`, `live:run routes`, ...) cũng hoạt động trong bản build profile. Các command thay đổi ứng dụng (`route`, `storage`, `auth`, seeder, ...) cần bản build debug.

Tự tắt nó, trước khi bất kỳ thứ gì khác chạy, từ một provider:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.useLive(false);
    return nylo;
  }

  @override
  boot(Nylo nylo) async {}
}
```

<div id="metro-live-shell"></div>

## Shell metro live

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Mở một shell kết nối đến ứng dụng đang chạy của bạn:

``` bash
metro live
```

Nylo Live phát hiện các ứng dụng đang chạy của dự án và kết nối với một trong số đó -- nó sẽ hỏi bạn chọn khi có nhiều hơn một ứng dụng đang chạy. Sau khi kết nối, dấu nhắc hiển thị thiết bị và route hiện đang hiển thị trên màn hình:

``` plaintext
Nylo Live · my_app
Connected to iPhone 15 Pro (debug)
Type help to see commands, and exit to leave.
iPhone 15 Pro /home ›
```

Live command bỏ tiền tố `metro live:` khi ở bên trong shell -- `status` thay vì `metro live:status`, `route /profile` thay vì `metro live:run route /profile`. Một vài command chỉ chạy được bên trong shell: `seed`, `seed:rollback`, `export`, `reload` và `restart`. Gõ `help` để liệt kê mọi command, hoặc `help <command>` để xem tùy chọn của một command. Gõ `exit` (hoặc Ctrl+D) để thoát.

Nếu ứng dụng khởi động lại trong khi bạn đang kết nối, shell sẽ tự kết nối lại.

<div id="shared-options"></div>

### Tùy chọn dùng chung

Mọi live command -- bên trong shell hoặc dưới dạng `metro live:*` -- đều chấp nhận các tùy chọn giống nhau:

| Tùy chọn | Mô tả |
|--------|-------------|
| `-d, --device <#\|name>` | Nhắm đến một ứng dụng theo số của nó trong `metro live:devices` hoặc theo tên thiết bị |
| `--all` | Chạy trên mọi ứng dụng đang chạy của dự án này |
| `--json` | In JSON có thể đọc được bằng máy |
| `--uri` | Dùng địa chỉ VM service này thay vì tự phát hiện ứng dụng |
| `--timeout` | Số giây chờ mỗi ứng dụng trong khi phát hiện (mặc định `3`) |

Một giá trị tùy chọn được viết dưới dạng `@path` sẽ được đọc từ tệp đó -- tiện lợi cho payload `--data` lớn:

``` bash
metro live:run route /profile --data @user.json
```

Bắt đầu giá trị bằng `@@` khi nó thực sự bắt đầu bằng `@`.

<div id="tab-completion-and-history"></div>

### Tab Completion và Lịch sử

Bên trong shell, Tab sẽ hoàn thành tên command, tùy chọn, route, storage và Backpack key, tên seeder và tên playbook dựa trên ứng dụng đang kết nối. Lịch sử được lưu trong `.dart_tool/nylo/live_history` và tồn tại giữa các phiên làm việc.

<div id="running-a-script"></div>

### Chạy một Script

Pipe một tệp vào `metro live` để chạy nó như một script, mỗi dòng một command, dừng lại ở command đầu tiên thất bại:

``` bash
metro live < seeds/demo.live
```

Các dòng trống và dòng bắt đầu bằng `#` sẽ được bỏ qua.

<div id="live-devices"></div>

## live:devices

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Liệt kê các ứng dụng đang chạy của dự án này, được đánh số cho `-d`:

``` bash
metro live:devices
```
``` plaintext
Running apps for my_app:
#  DEVICE          PLATFORM  MODE   ROUTE   APP
1  iPhone 15 Pro   ios       debug  /home   my_app
2  Pixel 8         android   debug  /login  my_app
```

<div id="live-status"></div>

## live:status

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Hiển thị ứng dụng, thiết bị, chế độ build, route và stack hiện tại, locale, theme, và liệu người dùng có đang đăng nhập hay không:

``` bash
metro live:status
```
``` plaintext
App            my_app (development)
Device         iPhone 15 Pro · ios 17.4
Mode           debug
Route          /home
Stack          /home
Locale         en
Theme          default
Authenticated  yes
Live commands  cart:seed
Seeders        demo_user
Playbooks      sign_up
```

<div id="live-run"></div>

## live:run

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Chạy một command dựng sẵn để kiểm tra hoặc điều khiển ứng dụng:

``` bash
metro live:run <command> [arguments]
```

| Command | Mô tả |
|---------|-------------|
| `data` | Hiển thị dữ liệu và các field của trang đang trên màn hình |
| `routes` | Liệt kê các route đã đăng ký |
| `route` | Mở một trang, với `--data` tùy chọn |
| `back` | Quay lại một trang, hoặc quay về một route |
| `deeplink` | Mở một deep link, hoặc hiển thị cách chúng được thiết lập |
| `storage` | Liệt kê local storage, hoặc hiển thị, lưu hoặc xóa một giá trị |
| `storage:clear` | Xóa local storage, với `--keep` tùy chọn |
| `backpack` | Liệt kê các giá trị Backpack, hoặc hiển thị, lưu hoặc xóa một giá trị |
| `auth` | Hiển thị ai đang đăng nhập, hoặc đăng nhập và đăng xuất |
| `locale` | Chuyển đổi ngôn ngữ của ứng dụng |
| `theme` | Chuyển đổi theme của ứng dụng |
| `state` | Gửi dữ liệu đến một state bằng `updateState` |
| `event` | Kích hoạt một trong các event của bạn |
| `toast` | Hiển thị một thông báo toast |
| `playbook` | Chạy các [playbook](/docs/{{ $version }}/playbooks) của bạn, hoặc liệt kê chúng |

Bên trong `metro live`, bỏ phần `live:run` -- `route /profile` thay vì `metro live:run route /profile`:

``` bash
metro live:run route /profile --data '{"id": 42}'
metro live:run storage SK_COINS 10
metro live:run backpack auth_user
metro live:run auth login --data '{"id": 42}'
metro live:run deeplink myapp://product/42
metro live:run state HomePage --data '{"count": 2}'
metro live:run event LogoutEvent --data '{"reason": "test"}'
metro live:run toast "Item saved" --title Success
```

`data` cũng đọc các field mà state của trang bạn khai báo, trực tiếp từ ứng dụng đang chạy -- Flutter không có reflection, nên điều này chỉ hoạt động với một state thực sự đang hiển thị trên màn hình:

``` bash
metro live:run data HomePage
```

<div id="playbooks"></div>

## Playbooks

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

Chạy một hoặc nhiều [playbook](/docs/{{ $version }}/playbooks) trên ứng dụng đang chạy của bạn. Mỗi bước được in ra khi nó diễn ra:

``` bash
metro live:run playbook sign_up
metro live:run playbook sign_up add_to_bag --speed fast
```

Nêu tên nhiều playbook để chạy chúng nối tiếp nhau. Khi không có tên, hoặc với `--list`, lệnh sẽ liệt kê các playbook của ứng dụng.

| Tùy chọn | Mô tả |
|--------|-------------|
| `--speed instant\|fast\|natural\|slow` | Chạy với nhịp độ này thay vì nhịp độ riêng của mỗi playbook |
| `--data <json>` | Một đối tượng JSON mà mọi playbook trong lần chạy đọc trong `data`, đè lên giá trị mặc định của chúng |
| `--no-overlay` | Ẩn đồng hồ đếm ngược, cùng ngón tay và chú thích của các playbook có hiển thị chúng |
| `--loop` | Chạy lặp đi lặp lại các playbook cho đến khi nhấn Ctrl+C |
| `--record[=<file>]` | Ghi màn hình thiết bị trong suốt lần chạy. Lưu vào `build/playbooks/<playbooks>-<device>-<time>.mp4`; `--record=demo.mp4` (hoặc `.gif`) chọn tệp, tính từ thư mục dự án. Cần ffmpeg |
| `--no-captions` | Bỏ tệp chú thích `.vtt` và các chapter của bản ghi |
| `--clean-status-bar` | Hiển thị 9:41 với đầy đủ sóng và pin trong khi playbook chạy, rồi khôi phục lại. Cũng hoạt động khi không có `--record` |
| `--countdown <seconds>` | Đếm ngược 0-10 giây trên màn hình trước bước đầu tiên |
| `--list` | Liệt kê các playbook của ứng dụng thay vì chạy một playbook |

Ctrl+C yêu cầu ứng dụng dừng lại: các bước còn lại bị bỏ qua, nhưng `tearDown` vẫn chạy. Nhấn Ctrl+C lần thứ hai để ngừng chờ.

Bên trong shell `metro live`, command là `playbook <name>`, và Tab hoàn thành tên playbook:

``` plaintext
› playbook sign_up --speed fast
```

<div id="recording-playbooks"></div>

### Ghi hình

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.4.0+, nylo_support 7.33.0+" />

<!-- uncertain: new Recording section; no prior locale context for recording terms -->
Thêm `--record` để lưu video của lần chạy:

``` bash
metro live:run playbook sign_up --record
metro live:run playbook sign_up --record=docs/sign-up.gif --speed slow
metro live:run playbook sign_up --record --clean-status-bar --countdown 3
```

Bên trong shell `metro live`, gõ `playbook sign_up --record`.

Ghi hình cần ffmpeg (ví dụ `brew install ffmpeg`). Metro kiểm tra nó trước khi bất kỳ thứ gì chạy.

- **iOS Simulator** -- ghi bằng `xcrun simctl`.
- **Android emulator hoặc thiết bị** -- ghi bằng `adb screenrecord`, tự dừng sau 3 phút, hoặc bằng `scrcpy` nếu đã cài (không giới hạn, và ghi cả âm thanh trên Android 11+). Nếu có nhiều thiết bị Android được kết nối và Metro không biết thiết bị nào chạy ứng dụng, hãy đặt `ANDROID_SERIAL`.
- **iPhone thật** -- không thể ghi từ dòng lệnh. Hãy ghi bằng QuickTime hoặc Control Center và dùng `--countdown 3` để có thời gian bắt đầu.

Kết quả là một tệp `.mp4` (H.264, 60 fps ổn định) dài đúng bằng lần chạy, hoặc một tệp `.gif` rộng 480px, 15 fps. Mỗi bước trở thành một chú thích trong tệp `.vtt` cạnh tệp `.mp4` và một chapter bên trong nó. Chúng được tạo từ các bước của lần chạy dù playbook có hiển thị chú thích trên màn hình hay không. Truyền `--no-captions` để bỏ qua.

``` plaintext
· Recording iPhone 17
Playbook "sign_up" (natural)
  ✓   240ms  Tap "Create account"
  ...
✓ Playbook "sign_up" passed in 9.8s
· Saving the recording
✓ Saved build/playbooks/sign_up-iphone-17-20261014-101530.mp4 (11.2s)
· Step captions in build/playbooks/sign_up-iphone-17-20261014-101530.vtt (8 steps)
```

Playbook thất bại hoặc bị dừng vẫn được lưu, nên bản ghi cũng đóng vai trò như một báo cáo lỗi. Ctrl+C dừng playbook (`tearDown` vẫn chạy) và bản ghi vẫn được lưu.

`--clean-status-bar` giữ nguyên thanh trạng thái bạn đã đặt bằng `simctl status_bar` trên Simulator. Trên Android, nó dùng chế độ demo và tắt đi sau đó. Với nhiều ứng dụng (`--all`), mỗi thiết bị có tệp riêng chứa tên thiết bị.

Playbook mặc định ẩn ngón tay và chú thích. Với video demo, hãy đặt `showFinger` và `caption` trên playbook -- xem [Ngón tay và Chú thích](/docs/{{ $version }}/playbooks#finger-and-caption).

<div id="playbook-live-commands"></div>

### Tham chiếu Live Command

Các command phía sau là `playbooks.list`, `playbooks.run` và `playbooks.stop`. Một lần chạy trả về id của nó ngay lập tức và phát trực tiếp từng bước dưới dạng event `nylo.playbook`, nên nó có thể kéo dài hơn timeout của một command. `live:status` liệt kê các playbook của ứng dụng. Playbook xuất hiện từ Nylo Live protocol phiên bản 9. Hỗ trợ ghi hình -- `countdown` trong `playbooks.run` (kèm event `countdown` mỗi giây) và `simulator` (UDID của iOS Simulator) trong `status` -- xuất hiện ở protocol phiên bản 10.

<div id="live-commands"></div>

## Live Command

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Đăng ký các command của riêng bạn để chạy bên trong ứng dụng, cùng với các command dựng sẵn ở trên.

<div id="creating-a-live-command"></div>

### Tạo một Live Command

``` bash
metro make:command seed_cart --live
```

| Tùy chọn | Mô tả |
|--------|-------------|
| `--category`, `-c` | Danh mục cho command (mặc định: `app`) |
| `--description` | Mô tả một dòng hiển thị bởi `--help` của command |
| `--force`, `-f` | Tạo tệp ngay cả khi nó đã tồn tại |

Lệnh này tạo một `LiveCommand` trong `lib/app/commands/`, đánh dấu nó là `"type": "live"` trong `commands.json`, đăng ký nó trong `lib/bootstrap/live_commands.dart`, và nối `liveCommands: liveCommands` vào `nylo.configure(...)` trong `app_provider.dart`:

``` dart
class SeedCartCommand extends LiveCommand {
  @override
  CommandBuilder builder(CommandBuilder command) {
    command.addOption('count', abbr: 'c', defaultValue: '3');
    command.addFlag('open', help: 'Open the cart afterwards');
    return command;
  }

  @override
  Future<void> handle(CommandResult result) async {
    final int count = result.getInt('count') ?? 3;
    await CartItem.seed(count: count);
    success('Seeded $count cart items');
  }
}
```

`LiveCommand` dùng chung hình dạng `builder`/`handle` với các command riêng của Metro, nhưng `handle` chạy **bên trong ứng dụng**, nên nó có thể dùng `NyStorage`, `routeTo`, `Auth` và các model của bạn. `package:nylo_framework/live.dart` export `LiveCommand` cùng với `Seeder`, `StorageSnapshot`, `LiveException` và `LiveOutput`.

<div id="running-a-live-command"></div>

### Chạy một Live Command

Một live command mới cần hot restart trước khi ứng dụng đang chạy có thể chạy nó. Sau đó, từ terminal:

``` bash
metro app:seed_cart --count 5
```

<div id="seeders"></div>

## Seeder

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

Một `Seeder` đưa ứng dụng đang chạy của bạn vào một trạng thái đã biết -- đã đăng nhập, đã hoàn tất onboarding, có sẵn dữ liệu mẫu -- và đưa nó trở lại như cũ.

<div id="creating-a-seeder"></div>

### Tạo một Seeder

``` bash
metro make:seeder demo_user
```

| Tùy chọn | Mô tả |
|--------|-------------|
| `--description` | Mô tả một dòng, hiển thị khi `seed` liệt kê các seeder |
| `--force`, `-f` | Tạo tệp ngay cả khi nó đã tồn tại |

Lệnh này tạo một `Seeder` trong `lib/app/seeders/`, đăng ký nó trong `lib/bootstrap/seeders.dart`, và nối `seeders: seeders` vào `nylo.configure(...)`:

``` dart
class DemoUserSeeder extends Seeder {
  @override
  String get description => 'Jane Doe, signed in, onboarding done';

  @override
  Future<void> up() async {
    await Auth.authenticate(data: {'name': 'Jane Doe', 'token': 'demo'});
    await saveToStorage({'onboarding_complete': true});
    success('Signed in as Jane Doe');
  }

  @override
  Future<void> down() async {
    await restore();
  }
}
```

Mọi giá trị storage và Backpack mà `up()` thay đổi đều được ghi lại tự động, nên `down()` mặc định (`restore()`) sẽ đưa từng giá trị trở lại đúng như cũ, theo thứ tự ngược lại -- ngay cả qua một lần hot restart. Gọi `seed(otherSeeders)` từ bên trong một seeder để chạy các seeder khác như một phần của nó; các thay đổi của chúng cũng được ghi lại và rollback.

<div id="seeding-and-rolling-back"></div>

### Seeding và Rollback

Seeding, rollback và export chỉ chạy được bên trong shell `metro live`:

``` plaintext
› seed demo_user
Seeding demo_user
  + storage    SK_ONBOARDING  added
  ✓ Signed in as Jane Doe
✓ Seeded demo_user in 42ms
Undo with seed:rollback demo_user

› seed:rollback demo_user
Rolling back demo_user
  - storage    SK_ONBOARDING  removed
✓ Rolled back demo_user in 8ms
```

`seed` một mình sẽ liệt kê các seeder đã đăng ký. `--fresh` xóa storage và Backpack trước khi seeding, và `--restart` hot restart ứng dụng sau đó, để nó khởi động đã được seed sẵn:

``` plaintext
› seed demo_user --fresh --restart
```

<div id="storage-snapshots"></div>

## Storage Snapshot

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`export` lưu mọi thứ mà ứng dụng hiện đang giữ trong storage và Backpack -- dưới dạng một seeder, hoặc dưới dạng một tệp JSON có thể mang đi -- và `seed <file>` nạp nó trở lại.

<div id="exporting-a-snapshot"></div>

### Xuất một Snapshot

Chạy nó mà không có tên để xem trước những gì sẽ được ghi lại:

``` plaintext
› export
Storage on iPhone 15 Pro (2 values, 96 B)
KEY          TYPE    VALUE
SK_THEME     string  dark
SK_USER      model   {id: 1, name: Jane Doe}

Backpack (0 values)
Backpack is empty

Save it as a seeder with export <name>, or as a file with export --to <path>.json
```

Đặt tên cho nó để lưu dưới dạng một seeder trong `lib/app/seeders/` (giống như `make:seeder`, nhưng đã điền sẵn các giá trị đã ghi lại):

``` plaintext
› export pro_user
```

Hoặc ghi nó vào một tệp thay vào đó:

``` plaintext
› export pro_user --to snapshots/pro_user.json
```

| Tùy chọn | Mô tả |
|--------|-------------|
| `--to <path>` | Ghi snapshot vào tệp JSON này thay vì một seeder |
| `--description` | Mô tả một dòng cho seeder |
| `--only <keys>` | Các key cách nhau bằng dấu phẩy để export; `*` khớp với mọi thứ, ví dụ `--only SK_USER,onboarding_*` |
| `--except <keys>` | Các key cách nhau bằng dấu phẩy để bỏ qua, ví dụ `--except 'cache_*'` |
| `--backpack` / `--no-backpack` | Bao gồm các giá trị Backpack (mặc định: có bao gồm) |
| `--force`, `-f` | Thay thế một seeder hoặc tệp đã tồn tại |

Một `Model`, hoặc bất kỳ class nào có decoder đã đăng ký, được gắn nhãn theo tên class để nó quay lại thông qua decoder riêng của nó thay vì dưới dạng một map thuần túy. `export` cảnh báo khi snapshot chứa các key trông giống thông tin đăng nhập, và khi nó đủ lớn để bạn có thể muốn dùng `--except`.

<div id="loading-a-snapshot"></div>

### Nạp một Snapshot

``` plaintext
› seed snapshots/pro_user.json
```

Nạp một tệp được ghi lại theo cùng cách như một seeder, nên `seed:rollback <name>` (tên tệp, hoặc bất cứ tên nào `--as` đã đặt) cũng hoàn tác nó. Từ code, một seeder có thể áp dụng một snapshot đã ghi lại bằng `importSnapshot`:

``` dart
class ProUserSeeder extends Seeder {
  @override
  Future<void> up() async {
    await importSnapshot(snapshot);
  }

  static const Map<String, Object?> snapshot = {
    'storage': {
      'SK_USER': {'type': 'model', 'model': 'User', 'value': {'id': 1}},
    },
    'backpack': {},
  };
}
```

`export` ghi ra chính xác hình dạng này, nên một tệp được ghi bằng `--to` có thể được dán thẳng vào field `snapshot` của một seeder.

<div id="page-actions"></div>

## Page Actions

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.2.0+, nylo_support 7.30.0+" />

`path.actions` trên một trang cho bạn một `PageStateActions` để gọi trang đó từ bên ngoài nó, mà không cần tự xây dựng một lệnh gọi `stateAction(...)`:

``` dart
class ProductPage extends NyStatefulWidget {
  static RouteView path = ("/product", (_) => ProductPage());
  static final actions = path.actions;

  ProductPage({super.key}) : super(child: () => _ProductPageState());
}

ProductPage.actions.showToast("Added to bag");
ProductPage.actions.refreshPage();
```

Xem [Gọi các Action của một trang](/docs/{{ $version }}/state-management#page-actions-shortcut) để biết đầy đủ các action dựng sẵn và cách khai báo các action có kiểu riêng của bạn. Nylo Live đọc và dùng chính các action này: `metro live:run data` liệt kê những gì một trang trên màn hình cung cấp dưới `actions`, và `metro live:run state` gửi dữ liệu trực tiếp đến một trang theo tên state của nó.
