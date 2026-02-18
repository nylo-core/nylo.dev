# NyState

---

<a name="section-1"></a>
- [Giriş](#introduction "Giriş")
- [NyState Nasıl Kullanılır](#how-to-use-nystate "NyState Nasıl Kullanılır")
- [Yükleme Stili](#loading-style "Yükleme Stili")
- [Durum Eylemleri](#state-actions "Durum Eylemleri")
- [Yardımcılar](#helpers "Yardımcılar")


<div id="introduction"></div>

## Giriş

`NyState`, standart Flutter `State` sınıfının genişletilmiş bir versiyonudur. Sayfalarınızın ve widget'larınızın durumunu daha verimli bir şekilde yönetmenize yardımcı olmak için ek işlevsellik sağlar.

Durumla normal bir Flutter durumu gibi **etkileşim** kurabilirsiniz, ancak NyState'in ek avantajlarıyla birlikte.

NyState'in nasıl kullanılacağını inceleyelim.

<div id="how-to-use-nystate"></div>

## NyState Nasıl Kullanılır

Bu sınıfı genişleterek kullanmaya başlayabilirsiniz.

Örnek

``` dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {

  };

  @override
  view(BuildContext context) {
    return Scaffold(
        body: Text("The page loaded")
    );
  }
```

`init` metodu, sayfanın durumunu başlatmak için kullanılır. Bu metodu async ile veya async olmadan kullanabilirsiniz; arka planda async çağrıyı yönetecek ve bir yükleyici görüntüleyecektir.

`view` metodu, sayfa için arayüzü görüntülemek amacıyla kullanılır.

#### NyState ile yeni bir stateful widget oluşturma

{{ config('app.name') }}'da yeni bir stateful widget oluşturmak için aşağıdaki komutu çalıştırabilirsiniz.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Yükleme Stili

Sayfanız için yükleme stilini ayarlamak amacıyla `loadingStyle` özelliğini kullanabilirsiniz.

Örnek

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**Varsayılan** `loadingStyle`, yükleme Widget'ınız (resources/widgets/loader_widget.dart) olacaktır.
Yükleme stilini güncellemek için `loadingStyle`'ı özelleştirebilirsiniz.

İşte kullanabileceğiniz farklı yükleme stillerinin tablosu:
// normal, skeletonizer, none

| Stil | Açıklama |
| --- | --- |
| normal | Varsayılan yükleme stili |
| skeletonizer | İskelet yükleme stili |
| none | Yükleme stili yok |

Yükleme stilini şu şekilde değiştirebilirsiniz:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Stillerden birindeki yükleme Widget'ını güncellemek istiyorsanız, `LoadingStyle`'a bir `child` geçirebilirsiniz.

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
// same for skeletonizer
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer(
    child: Container(
        child: PageLayoutForSkeletonizer(),
    )
);
```

Şimdi, sekme yüklenirken "Loading..." metni görüntülenecektir.

Aşağıdaki örnek:

``` dart
class _HomePageState extends NyState<HomePage> {
    get init => () async {
        await sleep(3); // simulate a network call for 3 seconds
    };

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );

    @override
    Widget view(BuildContext context) {
        return Scaffold(
            body: Text("The page loaded")
        );
    }
    ...
}
```

<div id="state-actions"></div>

## Durum Eylemleri

Nylo'da, Widget'larınızda diğer sınıflardan çağrılabilecek küçük **eylemler** tanımlayabilirsiniz. Bu, bir widget'ın durumunu başka bir sınıftan güncellemek istediğinizde kullanışlıdır.

Öncelikle, eylemlerinizi widget'ınızda **tanımlamalısınız**. Bu, `NyState` ve `NyPage` için çalışır.

``` dart
class _MyWidgetState extends NyState<MyWidget> {

  @override
  get init => () async {
    // handle how you want to initialize the state
  };

  @override
  get stateActions => {
    "hello_world_in_widget": () {
      print('Hello world');
    },
    "update_user_name": (User user) async {
      // Example with data
      _userName = user.name;
      setState(() {});
    },
    "show_toast": (String message) async {
      showToastSuccess(description: message);
    },
  };
}
```

Ardından, `stateAction` metodunu kullanarak eylemi başka bir sınıftan çağırabilirsiniz.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

stateActions'ı bir `NyPage` ile kullanıyorsanız, sayfanın **path**'ini kullanmalısınız.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

`StateAction` adında başka bir sınıf da vardır; widget'larınızın durumunu güncellemek için kullanabileceğiniz birkaç metodu bulunmaktadır.

- `refreshPage` - Sayfayı yenile.
- `pop` - Sayfayı kaldır.
- `showToastSorry` - Üzgünüz toast bildirimi göster.
- `showToastWarning` - Uyarı toast bildirimi göster.
- `showToastInfo` - Bilgi toast bildirimi göster.
- `showToastDanger` - Tehlike toast bildirimi göster.
- `showToastOops` - Hata toast bildirimi göster.
- `showToastSuccess` - Başarı toast bildirimi göster.
- `showToastCustom` - Özel toast bildirimi göster.
- `validate` - Widget'ınızdaki verileri doğrula.
- `changeLanguage` - Uygulamadaki dili değiştir.
- `confirmAction` - Onay eylemi gerçekleştir.

Örnek

``` dart
class _UpgradeButtonState extends NyState<UpgradeButton> {

  view(BuildContext context) {
    return Button.primary(
      onPressed: () {
        StateAction.showToastSuccess(UpgradePage.state,
          description: "You have successfully upgraded your account",
        );
      },
      text: "Upgrade",
    );
  }
}
```

Widget durum yönetimli olduğu sürece, uygulamanızdaki herhangi bir sayfa/widget'ın durumunu güncellemek için `StateAction` sınıfını kullanabilirsiniz.

<div id="helpers"></div>

## Yardımcılar

|  |  |
| --- | ----------- |
| [lockRelease](#lock-release "lockRelease") |
| [showToast](#showToast "showToast") | [isLoading](#is-loading "isLoading") |
| [validate](#validate "validate") | [afterLoad](#after-load "afterLoad") |
| [afterNotLocked](#afterNotLocked "afterNotLocked") | [afterNotNull](#after-not-null "afterNotNull") |
| [whenEnv](#when-env "whenEnv") | [setLoading](#set-loading "setLoading") |
| [pop](#pop "pop") | [isLocked](#is-locked "isLocked") |
| [changeLanguage](#change-language "changeLanguage") | [confirmAction](#confirm-action "confirmAction") |
| [showToastSuccess](#show-toast-success "showToastSuccess") | [showToastOops](#show-toast-oops "showToastOops") |
| [showToastDanger](#show-toast-danger "showToastDanger") | [showToastInfo](#show-toast-info "showToastInfo") |
| [showToastWarning](#show-toast-warning "showToastWarning") | [showToastSorry](#show-toast-sorry "showToastSorry") |


<div id="reboot"></div>

### Reboot

Bu metot, durumunuzdaki `init` metodunu yeniden çalıştıracaktır. Sayfadaki verileri yenilemek istediğinizde kullanışlıdır.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  List<User> users = [];

  @override
  get init => () async {
    users = await api<ApiService>((request) => request.fetchUsers());
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        appBar: AppBar(
          title: Text("Users"),
          actions: [
            IconButton(
              icon: Icon(Icons.refresh),
              onPressed: () {
                reboot(); // refresh the data
              },
            )
          ],
        ),
        body: ListView.builder(
          itemCount: users.length,
          itemBuilder: (context, index) {
            return Text(users[index].firstName);
          }
        ),
    );
  }
}
```

<div id="pop"></div>

### Pop

`pop` - Mevcut sayfayı yığından kaldırır.

Örnek

``` dart
class _HomePageState extends NyState<HomePage> {

  popView() {
    pop();
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: popView,
        child: Text("Pop current view")
      )
    );
  }
```


<div id="showToast"></div>

### showToast

Bağlamda bir toast bildirimi gösterir.

Örnek

```dart
class _HomePageState extends NyState<HomePage> {

  displayToast() {
    showToast(
        title: "Hello",
        description: "World",
        icon: Icons.account_circle,
        duration: Duration(seconds: 2),
        style: ToastNotificationStyleType.INFO // SUCCESS, INFO, DANGER, WARNING
    );
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: displayToast,
        child: Text("Display a toast")
      )
    );
  }
```


<div id="validate"></div>

### validate

`validate` yardımcısı, veriler üzerinde bir doğrulama kontrolü gerçekleştirir.

Doğrulayıcı hakkında daha fazla bilgiyi <a href="/docs/{{$version}}/validation" target="_BLANK">buradan</a> öğrenebilirsiniz.

Örnek

``` dart
class _HomePageState extends NyState<HomePage> {
TextEditingController _textFieldControllerEmail = TextEditingController();

  handleForm() {
    String textEmail = _textFieldControllerEmail.text;

    validate(rules: {
        "email address": [textEmail, "email"]
      }, onSuccess: () {
      print('passed validation')
    });
  }
```


<div id="change-language"></div>

### changeLanguage

Cihazda kullanılan json **/lang** dosyasını değiştirmek için `changeLanguage`'i çağırabilirsiniz.

Yerelleştirme hakkında daha fazla bilgiyi <a href="/docs/{{$version}}/localization" target="_BLANK">buradan</a> öğrenebilirsiniz.

Örnek

``` dart
class _HomePageState extends NyState<HomePage> {

  changeLanguageES() {
    await changeLanguage('es');
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      body: InkWell(
        onTap: changeLanguageES,
        child: Text("Change Language".tr())
      )
    );
  }
```


<div id="when-env"></div>

### whenEnv

Uygulamanız belirli bir durumda olduğunda bir fonksiyon çalıştırmak için `whenEnv`'i kullanabilirsiniz.
Örneğin, `.env` dosyanızdaki **APP_ENV** değişkeni 'developing' olarak ayarlanmışsa, `APP_ENV=developing`.

Örnek

```dart
class _HomePageState extends NyState<HomePage> {

  TextEditingController _textEditingController = TextEditingController();

  @override
  get init => () {
    whenEnv('developing', perform: () {
      _textEditingController.text = 'test-email@gmail.com';
    });
  };
```

<div id="lock-release"></div>

### lockRelease

Bu metot, bir fonksiyon çağrıldıktan sonra durumu kilitler; yalnızca metot tamamlandığında sonraki isteklere izin verilir. Bu metot ayrıca durumu günceller; kontrol etmek için `isLocked` kullanın.

`lockRelease`'i göstermek için en iyi örnek, kullanıcının 'Giriş Yap' düğmesine bastığı bir giriş ekranı hayal etmektir. Kullanıcıyı giriş yaptırmak için async bir çağrı gerçekleştirmek istiyoruz, ancak istenmeyen bir deneyim yaratabileceğinden metodun birden çok kez çağrılmasını istemiyoruz.

İşte aşağıdaki örnek.

```dart
class _LoginPageState extends NyState<LoginPage> {

  _login() async {
    await lockRelease('login_to_app', perform: () async {

      await Future.delayed(Duration(seconds: 4), () {
        print('Pretend to login...');
      });

    });
  }

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
            Center(
              child: InkWell(
                onTap: _login,
                child: Text("Login"),
              ),
            )
          ],
        )
    );
  }
```

**_login** metoduna dokunduğunuzda, orijinal istek tamamlanana kadar sonraki istekleri engelleyecektir. `isLocked('login_to_app')` yardımcısı, düğmenin kilitli olup olmadığını kontrol etmek için kullanılır. Yukarıdaki örnekte, yükleme Widget'ımızı ne zaman görüntüleyeceğimizi belirlemek için bunu kullandığımızı görebilirsiniz.

<div id="is-locked"></div>

### isLocked

Bu metot, [`lockRelease`](#lock-release) yardımcısı kullanılarak durumun kilitli olup olmadığını kontrol eder.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
    return Scaffold(
        body: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            if (isLocked('login_to_app'))
              AppLoader(),
          ],
        )
    );
  }
```

<div id="view"></div>

### view

`view` metodu, sayfa için arayüzü görüntülemek amacıyla kullanılır.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget view(BuildContext context) {
      return Scaffold(
          body: Center(
              child: Text("My Page")
          )
      );
  }
}
```

<div id="confirm-action"></div>

### confirmAction

`confirmAction` metodu, bir eylemi onaylamak için kullanıcıya bir iletişim kutusu gösterecektir.
Bu metot, devam etmeden önce kullanıcının bir eylemi onaylamasını istediğinizde kullanışlıdır.

Örnek

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

`showToastSuccess` metodu, kullanıcıya bir başarı toast bildirimi gösterecektir.

Örnek
``` dart
_login() {
    ...
    showToastSuccess(
        description: "You have successfully logged in"
    );
}
```

<div id="show-toast-oops"></div>

### showToastOops

`showToastOops` metodu, kullanıcıya bir hata toast bildirimi gösterecektir.

Örnek
``` dart
_error() {
    ...
    showToastOops(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-danger"></div>

### showToastDanger

`showToastDanger` metodu, kullanıcıya bir tehlike toast bildirimi gösterecektir.

Örnek
``` dart
_error() {
    ...
    showToastDanger(
        description: "Something went wrong"
    );
}
```

<div id="show-toast-info"></div>

### showToastInfo

`showToastInfo` metodu, kullanıcıya bir bilgi toast bildirimi gösterecektir.

Örnek
``` dart
_info() {
    ...
    showToastInfo(
        description: "Your account has been updated"
    );
}
```

<div id="show-toast-warning"></div>

### showToastWarning

`showToastWarning` metodu, kullanıcıya bir uyarı toast bildirimi gösterecektir.

Örnek
``` dart
_warning() {
    ...
    showToastWarning(
        description: "Your account is about to expire"
    );
}
```

<div id="show-toast-sorry"></div>

### showToastSorry

`showToastSorry` metodu, kullanıcıya bir üzgünüz toast bildirimi gösterecektir.

Örnek
``` dart
_sorry() {
    ...
    showToastSorry(
        description: "Your account has been suspended"
    );
}
```

<div id="is-loading"></div>

### isLoading

`isLoading` metodu, durumun yüklenip yüklenmediğini kontrol eder.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    if (isLoading()) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded", style: TextStyle(
          color: colors().primaryContent
        )
      )
    );
  }
```

<div id="after-load"></div>

### afterLoad

`afterLoad` metodu, durum 'yüklemeyi' bitirene kadar bir yükleyici görüntülemek için kullanılabilir.

Ayrıca **loadingKey** parametresini kullanarak diğer yükleme anahtarlarını kontrol edebilirsiniz: `afterLoad(child: () {}, loadingKey: 'home_data')`.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () {
    awaitData(perform: () async {
        await sleep(4);
        print('4 seconds after...');
    });
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterLoad(child: () {
          return Text("Loaded");
        })
    );
  }
```

<div id="after-not-locked"></div>

### afterNotLocked

`afterNotLocked` metodu, durumun kilitli olup olmadığını kontrol eder.

Durum kilitliyse [loading] widget'ını görüntüler.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: Container(
          alignment: Alignment.center,
          child: afterNotLocked('login', child: () {
            return MaterialButton(
              onPressed: () {
                login();
              },
              child: Text("Login"),
            );
          }),
        )
    );
  }

  login() async {
    await lockRelease('login', perform: () async {
      await sleep(4);
      print('4 seconds after...');
    });
  }
}
```

<div id="after-not-null"></div>

### afterNotNull

Bir değişken ayarlanana kadar bir yükleme widget'ı göstermek için `afterNotNull` kullanabilirsiniz.

Bir Future çağrısı kullanarak bir veritabanından kullanıcı hesabı almanız gerektiğini hayal edin; bu 1-2 saniye sürebilir, verilere sahip olana kadar bu değer üzerinde afterNotNull kullanabilirsiniz.

Örnek

```dart
class _HomePageState extends NyState<HomePage> {

  User? _user;

  @override
  get init => () async {
    _user = await api<ApiService>((request) => request.fetchUser()); // example
    setState(() {});
  };

  @override
  Widget build(BuildContext context) {
    return Scaffold(
        body: afterNotNull(_user, child: () {
          return Text(_user!.firstName);
        })
    );
  }
```

<div id="set-loading"></div>

### setLoading

`setLoading` kullanarak 'yükleniyor' durumuna geçebilirsiniz.

İlk parametre yüklenip yüklenmediği için bir `bool` kabul eder, sonraki parametre yükleme durumu için bir ad belirlemenize olanak tanır, örneğin `setLoading(true, name: 'refreshing_content');`.

Örnek
```dart
class _HomePageState extends NyState<HomePage> {

  @override
  get init => () async {
    setLoading(true, name: 'refreshing_content');

    await sleep(4);

    setLoading(false, name: 'refreshing_content');
  };

  @override
  Widget build(BuildContext context) {
    if (isLoading(name: 'refreshing_content')) {
      return AppLoader();
    }

    return Scaffold(
        body: Text("The page loaded")
    );
  }
```
