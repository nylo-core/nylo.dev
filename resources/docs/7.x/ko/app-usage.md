# App Usage

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [설정](#setup "설정")
- 모니터링
    - [앱 실행 횟수](#monitoring-app-launches "앱 실행 횟수")
    - [최초 실행 날짜](#monitoring-app-first-launch-date "최초 실행 날짜")
    - [최초 실행 이후 총 일수](#monitoring-app-total-days-since-first-launch "최초 실행 이후 총 일수")

<div id="introduction"></div>

## 소개

Nylo를 사용하면 앱 사용량을 기본적으로 모니터링할 수 있지만, 먼저 앱 프로바이더 중 하나에서 이 기능을 활성화해야 합니다.

현재 Nylo가 모니터링할 수 있는 항목은 다음과 같습니다:

- 앱 실행 횟수
- 최초 실행 날짜

이 문서를 읽으면 앱 사용량을 모니터링하는 방법을 배울 수 있습니다.

<div id="setup"></div>

## 설정

`app/providers/app_provider.dart` 파일을 엽니다.

그런 다음 `boot` 메서드에 다음 코드를 추가합니다.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // 앱 사용량 모니터링 활성화
    );
```

이렇게 하면 앱에서 사용량 모니터링이 활성화됩니다. 앱 사용량 모니터링이 활성화되어 있는지 확인해야 할 경우 `Nylo.instance.shouldMonitorAppUsage()` 메서드를 사용할 수 있습니다.

<div id="monitoring-app-launches"></div>

## 앱 실행 횟수 모니터링

`Nylo.appLaunchCount` 메서드를 사용하여 앱이 실행된 횟수를 모니터링할 수 있습니다.

> 앱 실행 횟수는 앱이 종료된 상태에서 열릴 때마다 카운트됩니다.

이 메서드를 사용하는 간단한 예제입니다:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## 최초 실행 날짜 모니터링

`Nylo.appFirstLaunchDate` 메서드를 사용하여 앱이 처음 실행된 날짜를 모니터링할 수 있습니다.

이 메서드를 사용하는 예제입니다:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## 최초 실행 이후 총 일수 모니터링

`Nylo.appTotalDaysSinceFirstLaunch` 메서드를 사용하여 앱이 처음 실행된 이후의 총 일수를 모니터링할 수 있습니다.

이 메서드를 사용하는 예제입니다:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
