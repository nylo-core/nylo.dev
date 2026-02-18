# 상태 관리

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [상태 관리를 사용해야 하는 경우](#when-to-use-state-management "상태 관리를 사용해야 하는 경우")
- [라이프사이클](#lifecycle "라이프사이클")
- [State Action](#state-actions "State Action")
  - [NyState - State Action](#state-actions-nystate "NyState - State Action")
  - [NyPage - State Action](#state-actions-nypage "NyPage - State Action")
- [상태 업데이트](#updating-a-state "상태 업데이트")
- [첫 번째 Widget 만들기](#building-your-first-widget "첫 번째 Widget 만들기")

<div id="introduction"></div>

## 소개

상태 관리를 사용하면 전체 페이지를 다시 빌드하지 않고 UI의 특정 부분을 업데이트할 수 있습니다. {{ config('app.name') }} v7에서는 앱 전체에서 서로 통신하고 업데이트하는 Widget을 빌드할 수 있습니다.

{{ config('app.name') }}는 상태 관리를 위한 두 가지 클래스를 제공합니다:
- **`NyState`** -- 재사용 가능한 Widget(장바구니 배지, 알림 카운터 또는 상태 표시기 등)을 빌드하기 위한 클래스
- **`NyPage`** -- 애플리케이션의 페이지를 빌드하기 위한 클래스 (`NyState`를 확장하며 페이지 전용 기능 포함)

상태 관리를 사용해야 하는 경우:
- 앱의 다른 부분에서 Widget을 업데이트해야 할 때
- Widget 간에 공유 데이터를 동기화해야 할 때
- UI의 일부만 변경될 때 전체 페이지를 다시 빌드하는 것을 방지할 때


### 먼저 상태 관리를 이해하겠습니다

Flutter의 모든 것은 Widget입니다. Widget은 완전한 앱을 만들기 위해 결합할 수 있는 작은 UI 조각입니다.

복잡한 페이지를 빌드하기 시작하면 Widget의 상태를 관리해야 합니다. 이는 데이터와 같이 무언가가 변경되면 전체 페이지를 다시 빌드하지 않고도 해당 Widget을 업데이트할 수 있다는 것을 의미합니다.

이것이 중요한 이유는 많지만, 주된 이유는 성능입니다. 끊임없이 변경되는 Widget이 있다면 변경될 때마다 전체 페이지를 다시 빌드하고 싶지 않을 것입니다.

이것이 상태 관리의 역할입니다. 애플리케이션에서 Widget의 상태를 관리할 수 있게 해줍니다.


<div id="when-to-use-state-management"></div>

### 상태 관리를 사용해야 하는 경우

전체 페이지를 다시 빌드하지 않고 업데이트해야 하는 Widget이 있을 때 상태 관리를 사용해야 합니다.

예를 들어, 이커머스 앱을 만들었다고 상상해 보겠습니다. 사용자의 장바구니에 있는 총 항목 수를 표시하는 Widget을 만들었습니다.
이 Widget을 `Cart()`라고 부르겠습니다.

Nylo에서 상태 관리가 적용된 `Cart` Widget은 다음과 같습니다:

**단계 1:** 정적 state 이름으로 Widget 정의

``` dart
/// Cart Widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // 이 Widget 상태의 고유 식별자

  @override
  _CartState createState() => _CartState();
}
```

**단계 2:** `NyState`를 확장하는 State 클래스 생성

``` dart
/// Cart Widget의 State 클래스
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // State 이름 등록
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // 초기 데이터 로드
  };

  @override
  void stateUpdated(data) {
    reboot(); // 상태 업데이트 시 Widget 다시 로드
  }

  @override
  Widget view(BuildContext context) {
    return Badge(
      child: Icon(Icons.shopping_cart),
      label: Text(_cartValue ?? "1"),
    );
  }
}
```

**단계 3:** 장바구니를 읽고 업데이트하는 헬퍼 함수 생성

``` dart
/// 스토리지에서 장바구니 값 가져오기
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// 장바구니 값 설정 및 Widget에 알림
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // Widget에서 stateUpdated()를 트리거합니다
}
```

이것을 분석해 보겠습니다.

1. `Cart` Widget은 `StatefulWidget`입니다.

2. `_CartState`는 `NyState<Cart>`를 확장합니다.

3. `state`에 대한 이름을 정의해야 합니다. 이것은 상태를 식별하는 데 사용됩니다.

4. `boot()` 메서드는 Widget이 처음 로드될 때 호출됩니다.

5. `stateUpdate()` 메서드는 상태가 업데이트될 때 어떤 일이 발생하는지 처리합니다.

{{ config('app.name') }} 프로젝트에서 이 예시를 시도하려면 `Cart`라는 새 Widget을 만드세요.

``` bash
metro make:state_managed_widget cart
```

그런 다음 위의 예시를 복사하여 프로젝트에서 시도할 수 있습니다.

이제 장바구니를 업데이트하려면 다음을 호출할 수 있습니다.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## 라이프사이클

`NyState` Widget의 라이프사이클은 다음과 같습니다:

1. `init()` - 상태가 초기화될 때 호출됩니다.

2. `stateUpdated(data)` - 상태가 업데이트될 때 호출됩니다.

    `updateState(MyStateName.state, data: "The Data")`를 호출하면 **stateUpdated(data)**가 트리거됩니다.

상태가 처음 초기화되면 상태를 관리하는 방법을 구현해야 합니다.


<div id="state-actions"></div>

## State Action

State Action을 사용하면 앱 어디서나 Widget의 특정 메서드를 트리거할 수 있습니다. Widget에 보낼 수 있는 명명된 명령으로 생각하면 됩니다.

State Action을 사용하는 경우:
- Widget에서 특정 동작을 트리거해야 할 때 (단순히 새로고침이 아닌)
- Widget에 데이터를 전달하고 특정 방식으로 응답하게 해야 할 때
- 여러 곳에서 호출할 수 있는 재사용 가능한 Widget 동작을 만들어야 할 때

``` dart
// Widget에 액션 보내기
stateAction('hello_world_in_widget', state: MyWidget.state);

// 데이터와 함께 보내는 다른 예시
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

Widget에서 처리하려는 액션을 정의할 수 있습니다.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // 데이터가 있는 예시
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

그런 다음 애플리케이션 어디서나 `stateAction` 메서드를 호출할 수 있습니다.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// 'Hello world'를 출력합니다

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

`init` getter에서 `whenStateAction` 메서드를 사용하여 State Action을 정의할 수도 있습니다.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // 배지 카운트 초기화
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - State Action

먼저 Stateful Widget을 생성합니다.

``` bash
metro make:stateful_widget [widget_name]
```
예시: metro make:stateful_widget user_avatar

이렇게 하면 `lib/resources/widgets/` 디렉토리에 새 Widget이 생성됩니다.

해당 파일을 열면 State Action을 정의할 수 있습니다.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // 예시
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // 예시
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

마지막으로 애플리케이션 어디서나 액션을 보낼 수 있습니다.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// Widget에서 'Hello'를 출력합니다

stateAction('reset_data', state: MyWidget.state);
// Widget에서 데이터 초기화

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// 메시지와 함께 성공 토스트 표시
```


<div id="state-actions-nypage"></div>

### NyPage - State Action

페이지도 State Action을 받을 수 있습니다. 이것은 Widget이나 다른 페이지에서 페이지 레벨 동작을 트리거하려 할 때 유용합니다.

먼저 상태 관리가 적용된 페이지를 생성합니다.

``` bash
metro make:page my_page
```

이렇게 하면 `lib/resources/pages/` 디렉토리에 `MyPage`라는 새 상태 관리 페이지가 생성됩니다.

해당 파일을 열면 State Action을 정의할 수 있습니다.

``` dart
class _MyPageState extends NyPage<MyPage> {
...

@override
bool get stateManaged => true;

@override
get stateActions => {
  "test_page_action": () {
    print('Hello from the page');
  },
  "reset_data": () {
    // 예시
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

마지막으로 애플리케이션 어디서나 액션을 보낼 수 있습니다.

``` dart
stateAction('test_page_action', state: MyPage.state);
// '페이지에서 Hello'를 출력합니다

stateAction('reset_data', state: MyPage.state);
// 페이지에서 데이터 초기화

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// 메시지와 함께 성공 토스트 표시
```

`whenStateAction` 메서드를 사용하여 State Action을 정의할 수도 있습니다.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // 배지 카운트 초기화
      _count = 0;
    }
  });
}
```

그런 다음 애플리케이션 어디서나 액션을 보낼 수 있습니다.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## 상태 업데이트

`updateState()` 메서드를 호출하여 상태를 업데이트할 수 있습니다.

``` dart
updateState(MyStateName.state);

// 또는 데이터와 함께
updateState(MyStateName.state, data: "The Data");
```

이것은 애플리케이션 어디서나 호출할 수 있습니다.

**참고:** 상태 관리 헬퍼 및 라이프사이클 메서드에 대한 자세한 내용은 [NyState](/docs/{{ $version }}/ny-state)를 참조하세요.


<div id="building-your-first-widget"></div>

## 첫 번째 Widget 만들기

Nylo 프로젝트에서 다음 명령어를 실행하여 새 Widget을 생성합니다.

``` bash
metro make:stateful_widget todo_list
```

이렇게 하면 `TodoList`라는 새로운 `NyState` Widget이 생성됩니다.

> 참고: 새 Widget은 `lib/resources/widgets/` 디렉토리에 생성됩니다.
