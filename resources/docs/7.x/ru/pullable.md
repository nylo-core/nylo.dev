# Pullable

---

<a name="section-1"></a>
- [Введение](#introduction "Введение")
- [Базовое использование](#basic-usage "Базовое использование")
- [Конструкторы](#constructors "Конструкторы")
- [PullableConfig](#pullable-config "PullableConfig")
- [Стили заголовков](#header-styles "Стили заголовков")
- [Потяните вверх для загрузки](#pull-up "Потяните вверх для загрузки")
- [Пользовательские заголовки и подвалы](#custom-headers "Пользовательские заголовки и подвалы")
- [Контроллер](#controller "Контроллер")
- [Метод расширения](#extension-method "Метод расширения")
- [Интеграция с CollectionView](#collection-view "Интеграция с CollectionView")
- [Примеры](#examples "Практические примеры")

<div id="introduction"></div>

## Введение

Виджет **Pullable** добавляет функциональность «потяните для обновления» и «загрузить ещё» к любому прокручиваемому содержимому. Он оборачивает дочерний виджет, добавляя поведение обновления и пагинации, управляемое жестами, с поддержкой различных стилей анимации заголовка.

Построенный на основе пакета `pull_to_refresh_flutter3`, Pullable предоставляет чистый API с именованными конструкторами для типовых конфигураций.

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

## Базовое использование

Оберните любой прокручиваемый виджет с помощью `Pullable`:

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

Когда пользователь тянет список вниз, срабатывает обратный вызов `onRefresh`. Индикатор обновления автоматически завершается, когда обратный вызов выполнен.

<div id="constructors"></div>

## Конструкторы

`Pullable` предоставляет именованные конструкторы для типовых конфигураций:

| Конструктор | Стиль заголовка | Описание |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | Конструктор по умолчанию |
| `Pullable.classicHeader()` | Classic | Классический стиль «потяните для обновления» |
| `Pullable.waterDropHeader()` | Water Drop | Анимация капли воды |
| `Pullable.materialClassicHeader()` | Material Classic | Классический стиль Material Design |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Стиль капли воды Material |
| `Pullable.bezierHeader()` | Bezier | Анимация кривой Безье |
| `Pullable.noBounce()` | Настраиваемый | Уменьшенный отскок с `ClampingScrollPhysics` |
| `Pullable.custom()` | Пользовательский виджет | Используйте собственные виджеты заголовка/подвала |
| `Pullable.builder()` | Настраиваемый | Полный контроль через `PullableConfig` |

### Примеры

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

Для тонкой настройки используйте `PullableConfig` с конструктором `Pullable.builder()`:

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

### Все параметры конфигурации

| Свойство | Тип | По умолчанию | Описание |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | Включить «потяните вниз для обновления» |
| `enablePullUp` | `bool` | `false` | Включить «потяните вверх для загрузки» |
| `physics` | `ScrollPhysics?` | null | Пользовательская физика прокрутки |
| `onRefresh` | `Future<void> Function()?` | null | Обратный вызов обновления |
| `onLoading` | `Future<void> Function()?` | null | Обратный вызов загрузки |
| `headerType` | `PullableHeaderType` | `waterDrop` | Стиль анимации заголовка |
| `customHeader` | `Widget?` | null | Пользовательский виджет заголовка |
| `customFooter` | `Widget?` | null | Пользовательский виджет подвала |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | Задержка перед завершением обновления |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | Задержка перед завершением загрузки |
| `enableOverScroll` | `bool` | `true` | Разрешить эффект прокрутки за пределы |
| `cacheExtent` | `double?` | null | Область кэширования прокрутки |
| `semanticChildCount` | `int?` | null | Количество семантических дочерних элементов |
| `dragStartBehavior` | `DragStartBehavior` | `start` | Поведение начала жеста перетаскивания |

<div id="header-styles"></div>

## Стили заголовков

Выберите одну из пяти встроенных анимаций заголовка:

``` dart
enum PullableHeaderType {
  classic,           // Classic pull indicator
  waterDrop,         // Water drop animation (default)
  materialClassic,   // Material Design classic
  waterDropMaterial,  // Material water drop
  bezier,            // Bezier curve animation
}
```

Установите стиль через конструктор или конфигурацию:

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

## Потяните вверх для загрузки

Включите пагинацию с загрузкой при вытягивании вверх:

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

## Пользовательские заголовки и подвалы

Предоставьте собственные виджеты заголовка и подвала:

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

## Контроллер

Используйте `RefreshController` для программного управления:

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

### Методы расширения RefreshController

| Метод/Геттер | Возвращаемый тип | Описание |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | Вручную запустить обновление |
| `triggerLoading()` | `void` | Вручную запустить загрузку |
| `isRefreshing` | `bool` | Активно ли обновление |
| `isLoading` | `bool` | Активна ли загрузка |

<div id="extension-method"></div>

## Метод расширения

Любой виджет можно обернуть функцией «потяните для обновления» с помощью расширения `.pullable()`:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

С пользовательской конфигурацией:

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

## Интеграция с CollectionView

`CollectionView` предоставляет варианты с поддержкой pullable и встроенной пагинацией:

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

### Параметры, специфичные для Pullable

| Параметр | Тип | Описание |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | Обратный вызов для пагинированных данных (iteration начинается с 1) |
| `onRefresh` | `Function()?` | Обратный вызов после обновления |
| `beforeRefresh` | `Function()?` | Хук перед началом обновления |
| `afterRefresh` | `Function(dynamic)?` | Хук после обновления с данными |
| `headerStyle` | `String?` | Имя типа заголовка (например, `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | Пользовательский индикатор загрузки для подвала |

<div id="examples"></div>

## Примеры

### Пагинированный список с обновлением

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

### Простое обновление с расширением

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
