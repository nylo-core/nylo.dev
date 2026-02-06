# InputField

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- [Utilisation de base](#basic-usage "Utilisation de base")
- [Validation](#validation "Validation")
- Variantes
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [Masquage de saisie](#masking "Masquage de saisie")
- [En-tete et pied de page](#header-footer "En-tete et pied de page")
- [Saisie effacable](#clearable "Saisie effacable")
- [Gestion d'etat](#state-management "Gestion d'etat")
- [Parametres](#parameters "Parametres")


<div id="introduction"></div>

## Introduction

Le widget **InputField** est le champ de texte ameliore de {{ config('app.name') }} avec un support integre pour :

- La validation avec des messages d'erreur personnalisables
- Le bouton de visibilite du mot de passe
- Le masquage de saisie (numeros de telephone, cartes de credit, etc.)
- Les widgets d'en-tete et de pied de page
- La saisie effacable
- L'integration de la gestion d'etat
- Les donnees factices pour le developpement

<div id="basic-usage"></div>

## Utilisation de base

``` dart
final TextEditingController _controller = TextEditingController();

@override
Widget build(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: Padding(
        padding: EdgeInsets.all(16),
        child: InputField(
          controller: _controller,
          labelText: "Username",
          hintText: "Enter your username",
        ),
      ),
    ),
  );
}
```

<div id="validation"></div>

## Validation

Utilisez le parametre `formValidator` pour ajouter des regles de validation :

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

Le champ se validera lorsque l'utilisateur deplacera le focus hors de celui-ci.

### Gestionnaire de validation personnalise

Gerez les erreurs de validation par programmation :

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  formValidator: FormValidator.rule("not_empty|min:3"),
  handleValidationError: (FormValidationResult result) {
    if (!result.isValid) {
      print("Error: ${result.getFirstErrorMessage()}");
    }
  },
)
```

Consultez toutes les regles de validation disponibles dans la documentation de [Validation](/docs/7.x/validation).

<div id="password"></div>

## InputField.password

Un champ de mot de passe preconfigure avec texte masque et bouton de visibilite :

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### Personnaliser la visibilite du mot de passe

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

Un champ d'email preconfigure avec clavier email et autofocus :

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

Met automatiquement en majuscule la premiere lettre de chaque mot :

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## Masquage de saisie

Appliquez des masques de saisie pour les donnees formatees comme les numeros de telephone ou les cartes de credit :

``` dart
// Phone number mask
InputField(
  controller: _phoneController,
  labelText: "Phone Number",
  mask: "(###) ###-####",
  maskMatch: r'[0-9]',
  maskedReturnValue: false, // Returns unmasked value: 1234567890
)

// Credit card mask
InputField(
  controller: _cardController,
  labelText: "Card Number",
  mask: "#### #### #### ####",
  maskMatch: r'[0-9]',
  maskedReturnValue: true, // Returns masked value: 1234 5678 9012 3456
)
```

| Parametre | Description |
|-----------|-------------|
| `mask` | Le motif de masque utilisant `#` comme espace reserve |
| `maskMatch` | Motif regex pour les caracteres de saisie valides |
| `maskedReturnValue` | Si vrai, retourne la valeur formatee ; si faux, retourne la saisie brute |

<div id="header-footer"></div>

## En-tete et pied de page

Ajoutez des widgets au-dessus ou en dessous du champ de saisie :

``` dart
InputField(
  controller: _controller,
  labelText: "Bio",
  header: Text(
    "Tell us about yourself",
    style: TextStyle(fontWeight: FontWeight.bold),
  ),
  footer: Text(
    "Max 200 characters",
    style: TextStyle(color: Colors.grey, fontSize: 12),
  ),
  maxLength: 200,
)
```

<div id="clearable"></div>

## Saisie effacable

Ajoutez un bouton d'effacement pour vider rapidement le champ :

``` dart
InputField(
  controller: _searchController,
  labelText: "Search",
  clearable: true,
  clearIcon: Icon(Icons.close, size: 20), // Custom clear icon
  onChanged: (value) {
    // Handle search
  },
)
```

<div id="state-management"></div>

## Gestion d'etat

Donnez a votre champ de saisie un nom d'etat pour le controler par programmation :

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### Actions d'etat

``` dart
// Clear the field
InputField.stateActions("username_field").clear();

// Set a value
updateState("username_field", data: {
  "action": "setValue",
  "value": "new_value"
});
```

<div id="parameters"></div>

## Parametres

### Parametres courants

| Parametre | Type | Par defaut | Description |
|-----------|------|------------|-------------|
| `controller` | `TextEditingController` | requis | Controle le texte en cours d'edition |
| `labelText` | `String?` | - | Label affiche au-dessus du champ |
| `hintText` | `String?` | - | Texte de remplacement |
| `formValidator` | `FormValidator?` | - | Regles de validation |
| `validateOnFocusChange` | `bool` | `true` | Valider lors du changement de focus |
| `obscureText` | `bool` | `false` | Masquer la saisie (pour les mots de passe) |
| `keyboardType` | `TextInputType` | `text` | Type de clavier |
| `autoFocus` | `bool` | `false` | Auto-focus a la construction |
| `readOnly` | `bool` | `false` | Rendre le champ en lecture seule |
| `enabled` | `bool?` | - | Activer/desactiver le champ |
| `maxLines` | `int?` | `1` | Nombre maximum de lignes |
| `maxLength` | `int?` | - | Nombre maximum de caracteres |

### Parametres de style

| Parametre | Type | Description |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | Couleur d'arriere-plan du champ |
| `borderRadius` | `BorderRadius?` | Rayon de bordure |
| `border` | `InputBorder?` | Bordure par defaut |
| `focusedBorder` | `InputBorder?` | Bordure avec le focus |
| `enabledBorder` | `InputBorder?` | Bordure active |
| `contentPadding` | `EdgeInsetsGeometry?` | Espacement interne |
| `style` | `TextStyle?` | Style du texte |
| `labelStyle` | `TextStyle?` | Style du texte du label |
| `hintStyle` | `TextStyle?` | Style du texte de remplacement |
| `prefixIcon` | `Widget?` | Icone avant la saisie |

### Parametres de masquage

| Parametre | Type | Description |
|-----------|------|-------------|
| `mask` | `String?` | Motif de masque (par exemple, "###-####") |
| `maskMatch` | `String?` | Regex pour les caracteres valides |
| `maskedReturnValue` | `bool?` | Retourner la valeur masquee ou brute |

### Parametres de fonctionnalite

| Parametre | Type | Description |
|-----------|------|-------------|
| `header` | `Widget?` | Widget au-dessus du champ |
| `footer` | `Widget?` | Widget en dessous du champ |
| `clearable` | `bool?` | Afficher le bouton d'effacement |
| `clearIcon` | `Widget?` | Icone d'effacement personnalisee |
| `passwordVisible` | `bool?` | Afficher le bouton de visibilite du mot de passe |
| `passwordViewable` | `bool?` | Permettre le basculement de visibilite du mot de passe |
| `dummyData` | `String?` | Donnees factices pour le developpement |
| `stateName` | `String?` | Nom pour la gestion d'etat |
| `onChanged` | `Function(String)?` | Appele lorsque la valeur change |
