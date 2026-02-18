# Pullable

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- [Uso Básico](#basic-usage "Uso Básico")
- [Construtores](#constructors "Construtores")
- [PullableConfig](#pullable-config "PullableConfig")
- [Estilos de Cabeçalho](#header-styles "Estilos de Cabeçalho")
- [Puxar para Cima para Carregar Mais](#pull-up "Puxar para Cima para Carregar Mais")
- [Cabeçalhos e Rodapés Personalizados](#custom-headers "Cabeçalhos e Rodapés Personalizados")
- [Controller](#controller "Controller")
- [Método de Extensão](#extension-method "Método de Extensão")
- [Integração com CollectionView](#collection-view "Integração com CollectionView")
- [Exemplos](#examples "Exemplos Práticos")

<div id="introduction"></div>

## Introdução

O widget **Pullable** adiciona funcionalidade de puxar para atualizar e carregar mais a qualquer conteúdo rolável. Ele envolve seu widget filho com comportamento de atualização e paginação orientado por gestos, suportando múltiplos estilos de animação de cabeçalho.

Construído sobre o pacote `pull_to_refresh_flutter3`, o Pullable fornece uma API limpa com construtores nomeados para configurações comuns.

``` dart
Pullable(
  onRefresh: () async {
    // Fetch fresh data
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## Uso Básico

Envolva qualquer widget rolável com `Pullable`:

``` dart
Pullable(
  onRefresh: () async {
    await loadLatestPosts();
  },
  child: ListView.builder(
    itemCount: posts.length,
    itemBuilder: (context, index) => PostCard(post: posts[index]),
  ),
)
```

Quando o usuário puxa para baixo na lista, o callback `onRefresh` é disparado. O indicador de atualização é concluído automaticamente quando o callback termina.

<div id="constructors"></div>

## Construtores

O `Pullable` fornece construtores nomeados para configurações comuns:

| Construtor | Estilo do Cabeçalho | Descrição |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Construtor padrão |
| `Pullable.classicHeader()` | Classic | Estilo clássico de puxar para atualizar |
| `Pullable.waterDropHeader()` | Water Drop | Animação de gota d'água |
| `Pullable.materialClassicHeader()` | Material Classic | Estilo clássico Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Estilo Material de gota d'água |
| `Pullable.bezierHeader()` | Bezier | Animação de curva Bezier |
| `Pullable.noBounce()` | Configurável | Bounce reduzido com `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget personalizado | Use seus próprios widgets de cabeçalho/rodapé |
| `Pullable.builder()` | Configurável | Controle total via `PullableConfig` |

### Exemplos

``` dart
// Classic header
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material header
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// No bounce effect
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// Custom header widget
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

Para controle detalhado, use `PullableConfig` com o construtor `Pullable.builder()`:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    headerType: PullableHeaderType.materialClassic,
    onRefresh: () async => await refreshData(),
    onLoading: () async => await loadMoreData(),
    refreshCompleteDelay: Duration(milliseconds: 500),
    loadCompleteDelay: Duration(milliseconds: 300),
    physics: BouncingScrollPhysics(),
  ),
  child: myListView,
)
```

### Todas as Opções de Configuração

| Propriedade | Tipo | Padrão | Descrição |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Habilitar puxar para baixo para atualizar |
| `enablePullUp` | `bool` | `false` | Habilitar puxar para cima para carregar mais |
| `physics` | `ScrollPhysics?` | null | Física de rolagem personalizada |
| `onRefresh` | `Future<void> Function()?` | null | Callback de atualização |
| `onLoading` | `Future<void> Function()?` | null | Callback de carregar mais |
| `headerType` | `PullableHeaderType` | `waterDrop` | Estilo de animação do cabeçalho |
| `customHeader` | `Widget?` | null | Widget de cabeçalho personalizado |
| `customFooter` | `Widget?` | null | Widget de rodapé personalizado |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Atraso antes de concluir a atualização |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Atraso antes de concluir o carregamento |
| `enableOverScroll` | `bool` | `true` | Permitir efeito de over-scroll |
| `cacheExtent` | `double?` | null | Extensão do cache de rolagem |
| `semanticChildCount` | `int?` | null | Contagem semântica de filhos |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Como os gestos de arrastar começam |

<div id="header-styles"></div>

## Estilos de Cabeçalho

Escolha entre cinco animações de cabeçalho integradas:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Defina o estilo via construtor ou configuração:

``` dart
// Via named constructor
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Via config
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## Puxar para Cima para Carregar Mais

Habilite a paginação com carregamento ao puxar para cima:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // Reset to page 1
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // Load next page
      page++;
      List<Item> more = await fetchItems(page: page);
      items.addAll(more);
      setState(() {});
    },
  ),
  child: ListView.builder(
    itemCount: items.length,
    itemBuilder: (context, index) => ItemTile(item: items[index]),
  ),
)
```

<div id="custom-headers"></div>

## Cabeçalhos e Rodapés Personalizados

Forneça seus próprios widgets de cabeçalho e rodapé:

``` dart
Pullable.custom(
  customHeader: Container(
    height: 60,
    alignment: Alignment.center,
    child: CircularProgressIndicator(),
  ),
  customFooter: Container(
    height: 40,
    alignment: Alignment.center,
    child: Text("Loading more..."),
  ),
  enablePullUp: true,
  onRefresh: () async => await refreshData(),
  onLoading: () async => await loadMore(),
  child: myListView,
)
```

<div id="controller"></div>

## Controller

Use um `RefreshController` para controle programático:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Trigger refresh programmatically
_controller.triggerRefresh();

// Trigger loading programmatically
_controller.triggerLoading();

// Check state
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### Métodos de Extensão no RefreshController

| Método/Getter | Tipo de Retorno | Descrição |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Disparar manualmente uma atualização |
| `triggerLoading()` | `void` | Disparar manualmente o carregar mais |
| `isRefreshing` | `bool` | Se a atualização está ativa |
| `isLoading` | `bool` | Se o carregamento está ativo |

<div id="extension-method"></div>

## Método de Extensão

Qualquer widget pode ser envolvido com puxar para atualizar usando a extensão `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Com configuração personalizada:

``` dart
myListView.pullable(
  onRefresh: () async => await refreshData(),
  pullableConfig: PullableConfig(
    headerType: PullableHeaderType.classic,
    enablePullUp: true,
    onLoading: () async => await loadMore(),
  ),
)
```

<div id="collection-view"></div>

## Integração com CollectionView

O `CollectionView` fornece variantes pullable com paginação integrada:

### CollectionView.pullable

``` dart
CollectionView<User>.pullable(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  onRefresh: () => print('Refreshed!'),
  headerStyle: 'WaterDropHeader',
)
```

### CollectionView.pullableSeparated

``` dart
CollectionView<User>.pullableSeparated(
  data: (iteration) async => api.getUsers(page: iteration),
  builder: (context, item) => UserTile(user: item.data),
  separatorBuilder: (context, index) => Divider(),
)
```

### CollectionView.pullableGrid

``` dart
CollectionView<Product>.pullableGrid(
  data: (iteration) async => api.getProducts(page: iteration),
  builder: (context, item) => ProductCard(product: item.data),
  crossAxisCount: 2,
  mainAxisSpacing: 8,
  crossAxisSpacing: 8,
)
```

### Parâmetros Específicos do Pullable

| Parâmetro | Tipo | Descrição |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback de dados paginados (iteration começa em 1) |
| `onRefresh` | `Function()?` | Callback após a atualização |
| `beforeRefresh` | `Function()?` | Hook antes do início da atualização |
| `afterRefresh` | `Function(dynamic)?` | Hook após a atualização com dados |
| `headerStyle` | `String?` | Nome do tipo de cabeçalho (ex.: `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Indicador de carregamento personalizado para o rodapé |

<div id="examples"></div>

## Exemplos

### Lista Paginada com Atualização

``` dart
class _PostListState extends NyState<PostListPage> {
  List<Post> posts = [];
  int page = 1;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Pullable.builder(
        config: PullableConfig(
          enablePullDown: true,
          enablePullUp: true,
          headerType: PullableHeaderType.materialClassic,
          onRefresh: () async {
            page = 1;
            posts = await api<PostApiService>((request) => request.getPosts(page: page));
            setState(() {});
          },
          onLoading: () async {
            page++;
            List<Post> more = await api<PostApiService>((request) => request.getPosts(page: page));
            posts.addAll(more);
            setState(() {});
          },
        ),
        child: ListView.builder(
          itemCount: posts.length,
          itemBuilder: (context, index) => PostCard(post: posts[index]),
        ),
      ),
    );
  }
}
```

### Atualização Simples com Extensão

``` dart
ListView(
  children: notifications
    .map((n) => ListTile(
      title: Text(n.title),
      subtitle: Text(n.body),
    ))
    .toList(),
).pullable(
  onRefresh: () async {
    notifications = await fetchNotifications();
    setState(() {});
  },
)
```
