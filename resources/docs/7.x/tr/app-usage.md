# Uygulama Kullan&#305;m&#305;

---

<a name="section-1"></a>
- [Giri&#351;](#introduction "Giri&#351;")
- [Kurulum](#setup "Kurulum")
- &#304;zleme
    - [Uygulama a&#231;&#305;l&#305;&#351;lar&#305;](#monitoring-app-launches "Uygulama a&#231;&#305;l&#305;&#351;lar&#305;")
    - [&#304;lk a&#231;&#305;l&#305;&#351; tarihi](#monitoring-app-first-launch-date "&#304;lk a&#231;&#305;l&#305;&#351; tarihi")
    - [&#304;lk a&#231;&#305;l&#305;&#351;tan bu yana toplam g&#252;n](#monitoring-app-total-days-since-first-launch "&#304;lk a&#231;&#305;l&#305;&#351;tan bu yana toplam g&#252;n")

<div id="introduction"></div>

## Giri&#351;

Nylo, uygulama kullan&#305;m&#305;n&#305;z&#305; kutudan &#231;&#305;kt&#305;&#287;&#305; haliyle izlemenize olanak tan&#305;r, ancak &#246;nce uygulama sa&#287;lay&#305;c&#305;lar&#305;n&#305;zdan birinde bu &#246;zelli&#287;i etkinle&#351;tirmeniz gerekir.

&#350;u anda Nylo &#351;unlar&#305; izleyebilir:

- Uygulama a&#231;&#305;l&#305;&#351;lar&#305;
- &#304;lk a&#231;&#305;l&#305;&#351; tarihi

Bu dok&#252;mantasyonu okuduktan sonra uygulama kullan&#305;m&#305;n&#305;z&#305; nas&#305;l izleyece&#287;inizi &#246;&#287;reneceksiniz.

<div id="setup"></div>

## Kurulum

`app/providers/app_provider.dart` dosyan&#305;z&#305; a&#231;&#305;n.

Ard&#305;ndan, `boot` metodunuza a&#351;a&#287;&#305;daki kodu ekleyin.

```dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.configure(
      ...
      monitorAppUsage: true, // Enable app usage monitoring
    );
```

Bu, uygulaman&#305;zda uygulama kullan&#305;m izlemeyi etkinle&#351;tirecektir. Uygulama kullan&#305;m izlemenin etkin olup olmad&#305;&#287;&#305;n&#305; kontrol etmeniz gerekirse, `Nylo.instance.shouldMonitorAppUsage()` metodunu kullanabilirsiniz.

<div id="monitoring-app-launches"></div>

## Uygulama A&#231;&#305;l&#305;&#351;lar&#305;n&#305; &#304;zleme

`Nylo.appLaunchCount` metodunu kullanarak uygulaman&#305;z&#305;n ka&#231; kez a&#231;&#305;ld&#305;&#287;&#305;n&#305; izleyebilirsiniz.

> Uygulama a&#231;&#305;l&#305;&#351;lar&#305;, uygulama kapat&#305;lm&#305;&#351; durumdan her a&#231;&#305;ld&#305;&#287;&#305;nda say&#305;l&#305;r.

Bu metodun nas&#305;l kullan&#305;laca&#287;&#305;na dair basit bir &#246;rnek:

```dart
int? launchCount = await Nylo.appLaunchCount();

print('App has been launched $launchCount times');
```

<div id="monitoring-app-first-launch-date"></div>

## Uygulaman&#305;n &#304;lk A&#231;&#305;l&#305;&#351; Tarihini &#304;zleme

`Nylo.appFirstLaunchDate` metodunu kullanarak uygulaman&#305;z&#305;n ilk a&#231;&#305;ld&#305;&#287;&#305; tarihi izleyebilirsiniz.

Bu metodun nas&#305;l kullan&#305;laca&#287;&#305;na dair bir &#246;rnek:

``` dart
DateTime? firstLaunchDate = await  Nylo.appFirstLaunchDate();

print("App was first launched on $firstLaunchDate");
```

<div id="monitoring-app-total-days-since-first-launch"></div>

## &#304;lk A&#231;&#305;l&#305;&#351;tan Bu Yana Toplam G&#252;nleri &#304;zleme

`Nylo.appTotalDaysSinceFirstLaunch` metodunu kullanarak uygulaman&#305;z&#305;n ilk a&#231;&#305;l&#305;&#351;&#305;ndan bu yana ge&#231;en toplam g&#252;nleri izleyebilirsiniz.

Bu metodun nas&#305;l kullan&#305;laca&#287;&#305;na dair bir &#246;rnek:

``` dart
int totalDaysSinceFirstLaunch = await Nylo.appTotalDaysSinceFirstLaunch();

print("It's been $totalDaysSinceFirstLaunch days since the app was first launched");
```
