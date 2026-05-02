# 상태 관리 위젯

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [패턴 선택](#choose-your-pattern "패턴 선택")
- [State Actions](#state-actions "State Actions")
  - [핸들러 정의](#defining-handlers "핸들러 정의")
  - [액션 트리거](#triggering-actions "액션 트리거")
  - [데이터가 있는 핸들러와 없는 핸들러](#handlers-with-and-without-data "데이터가 있는 핸들러와 없는 핸들러")
  - [StateActions 인스턴스 사용](#using-a-state-actions-instance "StateActions 인스턴스 사용")
- [패턴: 상태 관리 페이지 (NyPage)](#pattern-ny-page "패턴: 상태 관리 페이지")
- [패턴: Stateful 위젯 (NyState)](#pattern-ny-state "패턴: Stateful 위젯")
- [패턴: 상태 관리 위젯 (NyStateManaged)](#pattern-ny-state-managed "패턴: 상태 관리 위젯")
  - [고급: 다중 격리 인스턴스](#advanced-multiple-isolated-instances "고급: 다중 격리 인스턴스")
- [라이프사이클 참조](#lifecycle "라이프사이클 참조")

<div id="introduction"></div>

## 소개

상태 관리 위젯을 사용하면 페이지 전체를 재구성하지 않고도 앱 어디서나 UI의 특정 부분을 업데이트할 수 있습니다. {{ config('app.name') }} v7에서는 대상 위젯이나 페이지에 명명된 **state actions**을 트리거하면 해당 핸들러가 실행됩니다.

기본 개념은 단순합니다:

- 각 상태 관리 위젯이나 페이지는 고유한 **상태 키**（문자열）를 가집니다
- 처리 방법을 알고 있는 **명명된 액션** 맵을 정의합니다
- 앱 어디서나 다음을 호출할 수 있습니다
```
stateAction("action_name", state: TargetWidget.state)
``` 

상태 관리 UI를 구축하는 세 가지 패턴이 있습니다. 모두 동일한 `stateAction` 메커니즘을 공유하며, 유일한 차이점은 관리하는 UI의 종류입니다.


<div id="choose-your-pattern"></div>

## 패턴 선택

| 원하는 것 | 사용 | 스캐폴드 명령어 |
|---|---|---|
| 전체 페이지를 상태 관리 | `NyPage` | `metro make:page my_page` |
| 단일 인스턴스 위젯을 상태 관리 | `NyState` | `metro make:stateful_widget my_widget` |
| 다중 격리 인스턴스를 가진 위젯을 상태 관리 | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

모든 패턴이 사용하는 API를 설명하는 [State Actions](#state-actions) 섹션을 먼저 읽어보세요. 그런 다음 자신의 케이스에 맞는 패턴으로 이동하세요.


<div id="state-actions"></div>

## State Actions

State Actions는 위젯이나 페이지가 처리하는 방법을 알고 있는 명명된 명령입니다. 액션 이름에서 핸들러 함수로의 맵을 정의하고, 앱 어디서나 이름으로 트리거합니다.

다음과 같은 경우 State Actions를 사용합니다:
- 위젯이나 페이지에서 특정 동작을 트리거해야 할 때 (일반적인 새로고침이 아닌 것)
- 위젯에 데이터를 전달하고 정의된 방식으로 응답하게 해야 할 때
- 여러 호출 사이트에서 호출할 수 있는 재사용 가능한 위젯 동작을 구축해야 할 때

<div id="defining-handlers"></div>

### 핸들러 정의

핸들러는 `NyState` 또는 `NyPage` 클래스의 `stateActions` getter에 위치합니다. 맵의 키는 액션 이름이고, 값은 해당 액션이 트리거될 때 실행할 함수입니다.

``` dart
@override
Map<String, Function> get stateActions => {
  "reload_cart": () async {
    _cartValue = await getCartValue();
    setState(() {});
  },
  "clear_cart": () {
    _cartValue = null;
    setState(() {});
  },
  "apply_discount": (code) async {
    _discount = await validateDiscount(code);
    setState(() {});
  },
};
```

핸들러는 위젯을 재구성하려면 스스로 `setState`를 호출해야 합니다.

위의 `apply_discount` 핸들러는 `code` 인수를 받습니다 — 핸들러가 다음을 통해 전달된 페이로드가 필요할 때 단일 위치 매개변수를 선언하세요
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

액션이 페이로드를 가지지 않을 때는 인수 없는 형식 `()`을 사용하세요.


<div id="triggering-actions"></div>

### 액션 트리거

최상위 `stateAction` 함수를 사용하여 다른 위젯, 컨트롤러, 이벤트 핸들러, API 콜백 등 어디서나 액션을 발사합니다.

``` dart
// 데이터 없이 액션 발사
stateAction("clear_cart", state: Cart.state);

// 데이터와 함께 액션 발사
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

`state:` 인수는 대상의 **상태 키**입니다:
- 위젯의 경우 — `MyWidget.state` (문자열)
- 페이지의 경우 — `MyPage.path` (라우트)


<div id="handlers-with-and-without-data"></div>

### 데이터가 있는 핸들러와 없는 핸들러

핸들러는 동기 또는 비동기일 수 있으며, `data` 인수가 있거나 없이 정의할 수 있습니다:

``` dart
@override
Map<String, Function> get stateActions => {
  // 데이터 없음 — 핸들러를 그대로 실행
  "reset": () {
    _value = null;
    setState(() {});
  },

  // 데이터 있음 — `data:` 인수로 전달된 것을 수신
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // 비동기 지원 — 프레임워크가 핸들러를 await
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

`stateAction`에 `data:`를 전달해도 핸들러가 인수를 받지 않으면 데이터는 단순히 무시됩니다.


<div id="using-a-state-actions-instance"></div>

### StateActions 인스턴스 사용

위젯이 타입화된 `StateActions` 인스턴스（주로 `stateActions(stateName)` 정적 메서드로）를 노출하는 경우, 자유 함수 대신 인스턴스에서 직접 `.action(...)`을 호출할 수 있습니다. 같은 대상에 여러 액션을 발사할 때 더 깔끔합니다:

``` dart
// 자유 함수 사용
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// StateActions 인스턴스 사용 — 동일하지만 반복이 적음
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

여러 내장 위젯（`InputField`, `CollectionView`, `LanguageSwitcher`, `NyForm*` 계열）은 `.clear()`, `.setValue(...)`, `.refresh()` 같은 명명된 메서드를 가진 타입화된 `StateActions` 클래스를 제공합니다 — 각 위젯의 문서에서 사용 가능한 항목을 확인하세요.


<div id="pattern-ny-page"></div>

## 패턴: 상태 관리 페이지 (NyPage)

이벤트가 발생할 때 페이지를 새로고침하거나 자식 위젯에서 폼 상태를 지우는 등, 앱의 다른 곳에서 전체 페이지에 동작을 트리거하고 싶을 때 사용합니다.

**1단계:** 페이지를 스캐폴드합니다.

``` bash
metro make:page my_page
```

이렇게 하면 `lib/resources/pages/`에 `NyPage`가 생성됩니다:

``` dart
class MyPage extends NyStatefulWidget {

  static RouteView path = ("/my-page", (_) => MyPage());

  MyPage({super.key}) : super(child: () => _MyPageState());
}

class _MyPageState extends NyPage<MyPage> {

  @override
  get init => () {

  };

  @override
  bool get stateManaged => false;

  @override
  Widget view(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("My Page")
      ),
      body: SafeArea(
         child: Container(),
      ),
    );
  }
}
```

**2단계:** `stateManaged`를 `true`로 전환합니다.

기본적으로 페이지는 상태 이벤트를 구독하지 **않습니다** — 이는 필요하지 않은 페이지에 불필요한 리스너가 생기는 것을 방지합니다. 페이지에서 state actions를 활성화하려면 getter를 변경하세요:

``` dart
@override
bool get stateManaged => true;
```

**3단계:** `stateActions` 맵을 추가합니다.

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh_data": () async {
    _items = await fetchItems();
    setState(() {});
  },
  "show_toast": (data) {
    showToastSuccess(description: data["message"]);
  },
};
```

**4단계:** 페이지의 `path`를 사용하여 어디서나 액션을 트리거합니다.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> 페이지는 `MyPage.path`를 키로 사용하고, 위젯은 `MyWidget.state`를 키로 사용합니다. 이것이 호출 사이트에서의 유일한 차이입니다.


<div id="pattern-ny-state"></div>

## 패턴: Stateful 위젯 (NyState)

페이지당 단일 인스턴스로 존재하는 재사용 가능한 위젯 — 프로필 카드, 헤더 바, 상태 표시기 — 에 사용합니다. 위젯을 한 번에 하나의 인스턴스만 렌더링한다면 이것이 올바른 패턴입니다.

**1단계:** Stateful 위젯을 스캐폴드합니다.

``` bash
metro make:stateful_widget profile_card
```

이렇게 하면 `lib/resources/widgets/`에 다음이 생성됩니다:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  @override
  createState() => _ProfileCardState();
}

class _ProfileCardState extends NyState<ProfileCard> {

  @override
  get init => () {

  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

스캐폴드는 작동하는 `NyState` 위젯을 제공하지만 — **아직 상태 관리가 되지 않습니다**. state actions에 응답하게 하려면 네 가지를 추가해야 합니다:

1. 위젯 클래스에 `state` 키 — 앱의 다른 부분이 타깃으로 삼을 고유 문자열
2. 상태 키를 받아서 상태 클래스에 전달하는 생성자 매개변수
3. `stateName`을 할당하는 상태 클래스 생성자
4. 핸들러를 정의하는 `stateActions` 맵

**2단계:** 스캐폴드를 상태 관리 위젯으로 변환합니다.

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class ProfileCard extends StatefulWidget {

  const ProfileCard({super.key});

  static String get state => "profile_card";

  @override
  createState() => _ProfileCardState(state);
}

class _ProfileCardState extends NyState<ProfileCard> {

  _ProfileCardState(String? state) {
    this.stateName = state;
  }

  @override
  get init => () {
    // stateAction("hello_world", state: ProfileCard.state);
    // ^ 앱 어디서나 이것을 호출하여 아래 핸들러를 트리거할 수 있습니다
  };

  @override
  Map<String, Function> get stateActions => {
    "hello_world": () {
      print("Hello World");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container();
  }
}
```

변경 사항:
- `static String get state => "profile_card";`는 공개 상태 키를 정의합니다
- `createState() => _ProfileCardState(state)`는 키를 상태 클래스에 전달합니다
- `_ProfileCardState(String? state) { this.stateName = state; }`는 해당 키 아래에 이 상태 인스턴스를 등록하여 프레임워크가 어떤 위젯에 액션을 전달할지 알게 합니다
- `stateActions`는 명명된 핸들러를 선언합니다

**3단계:** 어디서나 트리거합니다.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

데이터가 있거나 없는 핸들러를 필요한 만큼 추가할 수 있습니다:

``` dart
@override
Map<String, Function> get stateActions => {
  "refresh": () async {
    _user = await loadUser();
    setState(() {});
  },
  "update_avatar": (User user) {
    _avatarUrl = user.avatarUrl;
    setState(() {});
  },
};
```


<div id="pattern-ny-state-managed"></div>

## 패턴: 상태 관리 위젯 (NyStateManaged)

화면에 **동일한 위젯의 여러 독립적인 인스턴스**가 필요할 때 사용합니다 — 예를 들어, 헤더의 장바구니 배지와 사이드바의 배지가 각각 독립적으로 업데이트되어야 하는 경우입니다.

`NyStateManaged`는 각 인스턴스를 개별적으로 지정할 수 있도록 `stateName` 매개변수를 추가합니다. 위젯의 인스턴스를 하나만 렌더링한다면 더 간단한 `NyState`를 사용하세요.

**1단계:** 상태 관리 위젯을 스캐폴드합니다.

``` bash
metro make:state_managed_widget cart
```

이렇게 하면 `lib/resources/widgets/`에 다음이 생성됩니다:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.stateName})
      : super(child: () => _CartState(stateName));

  static String state = "cart";

  static String _stateFor(String? state) =>
      state == null ? Cart.state : "${Cart.state}_$state";

  static action(String action, {dynamic data, String? stateName}) {
    return stateAction(action, data: data, state: _stateFor(stateName));
  }
}

class _CartState extends NyState<Cart> {
  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () {
    // 초기화 로직은 여기에
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // 앱 어디서나 액션 호출
      // Cart.action("my_action", data: "hello world");
      // Cart.action("clear_data");
    },
  };

  @override
  Widget view(BuildContext context) {
    return Container(
      child: Text("My Widget").bodyMedium(),
    );
  }
}
```

**2단계:** 상태를 구현합니다 — `init`에서 데이터를 로드하고, 핸들러를 정의하고, 렌더링합니다.

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

  _CartState(String? stateName) {
    this.stateName = Cart._stateFor(stateName);
  }

  @override
  get init => () async {
    _cartValue = await getCartValue();
  };

  @override
  Map<String, Function> get stateActions => {
    "reload_cart": () async {
      _cartValue = await getCartValue();
      setState(() {});
    },
    "clear_cart": () {
      _cartValue = null;
      setState(() {});
    },
    "set_quantity": (quantity) {
      _cartValue = quantity.toString();
      setState(() {});
    },
  };

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "0"),
    );
  }
}
```

**3단계:** 생성된 정적 `action()` 헬퍼를 사용하여 액션을 트리거합니다.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

이는 `stateAction("reload_cart", state: Cart.state)`와 동일합니다 — 정적 헬퍼는 단순히 보일러플레이트를 제거할 뿐입니다.


<div id="advanced-multiple-isolated-instances"></div>

### 고급: 다중 격리 인스턴스

`NyStateManaged`가 존재하는 이유는 동일한 위젯의 여러 독립적인 인스턴스를 지원하기 위해서입니다. 각 인스턴스는 고유한 `stateName`을 받아 네임스페이스화된 상태 키를 생성합니다.

서로 다른 이름으로 두 개의 장바구니를 렌더링합니다:

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

이제 각각을 독립적으로 업데이트할 수 있습니다:

``` dart
// 헤더 장바구니만 리로드
Cart.action("reload_cart", stateName: "header");

// 사이드바 장바구니만 리로드
Cart.action("reload_cart", stateName: "sidebar");

// stateName 없음 — 기본 이름 없는 인스턴스를 타깃으로
Cart.action("reload_cart");
```

`_stateFor` 헬퍼가 네임스페이싱을 처리합니다: `Cart(stateName: "header")`는 키 `"cart_header"` 아래에 등록되고, `Cart.action(..., stateName: "header")`는 그 정확한 키를 타깃으로 합니다.


<div id="lifecycle"></div>

## 라이프사이클 참조

상태 관리 위젯과 페이지는 두 가지 주요 라이프사이클 훅을 공유합니다:

1. **`init()`** — 상태가 처음 생성될 때 한 번 호출됩니다. 초기 데이터를 로드하는 데 사용합니다.

2. **`stateUpdated(data)`** — 이 상태에 대해 state action이 발사될 때마다 호출됩니다. `data` 인수는 전체 페이로드（액션 이름과 액션 데이터 포함）입니다. *모든* state action에 반응해야 할 경우 오버라이드하세요 — 대부분의 경우 `stateActions`에 핸들러를 정의하는 것이 원하는 방식입니다.

**참조:** 상태 헬퍼 및 라이프사이클 메서드의 전체 세트는 [NyState](/docs/{{ $version }}/ny-state)를 참조하세요.
