# FutureWidget

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso basico](#basic-usage "Uso basico")
- [Personalizar el estado de carga](#customizing-loading "Personalizar el estado de carga")
    - [Estilo de carga normal](#normal-loading "Estilo de carga normal")
    - [Estilo de carga Skeletonizer](#skeletonizer-loading "Estilo de carga Skeletonizer")
    - [Sin estilo de carga](#no-loading "Sin estilo de carga")
- [Manejo de errores](#error-handling "Manejo de errores")


<div id="introduction"></div>

## Introduccion

El **FutureWidget** es una forma simple de renderizar `Future`'s en tus proyectos de {{ config('app.name') }}. Envuelve el `FutureBuilder` de Flutter y proporciona una API mas limpia con estados de carga integrados.

Cuando tu Future esta en progreso, mostrara un cargador. Una vez que el Future se completa, los datos se devuelven a traves del callback `child`.

<div id="basic-usage"></div>

## Uso basico

Aqui tienes un ejemplo simple de uso de `FutureWidget`:

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

El widget manejara automaticamente el estado de carga para tus usuarios hasta que el Future se complete.

<div id="customizing-loading"></div>

## Personalizar el estado de carga

Puedes personalizar como aparece el estado de carga usando el parametro `loadingStyle`.

<div id="normal-loading"></div>

### Estilo de carga normal

Usa `LoadingStyle.normal()` para mostrar un widget de carga estandar. Opcionalmente puedes proporcionar un widget hijo personalizado:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

Si no se proporciona un hijo, se mostrara el cargador predeterminado de la aplicacion {{ config('app.name') }}.

<div id="skeletonizer-loading"></div>

### Estilo de carga Skeletonizer

Usa `LoadingStyle.skeletonizer()` para mostrar un efecto de carga skeleton. Esto es ideal para mostrar una interfaz de marcador de posicion que coincide con el diseno de tu contenido:

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Efectos skeleton disponibles:
- `SkeletonizerEffect.shimmer` - Efecto shimmer animado (por defecto)
- `SkeletonizerEffect.pulse` - Efecto de animacion pulsante
- `SkeletonizerEffect.solid` - Efecto de color solido

<div id="no-loading"></div>

### Sin estilo de carga

Usa `LoadingStyle.none()` para ocultar el indicador de carga completamente:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Manejo de errores

Puedes manejar errores de tu Future usando el callback `onError`:

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

Si no se proporciona un callback `onError` y ocurre un error, se mostrara un `SizedBox.shrink()` vacio.

### Parametros

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `future` | `Future<T>?` | El Future a esperar |
| `child` | `Widget Function(BuildContext, T?)` | Funcion constructora llamada cuando el Future se completa |
| `loadingStyle` | `LoadingStyle?` | Personalizar el indicador de carga |
| `onError` | `Widget Function(AsyncSnapshot)?` | Funcion constructora llamada cuando el Future tiene un error |
