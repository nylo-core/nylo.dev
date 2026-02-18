# Alerts

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [내장 스타일](#built-in-styles "내장 스타일")
- [페이지에서 알림 표시하기](#from-pages "페이지에서 알림 표시하기")
- [컨트롤러에서 알림 표시하기](#from-controllers "컨트롤러에서 알림 표시하기")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [위치 지정](#positioning "위치 지정")
- [커스텀 토스트 스타일](#custom-styles "커스텀 토스트 스타일")
  - [스타일 등록](#registering-styles "스타일 등록")
  - [스타일 팩토리 생성](#creating-a-style-factory "스타일 팩토리 생성")
- [AlertTab](#alert-tab "AlertTab")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

{{ config('app.name') }}은 사용자에게 알림을 표시하기 위한 토스트 알림 시스템을 제공합니다. **success**, **warning**, **info**, **danger** 네 가지 내장 스타일이 포함되어 있으며, 레지스트리 패턴을 통해 커스텀 스타일을 지원합니다.

알림은 페이지, 컨트롤러 또는 `BuildContext`가 있는 어디에서든 트리거할 수 있습니다.

<div id="basic-usage"></div>

## 기본 사용법

`NyState` 페이지에서 편의 메서드를 사용하여 토스트 알림을 표시합니다:

``` dart
// Success 토스트
showToastSuccess(description: "Item saved successfully");

// Warning 토스트
showToastWarning(description: "Your session is about to expire");

// Info 토스트
showToastInfo(description: "New version available");

// Danger 토스트
showToastDanger(description: "Failed to save item");
```

또는 스타일 ID와 함께 전역 함수를 사용합니다:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## 내장 스타일

{{ config('app.name') }}은 네 가지 기본 토스트 스타일을 등록합니다:

| 스타일 ID | 아이콘 | 색상 | 기본 제목 |
|----------|------|-------|---------------|
| `success` | 체크마크 | 초록색 | Success |
| `warning` | 느낌표 | 주황색 | Warning |
| `info` | 정보 아이콘 | 청록색 | Info |
| `danger` | 경고 아이콘 | 빨간색 | Error |

이들은 `lib/config/toast_notification.dart`에서 설정됩니다:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## 페이지에서 알림 표시하기

`NyState` 또는 `NyBaseState`를 확장하는 모든 페이지에서 다음 편의 메서드를 사용할 수 있습니다:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // 커스텀 제목 포함
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (danger 스타일 사용)
    showToastOops(description: "That didn't work");

    // Sorry (danger 스타일 사용)
    showToastSorry(description: "We couldn't process your request");

    // ID로 커스텀 스타일 지정
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### 일반 토스트 메서드

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## 컨트롤러에서 알림 표시하기

`NyController`를 확장하는 컨트롤러는 동일한 편의 메서드를 사용할 수 있습니다:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

사용 가능한 메서드: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

전역 `showToastNotification()` 함수는 `BuildContext`가 있는 어디에서든 토스트를 표시합니다:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // 토스트를 탭했을 때 호출됨
    routeTo("/details");
  },
  onDismiss: () {
    // 토스트가 닫혔을 때 호출됨
  },
  onShow: () {
    // 토스트가 표시되었을 때 호출됨
  },
);
```

### 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 필수 | Build context |
| `id` | `String` | `'success'` | 토스트 스타일 ID |
| `title` | `String?` | null | 기본 제목 재정의 |
| `description` | `String?` | null | 설명 텍스트 |
| `duration` | `Duration?` | null | 토스트 표시 시간 |
| `position` | `ToastNotificationPosition?` | null | 화면 위치 |
| `action` | `VoidCallback?` | null | 탭 콜백 |
| `onDismiss` | `VoidCallback?` | null | 닫기 콜백 |
| `onShow` | `VoidCallback?` | null | 표시 콜백 |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta`는 토스트 알림의 모든 데이터를 캡슐화합니다:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### 속성

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | 아이콘 위젯 |
| `title` | `String` | `''` | 제목 텍스트 |
| `style` | `String` | `''` | 스타일 식별자 |
| `description` | `String` | `''` | 설명 텍스트 |
| `color` | `Color?` | null | 아이콘 섹션 배경색 |
| `action` | `VoidCallback?` | null | 탭 콜백 |
| `dismiss` | `VoidCallback?` | null | 닫기 버튼 콜백 |
| `onDismiss` | `VoidCallback?` | null | 자동/수동 닫기 콜백 |
| `onShow` | `VoidCallback?` | null | 표시 콜백 |
| `duration` | `Duration` | 5초 | 표시 시간 |
| `position` | `ToastNotificationPosition` | `top` | 화면 위치 |
| `metaData` | `Map<String, dynamic>?` | null | 커스텀 메타데이터 |

### copyWith

`ToastMeta`의 수정된 복사본을 생성합니다:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## 위치 지정

화면에서 토스트가 나타나는 위치를 제어합니다:

``` dart
// 화면 상단 (기본값)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// 화면 하단
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// 화면 중앙
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## 커스텀 토스트 스타일

<div id="registering-styles"></div>

### 스타일 등록

`AppProvider`에서 커스텀 스타일을 등록합니다:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

또는 언제든지 스타일을 추가할 수 있습니다:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

그런 다음 사용합니다:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### 스타일 팩토리 생성

`ToastNotification.style()`은 `ToastStyleFactory`를 생성합니다:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `icon` | `Widget` | 토스트의 아이콘 위젯 |
| `color` | `Color` | 아이콘 섹션의 배경색 |
| `defaultTitle` | `String` | 제목이 제공되지 않았을 때 표시되는 제목 |
| `position` | `ToastNotificationPosition?` | 기본 위치 |
| `duration` | `Duration?` | 기본 표시 시간 |
| `builder` | `Widget Function(ToastMeta)?` | 완전한 제어를 위한 커스텀 위젯 빌더 |

### 완전한 커스텀 빌더

토스트 위젯을 완전히 제어하려면:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab`은 네비게이션 탭에 알림 표시기를 추가하기 위한 배지 위젯입니다. 토글할 수 있고 선택적으로 저장소에 상태를 유지할 수 있는 배지를 표시합니다.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `state` | `String` | 필수 | 추적을 위한 상태 이름 |
| `alertEnabled` | `bool?` | null | 배지 표시 여부 |
| `rememberAlert` | `bool?` | `true` | 배지 상태를 저장소에 유지 |
| `icon` | `Widget?` | null | 탭 아이콘 |
| `backgroundColor` | `Color?` | null | 탭 배경색 |
| `textColor` | `Color?` | null | 배지 텍스트 색상 |
| `alertColor` | `Color?` | null | 배지 배경색 |
| `smallSize` | `double?` | null | 작은 배지 크기 |
| `largeSize` | `double?` | null | 큰 배지 크기 |
| `textStyle` | `TextStyle?` | null | 배지 텍스트 스타일 |
| `padding` | `EdgeInsetsGeometry?` | null | 배지 패딩 |
| `alignment` | `Alignment?` | null | 배지 정렬 |
| `offset` | `Offset?` | null | 배지 오프셋 |
| `isLabelVisible` | `bool?` | `true` | 배지 라벨 표시 |

### 팩토리 생성자

`NavigationTab`에서 생성합니다:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## 예제

### 저장 후 성공 알림

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### 액션이 포함된 인터랙티브 토스트

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### 하단 위치 경고

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
