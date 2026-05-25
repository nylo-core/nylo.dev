# Виджеты с управляемым состоянием

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Выбор паттерна](#choose-your-pattern "Выбор паттерна")
- [Действия состояния](#state-actions "Действия состояния")
  - [Определение обработчиков](#defining-handlers "Определение обработчиков")
  - [Вызов действий](#triggering-actions "Вызов действий")
  - [Обработчики с данными и без](#handlers-with-and-without-data "Обработчики с данными и без")
  - [Использование экземпляра StateActions](#using-a-state-actions-instance "Использование экземпляра StateActions")
- [Паттерн: Страницы с управляемым состоянием (NyPage)](#pattern-ny-page "Паттерн: Страницы с управляемым состоянием")
- [Паттерн: Stateful-виджеты (NyState)](#pattern-ny-state "Паттерн: Stateful-виджеты")
- [Паттерн: Виджеты с управляемым состоянием (NyStateManaged)](#pattern-ny-state-managed "Паттерн: Виджеты с управляемым состоянием")
  - [Расширенно: Несколько изолированных экземпляров](#advanced-multiple-isolated-instances "Расширенно: Несколько изолированных экземпляров")
- [Справочник жизненного цикла](#lifecycle "Справочник жизненного цикла")

<div id="introduction"></div>

## Введение

Виджеты с управляемым состоянием позволяют обновлять конкретные части UI из любого места приложения — без перестроения целых страниц. В {{ config('app.name') }} v7 вы вызываете именованные **действия состояния** на целевом виджете или странице, и запускается соответствующий обработчик.

Ментальная модель проста:

- Каждый виджет или страница с управляемым состоянием имеет уникальный **ключ состояния** (строку)
- Он определяет карту **именованных действий**, которые умеет обрабатывать
- Из любого места приложения вы можете вызвать
```
stateAction("action_name", state: TargetWidget.state)
``` 

Существует три паттерна для построения UI с управляемым состоянием. Все они используют один и тот же механизм `stateAction` — единственное отличие в том, каким видом UI вы управляете.


<div id="choose-your-pattern"></div>

## Выбор паттерна

| Что вы хотите сделать... | Используйте | Команда scaffold |
|---|---|---|
| Управлять состоянием целой страницы | `NyPage` | `metro make:page my_page` |
| Управлять состоянием виджета с одним экземпляром | `NyState` | `metro make:stateful_widget my_widget` |
| Управлять состоянием виджета с несколькими изолированными экземплярами | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Сначала прочитайте раздел [Действия состояния](#state-actions) — он объясняет API, используемый всеми паттернами. Затем перейдите к паттерну, подходящему для вашего случая.


<div id="state-actions"></div>

## Действия состояния

Действия состояния — это именованные команды, которые виджет или страница умеет обрабатывать. Вы определяете карту имён действий к функциям-обработчикам и вызываете их по имени из любого места приложения.

Используйте действия состояния, когда вам нужно:
- Вызвать определённое поведение на виджете или странице (не просто обновить его)
- Передать данные виджету и получить определённую реакцию
- Создавать переиспользуемые поведения виджетов, вызываемые из нескольких мест

<div id="defining-handlers"></div>

### Определение обработчиков

Обработчики находятся в геттере `stateActions` вашего класса `NyState` или `NyPage`. Ключи карты — это имена действий; значения — функции, выполняемые при вызове соответствующего действия.

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

Обработчики самостоятельно отвечают за вызов `setState`, если хотят перестроить виджет.

Обработчик `apply_discount` принимает аргумент `code` — объявите один позиционный параметр, когда обработчику нужна нагрузка, переданная через
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Используйте форму без аргументов `()`, когда действие не несёт нагрузку.


<div id="triggering-actions"></div>

### Вызов действий

Используйте функцию верхнего уровня `stateAction` для вызова действия из любого места — другого виджета, контроллера, обработчика событий, колбэка API и т.д.

``` dart
// Вызов действия без данных
stateAction("clear_cart", state: Cart.state);

// Вызов действия с данными
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

Аргумент `state:` — это **ключ состояния** цели:
- Для виджетов — `MyWidget.state` (строка)
- Для страниц — `MyPage.path` (маршрут)


<div id="handlers-with-and-without-data"></div>

### Обработчики с данными и без

Обработчики могут быть синхронными или асинхронными, и могут определяться с аргументом `data` или без него:

``` dart
@override
Map<String, Function> get stateActions => {
  // Без данных — обработчик выполняется как есть
  "reset": () {
    _value = null;
    setState(() {});
  },

  // С данными — получает всё, что передано через аргумент `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Асинхронность поддерживается — фреймворк ожидает выполнения обработчика
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Если вы передаёте `data:` в `stateAction`, но ваш обработчик не принимает аргументов, данные просто игнорируются.


<div id="using-a-state-actions-instance"></div>

### Использование экземпляра StateActions

Если виджет предоставляет типизированный экземпляр `StateActions` (как правило, через статический метод `stateActions(stateName)`), вы можете вызывать `.action(...)` непосредственно на нём вместо свободной функции. Это удобнее, когда нужно вызвать несколько действий на одном целевом виджете:

``` dart
// Используя свободную функцию
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Используя экземпляр StateActions — эквивалентно, меньше повторений
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Ряд встроенных виджетов (`InputField`, `CollectionView`, `LanguageSwitcher`, семейство `NyForm*`) поставляется с типизированными классами `StateActions` и именованными методами, такими как `.clear()`, `.setValue(...)`, `.refresh()` — смотрите документацию виджета для уточнения доступных возможностей.


<div id="pattern-ny-page"></div>

## Паттерн: Страницы с управляемым состоянием (NyPage)

Используйте этот паттерн, когда нужно вызывать поведение на целой странице из другого места приложения — например, обновить страницу при наступлении события или очистить состояние формы из дочернего виджета.

**Шаг 1:** Создайте страницу через scaffold.

``` bash
metro make:page my_page
```

Это создаёт `NyPage` в `lib/resources/pages/`:

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

**Шаг 2:** Установите `stateManaged` в `true`.

По умолчанию страницы **не** подписываются на события состояния — это позволяет избежать ненужных слушателей на страницах, которым они не нужны. Чтобы включить действия состояния на странице, измените геттер:

``` dart
@override
bool get stateManaged => true;
```

**Шаг 3:** Добавьте карту `stateActions`.

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

**Шаг 4:** Вызовите действие из любого места, используя `path` страницы.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Страницы идентифицируются по `MyPage.path`, виджеты — по `MyWidget.state`. Это единственное отличие на стороне вызова.


<div id="pattern-ny-state"></div>

## Паттерн: Stateful-виджеты (NyState)

Используйте этот паттерн для переиспользуемых виджетов, существующих в единственном экземпляре на странице — карточка профиля, заголовок, индикатор статуса. Если вы отображаете только один экземпляр виджета одновременно, это правильный паттерн.

**Шаг 1:** Создайте stateful-виджет через scaffold.

``` bash
metro make:stateful_widget profile_card
```

Это создаёт следующее в `lib/resources/widgets/`:

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

Scaffold создаёт рабочий виджет `NyState` — но он **ещё не управляет состоянием**. Чтобы он реагировал на действия состояния, нужно добавить четыре вещи:

1. Ключ `state` на классе виджета — уникальная строка, по которой другие части приложения будут обращаться к нему
2. Параметр конструктора, получающий ключ состояния и передающий его в класс состояния
3. Конструктор на классе состояния, присваивающий `stateName`
4. Карта `stateActions` с определёнными обработчиками

**Шаг 2:** Преобразуйте scaffold в виджет с управляемым состоянием.

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
    // ^ вызовите это из любого места вашего приложения, чтобы запустить обработчик ниже
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

Что изменилось:
- `static String get state => "profile_card";` определяет публичный ключ состояния
- `createState() => _ProfileCardState(state)` передаёт ключ в класс состояния
- `_ProfileCardState(String? state) { this.stateName = state; }` регистрирует этот экземпляр состояния под этим ключом, чтобы фреймворк знал, какому виджету доставить действие
- `stateActions` объявляет именованные обработчики

**Шаг 3:** Вызовите из любого места.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Вы можете добавить столько обработчиков, сколько нужно, с данными или без:

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

## Паттерн: Виджеты с управляемым состоянием (NyStateManaged)

Используйте этот паттерн, когда нужно **несколько независимых экземпляров одного виджета** одновременно на экране — например, значок корзины в шапке и ещё один в боковой панели, которые должны обновляться независимо.

`NyStateManaged` добавляет параметр `id`, чтобы каждый экземпляр можно было адресовать отдельно. Если вы всегда отображаете только один экземпляр виджета, предпочтите `NyState` — он проще.

**Шаг 1:** Создайте виджет с управляемым состоянием через scaffold.

``` bash
metro make:state_managed_widget cart
```

Это создаёт следующее в `lib/resources/widgets/`:

``` dart
class Cart extends NyStateManaged {
  Cart({super.key, super.id})
      : super(baseState: state, child: () => _CartState());

  static const String state = "cart";

  static action(String action, {dynamic data, String? id}) =>
      stateAction(action, data: data, state: state, id: id);
}

class _CartState extends NyState<Cart> {
  @override
  get init => () {
    // логика инициализации здесь
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Вызывайте действия из любого места вашего приложения
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

**Шаг 2:** Наполните состояние — загрузите данные в `init`, определите обработчики и реализуйте отображение.

``` dart
class _CartState extends NyState<Cart> {
  String? _cartValue;

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

**Шаг 3:** Вызывайте действия с помощью сгенерированного статического хелпера `action()`.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Это эквивалентно `stateAction("reload_cart", state: Cart.state)` — статический хелпер просто убирает шаблонный код.


<div id="advanced-multiple-isolated-instances"></div>

### Расширенно: Несколько изолированных экземпляров

Причина существования `NyStateManaged` — поддержка нескольких независимых экземпляров одного виджета. Каждый экземпляр получает свой `id`, который формирует ключ состояния с пространством имён.

Отобразите две корзины с разными id:

``` dart
Column(
  children: [
    Cart(id: "header"),
    Cart(id: "sidebar"),
  ],
)
```

Теперь вы можете обновлять каждую из них независимо:

``` dart
// Обновить только корзину в шапке
Cart.action("reload_cart", id: "header");

// Обновить только корзину в боковой панели
Cart.action("reload_cart", id: "sidebar");

// Без id — обращается к экземпляру по умолчанию
Cart.action("reload_cart");
```

`NyStateManaged` выполняет пространство имён: `Cart(id: "header")` регистрируется под ключом `"cart_header"`, а `Cart.action(..., id: "header")` адресуется именно к этому ключу.


<div id="lifecycle"></div>

## Справочник жизненного цикла

Виджеты и страницы с управляемым состоянием имеют два ключевых хука жизненного цикла:

1. **`init()`** — вызывается один раз при первом создании состояния. Используйте для загрузки начальных данных.

2. **`stateUpdated(data)`** — вызывается каждый раз, когда на данное состояние вызвано действие. Аргумент `data` содержит полную нагрузку (включая имя действия и его данные). Переопределите его, если нужно реагировать на *каждое* действие состояния — в большинстве случаев вместо этого лучше определять обработчики в `stateActions`.

**Смотрите также:** [NyState](/docs/{{ $version }}/ny-state) для полного набора хелперов состояния и методов жизненного цикла.
