# Widgets Gestionados por Estado

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Elige tu Patrón](#choose-your-pattern "Elige tu Patrón")
- [Acciones de Estado](#state-actions "Acciones de Estado")
  - [Definir Manejadores](#defining-handlers "Definir Manejadores")
  - [Disparar Acciones](#triggering-actions "Disparar Acciones")
  - [Manejadores Con y Sin Datos](#handlers-with-and-without-data "Manejadores Con y Sin Datos")
  - [Usar una Instancia de StateActions](#using-a-state-actions-instance "Usar una Instancia de StateActions")
- [Patrón: Páginas Gestionadas por Estado (NyPage)](#pattern-ny-page "Patrón: Páginas Gestionadas por Estado")
- [Patrón: Widgets con Estado (NyState)](#pattern-ny-state "Patrón: Widgets con Estado")
- [Patrón: Widgets Gestionados por Estado (NyStateManaged)](#pattern-ny-state-managed "Patrón: Widgets Gestionados por Estado")
  - [Avanzado: Múltiples Instancias Aisladas](#advanced-multiple-isolated-instances "Avanzado: Múltiples Instancias Aisladas")
- [Referencia del Ciclo de Vida](#lifecycle "Referencia del Ciclo de Vida")

<div id="introduction"></div>

## Introducción

Los widgets gestionados por estado te permiten actualizar partes específicas de tu interfaz desde cualquier lugar de tu app, sin reconstruir páginas enteras. En {{ config('app.name') }} v7, disparas **acciones de estado** con nombre sobre un widget o página objetivo, y el manejador correspondiente se ejecuta.

El modelo mental es simple:

- Cada widget o página gestionado por estado tiene una **clave de estado** única (una cadena de texto)
- Define un mapa de **acciones con nombre** que sabe cómo manejar
- Desde cualquier lugar de tu app, puedes llamar a
```
stateAction("action_name", state: TargetWidget.state)
``` 

Hay tres patrones para construir interfaces gestionadas por estado. Todos comparten la misma mecánica de `stateAction` — la única diferencia es qué tipo de interfaz estás gestionando.


<div id="choose-your-pattern"></div>

## Elige tu Patrón

| Quieres... | Usa | Comando de andamiaje |
|---|---|---|
| Gestionar el estado de una página completa | `NyPage` | `metro make:page my_page` |
| Gestionar el estado de un widget de instancia única | `NyState` | `metro make:stateful_widget my_widget` |
| Gestionar el estado de un widget con múltiples instancias aisladas | `NyStateManaged` | `metro make:state_managed_widget my_widget` |

Lee primero la sección de [Acciones de Estado](#state-actions) — explica la API que utiliza cada patrón. Luego ve al patrón que se ajuste a tu caso.


<div id="state-actions"></div>

## Acciones de Estado

Las acciones de estado son comandos con nombre que un widget o página sabe cómo manejar. Defines un mapa de nombres de acciones a funciones manejadoras, y las disparas por nombre desde cualquier lugar de tu app.

Usa las acciones de estado cuando necesites:
- Disparar un comportamiento específico en un widget o página (no solo una actualización genérica)
- Pasar datos a un widget y que responda de una manera definida
- Construir comportamientos de widget reutilizables que se puedan invocar desde múltiples puntos

<div id="defining-handlers"></div>

### Definir Manejadores

Los manejadores viven en el getter `stateActions` de tu clase `NyState` o `NyPage`. Las claves del mapa son los nombres de las acciones; los valores son las funciones que se ejecutan cuando se dispara esa acción.

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

Los manejadores son responsables de llamar a `setState` ellos mismos si quieren que el widget se reconstruya.

El manejador `apply_discount` anterior recibe un argumento `code` — declara un único parámetro posicional cuando tu manejador necesita el valor pasado mediante
```
stateAction("reload_cart", state: TargetWidget.state);
stateAction("clear_cart", state: TargetWidget.state);
stateAction("apply_discount", state: TargetWidget.state, data: "promo_code_123");
```

Usa la forma sin argumentos `()` cuando la acción no lleva carga de datos.


<div id="triggering-actions"></div>

### Disparar Acciones

Usa la función global `stateAction` para disparar una acción desde cualquier lugar — otro widget, un controlador, un manejador de eventos, un callback de API, etc.

``` dart
// Disparar una acción sin datos
stateAction("clear_cart", state: Cart.state);

// Disparar una acción con datos
stateAction("show_toast", state: Cart.state, data: {
  "message": "Item added",
});
```

El argumento `state:` es la **clave de estado** del objetivo:
- Para widgets — `MyWidget.state` (una cadena de texto)
- Para páginas — `MyPage.path` (la ruta)


<div id="handlers-with-and-without-data"></div>

### Manejadores Con y Sin Datos

Los manejadores pueden ser síncronos o asíncronos, y pueden definirse con o sin un argumento `data`:

``` dart
@override
Map<String, Function> get stateActions => {
  // Sin datos — el manejador se ejecuta tal cual
  "reset": () {
    _value = null;
    setState(() {});
  },

  // Con datos — recibe lo que se pasó mediante el argumento `data:`
  "set_value": (data) {
    _value = data;
    setState(() {});
  },

  // Async está soportado — el framework aguarda al manejador
  "reload": (data) async {
    _items = await fetchItems();
    setState(() {});
  },
};
```

Si pasas `data:` a `stateAction` pero tu manejador no toma argumentos, los datos simplemente se ignoran.


<div id="using-a-state-actions-instance"></div>

### Usar una Instancia de StateActions

Si un widget expone una instancia tipada de `StateActions` (normalmente mediante un método estático `stateActions(stateName)`), puedes llamar a `.action(...)` directamente en ella en lugar de usar la función libre. Esto es más limpio cuando se disparan varias acciones hacia el mismo objetivo:

``` dart
// Usando la función libre
stateAction("reset_avatar", state: UserAvatar.state);
stateAction("update_user_image", state: UserAvatar.state, data: user);

// Usando una instancia de StateActions — equivalente, menos repetición
final actions = UserAvatar.stateActions(UserAvatar.state);
actions.action("reset_avatar");
actions.action("update_user_image", data: user);
```

Varios widgets integrados (`InputField`, `CollectionView`, `LanguageSwitcher`, la familia `NyForm*`) incluyen clases `StateActions` tipadas con métodos con nombre como `.clear()`, `.setValue(...)`, `.refresh()` — consulta la documentación del widget para ver qué está disponible.


<div id="pattern-ny-page"></div>

## Patrón: Páginas Gestionadas por Estado (NyPage)

Usa esto cuando quieras disparar comportamiento en una página completa desde otro lugar de tu app — por ejemplo, actualizar una página cuando se dispara un evento, o limpiar el estado de un formulario desde un widget hijo.

**Paso 1:** Genera una página con andamiaje.

``` bash
metro make:page my_page
```

Esto genera un `NyPage` en `lib/resources/pages/`:

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

**Paso 2:** Cambia `stateManaged` a `true`.

Por defecto, las páginas **no** se suscriben a eventos de estado — esto evita listeners innecesarios en páginas que no los necesitan. Para habilitar las acciones de estado en una página, cambia el getter:

``` dart
@override
bool get stateManaged => true;
```

**Paso 3:** Añade un mapa `stateActions`.

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

**Paso 4:** Dispara la acción desde cualquier lugar usando el `path` de la página.

``` dart
stateAction("refresh_data", state: MyPage.path);

stateAction("show_toast", state: MyPage.path, data: {
  "message": "Welcome back",
});
```

> Las páginas usan `MyPage.path` como clave, los widgets usan `MyWidget.state`. Esta es la única diferencia en el punto de llamada.


<div id="pattern-ny-state"></div>

## Patrón: Widgets con Estado (NyState)

Usa esto para widgets reutilizables que existen como una única instancia por página — una tarjeta de perfil, una barra de cabecera, un indicador de estado. Si solo renderizas una instancia del widget a la vez, este es el patrón correcto.

**Paso 1:** Genera un widget con estado mediante andamiaje.

``` bash
metro make:stateful_widget profile_card
```

Esto genera lo siguiente en `lib/resources/widgets/`:

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

El andamiaje te da un widget `NyState` funcional — pero **aún no está gestionado por estado**. Para que responda a las acciones de estado, necesitas añadir cuatro cosas:

1. Una clave `state` en la clase del widget — la cadena única que otras partes de tu app usarán como objetivo
2. Un parámetro en el constructor que recibe la clave de estado y la reenvía a la clase de estado
3. Un constructor en la clase de estado que asigna `stateName`
4. Un mapa `stateActions` que define los manejadores

**Paso 2:** Convierte el andamiaje en un widget gestionado por estado.

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
    // ^ llama esto desde cualquier lugar de tu app para disparar el manejador de abajo
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

Qué cambió:
- `static String get state => "profile_card";` define la clave de estado pública
- `createState() => _ProfileCardState(state)` pasa la clave a la clase de estado
- `_ProfileCardState(String? state) { this.stateName = state; }` registra esta instancia de estado bajo esa clave, para que el framework sepa a qué widget entregar la acción
- `stateActions` declara los manejadores con nombre

**Paso 3:** Dispara desde cualquier lugar.

``` dart
stateAction("hello_world", state: ProfileCard.state);
```

Puedes añadir tantos manejadores como necesites, con o sin datos:

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

## Patrón: Widgets Gestionados por Estado (NyStateManaged)

Usa esto cuando necesites **múltiples instancias independientes del mismo widget** en pantalla a la vez — por ejemplo, un badge de carrito en la cabecera y otro en una barra lateral que deben actualizarse de forma independiente.

`NyStateManaged` añade un parámetro `stateName` para que cada instancia pueda ser dirigida por separado. Si solo renderizas una instancia del widget, prefiere `NyState` en su lugar — es más sencillo.

**Paso 1:** Genera un widget gestionado por estado con andamiaje.

``` bash
metro make:state_managed_widget cart
```

Esto genera lo siguiente en `lib/resources/widgets/`:

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
    // lógica de inicialización aquí
  };

  @override
  Map<String, Function> get stateActions => {
    "my_action": (data) {},
    "clear_data": () {
      // Invoca acciones desde cualquier lugar de tu app
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

**Paso 2:** Desarrolla el estado — carga datos en `init`, define manejadores y renderiza.

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

**Paso 3:** Dispara acciones usando el helper estático `action()` generado.

``` dart
Cart.action("reload_cart");
Cart.action("clear_cart");
```

Esto es equivalente a `stateAction("reload_cart", state: Cart.state)` — el helper estático solo elimina el código repetitivo.


<div id="advanced-multiple-isolated-instances"></div>

### Avanzado: Múltiples Instancias Aisladas

La razón por la que existe `NyStateManaged` es para soportar múltiples instancias independientes del mismo widget. Cada instancia obtiene su propio `stateName`, que produce una clave de estado con espacio de nombres.

Renderiza dos carritos con nombres distintos:

``` dart
Column(
  children: [
    Cart(stateName: "header"),
    Cart(stateName: "sidebar"),
  ],
)
```

Ahora puedes actualizar cualquiera de ellos de forma independiente:

``` dart
// Actualizar solo el carrito de la cabecera
Cart.action("reload_cart", stateName: "header");

// Actualizar solo el carrito de la barra lateral
Cart.action("reload_cart", stateName: "sidebar");

// Sin stateName — apunta a la instancia predeterminada sin nombre
Cart.action("reload_cart");
```

El helper `_stateFor` gestiona el espacio de nombres: `Cart(stateName: "header")` se registra bajo la clave `"cart_header"`, y `Cart.action(..., stateName: "header")` apunta exactamente a esa clave.


<div id="lifecycle"></div>

## Referencia del Ciclo de Vida

Los widgets y páginas gestionados por estado comparten dos hooks clave del ciclo de vida:

1. **`init()`** — se llama una vez cuando el estado se crea por primera vez. Úsalo para cargar datos iniciales.

2. **`stateUpdated(data)`** — se llama cada vez que se dispara una acción de estado contra este estado. El argumento `data` es la carga completa (incluyendo el nombre de la acción y los datos de la acción). Sobreescríbelo si necesitas reaccionar a *cada* acción de estado — la mayor parte del tiempo, definir manejadores en `stateActions` es lo que querrás hacer.

**Ver también:** [NyState](/docs/{{ $version }}/ny-state) para el conjunto completo de helpers de estado y métodos del ciclo de vida.
