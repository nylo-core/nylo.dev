# 스크린샷
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [소개](#introduction "스크린샷 소개")
- [화면 캡처](#capturing-the-screen "전체 화면 캡처")
- [영역 캡처](#capturing-an-area "ScreenshotArea로 화면의 일부 캡처")
- [스크린샷 저장](#saving-a-screenshot "스크린샷을 디스크에 저장")
- [스토어 등록용](#store-listings "App Store와 Google Play에 맞게 스크린샷 크기 조정")
  - [스토어 크기](#store-sizes "ScreenshotStore 크기")
- [콘텐츠 숨기기](#hiding-content "스크린샷에서 콘텐츠 숨기기")
- [위젯 캡처](#capturing-a-widget "표시하지 않고 위젯 캡처")
- [로케일과 테마](#locales-and-themes "다른 로케일 또는 테마로 캡처")
- [Metro에서](#from-metro "Metro에서 스크린샷 찍기")

<div id="introduction"></div>

## 소개

<x-doc-strip label="필수" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot`은 앱 화면 또는 그 일부의 PNG를 찍습니다:

``` dart
// 다이얼로그와 시트를 포함한 전체 화면
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// 화면의 일부
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

캡처는 모든 빌드 모드에서 동작하므로 영수증 공유나 버그 리포트에 화면 첨부 같은 앱 기능에 활용할 수 있습니다. [Metro](#from-metro)와 [플레이북](/docs/{{ $version }}/playbooks#screenshots)도 같은 코드를 사용합니다.

`Screenshot`은 PNG를 `bytes`로 보관하며, 물리적 픽셀 단위의 `width`와 `height`를 가집니다.

<div id="capturing-the-screen"></div>

## 화면 캡처

`Screenshot.capture()`는 사용자가 보는 그대로의 앱 전체 화면을 읽습니다:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| 매개변수 | 설명 |
|-----------|-------------|
| `pixelRatio` | 논리 픽셀에 대한 상대 크기. 기본값은 기기의 값이므로 PNG는 화면의 기본 해상도를 가집니다 |
| `settle` | 캡처하기 전에 다음 프레임을 기다림 (기본값 `true`). 프레임이 방금 그려진 경우 `false`를 전달하세요 |
| `locale` | 캡처하는 동안 이 언어로 화면을 표시. 예: `'es'` |
| `theme` | 캡처하는 동안 이 테마 id로 화면을 표시. 예: `'dark'` |

`NyApp.materialApp`으로 빌드된 앱은 정확히 앱의 페이지만 캡처합니다. 직접 `MaterialApp`을 빌드하는 경우 `builder`에 `ScreenshotRoot`를 추가하세요:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## 영역 캡처

화면의 일부를 `ScreenshotArea`로 감싸고 이름을 지정하세요:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

그런 다음 해당 이름으로 캡처하세요:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area`는 `capture`와 동일한 `pixelRatio`, `settle`, `locale`, `theme` 매개변수를 받습니다. `Screenshot.areas`는 화면에 있는 영역의 이름을 나열합니다.

<div id="saving-a-screenshot"></div>

## 스크린샷 저장

`store`는 PNG를 파일 스토리지 디스크에 저장하고 경로를 반환합니다:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

`disk`를 지정하지 않으면 기본 디스크에 저장합니다. 이미지를 공유하거나 업로드하려면 `shot.bytes`를 읽으세요.

<div id="store-listings"></div>

## 스토어 등록용

`forStore`는 앱 스토어가 요구하는 정확한 크기의 새 스크린샷을 반환합니다:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

거의 같은 비율의 화면은 해당 크기를 가득 채우고, 그렇지 않은 화면은 그 안에 맞춰집니다. `frame: true`를 사용하면 화면이 `caption` 아래의 휴대폰 외곽선 안에 그려집니다.

| 매개변수 | 설명 |
|-----------|-------------|
| `frame` | 화면을 휴대폰 외곽선 안에 그림 |
| `caption` | 휴대폰 위의 텍스트. `frame`과 함께 사용 |
| `background` | 화면 주변의 `Color` |

스크린샷 자체 크기로 외곽선을 그리려면 `framed()`를 사용하세요:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### 스토어 크기

| 크기 | Id | 픽셀 |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all`은 모든 크기를 나열하고, `ScreenshotStore.named('ios-6.9')`는 id로 하나를 찾습니다.

<div id="hiding-content"></div>

## 콘텐츠 숨기기

비공개 콘텐츠는 무엇이든 `ScreenshotHidden`으로 감싸세요. 화면에는 평소처럼 표시되며, 모든 스크린샷에서는 회색 블록이 이를 가립니다:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

회색 블록 대신 직접 만든 플레이스홀더를 그리려면 `replacement`를 전달하세요.

<div id="capturing-a-widget"></div>

## 위젯 캡처

`Screenshot.widget`은 이미지 전용으로 만든 공유 카드처럼, 위젯을 표시하지 않고 캡처합니다:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

PNG는 위젯과 같은 크기이므로 정확한 크기가 필요하면 `SizedBox`로 감싸세요. 해당 context의 테마와 로컬라이제이션을 위젯에 적용하려면 `context`를, 레이아웃되는 공간을 설정하려면 `size`를, 이미지가 로드될 시간을 주려면 `delay`를 전달하세요.

<div id="locales-and-themes"></div>

## 로케일과 테마

한 번의 캡처에서 화면을 다르게 표시하려면 `locale` 또는 `theme`을 전달하세요:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

앱은 페이지, 상태, 내비게이션 스택을 유지한 채 제자리에서 전환되며 곧바로 원래대로 돌아갑니다. 아무것도 저장되지 않으므로 사용자 본인의 선택은 유지됩니다. 로케일에는 에셋의 언어 파일이 필요하고, 테마에는 등록된 테마 id가 필요합니다.

<div id="from-metro"></div>

## Metro에서

터미널에서 실행 중인 앱의 스크린샷을 저장하세요:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

모든 옵션은 [Metro Live](/docs/{{ $version }}/metro-live#screenshots)를 참고하세요. [플레이북](/docs/{{ $version }}/playbooks#screenshots)은 모든 언어와 테마에서 전체 여정의 스크린샷을 찍을 수 있습니다.
