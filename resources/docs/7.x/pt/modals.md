# Modals

---

<a name="section-1"></a>
- [Introducao](#introduction "Introducao")
- [Criando um Modal](#creating-a-modal "Criando um Modal")
- [Uso Basico](#basic-usage "Uso Basico")
- [Criando um Modal](#creating-a-modal "Criando um Modal")
- [BottomSheetModal](#bottom-sheet-modal "BottomSheetModal")
  - [Parametros](#parameters "Parametros")
  - [Acoes](#actions "Acoes")
  - [Cabecalho](#header "Cabecalho")
  - [Botao Fechar](#close-button "Botao Fechar")
  - [Decoracao Personalizada](#custom-decoration "Decoracao Personalizada")
- [BottomModalSheetStyle](#bottom-modal-sheet-style "BottomModalSheetStyle")
- [Exemplos](#examples "Exemplos Praticos")

<div id="introduction"></div>

## Introducao

{{ config('app.name') }} fornece um sistema de modais construido em torno de **bottom sheet modals**.

A classe `BottomSheetModal` oferece uma API flexivel para exibir sobreposicoes de conteudo com acoes, cabecalhos, botoes de fechar e estilizacao personalizada.

Modais sao uteis para:
- Dialogos de confirmacao (ex.: logout, exclusao)
- Formularios de entrada rapida
- Folhas de acoes com multiplas opcoes
- Sobreposicoes informativas

<div id="creating-a-modal"></div>

## Criando um Modal

Voce pode criar um novo modal usando o Metro CLI:

``` bash
metro make:bottom_sheet_modal payment_options
```

Isso gera duas coisas:

1. **Um widget de conteudo do modal** em `lib/resources/widgets/bottom_sheet_modals/modals/payment_options_modal.dart`:

``` dart
import 'package:flutter/material.dart';

/// Payment Options Modal
///
/// Used in BottomSheetModal.showPaymentOptions()
class PaymentOptionsModal extends StatelessWidget {
  const PaymentOptionsModal({super.key});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text('PaymentOptionsModal').headingLarge(),
      ],
    );
  }
}
```

2. **Um metodo estatico** adicionado a sua classe `BottomSheetModal` em `lib/resources/widgets/bottom_sheet_modals/bottom_sheet_modals.dart`:

``` dart
/// Show Payment Options modal
static Future<void> showPaymentOptions(BuildContext context) {
  return displayModal(
    context,
    isScrollControlled: false,
    child: const PaymentOptionsModal(),
  );
}
```

Voce pode entao exibir o modal de qualquer lugar:

``` dart
BottomSheetModal.showPaymentOptions(context);
```

Se um modal com o mesmo nome ja existir, use a flag `--force` para sobrescreve-lo:

``` bash
metro make:bottom_sheet_modal payment_options --force
```

<div id="basic-usage"></div>

## Uso Basico

Exiba um modal usando `BottomSheetModal`:

``` dart
BottomSheetModal.showLogout(context);
```

<div id="creating-a-modal"></div>

## Criando um Modal

O padrao recomendado e criar uma classe `BottomSheetModal` com metodos estaticos para cada tipo de modal. O boilerplate fornece essa estrutura:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';

class BottomSheetModal extends NyBaseModal {
  static ModalShowFunction get displayModal => displayModal;

  /// Show Logout modal
  static Future<void> showLogout(
    BuildContext context, {
    Function()? onLogoutPressed,
    Function()? onCancelPressed,
  }) {
    return displayModal(
      context,
      isScrollControlled: false,
      child: const LogoutModal(),
      actionsRow: [
        Button.secondary(
          text: "Logout",
          onPressed: onLogoutPressed ?? () => routeToInitial(),
        ),
        Button(
          text: "Cancel",
          onPressed: onCancelPressed ?? () => Navigator.pop(context),
        ),
      ],
    );
  }
}
```

Chame de qualquer lugar:

``` dart
BottomSheetModal.showLogout(context);

// With custom callbacks
BottomSheetModal.showLogout(
  context,
  onLogoutPressed: () {
    // Custom logout logic
  },
  onCancelPressed: () {
    Navigator.pop(context);
  },
);
```

<div id="bottom-sheet-modal"></div>

## BottomSheetModal

`displayModal<T>()` e o metodo principal para exibir modais.

<div id="parameters"></div>

### Parametros

| Parametro | Tipo | Padrao | Descricao |
|-----------|------|--------|-----------|
| `context` | `BuildContext` | obrigatorio | Contexto de build para o modal |
| `child` | `Widget` | obrigatorio | Widget de conteudo principal |
| `actionsRow` | `List<Widget>` | `[]` | Widgets de acao exibidos em uma linha horizontal |
| `actionsColumn` | `List<Widget>` | `[]` | Widgets de acao exibidos verticalmente |
| `height` | `double?` | null | Altura fixa para o modal |
| `header` | `Widget?` | null | Widget de cabecalho no topo |
| `useSafeArea` | `bool` | `true` | Envolver conteudo em SafeArea |
| `isScrollControlled` | `bool` | `false` | Permitir que o modal seja rolavel |
| `showCloseButton` | `bool` | `false` | Mostrar um botao X para fechar |
| `headerPadding` | `EdgeInsets?` | null | Preenchimento quando o cabecalho esta presente |
| `backgroundColor` | `Color?` | null | Cor de fundo do modal |
| `showHandle` | `bool` | `true` | Mostrar a alca de arraste no topo |
| `closeButtonColor` | `Color?` | null | Cor de fundo do botao fechar |
| `closeButtonIconColor` | `Color?` | null | Cor do icone do botao fechar |
| `modalDecoration` | `BoxDecoration?` | null | Decoracao personalizada para o container do modal |
| `handleColor` | `Color?` | null | Cor da alca de arraste |

<div id="actions"></div>

### Acoes

Acoes sao botoes exibidos na parte inferior do modal.

**Acoes em linha** sao colocadas lado a lado, cada uma ocupando espaco igual:

``` dart
displayModal(
  context,
  child: Text("Are you sure?"),
  actionsRow: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

**Acoes em coluna** sao empilhadas verticalmente:

``` dart
displayModal(
  context,
  child: Text("Choose an option"),
  actionsColumn: [
    ElevatedButton(
      onPressed: () => Navigator.pop(context, true),
      child: Text("Yes"),
    ),
    TextButton(
      onPressed: () => Navigator.pop(context, false),
      child: Text("No"),
    ),
  ],
);
```

<div id="header"></div>

### Cabecalho

Adicione um cabecalho que fica acima do conteudo principal:

``` dart
displayModal(
  context,
  header: Container(
    padding: EdgeInsets.all(16),
    color: Colors.blue,
    child: Text(
      "Modal Title",
      style: TextStyle(color: Colors.white, fontSize: 18),
    ),
  ),
  child: Text("Modal content goes here"),
);
```

<div id="close-button"></div>

### Botao Fechar

Exiba um botao fechar no canto superior direito:

``` dart
displayModal(
  context,
  showCloseButton: true,
  closeButtonColor: Colors.grey.shade200,
  closeButtonIconColor: Colors.black,
  child: Padding(
    padding: EdgeInsets.all(24),
    child: Text("Content with close button"),
  ),
);
```

<div id="custom-decoration"></div>

### Decoracao Personalizada

Personalize a aparencia do container do modal:

``` dart
displayModal(
  context,
  backgroundColor: Colors.transparent,
  modalDecoration: BoxDecoration(
    color: Colors.white,
    borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
    boxShadow: [
      BoxShadow(color: Colors.black26, blurRadius: 10),
    ],
  ),
  handleColor: Colors.grey.shade400,
  child: Text("Custom styled modal"),
);
```

<div id="bottom-modal-sheet-style"></div>

## BottomModalSheetStyle

`BottomModalSheetStyle` configura a aparencia dos bottom sheet modals usados por pickers de formulario e outros componentes:

``` dart
BottomModalSheetStyle(
  backgroundColor: NyColor(light: Colors.white, dark: Colors.grey.shade900),
  barrierColor: NyColor(light: Colors.black54, dark: Colors.black87),
  useRootNavigator: false,
  titleStyle: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
  itemStyle: TextStyle(fontSize: 16),
  clearButtonStyle: TextStyle(color: Colors.red),
)
```

| Propriedade | Tipo | Descricao |
|-------------|------|-----------|
| `backgroundColor` | `NyColor?` | Cor de fundo do modal |
| `barrierColor` | `NyColor?` | Cor da sobreposicao atras do modal |
| `useRootNavigator` | `bool` | Usar navigator raiz (padrao: `false`) |
| `routeSettings` | `RouteSettings?` | Configuracoes de rota para o modal |
| `titleStyle` | `TextStyle?` | Estilo para texto de titulo |
| `itemStyle` | `TextStyle?` | Estilo para texto de itens da lista |
| `clearButtonStyle` | `TextStyle?` | Estilo para texto do botao limpar |

<div id="examples"></div>

## Exemplos

### Modal de Confirmacao

``` dart
static Future<bool?> showConfirm(
  BuildContext context, {
  required String message,
}) {
  return displayModal<bool>(
    context,
    child: Padding(
      padding: EdgeInsets.symmetric(vertical: 16),
      child: Text(message, textAlign: TextAlign.center),
    ),
    actionsRow: [
      ElevatedButton(
        onPressed: () => Navigator.pop(context, true),
        child: Text("Confirm"),
      ),
      TextButton(
        onPressed: () => Navigator.pop(context, false),
        child: Text("Cancel"),
      ),
    ],
  );
}

// Usage
bool? confirmed = await BottomSheetModal.showConfirm(
  context,
  message: "Delete this item?",
);
if (confirmed == true) {
  // delete the item
}
```

### Modal com Conteudo Rolavel

``` dart
displayModal(
  context,
  isScrollControlled: true,
  height: MediaQuery.of(context).size.height * 0.8,
  showCloseButton: true,
  header: Padding(
    padding: EdgeInsets.all(16),
    child: Text("Terms of Service", style: TextStyle(fontSize: 20)),
  ),
  child: SingleChildScrollView(
    child: Text(longTermsText),
  ),
);
```

### Folha de Acoes

``` dart
displayModal(
  context,
  showHandle: true,
  child: Text("Share via", style: TextStyle(fontSize: 18)),
  actionsColumn: [
    ListTile(
      leading: Icon(Icons.email),
      title: Text("Email"),
      onTap: () {
        Navigator.pop(context);
        shareViaEmail();
      },
    ),
    ListTile(
      leading: Icon(Icons.message),
      title: Text("Message"),
      onTap: () {
        Navigator.pop(context);
        shareViaMessage();
      },
    ),
    ListTile(
      leading: Icon(Icons.copy),
      title: Text("Copy Link"),
      onTap: () {
        Navigator.pop(context);
        copyLink();
      },
    ),
  ],
);
```
