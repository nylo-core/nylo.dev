# Alerts

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Estilos integrados](#built-in-styles "Estilos integrados")
- [Mostrar alertas desde paginas](#from-pages "Mostrar alertas desde paginas")
- [Mostrar alertas desde controladores](#from-controllers "Mostrar alertas desde controladores")
- [showToastNotification](#show-toast-notification "showToastNotification")
- [ToastMeta](#toast-meta "ToastMeta")
- [Posicionamiento](#positioning "Posicionamiento")
- [Estilos de toast personalizados](#custom-styles "Estilos de toast personalizados")
  - [Registrar estilos](#registering-styles "Registrar estilos")
  - [Crear una fabrica de estilos](#creating-a-style-factory "Crear una fabrica de estilos")
- [AlertTab](#alert-tab "AlertTab")
- [Ejemplos](#examples "Ejemplos practicos")

<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} proporciona un sistema de notificaciones toast para mostrar alertas a los usuarios. Incluye cuatro estilos integrados -- **success**, **warning**, **info** y **danger** -- y soporta estilos personalizados a traves de un patron de registro.

Las alertas pueden activarse desde paginas, controladores o cualquier lugar donde tengas un `BuildContext`.

<div id="basic-usage"></div>

## Uso basico

Muestra una notificacion toast usando metodos de conveniencia en cualquier pagina `NyState`:

``` dart
// Success toast
showToastSuccess(description: "Item saved successfully");

// Warning toast
showToastWarning(description: "Your session is about to expire");

// Info toast
showToastInfo(description: "New version available");

// Danger toast
showToastDanger(description: "Failed to save item");
```

O usa la funcion global con un ID de estilo:

``` dart
showToastNotification(context, id: "success", description: "Item saved!");
```

<div id="built-in-styles"></div>

## Estilos integrados

{{ config('app.name') }} registra cuatro estilos de toast por defecto:

| ID de estilo | Icono | Color | Titulo por defecto |
|----------|------|-------|---------------|
| `success` | Marca de verificacion | Verde | Success |
| `warning` | Exclamacion | Naranja | Warning |
| `info` | Icono de info | Turquesa | Info |
| `danger` | Icono de advertencia | Rojo | Error |

Estos se configuran en `lib/config/toast_notification.dart`:

``` dart
class ToastNotificationConfig {
  static final Map<String, ToastStyleFactory> styles = {
    'success': ToastNotification.style(
      icon: Icon(Icons.check_circle, color: Colors.green, size: 20),
      color: Colors.green.shade50,
      defaultTitle: 'Success',
    ),
    'warning': ToastNotification.style(
      icon: Icon(Icons.warning_amber_rounded, color: Colors.orange, size: 20),
      color: Colors.orange.shade50,
      defaultTitle: 'Warning',
    ),
    'info': ToastNotification.style(
      icon: Icon(Icons.info_outline, color: Colors.teal, size: 20),
      color: Colors.teal.shade50,
      defaultTitle: 'Info',
    ),
    'danger': ToastNotification.style(
      icon: Icon(Icons.warning_rounded, color: Colors.red, size: 20),
      color: Colors.red.shade50,
      defaultTitle: 'Error',
    ),
  };
}
```

<div id="from-pages"></div>

## Mostrar alertas desde paginas

En cualquier pagina que extienda `NyState` o `NyBaseState`, usa estos metodos de conveniencia:

``` dart
class _MyPageState extends NyState<MyPage> {

  void onSave() {
    // Success
    showToastSuccess(description: "Saved!");

    // With custom title
    showToastSuccess(title: "Done", description: "Your profile was updated.");

    // Warning
    showToastWarning(description: "Check your input");

    // Info
    showToastInfo(description: "Tip: Swipe left to delete");

    // Danger
    showToastDanger(description: "Something went wrong");

    // Oops (uses danger style)
    showToastOops(description: "That didn't work");

    // Sorry (uses danger style)
    showToastSorry(description: "We couldn't process your request");

    // Custom style by ID
    showToastCustom(id: "custom", description: "Custom alert!");
  }
}
```

### Metodo general de toast

``` dart
showToast(
  id: 'success',
  title: 'Hello',
  description: 'Welcome back!',
  duration: Duration(seconds: 3),
);
```

<div id="from-controllers"></div>

## Mostrar alertas desde controladores

Los controladores que extienden `NyController` tienen los mismos metodos de conveniencia:

``` dart
class ProfileController extends NyController {
  void updateProfile() async {
    try {
      await api.updateProfile();
      showToastSuccess(description: "Profile updated");
    } catch (e) {
      showToastDanger(description: "Failed to update profile");
    }
  }
}
```

Metodos disponibles: `showToastSuccess`, `showToastWarning`, `showToastInfo`, `showToastDanger`, `showToastOops`, `showToastSorry`, `showToastCustom`.

<div id="show-toast-notification"></div>

## showToastNotification

La funcion global `showToastNotification()` muestra un toast desde cualquier lugar donde tengas un `BuildContext`:

``` dart
showToastNotification(
  context,
  id: 'success',
  title: 'Saved',
  description: 'Your changes have been saved.',
  duration: Duration(seconds: 3),
  position: ToastNotificationPosition.top,
  action: () {
    // Called when the toast is tapped
    routeTo("/details");
  },
  onDismiss: () {
    // Called when the toast is dismissed
  },
  onShow: () {
    // Called when the toast becomes visible
  },
);
```

### Parametros

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `context` | `BuildContext` | requerido | Contexto de construccion |
| `id` | `String` | `'success'` | ID del estilo de toast |
| `title` | `String?` | null | Sobrescribir el titulo por defecto |
| `description` | `String?` | null | Texto de descripcion |
| `duration` | `Duration?` | null | Tiempo de visualizacion del toast |
| `position` | `ToastNotificationPosition?` | null | Posicion en pantalla |
| `action` | `VoidCallback?` | null | Callback al tocar |
| `onDismiss` | `VoidCallback?` | null | Callback al descartar |
| `onShow` | `VoidCallback?` | null | Callback al mostrar |

<div id="toast-meta"></div>

## ToastMeta

`ToastMeta` encapsula todos los datos de una notificacion toast:

``` dart
ToastMeta(
  title: 'Custom Alert',
  description: 'Something happened.',
  icon: Icon(Icons.star, color: Colors.purple),
  color: Colors.purple.shade50,
  style: 'custom',
  duration: Duration(seconds: 5),
  position: ToastNotificationPosition.top,
  action: () => print("Tapped!"),
  dismiss: () => print("Dismiss pressed"),
  onDismiss: () => print("Toast dismissed"),
  onShow: () => print("Toast shown"),
  metaData: {"key": "value"},
)
```

### Propiedades

| Propiedad | Tipo | Predeterminado | Descripcion |
|----------|------|---------|-------------|
| `icon` | `Widget?` | null | Widget de icono |
| `title` | `String` | `''` | Texto del titulo |
| `style` | `String` | `''` | Identificador de estilo |
| `description` | `String` | `''` | Texto de descripcion |
| `color` | `Color?` | null | Color de fondo para la seccion de icono |
| `action` | `VoidCallback?` | null | Callback al tocar |
| `dismiss` | `VoidCallback?` | null | Callback del boton descartar |
| `onDismiss` | `VoidCallback?` | null | Callback de descarte automatico/manual |
| `onShow` | `VoidCallback?` | null | Callback de visibilidad |
| `duration` | `Duration` | 5 segundos | Duracion de visualizacion |
| `position` | `ToastNotificationPosition` | `top` | Posicion en pantalla |
| `metaData` | `Map<String, dynamic>?` | null | Metadatos personalizados |

### copyWith

Crea una copia modificada de `ToastMeta`:

``` dart
ToastMeta updated = originalMeta.copyWith(
  title: "New Title",
  duration: Duration(seconds: 10),
);
```

<div id="positioning"></div>

## Posicionamiento

Controla donde aparecen los toasts en la pantalla:

``` dart
// Top of screen (default)
showToastNotification(context,
  id: "success",
  description: "Top alert",
  position: ToastNotificationPosition.top,
);

// Bottom of screen
showToastNotification(context,
  id: "info",
  description: "Bottom alert",
  position: ToastNotificationPosition.bottom,
);

// Center of screen
showToastNotification(context,
  id: "warning",
  description: "Center alert",
  position: ToastNotificationPosition.center,
);
```

<div id="custom-styles"></div>

## Estilos de toast personalizados

<div id="registering-styles"></div>

### Registrar estilos

Registra estilos personalizados en tu `AppProvider`:

``` dart
class AppProvider implements NyProvider {
  @override
  setup(Nylo nylo) async {
    await nylo.configure(
      toastNotifications: {
        ...ToastNotificationConfig.styles,
        'custom': ToastNotification.style(
          icon: Icon(Icons.star, color: Colors.purple, size: 20),
          color: Colors.purple.shade50,
          defaultTitle: 'Custom!',
        ),
      },
    );
    return nylo;
  }
}
```

O agrega estilos en cualquier momento:

``` dart
nylo.addToastNotifications({
  'promo': ToastNotification.style(
    icon: Icon(Icons.local_offer, color: Colors.pink, size: 20),
    color: Colors.pink.shade50,
    defaultTitle: 'Special Offer',
    position: ToastNotificationPosition.bottom,
    duration: Duration(seconds: 8),
  ),
});
```

Luego usalo:

``` dart
showToastNotification(context, id: "promo", description: "50% off today!");
```

<div id="creating-a-style-factory"></div>

### Crear una fabrica de estilos

`ToastNotification.style()` crea un `ToastStyleFactory`:

``` dart
static ToastStyleFactory style({
  required Widget icon,
  required Color color,
  required String defaultTitle,
  ToastNotificationPosition? position,
  Duration? duration,
  Widget Function(ToastMeta toastMeta)? builder,
})
```

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `icon` | `Widget` | Widget de icono para el toast |
| `color` | `Color` | Color de fondo para la seccion de icono |
| `defaultTitle` | `String` | Titulo mostrado cuando no se proporciona ninguno |
| `position` | `ToastNotificationPosition?` | Posicion por defecto |
| `duration` | `Duration?` | Duracion por defecto |
| `builder` | `Widget Function(ToastMeta)?` | Constructor de widget personalizado para control total |

### Constructor completamente personalizado

Para control total sobre el widget del toast:

``` dart
'banner': (ToastMeta meta, void Function(ToastMeta) updateMeta) {
  return Container(
    margin: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
    padding: EdgeInsets.all(16),
    decoration: BoxDecoration(
      color: Colors.indigo,
      borderRadius: BorderRadius.circular(12),
    ),
    child: Row(
      children: [
        Icon(Icons.campaign, color: Colors.white),
        SizedBox(width: 12),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(meta.title, style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              Text(meta.description, style: TextStyle(color: Colors.white70)),
            ],
          ),
        ),
      ],
    ),
  );
}
```

<div id="alert-tab"></div>

## AlertTab

`AlertTab` es un widget de insignia para agregar indicadores de notificacion a las pestanas de navegacion. Muestra una insignia que puede alternarse y opcionalmente persistirse en el almacenamiento.

``` dart
AlertTab(
  state: "notifications_tab",
  alertEnabled: true,
  icon: Icon(Icons.notifications),
  alertColor: Colors.red,
)
```

### Parametros

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `state` | `String` | requerido | Nombre del estado para seguimiento |
| `alertEnabled` | `bool?` | null | Si se muestra la insignia |
| `rememberAlert` | `bool?` | `true` | Persistir el estado de la insignia en el almacenamiento |
| `icon` | `Widget?` | null | Icono de la pestana |
| `backgroundColor` | `Color?` | null | Fondo de la pestana |
| `textColor` | `Color?` | null | Color del texto de la insignia |
| `alertColor` | `Color?` | null | Color de fondo de la insignia |
| `smallSize` | `double?` | null | Tamano de insignia pequeno |
| `largeSize` | `double?` | null | Tamano de insignia grande |
| `textStyle` | `TextStyle?` | null | Estilo de texto de la insignia |
| `padding` | `EdgeInsetsGeometry?` | null | Relleno de la insignia |
| `alignment` | `Alignment?` | null | Alineacion de la insignia |
| `offset` | `Offset?` | null | Desplazamiento de la insignia |
| `isLabelVisible` | `bool?` | `true` | Mostrar etiqueta de la insignia |

### Constructor de fabrica

Crear desde un `NavigationTab`:

``` dart
AlertTab.fromNavigationTab(
  myNavigationTab,
  index: 0,
  icon: Icon(Icons.home),
  stateName: "home_alert",
)
```

<div id="examples"></div>

## Ejemplos

### Alerta de exito despues de guardar

``` dart
void saveItem() async {
  try {
    await api<ItemApiService>((request) => request.saveItem(item));
    showToastSuccess(description: "Item saved successfully");
  } catch (e) {
    showToastDanger(description: "Could not save item. Please try again.");
  }
}
```

### Toast interactivo con accion

``` dart
showToastNotification(
  context,
  id: "info",
  title: "New Message",
  description: "You have a new message from Anthony",
  action: () {
    routeTo(ChatPage.path, data: {"userId": "123"});
  },
  duration: Duration(seconds: 8),
);
```

### Advertencia posicionada en la parte inferior

``` dart
showToastNotification(
  context,
  id: "warning",
  title: "No Internet",
  description: "You appear to be offline. Some features may not work.",
  position: ToastNotificationPosition.bottom,
  duration: Duration(seconds: 10),
);
```
