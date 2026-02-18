# Button

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Tipos de boton disponibles](#button-types "Tipos de boton disponibles")
    - [Primary](#primary "Primary")
    - [Secondary](#secondary "Secondary")
    - [Outlined](#outlined "Outlined")
    - [Solo texto](#text-only "Solo texto")
    - [Icono](#icon "Icono")
    - [Gradiente](#gradient "Gradiente")
    - [Redondeado](#rounded "Redondeado")
    - [Transparencia](#transparency "Transparencia")
- [Estado de carga asincrono](#async-loading "Estado de carga asincrono")
- [Estilos de animacion](#animation-styles "Estilos de animacion")
    - [Clickable](#anim-clickable "Clickable")
    - [Bounce](#anim-bounce "Bounce")
    - [Pulse](#anim-pulse "Pulse")
    - [Squeeze](#anim-squeeze "Squeeze")
    - [Jelly](#anim-jelly "Jelly")
    - [Shine](#anim-shine "Shine")
    - [Ripple](#anim-ripple "Ripple")
    - [Morph](#anim-morph "Morph")
    - [Shake](#anim-shake "Shake")
- [Estilos de splash](#splash-styles "Estilos de splash")
- [Estilos de carga](#loading-styles "Estilos de carga")
- [Envio de formularios](#form-submission "Envio de formularios")
- [Personalizar botones](#customizing-buttons "Personalizar botones")
- [Referencia de parametros](#parameters "Referencia de parametros")


<div id="introduction"></div>

## Introduccion

{{ config('app.name') }} proporciona una clase `Button` con ocho estilos de boton preconstruidos listos para usar. Cada boton incluye soporte integrado para:

- **Estados de carga asincronos** -- devuelve un `Future` desde `onPressed` y el boton muestra automaticamente un indicador de carga
- **Estilos de animacion** -- elige entre efectos clickable, bounce, pulse, squeeze, jelly, shine, ripple, morph y shake
- **Estilos de splash** -- agrega retroalimentacion tactil de ripple, highlight, glow o ink
- **Envio de formularios** -- conecta un boton directamente a una instancia de `NyFormData`

Puedes encontrar las definiciones de botones de tu aplicacion en `lib/resources/widgets/buttons/buttons.dart`. Este archivo contiene una clase `Button` con metodos estaticos para cada tipo de boton, facilitando la personalizacion de los valores por defecto de tu proyecto.

<div id="basic-usage"></div>

## Uso basico

Usa la clase `Button` en cualquier parte de tus widgets. Aqui tienes un ejemplo simple dentro de una pagina:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Column(
          children: [
            Button.primary(
              text: "Sign Up",
              onPressed: () {
                routeTo(SignUpPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.secondary(
              text: "Learn More",
              onPressed: () {
                routeTo(AboutPage.path);
              },
            ),

            SizedBox(height: 12),

            Button.outlined(
              text: "Cancel",
              onPressed: () {
                Navigator.pop(context);
              },
            ),
          ],
        ),
      ),
    ),
  );
}
```

Todos los tipos de boton siguen el mismo patron -- pasa una etiqueta `text` y un callback `onPressed`.

<div id="button-types"></div>

## Tipos de boton disponibles

Todos los botones se acceden a traves de la clase `Button` usando metodos estaticos.

<div id="primary"></div>

### Primary

Un boton relleno con sombra, usando el color primario de tu tema. Ideal para elementos de accion principal.

``` dart
Button.primary(
  text: "Sign Up",
  onPressed: () {
    // Handle press
  },
)
```

<div id="secondary"></div>

### Secondary

Un boton relleno con un color de superficie mas suave y sombra sutil. Adecuado para acciones secundarias junto a un boton primario.

``` dart
Button.secondary(
  text: "Learn More",
  onPressed: () {
    // Handle press
  },
)
```

<div id="outlined"></div>

### Outlined

Un boton transparente con un borde. Util para acciones menos prominentes o botones de cancelar.

``` dart
Button.outlined(
  text: "Cancel",
  onPressed: () {
    // Handle press
  },
)
```

Puedes personalizar los colores del borde y del texto:

``` dart
Button.outlined(
  text: "Custom Outline",
  borderColor: Colors.red,
  textColor: Colors.red,
  onPressed: () {},
)
```

<div id="text-only"></div>

### Solo texto

Un boton minimalista sin fondo ni borde. Ideal para acciones en linea o enlaces.

``` dart
Button.textOnly(
  text: "Skip",
  onPressed: () {
    // Handle press
  },
)
```

Puedes personalizar el color del texto:

``` dart
Button.textOnly(
  text: "View Details",
  textColor: Colors.blue,
  onPressed: () {},
)
```

<div id="icon"></div>

### Icono

Un boton relleno que muestra un icono junto al texto. El icono aparece antes del texto por defecto.

``` dart
Button.icon(
  text: "Add to Cart",
  icon: Icon(Icons.shopping_cart),
  onPressed: () {
    // Handle press
  },
)
```

Puedes personalizar el color de fondo:

``` dart
Button.icon(
  text: "Download",
  icon: Icon(Icons.download),
  color: Colors.green,
  onPressed: () {},
)
```

<div id="gradient"></div>

### Gradiente

Un boton con un fondo de gradiente lineal. Usa los colores primario y terciario de tu tema por defecto.

``` dart
Button.gradient(
  text: "Get Started",
  onPressed: () {
    // Handle press
  },
)
```

Puedes proporcionar colores de gradiente personalizados:

``` dart
Button.gradient(
  text: "Premium",
  gradientColors: [Colors.purple, Colors.pink],
  onPressed: () {},
)
```

<div id="rounded"></div>

### Redondeado

Un boton en forma de pastilla con esquinas completamente redondeadas. El radio del borde por defecto es la mitad de la altura del boton.

``` dart
Button.rounded(
  text: "Continue",
  onPressed: () {
    // Handle press
  },
)
```

Puedes personalizar el color de fondo y el radio del borde:

``` dart
Button.rounded(
  text: "Apply",
  backgroundColor: Colors.teal,
  borderRadius: BorderRadius.circular(20),
  onPressed: () {},
)
```

<div id="transparency"></div>

### Transparencia

Un boton estilo cristal esmerilado con efecto de desenfoque de fondo. Funciona bien cuando se coloca sobre imagenes o fondos de color.

``` dart
Button.transparency(
  text: "Explore",
  onPressed: () {
    // Handle press
  },
)
```

Puedes personalizar el color del texto:

``` dart
Button.transparency(
  text: "View More",
  color: Colors.white,
  onPressed: () {},
)
```

<div id="async-loading"></div>

## Estado de carga asincrono

Una de las caracteristicas mas potentes de los botones de {{ config('app.name') }} es la **gestion automatica del estado de carga**. Cuando tu callback `onPressed` devuelve un `Future`, el boton mostrara automaticamente un indicador de carga y deshabilitara la interaccion hasta que la operacion se complete.

``` dart
Button.primary(
  text: "Submit",
  onPressed: () async {
    await sleep(3); // Simulates a 3 second async task
  },
)
```

Mientras la operacion asincrona esta en ejecucion, el boton mostrara un efecto de carga skeleton (por defecto). Una vez que el `Future` se completa, el boton regresa a su estado normal.

Esto funciona con cualquier operacion asincrona -- llamadas a API, escrituras en base de datos, subidas de archivos, o cualquier cosa que devuelva un `Future`:

``` dart
Button.primary(
  text: "Save Profile",
  onPressed: () async {
    await api<ApiService>((request) =>
      request.updateProfile(name: "John", email: "john@example.com")
    );
    showToastSuccess(description: "Profile saved!");
  },
)
```

``` dart
Button.secondary(
  text: "Sync Data",
  onPressed: () async {
    await fetchAndStoreData();
    await clearOldCache();
  },
)
```

No necesitas gestionar variables de estado `isLoading`, llamar a `setState`, ni envolver nada en un `StatefulWidget` -- {{ config('app.name') }} lo maneja todo por ti.

### Como funciona

Cuando el boton detecta que `onPressed` devuelve un `Future`, usa el mecanismo `lockRelease` para:

1. Mostrar un indicador de carga (controlado por `LoadingStyle`)
2. Deshabilitar el boton para evitar toques duplicados
3. Esperar a que el `Future` se complete
4. Restaurar el boton a su estado normal

<div id="animation-styles"></div>

## Estilos de animacion

Los botones soportan animaciones de pulsacion a traves de `ButtonAnimationStyle`. Estas animaciones proporcionan retroalimentacion visual cuando un usuario interactua con un boton. Puedes establecer el estilo de animacion al personalizar tus botones en `lib/resources/widgets/buttons/buttons.dart`.

<div id="anim-clickable"></div>

### Clickable

Un efecto de pulsacion 3D estilo Duolingo. El boton se desplaza hacia abajo al presionar y vuelve a su posicion al soltar. Ideal para acciones principales y experiencias de usuario tipo juego.

``` dart
animationStyle: ButtonAnimationStyle.clickable()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.clickable(
  translateY: 6.0,        // How far the button moves down (default: 4.0)
  shadowOffset: 6.0,      // Shadow depth (default: 4.0)
  duration: Duration(milliseconds: 100),
  enableHapticFeedback: true,
)
```

<div id="anim-bounce"></div>

### Bounce

Escala el boton hacia abajo al presionar y vuelve con un rebote al soltar. Ideal para botones de agregar al carrito, me gusta y favoritos.

``` dart
animationStyle: ButtonAnimationStyle.bounce()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.bounce(
  scaleMin: 0.90,         // Minimum scale on press (default: 0.92)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeOutBack,
  enableHapticFeedback: true,
)
```

<div id="anim-pulse"></div>

### Pulse

Un pulso de escala continuo y sutil mientras el boton esta presionado. Ideal para acciones de pulsacion larga o para llamar la atencion.

``` dart
animationStyle: ButtonAnimationStyle.pulse()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.pulse(
  pulseScale: 1.08,       // Max scale during pulse (default: 1.05)
  duration: Duration(milliseconds: 800),
  curve: Curves.easeInOut,
)
```

<div id="anim-squeeze"></div>

### Squeeze

Comprime el boton horizontalmente y lo expande verticalmente al presionar. Ideal para interfaces de usuario divertidas e interactivas.

``` dart
animationStyle: ButtonAnimationStyle.squeeze()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.squeeze(
  squeezeX: 0.93,         // Horizontal scale (default: 0.95)
  squeezeY: 1.07,         // Vertical scale (default: 1.05)
  duration: Duration(milliseconds: 120),
  enableHapticFeedback: true,
)
```

<div id="anim-jelly"></div>

### Jelly

Un efecto de deformacion elastica con bamboleo. Ideal para aplicaciones divertidas, casuales o de entretenimiento.

``` dart
animationStyle: ButtonAnimationStyle.jelly()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.jelly(
  jellyStrength: 0.2,     // Wobble intensity (default: 0.15)
  duration: Duration(milliseconds: 300),
  curve: Curves.elasticOut,
  enableHapticFeedback: true,
)
```

<div id="anim-shine"></div>

### Shine

Un destello brillante que recorre el boton al presionar. Ideal para funciones premium o CTAs a los que quieras llamar la atencion.

``` dart
animationStyle: ButtonAnimationStyle.shine()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.shine(
  shineColor: Colors.white,  // Color of the shine streak (default: white)
  shineWidth: 0.4,           // Width of the shine band (default: 0.3)
  duration: Duration(milliseconds: 600),
)
```

<div id="anim-ripple"></div>

### Ripple

Un efecto de onda mejorado que se expande desde el punto de contacto. Ideal para enfasis en Material Design.

``` dart
animationStyle: ButtonAnimationStyle.ripple()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.ripple(
  rippleScale: 2.5,       // How far the ripple expands (default: 2.0)
  duration: Duration(milliseconds: 400),
  curve: Curves.easeOut,
  enableHapticFeedback: true,
)
```

<div id="anim-morph"></div>

### Morph

El radio del borde del boton aumenta al presionar, creando un efecto de cambio de forma. Ideal para retroalimentacion sutil y elegante.

``` dart
animationStyle: ButtonAnimationStyle.morph()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.morph(
  morphRadius: 30.0,      // Target border radius on press (default: 24.0)
  duration: Duration(milliseconds: 150),
  curve: Curves.easeInOut,
)
```

<div id="anim-shake"></div>

### Shake

Una animacion de sacudida horizontal. Ideal para estados de error o acciones invalidas -- sacude el boton para indicar que algo salio mal.

``` dart
animationStyle: ButtonAnimationStyle.shake()
```

Ajusta el efecto:

``` dart
ButtonAnimationStyle.shake(
  shakeOffset: 10.0,      // Horizontal displacement (default: 8.0)
  shakeCount: 4,          // Number of shakes (default: 3)
  duration: Duration(milliseconds: 400),
  enableHapticFeedback: true,
)
```

### Deshabilitar animaciones

Para usar un boton sin animacion:

``` dart
animationStyle: ButtonAnimationStyle.none()
```

### Cambiar la animacion por defecto

Para cambiar la animacion por defecto de un tipo de boton, modifica tu archivo `lib/resources/widgets/buttons/buttons.dart`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      animationStyle: ButtonAnimationStyle.bounce(), // Change the default
    );
  }
}
```

<div id="splash-styles"></div>

## Estilos de splash

Los efectos de splash proporcionan retroalimentacion visual al tocar los botones. Configuralos a traves de `ButtonSplashStyle`. Los estilos de splash se pueden combinar con estilos de animacion para una retroalimentacion por capas.

### Estilos de splash disponibles

| Splash | Fabrica | Descripcion |
|--------|---------|-------------|
| Ripple | `ButtonSplashStyle.ripple()` | Ripple Material estandar desde el punto de contacto |
| Highlight | `ButtonSplashStyle.highlight()` | Resaltado sutil sin animacion de ripple |
| Glow | `ButtonSplashStyle.glow()` | Brillo suave que irradia desde el punto de contacto |
| Ink | `ButtonSplashStyle.ink()` | Salpicadura de tinta rapida, mas veloz y responsiva |
| None | `ButtonSplashStyle.none()` | Sin efecto de splash |
| Custom | `ButtonSplashStyle.custom()` | Control total sobre la fabrica de splash |

### Ejemplo

``` dart
class Button {
  static Widget outlined({
    required String text,
    VoidCallback? onPressed,
    ...
  }) {
    return OutlinedButton(
      text: text,
      onPressed: onPressed,
      splashStyle: ButtonSplashStyle.ripple(),
      animationStyle: ButtonAnimationStyle.clickable(),
    );
  }
}
```

Puedes personalizar los colores y la opacidad del splash:

``` dart
ButtonSplashStyle.ripple(
  splashColor: Colors.blue,
  highlightColor: Colors.blue,
  splashOpacity: 0.2,
  highlightOpacity: 0.1,
)
```

<div id="loading-styles"></div>

## Estilos de carga

El indicador de carga mostrado durante operaciones asincronas se controla mediante `LoadingStyle`. Puedes configurarlo por tipo de boton en tu archivo de botones.

### Skeletonizer (Por defecto)

Muestra un efecto de shimmer skeleton sobre el boton:

``` dart
loadingStyle: LoadingStyle.skeletonizer()
```

### Normal

Muestra un widget de carga (por defecto el cargador de la aplicacion):

``` dart
loadingStyle: LoadingStyle.normal(
  child: Text("Please wait..."),
)
```

### None

Mantiene el boton visible pero deshabilita la interaccion durante la carga:

``` dart
loadingStyle: LoadingStyle.none()
```

<div id="form-submission"></div>

## Envio de formularios

Todos los botones soportan el parametro `submitForm`, que conecta el boton a un `NyForm`. Al tocar, el boton validara el formulario y llamara a tu manejador de exito con los datos del formulario.

``` dart
Button.primary(
  text: "Submit",
  submitForm: (LoginForm(), (data) {
    // data contains the validated form fields
    print(data);
  }),
  onFailure: (error) {
    // Handle validation errors
    print(error);
  },
)
```

El parametro `submitForm` acepta un registro con dos valores:
1. Una instancia de `NyFormData` (o nombre del formulario como `String`)
2. Un callback que recibe los datos validados

Por defecto, `showToastError` es `true`, lo que muestra una notificacion toast cuando falla la validacion del formulario. Configuralo a `false` para manejar errores silenciosamente:

``` dart
Button.primary(
  text: "Login",
  submitForm: (LoginForm(), (data) async {
    await api<AuthApiService>((request) => request.login(data));
  }),
  showToastError: false,
  onFailure: (error) {
    // Custom error handling
  },
)
```

Cuando el callback `submitForm` devuelve un `Future`, el boton mostrara automaticamente un estado de carga hasta que la operacion asincrona se complete.

<div id="customizing-buttons"></div>

## Personalizar botones

Todos los valores por defecto de los botones se definen en tu proyecto en `lib/resources/widgets/buttons/buttons.dart`. Cada tipo de boton tiene una clase de widget correspondiente en `lib/resources/widgets/buttons/partials/`.

### Cambiar estilos por defecto

Para modificar la apariencia por defecto de un boton, edita la clase `Button`:

``` dart
class Button {
  static Widget primary({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return PrimaryButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.bounce(),
      splashStyle: ButtonSplashStyle.glow(),
    );
  }
}
```

### Personalizar un widget de boton

Para cambiar la apariencia visual de un tipo de boton, edita el widget correspondiente en `lib/resources/widgets/buttons/partials/`. Por ejemplo, para cambiar el radio del borde o la sombra del boton primario:

``` dart
// lib/resources/widgets/buttons/partials/primary_button_widget.dart

class PrimaryButton extends StatefulAppButton {
  ...

  @override
  Widget buildButton(BuildContext context) {
    final theme = Theme.of(context);
    final bgColor = backgroundColor ?? theme.colorScheme.primary;
    final fgColor = contentColor ?? theme.colorScheme.onPrimary;

    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: bgColor,
        borderRadius: BorderRadius.circular(8), // Change the radius
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: fgColor,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

### Crear un nuevo tipo de boton

Para agregar un nuevo tipo de boton:

1. Crea un nuevo archivo de widget en `lib/resources/widgets/buttons/partials/` extendiendo `StatefulAppButton`.
2. Implementa el metodo `buildButton`.
3. Agrega un metodo estatico en la clase `Button`.

``` dart
// lib/resources/widgets/buttons/partials/danger_button_widget.dart

class DangerButton extends StatefulAppButton {
  DangerButton({
    required super.text,
    super.onPressed,
    super.submitForm,
    super.onFailure,
    super.showToastError,
    super.loadingStyle,
    super.width,
    super.height,
    super.animationStyle,
    super.splashStyle,
  });

  @override
  Widget buildButton(BuildContext context) {
    return Container(
      width: width ?? double.infinity,
      height: height,
      decoration: BoxDecoration(
        color: Colors.red,
        borderRadius: BorderRadius.circular(14),
      ),
      child: Center(
        child: Text(
          text,
          style: TextStyle(
            color: Colors.white,
            fontSize: 16,
            fontWeight: FontWeight.w600,
          ),
        ),
      ),
    );
  }
}
```

Luego registralo en la clase `Button`:

``` dart
class Button {
  ...

  static Widget danger({
    required String text,
    VoidCallback? onPressed,
    Function(dynamic error)? onFailure,
    bool showToastError = true,
    double? width,
  }) {
    return DangerButton(
      text: text,
      onPressed: onPressed,
      submitForm: submitForm,
      onFailure: onFailure,
      showToastError: showToastError,
      loadingStyle: LoadingStyle.skeletonizer(),
      width: width,
      height: 52.0,
      animationStyle: ButtonAnimationStyle.shake(),
    );
  }
}
```

<div id="parameters"></div>

## Referencia de parametros

### Parametros comunes (todos los tipos de boton)

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `text` | `String` | requerido | Texto de la etiqueta del boton |
| `onPressed` | `VoidCallback?` | `null` | Callback cuando el boton es presionado. Devuelve un `Future` para estado de carga automatico |
| `submitForm` | `(dynamic, Function(dynamic))?` | `null` | Registro de envio de formulario (instancia del formulario, callback de exito) |
| `onFailure` | `Function(dynamic)?` | `null` | Se llama cuando la validacion del formulario falla |
| `showToastError` | `bool` | `true` | Mostrar notificacion toast en error de validacion del formulario |
| `width` | `double?` | `null` | Ancho del boton (por defecto ancho completo) |

### Parametros especificos por tipo

#### Button.outlined

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `borderColor` | `Color?` | Color de contorno del tema | Color del borde |
| `textColor` | `Color?` | Color primario del tema | Color del texto |

#### Button.textOnly

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `textColor` | `Color?` | Color primario del tema | Color del texto |

#### Button.icon

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `icon` | `Widget` | requerido | El widget de icono a mostrar |
| `color` | `Color?` | Color primario del tema | Color de fondo |

#### Button.gradient

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `gradientColors` | `List<Color>?` | Colores primario y terciario | Paradas de color del gradiente |

#### Button.rounded

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `backgroundColor` | `Color?` | Color del contenedor primario del tema | Color de fondo |
| `borderRadius` | `BorderRadius?` | Forma de pastilla (altura / 2) | Radio de las esquinas |

#### Button.transparency

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `color` | `Color?` | Adaptativo al tema | Color del texto |

### Parametros de ButtonAnimationStyle

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `duration` | `Duration` | Varia por tipo | Duracion de la animacion |
| `curve` | `Curve` | Varia por tipo | Curva de la animacion |
| `enableHapticFeedback` | `bool` | Varia por tipo | Activar retroalimentacion haptica al presionar |
| `translateY` | `double` | `4.0` | Clickable: distancia vertical de presion |
| `shadowOffset` | `double` | `4.0` | Clickable: profundidad de sombra |
| `scaleMin` | `double` | `0.92` | Bounce: escala minima al presionar |
| `pulseScale` | `double` | `1.05` | Pulse: escala maxima durante el pulso |
| `squeezeX` | `double` | `0.95` | Squeeze: compresion horizontal |
| `squeezeY` | `double` | `1.05` | Squeeze: expansion vertical |
| `jellyStrength` | `double` | `0.15` | Jelly: intensidad del bamboleo |
| `shineColor` | `Color` | `Colors.white` | Shine: color del destello |
| `shineWidth` | `double` | `0.3` | Shine: ancho de la banda de brillo |
| `rippleScale` | `double` | `2.0` | Ripple: escala de expansion |
| `morphRadius` | `double` | `24.0` | Morph: radio de borde objetivo |
| `shakeOffset` | `double` | `8.0` | Shake: desplazamiento horizontal |
| `shakeCount` | `int` | `3` | Shake: numero de oscilaciones |

### Parametros de ButtonSplashStyle

| Parametro | Tipo | Predeterminado | Descripcion |
|-----------|------|---------|-------------|
| `splashColor` | `Color?` | Color de superficie del tema | Color del efecto splash |
| `highlightColor` | `Color?` | Color de superficie del tema | Color del efecto highlight |
| `splashOpacity` | `double` | `0.12` | Opacidad del splash |
| `highlightOpacity` | `double` | `0.06` | Opacidad del highlight |
| `borderRadius` | `BorderRadius?` | `null` | Radio de recorte del splash |
