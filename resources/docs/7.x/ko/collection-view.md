# CollectionView

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [기본 사용법](#basic-usage "기본 사용법")
- [CollectionItem 헬퍼](#collection-item "CollectionItem 헬퍼")
- 변형
    - [CollectionView](#collection-view-basic "CollectionView")
    - [CollectionView.separated](#collection-view-separated "CollectionView.separated")
    - [CollectionView.grid](#collection-view-grid "CollectionView.grid")
- Pullable 변형
    - [CollectionView.pullable](#collection-view-pullable "CollectionView.pullable")
    - [CollectionView.pullableSeparated](#collection-view-pullable-separated "CollectionView.pullableSeparated")
    - [CollectionView.pullableGrid](#collection-view-pullable-grid "CollectionView.pullableGrid")
- [로딩 스타일](#loading-styles "로딩 스타일")
- [빈 상태](#empty-state "빈 상태")
- [데이터 정렬 및 변환](#sorting-transforming "데이터 정렬 및 변환")
- [상태 업데이트](#updating-state "상태 업데이트")
- [매개변수 참조](#parameters "매개변수 참조")


<div id="introduction"></div>

## 소개

**CollectionView** 위젯은 {{ config('app.name') }} 프로젝트에서 데이터 목록을 표시하기 위한 강력하고 타입 안전한 래퍼입니다. `ListView`, `ListView.separated` 및 그리드 레이아웃 작업을 단순화하면서 다음을 기본 지원합니다:

- 자동 로딩 상태가 포함된 비동기 데이터 로딩
- 당겨서 새로고침 및 페이지네이션
- 위치 헬퍼가 포함된 타입 안전 항목 빌더
- 빈 상태 처리
- 데이터 정렬 및 변환

<div id="basic-usage"></div>

## 기본 사용법

항목 목록을 표시하는 간단한 예제입니다:

``` dart
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: CollectionView<String>(
      data: () => ['Item 1', 'Item 2', 'Item 3'],
      builder: (context, item) {
        return ListTile(
          title: Text(item.data),
        );
      },
    ),
  );
}
```

API에서 비동기 데이터를 사용하는 경우:

``` dart
CollectionView<Todo>(
  data: () async {
    return await api<ApiService>((request) =>
      request.get('https://jsonplaceholder.typicode.com/todos')
    );
  },
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.title),
      subtitle: Text(item.data.completed ? 'Done' : 'Pending'),
    );
  },
)
```

<div id="collection-item"></div>

## CollectionItem 헬퍼

`builder` 콜백은 유용한 위치 헬퍼로 데이터를 감싸는 `CollectionItem<T>` 객체를 받습니다:

``` dart
CollectionView<String>(
  data: () => ['First', 'Second', 'Third', 'Fourth'],
  builder: (context, item) {
    return Container(
      color: item.isEven ? Colors.grey[100] : Colors.white,
      child: ListTile(
        title: Text('${item.data} (index: ${item.index})'),
        subtitle: Text('Progress: ${(item.progress * 100).toInt()}%'),
      ),
    );
  },
)
```

### CollectionItem 속성

| 속성 | 타입 | 설명 |
|----------|------|-------------|
| `data` | `T` | 실제 항목 데이터 |
| `index` | `int` | 리스트에서의 현재 인덱스 |
| `totalItems` | `int` | 총 항목 수 |
| `isFirst` | `bool` | 첫 번째 항목이면 true |
| `isLast` | `bool` | 마지막 항목이면 true |
| `isOdd` | `bool` | 인덱스가 홀수이면 true |
| `isEven` | `bool` | 인덱스가 짝수이면 true |
| `progress` | `double` | 리스트 진행률 (0.0 ~ 1.0) |

### CollectionItem 메서드

| 메서드 | 설명 |
|--------|-------------|
| `isAt(int position)` | 항목이 특정 위치에 있는지 확인 |
| `isInRange(int start, int end)` | 인덱스가 범위 내에 있는지 확인 (포함) |
| `isMultipleOf(int divisor)` | 인덱스가 제수의 배수인지 확인 |

<div id="collection-view-basic"></div>

## CollectionView

기본 생성자는 표준 리스트 뷰를 생성합니다:

``` dart
CollectionView<Map<String, dynamic>>(
  data: () async {
    return [
      {"title": "Clean Room"},
      {"title": "Go shopping"},
      {"title": "Buy groceries"},
    ];
  },
  builder: (context, item) {
    return ListTile(title: Text(item.data['title']));
  },
  spacing: 8.0, // Add spacing between items
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-separated"></div>

## CollectionView.separated

항목 사이에 구분선이 있는 리스트를 생성합니다:

``` dart
CollectionView<User>.separated(
  data: () async => await fetchUsers(),
  builder: (context, item) {
    return ListTile(
      title: Text(item.data.name),
      subtitle: Text(item.data.email),
    );
  },
  separatorBuilder: (context, index) {
    return Divider(height: 1);
  },
)
```

<div id="collection-view-grid"></div>

## CollectionView.grid

스태거드 그리드를 사용한 그리드 레이아웃을 생성합니다:

``` dart
CollectionView<Product>.grid(
  data: () async => await fetchProducts(),
  builder: (context, item) {
    return ProductCard(product: item.data);
  },
  crossAxisCount: 2,
  mainAxisSpacing: 8.0,
  crossAxisSpacing: 8.0,
  padding: EdgeInsets.all(16),
)
```

<div id="collection-view-pullable"></div>

## CollectionView.pullable

당겨서 새로고침과 무한 스크롤 페이지네이션이 있는 리스트를 생성합니다:

``` dart
CollectionView<Post>.pullable(
  data: (int iteration) async {
    // iteration은 1에서 시작하여 로드할 때마다 증가합니다
    return await api<ApiService>((request) =>
      request.get('/posts?page=$iteration')
    );
  },
  builder: (context, item) {
    return PostCard(post: item.data);
  },
  onRefresh: () {
    print('List was refreshed!');
  },
  headerStyle: 'WaterDropHeader', // Pull indicator style
)
```

### 헤더 스타일

`headerStyle` 매개변수가 허용하는 값:
- `'WaterDropHeader'` (기본값) - 물방울 애니메이션
- `'ClassicHeader'` - 클래식 당기기 인디케이터
- `'MaterialClassicHeader'` - Material Design 스타일
- `'WaterDropMaterialHeader'` - Material 물방울
- `'BezierHeader'` - 베지어 커브 애니메이션

### 페이지네이션 콜백

| 콜백 | 설명 |
|----------|-------------|
| `beforeRefresh` | 새로고침 시작 전에 호출 |
| `onRefresh` | 새로고침 완료 시 호출 |
| `afterRefresh` | 데이터 로드 후 호출, 변환을 위한 데이터를 받음 |

<div id="collection-view-pullable-separated"></div>

## CollectionView.pullableSeparated

당겨서 새로고침과 구분된 리스트를 결합합니다:

``` dart
CollectionView<Message>.pullableSeparated(
  data: (iteration) async => await fetchMessages(page: iteration),
  builder: (context, item) {
    return MessageTile(message: item.data);
  },
  separatorBuilder: (context, index) => Divider(),
)
```

<div id="collection-view-pullable-grid"></div>

## CollectionView.pullableGrid

당겨서 새로고침과 그리드 레이아웃을 결합합니다:

``` dart
CollectionView<Photo>.pullableGrid(
  data: (iteration) async => await fetchPhotos(page: iteration),
  builder: (context, item) {
    return Image.network(item.data.url);
  },
  crossAxisCount: 3,
  mainAxisSpacing: 4,
  crossAxisSpacing: 4,
)
```

<div id="loading-styles"></div>

## 로딩 스타일

`loadingStyle`을 사용하여 로딩 인디케이터를 커스터마이즈합니다:

``` dart
// 커스텀 위젯으로 일반 로딩
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading items..."),
  ),
)

// 스켈레톤 로딩 효과
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserCard(user: item.data),
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()),
    effect: SkeletonizerEffect.shimmer,
  ),
)
```

<div id="empty-state"></div>

## 빈 상태

리스트가 비어있을 때 커스텀 위젯을 표시합니다:

``` dart
CollectionView<Item>(
  data: () async => await fetchItems(),
  builder: (context, item) => ItemTile(item: item.data),
  empty: Center(
    child: Column(
      mainAxisAlignment: MainAxisAlignment.center,
      children: [
        Icon(Icons.inbox, size: 64, color: Colors.grey),
        SizedBox(height: 16),
        Text('No items found'),
      ],
    ),
  ),
)
```

<div id="sorting-transforming"></div>

## 데이터 정렬 및 변환

### 정렬

표시 전에 항목을 정렬합니다:

``` dart
CollectionView<Task>(
  data: () async => await fetchTasks(),
  builder: (context, item) => TaskTile(task: item.data),
  sort: (List<Task> items) {
    items.sort((a, b) => a.dueDate.compareTo(b.dueDate));
    return items;
  },
)
```

### 변환

로딩 후 데이터를 변환합니다:

``` dart
CollectionView<User>(
  data: () async => await fetchUsers(),
  builder: (context, item) => UserTile(user: item.data),
  transform: (List<User> users) {
    // 활성 사용자만 필터링
    return users.where((u) => u.isActive).toList();
  },
)
```

<div id="updating-state"></div>

## 상태 업데이트

`stateName`을 지정하여 CollectionView를 업데이트하거나 리셋할 수 있습니다:

``` dart
CollectionView<Todo>(
  stateName: "my_todo_list",
  data: () async => await fetchTodos(),
  builder: (context, item) => TodoTile(todo: item.data),
)
```

### 리스트 리셋

``` dart
// 처음부터 데이터를 리셋하고 다시 로드
CollectionView.stateReset("my_todo_list");
```

### 항목 제거

``` dart
// 인덱스 2의 항목 제거
CollectionView.removeFromIndex("my_todo_list", 2);
```

### 일반 업데이트 트리거

``` dart
// 전역 updateState 헬퍼 사용
updateState("my_todo_list");
```

<div id="parameters"></div>

## 매개변수 참조

### 공통 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `data` | `Function()` | `List<T>` 또는 `Future<List<T>>`를 반환하는 함수 |
| `builder` | `CollectionItemBuilder<T>` | 각 항목에 대한 빌더 함수 |
| `empty` | `Widget?` | 리스트가 비어있을 때 표시되는 위젯 |
| `loadingStyle` | `LoadingStyle?` | 로딩 인디케이터 커스터마이즈 |
| `header` | `Widget?` | 리스트 위에 표시되는 헤더 위젯 |
| `stateName` | `String?` | 상태 관리를 위한 이름 |
| `sort` | `Function(List<T>)?` | 항목 정렬 함수 |
| `transform` | `Function(List<T>)?` | 데이터 변환 함수 |
| `spacing` | `double?` | 항목 간 간격 |

### Pullable 전용 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `data` | `Function(int iteration)` | 페이지네이션 데이터 함수 |
| `onRefresh` | `Function()?` | 새로고침 완료 시 콜백 |
| `beforeRefresh` | `Function()?` | 새로고침 전 콜백 |
| `afterRefresh` | `Function(dynamic)?` | 데이터 로드 후 콜백 |
| `headerStyle` | `String?` | 당기기 인디케이터 스타일 |
| `footerLoadingIcon` | `Widget?` | 페이지네이션용 커스텀 로딩 인디케이터 |

### Grid 전용 매개변수

| 매개변수 | 타입 | 설명 |
|-----------|------|-------------|
| `crossAxisCount` | `int` | 열 수 (기본값: 2) |
| `mainAxisSpacing` | `double` | 항목 간 세로 간격 |
| `crossAxisSpacing` | `double` | 항목 간 가로 간격 |

### ListView 매개변수

모든 표준 `ListView` 매개변수도 지원됩니다: `scrollDirection`, `reverse`, `controller`, `physics`, `shrinkWrap`, `padding`, `cacheExtent` 등.
