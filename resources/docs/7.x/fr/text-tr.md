# TextTr

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Interpolation de chaines](#string-interpolation "Interpolation de chaines")
- [Constructeurs avec style](#styled-constructors "Constructeurs avec style")
- [Parametres](#parameters "Parametres")


<div id="introduction"></div>

## Introduction

Le widget **TextTr** est un raccourci pratique autour du widget `Text` de Flutter qui traduit automatiquement son contenu en utilisant le systeme de localisation de {{ config('app.name') }}.

Au lieu d'ecrire :

``` dart
Text("hello_world".tr())
```

Vous pouvez ecrire :

``` dart
TextTr("hello_world")
```

Cela rend votre code plus propre et plus lisible, surtout lorsque vous travaillez avec de nombreuses chaines traduites.

<div id="basic-usage"></div>

## Utilisation de base

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    children: [
      TextTr("welcome_message"),

      TextTr(
        "app_title",
        style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        textAlign: TextAlign.center,
      ),
    ],
  );
}
```

Le widget recherchera la cle de traduction dans vos fichiers de langue (par exemple, `/lang/en.json`) :

``` json
{
  "welcome_message": "Welcome to our app!",
  "app_title": "My Application"
}
```

<div id="string-interpolation"></div>

## Interpolation de chaines

Utilisez le parametre `arguments` pour injecter des valeurs dynamiques dans vos traductions :

``` dart
TextTr(
  "greeting",
  arguments: {"name": "John"},
)
```

Dans votre fichier de langue :

``` json
{
  "greeting": "Hello, @{{name}}!"
}
```

Resultat : **Hello, John!**

### Arguments multiples

``` dart
TextTr(
  "order_summary",
  arguments: {
    "item": "Coffee",
    "quantity": "2",
    "total": "\$8.50",
  },
)
```

``` json
{
  "order_summary": "You ordered @{{quantity}}x @{{item}} for @{{total}}"
}
```

Resultat : **You ordered 2x Coffee for $8.50**

<div id="styled-constructors"></div>

## Constructeurs avec style

`TextTr` fournit des constructeurs nommes qui appliquent automatiquement les styles de texte de votre theme :

### displayLarge

``` dart
TextTr.displayLarge("page_title")
```

Utilise le style `Theme.of(context).textTheme.displayLarge`.

### headlineLarge

``` dart
TextTr.headlineLarge("section_heading")
```

Utilise le style `Theme.of(context).textTheme.headlineLarge`.

### bodyLarge

``` dart
TextTr.bodyLarge("paragraph_text")
```

Utilise le style `Theme.of(context).textTheme.bodyLarge`.

### labelLarge

``` dart
TextTr.labelLarge("button_label")
```

Utilise le style `Theme.of(context).textTheme.labelLarge`.

### Exemple avec les constructeurs avec style

``` dart
@override
Widget build(BuildContext context) {
  return Column(
    crossAxisAlignment: CrossAxisAlignment.start,
    children: [
      TextTr.headlineLarge("welcome_title"),
      SizedBox(height: 16),
      TextTr.bodyLarge(
        "welcome_description",
        arguments: {"app_name": "MyApp"},
      ),
      SizedBox(height: 24),
      TextTr.labelLarge("get_started"),
    ],
  );
}
```

<div id="parameters"></div>

## Parametres

`TextTr` prend en charge tous les parametres standard du widget `Text` :

| Parametre | Type | Description |
|-----------|------|-------------|
| `data` | `String` | La cle de traduction a rechercher |
| `arguments` | `Map<String, String>?` | Paires cle-valeur pour l'interpolation de chaines |
| `style` | `TextStyle?` | Style du texte |
| `textAlign` | `TextAlign?` | Alignement du texte |
| `maxLines` | `int?` | Nombre maximum de lignes |
| `overflow` | `TextOverflow?` | Gestion du debordement |
| `softWrap` | `bool?` | Retour a la ligne automatique |
| `textDirection` | `TextDirection?` | Direction du texte |
| `locale` | `Locale?` | Locale pour le rendu du texte |
| `semanticsLabel` | `String?` | Label d'accessibilite |

## Comparaison

| Approche | Code |
|----------|------|
| Traditionnelle | `Text("hello".tr())` |
| TextTr | `TextTr("hello")` |
| Avec arguments | `TextTr("hello", arguments: {"name": "John"})` |
| Avec style | `TextTr.headlineLarge("title")` |
