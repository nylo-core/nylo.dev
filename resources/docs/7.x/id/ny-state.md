# NyState

---

<a name="section-1"></a>
- [Pengantar](#introduction "Pengantar")
- [Cara Menggunakan NyState](#how-to-use-nystate "Cara menggunakan NyState")
- [Gaya Loading](#loading-style "Gaya Loading")
- [Aksi State](#state-actions "Aksi State")
- [Helper](#helpers "Helper")


<div id="introduction"></div>

## Pengantar

`NyState` adalah versi yang diperluas dari kelas `State` standar Flutter. Kelas ini menyediakan fungsionalitas tambahan untuk membantu mengelola state halaman dan widget Anda dengan cara yang lebih efisien.

Anda dapat **berinteraksi** dengan state persis seperti yang Anda lakukan dengan state Flutter biasa, tetapi dengan manfaat tambahan dari NyState.

Mari kita bahas cara menggunakan NyState.

<div id="how-to-use-nystate"></div>

## Cara Menggunakan NyState

Anda dapat mulai menggunakan kelas ini dengan meng-extend-nya.

Contoh

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

Method `init` digunakan untuk menginisialisasi state halaman. Anda dapat menggunakan method ini sebagai async atau tanpa async dan di balik layar, ia akan menangani panggilan async dan menampilkan loader.

Method `view` digunakan untuk menampilkan UI halaman.

#### Membuat stateful widget baru dengan NyState

Untuk membuat stateful widget baru di {{ config('app.name') }}, Anda dapat menjalankan perintah di bawah ini.

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## Gaya Loading

Anda dapat menggunakan properti `loadingStyle` untuk mengatur gaya loading halaman Anda.

Contoh

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

`loadingStyle` **default** akan menjadi Widget loading Anda (resources/widgets/loader_widget.dart).
Anda dapat menyesuaikan `loadingStyle` untuk memperbarui gaya loading.

Berikut tabel untuk berbagai gaya loading yang dapat Anda gunakan:
// normal, skeletonizer, none

| Gaya | Deskripsi |
| --- | --- |
| normal | Gaya loading default |
| skeletonizer | Gaya loading skeleton |
| none | Tanpa gaya loading |

Anda dapat mengubah gaya loading seperti ini:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

Jika Anda ingin memperbarui Widget loading di salah satu gaya, Anda dapat memasukkan `child` ke `LoadingStyle`.

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

Sekarang, saat tab sedang loading, teks "Loading..." akan ditampilkan.

Contoh di bawah ini:

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

## Aksi State

Di Nylo, Anda dapat mendefinisikan **aksi** kecil di Widget Anda yang dapat dipanggil dari kelas lain. Ini berguna jika Anda ingin memperbarui state widget dari kelas lain.

Pertama, Anda harus **mendefinisikan** aksi Anda di widget. Ini bekerja untuk `NyState` dan `NyPage`.

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

Kemudian, Anda dapat memanggil aksi dari kelas lain menggunakan method `stateAction`.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

Jika Anda menggunakan stateActions dengan `NyPage`, Anda harus menggunakan **path** dari halaman tersebut.

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

Ada juga kelas lain bernama `StateAction`, kelas ini memiliki beberapa method yang dapat Anda gunakan untuk memperbarui state widget Anda.

- `refreshPage` - Segarkan halaman.
- `pop` - Pop halaman.
- `showToastSorry` - Tampilkan notifikasi toast sorry.
- `showToastWarning` - Tampilkan notifikasi toast warning.
- `showToastInfo` - Tampilkan notifikasi toast info.
- `showToastDanger` - Tampilkan notifikasi toast danger.
- `showToastOops` - Tampilkan notifikasi toast oops.
- `showToastSuccess` - Tampilkan notifikasi toast success.
- `showToastCustom` - Tampilkan notifikasi toast kustom.
- `validate` - Validasi data dari widget Anda.
- `changeLanguage` - Perbarui bahasa di aplikasi.
- `confirmAction` - Lakukan aksi konfirmasi.

Contoh

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

Anda dapat menggunakan kelas `StateAction` untuk memperbarui state halaman/widget mana pun di aplikasi Anda selama widget tersebut dikelola state-nya.

<div id="helpers"></div>

## Helper

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

Method ini akan menjalankan ulang method `init` di state Anda. Berguna jika Anda ingin menyegarkan data di halaman.

Contoh
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

`pop` - Hapus halaman saat ini dari stack.

Contoh

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

Tampilkan notifikasi toast pada context.

Contoh

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

Helper `validate` melakukan pemeriksaan validasi pada data.

Anda dapat mempelajari lebih lanjut tentang validator <a href="/docs/{{$version}}/validation" target="_BLANK">di sini</a>.

Contoh

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

Anda dapat memanggil `changeLanguage` untuk mengubah file json **/lang** yang digunakan di perangkat.

Pelajari lebih lanjut tentang lokalisasi <a href="/docs/{{$version}}/localization" target="_BLANK">di sini</a>.

Contoh

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

Anda dapat menggunakan `whenEnv` untuk menjalankan fungsi ketika aplikasi Anda berada dalam keadaan tertentu.
Contoh: variabel **APP_ENV** di dalam file `.env` Anda diatur ke 'developing', `APP_ENV=developing`.

Contoh

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

Method ini akan mengunci state setelah fungsi dipanggil, hanya setelah method selesai barulah ia mengizinkan pengguna membuat permintaan berikutnya. Method ini juga akan memperbarui state, gunakan `isLocked` untuk memeriksa.

Contoh terbaik untuk menunjukkan `lockRelease` adalah membayangkan kita memiliki layar login ketika pengguna mengetuk 'Login'. Kita ingin melakukan panggilan async untuk login pengguna tetapi kita tidak ingin method dipanggil berkali-kali karena dapat membuat pengalaman yang tidak diinginkan.

Berikut contoh di bawah ini.

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

Setelah Anda mengetuk method **_login**, ia akan memblokir permintaan berikutnya sampai permintaan asli selesai. Helper `isLocked('login_to_app')` digunakan untuk memeriksa apakah tombol terkunci. Pada contoh di atas, Anda dapat melihat kami menggunakannya untuk menentukan kapan menampilkan Widget loading.

<div id="is-locked"></div>

### isLocked

Method ini akan memeriksa apakah state terkunci menggunakan helper [`lockRelease`](#lock-release).

Contoh
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

Method `view` digunakan untuk menampilkan UI halaman.

Contoh
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

Method `confirmAction` akan menampilkan dialog kepada pengguna untuk mengonfirmasi aksi.
Method ini berguna jika Anda ingin pengguna mengonfirmasi aksi sebelum melanjutkan.

Contoh

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

Method `showToastSuccess` akan menampilkan notifikasi toast sukses kepada pengguna.

Contoh
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

Method `showToastOops` akan menampilkan notifikasi toast oops kepada pengguna.

Contoh
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

Method `showToastDanger` akan menampilkan notifikasi toast danger kepada pengguna.

Contoh
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

Method `showToastInfo` akan menampilkan notifikasi toast info kepada pengguna.

Contoh
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

Method `showToastWarning` akan menampilkan notifikasi toast warning kepada pengguna.

Contoh
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

Method `showToastSorry` akan menampilkan notifikasi toast sorry kepada pengguna.

Contoh
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

Method `isLoading` akan memeriksa apakah state sedang loading.

Contoh
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

Method `afterLoad` dapat digunakan untuk menampilkan loader sampai state selesai 'loading'.

Anda juga dapat memeriksa key loading lain menggunakan parameter **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`.

Contoh
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

Method `afterNotLocked` akan memeriksa apakah state terkunci.

Jika state terkunci, ia akan menampilkan widget [loading].

Contoh
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

Anda dapat menggunakan `afterNotNull` untuk menampilkan widget loading sampai variabel telah diatur.

Bayangkan Anda perlu mengambil akun pengguna dari DB menggunakan panggilan Future yang mungkin memakan waktu 1-2 detik, Anda dapat menggunakan afterNotNull pada nilai tersebut sampai Anda memiliki datanya.

Contoh

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

Anda dapat beralih ke state 'loading' dengan menggunakan `setLoading`.

Parameter pertama menerima `bool` apakah sedang loading atau tidak, parameter berikutnya memungkinkan Anda mengatur nama untuk state loading, contoh: `setLoading(true, name: 'refreshing_content');`.

Contoh
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
