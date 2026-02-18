# Modals

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [모달 생성](#creating-a-modal "모달 생성")
- [기본 사용법](#basic-usage "기본 사용법")
- [모달 생성](#creating-a-modal "모달 생성")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [매개변수](#parameters "매개변수")
  - [액션](#actions "액션")
  - [헤더](#header "헤더")
  - [닫기 버튼](#close-button "닫기 버튼")
  - [커스텀 데코레이션](#custom-decoration "커스텀 데코레이션")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

{{ config('app.name') }}은 **하단 시트 모달** 기반의 모달 시스템을 제공합니다.

`BottomSheetModal` 클래스는 액션, 헤더, 닫기 버튼, 커스텀 스타일링이 포함된 콘텐츠 오버레이를 표시하기 위한 유연한 API를 제공합니다.

모달은 다음에 유용합니다:
- 확인 대화상자 (예: 로그아웃, 삭제)
- 빠른 입력 폼
- 여러 옵션이 있는 액션 시트
- 정보 오버레이

<div id="creating-a-modal"></div>

## 모달 생성

Metro CLI를 사용하여 새 모달을 생성할 수 있습니다:

``` bash
metro make:bottom_sheet_modal payment_options
```

이 명령은 두 가지를 생성합니다:

1. `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`에 **모달 콘텐츠 위젯**:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`의 `BottomSheetModal` 클래스에 **정적 메서드**가 추가됩니다:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

그러면 어디서든 모달을 표시할 수 있습니다:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

동일한 이름의 모달이 이미 존재하는 경우, `--force` 플래그를 사용하여 덮어쓸 수 있습니다:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## 기본 사용법

`BottomSheetModal`을 사용하여 모달을 표시합니다:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## 모달 생성

권장 패턴은 각 모달 타입에 대한 정적 메서드가 있는 `BottomSheetModal` 클래스를 만드는 것입니다. 보일러플레이트는 다음 구조를 제공합니다:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

어디서든 호출합니다:

``` dart
BottomSheetModal.showLogout(context);

// 커스텀 콜백과 함께
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // 커스텀 로그아웃 로직
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()`은 모달을 표시하는 핵심 메서드입니다.

<div id="parameters"></div>

### 매개변수

| 매개변수 | 타입 | 기본값 | 설명 |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | 필수 | 모달의 빌드 컨텍스트 |
| `child` | `Widget` | 필수 | 메인 콘텐츠 위젯 |
| `actionsRow` | `List<Widget>` | `[]` | 가로로 배치되는 액션 위젯 |
| `actionsColumn` | `List<Widget>` | `[]` | 세로로 배치되는 액션 위젯 |
| `height` | `double?` | null | 모달의 고정 높이 |
| `header` | `Widget?` | null | 상단 헤더 위젯 |
| `useSafeArea` | `bool` | `true` | SafeArea로 콘텐츠 감싸기 |
| `isScrollControlled` | `bool` | `false` | 모달 스크롤 가능 여부 |
| `showCloseButton` | `bool` | `false` | X 닫기 버튼 표시 |
| `headerPadding` | `EdgeInsets?` | null | 헤더가 있을 때의 패딩 |
| `backgroundColor` | `Color?` | null | 모달 배경 색상 |
| `showHandle` | `bool` | `true` | 상단 드래그 핸들 표시 |
| `closeButtonColor` | `Color?` | null | 닫기 버튼 배경 색상 |
| `closeButtonIconColor` | `Color?` | null | 닫기 버튼 아이콘 색상 |
| `modalDecoration` | `BoxDecoration?` | null | 모달 컨테이너의 커스텀 데코레이션 |
| `handleColor` | `Color?` | null | 드래그 핸들 색상 |

<div id="actions"></div>

### 액션

액션은 모달 하단에 표시되는 버튼입니다.

**행 액션**은 나란히 배치되며, 각각 동일한 공간을 차지합니다:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**열 액션**은 세로로 쌓입니다:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### 헤더

메인 콘텐츠 위에 위치하는 헤더를 추가합니다:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### 닫기 버튼

오른쪽 상단에 닫기 버튼을 표시합니다:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### 커스텀 데코레이션

모달 컨테이너의 외관을 커스터마이징합니다:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle`은 폼 피커와 기타 컴포넌트에서 사용하는 하단 시트 모달의 외관을 구성합니다:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `backgroundColor` | `NyColor?` | 모달 배경 색상 |
| `barrierColor` | `NyColor?` | 모달 뒤 오버레이 색상 |
| `useRootNavigator` | `bool` | 루트 네비게이터 사용 (기본값: `false`) |
| `routeSettings` | `RouteSettings?` | 모달의 라우트 설정 |
| `titleStyle` | `TextStyle?` | 제목 텍스트 스타일 |
| `itemStyle` | `TextStyle?` | 목록 항목 텍스트 스타일 |
| `clearButtonStyle` | `TextStyle?` | 초기화 버튼 텍스트 스타일 |

<div id="examples"></div>

## 예제

### 확인 모달

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// 사용법
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // 항목 삭제
}
```

### 스크롤 가능한 콘텐츠 모달

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### 액션 시트

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
