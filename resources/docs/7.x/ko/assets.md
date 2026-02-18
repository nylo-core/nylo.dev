# 에셋

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- 파일
  - [이미지 표시](#displaying-images "이미지 표시")
  - [커스텀 에셋 경로](#custom-asset-paths "커스텀 에셋 경로")
  - [에셋 경로 반환](#returning-asset-paths "에셋 경로 반환")
- 에셋 관리
  - [새 파일 추가](#adding-new-files "새 파일 추가")
  - [에셋 설정](#asset-configuration "에셋 설정")

<div id="introduction"></div>

## 소개

{{ config('app.name') }} v7은 Flutter 앱에서 에셋을 관리하기 위한 헬퍼 메서드를 제공합니다. 에셋은 `assets/` 디렉토리에 저장되며 이미지, 비디오, 폰트 및 기타 파일을 포함합니다.

기본 에셋 구조:

```
assets/
├── images/
│   ├── nylo_logo.png
│   └── icons/
├── fonts/
└── videos/
```

<div id="displaying-images"></div>

## 이미지 표시

`LocalAsset()` Widget을 사용하여 에셋의 이미지를 표시합니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 기본 사용법
LocalAsset.image("nylo_logo.png")

// `getImageAsset` 헬퍼 사용
Image.asset(getImageAsset("nylo_logo.png"))
```

두 방법 모두 설정된 에셋 디렉토리를 포함한 전체 에셋 경로를 반환합니다.

<div id="custom-asset-paths"></div>

## 커스텀 에셋 경로

다른 에셋 하위 디렉토리를 지원하려면 `LocalAsset` Widget에 커스텀 생성자를 추가할 수 있습니다.

``` dart
// /resources/widgets/local_asset_widget.dart
class LocalAsset extends StatelessWidget {
  // images
  const LocalAsset.image(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/$assetName";

  // icons <- icons 폴더를 위한 새 생성자
  const LocalAsset.icons(String assetName,
      {super.key, this.width, this.height, this.fit, this.opacity, this.borderRadius})
      : assetName = "images/icons/$assetName";
}

// 사용 예시
LocalAsset.icons("icon_name.png", width: 32, height: 32)
LocalAsset.image("logo.png", width: 100, height: 100)
```

<div id="returning-asset-paths"></div>

## 에셋 경로 반환

`assets/` 디렉토리의 모든 파일 타입에 `getAsset()`을 사용합니다:

``` dart
// 비디오 파일
String videoPath = getAsset("videos/welcome.mp4");

// JSON 데이터 파일
String jsonPath = getAsset("data/config.json");

// 폰트 파일
String fontPath = getAsset("fonts/custom_font.ttf");
```

### 다양한 Widget과 함께 사용

``` dart
// 비디오 플레이어
VideoPlayerController.asset(getAsset("videos/intro.mp4"))

// JSON 로딩
final String jsonString = await rootBundle.loadString(getAsset("data/settings.json"));
```

<div id="adding-new-files"></div>

## 새 파일 추가

1. `assets/`의 적절한 하위 디렉토리에 파일을 배치합니다:
   - 이미지: `assets/images/`
   - 비디오: `assets/videos/`
   - 폰트: `assets/fonts/`
   - 기타: `assets/data/` 또는 커스텀 폴더

2. `pubspec.yaml`에 폴더가 나열되어 있는지 확인합니다:

``` yaml
flutter:
  assets:
    - assets/images/
    - assets/videos/
    - assets/data/
```

<div id="asset-configuration"></div>

## 에셋 설정

{{ config('app.name') }} v7은 `.env` 파일의 `ASSET_PATH` 환경 변수를 통해 에셋 경로를 설정합니다:

``` bash
ASSET_PATH="assets"
```

헬퍼 함수는 이 경로를 자동으로 앞에 추가하므로 호출 시 `assets/`를 포함할 필요가 없습니다:

``` dart
// 다음은 동일합니다:
getAsset("videos/intro.mp4")
// 반환: "assets/videos/intro.mp4"

getImageAsset("logo.png")
// 반환: "assets/images/logo.png"
```

### 기본 경로 변경

다른 에셋 구조가 필요한 경우 `.env`에서 `ASSET_PATH`를 업데이트합니다:

``` bash
# 다른 기본 폴더 사용
ASSET_PATH="res"
```

변경 후 환경 설정을 재생성합니다:

``` bash
metro make:env --force
```
