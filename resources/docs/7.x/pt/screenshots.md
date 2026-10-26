# Screenshots
<!-- uncertain: newly created translation; needs full human review -->

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução aos Screenshots")
- [Capturando a Tela](#capturing-the-screen "Capturando a tela inteira")
- [Capturando uma Área](#capturing-an-area "Capturando uma parte da tela com ScreenshotArea")
- [Salvando um Screenshot](#saving-a-screenshot "Salvando um screenshot em um disco")
- [Páginas de Loja](#store-listings "Dimensionando screenshots para a App Store e o Google Play")
  - [Tamanhos de Loja](#store-sizes "Tamanhos de ScreenshotStore")
- [Ocultando Conteúdo](#hiding-content "Ocultando conteúdo dos screenshots")
- [Capturando um Widget](#capturing-a-widget "Capturando um widget sem exibi-lo")
- [Idiomas e Temas](#locales-and-themes "Capturando em outro idioma ou tema")
- [Pelo Metro](#from-metro "Tirando screenshots pelo Metro")

<div id="introduction"></div>

## Introdução

<x-doc-strip label="Requer" items="{{ config('app.name') }} 7.5.0+, nylo_support 7.37.0+" />

`Screenshot` tira um PNG da tela do seu app, ou de uma parte dela:

``` dart
// A tela inteira, incluindo dialogs e sheets
final Screenshot shot = await Screenshot.capture();
await shot.store('reports/checkout.png');

// Uma parte da tela
final Screenshot receipt = await Screenshot.area('receipt');
await receipt.store('order-1042.png', disk: 'public');
```

As capturas funcionam em todos os modos de build, então podem alimentar recursos no seu app, como compartilhar um recibo ou anexar a tela a um relatório de bug. O [Metro](#from-metro) e os [playbooks](/docs/{{ $version }}/playbooks#screenshots) usam o mesmo código.

Um `Screenshot` guarda o PNG como `bytes`, com seu `width` e `height` em pixels físicos.

<div id="capturing-the-screen"></div>

## Capturando a Tela

`Screenshot.capture()` lê a tela inteira do app como o usuário a vê:

``` dart
final Screenshot shot = await Screenshot.capture();
```

| Parâmetro | Descrição |
|-----------|-------------|
| `pixelRatio` | O tamanho em relação aos pixels lógicos. Por padrão, o do dispositivo, então o PNG tem a resolução nativa da tela |
| `settle` | Espera o próximo frame antes de capturar (padrão `true`). Passe `false` quando um frame acabou de ser desenhado |
| `locale` | Exibe a tela neste idioma durante a captura, ex.: `'es'` |
| `theme` | Exibe a tela neste id de tema durante a captura, ex.: `'dark'` |

Um app construído com `NyApp.materialApp` captura exatamente as suas páginas. Se você construir seu próprio `MaterialApp`, adicione um `ScreenshotRoot` ao seu `builder`:

``` dart
MaterialApp(
  builder: (context, child) => ScreenshotRoot(child: child!),
)
```

<div id="capturing-an-area"></div>

## Capturando uma Área

Envolva uma parte de uma tela em um `ScreenshotArea` e dê um nome a ela:

``` dart
ScreenshotArea(
  name: 'receipt',
  child: ReceiptCard(order: order),
)
```

Depois capture-a por esse nome:

``` dart
final Screenshot receipt = await Screenshot.area('receipt');
```

`Screenshot.area` aceita os mesmos parâmetros `pixelRatio`, `settle`, `locale` e `theme` que `capture`. `Screenshot.areas` lista os nomes das áreas na tela.

<div id="saving-a-screenshot"></div>

## Salvando um Screenshot

`store` salva o PNG em um disco do armazenamento de arquivos e retorna o caminho:

``` dart
final Screenshot shot = await Screenshot.capture();

String path = await shot.store('reports/checkout.png');
await shot.store('order-1042.png', disk: 'public');
```

Sem `disk`, ele salva no disco padrão. Para compartilhar a imagem ou enviá-la, leia `shot.bytes`.

<div id="store-listings"></div>

## Páginas de Loja

`forStore` retorna um novo screenshot no tamanho exato que uma loja de apps pede:

``` dart
final Screenshot listing = await shot.forStore(
  ScreenshotStore.ios69,
  frame: true,
  caption: 'Checkout in one tap',
);
await listing.store('store/ios-6.9/checkout.png');
```

Uma tela de formato quase igual preenche o tamanho, e qualquer outra é ajustada dentro dele. Com `frame: true`, a tela é desenhada em um contorno de celular sob o `caption`.

| Parâmetro | Descrição |
|-----------|-------------|
| `frame` | Desenha a tela dentro de um contorno de celular |
| `caption` | Texto acima do celular, usado com `frame` |
| `background` | A `Color` ao redor da tela |

Use `framed()` para desenhar o contorno no tamanho do próprio screenshot:

``` dart
final Screenshot framed = await shot.framed(caption: 'Checkout in one tap');
```

<div id="store-sizes"></div>

### Tamanhos de Loja

| Tamanho | Id | Pixels |
|------|----|--------|
| `ScreenshotStore.ios69` | `ios-6.9` | 1320 x 2868 |
| `ScreenshotStore.ios65` | `ios-6.5` | 1284 x 2778 |
| `ScreenshotStore.ipad13` | `ipad-13` | 2064 x 2752 |
| `ScreenshotStore.playPhone` | `play-phone` | 1080 x 1920 |
| `ScreenshotStore.playTablet` | `play-tablet` | 1600 x 2560 |

`ScreenshotStore.all` lista todos os tamanhos, e `ScreenshotStore.named('ios-6.9')` encontra um pelo seu id.

<div id="hiding-content"></div>

## Ocultando Conteúdo

Envolva qualquer coisa privada em `ScreenshotHidden`. Ela aparece normalmente na tela, e um bloco cinza a cobre em todo screenshot:

``` dart
ScreenshotHidden(
  child: Text('Account 4021 8890 1177'),
)
```

Passe `replacement` para desenhar seu próprio placeholder no lugar do bloco cinza.

<div id="capturing-a-widget"></div>

## Capturando um Widget

`Screenshot.widget` captura um widget sem exibi-lo, como um card de compartilhamento criado só para a imagem:

``` dart
final Screenshot card = await Screenshot.widget(
  SizedBox(
    width: 400,
    height: 400,
    child: ShareCard(order: order),
  ),
  context: context,
);
```

O PNG tem o tamanho do widget, então envolva-o em um `SizedBox` para um tamanho exato. Passe `context` para dar ao widget o tema e as localizações desse context, `size` para definir o espaço em que ele é disposto, e `delay` para dar tempo às imagens de carregar.

<div id="locales-and-themes"></div>

## Idiomas e Temas

Passe `locale` ou `theme` para exibir a tela de outra forma em uma captura:

``` dart
final Screenshot spanish = await Screenshot.capture(locale: 'es');
final Screenshot dark = await Screenshot.area('receipt', theme: 'dark');
```

O app troca no lugar, mantendo a página, seu estado e a pilha de navegação, e volta logo em seguida. Nada é salvo, então as escolhas do próprio usuário são mantidas. Um idioma precisa de um arquivo de idioma nos seus assets, e um tema precisa de um id de tema registrado.

<div id="from-metro"></div>

## Pelo Metro

Salve um screenshot do seu app em execução pelo terminal:

``` bash
metro live:run screenshot --open
metro live:run screenshot --area receipt
metro live:run screenshot --store ios-6.9 --frame
```

Veja [Metro Live](/docs/{{ $version }}/metro-live#screenshots) para todas as opções. Um [playbook](/docs/{{ $version }}/playbooks#screenshots) pode tirar screenshots de uma jornada inteira, em todos os idiomas e temas.
