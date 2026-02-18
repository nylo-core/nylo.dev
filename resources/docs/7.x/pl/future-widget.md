# FutureWidget

---

<a name="section-1"></a>
- [Wprowadzenie](#introduction "Wprowadzenie")
- [Podstawowe użycie](#basic-usage "Podstawowe użycie")
- [Personalizacja stanu ładowania](#customizing-loading "Personalizacja stanu ładowania")
    - [Normalny styl ładowania](#normal-loading "Normalny styl ładowania")
    - [Styl ładowania Skeletonizer](#skeletonizer-loading "Styl ładowania Skeletonizer")
    - [Brak stylu ładowania](#no-loading "Brak stylu ładowania")
- [Obsługa błędów](#error-handling "Obsługa błędów")


<div id="introduction"></div>

## Wprowadzenie

**FutureWidget** to prosty sposób renderowania obiektów `Future` w projektach {{ config('app.name') }}. Opakowuje Flutterowy `FutureBuilder` i zapewnia czystsze API z wbudowanymi stanami ładowania.

Gdy Future jest w trakcie przetwarzania, wyświetlany jest wskaźnik ładowania. Po zakończeniu Future dane są zwracane przez callback `child`.

<div id="basic-usage"></div>

## Podstawowe użycie

Oto prosty przykład użycia `FutureWidget`:

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

Widget automatycznie obsłuży stan ładowania dla użytkowników do momentu zakończenia Future.

<div id="customizing-loading"></div>

## Personalizacja stanu ładowania

Możesz dostosować wygląd stanu ładowania za pomocą parametru `loadingStyle`.

<div id="normal-loading"></div>

### Normalny styl ładowania

Użyj `LoadingStyle.normal()`, aby wyświetlić standardowy widget ładowania. Opcjonalnie możesz podać niestandardowy widget potomny:

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

Jeśli nie podano widgetu potomnego, wyświetlony zostanie domyślny wskaźnik ładowania aplikacji {{ config('app.name') }}.

<div id="skeletonizer-loading"></div>

### Styl ładowania Skeletonizer

Użyj `LoadingStyle.skeletonizer()`, aby wyświetlić efekt ładowania szkieletowego. Jest to idealne do pokazywania zastępczego interfejsu, który odpowiada układowi treści:

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

Dostępne efekty szkieletowe:
- `SkeletonizerEffect.shimmer` - Animowany efekt połysku (domyślny)
- `SkeletonizerEffect.pulse` - Efekt pulsującej animacji
- `SkeletonizerEffect.solid` - Efekt jednolitego koloru

<div id="no-loading"></div>

### Brak stylu ładowania

Użyj `LoadingStyle.none()`, aby całkowicie ukryć wskaźnik ładowania:

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

## Obsługa błędów

Możesz obsługiwać błędy z Future za pomocą callbacku `onError`:

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

Jeśli callback `onError` nie jest podany i wystąpi błąd, wyświetlony zostanie pusty `SizedBox.shrink()`.

### Parametry

| Parametr | Typ | Opis |
|----------|-----|------|
| `future` | `Future<T>?` | Future do oczekiwania |
| `child` | `Widget Function(BuildContext, T?)` | Funkcja buildera wywoływana po zakończeniu Future |
| `loadingStyle` | `LoadingStyle?` | Personalizacja wskaźnika ładowania |
| `onError` | `Widget Function(AsyncSnapshot)?` | Funkcja buildera wywoływana, gdy Future ma błąd |
