# Modals

---

<a name="section-1"></a>
- [Introducción](#introduction "Introducción")
- [Crear un modal](#creating-a-modal "Crear un modal")
- [Uso básico](#basic-usage "Uso básico")
- [Crear un modal](#creating-a-modal "Crear un modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parámetros](#parameters "Parámetros")
  - [Acciones](#actions "Acciones")
  - [Encabezado](#header "Encabezado")
  - [Botón de cerrar](#close-button "Botón de cerrar")
  - [Decoración personalizada](#custom-decoration "Decoración personalizada")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Ejemplos](#examples "Ejemplos prácticos")

<div id="introduction"></div>

## Introducción

{{ config('app.name') }} proporciona un sistema de modales basado en **bottom sheet modals**.

La clase `BottomSheetModal` te ofrece una API flexible para mostrar superposiciones de contenido con acciones, encabezados, botones de cerrar y estilos personalizados.

Los modales son útiles para:
- Diálogos de confirmación (ej., cerrar sesión, eliminar)
- Formularios de entrada rápida
- Hojas de acciones con múltiples opciones
- Superposiciones informativas

<div id="creating-a-modal"></div>

## Crear un modal

Puedes crear un nuevo modal usando Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

Esto genera dos cosas:

1. **Un widget de contenido del modal** en `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **Un método estático** agregado a tu clase `BottomSheetModal` en `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

Luego puedes mostrar el modal desde cualquier lugar:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Si ya existe un modal con el mismo nombre, usa el flag `--force` para sobrescribirlo:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Uso básico

Muestra un modal usando `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Crear un modal

El patrón recomendado es crear una clase `BottomSheetModal` con métodos estáticos para cada tipo de modal. El boilerplate proporciona esta estructura:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Llámalo desde cualquier lugar:

``` dart
BottomSheetModal.showLogout(context);

// Con callbacks personalizados
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Lógica personalizada de cierre de sesión
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` es el método principal para mostrar modales.

<div id="parameters"></div>

### Parámetros

| Parámetro | Tipo | Predeterminado | Descripción |
|-----------|------|----------------|-------------|
| `context` | `BuildContext` | requerido | Build context para el modal |
| `child` | `Widget` | requerido | Widget de contenido principal |
| `actionsRow` | `List<Widget>` | `[]` | Widgets de acción en fila horizontal |
| `actionsColumn` | `List<Widget>` | `[]` | Widgets de acción en disposición vertical |
| `height` | `double?` | null | Altura fija del modal |
| `header` | `Widget?` | null | Widget de encabezado en la parte superior |
| `useSafeArea` | `bool` | `true` | Envolver contenido en SafeArea |
| `isScrollControlled` | `bool` | `false` | Permitir que el modal sea desplazable |
| `showCloseButton` | `bool` | `false` | Mostrar un botón X de cerrar |
| `headerPadding` | `EdgeInsets?` | null | Relleno cuando hay encabezado |
| `backgroundColor` | `Color?` | null | Color de fondo del modal |
| `showHandle` | `bool` | `true` | Mostrar el indicador de arrastre en la parte superior |
| `closeButtonColor` | `Color?` | null | Color de fondo del botón de cerrar |
| `closeButtonIconColor` | `Color?` | null | Color del ícono del botón de cerrar |
| `modalDecoration` | `BoxDecoration?` | null | Decoración personalizada del contenedor del modal |
| `handleColor` | `Color?` | null | Color del indicador de arrastre |

<div id="actions"></div>

### Acciones

Las acciones son botones que se muestran en la parte inferior del modal.

**Las acciones en fila** se colocan una al lado de la otra, cada una ocupando espacio igual:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Las acciones en columna** se apilan verticalmente:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Encabezado

Agrega un encabezado que se ubica sobre el contenido principal:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Botón de cerrar

Muestra un botón de cerrar en la esquina superior derecha:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Decoración personalizada

Personaliza la apariencia del contenedor del modal:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` configura la apariencia de los modales de hoja inferior usados por selectores de formularios y otros componentes:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Propiedad | Tipo | Descripción |
|-----------|------|-------------|
| `backgroundColor` | `NyColor?` | Color de fondo del modal |
| `barrierColor` | `NyColor?` | Color de la superposición detrás del modal |
| `useRootNavigator` | `bool` | Usar navegador raíz (predeterminado: `false`) |
| `routeSettings` | `RouteSettings?` | Configuración de ruta para el modal |
| `titleStyle` | `TextStyle?` | Estilo para el texto del título |
| `itemStyle` | `TextStyle?` | Estilo para el texto de elementos de lista |
| `clearButtonStyle` | `TextStyle?` | Estilo para el texto del botón de limpiar |

<div id="examples"></div>

## Ejemplos

### Modal de confirmación

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Uso
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // eliminar el elemento
}
```

### Modal con contenido desplazable

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Hoja de acciones

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
