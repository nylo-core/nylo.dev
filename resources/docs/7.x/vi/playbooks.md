# Playbooks
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu về Playbook")
- [Tạo Playbook](#creating-a-playbook "Tạo một playbook")
- [Ghi lại Playbook](#recording-a-playbook "Ghi lại một playbook từ ứng dụng đang chạy")
- [Các bước](#steps "Các bước mà một playbook có thể thực hiện")
- [Mục tiêu](#targets "Tìm các thành phần trên màn hình")
  - [Chuỗi thuần](#plain-strings "Cách một chuỗi thuần được khớp")
  - [Find](#find "Mục tiêu chính xác với Find")
- [Dữ liệu và Thiết lập](#data-and-setup "Data, setUp và tearDown")
- [Chạy Playbook khác](#playing-other-playbooks "Chạy một playbook từ playbook khác")
- [Tốc độ](#speed "PlaybookSpeed")
- [Ngón tay và Chú thích](#finger-and-caption "Ngón tay và chú thích của bước")
- [Ảnh chụp màn hình](#screenshots "Chụp ảnh màn hình trong một playbook")
  - [Locale và Theme](#screenshots-locales-and-themes "Chụp một màn hình ở mọi locale và theme")
  - [Thu thập từ code](#collecting-screenshots "Thu thập ảnh chụp bằng onScreenshot")
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

Để bắt đầu từ mọi trang mà router của bạn thêm vào, hãy truyền `--from-routes`:

``` bash
metro make:playbook tour --from-routes
```

Metro viết một `visit()` và một [`screenshot()`](#screenshots) cho mỗi trang, nên một lần chạy chụp được toàn bộ ứng dụng của bạn.

<div id="recording-a-playbook"></div>

## Ghi lại Playbook

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.36.0+" />

Thay vì viết các bước, hãy ghi lại chúng. Khi ứng dụng đang chạy ở chế độ debug, mở `metro live` và gõ:

``` plaintext
› record sign_up
```

Sau đó hãy dùng ứng dụng. Mỗi lần chạm, nhấn giữ, nhập vào trường, cuộn và chuyển trang được viết thành một bước ngay khi nó xảy ra, và được in ra trong khi bạn thao tác:

``` plaintext
● Recording sign_up on iPhone 17. Use the app; each step is written as you go.
· Enter finishes · a: the next tap becomes see() · d: dontSee() · s <title>: start a scene · u: undo the last step · p: pause or resume · Ctrl+C discards
  1  visit(HomePage.path)
  2  tap('Get started')
  3  waitForPage(SignUpPage.path)
  4  type('Email', data['email'])
  5  type('Password', data['password'], secret: true, submit: true)
  6  tap('Create account')
  7  waitForPage(DashboardPage.path)
  8  see('Welcome')
✓ Recorded 8 steps in 41s
✓ Registered as 'sign_up' in lib/bootstrap/playbooks.dart
Play it back now at fast speed? It takes a hot restart. [Y/n]
```

Nhấn Enter để kết thúc. Playbook được ghi vào `lib/app/playbooks/sign_up_playbook.dart`, bố cục giống như một playbook do `make:playbook` tạo, và được đăng ký, nên nó chạy được ngay khi ứng dụng khởi động lại. `metro make:playbook sign_up --record` làm điều tương tự từ terminal: nó mở `metro live` và bắt đầu ghi ngay lập tức.

Trong khi ghi, gõ một phím rồi nhấn Enter:

| Phím | Mô tả |
|-----|-------------|
| `a` | Lần chạm tiếp theo được viết thành `see(...)` thay vì một thao tác chạm |
| `d` | Lần chạm tiếp theo được viết thành `dontSee(...)` |
| `s <title>` | Bắt đầu một `scene` với tiêu đề đó; các bước tiếp theo được lồng bên trong nó |
| `u` | Xóa bước cuối cùng |
| `p` | Tạm dừng ghi, ví dụ để đăng nhập bằng thông tin thật; `p` lần nữa để tiếp tục |
| Enter | Kết thúc và ghi playbook |
| Ctrl+C | Hủy bản ghi |

Các mục tiêu được đặt tên theo cách trình chạy đọc chúng, ưu tiên văn bản hiển thị, và mỗi tên được kiểm tra với ứng dụng đang chạy trước khi được ghi, nên một bước đã ghi có thể chạy lại. Khi một chuỗi thuần khớp với nhiều hơn một widget, bước đó thu hẹp nó bằng `Find`: `tap(Find.text('Add').at(1))`. Một nút icon không có văn bản hoặc tooltip trở thành `tap(Find.icon(Icons.add))`. Một widget không có gì đặt tên được ghi thành `tap(Find.type<GestureDetector>().at(3))` dưới một comment `// TODO` kèm tệp và dòng nơi nó được tạo, và Metro liệt kê nó khi bản ghi kết thúc. Hãy gán cho nó một `Key`, rồi dùng key đó thay thế.

- **Nhập liệu** trở thành một bước `type` đọc một placeholder `data[...]`, với giá trị đã nhập trong `defaults`. Một trường được để lại với phím hành động của bàn phím sẽ có `submit: true`. Một trường bị che sẽ có `secret: true`, giá trị của nó trong `defaults` và một cảnh báo phải đổi nó trước khi bạn commit. Truyền `--no-data` để ghi các giá trị đúng như đã gõ.
- **Chuyển trang** sau một lần chạm trở thành `waitForPage(SignUpPage.path)` khi một trong các trang của bạn khai báo route đó, hoặc `waitForPage('/sign-up')` nếu không. Quay lại trở thành `back()`.
- **Cuộn** được viết thành `scrollTo(...)` trên thứ bạn chạm tiếp theo, và chỉ khi việc cuộn đưa nó vào cây widget. Trình chạy tự hiển thị mọi thứ khác.
- **Các thao tác vuốt không phải cuộn** -- một `Dismissible`, một `PageView`, một `Slider` -- chưa có động từ playbook nào, nên chúng được để lại thành một comment `// TODO` với điểm bắt đầu và điểm kết thúc.

Việc ghi cần bản build debug và hoạt động trên simulator, emulator và thiết bị thật như nhau. Các thao tác chạm của chính playbook không bao giờ được ghi. Xem [Metro Live](/docs/{{ $version }}/metro-live#recording-a-journey) để biết các tùy chọn của lệnh.

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
| `screenshot(name)` | Chụp màn hình -- xem [Ảnh chụp màn hình](#screenshots) |
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
| `Find.icon(icon, label:)` | Một icon. `label:` đặt tên cho nó trong chú thích các bước |
| `Find.type<T>()` | Một widget kiểu `T` |
| `Find.key(key)` | Một widget có key đó |
| `Find.field(name)` | Một trường `NyForm` theo tên |
| `Find.input(text)` | Một trường văn bản theo hint, label hoặc placeholder |
| `Find.tooltip(message)` | Một tooltip |
| `Find.semantics(label)` | Một semantics label |
| `Find.where(test)` | Các widget vượt qua bài kiểm tra của bạn |

Gán cho một icon một `label` và chú thích bước của nó sẽ đọc là `Tap the bag icon` thay vì `Tap icon f37d`:

``` dart
tap(Find.icon(Icons.shopping_bag_outlined, label: 'bag')),
```

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

<div id="screenshots"></div>

## Ảnh chụp màn hình

<x-doc-strip label="Yêu cầu" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

Thêm một bước `screenshot` ở bất cứ nơi nào màn hình đáng được giữ lại:

``` dart
@override
List<PlaybookStep> get steps => [
  visit(ShopPage.path),
  screenshot('shop'),
  tap('Running shoes'),
  tap('Add to bag'),
  screenshot('cart', caption: 'Your bag, ready to go'),
];
```

`metro live:run playbook <name>` lưu mỗi ảnh thành `screenshots/<playbook>/<name>.png`, không cần cờ nào. Một lần chạy từ code thu thập chúng bằng [`onScreenshot`](#collecting-screenshots), và mọi lần chạy khác, như `PLAYBOOK_ON_BOOT`, chỉ chờ màn hình ổn định. Một tên bắt đầu bằng chữ cái hoặc chữ số và dùng chữ cái, chữ số, `-`, `_` và `.`.

| Tham số | Mô tả |
|-----------|-------------|
| `name` | Tên mà ảnh chụp được lưu dưới đó |
| `locales` | Chụp một lần ở mỗi mã ngôn ngữ này. `Screenshot.allLocales` là mọi ngôn ngữ mà ứng dụng đóng gói |
| `themes` | Chụp một lần ở mỗi id theme này. `Screenshot.allThemes` là mọi theme đã đăng ký |
| `caption` | Văn bản phía trên màn hình khi lần chạy đóng khung ảnh chụp của nó, hoặc một khóa dịch |
| `native` | Chụp bằng Metro, trên iOS Simulator và Android (mặc định `true`) |

Trên iOS Simulator và Android, Metro chụp toàn bộ màn hình thiết bị, nên thanh trạng thái và bàn phím đều có trong ảnh, và ngón tay cùng chú thích được ẩn trong lúc đó. Thêm `--clean-status-bar` vào lần chạy để có 9:41 với đầy đủ sóng và pin. Ở nơi khác, và trong các lần chạy `--store` và `--frame`, ứng dụng tự chụp màn hình của nó. Truyền `native: false` để luôn dùng cách chụp của ứng dụng:

``` dart
screenshot('checkout', native: false),
```

Để lưu màn hình của mọi bước, hãy chạy `metro live:run playbook <name> --screenshots`. Xem [Metro Live](/docs/{{ $version }}/metro-live#playbook-screenshots) để biết các tùy chọn đặt kích thước ảnh chụp cho App Store và Google Play, và [Ảnh chụp màn hình](/docs/{{ $version }}/screenshots) để biết lớp `Screenshot` đứng sau chúng.

<div id="screenshots-locales-and-themes"></div>

### Locale và Theme

Cho một bước `screenshot` `locales`, `themes` hoặc cả hai để chụp cùng một màn hình một lần ở mỗi giá trị:

``` dart
screenshot('cart', locales: ['en', 'es'], themes: ['light', 'dark']),
screenshot('home', locales: Screenshot.allLocales),
```

Ứng dụng chuyển đổi tại chỗ, giữ nguyên trang và state của nó, và chuyển lại trước bước tiếp theo. Mỗi lần chụp được lưu trong một thư mục đặt tên theo locale và theme của nó:

``` plaintext
screenshots/sign_up/en/cart.png
screenshots/sign_up/en-dark/cart.png
screenshots/sign_up/es/cart.png
screenshots/sign_up/es-dark/cart.png
```

Để chạy cả một playbook ở mọi ngôn ngữ và theme, hãy truyền `--locales` và `--themes` vào `metro live:run playbook`.

<div id="collecting-screenshots"></div>

### Thu thập từ code

`run()` nhận `onScreenshot` để nhận các ảnh chụp của một lần chạy dưới dạng `PlaybookShot`, tiện cho một test hoặc công cụ của riêng bạn:

``` dart
final List<PlaybookShot> shots = [];

final PlaybookReport report = await ShopPlaybook().run(
  onScreenshot: shots.add,
  screenshots: PlaybookScreenshots.marked,
  pixelRatio: 2,
);

for (final PlaybookShot shot in shots) {
  print('${shot.name} ${shot.locale} ${shot.theme}');
}
```

`screenshots` chọn những màn hình nào được chụp:

| Chế độ | Chụp |
|------|----------|
| `PlaybookScreenshots.off` | Không gì cả |
| `PlaybookScreenshots.failures` | Chỉ màn hình tại một bước thất bại |
| `PlaybookScreenshots.marked` | Các bước `screenshot()`, và các bước thất bại |
| `PlaybookScreenshots.auto` | Các bước `screenshot()` của một playbook có chúng, và mọi bước của một playbook không có. Cả các bước thất bại. Mặc định |
| `PlaybookScreenshots.all` | Mọi bước, các bước `screenshot()`, và các bước thất bại |

`pixelRatio` đặt kích thước ảnh chụp so với kích thước của thiết bị (mặc định `1.0`). Mỗi `PlaybookShot` có một `screenshot` để [lưu](/docs/{{ $version }}/screenshots#saving-a-screenshot) hoặc đọc `bytes`, một `kind`, cùng `name`, `locale` và `theme` mà nó được chụp.

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

Bên trong `metro live`, gõ `playbook sign_up`. Xem [Metro Live](/docs/{{ $version }}/metro-live#playbooks) để biết mọi tùy chọn. Thêm `--record` để lưu video của lần chạy; xem [Ghi hình](/docs/{{ $version }}/metro-live#recording-playbooks). Nếu lần chạy ngừng chờ sau 20 giây, nhiều khả năng ứng dụng đã được khởi động bằng `flutter run --no-dds`; xem [Metro Live](/docs/{{ $version }}/metro-live#playbooks).

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

`run()` nhận `speed`, `data`, `clock`, `overlay` (mặc định `true`), `onEvent`, cùng `onScreenshot`, `screenshots` và `pixelRatio` để [thu thập ảnh chụp](#collecting-screenshots). Một bước thất bại không ném lỗi -- hãy kiểm tra `report.passed`. Chỉ một playbook có thể chạy tại một thời điểm; dùng `play()` để chạy playbook này bên trong playbook khác.

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
nyWidgetTest('signs up', (tester) async {
  await tester.visit(SignUpPage.path);
  final report = await SignUpPlaybook().run(clock: NyTesterClock(tester));
  expectPlaybookPassed(report);
});
```

Xem [Kiểm thử Playbook](/docs/{{ $version }}/testing#testing-playbooks) cho integration test.
