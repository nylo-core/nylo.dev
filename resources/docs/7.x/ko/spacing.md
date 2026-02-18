# Spacing

---

<a name="section-1"></a>
- [소개](#introduction "소개")
- [프리셋 크기](#preset-sizes "프리셋 크기")
- [커스텀 간격](#custom-spacing "커스텀 간격")
- [Sliver와 함께 사용](#slivers "Sliver와 함께 사용")


<div id="introduction"></div>

## 소개

**Spacing** 위젯은 UI 요소 간에 일관된 간격을 추가하는 깔끔하고 읽기 쉬운 방법을 제공합니다. 코드 전체에 수동으로 `SizedBox` 인스턴스를 만드는 대신, 시맨틱 프리셋이나 커스텀 값으로 `Spacing`을 사용할 수 있습니다.

``` dart
// 이렇게 하는 대신:
SizedBox(height: 16),

// 이렇게 작성하세요:
Spacing.md,
```

<div id="preset-sizes"></div>

## 프리셋 크기

`Spacing`에는 일반적인 간격 값을 위한 내장 프리셋이 포함되어 있습니다. 이를 통해 앱 전체에서 일관된 간격을 유지할 수 있습니다.

### 수직 간격 프리셋

`Column` 위젯이나 수직 간격이 필요한 곳에서 사용합니다:

| 프리셋 | 크기 | 용도 |
|--------|------|-------|
| `Spacing.zero` | 0px | 조건부 간격 |
| `Spacing.xs` | 4px | 매우 작음 |
| `Spacing.sm` | 8px | 작음 |
| `Spacing.md` | 16px | 중간 |
| `Spacing.lg` | 24px | 큼 |
| `Spacing.xl` | 32px | 매우 큼 |

``` dart
Column(
  children: [
    Text("Title"),
    Spacing.sm,
    Text("Subtitle"),
    Spacing.lg,
    Text("Body content"),
    Spacing.xl,
    ElevatedButton(
      onPressed: () {},
      child: Text("Action"),
    ),
  ],
)
```

### 수평 간격 프리셋

`Row` 위젯이나 수평 간격이 필요한 곳에서 사용합니다:

| 프리셋 | 크기 | 용도 |
|--------|------|-------|
| `Spacing.xsHorizontal` | 4px | 매우 작음 |
| `Spacing.smHorizontal` | 8px | 작음 |
| `Spacing.mdHorizontal` | 16px | 중간 |
| `Spacing.lgHorizontal` | 24px | 큼 |
| `Spacing.xlHorizontal` | 32px | 매우 큼 |

``` dart
Row(
  children: [
    Icon(Icons.star),
    Spacing.smHorizontal,
    Text("Rating"),
    Spacing.lgHorizontal,
    Text("5.0"),
  ],
)
```

<div id="custom-spacing"></div>

## 커스텀 간격

프리셋이 요구 사항에 맞지 않을 때 커스텀 간격을 만듭니다:

### 수직 간격

``` dart
Spacing.vertical(12) // 12 논리 픽셀의 수직 간격
```

### 수평 간격

``` dart
Spacing.horizontal(20) // 20 논리 픽셀의 수평 간격
```

### 양방향 크기

``` dart
Spacing(width: 10, height: 20) // 커스텀 너비와 높이
```

### 예시

``` dart
Column(
  children: [
    Text("Header"),
    Spacing.vertical(40), // 커스텀 40px 간격
    Card(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: Row(
          children: [
            Icon(Icons.info),
            Spacing.horizontal(12), // 커스텀 12px 간격
            Expanded(child: Text("Information text")),
          ],
        ),
      ),
    ),
  ],
)
```

<div id="slivers"></div>

## Sliver와 함께 사용

`CustomScrollView`와 Sliver를 사용할 때 `asSliver()` 메서드를 사용합니다:

``` dart
CustomScrollView(
  slivers: [
    SliverAppBar(
      title: Text("My App"),
    ),
    Spacing.lg.asSliver(), // SliverToBoxAdapter로 변환
    SliverList(
      delegate: SliverChildBuilderDelegate(
        (context, index) => ListTile(title: Text("Item $index")),
        childCount: 10,
      ),
    ),
    Spacing.xl.asSliver(),
    SliverToBoxAdapter(
      child: Text("Footer"),
    ),
  ],
)
```

`asSliver()` 메서드는 `Spacing` 위젯을 `SliverToBoxAdapter`로 감싸 Sliver 기반 레이아웃과 호환되게 합니다.

## 조건부 간격

조건부 간격에는 `Spacing.zero`를 사용합니다:

``` dart
Column(
  children: [
    Text("Always visible"),
    showExtraSpace ? Spacing.lg : Spacing.zero,
    Text("Content below"),
  ],
)
```
