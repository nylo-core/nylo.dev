# FadeOverlay

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Uso Basico](#basic-usage "Uso Basico")
- [Construtores Direcionais](#directional "Construtores Direcionais")
- [Personalizacao](#customization "Personalizacao")
- [Parametros](#parameters "Parametros")


<div id="introduction"></div>

## Introducao

O widget **FadeOverlay** aplica um efeito de gradiente de esmaecimento sobre seu widget filho. Isso e util para criar profundidade visual, melhorar a legibilidade do texto sobre imagens ou adicionar efeitos estilisticos a sua interface.

<div id="basic-usage"></div>

## Uso Basico

Envolva qualquer widget com `FadeOverlay` para aplicar um gradiente de esmaecimento:

``` dart
FadeOverlay(
  child: Image.asset('assets/images/background.jpg'),
)
```

Isso cria um esmaecimento sutil de transparente no topo ate uma sobreposicao escura na parte inferior.

### Com Texto Sobre Imagem

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

## Construtores Direcionais

O `FadeOverlay` fornece construtores nomeados para direcoes comuns de esmaecimento:

### FadeOverlay.top

Esmaecer de baixo (transparente) para cima (cor):

``` dart
FadeOverlay.top(
  child: Image.asset('assets/header.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.bottom

Esmaecer de cima (transparente) para baixo (cor):

``` dart
FadeOverlay.bottom(
  child: Image.asset('assets/card.jpg'),
  strength: 0.4,
)
```

### FadeOverlay.left

Esmaecer da direita (transparente) para a esquerda (cor):

``` dart
FadeOverlay.left(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

### FadeOverlay.right

Esmaecer da esquerda (transparente) para a direita (cor):

``` dart
FadeOverlay.right(
  child: Image.asset('assets/banner.jpg'),
  strength: 0.3,
)
```

<div id="customization"></div>

## Personalizacao

### Ajustando a Intensidade

O parametro `strength` controla a intensidade do efeito de esmaecimento (0.0 a 1.0):

``` dart
// Subtle fade
FadeOverlay(
  child: myImage,
  strength: 0.1,
)

// Medium fade
FadeOverlay(
  child: myImage,
  strength: 0.5,
)

// Strong fade
FadeOverlay(
  child: myImage,
  strength: 1.0,
)
```

### Cor Personalizada

Altere a cor da sobreposicao para combinar com seu design:

``` dart
// Dark blue overlay
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.blue.shade900,
  strength: 0.6,
)

// White overlay for light themes
FadeOverlay.bottom(
  child: Image.asset('assets/photo.jpg'),
  color: Colors.white,
  strength: 0.4,
)
```

### Direcao de Gradiente Personalizada

Para direcoes nao padrao, especifique os alinhamentos `begin` e `end`:

``` dart
// Diagonal fade (top-left to bottom-right)
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.topLeft,
  end: Alignment.bottomRight,
  strength: 0.5,
)

// Center outward fade
FadeOverlay(
  child: Image.asset('assets/photo.jpg'),
  begin: Alignment.center,
  end: Alignment.bottomCenter,
  strength: 0.4,
)
```

<div id="parameters"></div>

## Parametros

| Parametro | Tipo | Padrao | Descricao |
|-----------|------|--------|-----------|
| `child` | `Widget` | obrigatorio | O widget sobre o qual aplicar o efeito de esmaecimento |
| `strength` | `double` | `0.2` | Intensidade do esmaecimento (0.0 a 1.0) |
| `color` | `Color` | `Colors.black` | A cor da sobreposicao de esmaecimento |
| `begin` | `AlignmentGeometry` | `Alignment.topCenter` | Alinhamento de inicio do gradiente |
| `end` | `AlignmentGeometry` | `Alignment.bottomCenter` | Alinhamento de fim do gradiente |

## Exemplo: Card com Esmaecimento

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
