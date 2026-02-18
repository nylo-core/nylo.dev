# FutureWidget

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Uso Basico](#basic-usage "Uso Basico")
- [Personalizando o Estado de Carregamento](#customizing-loading "Personalizando o Estado de Carregamento")
    - [Estilo de Carregamento Normal](#normal-loading "Estilo de Carregamento Normal")
    - [Estilo de Carregamento Skeletonizer](#skeletonizer-loading "Estilo de Carregamento Skeletonizer")
    - [Sem Estilo de Carregamento](#no-loading "Sem Estilo de Carregamento")
- [Tratamento de Erros](#error-handling "Tratamento de Erros")


<div id="introduction"></div>

## Introducao

O **FutureWidget** e uma forma simples de renderizar `Future`'s nos seus projetos {{ config('app.name') }}. Ele envolve o `FutureBuilder` do Flutter e fornece uma API mais limpa com estados de carregamento integrados.

Enquanto seu Future esta em andamento, ele exibira um carregador. Quando o Future for concluido, os dados sao retornados pelo callback `child`.

<div id="basic-usage"></div>

## Uso Basico

Aqui esta um exemplo simples de uso do `FutureWidget`:

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

O widget lidara automaticamente com o estado de carregamento para seus usuarios ate que o Future seja concluido.

<div id="customizing-loading"></div>

## Personalizando o Estado de Carregamento

Voce pode personalizar como o estado de carregamento aparece usando o parametro `loadingStyle`.

<div id="normal-loading"></div>

### Estilo de Carregamento Normal

Use `LoadingStyle.normal()` para exibir um widget de carregamento padrao. Voce pode opcionalmente fornecer um widget filho personalizado:

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

Se nenhum filho for fornecido, o carregador padrao do app {{ config('app.name') }} sera exibido.

<div id="skeletonizer-loading"></div>

### Estilo de Carregamento Skeletonizer

Use `LoadingStyle.skeletonizer()` para exibir um efeito de carregamento esqueleto. Isso e otimo para mostrar uma interface placeholder que corresponde ao layout do seu conteudo:

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

Efeitos de esqueleto disponiveis:
- `SkeletonizerEffect.shimmer` - Efeito de brilho animado (padrao)
- `SkeletonizerEffect.pulse` - Efeito de animacao pulsante
- `SkeletonizerEffect.solid` - Efeito de cor solida

<div id="no-loading"></div>

### Sem Estilo de Carregamento

Use `LoadingStyle.none()` para ocultar completamente o indicador de carregamento:

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

## Tratamento de Erros

Voce pode tratar erros do seu Future usando o callback `onError`:

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

Se nenhum callback `onError` for fornecido e um erro ocorrer, um `SizedBox.shrink()` vazio sera exibido.

### Parametros

| Parametro | Tipo | Descricao |
|-----------|------|-----------|
| `future` | `Future<T>?` | O Future a ser aguardado |
| `child` | `Widget Function(BuildContext, T?)` | Funcao builder chamada quando o Future e concluido |
| `loadingStyle` | `LoadingStyle?` | Personalizar o indicador de carregamento |
| `onError` | `Widget Function(AsyncSnapshot)?` | Funcao builder chamada quando o Future tem um erro |
