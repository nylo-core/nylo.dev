# FadeOverlay

---

<a name="section-1"></a>
- [はじめに](#introduction "はじめに")
- [基本的な使い方](#basic-usage "基本的な使い方")
- [方向別コンストラクタ](#directional "方向別コンストラクタ")
- [カスタマイズ](#customization "カスタマイズ")
- [パラメータ](#parameters "パラメータ")


<div id="introduction"></div>

## はじめに

**FadeOverlay** ウィジェットは、子ウィジェットにグラデーションフェードエフェクトを適用します。視覚的な奥行きの作成、画像上のテキストの可読性向上、UI へのスタイリッシュなエフェクトの追加に便利です。

<div id="basic-usage"></div>

## 基本的な使い方

任意のウィジェットを `FadeOverlay` でラップしてグラデーションフェードを適用します:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

これにより、上部の透明から下部のダークオーバーレイへの微妙なフェードが作成されます。

### 画像上のテキスト

``` dart
Stack(
  children: [
    FadeOverlay(
      child: Image.network(
        'https://example.com/image.jpg',
        fit: BoxFit.cover,
      ),
      strength: 0.5,
    ),
    Positioned(
      bottom: 16,
      left: 16,
      child: Text(
        "Photo Title",
        style: TextStyle(color: Colors.white, fontSize: 24),
      ),
    ),
  ],
)
```

<div id="directional"></div>

## 方向別コンストラクタ

`FadeOverlay` は一般的なフェード方向のための名前付きコンストラクタを提供します:

### FadeOverlay.top

下部（透明）から上部（カラー）へフェードします:

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

上部（透明）から下部（カラー）へフェードします:

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

右部（透明）から左部（カラー）へフェードします:

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

左部（透明）から右部（カラー）へフェードします:

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## カスタマイズ

### 強度の調整

`strength` パラメータはフェードエフェクトの強度を制御します（0.0 から 1.0）:

``` dart
// 微妙なフェード
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// 中程度のフェード
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// 強いフェード
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### カスタムカラー

デザインに合わせてオーバーレイの色を変更します:

``` dart
// ダークブルーのオーバーレイ
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// ライトテーマ用のホワイトオーバーレイ
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### カスタムグラデーション方向

標準外の方向には、`begin` と `end` のアライメントを指定します:

``` dart
// 斜めフェード（左上から右下へ）
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// 中央から外側へのフェード
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## パラメータ

| パラメータ | 型 | デフォルト | 説明 |
|-----------|------|---------|-------------|
| `child` | `Widget` | 必須 | フェードエフェクトを適用するウィジェット |
| `strength` | `double` | `0.2` | フェードの強度（0.0 から 1.0） |
| `color` | `Color` | `Colors.black` | フェードオーバーレイの色 |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | グラデーションの開始アライメント |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | グラデーションの終了アライメント |

## 例: フェード付きカード

``` dart
Container(
  height: 200,
  width: double.infinity,
  child: ClipRRect(
    borderRadius: BorderRadius.circular(12),
    child: Stack(
      fit: StackFit.expand,
      children: [
        FadeOverlay.bottom(
          strength: 0.6,
          child: Image.network(
            'https://example.com/product.jpg',
            fit: BoxFit.cover,
          ),
        ),
        Positioned(
          bottom: 16,
          left: 16,
          right: 16,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                "Product Name",
                style: TextStyle(
                  color: Colors.white,
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),
              Text(
                "\$29.99",
                style: TextStyle(color: Colors.white70),
              ),
            ],
          ),
        ),
      ],
    ),
  ),
)
```
