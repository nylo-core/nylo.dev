# FutureWidget

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Personnaliser l'etat de chargement](#customizing-loading "Personnaliser l'etat de chargement")
    - [Style de chargement normal](#normal-loading "Style de chargement normal")
    - [Style de chargement Skeletonizer](#skeletonizer-loading "Style de chargement Skeletonizer")
    - [Sans chargement](#no-loading "Sans chargement")
- [Gestion des erreurs](#error-handling "Gestion des erreurs")


<div id="introduction"></div>

## Introduction

Le **FutureWidget** est un moyen simple de rendre des `Future` dans vos projets {{ config('app.name') }}. Il encapsule le `FutureBuilder` de Flutter et fournit une API plus propre avec des etats de chargement integres.

Lorsque votre Future est en cours, il affichera un chargeur. Une fois le Future termine, les donnees sont retournees via le callback `child`.

<div id="basic-usage"></div>

## Utilisation de base

Voici un exemple simple d'utilisation du `FutureWidget` :

``` dart
// A Future that takes 3 seconds to complete
Future<String> _findUserName() async {
  await sleep(3); // wait for 3 seconds
  return "John Doe";
}

// Use the FutureWidget
@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
       child: FutureWidget<String>(
         future: _findUserName(),
         child: (context, data) {
           // data = "John Doe"
           return Text(data!);
         },
       ),
    ),
  );
}
```

Le widget gerera automatiquement l'etat de chargement pour vos utilisateurs jusqu'a ce que le Future soit termine.

<div id="customizing-loading"></div>

## Personnaliser l'etat de chargement

Vous pouvez personnaliser l'apparence de l'etat de chargement en utilisant le parametre `loadingStyle`.

<div id="normal-loading"></div>

### Style de chargement normal

Utilisez `LoadingStyle.normal()` pour afficher un widget de chargement standard. Vous pouvez optionnellement fournir un widget enfant personnalise :

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.normal(
    child: Text("Loading..."), // custom loading widget
  ),
)
```

Si aucun enfant n'est fourni, le chargeur par defaut de l'application {{ config('app.name') }} sera affiche.

<div id="skeletonizer-loading"></div>

### Style de chargement Skeletonizer

Utilisez `LoadingStyle.skeletonizer()` pour afficher un effet de chargement en squelette. C'est ideal pour afficher une interface de remplacement qui correspond a la disposition de votre contenu :

``` dart
FutureWidget<User>(
  future: _fetchUser(),
  child: (context, user) {
    return UserCard(user: user!);
  },
  loadingStyle: LoadingStyle.skeletonizer(
    child: UserCard(user: User.placeholder()), // skeleton placeholder
    effect: SkeletonizerEffect.shimmer, // shimmer, pulse, or solid
  ),
)
```

Effets de squelette disponibles :
- `SkeletonizerEffect.shimmer` - Effet de miroitement anime (par defaut)
- `SkeletonizerEffect.pulse` - Effet d'animation pulsante
- `SkeletonizerEffect.solid` - Effet de couleur solide

<div id="no-loading"></div>

### Sans chargement

Utilisez `LoadingStyle.none()` pour masquer completement l'indicateur de chargement :

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  loadingStyle: LoadingStyle.none(),
)
```

<div id="error-handling"></div>

## Gestion des erreurs

Vous pouvez gerer les erreurs de votre Future en utilisant le callback `onError` :

``` dart
FutureWidget<String>(
  future: _fetchData(),
  child: (context, data) {
    return Text(data!);
  },
  onError: (AsyncSnapshot snapshot) {
    print(snapshot.error.toString());
    return Text("Something went wrong");
  },
)
```

Si aucun callback `onError` n'est fourni et qu'une erreur se produit, un `SizedBox.shrink()` vide sera affiche.

### Parametres

| Parametre | Type | Description |
|-----------|------|-------------|
| `future` | `Future<T>?` | Le Future a attendre |
| `child` | `Widget Function(BuildContext, T?)` | Fonction de construction appelee lorsque le Future se termine |
| `loadingStyle` | `LoadingStyle?` | Personnaliser l'indicateur de chargement |
| `onError` | `Widget Function(AsyncSnapshot)?` | Fonction de construction appelee lorsque le Future a une erreur |
