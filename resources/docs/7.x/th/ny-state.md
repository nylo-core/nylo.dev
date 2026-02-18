# NyState

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [วิธีใช้ NyState](#how-to-use-nystate "วิธีใช้ NyState")
- [รูปแบบการโหลด](#loading-style "รูปแบบการโหลด")
- [State Actions](#state-actions "State Actions")
- [ตัวช่วย](#helpers "ตัวช่วย")


<div id="introduction"></div>

## บทนำ

`NyState` เป็นเวอร์ชันขยายของคลาส `State` มาตรฐานของ Flutter มันมีฟังก์ชันเพิ่มเติมเพื่อช่วยจัดการ state ของหน้าและ widgets ของคุณอย่างมีประสิทธิภาพมากขึ้น

คุณสามารถ **โต้ตอบ** กับ state ได้เหมือนกับ Flutter state ปกติ แต่ด้วยประโยชน์เพิ่มเติมของ NyState

มาดูวิธีใช้ NyState กัน

<div id="how-to-use-nystate"></div>

## วิธีใช้ NyState

คุณสามารถเริ่มใช้คลาสนี้ได้โดยการ extend มัน

ตัวอย่าง

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

เมธอด `init` ใช้สำหรับเริ่มต้น state ของหน้า คุณสามารถใช้เมธอดนี้แบบ async หรือไม่มี async ก็ได้ และเบื้องหลังจะจัดการการเรียก async และแสดงตัวโหลดให้

เมธอด `view` ใช้สำหรับแสดง UI ของหน้า

#### สร้าง stateful widget ใหม่ด้วย NyState

เพื่อสร้างหน้าใหม่ใน {{ config('app.name') }} คุณสามารถรันคำสั่งด้านล่าง

``` bash
metro make:stateful_widget ProfileImage
```

<div id="loading-style"></div>

## รูปแบบการโหลด

คุณสามารถใช้คุณสมบัติ `loadingStyle` เพื่อตั้งค่ารูปแบบการโหลดสำหรับหน้าของคุณ

ตัวอย่าง

``` dart
class _ProfileImageState extends NyState<ProfileImage> {

  @override
  LoadingStyleType get loadingStyle => LoadingStyleType.normal();

  @override
  get init => () async {
    await sleep(3); // simulate a network call for 3 seconds
  };
```

**ค่าเริ่มต้น** `loadingStyle` จะเป็น Widget โหลดของคุณ (resources/widgets/loader_widget.dart)
คุณสามารถปรับแต่ง `loadingStyle` เพื่ออัปเดตรูปแบบการโหลด

นี่คือตารางสำหรับรูปแบบการโหลดต่างๆ ที่คุณสามารถใช้ได้:
// normal, skeletonizer, none

| รูปแบบ | คำอธิบาย |
| --- | --- |
| normal | รูปแบบการโหลดเริ่มต้น |
| skeletonizer | รูปแบบการโหลดแบบ skeleton |
| none | ไม่มีรูปแบบการโหลด |

คุณสามารถเปลี่ยนรูปแบบการโหลดได้ดังนี้:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// or
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

หากคุณต้องการอัปเดต Widget โหลดในรูปแบบใดรูปแบบหนึ่ง คุณสามารถส่ง `child` ไปยัง `LoadingStyle` ได้

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

ตอนนี้เมื่อแท็บกำลังโหลด ข้อความ "Loading..." จะถูกแสดง

ตัวอย่างด้านล่าง:

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

## State Actions

ใน Nylo คุณสามารถกำหนด **actions** เล็กๆ ใน Widgets ของคุณที่สามารถเรียกจากคลาสอื่นได้ สิ่งนี้มีประโยชน์หากคุณต้องการอัปเดต state ของ widget จากคลาสอื่น

ก่อนอื่น คุณต้อง **กำหนด** actions ใน widget ของคุณ สิ่งนี้ใช้ได้กับ `NyState` และ `NyPage`

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

จากนั้นคุณสามารถเรียก action จากคลาสอื่นโดยใช้เมธอด `stateAction`

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: MyWidget.state, data: user);
// Another example with data
stateAction('show_toast', state: MyWidget.state, data: "Hello world");
```

หากคุณใช้ stateActions กับ `NyPage` คุณต้องใช้ **path** ของหน้า

``` dart
stateAction('hello_world_in_widget', state: ProfilePage.path);

// Another example with data
User user = User(name: "John Doe");
stateAction('update_user_name', state: ProfilePage.path, data: user);

// Another example with data
stateAction('show_toast', state: ProfilePage.path, data: "Hello world");
```

ยังมีคลาสอื่นที่เรียกว่า `StateAction` ซึ่งมีเมธอดหลายตัวที่คุณสามารถใช้อัปเดต state ของ widgets ได้

- `refreshPage` - รีเฟรชหน้า
- `pop` - ปิดหน้า
- `showToastSorry` - แสดงการแจ้งเตือน toast แบบขออภัย
- `showToastWarning` - แสดงการแจ้งเตือน toast แบบเตือน
- `showToastInfo` - แสดงการแจ้งเตือน toast แบบข้อมูล
- `showToastDanger` - แสดงการแจ้งเตือน toast แบบอันตราย
- `showToastOops` - แสดงการแจ้งเตือน toast แบบโอ๊ะ
- `showToastSuccess` - แสดงการแจ้งเตือน toast แบบสำเร็จ
- `showToastCustom` - แสดงการแจ้งเตือน toast ที่กำหนดเอง
- `validate` - ตรวจสอบข้อมูลจาก widget ของคุณ
- `changeLanguage` - อัปเดตภาษาในแอปพลิเคชัน
- `confirmAction` - ดำเนินการยืนยัน

ตัวอย่าง

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

คุณสามารถใช้คลาส `StateAction` เพื่ออัปเดต state ของหน้า/widget ใดก็ได้ในแอปพลิเคชันของคุณ ตราบใดที่ widget นั้นเป็นแบบ state managed

<div id="helpers"></div>

## ตัวช่วย

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

เมธอดนี้จะรันเมธอด `init` ใน state ของคุณอีกครั้ง มีประโยชน์หากคุณต้องการรีเฟรชข้อมูลบนหน้า

ตัวอย่าง
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

`pop` - ลบหน้าปัจจุบันออกจาก stack

ตัวอย่าง

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

แสดงการแจ้งเตือน toast บน context

ตัวอย่าง

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

ตัวช่วย `validate` ทำการตรวจสอบความถูกต้องของข้อมูล

คุณสามารถเรียนรู้เพิ่มเติมเกี่ยวกับตัวตรวจสอบ <a href="/docs/{{$version}}/validation" target="_BLANK">ที่นี่</a>

ตัวอย่าง

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

คุณสามารถเรียก `changeLanguage` เพื่อเปลี่ยนไฟล์ json **/lang** ที่ใช้บนอุปกรณ์

เรียนรู้เพิ่มเติมเกี่ยวกับการแปลภาษา <a href="/docs/{{$version}}/localization" target="_BLANK">ที่นี่</a>

ตัวอย่าง

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

คุณสามารถใช้ `whenEnv` เพื่อรันฟังก์ชันเมื่อแอปพลิเคชันของคุณอยู่ในสถานะที่กำหนด
เช่น ตัวแปร **APP_ENV** ภายในไฟล์ `.env` ของคุณถูกตั้งค่าเป็น 'developing' `APP_ENV=developing`

ตัวอย่าง

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

เมธอดนี้จะล็อก state หลังจากฟังก์ชันถูกเรียก จนกว่าเมธอดจะทำงานเสร็จจึงจะอนุญาตให้ผู้ใช้ส่งคำขอต่อไปได้ เมธอดนี้จะอัปเดต state ด้วย ใช้ `isLocked` เพื่อตรวจสอบ

ตัวอย่างที่ดีที่สุดในการแสดง `lockRelease` คือจินตนาการว่าเรามีหน้าจอเข้าสู่ระบบ เมื่อผู้ใช้แตะ 'Login' เราต้องการเรียก async เพื่อเข้าสู่ระบบ แต่เราไม่ต้องการให้เมธอดถูกเรียกหลายครั้งเพราะอาจสร้างประสบการณ์ที่ไม่พึงประสงค์

นี่คือตัวอย่างด้านล่าง

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

เมื่อคุณแตะเมธอด **_login** มันจะบล็อกคำขอที่ตามมาจนกว่าคำขอเดิมจะเสร็จสิ้น ตัวช่วย `isLocked('login_to_app')` ใช้ตรวจสอบว่าปุ่มถูกล็อกหรือไม่ ในตัวอย่างข้างต้น คุณจะเห็นว่าเราใช้มันเพื่อกำหนดว่าจะแสดง Widget โหลดเมื่อใด

<div id="is-locked"></div>

### isLocked

เมธอดนี้จะตรวจสอบว่า state ถูกล็อกหรือไม่โดยใช้ตัวช่วย [`lockRelease`](#lock-release)

ตัวอย่าง
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

เมธอด `view` ใช้สำหรับแสดง UI ของหน้า

ตัวอย่าง
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

เมธอด `confirmAction` จะแสดงกล่องโต้ตอบให้ผู้ใช้ยืนยันการดำเนินการ
เมธอดนี้มีประโยชน์หากคุณต้องการให้ผู้ใช้ยืนยันก่อนดำเนินการต่อ

ตัวอย่าง

``` dart
_logout() {
 confirmAction(() {
    // logout();
 }, title: "Logout of the app?");
}
```

<div id="show-toast-success"></div>

### showToastSuccess

เมธอด `showToastSuccess` จะแสดงการแจ้งเตือน toast แบบสำเร็จให้ผู้ใช้

ตัวอย่าง
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

เมธอด `showToastOops` จะแสดงการแจ้งเตือน toast แบบโอ๊ะให้ผู้ใช้

ตัวอย่าง
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

เมธอด `showToastDanger` จะแสดงการแจ้งเตือน toast แบบอันตรายให้ผู้ใช้

ตัวอย่าง
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

เมธอด `showToastInfo` จะแสดงการแจ้งเตือน toast แบบข้อมูลให้ผู้ใช้

ตัวอย่าง
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

เมธอด `showToastWarning` จะแสดงการแจ้งเตือน toast แบบเตือนให้ผู้ใช้

ตัวอย่าง
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

เมธอด `showToastSorry` จะแสดงการแจ้งเตือน toast แบบขออภัยให้ผู้ใช้

ตัวอย่าง
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

เมธอด `isLoading` จะตรวจสอบว่า state กำลังโหลดอยู่หรือไม่

ตัวอย่าง
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

เมธอด `afterLoad` สามารถใช้แสดงตัวโหลดจนกว่า state จะโหลดเสร็จ

คุณยังสามารถตรวจสอบคีย์การโหลดอื่นได้โดยใช้พารามิเตอร์ **loadingKey** `afterLoad(child: () {}, loadingKey: 'home_data')`

ตัวอย่าง
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

เมธอด `afterNotLocked` จะตรวจสอบว่า state ถูกล็อกหรือไม่

หาก state ถูกล็อก จะแสดง widget [loading]

ตัวอย่าง
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

คุณสามารถใช้ `afterNotNull` เพื่อแสดง widget โหลดจนกว่าตัวแปรจะถูกตั้งค่า

ลองจินตนาการว่าคุณต้องดึงบัญชีผู้ใช้จาก DB โดยใช้ Future call ซึ่งอาจใช้เวลา 1-2 วินาที คุณสามารถใช้ afterNotNull กับค่านั้นจนกว่าจะมีข้อมูล

ตัวอย่าง

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

คุณสามารถเปลี่ยนเป็นสถานะ 'loading' โดยใช้ `setLoading`

พารามิเตอร์แรกรับ `bool` สำหรับว่ากำลังโหลดหรือไม่ พารามิเตอร์ถัดไปอนุญาตให้ตั้งชื่อสำหรับสถานะการโหลด เช่น `setLoading(true, name: 'refreshing_content');`

ตัวอย่าง
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
