# 控制器

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [创建控制器](#creating-controllers "创建控制器")
- [使用控制器](#using-controllers "使用控制器")
- 控制器功能
  - [访问路由数据](#accessing-route-data "访问路由数据")
  - [查询参数](#query-parameters "查询参数")
  - [页面状态管理](#page-state-management "页面状态管理")
  - [Toast 通知](#toast-notifications "Toast 通知")
  - [表单验证](#form-validation "表单验证")
  - [语言切换](#language-switching "语言切换")
  - [锁定释放](#lock-release "锁定释放")
  - [确认操作](#confirm-actions "确认操作")
- [单例控制器](#singleton-controllers "单例控制器")
- [控制器解码器](#controller-decoders "控制器解码器")
- [路由守卫](#route-guards "路由守卫")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 中的控制器充当视图（页面）和业务逻辑之间的协调者。它们处理用户输入、管理状态更新，并提供清晰的关注点分离。

{{ config('app.name') }} v7 引入了 `NyController` 类，内置了强大的 toast 通知、表单验证、状态管理等方法。

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class HomeController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // 初始化服务或获取数据
  }

  void onTapProfile() {
    routeTo(ProfilePage.path);
  }

  void submitForm() {
    validate(
      rules: {"email": "email"},
      onSuccess: () => showToastSuccess(description: "Form submitted!"),
    );
  }
}
```

<div id="creating-controllers"></div>

## 创建控制器

使用 Metro CLI 生成控制器：

``` bash
# 创建带控制器的页面
metro make:page dashboard --controller
# 或简写
metro make:page dashboard -c

# 仅创建控制器
metro make:controller profile_controller
```

这会创建：
- **控制器**：`lib/app/controllers/dashboard_controller.dart`
- **页面**：`lib/resources/pages/dashboard_page.dart`

<div id="using-controllers"></div>

## 使用控制器

通过在 `NyStatefulWidget` 上指定泛型类型来将控制器连接到页面：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';

class HomePage extends NyStatefulWidget<HomeController> {

  static RouteView path = ("/home", (_) => HomePage());

  HomePage() : super(child: () => _HomePageState());
}

class _HomePageState extends NyPage<HomePage> {

  @override
  get init => () async {
    // 访问控制器方法
    widget.controller.fetchData();
  };

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Home")),
      body: Column(
        children: [
          ElevatedButton(
            onPressed: widget.controller.onTapProfile,
            child: Text("View Profile"),
          ),
          TextField(
            controller: widget.controller.nameController,
          ),
        ],
      ),
    );
  }
}
```

<div id="accessing-route-data"></div>

## 访问路由数据

在页面之间传递数据并在控制器中访问：

``` dart
// 携带数据导航
routeTo(ProfilePage.path, data: {"userId": 123});

// 控制器内部
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // 获取传递的数据
    Map<String, dynamic>? userData = data();
    int? userId = userData?['userId'];
  }
}
```

或者直接在页面状态中访问数据：

``` dart
class _ProfilePageState extends NyPage<ProfilePage> {

  @override
  get init => () async {
    // 来自控制器
    var userData = widget.controller.data();

    // 或直接来自组件
    var userData = widget.data();
  };
}
```

<div id="query-parameters"></div>

## 查询参数

在控制器中访问 URL 查询参数：

``` dart
// 导航到：/profile?tab=settings&highlight=true
routeTo("/profile?tab=settings&highlight=true");

// 控制器内部
class ProfileController extends NyController {

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);

    // 获取所有查询参数为 Map
    Map<String, dynamic>? params = queryParameters();
    // {"tab": "settings", "highlight": "true"}

    // 获取特定参数
    String? tab = queryParameters(key: "tab");
    // "settings"
  }
}
```

检查查询参数是否存在：

``` dart
// 在页面中
if (widget.hasQueryParameter("tab")) {
  // 处理 tab 参数
}
```

<div id="page-state-management"></div>

## 页面状态管理

控制器可以直接管理页面状态：

``` dart
class HomeController extends NyController {

  int counter = 0;

  void increment() {
    counter++;
    // 触发页面的 setState
    setState(setState: () {});
  }

  void refresh() {
    // 刷新整个页面
    refreshPage();
  }

  void goBack() {
    // 带可选结果弹出页面
    pop(result: {"updated": true});
  }

  void goBackFromRoot() {
    // 从根导航器弹出（例如关闭 Navigation Hub 中的根级模态框）
    pop(rootNavigator: true);
  }

  void updateCustomState() {
    // 向页面发送自定义操作
    updatePageState("customAction", {"key": "value"});
  }
}
```

<div id="toast-notifications"></div>

## Toast 通知

控制器包含内置的 toast 通知方法：

``` dart
class FormController extends NyController {

  void showNotifications() {
    // 成功 toast
    showToastSuccess(description: "Profile updated!");

    // 警告 toast
    showToastWarning(description: "Please check your input");

    // 错误/危险 toast
    showToastDanger(description: "Failed to save changes");

    // 信息 toast
    showToastInfo(description: "New features available");

    // Sorry toast
    showToastSorry(description: "We couldn't process your request");

    // Oops toast
    showToastOops(description: "Something went wrong");
  }

  void showCustomToast() {
    // 带标题的自定义 toast
    showToastSuccess(
      title: "Great Job!",
      description: "Your changes have been saved",
    );

    // 使用自定义 toast 样式（在 Nylo 中注册）
    showToastCustom(
      title: "Custom",
      description: "Using custom style",
      id: "my_custom_toast",
    );
  }
}
```

<div id="form-validation"></div>

## 表单验证

直接从控制器验证表单数据：

``` dart
class RegisterController extends NyController {

  TextEditingController emailController = TextEditingController();
  TextEditingController passwordController = TextEditingController();

  void submitRegistration() {
    validate(
      rules: {
        "email": "email|max:50",
        "password": "min:8|max:64",
      },
      data: {
        "email": emailController.text,
        "password": passwordController.text,
      },
      messages: {
        "email.email": "Please enter a valid email",
        "password.min": "Password must be at least 8 characters",
      },
      showAlert: true,
      alertStyle: 'warning',
      onSuccess: () {
        // 验证通过
        _performRegistration();
      },
      onFailure: (exception) {
        // 验证失败
        print(exception.toString());
      },
    );
  }

  void _performRegistration() async {
    // 处理注册逻辑
    showToastSuccess(description: "Account created!");
  }
}
```

<div id="language-switching"></div>

## 语言切换

从控制器更改应用的语言：

``` dart
class SettingsController extends NyController {

  void switchToSpanish() {
    changeLanguage('es', restartState: true);
  }

  void switchToEnglish() {
    changeLanguage('en', restartState: true);
  }
}
```

<div id="lock-release"></div>

## 锁定释放

防止按钮被多次快速点击：

``` dart
class CheckoutController extends NyController {

  void onTapPurchase() {
    lockRelease("purchase_lock", perform: () async {
      // 此代码在锁释放之前只运行一次
      await processPayment();
      showToastSuccess(description: "Payment complete!");
    });
  }

  void onTapWithoutSetState() {
    lockRelease(
      "my_lock",
      perform: () async {
        await someAsyncOperation();
      },
      shouldSetState: false, // 完成后不触发 setState
    );
  }
}
```

<div id="confirm-actions"></div>

## 确认操作

在执行破坏性操作之前显示确认对话框：

``` dart
class AccountController extends NyController {

  void onTapDeleteAccount() {
    confirmAction(
      () async {
        // 用户已确认 - 执行删除
        await deleteAccount();
        showToastSuccess(description: "Account deleted");
      },
      title: "Delete Account?",
      dismissText: "Cancel",
    );
  }
}
```

<div id="singleton-controllers"></div>

## 单例控制器

将控制器作为单例在应用中持久化：

``` dart
class AuthController extends NyController {

  @override
  bool get singleton => true;

  User? currentUser;

  Future<void> login(String email, String password) async {
    // 登录逻辑
    currentUser = await AuthService.login(email, password);
  }

  bool get isLoggedIn => currentUser != null;
}
```

单例控制器只创建一次，并在整个应用生命周期中重复使用。

<div id="controller-decoders"></div>

## 控制器解码器

在 `lib/config/decoders.dart` 中注册您的控制器：

``` dart
import 'package:nylo_framework/nylo_framework.dart';
import '/app/controllers/home_controller.dart';
import '/app/controllers/profile_controller.dart';
import '/app/controllers/auth_controller.dart';

final Map<Type, BaseController Function()> controllers = {
  HomeController: () => HomeController(),
  ProfileController: () => ProfileController(),
  AuthController: () => AuthController(),
};
```

这个映射允许 {{ config('app.name') }} 在页面加载时解析控制器。

<div id="route-guards"></div>

## 路由守卫

控制器可以定义在页面加载之前运行的路由守卫：

``` dart
class AdminController extends NyController {

  @override
  List<RouteGuard> get routeGuards => [
    AuthRouteGuard(),
    AdminRoleGuard(),
  ];

  @override
  Future<void> construct(BuildContext context) async {
    super.construct(context);
    // 仅在所有守卫通过时运行
  }
}
```

查看[路由文档](/docs/7.x/router)了解更多关于路由守卫的详情。

