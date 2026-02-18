# FutureWidget

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Настройка состояния загрузки](#customizing-loading "Настройка состояния загрузки")
    - [Обычный стиль загрузки](#normal-loading "Обычный стиль загрузки")
    - [Стиль загрузки Skeletonizer](#skeletonizer-loading "Стиль загрузки Skeletonizer")
    - [Без загрузки](#no-loading "Без загрузки")
- [Обработка ошибок](#error-handling "Обработка ошибок")


<div id="introduction"></div>

## Введение

**FutureWidget** -- это простой способ отображения `Future` в ваших проектах {{ config('app.name') }}. Он оборачивает `FutureBuilder` из Flutter и предоставляет более чистый API со встроенными состояниями загрузки.

Пока Future выполняется, виджет отображает загрузчик. После завершения Future данные возвращаются через колбэк `child`.

<div id="basic-usage"></div>

## Базовое использование

Вот простой пример использования `FutureWidget`:

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

Виджет автоматически обрабатывает состояние загрузки для ваших пользователей до завершения Future.

<div id="customizing-loading"></div>

## Настройка состояния загрузки

Вы можете настроить внешний вид состояния загрузки с помощью параметра `loadingStyle`.

<div id="normal-loading"></div>

### Обычный стиль загрузки

Используйте `LoadingStyle.normal()` для отображения стандартного виджета загрузки. При желании можно указать пользовательский дочерний виджет:

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

Если дочерний виджет не указан, будет отображён стандартный загрузчик приложения {{ config('app.name') }}.

<div id="skeletonizer-loading"></div>

### Стиль загрузки Skeletonizer

Используйте `LoadingStyle.skeletonizer()` для отображения эффекта скелетонной загрузки. Это отлично подходит для показа заполнителя интерфейса, соответствующего макету вашего содержимого:

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

Доступные эффекты скелетона:
- `SkeletonizerEffect.shimmer` - Анимированный эффект мерцания (по умолчанию)
- `SkeletonizerEffect.pulse` - Эффект пульсирующей анимации
- `SkeletonizerEffect.solid` - Эффект сплошного цвета

<div id="no-loading"></div>

### Без загрузки

Используйте `LoadingStyle.none()`, чтобы полностью скрыть индикатор загрузки:

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

## Обработка ошибок

Вы можете обрабатывать ошибки Future с помощью колбэка `onError`:

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

Если колбэк `onError` не указан и произошла ошибка, будет отображён пустой `SizedBox.shrink()`.

### Параметры

| Параметр | Тип | Описание |
|----------|-----|----------|
| `future` | `Future<T>?` | Future для ожидания |
| `child` | `Widget Function(BuildContext, T?)` | Функция-построитель, вызываемая при завершении Future |
| `loadingStyle` | `LoadingStyle?` | Настройка индикатора загрузки |
| `onError` | `Widget Function(AsyncSnapshot)?` | Функция-построитель, вызываемая при ошибке Future |
