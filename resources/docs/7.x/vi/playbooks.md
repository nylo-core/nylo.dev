# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về Playbook")
- [Tạo Playbook](#creating-a-playbook "Tạo một playbook")
- [Các bước](#steps "Các bước mà một playbook có thể thực hiện")
- [Mục tiêu](#targets "Tìm các thành phần trên màn hình")
  - [Chuỗi thuần](#plain-strings "Cách một chuỗi thuần được khớp")
  - [Find](#find "Mục tiêu chính xác với Find")
- [Dữ liệu và Thiết lập](#data-and-setup "Data, setUp và tearDown")
- [Chạy Playbook khác](#playing-other-playbooks "Chạy một playbook từ playbook khác")
- [Tốc độ](#speed "PlaybookSpeed")
- [Ngón tay và Chú thích](#finger-and-caption "Ngón tay và chú thích của bước")
- [Đăng ký Playbook](#registering-playbooks "Đăng ký playbook với Nylo")
- [Chạy một Playbook](#running-a-playbook "Chạy một playbook")
  - [Từ Metro](#running-from-metro "Chạy từ Metro")
  - [Khi khởi động](#running-on-boot "Chạy khi khởi động")
  - [Từ Dart](#running-from-dart "Chạy từ Dart")
- [Báo cáo](#reports "PlaybookReport")
- [Bản build Release](#release-builds "Playbook trong bản build release")
- [Kiểm thử](#testing "Kiểm thử một playbook")

<div id="introduction"></div>

## Giới thiệu

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.3.0+, nylo_support 7.32.0+" />

**Playbook** là một class liệt kê các thao tác chạm, gõ, cuộn và chuyển trang của một hành trình người dùng. Ứng dụng đang chạy của bạn tự thực hiện chúng, và có thể hiển thị ngón tay cùng chú thích cho từng bước -- tiện cho demo, quay màn hình và kiểm tra một hành trình từ đầu đến cuối.

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import 'package:nylo_framework/playbook.dart';

class SignUpPlaybook extends Playbook {
  @override
  String get description => 'New user signs up and lands on the dashboard';

  @override
  Map<String, dynamic> get defaults => {'email': 'demo@nylo.dev'};

  @override
  List<PlaybookStep> get steps => [
    visit(SignUpPage.path),
    type('Email', data['email']),
    type('Password', 'secret123', secret: true),
    tap('Create account'),
    waitForPage(DashboardPage.path),
    see('Welcome'),
  ];
}
```

Import `package:nylo_framework/playbook.dart` ở bất cứ nơi nào bạn viết hoặc đăng ký playbook.

<div id="creating-a-playbook"></div>

## Tạo Playbook

Sử dụng Metro để tạo một playbook:

``` bash
metro make:playbook sign_up
```

Lệnh này tạo `lib/app/playbooks/sign_up_playbook.dart` và đăng ký nó với ứng dụng của bạn. Xem [Tạo Playbook](/docs/{{ $version }}/metro#make-playbook) để biết các tùy chọn của lệnh.

<div id="steps"></div>

## Các bước

`steps` của một playbook được xây dựng bằng các động từ mà nó kế thừa:

| Bước | Mô tả |
|------|-------------|
| `tap(target)` | Chạm vào một mục tiêu |
| `longPress(target)` | Nhấn và giữ một mục tiêu |
| `type(target, text)` | Gõ vào một trường. Xóa nội dung trước trừ khi `clear: false`; `submit: true` nhấn nút hành động của bàn phím; `secret: true` ẩn văn bản trong chú thích và báo cáo |
| `scrollTo(target)` | Cuộn cho đến khi mục tiêu xuất hiện trên màn hình. Truyền `within:` để cuộn một widget cuộn cụ thể |
| `see(target)` | Kiểm tra một mục tiêu có trên màn hình, chờ nó xuất hiện |
| `dontSee(target)` | Kiểm tra một mục tiêu không có trên màn hình, chờ nó biến mất |
| `waitFor(target)` | Chờ một mục tiêu, tối đa `timeout` (mặc định 10 giây) |
| `waitForPage(path)` | Chờ trang tại một route path hoặc `RouteView` (ví dụ: `HomePage.path`) |
| `visit(path)` | Mở một trang theo route path hoặc `RouteView`, với `data:` tùy chọn |
| `back()` | Quay lại một trang |
| `pause(seconds: 1)` | Đứng yên, ví dụ để demo có nhịp nghỉ |
| `step(title, action)` | Chạy bất kỳ đoạn code nào với cùng các động từ, thông qua `PlaybookActor` |
| `scene(title, steps)` | Nhóm các bước dưới một tiêu đề; chú thích của chúng bắt đầu bằng tiêu đề đó và kết quả được lồng trong báo cáo |
| `play(playbook)` | Chạy một playbook khác -- xem [Chạy Playbook khác](#playing-other-playbooks) |

``` dart
@override
List<PlaybookStep> get steps => [
  scene('Add to bag', [
    scrollTo('Running shoes'),
    tap('Running shoes'),
    tap('Add to bag'),
  ]),
  step('Clear the cache', (actor) async {
    await Backpack.instance.delete('cart_cache');
  }),
  see('1 item'),
];
```

Các bước chạy theo thứ tự và dừng ở lần thất bại đầu tiên. Các bước còn lại được báo cáo là bị bỏ qua.

<div id="targets"></div>

## Mục tiêu

Hầu hết các bước nhận một mục tiêu: thứ cần chạm, gõ vào hoặc tìm kiếm.

<div id="plain-strings"></div>

### Chuỗi thuần

Một chuỗi thuần được khớp theo cách một người đọc màn hình. Nó thử lần lượt theo thứ tự sau:

1. Văn bản hiển thị
2. Hint hoặc label của một trường
3. Tooltip
4. Semantics label
5. Một `ValueKey`
6. Văn bản chứa chuỗi đó

``` dart
tap('Create account'),
type('Email', 'demo@nylo.dev'),
```

Một `Key`, `Type` hoặc `IconData` cũng có thể được truyền thẳng vào một bước.

<div id="find"></div>

### Find

Sử dụng `Find` khi chuỗi thuần không đủ chính xác:

``` dart
tap(Find.icon(Icons.add)),
tap(Find.type<FloatingActionButton>()),
tap(Find.key(const ValueKey('checkout'))),
type(Find.field('email'), 'demo@nylo.dev'),
tap(Find.text('Delete').inside(Find.type<AlertDialog>())),
tap(Find.text('Add').at(1)),
```

| Finder | Khớp với |
|--------|---------|
| `Find.text(text)` | Văn bản hiển thị |
| `Find.textContaining(text)` | Văn bản chứa chuỗi đó |
| `Find.icon(icon)` | Một icon |
| `Find.type<T>()` | Một widget kiểu `T` |
| `Find.key(key)` | Một widget có key đó |
| `Find.field(name)` | Một trường `NyForm` theo tên |
| `Find.input(text)` | Một trường văn bản theo hint, label hoặc placeholder |
| `Find.tooltip(message)` | Một tooltip |
| `Find.semantics(label)` | Một semantics label |
| `Find.where(test)` | Các widget vượt qua bài kiểm tra của bạn |

Thu hẹp bất kỳ finder nào bằng `.inside(ancestor)`, `.at(index)` (đếm từ 0), `.first` hoặc `.last`.

Một thao tác chạm sẽ bị từ chối khi có thứ không liên quan, như lớp chắn của dialog, che mục tiêu.

<div id="data-and-setup"></div>

## Dữ liệu và Thiết lập

`defaults` chứa dữ liệu ban đầu của các bước. Đọc nó qua `data` bên trong `steps`. `setUp` chạy trước các bước và `tearDown` chạy sau chúng, kể cả khi một bước thất bại:

``` dart
class CheckoutPlaybook extends Playbook {
  @override
  Map<String, dynamic> get defaults => {'coupon': 'SAVE10'};

  @override
  Future<void> setUp(PlaybookActor actor) async {
    await Auth.logout();
    await seed(['demo_user']);
  }

  @override
  Future<void> tearDown(PlaybookActor actor) async {
    await Auth.logout();
  }

  @override
  List<PlaybookStep> get steps => [
    tap('Checkout'),
    type('Coupon', data['coupon']),
  ];
}
```

`seed([...])` chạy các [Seeder](/docs/{{ $version }}/metro-live#seeders) (instance hoặc tên đã đăng ký) và ghi lại chúng giống như `seed` trong `metro live`.

<div id="playing-other-playbooks"></div>

## Chạy Playbook khác

`play()` chạy đầy đủ một playbook khác, với `setUp` và `tearDown` riêng của nó. Truyền một instance hoặc tên mà nó được đăng ký:

``` dart
@override
List<PlaybookStep> get steps => [
  play(SignUpPlaybook()),
  play('add_to_bag', data: {'item': 'Running shoes'}),
  tap('Checkout'),
];
```

- **Data** được gộp theo thứ tự: `defaults` của playbook con, rồi `data` của playbook cha, rồi `data:` truyền vào `play`.
- **Tốc độ** được kế thừa từ playbook cha trừ khi bạn truyền `speed:`.
- **Kết quả** được lồng dưới bước đó trong báo cáo.
- **Playbook con thất bại** khiến bước thất bại.
- **Vòng lặp** bị từ chối, và các playbook lồng nhau tối đa 8 cấp.

<div id="speed"></div>

## Tốc độ

`PlaybookSpeed` đặt nhịp độ của mọi lần chờ:

| Tốc độ | Mô tả |
|-------|-------------|
| `PlaybookSpeed.instant` | Không chờ thêm ngoài những gì việc cuộn cần. Dành cho test |
| `PlaybookSpeed.fast` | Nhanh, nhưng vẫn dễ theo dõi |
| `PlaybookSpeed.natural` | Nhịp độ của một người dùng ứng dụng. Mặc định |
| `PlaybookSpeed.slow` | Đủ chậm để thuyết minh |

Co giãn bất kỳ tốc độ nào bằng `.times(n)`:

``` dart
@override
PlaybookSpeed get speed => PlaybookSpeed.natural.times(2); // chậm gấp đôi
```

Tốc độ truyền vào `run()`, `--speed` từ Metro, hoặc tốc độ của playbook chạy playbook này sẽ được ưu tiên hơn tốc độ của chính playbook.

<div id="finger-and-caption"></div>

## Ngón tay và Chú thích

Ngón tay và chú thích các bước mặc định bị tắt. Ghi đè `showFinger` và `caption` để bật chúng:

``` dart
@override
bool get showFinger => true;

@override
PlaybookCaption get caption => PlaybookCaption.bottom; // hoặc .top
```

`showFinger` mặc định là `false` và `caption` là `PlaybookCaption.hidden`; `PlaybookCaption` là `top`, `bottom` hoặc `hidden`. Các thao tác chạm và vuốt diễn ra trong cả hai trường hợp. Playbook được playbook này chạy bằng `play()` dùng thiết lập riêng của nó, và thiết lập của playbook này được khôi phục khi nó kết thúc. Để ẩn cả hai cho một lần chạy, dùng `--no-overlay` từ Metro hoặc `run(overlay: false)` từ Dart.

Trước nylo_support 7.33.0 ({{ config('app.name') }} 7.4.0), ngón tay và chú thích phía dưới được bật mặc định. Thêm các ghi đè ở trên để giữ giao diện đó.

<div id="registering-playbooks"></div>

## Đăng ký Playbook

Đăng ký các playbook của bạn để Metro và `play('name')` có thể tìm thấy chúng:

``` dart
nylo.addPlaybooks({
  'sign_up': SignUpPlaybook.new,
});
```

`nylo.getPlaybooks()` trả về những gì đã đăng ký. Bạn cũng có thể truyền chúng khi cấu hình Nylo trong `AppProvider`:

``` dart
await nylo.configure(
  // ...
  playbooks: playbooks,
);
```

`metro make:playbook` làm điều này cho bạn. Nó giữ registry trong `lib/bootstrap/playbooks.dart`, thêm mỗi playbook mới vào đó, và truyền `playbooks` vào `nylo.configure(...)`. Registry là một map rỗng trong các bản build release:

``` dart
final Map<String, Playbook Function()> playbooks = kReleaseMode ? const {} : {
  'sign_up': SignUpPlaybook.new,
};
```

Một playbook đã đăng ký chạy dưới key của nó. Playbook chưa đăng ký chạy dưới `name` của nó, hoặc tên class ở dạng snake case (`SignUpPlaybook` là `sign_up`).

<div id="running-a-playbook"></div>

## Chạy một Playbook

<div id="running-from-metro"></div>

### Từ Metro

Khi ứng dụng của bạn đang chạy ở chế độ debug:

``` bash
metro live:run playbook sign_up --speed fast
```

Bên trong `metro live`, gõ `playbook sign_up`. Xem [Metro Live](/docs/{{ $version }}/metro-live#playbooks) để biết mọi tùy chọn. Thêm `--record` để lưu video của lần chạy; xem [Ghi hình](/docs/{{ $version }}/metro-live#recording-playbooks).

<div id="running-on-boot"></div>

### Khi khởi động

Thêm các key này vào tệp `.env` để chạy playbook ngay khi ứng dụng xuất hiện trên màn hình:

``` bash
PLAYBOOK_ON_BOOT="sign_up,add_to_bag"
PLAYBOOK_SPEED="fast"
```

`PLAYBOOK_ON_BOOT` là danh sách tên playbook phân tách bằng dấu phẩy và `PLAYBOOK_SPEED` là một trong `instant`, `fast`, `natural` hoặc `slow`. Cả hai chỉ áp dụng cho bản build debug và profile.

<div id="running-from-dart"></div>

### Từ Dart

``` dart
final PlaybookReport report = await SignUpPlaybook().run(
  speed: PlaybookSpeed.fast,
  data: {'email': 'jane@example.com'},
);

if (!report.passed) {
  print(report);
}
```

`run()` nhận `speed`, `data`, `clock`, `overlay` (mặc định `true`) và `onEvent`. Một bước thất bại không ném lỗi -- hãy kiểm tra `report.passed`. Chỉ một playbook có thể chạy tại một thời điểm; dùng `play()` để chạy playbook này bên trong playbook khác.

Dừng một playbook đang chạy bằng `Playbook.stop()`. Các bước còn lại bị bỏ qua nhưng `tearDown` vẫn chạy. `Playbook.isRunning` cho bạn biết có playbook nào đang chạy hay không.

<div id="reports"></div>

## Báo cáo

`run()` trả về một `PlaybookReport` với `name`, `speed`, thời gian `elapsed` của playbook, việc nó có bị `stopped` hay không, và danh sách `steps` gồm các `PlaybookStepResult` (mỗi cái có `title`, `status`, `elapsed`, `error` và `steps` lồng nhau). `report.passed` là true khi mọi bước đều đạt, và in một báo cáo sẽ giống đầu ra của Metro. `report.toJson()` cho cùng dữ liệu dưới dạng JSON.

<div id="release-builds"></div>

## Bản build Release

Playbook không bao giờ chạy trong bản build release, nên một hành trình không thể điều khiển ứng dụng của người dùng:

- `Playbook.run()` ném `UnsupportedError`.
- `addPlaybooks` bị bỏ qua.
- Registry `lib/bootstrap/playbooks.dart` do Metro tạo ra là rỗng.

`Playbook.isAvailable` cho bạn biết bản build hiện tại có thể chạy playbook hay không.

<div id="testing"></div>

## Kiểm thử

Chạy một playbook trong widget test bằng `NyTesterClock`, rồi xác nhận báo cáo của nó bằng `expectPlaybookPassed`:

``` dart
testWidgets('signs up', (tester) async {
  await tester.pumpWidget(const MyApp());
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Xem [Kiểm thử Playbook](/docs/{{ $version }}/testing#testing-playbooks) cho integration test.
