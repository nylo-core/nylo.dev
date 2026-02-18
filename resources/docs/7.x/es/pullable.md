# Pullable

---

<a name="section-1"></a>
- [Introduccion](#introduction "Introduccion")
- [Uso Basico](#basic-usage "Uso Basico")
- [Constructores](#constructors "Constructores")
- [PullableConfig](#pullable-config "PullableConfig")
- [Estilos de Encabezado](#header-styles "Estilos de Encabezado")
- [Deslizar Hacia Arriba para Cargar Mas](#pull-up "Deslizar Hacia Arriba para Cargar Mas")
- [Encabezados y Pies de Pagina Personalizados](#custom-headers "Encabezados y Pies de Pagina Personalizados")
- [Controlador](#controller "Controlador")
- [Metodo de Extension](#extension-method "Metodo de Extension")
- [Integracion con CollectionView](#collection-view "Integracion con CollectionView")
- [Ejemplos](#examples "Ejemplos Practicos")

<div id="introduction"></div>

## Introduccion

El widget **Pullable** agrega funcionalidad de pull-to-refresh y cargar mas a cualquier contenido desplazable. Envuelve tu widget hijo con comportamiento de actualizacion y paginacion basado en gestos, soportando multiples estilos de animacion de encabezado.

Construido sobre el paquete `pull_to_refresh_flutter3`, Pullable proporciona una API limpia con constructores con nombre para configuraciones comunes.

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

## Uso Basico

Envuelve cualquier widget desplazable con `Pullable`:

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

Cuando el usuario desliza hacia abajo en la lista, se ejecuta el callback `onRefresh`. El indicador de actualizacion se completa automaticamente cuando el callback termina.

<div id="constructors"></div>

## Constructores

`Pullable` proporciona constructores con nombre para configuraciones comunes:

| Constructor | Estilo de Encabezado | Descripcion |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Constructor por defecto |
| `Pullable.classicHeader()` | Classic | Estilo clasico de pull-to-refresh |
| `Pullable.waterDropHeader()` | Water Drop | Animacion de gota de agua |
| `Pullable.materialClassicHeader()` | Material Classic | Estilo clasico de Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Estilo Material de gota de agua |
| `Pullable.bezierHeader()` | Bezier | Animacion de curva Bezier |
| `Pullable.noBounce()` | Configurable | Rebote reducido con `ClampingScrollPhysics` |
| `Pullable.custom()` | Widget personalizado | Usa tus propios widgets de encabezado/pie de pagina |
| `Pullable.builder()` | Configurable | Control total con `PullableConfig` |

### Ejemplos

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

Para un control mas detallado, usa `PullableConfig` con el constructor `Pullable.builder()`:

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

### Todas las Opciones de Configuracion

| Propiedad | Tipo | Por Defecto | Descripcion |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Habilitar pull-down-to-refresh |
| `enablePullUp` | `bool` | `false` | Habilitar pull-up-to-load-more |
| `physics` | `ScrollPhysics?` | null | Fisica de desplazamiento personalizada |
| `onRefresh` | `Future<void> Function()?` | null | Callback de actualizacion |
| `onLoading` | `Future<void> Function()?` | null | Callback de cargar mas |
| `headerType` | `PullableHeaderType` | `waterDrop` | Estilo de animacion del encabezado |
| `customHeader` | `Widget?` | null | Widget de encabezado personalizado |
| `customFooter` | `Widget?` | null | Widget de pie de pagina personalizado |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Retraso antes de completar la actualizacion |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Retraso antes de completar la carga |
| `enableOverScroll` | `bool` | `true` | Permitir efecto de over-scroll |
| `cacheExtent` | `double?` | null | Extension de cache de desplazamiento |
| `semanticChildCount` | `int?` | null | Conteo semantico de hijos |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Como comienzan los gestos de arrastre |

<div id="header-styles"></div>

## Estilos de Encabezado

Elige entre cinco animaciones de encabezado integradas:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Establece el estilo a traves del constructor o la configuracion:

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

## Deslizar Hacia Arriba para Cargar Mas

Habilita la paginacion con carga al deslizar hacia arriba:

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

## Encabezados y Pies de Pagina Personalizados

Proporciona tus propios widgets de encabezado y pie de pagina:

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

## Controlador

Usa un `RefreshController` para control programatico:

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

### Metodos de Extension en RefreshController

| Metodo/Getter | Tipo de Retorno | Descripcion |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Activar manualmente una actualizacion |
| `triggerLoading()` | `void` | Activar manualmente cargar mas |
| `isRefreshing` | `bool` | Si la actualizacion esta activa |
| `isLoading` | `bool` | Si la carga esta activa |

<div id="extension-method"></div>

## Metodo de Extension

Cualquier widget puede ser envuelto con pull-to-refresh usando la extension `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

Con configuracion personalizada:

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

## Integracion con CollectionView

`CollectionView` proporciona variantes pullable con paginacion integrada:

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

### Parametros Especificos de Pullable

| Parametro | Tipo | Descripcion |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Callback de datos paginados (la iteracion comienza en 1) |
| `onRefresh` | `Function()?` | Callback despues de la actualizacion |
| `beforeRefresh` | `Function()?` | Hook antes de que comience la actualizacion |
| `afterRefresh` | `Function(dynamic)?` | Hook despues de la actualizacion con datos |
| `headerStyle` | `String?` | Nombre del tipo de encabezado (ej., `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Indicador de carga personalizado para el pie de pagina |

<div id="examples"></div>

## Ejemplos

### Lista Paginada con Actualizacion

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

### Actualizacion Simple con Extension

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
