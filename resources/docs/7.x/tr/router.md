# Router

---

<a name="section-1"></a>

- [Giris](#introduction "Giris")
- Temel Bilgiler
  - [Rota ekleme](#adding-routes "Rota ekleme")
  - [Sayfalara yonlendirme](#navigating-to-pages "Sayfalara yonlendirme")
  - [Baslangic rotasi](#initial-route "Baslangic rotasi")
  - [Onizleme Rotasi](#preview-route "Onizleme Rotasi")
  - [Kimlik dogrulamali rota](#authenticated-route "Kimlik dogrulamali rota")
  - [Bilinmeyen Rota](#unknown-route "Bilinmeyen Rota")
- Baska bir sayfaya veri gonderme
  - [Baska bir sayfaya veri aktarma](#passing-data-to-another-page "Baska bir sayfaya veri aktarma")
- Navigasyon
  - [Navigasyon turleri](#navigation-types "Navigasyon turleri")
  - [Geri gitme](#navigating-back "Geri gitme")
  - [Kosullu Navigasyon](#conditional-navigation "Kosullu Navigasyon")
  - [Sayfa gecisleri](#page-transitions "Sayfa gecisleri")
  - [Rota Gecmisi](#route-history "Rota Gecmisi")
  - [Rota Yiginini Guncelleme](#update-route-stack "Rota Yiginini Guncelleme")
- Rota parametreleri
  - [Rota Parametrelerini Kullanma](#route-parameters "Rota Parametrelerini Kullanma")
  - [Sorgu Parametreleri](#query-parameters "Sorgu Parametreleri")
- Rota Koruyuculari
  - [Rota Koruyuculari Olusturma](#route-guards "Rota Koruyuculari Olusturma")
  - [NyRouteGuard Yasam Dongusu](#nyroute-guard-lifecycle "NyRouteGuard Yasam Dongusu")
  - [Koruyucu Yardimci Metotlari](#guard-helper-methods "Koruyucu Yardimci Metotlari")
  - [Parametreli Koruyucular](#parameterized-guards "Parametreli Koruyucular")
  - [Koruyucu Yiginlari](#guard-stacks "Koruyucu Yiginlari")
  - [Kosullu Koruyucular](#conditional-guards "Kosullu Koruyucular")
- Rota Gruplari
  - [Rota Gruplari](#route-groups "Rota Gruplari")
- [Derin baglanti](#deep-linking "Derin baglanti")
- [Gelismis](#advanced "Gelismis")



<div id="introduction"></div>

## Giris

Rotalar, uygulamanizda farkli sayfalari tanimlamaniza ve aralarinda gezinmenize olanak tanir.

Rotalari su durumlarda kullanin:
- Uygulamanizda mevcut sayfalari tanimlamak
- Kullanicilari ekranlar arasinda yonlendirmek
- Sayfalari kimlik dogrulama arkasinda korumak
- Bir sayfadan digerine veri aktarmak
- URL'lerden gelen derin baglantilari yonetmek

Rotalari `lib/routes/router.dart` dosyasinin icine ekleyebilirsiniz.

``` dart
appRouter() => nyRoutes((router) {

  router.add(HomePage.path).initialRoute();

  router.add(PostsPage.path);

  router.add(PostDetailPage.path);

  // daha fazla rota ekle
  // router.add(AccountPage.path);

});
```

> **Ipucu:** Rotalarinizi manuel olarak olusturabilir veya <a href="/docs/{{ $version }}/metro">Metro</a> CLI aracini kullanarak otomatik olusturabilirsiniz.

Iste Metro kullanarak bir 'account' sayfasi olusturma ornegi.

``` bash
metro make:page account_page
```

``` dart
// Yeni rotanizi otomatik olarak /lib/routes/router.dart dosyasina ekler
appRouter() => nyRoutes((router) {
  ...
  router.add(AccountPage.path);
});
```

Bir gorunumden digerine veri aktarmaniz da gerekebilir. {{ config('app.name') }}'da bu, `NyStatefulWidget` (yerlesik rota veri erisimi olan durum bilgili bir widget) kullanilarak mumkundur. Nasil calistigini aciklamak icin daha derinlemesine inceleyecegiz.


<div id="adding-routes"></div>

## Rota ekleme

Projenize yeni rotalar eklemenin en kolay yolu budur.

Yeni bir sayfa olusturmak icin asagidaki komutu calistirin.

```bash
metro make:page profile_page
```

Yukaridaki komutu calistirdiktan sonra, `ProfilePage` adinda yeni bir Widget olusturur ve bunu `resources/pages/` dizinine ekler.
Ayrica yeni rotayi `lib/routes/router.dart` dosyaniza ekler.

Dosya: <b>/lib/routes/router.dart</b>

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.add(HomePage.path).initialRoute();

  // Yeni rotam
  router.add(ProfilePage.path);
});
```

<div id="navigating-to-pages"></div>

## Sayfalara yonlendirme

`routeTo` yardimcisini kullanarak yeni sayfalara yonlendirebilirsiniz.

``` dart
void _pressedSettings() {
    routeTo(SettingsPage.path);
}
```

<div id="initial-route"></div>

## Baslangic rotasi

Router'larinizda, `.initialRoute()` metodunu kullanarak yuklenmesi gereken ilk sayfalari tanimlayabilirsiniz.

Baslangic rotasini ayarladiktan sonra, uygulamayi actiginizda yuklenen ilk sayfa olacaktir.

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).initialRoute();
  // yeni baslangic rotasi
});
```


### Kosullu Baslangic Rotasi

`when` parametresini kullanarak kosullu bir baslangic rotasi da ayarlayabilirsiniz:

``` dart
appRouter() => nyRoutes((router) {
  router.add(OnboardingPage.path).initialRoute(
    when: () => !hasCompletedOnboarding()
  );

  router.add(HomePage.path).initialRoute(
    when: () => hasCompletedOnboarding()
  );
});
```

### Baslangic Rotasina Yonlendirme

Uygulamanin baslangic rotasina yonlendirmek icin `routeToInitial()` kullanin:

``` dart
void _goHome() {
    routeToInitial();
}
```

Bu, `.initialRoute()` ile isaretlenmis rotaya yonlendirir ve navigasyon yiginini temizler.

<div id="preview-route"></div>

## Onizleme Rotasi

Gelistirme sirasinda, baslangic rotanizi kalici olarak degistirmeden belirli bir sayfalari hizlica onizlemek isteyebilirsiniz. Herhangi bir rotayi gecici olarak baslangic rotasi yapmak icin `.previewRoute()` kullanin:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(SettingsPage.path);

  router.add(ProfilePage.path).previewRoute(); // Gelistirme sirasinda ilk gosterilecek olan
});
```

`previewRoute()` metodu:
- Mevcut `initialRoute()` ve `authenticatedRoute()` ayarlarini gecersiz kilar
- Belirtilen rotayi baslangic rotasi yapar
- Gelistirme sirasinda belirli sayfalari hizlica test etmek icin kullanislidir

> **Uyari:** Uygulamanizi yayinlamadan once `.previewRoute()` kaldirmayi unutmayin!

<div id="authenticated-route"></div>

## Kimlik Dogrulamali Rota

Uygulamanizda, kullanici kimligi dogrulandiginda baslangic rotasi olacak bir rota tanimlayabilirsiniz.
Bu, varsayilan baslangic rotasini otomatik olarak gecersiz kilar ve kullanicinin giris yaptiginda gordugu ilk sayfa olur.

Ilk olarak, kullanicimiz `Auth.authenticate({...})` yardimcisi kullanilarak giris yapmis olmalidir.

Artik uygulamayi actiklarinda, tanimladiginiz rota cikis yapana kadar varsayilan sayfa olacaktir.

``` dart
appRouter() => nyRoutes((router) {

  router.add(IntroPage.path).initialRoute();

  router.add(LoginPage.path);

  router.add(ProfilePage.path).authenticatedRoute();
  // kimlik dogrulamali sayfa
});
```

### Kosullu Kimlik Dogrulamali Rota

Kosullu bir kimlik dogrulamali rota da ayarlayabilirsiniz:

``` dart
router.add(ProfilePage.path).authenticatedRoute(
  when: () => hasCompletedSetup()
);
```

### Kimlik Dogrulamali Rotaya Yonlendirme

`routeToAuthenticatedRoute()` yardimcisini kullanarak kimlik dogrulamali sayfaya yonlendirebilirsiniz:

``` dart
routeToAuthenticatedRoute();
```

**Ayrica bakiniz:** [Kimlik Dogrulama](/docs/{{ $version }}/authentication) kullanicilarin kimlik dogrulamasi ve oturum yonetimi hakkinda detaylar icin.


<div id="unknown-route"></div>

## Bilinmeyen Rota

`.unknownRoute()` kullanarak 404/bulunamadi senaryolarini yonetecek bir rota tanimlayabilirsiniz:

``` dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path).initialRoute();

  router.add(NotFoundPage.path).unknownRoute();
});
```

Bir kullanici var olmayan bir rotaya yonlendiginde, bilinmeyen rota sayfasi gosterilecektir.


<div id="route-guards"></div>

## Rota koruyuculari

Rota koruyuculari, sayfalarinizi yetkisiz erisimden korur. Navigasyon tamamlanmadan once calisir ve kosullara gore kullanicilari yonlendirmenize veya erisimi engellemenize olanak tanir.

Rota koruyucularini su durumlarda kullanin:
- Sayfalari kimlik dogrulanmamis kullanicilardan korumak
- Erisime izin vermeden once yetkileri kontrol etmek
- Kosullara gore kullanicilari yonlendirmek (orn. tamamlanmamis onboarding)
- Sayfa goruntulemelerini kaydetmek veya izlemek

Yeni bir Rota Koruyucusu olusturmak icin asagidaki komutu calistirin.

``` bash
metro make:route_guard dashboard
```

Ardindan, yeni Rota Koruyucusunu rotaniza ekleyin.

``` dart
// Dosya: /routes/router.dart
appRouter() => nyRoutes((router) {
  router.add(HomePage.path);

  router.add(LoginPage.path);

  router.add(DashboardPage.path,
    routeGuards: [
      DashboardRouteGuard() // Koruyucunuzu ekleyin
    ]
  ); // kisitli sayfa
});
```

`addRouteGuard` metodunu kullanarak da rota koruyuculari ayarlayabilirsiniz:

``` dart
// Dosya: /routes/router.dart
appRouter() => nyRoutes((router) {
    router.add(DashboardPage.path)
            .addRouteGuard(MyRouteGuard());

    // veya birden fazla koruyucu ekle

    router.add(DashboardPage.path)
            .addRouteGuards([MyRouteGuard(), MyOtherRouteGuard()]);
})
```

<div id="nyroute-guard-lifecycle"></div>

## NyRouteGuard Yasam Dongusu

v7'de rota koruyuculari uc yasam dongusu metoduna sahip `NyRouteGuard` sinifini kullanir:

- **`onBefore(RouteContext context)`** - Navigasyondan once cagrilir. Devam etmek icin `next()`, baska yere yonlendirmek icin `redirect()`, veya durdurmak icin `abort()` dondurun.
- **`onAfter(RouteContext context)`** - Rotaya basarili navigasyondan sonra cagrilir.

### Temel Ornek

Dosya: **/routes/guards/dashboard_route_guard.dart**
``` dart
class DashboardRouteGuard extends NyRouteGuard {
  DashboardRouteGuard();

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    // Sayfaya erisip erisemeyeceklerini kontrol et
    bool userLoggedIn = await Auth.isAuthenticated();

    if (userLoggedIn == false) {
      return redirect(LoginPage.path);
    }

    return next();
  }

  @override
  Future<void> onAfter(RouteContext context) async {
    // Basarili navigasyondan sonra sayfa goruntulemesini kaydet
    Analytics.trackPageView(context.routeName);
  }
}
```

### RouteContext

`RouteContext` sinifi navigasyon bilgilerine erisim saglar:

| Ozellik | Tur | Aciklama |
|---------|-----|----------|
| `context` | `BuildContext?` | Mevcut build context |
| `data` | `dynamic` | Rotaya aktarilan veri |
| `queryParameters` | `Map<String, String>` | URL sorgu parametreleri |
| `routeName` | `String` | Rota adi/yolu |
| `originalRouteName` | `String?` | Donusumlerden onceki orijinal rota adi |

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  print('Yonlendiriliyor: ${context.routeName}');
  print('Sorgu parametreleri: ${context.queryParameters}');
  print('Rota verisi: ${context.data}');

  return next();
}
```

<div id="guard-helper-methods"></div>

## Koruyucu Yardimci Metotlari

### next()

Sonraki koruyucuya veya rotaya devam et:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  return next(); // Navigasyonun devam etmesine izin ver
}
```

### redirect()

Farkli bir rotaya yonlendir:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (!isLoggedIn) {
    return redirect(
      LoginPage.path,
      data: {'returnTo': context.routeName},
      navigationType: NavigationType.pushReplace,
    );
  }
  return next();
}
```

`redirect()` metodu sunlari kabul eder:

| Parametre | Tur | Aciklama |
|-----------|-----|----------|
| `path` | `Object` | Rota yolu veya RouteView |
| `data` | `dynamic` | Rotaya aktarilacak veri |
| `queryParameters` | `Map<String, dynamic>?` | Sorgu parametreleri |
| `navigationType` | `NavigationType` | Navigasyon turu (varsayilan: pushReplace) |
| `transitionType` | `TransitionType?` | Sayfa gecisi |
| `onPop` | `Function(dynamic)?` | Rota geri dondugunde callback |

### abort()

Yonlendirmeden navigasyonu durdur:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  if (isMaintenanceMode) {
    showMaintenanceDialog();
    return abort(); // Kullanici mevcut rotada kalir
  }
  return next();
}
```

### setData()

Sonraki koruyuculara ve rotaya aktarilan veriyi degistir:

``` dart
@override
Future<GuardResult> onBefore(RouteContext context) async {
  final user = await fetchUser();
  setData({'user': user, ...?context.data});
  return next();
}
```

<div id="parameterized-guards"></div>

## Parametreli Koruyucular

Koruyucu davranisini rota bazinda yapilandirmaniz gerektiginde `ParameterizedGuard` kullanin:

``` dart
class RoleGuard extends ParameterizedGuard<List<String>> {
  RoleGuard(super.params);

  @override
  Future<GuardResult> onBefore(RouteContext context) async {
    final user = await Auth.user();
    if (!params.any((role) => user.hasRole(role))) {
      return redirect('/unauthorized');
    }
    return next();
  }
}

// Kullanim:
router.add(AdminPage.path, routeGuards: [
  RoleGuard(['admin', 'moderator'])
]);
```

<div id="guard-stacks"></div>

## Koruyucu Yiginlari

`GuardStack` kullanarak birden fazla koruyucuyu tek bir yeniden kullanilabilir koruyucu olarak birlestirin:

``` dart
// Yeniden kullanilabilir koruyucu kombinasyonlari olustur
final adminGuards = GuardStack([
  AuthGuard(),
  RoleGuard(['admin']),
  AuditLogGuard(),
]);

router.add(AdminPage.path, routeGuards: [adminGuards]);
```

<div id="conditional-guards"></div>

## Kosullu Koruyucular

Bir kosula bagli olarak koruyuculari kosullu olarak uygulayin:

``` dart
router.add(DashboardPage.path, routeGuards: [
  ConditionalGuard(
    condition: (context) => context.routeName.startsWith('/admin'),
    guard: AdminGuard(),
  )
]);
```


<div id="passing-data-to-another-page"></div>

## Baska bir sayfaya veri aktarma

Bu bolumde, bir widget'tan digerine nasil veri aktarabileceginizi gosterecegiz.

Widget'inizdan `routeTo` yardimcisini kullanin ve yeni sayfaya gondermek istediginiz `data` degerini aktarin.

``` dart
// HomePage Widget'i
void _pressedSettings() {
    routeTo(SettingsPage.path, data: "Hello World");
}
...
// SettingsPage Widget'i'i (diger sayfa)
class _SettingsPageState extends NyPage<SettingsPage> {
  ...
  @override
  get init => () {
    print(widget.data()); // Hello World
    // veya
    print(data()); // Hello World
  };
```

Daha fazla ornek

``` dart
// Ana sayfa widget'i
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    User user = new User();
    user.firstName = 'Anthony';

    routeTo(ProfilePage.path, data: user);
  }

...

// Profil sayfasi widget'i (diger sayfa)
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    User user = widget.data();
    print(user.firstName); // Anthony

  };
```


<div id="route-groups"></div>

## Rota Gruplari

Rota gruplari ilgili rotalari organize eder ve paylasilan ayarlari uygular. Birden fazla rota ayni koruyuculara, URL onekine veya gecis stiline ihtiyac duyduklarinda kullanislidir.

Rota gruplarini su durumlarda kullanin:
- Ayni rota koruyucusunu birden fazla sayfaya uygulamak
- Bir rota grubuna URL oneki eklemek (orn. `/admin/...`)
- Ilgili rotalar icin ayni sayfa gecisini ayarlamak

Asagidaki ornekteki gibi bir rota grubu tanimlayabilirsiniz.

``` dart
appRouter() => nyRoutes((router) {
  ...
  router.group(() => {
    "route_guards": [AuthRouteGuard()],
    "prefix": "/dashboard",
    "transition_type": TransitionType.fade(),
  }, (router) {
    router.add(ChatPage.path);

    router.add(FollowersPage.path);
  });
```

#### Rota gruplari icin istege bagli ayarlar:

| Ayar | Tur | Aciklama |
|------|-----|----------|
| `route_guards` | `List<RouteGuard>` | Gruptaki tum rotalara rota koruyuculari uygula |
| `prefix` | `String` | Gruptaki tum rota yollarina onek ekle |
| `transition_type` | `TransitionType` | Gruptaki tum rotalar icin gecis ayarla |
| `transition` | `PageTransitionType` | Sayfa gecis turunu ayarla (kullanimdan kaldirildi, transition_type kullanin) |
| `transition_settings` | `PageTransitionSettings` | Gecis ayarlarini belirle |


<div id="route-parameters"></div>

## Rota Parametrelerini Kullanma

Yeni bir sayfa olusturduguzda, rotayi parametre kabul edecek sekilde guncelleyebilirsiniz.

``` dart
class ProfilePage extends NyStatefulWidget<HomeController> {
  static RouteView path = ("/profile/{userId}", (_) => ProfilePage());

  ProfilePage() : super(child: () => _ProfilePageState());
}
```

Simdi, sayfaya yonlendirdiginizde, `userId` parametresini gecebilirsiniz

``` dart
routeTo(ProfilePage.path.withParams({"userId": 7}));
```

Yeni sayfada parametrelere su sekilde erisebilirsiniz.

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () {
    print(widget.queryParameters()); // {"userId": 7}
  };
}
```


<div id="query-parameters"></div>

## Sorgu Parametreleri

Yeni bir sayfaya yonlendirirken sorgu parametreleri de saglayabilirsiniz.

Bir goz atalim.

```dart
  // Ana sayfa
  routeTo(ProfilePage.path, queryParameters: {"user": "7"});
  // profil sayfasina gec

  ...

  // Profil Sayfasi
  @override
  get init => () {
    print(widget.queryParameters()); // {"user": 7}
    // veya
    print(queryParameters()); // {"user": 7}
  };
```

> **Not:** Sayfa widget'iniz `NyStatefulWidget` ve `NyPage` sinifini genisletigi surece, rota adindan tum sorgu parametrelerini almak icin `widget.queryParameters()` cagirabilirsiniz.

```dart
// Ornek sayfa
routeTo(ProfilePage.path, queryParameters: {"hello": "world", "say": "I love code"});
...

// Ana sayfa
class MyHomePage extends NyStatefulWidget<HomeController> {
  ...
}

class _MyHomePageState extends NyPage<MyHomePage> {

  @override
  get init => () {
    widget.queryParameters(); // {"hello": "World", "say": "I love code"}
    // veya
    queryParameters(); // {"hello": "World", "say": "I love code"}
  };
```

> **Ipucu:** Sorgu parametreleri HTTP protokolunu takip etmelidir, Orn. /account?userId=1&tab=2


<div id="page-transitions"></div>

## Sayfa Gecisleri

`router.dart` dosyanizi degistirerek bir sayfadan digerine gecis yaparken gecis efektleri ekleyebilirsiniz.

``` dart
import 'package:page_transition/page_transition.dart';

appRouter() => nyRoutes((router) {

  // bottomToTop
  router.add(SettingsPage.path,
    transitionType: TransitionType.bottomToTop()
  );

  // fade
  router.add(HomePage.path,
    transitionType: TransitionType.fade()
  );

});
```

### Mevcut Sayfa Gecisleri

#### Temel Gecisler
- **`TransitionType.fade()`** - Eski sayfa solar, yeni sayfa belirir
- **`TransitionType.theme()`** - Uygulama temasinin sayfa gecis temasini kullanir

#### Yonlu Kaydirma Gecisleri
- **`TransitionType.rightToLeft()`** - Ekranin sag kenarindan kayar
- **`TransitionType.leftToRight()`** - Ekranin sol kenarindan kayar
- **`TransitionType.topToBottom()`** - Ekranin ust kenarindan kayar
- **`TransitionType.bottomToTop()`** - Ekranin alt kenarindan kayar

#### Solmali Kaydirma Gecisleri
- **`TransitionType.rightToLeftWithFade()`** - Sag kenardan kayar ve solar
- **`TransitionType.leftToRightWithFade()`** - Sol kenardan kayar ve solar

#### Donusum Gecisleri
- **`TransitionType.scale(alignment: ...)`** - Belirtilen hizalama noktasindan olceklenir
- **`TransitionType.rotate(alignment: ...)`** - Belirtilen hizalama noktasi etrafinda doner
- **`TransitionType.size(alignment: ...)`** - Belirtilen hizalama noktasindan buyur

#### Birlesik Gecisler (Mevcut widget gerektirir)
- **`TransitionType.leftToRightJoined(childCurrent: ...)`** - Mevcut sayfa saga cikar, yeni sayfa soldan girer
- **`TransitionType.rightToLeftJoined(childCurrent: ...)`** - Mevcut sayfa sola cikar, yeni sayfa sagdan girer
- **`TransitionType.topToBottomJoined(childCurrent: ...)`** - Mevcut sayfa asagi cikar, yeni sayfa yukaridan girer
- **`TransitionType.bottomToTopJoined(childCurrent: ...)`** - Mevcut sayfa yukari cikar, yeni sayfa asagidan girer

#### Pop Gecisleri (Mevcut widget gerektirir)
- **`TransitionType.leftToRightPop(childCurrent: ...)`** - Mevcut sayfa saga cikar, yeni sayfa yerinde kalir
- **`TransitionType.rightToLeftPop(childCurrent: ...)`** - Mevcut sayfa sola cikar, yeni sayfa yerinde kalir
- **`TransitionType.topToBottomPop(childCurrent: ...)`** - Mevcut sayfa asagi cikar, yeni sayfa yerinde kalir
- **`TransitionType.bottomToTopPop(childCurrent: ...)`** - Mevcut sayfa yukari cikar, yeni sayfa yerinde kalir

#### Material Design Paylasimli Eksen Gecisleri
- **`TransitionType.sharedAxisHorizontal()`** - Yatay kaydirma ve solma gecisi
- **`TransitionType.sharedAxisVertical()`** - Dikey kaydirma ve solma gecisi
- **`TransitionType.sharedAxisScale()`** - Olcekleme ve solma gecisi

#### Ozellestirme Parametreleri
Her gecis asagidaki istege bagli parametreleri kabul eder:

| Parametre | Aciklama | Varsayilan |
|-----------|----------|-----------|
| `curve` | Animasyon egrisi | Platforma ozgu egriler |
| `duration` | Animasyon suresi | Platforma ozgu sureler |
| `reverseDuration` | Ters animasyon suresi | duration ile ayni |
| `fullscreenDialog` | Rotanin tam ekran diyalog olup olmadigi | `false` |
| `opaque` | Rotanin opak olup olmadigi | `false` |


``` dart
// Ana sayfa widget'i
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(ProfilePage.path,
      transitionType: TransitionType.bottomToTop()
    );
  }
...
```


<div id="navigation-types"></div>

## Navigasyon Turleri

Yonlendirme yaparken, `routeTo` yardimcisini kullaniyorsaniz asagidakilerden birini belirtebilirsiniz.

| Tur | Aciklama |
|-----|----------|
| `NavigationType.push` | Uygulamanizin rota yiginina yeni bir sayfa ekle |
| `NavigationType.pushReplace` | Mevcut rotayi degistir, yeni rota tamamlandiginda onceki rotayi kaldir |
| `NavigationType.popAndPushNamed` | Mevcut rotayi navigatordan cikar ve yerine adlandirilmis bir rota ekle |
| `NavigationType.pushAndRemoveUntil` | Kosul dogru olana kadar rotalari ekle ve kaldir |
| `NavigationType.pushAndForgetAll` | Yeni bir sayfaya git ve rota yiginindaki diger tum sayfalari kaldir |

``` dart
// Ana sayfa widget'i
class _HomePageState extends NyPage<HomePage> {

  _showProfile() {
    routeTo(
      ProfilePage.path,
      navigationType: NavigationType.pushReplace
    );
  }
...
```


<div id="navigating-back"></div>

## Geri gitme

Yeni sayfadayken, mevcut sayfaya geri donmek icin `pop()` yardimcisini kullanabilirsiniz.

``` dart
// SettingsPage Widget'i
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop();
    // veya
    Navigator.pop(context);
  }
...
```

Onceki widget'a bir deger dondurmek istiyorsaniz, asagidaki ornekteki gibi bir `result` saglayim.

``` dart
// SettingsPage Widget'i
class _SettingsPageState extends NyPage<SettingsPage> {

  _back() {
    pop(result: {"status": "COMPLETE"});
  }

...

// Onceki widget'tan degeri `onPop` parametresi ile al
// HomePage Widget'i
class _HomePageState extends NyPage<HomePage> {

  _viewSettings() {
    routeTo(SettingsPage.path, onPop: (value) {
      print(value); // {"status": "COMPLETE"}
    });
  }
...

```

<div id="conditional-navigation"></div>

## Kosullu Navigasyon

Yalnizca bir kosul karsilandiginda yonlendirme yapmak icin `routeIf()` kullanin:

``` dart
// Yalnizca kullanici giris yapmissa yonlendir
routeIf(isLoggedIn, DashboardPage.path);

// Ek seceneklerle
routeIf(
  hasPermission('view_reports'),
  ReportsPage.path,
  data: {'filters': defaultFilters},
  navigationType: NavigationType.push,
);
```

Kosul `false` ise, navigasyon gerceklesmez.


<div id="route-history"></div>

## Rota Gecmisi

{{ config('app.name') }}'da, asagidaki yardimcilari kullanarak rota gecmisi bilgilerine erisebilirsiniz.

``` dart
// Rota gecmisini al
Nylo.getRouteHistory(); // List<dynamic>

// Mevcut rotayi al
Nylo.getCurrentRoute(); // Route<dynamic>?

// Onceki rotayi al
Nylo.getPreviousRoute(); // Route<dynamic>?

// Mevcut rota adini al
Nylo.getCurrentRouteName(); // String?

// Onceki rota adini al
Nylo.getPreviousRouteName(); // String?

// Mevcut rota argumanlarini al
Nylo.getCurrentRouteArguments(); // dynamic

// Onceki rota argumanlarini al
Nylo.getPreviousRouteArguments(); // dynamic
```


<div id="update-route-stack"></div>

## Rota Yiginini Guncelleme

`NyNavigator.updateStack()` kullanarak navigasyon yiginini programatik olarak guncelleyebilirsiniz:

``` dart
// Yigini rota listesiyle guncelle
NyNavigator.updateStack([
  HomePage.path,
  SettingsPage.path,
  ProfilePage.path,
], replace: true);

// Belirli rotalara veri aktar
NyNavigator.updateStack([
  HomePage.path,
  ProfilePage.path,
],
  replace: true,
  dataForRoute: {
    ProfilePage.path: {"userId": 42}
  }
);
```

| Parametre | Tur | Varsayilan | Aciklama |
|-----------|-----|-----------|----------|
| `routes` | `List<String>` | gerekli | Yonlendirilecek rota yollarinin listesi |
| `replace` | `bool` | `true` | Mevcut yiginin degistirilip degistirilmeyecegi |
| `dataForRoute` | `Map<String, dynamic>?` | `null` | Belirli rotalara aktarilacak veri |

Bu sunlar icin kullanislidir:
- Derin baglanti senaryolari
- Navigasyon durumunu geri yukleme
- Karmasik navigasyon akislari olusturma


<div id="deep-linking"></div>

## Derin Baglanti

Derin baglanti, kullanicilarin URL'ler kullanarak uygulamanizdaki belirli iceriklere dogrudan ulasmasini saglar. Bu sunlar icin kullanislidir:
- Belirli uygulama icerigine dogrudan baglantilar paylasma
- Belirli uygulama ici ozellikleri hedefleyen pazarlama kampanyalari
- Belirli uygulama ekranlarini acmasi gereken bildirimleri yonetme
- Kesintisiz web'den uygulamaya gecisler

## Kurulum

Uygulamanizda derin baglantiyi uygulamadan once, projenizin duzgun yapilandirildigindan emin olun:

### 1. Platform Yapilandirmasi

**iOS**: Xcode projenizde universal link'leri yapilandirin
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-universal-links#adjust-ios-build-settings" target="_BLANK">Flutter Universal Links Yapilandirma Kilavuzu</a>

**Android**: AndroidManifest.xml dosyanizda uygulama baglantilarini ayarlayin
- <a href="https://docs.flutter.dev/cookbook/navigation/set-up-app-links#2-modify-androidmanifest-xml" target="_BLANK">Flutter Uygulama Baglantilari Yapilandirma Kilavuzu</a>

### 2. Rotalarinizi Tanimlayin

Derin baglantilar araciligiyla erisilebilir olmasi gereken tum rotalar, router yapilandirmanizda kayitli olmalidir:

```dart
// Dosya: /lib/routes/router.dart
appRouter() => nyRoutes((router) {
  // Temel rotalar
  router.add(HomePage.path).initialRoute();
  router.add(ProfilePage.path);
  router.add(SettingsPage.path);

  // Parametreli rota
  router.add(HotelBookingPage.path);
});
```

## Derin Baglantilari Kullanma

Yapilandirildiktan sonra, uygulamaniz cesitli formatlarda gelen URL'leri isleyebilir:

### Temel Derin Baglantilar

Belirli sayfalara basit navigasyon:

``` bash
https://yourdomain.com/profile       // Profil sayfasini acar
https://yourdomain.com/settings      // Ayarlar sayfasini acar
```

Bu navigasyonlari uygulamaniz icinde programatik olarak tetiklemek icin:

```dart
routeTo(ProfilePage.path);
routeTo(SettingsPage.path);
```

### Yol Parametreleri

Yolun parcasi olarak dinamik veri gerektiren rotalar icin:

#### Rota Tanimi

```dart
class HotelBookingPage extends NyStatefulWidget {
  // {id} parametre yer tutucu ile bir rota tanimla
  static RouteView path = ("/hotel/{id}/booking", (_) => HotelBookingPage());

  HotelBookingPage({super.key}) : super(child: () => _HotelBookingPageState());
}

class _HotelBookingPageState extends NyPage<HotelBookingPage> {
  @override
  get init => () {
    // Yol parametresine eris
    final hotelId = queryParameters()["id"]; // ../hotel/87/booking URL'si icin "87" dondurur
    print("Loading hotel ID: $hotelId");

    // Otel verisini cekme veya islem yapmak icin ID'yi kullan
  };

  // Sayfanizin geri kalan implementasyonu
}
```

#### URL Formati

``` bash
https://yourdomain.com/hotel/87/booking
```

#### Programatik Navigasyon

```dart
// Parametrelerle yonlendir
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "bookings": "active",
            });
```

### Sorgu Parametreleri

Istege bagli parametreler veya birden fazla dinamik deger gerektiginde:

#### URL Formati

``` bash
https://yourdomain.com/profile?user=20&tab=posts
https://yourdomain.com/hotel/87/booking?checkIn=2025-04-10&nights=3
```

#### Sorgu Parametrelerine Erisim

```dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  get init => () {
    // Tum sorgu parametrelerini al
    final params = queryParameters();

    // Belirli parametrelere eris
    final userId = params["user"];            // "20"
    final activeTab = params["tab"];          // "posts"

    // Alternatif erisim yontemi
    final params2 = widget.queryParameters();
    print(params2);                           // {"user": "20", "tab": "posts"}
  };
}
```

#### Sorgu Parametreleriyle Programatik Navigasyon

```dart
// Sorgu parametreleriyle yonlendir
routeTo(ProfilePage.path.withQueryParams({"user": "20", "tab": "posts"}));

// Yol ve sorgu parametrelerini birlestir
routeTo(HotelBookingPage.path.withParams({"id": "87"}), queryParameters: {
              "checkIn": "2025-04-10",
              "nights": "3",
            });
```

## Derin Baglantilari Yonetme

Derin baglanti olaylarini `RouteProvider`'inizda yonetebilirsiniz:

```dart
class RouteProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    nylo.addRouter(appRouter());

    // Derin baglantilari isle
    nylo.onDeepLink(_onDeepLink);
    return nylo;
  }

  _onDeepLink(String route, Map<String, String>? data) {
    print("Deep link route: $route");
    print("Deep link data: $data");

    // Derin baglantilar icin rota yiginini guncelle
    if (route == ProfilePage.path) {
      NyNavigator.updateStack([
        HomePage.path,
        ProfilePage.path,
      ], replace: true, dataForRoute: {
        ProfilePage.path: data,
      });
    }
  }

  @override
  boot(Nylo nylo) async {
    nylo.initRoutes();
  }
}
```

### Derin Baglantilari Test Etme

Gelistirme ve test icin, ADB (Android) veya xcrun (iOS) kullanarak derin baglanti aktivasyonunu simule edebilirsiniz:

```bash
# Android
adb shell am start -a android.intent.action.VIEW -d "https://yourdomain.com/profile?user=20" com.yourcompany.yourapp

# iOS (Simulator)
xcrun simctl openurl booted "https://yourdomain.com/profile?user=20"
```

### Hata Ayiklama Ipuclari

- Dogru ayristirmayi dogrulamak icin init metodunuzda tum parametreleri yazdirin
- Uygulamanizin bunlari dogru sekilde islediginden emin olmak icin farkli URL formatlarini test edin
- Sorgu parametrelerinin her zaman dize olarak alindigini unutmayin, gerektiginde uygun ture donusturun

---

## Yaygin Desenler

### Parametre Turu Donusturme

Tum URL parametreleri dize olarak aktarildigindan, genellikle donusturmeniz gerekecektir:

```dart
// Metin parametrelerini uygun turlere donustur
final hotelId = int.parse(queryParameters()["id"] ?? "0");
final isAvailable = (queryParameters()["available"] ?? "false") == "true";
final checkInDate = DateTime.parse(queryParameters()["checkIn"] ?? "");
```

### Istege Bagli Parametreler

Parametrelerin eksik olabilecegi durumlari yonetin:

```dart
final userId = queryParameters()["user"];
if (userId != null) {
  // Belirli kullanici profilini yukle
} else {
  // Mevcut kullanici profilini yukle
}

// Veya hasQueryParameter ile kontrol et
if (hasQueryParameter('status')) {
  // status parametresiyle bir seyler yap
} else {
  // Parametrenin yoklugunu isle
}
```


<div id="advanced"></div>

## Gelismis

### Rotanin Var Olup Olmadigini Kontrol Etme

Router'inizda bir rotanin kayitli olup olmadigini kontrol edebilirsiniz:

``` dart
if (Nylo.containsRoute("/profile")) {
  routeTo("/profile");
}
```

### NyRouter Metotlari

`NyRouter` sinifi bircok kullanisli metot sunar:

| Metot | Aciklama |
|-------|----------|
| `getRegisteredRouteNames()` | Tum kayitli rota adlarini liste olarak al |
| `getRegisteredRoutes()` | Tum kayitli rotalari map olarak al |
| `containsRoutes(routes)` | Router'in belirtilen tum rotalari icerip icermedigini kontrol et |
| `getInitialRouteName()` | Baslangic rota adini al |
| `getAuthRouteName()` | Kimlik dogrulamali rota adini al |
| `getUnknownRouteName()` | Bilinmeyen/404 rota adini al |

### Rota Argumanlarini Alma

`NyRouter.args<T>()` kullanarak rota argumanlarini alabilirsiniz:

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {
  @override
  Widget build(BuildContext context) {
    // Tipli argumanlari al
    final args = NyRouter.args<NyArgument>(context);
    final userData = args?.data;

    return Scaffold(...);
  }
}
```

### NyArgument ve NyQueryParameters

Rotalar arasinda aktarilan veri bu siniflarda sarilir:

``` dart
// NyArgument rota verisini icerir
NyArgument argument = NyArgument({'userId': 42});
print(argument.data); // {'userId': 42}

// NyQueryParameters URL sorgu parametrelerini icerir
NyQueryParameters params = NyQueryParameters({'tab': 'posts'});
print(params.data); // {'tab': 'posts'}
```
