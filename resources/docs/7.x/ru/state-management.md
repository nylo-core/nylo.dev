# State Management

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Когда использовать управление состоянием](#when-to-use-state-management "Когда использовать управление состоянием")
- [Жизненный цикл](#lifecycle "Жизненный цикл")
- [Действия состояния](#state-actions "Действия состояния")
  - [NyState - Действия состояния](#state-actions-nystate "NyState - Действия состояния")
  - [NyPage - Действия состояния](#state-actions-nypage "NyPage - Действия состояния")
- [Обновление состояния](#updating-a-state "Обновление состояния")
- [Создание вашего первого виджета](#building-your-first-widget "Создание вашего первого виджета")

<div id="introduction"></div>

## Введение

Управление состоянием позволяет обновлять определённые части вашего UI без перестроения целых страниц. В {{ config('app.name') }} v7 вы можете создавать виджеты, которые взаимодействуют и обновляют друг друга по всему приложению.

{{ config('app.name') }} предоставляет два класса для управления состоянием:
- **`NyState`** --- для создания переиспользуемых виджетов (таких как значок корзины, счётчик уведомлений или индикатор статуса)
- **`NyPage`** --- для создания страниц вашего приложения (расширяет `NyState` функциями, специфичными для страниц)

Используйте управление состоянием, когда вам нужно:
- Обновить виджет из другой части приложения
- Поддерживать синхронизацию виджетов с общими данными
- Избежать перестроения целых страниц, когда меняется только часть UI


### Давайте сначала разберёмся с управлением состоянием

Всё во Flutter --- это виджет, это просто небольшие части UI, которые вы можете комбинировать для создания полноценного приложения.

Когда вы начнёте создавать сложные страницы, вам потребуется управлять состоянием виджетов. Это означает, что при изменении чего-либо, например данных, вы можете обновить этот виджет без необходимости перестраивать всю страницу.

Есть множество причин, почему это важно, но главная --- производительность. Если у вас есть виджет, который постоянно меняется, вы не хотите перестраивать всю страницу каждый раз, когда он меняется.

Именно здесь на помощь приходит управление состоянием --- оно позволяет управлять состоянием виджета в вашем приложении.


<div id="when-to-use-state-management"></div>

### Когда использовать управление состоянием

Используйте управление состоянием, когда у вас есть виджет, который нужно обновлять без перестроения всей страницы.

Например, представьте, что вы создали приложение для электронной коммерции. Вы создали виджет для отображения общего количества товаров в корзине пользователя.
Назовём этот виджет `Cart()`.

Виджет `Cart` с управляемым состоянием в Nylo будет выглядеть примерно так:

**Шаг 1:** Определите виджет со статическим именем состояния

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Шаг 2:** Создайте класс состояния, расширяющий `NyState`

``` dart
/// The state class for the Cart widget
class _CartState extends NyState<Cart> {

  String? _cartValue;

  _CartState() {
    stateName = Cart.state; // Register the state name
  }

  @override
  get init => () async {
    _cartValue = await getCartValue(); // Load initial data
  };

  @override
  void stateUpdated(data) {
    reboot(); // Reload the widget when state updates
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

**Шаг 3:** Создайте вспомогательные функции для чтения и обновления корзины

``` dart
/// Get the cart value from storage
Future<String> getCartValue() async {
  return await storageRead(Keys.cart) ?? "1";
}

/// Set the cart value and notify the widget
Future setCartValue(String value) async {
    await storageSave(Keys.cart, value);
    updateState(Cart.state); // This triggers stateUpdated() on the widget
}
```

Разберём это по шагам.

1. Виджет `Cart` --- это `StatefulWidget`.

2. `_CartState` расширяет `NyState<Cart>`.

3. Вам нужно определить имя для `state` --- оно используется для идентификации состояния.

4. Метод `boot()` вызывается при первой загрузке виджета.

5. Методы `stateUpdate()` обрабатывают то, что происходит при обновлении состояния.

Если вы хотите попробовать этот пример в вашем проекте {{ config('app.name') }}, создайте новый виджет с именем `Cart`.

``` bash
metro make:state_managed_widget cart
```

Затем вы можете скопировать пример выше и попробовать его в своём проекте.

Теперь, чтобы обновить корзину, вы можете вызвать следующее.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Жизненный цикл

Жизненный цикл виджета `NyState` выглядит следующим образом:

1. `init()` --- этот метод вызывается при инициализации состояния.

2. `stateUpdated(data)` --- этот метод вызывается при обновлении состояния.

    Если вы вызовете `updateState(MyStateName.state, data: "The Data")`, это запустит вызов **stateUpdated(data)**.

После первой инициализации состояния вам нужно реализовать способ управления состоянием.


<div id="state-actions"></div>

## Действия состояния

Действия состояния позволяют запускать определённые методы виджета из любого места вашего приложения. Думайте о них как об именованных командах, которые вы можете отправлять виджету.

Используйте действия состояния, когда вам нужно:
- Запустить определённое поведение виджета (а не просто обновить его)
- Передать данные виджету и получить определённую реакцию
- Создать переиспользуемые поведения виджетов, которые можно вызывать из нескольких мест

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

В вашем виджете вы можете определить действия, которые хотите обрабатывать.

``` dart
...
@override
get stateActions => {
  "hello_world_in_widget": () {
    print('Hello world');
  },
  "reset_data": (data) async {
    // Example with data
    _textController.clear();
    _myData = null;
    setState(() {});
  },
};
```

Затем вы можете вызвать метод `stateAction` из любого места вашего приложения.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Вы также можете определить действия состояния, используя метод `whenStateAction` в геттере `init`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```


<div id="state-actions-nystate"></div>

### NyState - Действия состояния

Сначала создайте stateful-виджет.

``` bash
metro make:stateful_widget [widget_name]
```
Пример: metro make:stateful_widget user_avatar

Это создаст новый виджет в директории `lib/resources/widgets/`.

Если вы откроете этот файл, вы сможете определить действия состояния.

``` dart
class _UserAvatarState extends NyState<UserAvatar> {
...

@override
get stateActions => {
  "reset_avatar": () {
    // Example
    _avatar = null;
    setState(() {});
  },
  "update_user_image": (User user) {
    // Example
    _avatar = user.image;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Наконец, вы можете отправить действие из любого места вашего приложения.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - Действия состояния

Страницы также могут получать действия состояния. Это полезно, когда вы хотите запускать поведение на уровне страницы из виджетов или других страниц.

Сначала создайте страницу с управляемым состоянием.

``` bash
metro make:page my_page
```

Это создаст новую страницу с управляемым состоянием под названием `MyPage` в директории `lib/resources/pages/`.

Если вы откроете этот файл, вы сможете определить действия состояния.

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
    // Example
    _textController.clear();
    _myData = null;
    setState(() {});
  },
  "show_toast": (data) {
    showSuccessToast(description: data['message']);
  },
};
```

Наконец, вы можете отправить действие из любого места вашего приложения.

``` dart
stateAction('test_page_action', state: MyPage.state);
// prints 'Hello from the page'

stateAction('reset_data', state: MyPage.state);
// Reset data in page

stateAction('show_toast', state: MyPage.state, data: {
  "message": "Hello from the page"
});
// shows a success toast with the message
```

Вы также можете определить действия состояния, используя метод `whenStateAction`.

``` dart
@override
get init => () async {
  ...
  whenStateAction({
    "reset_badge": () {
      // Reset the badge count
      _count = 0;
    }
  });
}
```

Затем вы можете отправить действие из любого места вашего приложения.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Обновление состояния

Вы можете обновить состояние, вызвав метод `updateState()`.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Это можно вызвать из любого места вашего приложения.

**Смотрите также:** [NyState](/docs/{{ $version }}/ny-state) для получения более подробной информации о хелперах и методах жизненного цикла управления состоянием.


<div id="building-your-first-widget"></div>

## Создание вашего первого виджета

В вашем проекте Nylo выполните следующую команду для создания нового виджета.

``` bash
metro make:stateful_widget todo_list
```

Это создаст новый виджет `NyState` с именем `TodoList`.

> Примечание: Новый виджет будет создан в директории `lib/resources/widgets/`.
