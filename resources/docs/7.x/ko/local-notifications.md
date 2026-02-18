# Local Notifications

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [예약 알림](#scheduled-notifications "예약 알림")
- [빌더 패턴](#builder-pattern "빌더 패턴")
- [플랫폼 설정](#platform-configuration "플랫폼 설정")
  - [Android 설정](#android-configuration "Android 설정")
  - [iOS 설정](#ios-configuration "iOS 설정")
- [알림 관리](#managing-notifications "알림 관리")
- [권한](#permissions "권한")
- [플랫폼 셋업](#platform-setup "플랫폼 셋업")
- [API 레퍼런스](#api-reference "API 레퍼런스")

<div id="introduction"></div>

## 소개

{{ config('app.name') }}은 `LocalNotification` 클래스를 통해 로컬 알림 시스템을 제공합니다. 이를 통해 iOS와 Android에서 풍부한 콘텐츠가 포함된 즉시 또는 예약 알림을 보낼 수 있습니다.

> 로컬 알림은 웹에서 지원되지 않습니다. 웹에서 사용하려고 하면 `NotificationException`이 발생합니다.

<div id="basic-usage"></div>

## 기본 사용법

빌더 패턴 또는 정적 메서드를 사용하여 알림을 보냅니다:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 빌더 패턴
await LocalNotification(title: "Hello", body: "World").send();

// 헬퍼 함수 사용
await localNotification("Hello", "World").send();

// 정적 메서드 사용
await LocalNotification.sendNotification(
  title: "Hello",
  body: "World",
);
```

<div id="scheduled-notifications"></div>

## 예약 알림

특정 시간에 전달되도록 알림을 예약합니다:

``` dart
// 내일 예약
final tomorrow = DateTime.now().add(Duration(days: 1));

await LocalNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
).send(at: tomorrow);

// 정적 메서드 사용
await LocalNotification.sendNotification(
  title: "Reminder",
  body: "Don't forget your appointment!",
  at: tomorrow,
);
```

<div id="builder-pattern"></div>

## 빌더 패턴

`LocalNotification` 클래스는 플루언트 빌더 API를 제공합니다. 모든 체이닝 가능한 메서드는 `LocalNotification` 인스턴스를 반환합니다:

``` dart
await LocalNotification(title: "New Photo", body: "Check this out!")
    .addPayload("photo_id:123")
    .addId(42)
    .addSubtitle("From your friend")
    .addBadgeNumber(3)
    .addSound("custom_sound.wav")
    .send();
```

### 체이닝 가능한 메서드

| 메서드 | 매개변수 | 설명 |
|--------|------------|-------------|
| `addPayload` | `String payload` | 알림에 커스텀 데이터 문자열을 설정합니다 |
| `addId` | `int id` | 알림의 고유 식별자를 설정합니다 |
| `addSubtitle` | `String subtitle` | 부제목 텍스트를 설정합니다 |
| `addBadgeNumber` | `int badgeNumber` | 앱 아이콘 배지 번호를 설정합니다 |
| `addSound` | `String sound` | 커스텀 사운드 파일명을 설정합니다 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | 첨부 파일을 추가합니다 (iOS 전용, URL에서 다운로드) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | Android 전용 설정을 지정합니다 |
| `setIOSConfig` | `IOSNotificationConfig config` | iOS 전용 설정을 지정합니다 |

### send()

``` dart
Future<void> send({
  DateTime? at,
  AndroidScheduleMode? androidScheduleMode,
})
```

`at`이 제공되면 해당 시간에 알림을 예약합니다. 그렇지 않으면 즉시 표시합니다.

<div id="platform-configuration"></div>

## 플랫폼 설정

v7에서는 플랫폼별 옵션을 `AndroidNotificationConfig`과 `IOSNotificationConfig` 객체를 통해 구성합니다.

<div id="android-configuration"></div>

### Android 설정

`setAndroidConfig()`에 `AndroidNotificationConfig`을 전달합니다:

``` dart
await LocalNotification(
  title: "Android Notification",
  body: "With custom configuration",
)
.setAndroidConfig(AndroidNotificationConfig(
  channelId: "custom_channel",
  channelName: "Custom Channel",
  channelDescription: "Notifications from custom channel",
  importance: Importance.max,
  priority: Priority.high,
  enableVibration: true,
  vibrationPattern: [0, 1000, 500, 1000],
  enableLights: true,
  ledColor: Color(0xFF00FF00),
))
.send();
```

#### AndroidNotificationConfig 속성

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `channelId` | `String?` | `'default_channel'` | 알림 채널 ID |
| `channelName` | `String?` | `'Default Channel'` | 알림 채널 이름 |
| `channelDescription` | `String?` | `'Default Channel'` | 채널 설명 |
| `importance` | `Importance?` | `Importance.max` | 알림 중요도 수준 |
| `priority` | `Priority?` | `Priority.high` | 알림 우선순위 |
| `ticker` | `String?` | `'ticker'` | 접근성을 위한 티커 텍스트 |
| `icon` | `String?` | `'app_icon'` | 작은 아이콘 리소스 이름 |
| `playSound` | `bool?` | `true` | 사운드 재생 여부 |
| `enableVibration` | `bool?` | `true` | 진동 활성화 여부 |
| `vibrationPattern` | `List<int>?` | - | 커스텀 진동 패턴 (ms) |
| `groupKey` | `String?` | - | 알림 그룹화를 위한 그룹 키 |
| `setAsGroupSummary` | `bool?` | `false` | 그룹 요약인지 여부 |
| `groupAlertBehavior` | `GroupAlertBehavior?` | `GroupAlertBehavior.all` | 그룹 알림 동작 |
| `autoCancel` | `bool?` | `true` | 탭 시 자동 해제 |
| `ongoing` | `bool?` | `false` | 사용자가 해제할 수 없음 |
| `silent` | `bool?` | `false` | 무음 알림 |
| `color` | `Color?` | - | 강조 색상 |
| `largeIcon` | `String?` | - | 큰 아이콘 리소스 경로 |
| `onlyAlertOnce` | `bool?` | `false` | 처음 표시 시에만 알림 |
| `showWhen` | `bool?` | `true` | 타임스탬프 표시 |
| `when` | `int?` | - | 커스텀 타임스탬프 (에포크 이후 ms) |
| `usesChronometer` | `bool?` | `false` | 크로노미터 표시 사용 |
| `chronometerCountDown` | `bool?` | `false` | 크로노미터 카운트다운 |
| `channelShowBadge` | `bool?` | `true` | 채널에 배지 표시 |
| `showProgress` | `bool?` | `false` | 진행률 표시기 표시 |
| `maxProgress` | `int?` | `0` | 최대 진행률 값 |
| `progress` | `int?` | `0` | 현재 진행률 값 |
| `indeterminate` | `bool?` | `false` | 불확정 진행률 |
| `channelAction` | `AndroidNotificationChannelAction?` | `createIfNotExists` | 채널 액션 |
| `enableLights` | `bool?` | `false` | 알림 LED 활성화 |
| `ledColor` | `Color?` | - | LED 색상 |
| `ledOnMs` | `int?` | - | LED 켜짐 시간 (ms) |
| `ledOffMs` | `int?` | - | LED 꺼짐 시간 (ms) |
| `visibility` | `NotificationVisibility?` | - | 잠금 화면 표시 여부 |
| `timeoutAfter` | `int?` | - | 자동 해제 타임아웃 (ms) |
| `fullScreenIntent` | `bool?` | `false` | 전체 화면 인텐트로 실행 |
| `shortcutId` | `String?` | - | 바로가기 ID |
| `additionalFlags` | `List<int>?` | - | 추가 플래그 |
| `tag` | `String?` | - | 알림 태그 |
| `actions` | `List<AndroidNotificationAction>?` | - | 액션 버튼 |
| `colorized` | `bool?` | `false` | 색상화 활성화 |
| `audioAttributesUsage` | `AudioAttributesUsage?` | `notification` | 오디오 속성 사용 |

<div id="ios-configuration"></div>

### iOS 설정

`setIOSConfig()`에 `IOSNotificationConfig`을 전달합니다:

``` dart
await LocalNotification(
  title: "iOS Notification",
  body: "With custom configuration",
)
.setIOSConfig(IOSNotificationConfig(
  presentAlert: true,
  presentBanner: true,
  presentSound: true,
  threadIdentifier: "thread_1",
  interruptionLevel: InterruptionLevel.active,
))
.addBadgeNumber(1)
.addSound("custom_sound.wav")
.send();
```

#### IOSNotificationConfig 속성

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `presentList` | `bool?` | `true` | 알림 목록에 표시 |
| `presentAlert` | `bool?` | `true` | 알림 표시 |
| `presentBadge` | `bool?` | `true` | 앱 배지 업데이트 |
| `presentSound` | `bool?` | `true` | 사운드 재생 |
| `presentBanner` | `bool?` | `true` | 배너 표시 |
| `sound` | `String?` | - | 사운드 파일 이름 |
| `badgeNumber` | `int?` | - | 배지 번호 |
| `threadIdentifier` | `String?` | - | 그룹화를 위한 스레드 식별자 |
| `categoryIdentifier` | `String?` | - | 액션을 위한 카테고리 식별자 |
| `interruptionLevel` | `InterruptionLevel?` | - | 인터럽션 수준 |

### 첨부 파일 (iOS 전용)

iOS 알림에 이미지, 오디오 또는 비디오 첨부 파일을 추가합니다. 첨부 파일은 URL에서 다운로드됩니다:

``` dart
await LocalNotification(
  title: "New Photo",
  body: "Check out this image!",
)
.addAttachment(
  "https://example.com/image.jpg",
  "photo.jpg",
  showThumbnail: true,
)
.send();
```

<div id="managing-notifications"></div>

## 알림 관리

### 특정 알림 취소

``` dart
await LocalNotification.cancelNotification(42);

// 태그와 함께 (Android)
await LocalNotification.cancelNotification(42, tag: "my_tag");
```

### 모든 알림 취소

``` dart
await LocalNotification.cancelAllNotifications();
```

### 배지 카운트 초기화

``` dart
await LocalNotification.clearBadgeCount();
```

<div id="permissions"></div>

## 권한

사용자에게 알림 권한을 요청합니다:

``` dart
// 기본값으로 요청
await LocalNotification.requestPermissions();

// 특정 옵션으로 요청
await LocalNotification.requestPermissions(
  alert: true,
  badge: true,
  sound: true,
  provisional: false,
  critical: false,
  vibrate: true,
  enableLights: true,
  channelId: 'default_notification_channel_id',
  channelName: 'Default Notification Channel',
);
```

권한은 `NyScheduler.taskOnce`를 통해 첫 번째 알림 전송 시 자동으로 요청됩니다.

<div id="platform-setup"></div>

## 플랫폼 셋업

### iOS 셋업

`Info.plist`에 추가합니다:

``` xml
<key>UIBackgroundModes</key>
<array>
    <string>remote-notification</string>
</array>
```

### Android 셋업

`AndroidManifest.xml`에 추가합니다:

``` xml
<uses-permission android:name="android.permission.RECEIVE_BOOT_COMPLETED"/>
<uses-permission android:name="android.permission.VIBRATE" />
<uses-permission android:name="android.permission.USE_FULL_SCREEN_INTENT" />
```

<div id="api-reference"></div>

## API 레퍼런스

### 정적 메서드

| 메서드 | 시그니처 | 설명 |
|--------|-----------|-------------|
| `sendNotification` | `static Future<void> sendNotification({required String title, required String body, String? payload, DateTime? at, int? id, String? subtitle, int? badgeNumber, String? sound, AndroidNotificationConfig? androidConfig, IOSNotificationConfig? iosConfig, AndroidScheduleMode? androidScheduleMode})` | 모든 옵션으로 알림 전송 |
| `cancelNotification` | `static Future<void> cancelNotification(int id, {String? tag})` | 특정 알림 취소 |
| `cancelAllNotifications` | `static Future<void> cancelAllNotifications()` | 모든 알림 취소 |
| `requestPermissions` | `static Future<void> requestPermissions({...})` | 알림 권한 요청 |
| `clearBadgeCount` | `static Future<void> clearBadgeCount()` | iOS 배지 카운트 초기화 |

### 인스턴스 메서드 (체이닝 가능)

| 메서드 | 매개변수 | 반환값 | 설명 |
|--------|------------|---------|-------------|
| `addPayload` | `String payload` | `LocalNotification` | 페이로드 데이터 설정 |
| `addId` | `int id` | `LocalNotification` | 알림 ID 설정 |
| `addSubtitle` | `String subtitle` | `LocalNotification` | 부제목 설정 |
| `addBadgeNumber` | `int badgeNumber` | `LocalNotification` | 배지 번호 설정 |
| `addSound` | `String sound` | `LocalNotification` | 커스텀 사운드 설정 |
| `addAttachment` | `String url, String fileName, {bool? showThumbnail}` | `LocalNotification` | 첨부 파일 추가 (iOS) |
| `setAndroidConfig` | `AndroidNotificationConfig config` | `LocalNotification` | Android 설정 지정 |
| `setIOSConfig` | `IOSNotificationConfig config` | `LocalNotification` | iOS 설정 지정 |
| `send` | `{DateTime? at, AndroidScheduleMode? androidScheduleMode}` | `Future<void>` | 알림 전송 |
