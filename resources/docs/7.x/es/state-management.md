# State Management

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Cuando Usar la Gestion de Estado](#when-to-use-state-management "Cuando Usar la Gestion de Estado")
- [Ciclo de Vida](#lifecycle "Ciclo de Vida")
- [Acciones de Estado](#state-actions "Acciones de Estado")
  - [NyState - Acciones de Estado](#state-actions-nystate "NyState - Acciones de Estado")
  - [NyPage - Acciones de Estado](#state-actions-nypage "NyPage - Acciones de Estado")
- [Actualizar un Estado](#updating-a-state "Actualizar un Estado")
- [Construyendo tu Primer Widget](#building-your-first-widget "Construyendo tu Primer Widget")

<div id="introduction"></div>

## Introduccion

La gestion de estado te permite actualizar partes especificas de tu interfaz sin reconstruir paginas enteras. En {{ config('app.name') }} v7, puedes construir widgets que se comunican y actualizan entre si en toda tu aplicacion.

{{ config('app.name') }} proporciona dos clases para la gestion de estado:
- **`NyState`** -- Para construir widgets reutilizables (como una insignia de carrito, contador de notificaciones o indicador de estado)
- **`NyPage`** -- Para construir paginas en tu aplicacion (extiende `NyState` con funcionalidades especificas de pagina)

Usa la gestion de estado cuando necesites:
- Actualizar un widget desde una parte diferente de tu aplicacion
- Mantener los widgets sincronizados con datos compartidos
- Evitar reconstruir paginas enteras cuando solo cambia una parte de la interfaz


### Primero entendamos la Gestion de Estado

Todo en Flutter es un widget, son solo pequenos fragmentos de interfaz que puedes combinar para crear una aplicacion completa.

Cuando comienzas a construir paginas complejas, necesitaras gestionar el estado de tus widgets. Esto significa que cuando algo cambia, por ejemplo datos, puedes actualizar ese widget sin tener que reconstruir la pagina entera.

Hay muchas razones por las que esto es importante, pero la razon principal es el rendimiento. Si tienes un widget que cambia constantemente, no quieres reconstruir la pagina entera cada vez que cambia.

Aqui es donde entra la Gestion de Estado, te permite gestionar el estado de un widget en tu aplicacion.


<div id="when-to-use-state-management"></div>

### Cuando Usar la Gestion de Estado

Debes usar la Gestion de Estado cuando tienes un widget que necesita actualizarse sin reconstruir la pagina entera.

Por ejemplo, imaginemos que has creado una aplicacion de comercio electronico. Has construido un widget para mostrar la cantidad total de articulos en el carrito del usuario.
Llamemos a este widget `Cart()`.

Un widget `Cart` gestionado por estado en Nylo se veria algo asi:

**Paso 1:** Define el widget con un nombre de estado estatico

``` dart
/// The Cart widget
class Cart extends StatefulWidget {

  Cart({Key? key}) : super(key: key);

  static String state = "cart"; // Unique identifier for this widget's state

  @override
  _CartState createState() => _CartState();
}
```

**Paso 2:** Crea la clase de estado extendiendo `NyState`

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

**Paso 3:** Crea funciones auxiliares para leer y actualizar el carrito

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

Desglosemos esto.

1. El widget `Cart` es un `StatefulWidget`.

2. `_CartState` extiende `NyState<Cart>`.

3. Necesitas definir un nombre para el `state`, esto se usa para identificar el estado.

4. El metodo `boot()` se llama cuando el widget se carga por primera vez.

5. Los metodos `stateUpdate()` manejan lo que sucede cuando el estado se actualiza.

Si quieres probar este ejemplo en tu proyecto de {{ config('app.name') }}, crea un nuevo widget llamado `Cart`.

``` bash
metro make:state_managed_widget cart
```

Luego puedes copiar el ejemplo anterior y probarlo en tu proyecto.

Ahora, para actualizar el carrito, puedes llamar lo siguiente.

```dart
_updateCart() async {
  String count = await getCartValue();
  String countIncremented = (int.parse(count) + 1).toString();

  await storageSave(Keys.cart, countIncremented);

  updateState(Cart.state);
}
```


<div id="lifecycle"></div>

## Ciclo de Vida

El ciclo de vida de un widget `NyState` es el siguiente:

1. `init()` - Este metodo se llama cuando el estado se inicializa.

2. `stateUpdated(data)` - Este metodo se llama cuando el estado se actualiza.

    Si llamas a `updateState(MyStateName.state, data: "The Data")`, activara que se llame a **stateUpdated(data)**.

Una vez que el estado se inicializa por primera vez, necesitaras implementar como quieres gestionar el estado.


<div id="state-actions"></div>

## Acciones de Estado

Las acciones de estado te permiten activar metodos especificos en un widget desde cualquier parte de tu aplicacion. Piensa en ellas como comandos con nombre que puedes enviar a un widget.

Usa las acciones de estado cuando necesites:
- Activar un comportamiento especifico en un widget (no solo actualizarlo)
- Pasar datos a un widget y que responda de una manera particular
- Crear comportamientos de widget reutilizables que pueden ser invocados desde multiples lugares

``` dart
// Sending an action to the widget
stateAction('hello_world_in_widget', state: MyWidget.state);

// Another example with data
stateAction('show_high_score', state: HighScore.state, data: {
  "high_score": 100,
});
```

En tu widget, puedes definir las acciones que quieres manejar.

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

Luego, puedes llamar al metodo `stateAction` desde cualquier parte de tu aplicacion.

``` dart
stateAction('hello_world_in_widget', state: MyWidget.state);
// prints 'Hello world'

User user = User(name: "John Doe", age: 30);
stateAction('update_user_info', state: MyWidget.state, data: user);
```

Tambien puedes definir tus acciones de estado usando el metodo `whenStateAction` en tu getter `init`.

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

### NyState - Acciones de Estado

Primero, crea un widget stateful.

``` bash
metro make:stateful_widget [widget_name]
```
Ejemplo: metro make:stateful_widget user_avatar

Esto creara un nuevo widget en el directorio `lib/resources/widgets/`.

Si abres ese archivo, podras definir tus acciones de estado.

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

Finalmente, puedes enviar la accion desde cualquier parte de tu aplicacion.

``` dart
stateAction('reset_avatar', state: MyWidget.state);
// prints 'Hello from the widget'

stateAction('reset_data', state: MyWidget.state);
// Reset data in widget

stateAction('show_toast', state: MyWidget.state, data: "Hello world");
// shows a success toast with the message
```


<div id="state-actions-nypage"></div>

### NyPage - Acciones de Estado

Las paginas tambien pueden recibir acciones de estado. Esto es util cuando quieres activar comportamientos a nivel de pagina desde widgets u otras paginas.

Primero, crea tu pagina gestionada por estado.

``` bash
metro make:page my_page
```

Esto creara una nueva pagina gestionada por estado llamada `MyPage` en el directorio `lib/resources/pages/`.

Si abres ese archivo, podras definir tus acciones de estado.

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

Finalmente, puedes enviar la accion desde cualquier parte de tu aplicacion.

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

Tambien puedes definir tus acciones de estado usando el metodo `whenStateAction`.

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

Luego puedes enviar la accion desde cualquier parte de tu aplicacion.

``` dart
stateAction('reset_badge', state: MyWidget.state);
```


<div id="updating-a-state"></div>

## Actualizar un Estado

Puedes actualizar un estado llamando al metodo `updateState()`.

``` dart
updateState(MyStateName.state);

// or with data
updateState(MyStateName.state, data: "The Data");
```

Esto puede ser llamado desde cualquier parte de tu aplicacion.

**Ver tambien:** [NyState](/docs/{{ $version }}/ny-state) para mas detalles sobre los helpers de gestion de estado y metodos del ciclo de vida.


<div id="building-your-first-widget"></div>

## Construyendo tu Primer Widget

En tu proyecto Nylo, ejecuta el siguiente comando para crear un nuevo widget.

``` bash
metro make:stateful_widget todo_list
```

Esto creara un nuevo widget `NyState` llamado `TodoList`.

> Nota: El nuevo widget sera creado en el directorio `lib/resources/widgets/`.
