# Navigation Hub

---

<a name="section-1"></a>
- [Giới thiệu](#introduction "Giới thiệu")
- [Cách sử dụng cơ bản](#basic-usage "Cách sử dụng cơ bản")
  - [Tạo Navigation Hub](#creating-a-navigation-hub "Tạo Navigation Hub")
  - [Tạo Navigation Tab](#creating-navigation-tabs "Tạo Navigation Tab")
  - [Điều hướng dưới](#bottom-navigation "Điều hướng dưới")
    - [Tùy chỉnh Nav Bar Builder](#custom-nav-bar-builder "Tùy chỉnh Nav Bar Builder")
  - [Điều hướng trên](#top-navigation "Điều hướng trên")
  - [Điều hướng hành trình](#journey-navigation "Điều hướng hành trình")
    - [Kiểu tiến trình](#journey-progress-styles "Kiểu tiến trình")
    - [JourneyState](#journey-state "JourneyState")
    - [Phương thức helper JourneyState](#journey-state-helper-methods "Phương thức helper JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [Điều hướng trong tab](#navigating-within-a-tab "Điều hướng trong tab")
- [Tab](#tabs "Tab")
  - [Thêm huy hiệu vào tab](#adding-badges-to-tabs "Thêm huy hiệu vào tab")
  - [Thêm cảnh báo vào tab](#adding-alerts-to-tabs "Thêm cảnh báo vào tab")
- [Chỉ mục ban đầu](#initial-index "Chỉ mục ban đầu")
- [Duy trì trạng thái](#maintaining-state "Duy trì trạng thái")
- [onTap](#on-tap "onTap")
- [Hành động trạng thái](#state-actions "Hành động trạng thái")
- [Kiểu tải](#loading-style "Kiểu tải")

<div id="introduction"></div>

## Giới thiệu

Navigation Hub là nơi tập trung để bạn có thể **quản lý** điều hướng cho tất cả widget của mình.
Ngay từ đầu, bạn có thể tạo bố cục điều hướng dưới, trên và hành trình trong vài giây.

Hãy **tưởng tượng** bạn có một ứng dụng và muốn thêm thanh điều hướng dưới cùng và cho phép người dùng chuyển đổi giữa các tab khác nhau trong ứng dụng.

Bạn có thể sử dụng Navigation Hub để xây dựng điều này.

Hãy cùng tìm hiểu cách sử dụng Navigation Hub trong ứng dụng của bạn.

<div id="basic-usage"></div>

## Cách sử dụng cơ bản

Bạn có thể tạo Navigation Hub bằng lệnh sau.

``` bash
metro make:navigation_hub base
```

Lệnh sẽ hướng dẫn bạn qua quá trình thiết lập tương tác:

1. **Chọn loại bố cục** - Chọn giữa `navigation_tabs` (điều hướng dưới) hoặc `journey_states` (luồng tuần tự).
2. **Nhập tên tab/trạng thái** - Cung cấp tên phân cách bằng dấu phẩy cho các tab hoặc trạng thái hành trình.

Lệnh này sẽ tạo các tệp trong thư mục `resources/pages/navigation_hubs/base/`:
- `base_navigation_hub.dart` - Widget hub chính
- `tabs/` hoặc `states/` - Chứa widget con cho mỗi tab hoặc trạng thái hành trình

Đây là giao diện của Navigation Hub được tạo:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// Layout builder
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// Should the state be maintained
  @override
  bool get maintainState => true;

  /// The initial index
  @override
  int get initialIndex => 0;

  /// Navigation pages
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// Handle the tap event
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

Bạn có thể thấy Navigation Hub có **hai** tab, Home và Settings.

Phương thức `layout` trả về loại bố cục cho hub. Nó nhận `BuildContext` để bạn có thể truy cập dữ liệu theme và media query khi cấu hình bố cục.

Bạn có thể tạo thêm tab bằng cách thêm `NavigationTab` vào Navigation Hub.

Đầu tiên, bạn cần tạo widget mới bằng Metro.

``` bash
metro make:stateful_widget news_tab
```

Bạn cũng có thể tạo nhiều widget cùng lúc.

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

Sau đó, bạn có thể thêm widget mới vào Navigation Hub.

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

Để sử dụng Navigation Hub, thêm nó vào router như route ban đầu:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// or navigate to the Navigation Hub from anywhere in your app

routeTo(BaseNavigationHub.path);
```

Có **nhiều hơn** những gì bạn có thể làm với Navigation Hub, hãy cùng khám phá một số tính năng.

<div id="bottom-navigation"></div>

### Điều hướng dưới

Bạn có thể đặt bố cục thành thanh điều hướng dưới bằng cách trả về `NavigationHubLayout.bottomNav` từ phương thức `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

Bạn có thể tùy chỉnh thanh điều hướng dưới bằng cách đặt các thuộc tính như sau:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

Bạn có thể áp dụng kiểu preset cho thanh điều hướng dưới bằng tham số `style`.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // Default Flutter material style
);
```

<div id="custom-nav-bar-builder"></div>

### Tùy chỉnh Nav Bar Builder

Để kiểm soát hoàn toàn thanh điều hướng, bạn có thể sử dụng tham số `navBarBuilder`.

Điều này cho phép bạn xây dựng bất kỳ widget tùy chỉnh nào trong khi vẫn nhận được dữ liệu điều hướng.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

Đối tượng `NavBarData` chứa:

| Thuộc tính | Kiểu | Mô tả |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | Các item thanh điều hướng |
| `currentIndex` | `int` | Chỉ mục đang được chọn |
| `onTap` | `ValueChanged<int>` | Callback khi nhấn vào tab |

Đây là ví dụ về nav bar kính tùy chỉnh hoàn toàn:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **Lưu ý:** Khi sử dụng `navBarBuilder`, tham số `style` sẽ bị bỏ qua.

<div id="top-navigation"></div>

### Điều hướng trên

Bạn có thể thay đổi bố cục thành thanh điều hướng trên bằng cách trả về `NavigationHubLayout.topNav` từ phương thức `layout`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

Bạn có thể tùy chỉnh thanh điều hướng trên bằng cách đặt các thuộc tính như sau:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### Điều hướng hành trình

Bạn có thể thay đổi bố cục thành điều hướng hành trình bằng cách trả về `NavigationHubLayout.journey` từ phương thức `layout`.

Đây là lựa chọn tuyệt vời cho luồng onboarding hoặc form nhiều bước.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

Bạn cũng có thể đặt `backgroundGradient` cho bố cục hành trình:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **Lưu ý:** Khi `backgroundGradient` được đặt, nó sẽ ưu tiên hơn `backgroundColor`.

Nếu bạn muốn sử dụng bố cục điều hướng hành trình, **widget** của bạn nên sử dụng `JourneyState` vì nó chứa nhiều phương thức helper giúp bạn quản lý hành trình.

Bạn có thể tạo toàn bộ hành trình bằng lệnh `make:navigation_hub` với bố cục `journey_states`:

``` bash
metro make:navigation_hub onboarding
# Select: journey_states
# Enter: welcome, personal_info, add_photos
```

Lệnh này sẽ tạo hub và tất cả widget trạng thái hành trình trong `resources/pages/navigation_hubs/onboarding/states/`.

Hoặc bạn có thể tạo widget hành trình riêng lẻ bằng:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Sau đó bạn có thể thêm widget mới vào Navigation Hub.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### Kiểu tiến trình

Bạn có thể tùy chỉnh kiểu chỉ báo tiến trình bằng class `JourneyProgressStyle`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

Bạn có thể sử dụng các chỉ báo tiến trình sau:

- `JourneyProgressIndicator.none()`: Không hiển thị gì - hữu ích để ẩn chỉ báo trên tab cụ thể.
- `JourneyProgressIndicator.linear()`: Thanh tiến trình tuyến tính.
- `JourneyProgressIndicator.dots()`: Chỉ báo tiến trình dạng chấm.
- `JourneyProgressIndicator.numbered()`: Chỉ báo tiến trình dạng đánh số.
- `JourneyProgressIndicator.segments()`: Thanh tiến trình dạng phân đoạn.
- `JourneyProgressIndicator.circular()`: Chỉ báo tiến trình dạng vòng tròn.
- `JourneyProgressIndicator.timeline()`: Chỉ báo tiến trình dạng dòng thời gian.
- `JourneyProgressIndicator.custom()`: Chỉ báo tiến trình tùy chỉnh sử dụng hàm builder.

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

Bạn có thể tùy chỉnh vị trí và padding chỉ báo tiến trình trong `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

Bạn có thể sử dụng các vị trí chỉ báo tiến trình sau:

- `ProgressIndicatorPosition.top`: Chỉ báo tiến trình ở trên cùng màn hình.
- `ProgressIndicatorPosition.bottom`: Chỉ báo tiến trình ở dưới cùng màn hình.

#### Ghi đè kiểu tiến trình theo tab

Bạn có thể ghi đè `progressStyle` cấp bố cục trên từng tab bằng `NavigationTab.journey(progressStyle: ...)`. Tab không có `progressStyle` riêng sẽ kế thừa mặc định từ bố cục. Tab không có mặc định bố cục và không có kiểu riêng sẽ không hiển thị chỉ báo tiến trình.

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // overrides the layout default for this tab only
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

Class `JourneyState` mở rộng `NyState` với chức năng dành riêng cho hành trình để giúp bạn dễ dàng tạo luồng onboarding và hành trình nhiều bước.

Để tạo `JourneyState` mới, bạn có thể sử dụng lệnh sau.

``` bash
metro make:journey_widget onboard_user_dob
```

Hoặc nếu bạn muốn tạo nhiều widget cùng lúc, bạn có thể sử dụng lệnh sau.

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

Đây là giao diện của widget JourneyState được tạo:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // Your initialization logic here
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // Navigation buttons
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// Check if the journey can continue to the next step
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// Called before navigating to the next step
  @override
  Future<void> onBeforeNext() async {
    // E.g. save data to session
  }

  /// Called when the journey is complete (at the last step)
  @override
  Future<void> onComplete() async {}
}
```

Bạn sẽ thấy class **JourneyState** sử dụng `nextStep` để điều hướng tiến và `onBackPressed` để quay lại.

Phương thức `nextStep` chạy qua vòng đời xác thực đầy đủ: `canContinue()` -> `onBeforeNext()` -> điều hướng (hoặc `onComplete()` nếu ở bước cuối) -> `onAfterNext()`.

Bạn cũng có thể sử dụng `buildJourneyContent` để xây dựng bố cục có cấu trúc với nút điều hướng tùy chọn:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

Đây là các thuộc tính bạn có thể sử dụng trong phương thức `buildJourneyContent`.

| Thuộc tính | Kiểu | Mô tả |
| --- | --- | --- |
| `content` | `Widget` | Nội dung chính của trang. |
| `nextButton` | `Widget?` | Widget nút tiếp theo. |
| `backButton` | `Widget?` | Widget nút quay lại. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding cho nội dung. |
| `header` | `Widget?` | Widget header. |
| `footer` | `Widget?` | Widget footer. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Căn chỉnh trục chéo của nội dung. |

<div id="journey-state-helper-methods"></div>

### Phương thức helper JourneyState

Class `JourneyState` có các phương thức helper và thuộc tính bạn có thể sử dụng để tùy chỉnh hành vi hành trình.

| Phương thức / Thuộc tính | Mô tả |
| --- | --- |
| [`nextStep()`](#next-step) | Điều hướng đến bước tiếp theo với xác thực. Trả về `Future<bool>`. |
| [`previousStep()`](#previous-step) | Điều hướng đến bước trước đó. Trả về `Future<bool>`. |
| [`onBackPressed()`](#on-back-pressed) | Helper đơn giản để điều hướng về bước trước. |
| [`onComplete()`](#on-complete) | Được gọi khi hành trình hoàn tất (ở bước cuối). |
| [`onBeforeNext()`](#on-before-next) | Được gọi trước khi điều hướng đến bước tiếp theo. |
| [`onAfterNext()`](#on-after-next) | Được gọi sau khi điều hướng đến bước tiếp theo. |
| [`canContinue()`](#can-continue) | Kiểm tra xác thực trước khi điều hướng đến bước tiếp theo. |
| [`isFirstStep`](#is-first-step) | Trả về true nếu đây là bước đầu tiên trong hành trình. |
| [`isLastStep`](#is-last-step) | Trả về true nếu đây là bước cuối cùng trong hành trình. |
| [`currentStep`](#current-step) | Trả về chỉ mục bước hiện tại (bắt đầu từ 0). |
| [`totalSteps`](#total-steps) | Trả về tổng số bước. |
| [`completionPercentage`](#completion-percentage) | Trả về phần trăm hoàn thành (0.0 đến 1.0). |
| [`goToStep(int index)`](#go-to-step) | Nhảy đến bước cụ thể theo chỉ mục. |
| [`goToNextStep()`](#go-to-next-step) | Nhảy đến bước tiếp theo (không xác thực). |
| [`goToPreviousStep()`](#go-to-previous-step) | Nhảy đến bước trước đó (không xác thực). |
| [`goToFirstStep()`](#go-to-first-step) | Nhảy đến bước đầu tiên. |
| [`goToLastStep()`](#go-to-last-step) | Nhảy đến bước cuối cùng. |
| [`exitJourney()`](#exit-journey) | Thoát hành trình bằng cách pop root navigator. |
| [`resetCurrentStep()`](#reset-current-step) | Đặt lại trạng thái của bước hiện tại. |
| [`onJourneyComplete`](#on-journey-complete) | Callback khi hành trình hoàn tất (ghi đè ở bước cuối). |
| [`buildJourneyPage()`](#build-journey-page) | Xây dựng trang hành trình toàn màn hình với Scaffold. |


<div id="next-step"></div>

#### nextStep

Phương thức `nextStep` điều hướng đến bước tiếp theo với xác thực đầy đủ. Nó chạy qua vòng đời: `canContinue()` -> `onBeforeNext()` -> điều hướng hoặc `onComplete()` -> `onAfterNext()`.

Bạn có thể truyền `force: true` để bỏ qua xác thực và điều hướng trực tiếp.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // runs validation then navigates
        ),
    );
}
```

Để bỏ qua xác thực:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

Phương thức `previousStep` điều hướng đến bước trước đó. Trả về `true` nếu thành công, `false` nếu đã ở bước đầu tiên.

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // Already at first step
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

Phương thức `onBackPressed` là helper đơn giản gọi `previousStep()` bên trong.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

Phương thức `onComplete` được gọi khi `nextStep()` được kích hoạt ở bước cuối (sau khi xác thực thành công).

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

Phương thức `onBeforeNext` được gọi trước khi điều hướng đến bước tiếp theo.

Ví dụ: nếu bạn muốn lưu dữ liệu trước khi chuyển đến bước tiếp theo, bạn có thể thực hiện tại đây.

``` dart
@override
Future<void> onBeforeNext() async {
    // E.g. save data to session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

Phương thức `onAfterNext` được gọi sau khi điều hướng đến bước tiếp theo.

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

Phương thức `canContinue` được gọi khi `nextStep()` được kích hoạt. Trả về `false` để ngăn điều hướng.

``` dart
@override
Future<bool> canContinue() async {
    // Perform your validation logic here
    // Return true if the journey can continue, false otherwise
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

Thuộc tính `isFirstStep` trả về true nếu đây là bước đầu tiên trong hành trình.

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

Thuộc tính `isLastStep` trả về true nếu đây là bước cuối cùng trong hành trình.

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

Thuộc tính `currentStep` trả về chỉ mục bước hiện tại (bắt đầu từ 0).

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

Thuộc tính `totalSteps` trả về tổng số bước trong hành trình.

<div id="completion-percentage"></div>

#### completionPercentage

Thuộc tính `completionPercentage` trả về phần trăm hoàn thành dưới dạng giá trị từ 0.0 đến 1.0.

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

Phương thức `goToStep` nhảy trực tiếp đến bước cụ thể theo chỉ mục. Phương thức này **không** kích hoạt xác thực.

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // jump to step index 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

Phương thức `goToNextStep` nhảy đến bước tiếp theo mà không xác thực. Nếu đã ở bước cuối, phương thức không làm gì.

``` dart
onPressed: () {
    goToNextStep(); // skip validation and go to next step
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

Phương thức `goToPreviousStep` nhảy đến bước trước đó mà không xác thực. Nếu đã ở bước đầu tiên, phương thức không làm gì.

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

Phương thức `goToFirstStep` nhảy đến bước đầu tiên.

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

Phương thức `goToLastStep` nhảy đến bước cuối cùng.

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

Phương thức `exitJourney` thoát hành trình bằng cách pop root navigator.

``` dart
onPressed: () {
    exitJourney(); // pop the root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

Phương thức `resetCurrentStep` đặt lại trạng thái của bước hiện tại.

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

Getter `onJourneyComplete` có thể được ghi đè ở **bước cuối cùng** của hành trình để định nghĩa điều gì xảy ra khi người dùng hoàn tất luồng.

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback when journey completes
  @override
  void Function()? get onJourneyComplete => () {
    // Navigate to your home page or next destination
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // triggers the completion callback
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

Phương thức `buildJourneyPage` xây dựng trang hành trình toàn màn hình được bọc trong `Scaffold` với `SafeArea`.

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| Thuộc tính | Kiểu | Mô tả |
| --- | --- | --- |
| `content` | `Widget` | Nội dung chính của trang. |
| `nextButton` | `Widget?` | Widget nút tiếp theo. |
| `backButton` | `Widget?` | Widget nút quay lại. |
| `contentPadding` | `EdgeInsetsGeometry` | Padding cho nội dung. |
| `header` | `Widget?` | Widget header. |
| `footer` | `Widget?` | Widget footer. |
| `backgroundColor` | `Color?` | Màu nền của Scaffold. |
| `appBar` | `Widget?` | Widget AppBar tùy chọn. |
| `crossAxisAlignment` | `CrossAxisAlignment` | Căn chỉnh trục chéo của nội dung. |

<div id="navigating-within-a-tab"></div>

## Điều hướng đến widget trong tab

Bạn có thể điều hướng đến widget trong tab bằng helper `pushTo`.

Bên trong tab, bạn có thể sử dụng helper `pushTo` để điều hướng đến widget khác.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

Bạn cũng có thể truyền dữ liệu đến widget bạn đang điều hướng đến.

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## Tab

Tab là khối xây dựng chính của Navigation Hub.

Bạn có thể thêm tab vào Navigation Hub bằng class `NavigationTab` và các constructor được đặt tên.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Trong ví dụ trên, chúng ta đã thêm hai tab vào Navigation Hub, Home và Settings.

Bạn có thể sử dụng các loại tab khác nhau:

- `NavigationTab.tab()` - Tab điều hướng tiêu chuẩn.
- `NavigationTab.badge()` - Tab có số huy hiệu.
- `NavigationTab.alert()` - Tab có chỉ báo cảnh báo.
- `NavigationTab.journey()` - Tab cho bố cục điều hướng hành trình.

<div id="adding-badges-to-tabs"></div>

## Thêm huy hiệu vào tab

Chúng tôi đã giúp bạn dễ dàng thêm huy hiệu vào tab.

Huy hiệu là cách tuyệt vời để cho người dùng biết có gì mới trong tab.

Ví dụ, nếu bạn có ứng dụng chat, bạn có thể hiển thị số tin nhắn chưa đọc trong tab chat.

Để thêm huy hiệu vào tab, bạn có thể sử dụng constructor `NavigationTab.badge`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Trong ví dụ trên, chúng ta đã thêm huy hiệu vào tab Chat với số lượng ban đầu là 10.

Bạn cũng có thể cập nhật số lượng huy hiệu theo chương trình.

``` dart
/// Increment the badge count
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// Update the badge count
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// Clear the badge count
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

Mặc định, số lượng huy hiệu sẽ được ghi nhớ. Nếu bạn muốn **xóa** số lượng huy hiệu mỗi phiên, bạn có thể đặt `rememberCount` thành `false`.

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## Thêm cảnh báo vào tab

Bạn có thể thêm cảnh báo vào tab.

Đôi khi bạn có thể không muốn hiển thị số huy hiệu, nhưng muốn hiển thị chỉ báo cảnh báo cho người dùng.

Để thêm cảnh báo vào tab, bạn có thể sử dụng constructor `NavigationTab.alert`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

Lệnh này sẽ thêm cảnh báo vào tab Chat với màu đỏ.

Bạn cũng có thể cập nhật cảnh báo theo chương trình.

``` dart
/// Enable the alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// Disable the alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## Chỉ mục ban đầu

Mặc định, Navigation Hub bắt đầu ở tab đầu tiên (chỉ mục 0). Bạn có thể thay đổi bằng cách ghi đè getter `initialIndex`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // Start on the second tab
    ...
}
```

<div id="maintaining-state"></div>

## Duy trì trạng thái

Mặc định, trạng thái của Navigation Hub được duy trì.

Điều này có nghĩa khi bạn điều hướng đến tab, trạng thái của tab được bảo toàn.

Nếu bạn muốn xóa trạng thái tab mỗi khi điều hướng đến nó, bạn có thể đặt `maintainState` thành `false`.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

Bạn có thể ghi đè phương thức `onTap` để thêm logic tùy chỉnh khi tab được nhấn.

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // Add custom logic here
        // E.g. track analytics, show confirmation, etc.
        super.onTap(index); // Always call super to handle the tab switch
    }
}
```

<div id="state-actions"></div>

## Hành động trạng thái

Hành động trạng thái là cách tương tác với Navigation Hub từ bất kỳ đâu trong ứng dụng.

Đây là các hành động trạng thái bạn có thể sử dụng:

``` dart
/// Reset the tab at a given index
/// E.g. MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// Change the current tab programmatically
/// E.g. MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// Update the badge count
/// E.g. MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// Increment the badge count
/// E.g. MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// Clear the badge count
/// E.g. MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// Enable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// Disable the alert for a tab
/// E.g. MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// Navigate to the next page in a journey layout
/// E.g. await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// Navigate to the previous page in a journey layout
/// E.g. await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

Để sử dụng hành động trạng thái, bạn có thể thực hiện như sau:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // Switch to tab 2

await MyNavigationHub.stateActions.nextPage(); // Journey: go to next page
```

<div id="loading-style"></div>

## Kiểu tải

Mặc định, Navigation Hub sẽ hiển thị widget tải **mặc định** (resources/widgets/loader_widget.dart) khi tab đang tải.

Bạn có thể tùy chỉnh `loadingStyle` để cập nhật kiểu tải.

| Kiểu | Mô tả |
| --- | --- |
| normal | Kiểu tải mặc định |
| skeletonizer | Kiểu tải skeleton |
| none | Không có kiểu tải |

Bạn có thể thay đổi kiểu tải như sau:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Nếu bạn muốn cập nhật widget tải trong một trong các kiểu, bạn có thể truyền `child` vào `LoadingStyle`.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

Bây giờ, khi tab đang tải, dòng chữ "Loading..." sẽ được hiển thị.

Ví dụ bên dưới:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // simulate loading for 3 seconds

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## Tạo Navigation Hub

Để tạo Navigation Hub, bạn có thể sử dụng [Metro](/docs/{{$version}}/metro), sử dụng lệnh bên dưới.

``` bash
metro make:navigation_hub base
```

Lệnh sẽ hướng dẫn bạn qua quá trình thiết lập tương tác nơi bạn có thể chọn loại bố cục và định nghĩa các tab hoặc trạng thái hành trình.

Lệnh này sẽ tạo tệp `base_navigation_hub.dart` trong thư mục `resources/pages/navigation_hubs/base/` với widget con được tổ chức trong thư mục con `tabs/` hoặc `states/`.
