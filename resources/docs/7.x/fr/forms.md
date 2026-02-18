# Formulaires

---

<a name="section-1"></a>
- [Introduction](#introduction "Introduction")
- Premiers pas
  - [Creer un formulaire](#creating-forms "Creer un formulaire")
  - [Afficher un formulaire](#displaying-a-form "Afficher un formulaire")
  - [Soumettre un formulaire](#submitting-a-form "Soumettre un formulaire")
- Types de champs
  - [Champs texte](#text-fields "Champs texte")
  - [Champs numeriques](#number-fields "Champs numeriques")
  - [Champs mot de passe](#password-fields "Champs mot de passe")
  - [Champs email](#email-fields "Champs email")
  - [Champs URL](#url-fields "Champs URL")
  - [Champs zone de texte](#text-area-fields "Champs zone de texte")
  - [Champs numero de telephone](#phone-number-fields "Champs numero de telephone")
  - [Majuscule aux mots](#capitalize-words-fields "Majuscule aux mots")
  - [Majuscule aux phrases](#capitalize-sentences-fields "Majuscule aux phrases")
  - [Champs date](#date-fields "Champs date")
  - [Champs date et heure](#datetime-fields "Champs date et heure")
  - [Champs saisie masquee](#masked-input-fields "Champs saisie masquee")
  - [Champs devise](#currency-fields "Champs devise")
  - [Champs case a cocher](#checkbox-fields "Champs case a cocher")
  - [Champs interrupteur](#switch-box-fields "Champs interrupteur")
  - [Champs selecteur](#picker-fields "Champs selecteur")
  - [Champs bouton radio](#radio-fields "Champs bouton radio")
  - [Champs chip](#chip-fields "Champs chip")
  - [Champs curseur](#slider-fields "Champs curseur")
  - [Champs curseur de plage](#range-slider-fields "Champs curseur de plage")
  - [Champs personnalises](#custom-fields "Champs personnalises")
  - [Champs widget](#widget-fields "Champs widget")
- [FormCollection](#form-collection "FormCollection")
- [Validation de formulaire](#form-validation "Validation de formulaire")
- [Gestion des donnees du formulaire](#managing-form-data "Gestion des donnees du formulaire")
  - [Donnees initiales](#initial-data "Donnees initiales")
  - [Definir les valeurs des champs](#setting-field-values "Definir les valeurs des champs")
  - [Definir les options des champs](#setting-field-options "Definir les options des champs")
  - [Lire les donnees du formulaire](#reading-form-data "Lire les donnees du formulaire")
  - [Effacer les donnees](#clearing-data "Effacer les donnees")
  - [Mettre a jour les champs](#finding-and-updating-fields "Mettre a jour les champs")
- [Bouton de soumission](#submit-button "Bouton de soumission")
- [Disposition du formulaire](#form-layout "Disposition du formulaire")
- [Visibilite des champs](#field-visibility "Visibilite des champs")
- [Style des champs](#field-styling "Style des champs")
- [Methodes statiques NyFormWidget](#ny-form-widget-static-methods "Methodes statiques NyFormWidget")
- [Reference du constructeur NyFormWidget](#ny-form-widget-constructor-reference "Reference du constructeur NyFormWidget")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [Reference de tous les types de champs](#all-field-types-reference "Reference de tous les types de champs")

<div id="introduction"></div>

## Introduction

{{ config('app.name') }} v7 fournit un systeme de formulaires construit autour de `NyFormWidget`. Votre classe de formulaire etend `NyFormWidget` et **est** le widget -- aucun wrapper separe n'est necessaire. Les formulaires prennent en charge la validation integree, de nombreux types de champs, le style et la gestion des donnees.

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. Define a form
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. Display and submit it
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## Creer un formulaire

Utilisez le Metro CLI pour creer un nouveau formulaire :

``` bash
metro make:form LoginForm
```

Cela cree `lib/app/forms/login_form.dart` :

``` dart
import 'package:nylo_framework/nylo_framework.dart';

class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

Les formulaires etendent `NyFormWidget` et surchargent la methode `fields()` pour definir les champs du formulaire. Chaque champ utilise un constructeur nomme comme `Field.text()`, `Field.email()` ou `Field.password()`. Le getter `static NyFormActions get actions` offre un moyen pratique d'interagir avec le formulaire depuis n'importe ou dans votre application.


<div id="displaying-a-form"></div>

## Afficher un formulaire

Puisque votre classe de formulaire etend `NyFormWidget`, elle **est** le widget. Utilisez-le directement dans votre arbre de widgets :

``` dart
@override
Widget view(BuildContext context) {
  return Scaffold(
    body: SafeArea(
      child: LoginForm(
        submitButton: Button.primary(text: "Submit"),
        onSubmit: (data) {
          print(data);
        },
      ),
    ),
  );
}
```


<div id="submitting-a-form"></div>

## Soumettre un formulaire

Il existe trois facons de soumettre un formulaire :

### Utiliser onSubmit et submitButton

Passez `onSubmit` et un `submitButton` lors de la construction du formulaire. {{ config('app.name') }} fournit des boutons preconstruits qui fonctionnent comme boutons de soumission :

``` dart
LoginForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Styles de boutons disponibles : `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`.

### Utiliser NyFormActions

Utilisez le getter `actions` pour soumettre depuis n'importe ou :

``` dart
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

### Utiliser la methode statique NyFormWidget.submit()

Soumettez un formulaire par son nom depuis n'importe ou :

``` dart
NyFormWidget.submit("LoginForm",
  onSuccess: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
  showToastError: true,
);
```

Lors de la soumission, le formulaire valide tous les champs. Si la validation reussit, `onSuccess` est appele avec un `Map<String, dynamic>` des donnees du formulaire (les cles sont des versions snake_case des noms de champs). Si la validation echoue, une erreur toast est affichee par defaut et `onFailure` est appele si fourni.


<div id="field-types"></div>

## Types de champs

{{ config('app.name') }} v7 fournit 22 types de champs via des constructeurs nommes sur la classe `Field`. Tous les constructeurs de champs partagent ces parametres communs :

| Parametre | Type | Defaut | Description |
|-----------|------|--------|-------------|
| `key` | `String` | Requis | L'identifiant du champ (positionnel) |
| `label` | `String?` | `null` | Libelle d'affichage personnalise (par defaut la cle en titre) |
| `value` | `dynamic` | `null` | Valeur initiale |
| `validator` | `FormValidator?` | `null` | Regles de validation |
| `autofocus` | `bool` | `false` | Focus automatique au chargement |
| `dummyData` | `String?` | `null` | Donnees de test/developpement |
| `header` | `Widget?` | `null` | Widget affiche au-dessus du champ |
| `footer` | `Widget?` | `null` | Widget affiche en dessous du champ |
| `titleStyle` | `TextStyle?` | `null` | Style de texte personnalise pour le libelle |
| `hidden` | `bool` | `false` | Masquer le champ |
| `readOnly` | `bool?` | `null` | Rendre le champ en lecture seule |
| `style` | `FieldStyle?` | Variable | Configuration de style specifique au champ |
| `onChanged` | `Function(dynamic)?` | `null` | Callback de changement de valeur |

<div id="text-fields"></div>

### Champs texte

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

Type de style : `FieldStyleTextField`

<div id="number-fields"></div>

### Champs numeriques

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

Le parametre `decimal` controle si la saisie decimale est autorisee. Type de style : `FieldStyleTextField`

<div id="password-fields"></div>

### Champs mot de passe

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

Le parametre `viewable` ajoute un bouton afficher/masquer. Type de style : `FieldStyleTextField`

<div id="email-fields"></div>

### Champs email

``` dart
Field.email("Email", validator: FormValidator.email())
```

Configure automatiquement le type de clavier email et filtre les espaces. Type de style : `FieldStyleTextField`

<div id="url-fields"></div>

### Champs URL

``` dart
Field.url("Website", validator: FormValidator.url())
```

Configure le type de clavier URL. Type de style : `FieldStyleTextField`

<div id="text-area-fields"></div>

### Champs zone de texte

``` dart
Field.textArea("Description")
```

Saisie de texte multiligne. Type de style : `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Champs numero de telephone

``` dart
Field.phoneNumber("Mobile Phone")
```

Formate automatiquement la saisie du numero de telephone. Type de style : `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Majuscule aux mots

``` dart
Field.capitalizeWords("Full Name")
```

Met en majuscule la premiere lettre de chaque mot. Type de style : `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Majuscule aux phrases

``` dart
Field.capitalizeSentences("Bio")
```

Met en majuscule la premiere lettre de chaque phrase. Type de style : `FieldStyleTextField`

<div id="date-fields"></div>

### Champs date

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// Disable the clear button
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// Custom clear icon
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

Ouvre un selecteur de date. Par defaut, le champ affiche un bouton d'effacement qui permet aux utilisateurs de reinitialiser la valeur. Definissez `canClear: false` pour le masquer, ou utilisez `clearIconData` pour changer l'icone. Type de style : `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### Champs date et heure

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

Ouvre un selecteur de date et d'heure. Vous pouvez definir `firstDate`, `lastDate`, `dateFormat` et `initialPickerDateTime` directement comme parametres de premier niveau. Type de style : `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Champs saisie masquee

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

Le caractere `#` dans le masque est remplace par la saisie de l'utilisateur. Utilisez `match` pour controler les caracteres autorises. Lorsque `maskReturnValue` est `true`, la valeur retournee inclut le formatage du masque.

<div id="currency-fields"></div>

### Champs devise

``` dart
Field.currency("Price", currency: "usd")
```

Le parametre `currency` est requis et determine le format de la devise. Type de style : `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Champs case a cocher

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

Type de style : `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Champs interrupteur

``` dart
Field.switchBox("Enable Notifications")
```

Type de style : `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Champs selecteur

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// With key-value pairs
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

Le parametre `options` necessite un `FormCollection` (pas une liste brute). Voir [FormCollection](#form-collection) pour plus de details. Type de style : `FieldStylePicker`

#### Styles de tuile de liste

Vous pouvez personnaliser l'apparence des elements dans la feuille inferieure du selecteur en utilisant `PickerListTileStyle`. Par defaut, la feuille inferieure affiche des tuiles de texte simple. Utilisez les presets integres pour ajouter des indicateurs de selection, ou fournissez un constructeur entierement personnalise.

**Style radio** — affiche une icone de bouton radio comme widget principal :

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// With a custom active color
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**Style coche** — affiche une icone de coche comme widget secondaire lorsque selectionne :

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Constructeur personnalise** — controle total sur le widget de chaque tuile :

``` dart
Field.picker("Color",
  options: FormCollection.from(["Red", "Green", "Blue"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.custom(
      builder: (option, isSelected, onTap) {
        return ListTile(
          title: Text(option.label,
            style: TextStyle(
              fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
            ),
          ),
          trailing: isSelected ? Icon(Icons.check_circle) : null,
          onTap: onTap,
        );
      },
    ),
  ),
)
```

Les deux styles predefinis supportent egalement `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` et `selectedTileColor` :

``` dart
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(
    activeColor: Colors.blue,
    textStyle: TextStyle(fontSize: 16),
    selectedTextStyle: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
    selectedTileColor: Colors.blue.shade50,
  ),
)
```

<div id="radio-fields"></div>

### Champs bouton radio

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

Le parametre `options` necessite un `FormCollection`. Type de style : `FieldStyleRadio`

<div id="chip-fields"></div>

### Champs chip

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// With key-value pairs
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

Permet la selection multiple via des widgets chip. Le parametre `options` necessite un `FormCollection`. Type de style : `FieldStyleChip`

<div id="slider-fields"></div>

### Champs curseur

``` dart
Field.slider("Rating",
  label: "Rate us",
  validator: FormValidator.minValue(4, message: "Rating must be at least 4"),
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Type de style : `FieldStyleSlider` -- configurez `min`, `max`, `divisions`, les couleurs, l'affichage de la valeur, et plus encore.

<div id="range-slider-fields"></div>

### Champs curseur de plage

``` dart
Field.rangeSlider("Price Range",
  style: FieldStyleRangeSlider(
    min: 0,
    max: 1000,
    divisions: 20,
    activeColor: Colors.blue,
    inactiveColor: Colors.grey,
  ),
)
```

Retourne un objet `RangeValues`. Type de style : `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Champs personnalises

Utilisez `Field.custom()` pour fournir votre propre widget avec etat :

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

Le parametre `child` necessite un widget qui etend `NyFieldStatefulWidget`. Cela vous donne un controle total sur le rendu et le comportement du champ.

<div id="widget-fields"></div>

### Champs widget

Utilisez `Field.widget()` pour integrer n'importe quel widget dans le formulaire sans qu'il soit un champ de formulaire :

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Les champs widget ne participent pas a la validation ni a la collecte de donnees. Ils sont purement destines a la mise en page.


<div id="form-collection"></div>

## FormCollection

Les champs selecteur, radio et chip necessitent un `FormCollection` pour leurs options. `FormCollection` fournit une interface unifiee pour gerer differents formats d'options.

### Creer un FormCollection

``` dart
// From a list of strings (value and label are the same)
FormCollection.from(["Red", "Green", "Blue"])

// Same as above, explicit
FormCollection.fromArray(["Red", "Green", "Blue"])

// From a map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// From structured data (useful for API responses)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` detecte automatiquement le format des donnees et delegue au constructeur approprie.

### FormOption

Chaque option dans un `FormCollection` est un `FormOption` avec les proprietes `value` et `label` :

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### Interroger les options

``` dart
FormCollection options = FormCollection.fromMap({"us": "United States", "ca": "Canada"});

options.getByValue("us");          // FormOption(value: us, label: United States)
options.getLabelByValue("us");     // "United States"
options.containsValue("ca");      // true
options.searchByLabel("can");      // [FormOption(value: ca, label: Canada)]
options.values;                    // ["us", "ca"]
options.labels;                    // ["United States", "Canada"]
```


<div id="form-validation"></div>

## Validation de formulaire

Ajoutez la validation a n'importe quel champ en utilisant le parametre `validator` avec `FormValidator` :

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// Chained rules
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// Password with strength level
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// Boolean validation
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// Custom inline validation
Field.number("Age",
  validator: FormValidator.custom(
    message: "Age must be between 18 and 100",
    validate: (data) {
      int? age = int.tryParse(data.toString());
      return age != null && age >= 18 && age <= 100;
    },
  )
)
```

Lors de la soumission d'un formulaire, tous les validateurs sont verifies. Si l'un echoue, une erreur toast affiche le premier message d'erreur et le callback `onFailure` est appele.

**Voir aussi :** Pour une liste complete des validateurs disponibles, consultez la page [Validation](/docs/7.x/validation#validation-rules).


<div id="managing-form-data"></div>

## Gestion des donnees du formulaire

<div id="initial-data"></div>

### Donnees initiales

Il existe deux facons de definir les donnees initiales d'un formulaire.

**Option 1 : Surcharger le getter `init` dans votre classe de formulaire**

``` dart
class EditAccountForm extends NyFormWidget {
  EditAccountForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final user = await api<ApiService>((request) => request.getUserData());

    return {
      "First Name": user?.firstName,
      "Last Name": user?.lastName,
    };
  };

  @override
  fields() => [
    Field.text("First Name"),
    Field.text("Last Name"),
  ];

  static NyFormActions get actions => const NyFormActions('EditAccountForm');
}
```

Le getter `init` peut retourner soit un `Map` synchrone, soit un `Future<Map>` asynchrone. Les cles sont associees aux noms de champs en utilisant la normalisation snake_case, donc `"First Name"` correspond a un champ avec la cle `"First Name"`.

#### Utilisation de `define()` dans init

Utilisez le helper `define()` lorsque vous devez definir des **options** (ou a la fois une valeur et des options) pour un champ dans `init`. Ceci est utile pour les champs picker, chip et radio dont les options proviennent d'une API ou d'une autre source asynchrone.

``` dart
class CreatePostForm extends NyFormWidget {
  CreatePostForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  Function()? get init => () async {
    final categories = await api<ApiService>((request) => request.getCategories());

    return {
      "Title": "My Post",
      "Category": define(options: categories),
    };
  };

  @override
  fields() => [
    Field.text("Title"),
    Field.picker("Category", options: FormCollection.from([])),
  ];

  static NyFormActions get actions => const NyFormActions('CreatePostForm');
}
```

`define()` accepte deux parametres nommes :

| Parametre | Description |
|-----------|-------------|
| `value` | La valeur initiale du champ |
| `options` | Les options pour les champs picker, chip ou radio |

``` dart
// Set only options (no initial value)
"Category": define(options: categories),

// Set only an initial value
"Price": define(value: "100"),

// Set both a value and options
"Country": define(value: "us", options: countries),

// Plain values still work for simple fields
"Name": "John",
```

Les options passees a `define()` peuvent etre une `List`, `Map` ou `FormCollection`. Elles sont automatiquement converties en `FormCollection` lors de l'application.

**Option 2 : Passer `initialData` au widget du formulaire**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### Definir les valeurs des champs

Utilisez `NyFormActions` pour definir les valeurs des champs depuis n'importe ou :

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### Definir les options des champs

Mettez a jour les options des champs selecteur, chip ou radio de maniere dynamique :

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### Lire les donnees du formulaire

Les donnees du formulaire sont accessibles via le callback `onSubmit` lors de la soumission du formulaire, ou via le callback `onChanged` pour les mises a jour en temps reel :

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data is a Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### Effacer les donnees

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### Mettre a jour les champs

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## Bouton de soumission

Passez un `submitButton` et un callback `onSubmit` lors de la construction du formulaire :

``` dart
UserInfoForm(
  submitButton: Button.primary(text: "Submit"),
  onSubmit: (data) {
    print(data);
  },
  onFailure: (errors) {
    print(errors.first.rule.getMessage());
  },
)
```

Le `submitButton` est automatiquement affiche sous les champs du formulaire. Vous pouvez utiliser n'importe lequel des styles de boutons integres ou un widget personnalise.

Vous pouvez egalement utiliser n'importe quel widget comme bouton de soumission en le passant comme `footer` :

``` dart
UserInfoForm(
  onSubmit: (data) {
    print(data);
  },
  footer: ElevatedButton(
    onPressed: () {
      UserInfoForm.actions.submit(
        onSuccess: (data) {
          print(data);
        },
      );
    },
    child: Text("Submit"),
  ),
)
```


<div id="form-layout"></div>

## Disposition du formulaire

Placez les champs cote a cote en les enveloppant dans une `List` :

``` dart
@override
fields() => [
  // Single field (full width)
  Field.text("Title"),

  // Two fields in a row
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Another single field
  Field.textArea("Bio"),

  // Slider and range slider in a row
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // Embed a non-field widget
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Les champs dans une `List` sont affiches dans un `Row` avec des largeurs `Expanded` egales. L'espacement entre les champs est controle par le parametre `crossAxisSpacing` sur `NyFormWidget`.


<div id="field-visibility"></div>

## Visibilite des champs

Affichez ou masquez les champs par programmation en utilisant les methodes `hide()` et `show()` sur `Field`. Vous pouvez acceder aux champs a l'interieur de votre classe de formulaire ou via le callback `onChanged` :

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

Les champs masques ne sont pas affiches dans l'interface utilisateur mais existent toujours dans la liste des champs du formulaire.


<div id="field-styling"></div>

## Style des champs

Chaque type de champ possede une sous-classe `FieldStyle` correspondante pour le style :

| Type de champ | Classe de style |
|---------------|-----------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

Passez un objet de style au parametre `style` de n'importe quel champ :

``` dart
Field.text("Name",
  style: FieldStyleTextField(
    filled: true,
    fillColor: Colors.grey.shade100,
    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
    contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
    prefixIcon: Icon(Icons.person),
  ),
)

Field.slider("Rating",
  style: FieldStyleSlider(
    min: 0,
    max: 10,
    divisions: 10,
    activeColor: Colors.blue,
    showValue: true,
  ),
)

Field.chips("Tags",
  options: FormCollection.from(["Sale", "New", "Featured"]),
  style: FieldStyleChip(
    selectedColor: Colors.blue,
    checkmarkColor: Colors.white,
    spacing: 8.0,
    runSpacing: 8.0,
  ),
)
```


<div id="ny-form-widget-static-methods"></div>

## Methodes statiques NyFormWidget

`NyFormWidget` fournit des methodes statiques pour interagir avec les formulaires par nom depuis n'importe ou dans votre application :

| Methode | Description |
|---------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | Soumettre un formulaire par son nom |
| `NyFormWidget.stateRefresh(name)` | Rafraichir l'etat de l'interface du formulaire |
| `NyFormWidget.stateSetValue(name, key, value)` | Definir la valeur d'un champ par nom de formulaire |
| `NyFormWidget.stateSetOptions(name, key, options)` | Definir les options d'un champ par nom de formulaire |
| `NyFormWidget.stateClearData(name)` | Effacer tous les champs par nom de formulaire |
| `NyFormWidget.stateRefreshForm(name)` | Rafraichir les champs du formulaire (re-appelle `fields()`) |

``` dart
// Submit a form named "LoginForm" from anywhere
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// Update a field value remotely
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// Clear all form data
NyFormWidget.stateClearData("LoginForm");
```

> **Conseil :** Preferez utiliser `NyFormActions` (voir ci-dessous) au lieu d'appeler ces methodes statiques directement -- c'est plus concis et moins sujet aux erreurs.


<div id="ny-form-widget-constructor-reference"></div>

## Reference du constructeur NyFormWidget

Lors de l'extension de `NyFormWidget`, voici les parametres du constructeur que vous pouvez passer :

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // Horizontal spacing between row fields
  double mainAxisSpacing = 10,   // Vertical spacing between fields
  Map<String, dynamic>? initialData, // Initial field values
  Function(Field field, dynamic value)? onChanged, // Field change callback
  Widget? header,                // Widget above the form
  Widget? submitButton,          // Submit button widget
  Widget? footer,                // Widget below the form
  double headerSpacing = 10,     // Spacing after header
  double submitButtonSpacing = 10, // Spacing after submit button
  double footerSpacing = 10,     // Spacing before footer
  LoadingStyle? loadingStyle,    // Loading indicator style
  bool locked = false,           // Makes form read-only
  Function(dynamic data)? onSubmit,   // Called with form data on successful validation
  Function(dynamic error)? onFailure, // Called with errors on failed validation
)
```

Le callback `onChanged` recoit le `Field` qui a change et sa nouvelle valeur :

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` fournit un moyen pratique d'interagir avec un formulaire depuis n'importe ou dans votre application. Definissez-le comme un getter statique sur votre classe de formulaire :

``` dart
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}
```

### Actions disponibles

| Methode | Description |
|---------|-------------|
| `actions.updateField(key, value)` | Definir la valeur d'un champ |
| `actions.clearField(key)` | Effacer un champ specifique |
| `actions.clear()` | Effacer tous les champs |
| `actions.refresh()` | Rafraichir l'etat de l'interface du formulaire |
| `actions.refreshForm()` | Re-appeler `fields()` et reconstruire |
| `actions.setOptions(key, options)` | Definir les options sur les champs selecteur/chip/radio |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | Soumettre avec validation |

``` dart
// Update a field value
LoginForm.actions.updateField("Email", "new@email.com");

// Clear all form data
LoginForm.actions.clear();

// Submit the form
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### Surcharges NyFormWidget

Methodes que vous pouvez surcharger dans votre sous-classe `NyFormWidget` :

| Surcharge | Description |
|----------|-------------|
| `fields()` | Definir les champs du formulaire (requis) |
| `init` | Fournir les donnees initiales (synchrone ou asynchrone) |
| `onChange(field, data)` | Gerer les changements de champs en interne |


<div id="all-field-types-reference"></div>

## Reference de tous les types de champs

| Constructeur | Parametres cles | Description |
|--------------|-----------------|-------------|
| `Field.text()` | -- | Saisie de texte standard |
| `Field.email()` | -- | Saisie email avec type de clavier |
| `Field.password()` | `viewable` | Mot de passe avec bouton de visibilite optionnel |
| `Field.number()` | `decimal` | Saisie numerique, decimale optionnelle |
| `Field.currency()` | `currency` (requis) | Saisie formatee en devise |
| `Field.capitalizeWords()` | -- | Saisie de texte en casse titre |
| `Field.capitalizeSentences()` | -- | Saisie de texte en casse phrase |
| `Field.textArea()` | -- | Saisie de texte multiligne |
| `Field.phoneNumber()` | -- | Numero de telephone formate automatiquement |
| `Field.url()` | -- | Saisie URL avec type de clavier |
| `Field.mask()` | `mask` (requis), `match`, `maskReturnValue` | Saisie de texte masquee |
| `Field.date()` | -- | Selecteur de date |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | Selecteur de date et heure |
| `Field.checkbox()` | -- | Case a cocher booleenne |
| `Field.switchBox()` | -- | Interrupteur a bascule booleen |
| `Field.picker()` | `options` (`FormCollection` requis) | Selection unique dans une liste |
| `Field.radio()` | `options` (`FormCollection` requis) | Groupe de boutons radio |
| `Field.chips()` | `options` (`FormCollection` requis) | Chips a selection multiple |
| `Field.slider()` | -- | Curseur a valeur unique |
| `Field.rangeSlider()` | -- | Curseur a plage de valeurs |
| `Field.custom()` | `child` (`NyFieldStatefulWidget` requis) | Widget avec etat personnalise |
| `Field.widget()` | `child` (`Widget` requis) | Integrer n'importe quel widget (non-champ) |

