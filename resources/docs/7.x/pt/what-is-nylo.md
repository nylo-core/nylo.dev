# What is {{ config('app.name') }}?

---

<a name="section-1"></a>
- [Introdução](#introduction "Introdução")
- Desenvolvimento de Apps
    - [Novo no Flutter?](#new-to-flutter "Novo no Flutter?")
    - [Manutenção e Cronograma de Lançamentos](#maintenance-and-release-schedule "Manutenção e Cronograma de Lançamentos")
- Créditos
    - [Dependências do Framework](#framework-dependencies "Dependências do Framework")
    - [Contribuidores](#contributors "Contribuidores")


<div id="introduction"></div>

## Introdução

{{ config('app.name') }} é um micro-framework para Flutter projetado para ajudar a simplificar o desenvolvimento de apps. Ele fornece um boilerplate estruturado com essenciais pré-configurados para que você possa focar em construir as funcionalidades do seu app em vez de configurar infraestrutura.

Por padrão, {{ config('app.name') }} inclui:

- **Routing** - Gerenciamento de rotas simples e declarativo com guards e deep linking
- **Networking** - Serviços de API com Dio, interceptors e morphing de resposta
- **State Management** - Estado reativo com NyState e atualizações de estado globais
- **Localization** - Suporte multi-idioma com arquivos de tradução JSON
- **Themes** - Modo claro/escuro com alternância de tema
- **Local Storage** - Armazenamento seguro com Backpack e NyStorage
- **Forms** - Manipulação de formulários com validação e tipos de campos
- **Push Notifications** - Suporte a notificações locais e remotas
- **CLI Tool (Metro)** - Gere páginas, controllers, models e mais

<div id="new-to-flutter"></div>

## Novo no Flutter?

Se você é novo no Flutter, comece com os recursos oficiais:

- <a href="https://flutter.dev" target="_BLANK">Documentação do Flutter</a> - Guias completos e referência de API
- <a href="https://www.youtube.com/c/flutterdev" target="_BLANK">Canal do Flutter no YouTube</a> - Tutoriais e atualizações
- <a href="https://docs.flutter.dev/cookbook" target="_BLANK">Flutter Cookbook</a> - Receitas práticas para tarefas comuns

Uma vez que você esteja confortável com os conceitos básicos do Flutter, {{ config('app.name') }} será intuitivo pois ele se baseia em padrões padrão do Flutter.


<div id="maintenance-and-release-schedule"></div>

## Manutenção e Cronograma de Lançamentos

{{ config('app.name') }} segue o <a href="https://semver.org" target="_BLANK">Versionamento Semântico</a>:

- **Lançamentos maiores** (7.x → 8.x) - Uma vez por ano para mudanças incompatíveis
- **Lançamentos menores** (7.0 → 7.1) - Novas funcionalidades, compatíveis com versões anteriores
- **Lançamentos de correção** (7.0.0 → 7.0.1) - Correções de bugs e melhorias menores

Correções de bugs e patches de segurança são tratados prontamente através dos repositórios no GitHub.


<div id="framework-dependencies"></div>

## Dependências do Framework

{{ config('app.name') }} v7 é construído sobre estes pacotes open source:

### Dependências Principais

| Pacote | Finalidade |
|---------|---------|
| <a href="https://pub.dev/packages/dio" target="_BLANK">dio</a> | Cliente HTTP para requisições de API |
| <a href="https://pub.dev/packages/flutter_secure_storage" target="_BLANK">flutter_secure_storage</a> | Armazenamento local seguro |
| <a href="https://pub.dev/packages/intl" target="_BLANK">intl</a> | Internacionalização e formatação |
| <a href="https://pub.dev/packages/rxdart" target="_BLANK">rxdart</a> | Extensões reativas para streams |
| <a href="https://pub.dev/packages/equatable" target="_BLANK">equatable</a> | Igualdade de valor para objetos |

### UI & Widgets

| Pacote | Finalidade |
|---------|---------|
| <a href="https://pub.dev/packages/skeletonizer" target="_BLANK">skeletonizer</a> | Efeitos de carregamento skeleton |
| <a href="https://pub.dev/packages/flutter_styled_toast" target="_BLANK">flutter_styled_toast</a> | Notificações toast |
| <a href="https://pub.dev/packages/pull_to_refresh_flutter3" target="_BLANK">pull_to_refresh_flutter3</a> | Funcionalidade pull-to-refresh |
| <a href="https://pub.dev/packages/flutter_staggered_grid_view" target="_BLANK">flutter_staggered_grid_view</a> | Layouts de grid escalonado |
| <a href="https://pub.dev/packages/date_field" target="_BLANK">date_field</a> | Campos de seleção de data |

### Notificações & Conectividade

| Pacote | Finalidade |
|---------|---------|
| <a href="https://pub.dev/packages/flutter_local_notifications" target="_BLANK">flutter_local_notifications</a> | Notificações push locais |
| <a href="https://pub.dev/packages/connectivity_plus" target="_BLANK">connectivity_plus</a> | Status de conectividade de rede |
| <a href="https://pub.dev/packages/app_badge_plus" target="_BLANK">app_badge_plus</a> | Badges do ícone do app |

### Utilitários

| Pacote | Finalidade |
|---------|---------|
| <a href="https://pub.dev/packages/url_launcher" target="_BLANK">url_launcher</a> | Abrir URLs e apps |
| <a href="https://pub.dev/packages/recase" target="_BLANK">recase</a> | Conversão de case de strings |
| <a href="https://pub.dev/packages/uuid" target="_BLANK">uuid</a> | Geração de UUID |
| <a href="https://pub.dev/packages/path_provider" target="_BLANK">path_provider</a> | Caminhos do sistema de arquivos |
| <a href="https://pub.dev/packages/mask_text_input_formatter" target="_BLANK">mask_text_input_formatter</a> | Máscara de entrada |


<div id="contributors"></div>

## Contribuidores

Obrigado a todos que contribuíram para o {{ config('app.name') }}! Se você contribuiu, entre em contato via <a href="mailto:support@nylo.dev">support@nylo.dev</a> para ser adicionado aqui.

- <a href="https://github.com/agordn52" target="_BLANK">Anthony Gordon</a> (Criador)
- <a href="https://github.com/Abdulrasheed1729" target="_BLANK">Abdulrasheed1729</a>
- <a href="https://github.com/Rashid-Khabeer" target="_BLANK">Rashid-Khabeer</a>
- <a href="https://github.com/lpdevit" target="_BLANK">lpdevit</a>
- <a href="https://github.com/youssefKadaouiAbbassi" target="_BLANK">youssefKadaouiAbbassi</a>
- <a href="https://github.com/jeremyhalin" target="_BLANK">jeremyhalin</a>
- <a href="https://github.com/abdulawalarif" target="_BLANK">abdulawalarif</a>
- <a href="https://github.com/lepresk" target="_BLANK">lepresk</a>
- <a href="https://github.com/joshua1996" target="_BLANK">joshua1996</a>
- <a href="https://github.com/stensonb" target="_BLANK">stensonb</a>
- <a href="https://github.com/ruwiss" target="_BLANK">ruwiss</a>
- <a href="https://github.com/rytisder" target="_BLANK">rytisder</a>
- <a href="https://github.com/israelins85" target="_BLANK">israelins85</a>
- <a href="https://github.com/voytech-net" target="_BLANK">voytech-net</a>
- <a href="https://github.com/sadobass" target="_BLANK">sadobass</a>
