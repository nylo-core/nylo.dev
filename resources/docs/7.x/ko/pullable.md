# Pullable

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [생성자](#constructors "생성자")
- [PullableConfig](#pullable-config "PullableConfig")
- [헤더 스타일](#header-styles "헤더 스타일")
- [위로 당겨 더 보기](#pull-up "위로 당겨 더 보기")
- [커스텀 헤더와 푸터](#custom-headers "커스텀 헤더와 푸터")
- [컨트롤러](#controller "컨트롤러")
- [확장 메서드](#extension-method "확장 메서드")
- [CollectionView 통합](#collection-view "CollectionView 통합")
- [예제](#examples "예제")

<div id="introduction"></div>

## 소개

**Pullable** 위젯은 모든 스크롤 가능한 콘텐츠에 당겨서 새로고침 및 더 보기 기능을 추가합니다. 자식 위젯을 제스처 기반 새로고침 및 페이지네이션 동작으로 감싸며, 여러 헤더 애니메이션 스타일을 지원합니다.

`pull_to_refresh_flutter3` 패키지 위에 구축된 Pullable은 일반적인 설정을 위한 네임드 생성자와 함께 깔끔한 API를 제공합니다.

``` dart
Pullable(
  onRefresh: () async {
    // 최신 데이터 가져오기
    await fetchData();
  },
  child: ListView(
    children: items.map((item) => ListTile(title: Text(item))).toList(),
  ),
)
```

<div id="basic-usage"></div>

## 기본 사용법

모든 스크롤 가능한 위젯을 `Pullable`로 감쌉니다:

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

사용자가 목록을 아래로 당기면 `onRefresh` 콜백이 실행됩니다. 새로고침 인디케이터는 콜백이 완료되면 자동으로 종료됩니다.

<div id="constructors"></div>

## 생성자

`Pullable`은 일반적인 설정을 위한 네임드 생성자를 제공합니다:

| 생성자 | 헤더 스타일 | 설명 |
|-------------|-------------|-------------|
| `Pullable()` | Water Drop | 기본 생성자 |
| `Pullable.classicHeader()` | Classic | 클래식 당겨서 새로고침 스타일 |
| `Pullable.waterDropHeader()` | Water Drop | 물방울 애니메이션 |
| `Pullable.materialClassicHeader()` | Material Classic | Material Design 클래식 스타일 |
| `Pullable.waterDropMaterialHeader()` | Water Drop Material | Material 물방울 스타일 |
| `Pullable.bezierHeader()` | Bezier | 베지어 곡선 애니메이션 |
| `Pullable.noBounce()` | 설정 가능 | `ClampingScrollPhysics`로 바운스 감소 |
| `Pullable.custom()` | 커스텀 위젯 | 자체 헤더/푸터 위젯 사용 |
| `Pullable.builder()` | 설정 가능 | 전체 `PullableConfig` 제어 |

### 예시

``` dart
// 클래식 헤더
Pullable.classicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// Material 헤더
Pullable.materialClassicHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// 바운스 효과 없음
Pullable.noBounce(
  onRefresh: () async => await refreshData(),
  headerType: PullableHeaderType.classic,
  child: myListView,
)

// 커스텀 헤더 위젯
Pullable.custom(
  customHeader: MyCustomRefreshHeader(),
  onRefresh: () async => await refreshData(),
  child: myListView,
)
```

<div id="pullable-config"></div>

## PullableConfig

세밀한 제어를 위해 `Pullable.builder()` 생성자와 함께 `PullableConfig`를 사용합니다:

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

### 모든 설정 옵션

| 속성 | 타입 | 기본값 | 설명 |
|----------|------|---------|-------------|
| `enablePullDown` | `bool` | `true` | 아래로 당겨서 새로고침 활성화 |
| `enablePullUp` | `bool` | `false` | 위로 당겨서 더 보기 활성화 |
| `physics` | `ScrollPhysics?` | null | 커스텀 스크롤 물리 |
| `onRefresh` | `Future<void> Function()?` | null | 새로고침 콜백 |
| `onLoading` | `Future<void> Function()?` | null | 더 보기 콜백 |
| `headerType` | `PullableHeaderType` | `waterDrop` | 헤더 애니메이션 스타일 |
| `customHeader` | `Widget?` | null | 커스텀 헤더 위젯 |
| `customFooter` | `Widget?` | null | 커스텀 푸터 위젯 |
| `refreshCompleteDelay` | `Duration` | `Duration.zero` | 새로고침 완료 전 지연 |
| `loadCompleteDelay` | `Duration` | `Duration.zero` | 로드 완료 전 지연 |
| `enableOverScroll` | `bool` | `true` | 오버스크롤 효과 허용 |
| `cacheExtent` | `double?` | null | 스크롤 캐시 범위 |
| `semanticChildCount` | `int?` | null | 시맨틱 자식 수 |
| `dragStartBehavior` | `DragStartBehavior` | `start` | 드래그 제스처 시작 방식 |

<div id="header-styles"></div>

## 헤더 스타일

다섯 가지 내장 헤더 애니메이션 중에서 선택합니다:

``` dart
enum PullableHeaderType {
  classic,           // 클래식 당기기 인디케이터
  waterDrop,         // 물방울 애니메이션 (기본값)
  materialClassic,   // Material Design 클래식
  waterDropMaterial,  // Material 물방울
  bezier,            // 베지어 곡선 애니메이션
}
```

생성자 또는 config를 통해 스타일을 설정합니다:

``` dart
// 네임드 생성자를 통해
Pullable.bezierHeader(
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// config를 통해
Pullable.builder(
  config: PullableConfig(
    headerType: PullableHeaderType.bezier,
    onRefresh: () async => await refreshData(),
  ),
  child: myListView,
)
```

<div id="pull-up"></div>

## 위로 당겨 더 보기

위로 당겨 로딩을 통해 페이지네이션을 활성화합니다:

``` dart
Pullable.builder(
  config: PullableConfig(
    enablePullDown: true,
    enablePullUp: true,
    onRefresh: () async {
      // 페이지 1로 초기화
      page = 1;
      items = await fetchItems(page: page);
      setState(() {});
    },
    onLoading: () async {
      // 다음 페이지 로드
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

## 커스텀 헤더와 푸터

자체 헤더 및 푸터 위젯을 제공합니다:

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

## 컨트롤러

프로그래밍 방식의 제어를 위해 `RefreshController`를 사용합니다:

``` dart
final RefreshController _controller = RefreshController();

Pullable(
  controller: _controller,
  onRefresh: () async => await refreshData(),
  child: myListView,
)

// 프로그래밍 방식으로 새로고침 트리거
_controller.triggerRefresh();

// 프로그래밍 방식으로 로딩 트리거
_controller.triggerLoading();

// 상태 확인
bool refreshing = _controller.isRefreshing;
bool loading = _controller.isLoading;
```

### RefreshController의 확장 메서드

| 메서드/Getter | 반환 타입 | 설명 |
|---------------|-------------|-------------|
| `triggerRefresh()` | `void` | 수동으로 새로고침 트리거 |
| `triggerLoading()` | `void` | 수동으로 더 보기 트리거 |
| `isRefreshing` | `bool` | 새로고침 활성 여부 |
| `isLoading` | `bool` | 로딩 활성 여부 |

<div id="extension-method"></div>

## 확장 메서드

`.pullable()` 확장을 사용하여 모든 위젯에 당겨서 새로고침을 적용할 수 있습니다:

``` dart
ListView(
  children: items.map((item) => ListTile(title: Text(item.name))).toList(),
).pullable(
  onRefresh: () async {
    await fetchItems();
  },
)
```

커스텀 config와 함께:

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

## CollectionView 통합

`CollectionView`는 내장 페이지네이션이 포함된 pullable 변형을 제공합니다:

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

### Pullable 전용 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | 페이지네이션 데이터 콜백 (iteration은 1부터 시작) |
| `onRefresh` | `Function()?` | 새로고침 후 콜백 |
| `beforeRefresh` | `Function()?` | 새로고침 시작 전 훅 |
| `afterRefresh` | `Function(dynamic)?` | 데이터와 함께 새로고침 후 훅 |
| `headerStyle` | `String?` | 헤더 타입 이름 (예: `'WaterDropHeader'`, `'ClassicHeader'`) |
| `footerLoadingIcon` | `Widget?` | 푸터용 커스텀 로딩 인디케이터 |

<div id="examples"></div>

## 예제

### 새로고침이 포함된 페이지네이션 목록

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

### 확장을 사용한 간단한 새로고침

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
