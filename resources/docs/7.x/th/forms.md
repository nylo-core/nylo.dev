# Forms

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับฟอร์ม")
- เริ่มต้นใช้งาน
  - [การสร้างฟอร์ม](#creating-forms "การสร้างฟอร์ม")
  - [การแสดงฟอร์ม](#displaying-a-form "การแสดงฟอร์ม")
  - [การส่งฟอร์ม](#submitting-a-form "การส่งฟอร์ม")
- ประเภท Field
  - [Text Fields](#text-fields "Text Fields")
  - [Number Fields](#number-fields "Number Fields")
  - [Password Fields](#password-fields "Password Fields")
  - [Email Fields](#email-fields "Email Fields")
  - [URL Fields](#url-fields "URL Fields")
  - [Text Area Fields](#text-area-fields "Text Area Fields")
  - [Phone Number Fields](#phone-number-fields "Phone Number Fields")
  - [Capitalize Words](#capitalize-words-fields "Capitalize Words Fields")
  - [Capitalize Sentences](#capitalize-sentences-fields "Capitalize Sentences Fields")
  - [Date Fields](#date-fields "Date Fields")
  - [DateTime Fields](#datetime-fields "DateTime Fields")
  - [Masked Input Fields](#masked-input-fields "Masked Input Fields")
  - [Currency Fields](#currency-fields "Currency Fields")
  - [Checkbox Fields](#checkbox-fields "Checkbox Fields")
  - [Switch Box Fields](#switch-box-fields "Switch Box Fields")
  - [Picker Fields](#picker-fields "Picker Fields")
  - [Radio Fields](#radio-fields "Radio Fields")
  - [Chip Fields](#chip-fields "Chip Fields")
  - [Slider Fields](#slider-fields "Slider Fields")
  - [Range Slider Fields](#range-slider-fields "Range Slider Fields")
  - [Custom Fields](#custom-fields "Custom Fields")
  - [Widget Fields](#widget-fields "Widget Fields")
- [FormCollection](#form-collection "FormCollection")
- [การตรวจสอบฟอร์ม](#form-validation "การตรวจสอบฟอร์ม")
- [การจัดการข้อมูลฟอร์ม](#managing-form-data "การจัดการข้อมูลฟอร์ม")
  - [ข้อมูลเริ่มต้น](#initial-data "ข้อมูลเริ่มต้น")
  - [การตั้งค่า Field Values](#setting-field-values "การตั้งค่า Field Values")
  - [การตั้งค่า Field Options](#setting-field-options "การตั้งค่า Field Options")
  - [การอ่านข้อมูลฟอร์ม](#reading-form-data "การอ่านข้อมูลฟอร์ม")
  - [การล้างข้อมูล](#clearing-data "การล้างข้อมูล")
  - [การอัปเดต Fields](#finding-and-updating-fields "การอัปเดต Fields")
- [ปุ่มส่งฟอร์ม](#submit-button "ปุ่มส่งฟอร์ม")
- [เลย์เอาต์ฟอร์ม](#form-layout "เลย์เอาต์ฟอร์ม")
- [การมองเห็น Field](#field-visibility "การมองเห็น Field")
- [การจัดสไตล์ Field](#field-styling "การจัดสไตล์ Field")
- [NyFormWidget Static Methods](#ny-form-widget-static-methods "NyFormWidget Static Methods")
- [NyFormWidget Constructor Reference](#ny-form-widget-constructor-reference "NyFormWidget Constructor Reference")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [อ้างอิงประเภท Field ทั้งหมด](#all-field-types-reference "อ้างอิงประเภท Field ทั้งหมด")

<div id="introduction"></div>

## บทนำ

{{ config('app.name') }} v7 มีระบบฟอร์มที่สร้างขึ้นรอบ `NyFormWidget` คลาสฟอร์มของคุณ extend จาก `NyFormWidget` และ **เป็น** widget โดยตรง ไม่จำเป็นต้องมี wrapper แยกต่างหาก ฟอร์มรองรับการตรวจสอบในตัว ประเภท field หลากหลาย การจัดสไตล์ และการจัดการข้อมูล

``` dart
import 'package:nylo_framework/nylo_framework.dart';

// 1. กำหนดฟอร์ม
class LoginForm extends NyFormWidget {
  LoginForm({super.key, super.submitButton, super.onSubmit, super.onFailure});

  @override
  fields() => [
    Field.email("Email", validator: FormValidator.email()),
    Field.password("Password", validator: FormValidator.password()),
  ];

  static NyFormActions get actions => const NyFormActions('LoginForm');
}

// 2. แสดงและส่งฟอร์ม
LoginForm(
  submitButton: Button.primary(text: "Login"),
  onSubmit: (data) {
    print(data); // {email: "...", password: "..."}
  },
)
```


<div id="creating-forms"></div>

## การสร้างฟอร์ม

ใช้ Metro CLI เพื่อสร้างฟอร์มใหม่:

``` bash
metro make:form LoginForm
```

คำสั่งนี้จะสร้างไฟล์ `lib/app/forms/login_form.dart`:

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

ฟอร์ม extend จาก `NyFormWidget` และ override เมธอด `fields()` เพื่อกำหนด form fields แต่ละ field ใช้ named constructor เช่น `Field.text()`, `Field.email()` หรือ `Field.password()` getter `static NyFormActions get actions` มอบวิธีที่สะดวกในการโต้ตอบกับฟอร์มจากที่ใดก็ได้ในแอปของคุณ


<div id="displaying-a-form"></div>

## การแสดงฟอร์ม

เนื่องจากคลาสฟอร์มของคุณ extend จาก `NyFormWidget` มัน **เป็น** widget ใช้มันโดยตรงใน widget tree ของคุณ:

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

## การส่งฟอร์ม

มีสามวิธีในการส่งฟอร์ม:

### การใช้ onSubmit และ submitButton

ส่ง `onSubmit` และ `submitButton` เมื่อสร้างฟอร์ม {{ config('app.name') }} มีปุ่มสำเร็จรูปที่ทำงานเป็นปุ่มส่งฟอร์ม:

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

สไตล์ปุ่มที่มีให้ใช้: `Button.primary`, `Button.secondary`, `Button.outlined`, `Button.textOnly`, `Button.icon`, `Button.gradient`, `Button.rounded`, `Button.transparency`

### การใช้ NyFormActions

ใช้ getter `actions` เพื่อส่งฟอร์มจากที่ใดก็ได้:

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

### การใช้ NyFormWidget.submit() static method

ส่งฟอร์มโดยใช้ชื่อจากที่ใดก็ได้:

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

เมื่อส่งฟอร์ม ระบบจะตรวจสอบ fields ทั้งหมด หากถูกต้อง `onSuccess` จะถูกเรียกพร้อม `Map<String, dynamic>` ของข้อมูล field (keys เป็นเวอร์ชัน snake_case ของชื่อ field) หากไม่ถูกต้อง toast error จะแสดงโดยค่าเริ่มต้น และ `onFailure` จะถูกเรียกหากมีการกำหนดไว้


<div id="field-types"></div>

## ประเภท Field

{{ config('app.name') }} v7 มี 22 ประเภท field ผ่าน named constructors ในคลาส `Field` field constructors ทั้งหมดมีพารามิเตอร์ร่วมเหล่านี้:

| พารามิเตอร์ | ชนิด | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `key` | `String` | จำเป็น | ตัวระบุ field (positional) |
| `label` | `String?` | `null` | ป้ายกำกับแสดงผลแบบกำหนดเอง (ค่าเริ่มต้นเป็น key ในรูปแบบ title case) |
| `value` | `dynamic` | `null` | ค่าเริ่มต้น |
| `validator` | `FormValidator?` | `null` | กฎการตรวจสอบ |
| `autofocus` | `bool` | `false` | โฟกัสอัตโนมัติเมื่อโหลด |
| `dummyData` | `String?` | `null` | ข้อมูลทดสอบ/พัฒนา |
| `header` | `Widget?` | `null` | Widget ที่แสดงเหนือ field |
| `footer` | `Widget?` | `null` | Widget ที่แสดงใต้ field |
| `titleStyle` | `TextStyle?` | `null` | สไตล์ข้อความป้ายกำกับแบบกำหนดเอง |
| `hidden` | `bool` | `false` | ซ่อน field |
| `readOnly` | `bool?` | `null` | ทำให้ field เป็นแบบอ่านอย่างเดียว |
| `style` | `FieldStyle?` | แตกต่างกัน | การตั้งค่าสไตล์เฉพาะ field |
| `onChanged` | `Function(dynamic)?` | `null` | callback เมื่อค่าเปลี่ยน |

<div id="text-fields"></div>

### Text Fields

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

ชนิดสไตล์: `FieldStyleTextField`

<div id="number-fields"></div>

### Number Fields

``` dart
Field.number("Age")

// ตัวเลขทศนิยม
Field.number("Score", decimal: true)
```

พารามิเตอร์ `decimal` ควบคุมว่าอนุญาตให้ป้อนทศนิยมหรือไม่ ชนิดสไตล์: `FieldStyleTextField`

<div id="password-fields"></div>

### Password Fields

``` dart
Field.password("Password")

// พร้อมปุ่มสลับการมองเห็น
Field.password("Password", viewable: true)
```

พารามิเตอร์ `viewable` เพิ่มปุ่มสลับแสดง/ซ่อน ชนิดสไตล์: `FieldStyleTextField`

<div id="email-fields"></div>

### Email Fields

``` dart
Field.email("Email", validator: FormValidator.email())
```

ตั้งค่าประเภทแป้นพิมพ์อีเมลและกรองช่องว่างโดยอัตโนมัติ ชนิดสไตล์: `FieldStyleTextField`

<div id="url-fields"></div>

### URL Fields

``` dart
Field.url("Website", validator: FormValidator.url())
```

ตั้งค่าประเภทแป้นพิมพ์ URL ชนิดสไตล์: `FieldStyleTextField`

<div id="text-area-fields"></div>

### Text Area Fields

``` dart
Field.textArea("Description")
```

การป้อนข้อความหลายบรรทัด ชนิดสไตล์: `FieldStyleTextField`

<div id="phone-number-fields"></div>

### Phone Number Fields

``` dart
Field.phoneNumber("Mobile Phone")
```

จัดรูปแบบหมายเลขโทรศัพท์ที่ป้อนโดยอัตโนมัติ ชนิดสไตล์: `FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### Capitalize Words

``` dart
Field.capitalizeWords("Full Name")
```

ทำให้ตัวอักษรแรกของแต่ละคำเป็นตัวพิมพ์ใหญ่ ชนิดสไตล์: `FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### Capitalize Sentences

``` dart
Field.capitalizeSentences("Bio")
```

ทำให้ตัวอักษรแรกของแต่ละประโยคเป็นตัวพิมพ์ใหญ่ ชนิดสไตล์: `FieldStyleTextField`

<div id="date-fields"></div>

### Date Fields

``` dart
Field.date("Birthday")

Field.date("Birthday",
  dummyData: "1990-01-01",
  style: FieldStyleDateTimePicker(
    firstDate: DateTime(1900),
    lastDate: DateTime.now(),
  ),
)

// ปิดใช้งานปุ่มล้าง
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    canClear: false,
  ),
)

// ไอคอนล้างแบบกำหนดเอง
Field.date("Birthday",
  style: FieldStyleDateTimePicker(
    clearIconData: Icons.close,
  ),
)
```

เปิดตัวเลือกวันที่ โดยค่าเริ่มต้น field จะแสดงปุ่มล้างที่ให้ผู้ใช้รีเซ็ตค่า ตั้ง `canClear: false` เพื่อซ่อนมัน หรือใช้ `clearIconData` เพื่อเปลี่ยนไอคอน ชนิดสไตล์: `FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### DateTime Fields

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

เปิดตัวเลือกวันที่และเวลา คุณสามารถตั้ง `firstDate`, `lastDate`, `dateFormat` และ `initialPickerDateTime` เป็นพารามิเตอร์ระดับบนได้โดยตรง ชนิดสไตล์: `FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### Masked Input Fields

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // คืนค่าที่จัดรูปแบบแล้ว
)
```

อักขระ `#` ใน mask จะถูกแทนที่ด้วยข้อมูลที่ผู้ใช้ป้อน ใช้ `match` เพื่อควบคุมอักขระที่อนุญาต เมื่อ `maskReturnValue` เป็น `true` ค่าที่คืนจะรวมการจัดรูปแบบ mask ด้วย

<div id="currency-fields"></div>

### Currency Fields

``` dart
Field.currency("Price", currency: "usd")
```

พารามิเตอร์ `currency` เป็นค่าจำเป็นและกำหนดรูปแบบสกุลเงิน ชนิดสไตล์: `FieldStyleTextField`

<div id="checkbox-fields"></div>

### Checkbox Fields

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

ชนิดสไตล์: `FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### Switch Box Fields

``` dart
Field.switchBox("Enable Notifications")
```

ชนิดสไตล์: `FieldStyleSwitchBox`

<div id="picker-fields"></div>

### Picker Fields

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
)

// พร้อมคู่ key-value
Field.picker("Country",
  options: FormCollection.fromMap({
    "us": "United States",
    "ca": "Canada",
    "uk": "United Kingdom",
  }),
)
```

พารามิเตอร์ `options` ต้องการ `FormCollection` (ไม่ใช่ raw list) ดู [FormCollection](#form-collection) สำหรับรายละเอียด ชนิดสไตล์: `FieldStylePicker`

#### สไตล์ List Tile

คุณสามารถปรับแต่งลักษณะการแสดงรายการใน bottom sheet ของ picker โดยใช้ `PickerListTileStyle` โดยค่าเริ่มต้น bottom sheet จะแสดง tile ข้อความธรรมดา ใช้ preset ในตัวเพื่อเพิ่มตัวบ่งชี้การเลือก หรือให้ custom builder เต็มรูปแบบ

**สไตล์ Radio** -- แสดงไอคอนปุ่ม radio เป็น leading widget:

``` dart
Field.picker("Country",
  options: FormCollection.from(["United States", "Canada", "United Kingdom"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.radio(),
  ),
)

// พร้อมสีที่ใช้งานแบบกำหนดเอง
FieldStylePicker(
  listTileStyle: PickerListTileStyle.radio(activeColor: Colors.blue),
)
```

**สไตล์ Checkmark** -- แสดงไอคอนเครื่องหมายถูกเป็น trailing widget เมื่อเลือก:

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**Custom builder** -- ควบคุมเต็มรูปแบบสำหรับ widget ของแต่ละ tile:

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

สไตล์ preset ทั้งสองยังรองรับ `textStyle`, `selectedTextStyle`, `contentPadding`, `tileColor` และ `selectedTileColor`:

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

### Radio Fields

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

พารามิเตอร์ `options` ต้องการ `FormCollection` ชนิดสไตล์: `FieldStyleRadio`

<div id="chip-fields"></div>

### Chip Fields

``` dart
Field.chips("Tags",
  options: FormCollection.from(["Featured", "Sale", "New"]),
)

// พร้อมคู่ key-value
Field.chips("Engine Size",
  options: FormCollection.fromMap({
    "125": "125cc",
    "250": "250cc",
    "500": "500cc",
  }),
)
```

อนุญาตให้เลือกหลายรายการผ่าน chip widgets พารามิเตอร์ `options` ต้องการ `FormCollection` ชนิดสไตล์: `FieldStyleChip`

<div id="slider-fields"></div>

### Slider Fields

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

ชนิดสไตล์: `FieldStyleSlider` -- ตั้งค่า `min`, `max`, `divisions`, สี, การแสดงค่า และอื่นๆ

<div id="range-slider-fields"></div>

### Range Slider Fields

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

คืนค่าเป็นอ็อบเจกต์ `RangeValues` ชนิดสไตล์: `FieldStyleRangeSlider`

<div id="custom-fields"></div>

### Custom Fields

ใช้ `Field.custom()` เพื่อให้ stateful widget ของคุณเอง:

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

พารามิเตอร์ `child` ต้องการ widget ที่ extend จาก `NyFieldStatefulWidget` ซึ่งให้คุณควบคุมการแสดงผลและพฤติกรรมของ field ได้อย่างเต็มที่

<div id="widget-fields"></div>

### Widget Fields

ใช้ `Field.widget()` เพื่อฝัง widget ใดก็ได้ภายในฟอร์มโดยไม่ใช่ form field:

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

Widget fields ไม่มีส่วนร่วมในการตรวจสอบหรือการเก็บรวบรวมข้อมูล ใช้สำหรับเลย์เอาต์เท่านั้น


<div id="form-collection"></div>

## FormCollection

Picker, radio และ chip fields ต้องการ `FormCollection` สำหรับตัวเลือก `FormCollection` มอบอินเตอร์เฟสแบบรวมสำหรับการจัดการรูปแบบตัวเลือกต่างๆ

### การสร้าง FormCollection

``` dart
// จากรายการ strings (value และ label เหมือนกัน)
FormCollection.from(["Red", "Green", "Blue"])

// เหมือนด้านบน แบบชัดเจน
FormCollection.fromArray(["Red", "Green", "Blue"])

// จาก map (key = value, value = label)
FormCollection.fromMap({
  "us": "United States",
  "ca": "Canada",
})

// จากข้อมูลที่มีโครงสร้าง (มีประโยชน์สำหรับการตอบกลับ API)
FormCollection.fromKeyValue([
  {"value": "en", "label": "English"},
  {"value": "es", "label": "Spanish"},
])
```

`FormCollection.from()` ตรวจจับรูปแบบข้อมูลโดยอัตโนมัติและมอบหมายให้ constructor ที่เหมาะสม

### FormOption

แต่ละตัวเลือกใน `FormCollection` เป็น `FormOption` ที่มีคุณสมบัติ `value` และ `label`:

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### การค้นหาตัวเลือก

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

## การตรวจสอบฟอร์ม

เพิ่มการตรวจสอบให้กับ field ใดก็ได้โดยใช้พารามิเตอร์ `validator` กับ `FormValidator`:

``` dart
// Named constructor
Field.email("Email", validator: FormValidator.email())

// กฎแบบต่อเชื่อม
Field.text("Username",
  validator: FormValidator()
    .notEmpty()
    .minLength(3)
    .maxLength(20)
)

// รหัสผ่านพร้อมระดับความแข็งแกร่ง
Field.password("Password",
  validator: FormValidator.password(strength: 2)
)

// การตรวจสอบ boolean
Field.checkbox("Terms",
  validator: FormValidator.booleanTrue(message: "You must accept the terms")
)

// การตรวจสอบแบบ inline ที่กำหนดเอง
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

เมื่อส่งฟอร์ม validators ทั้งหมดจะถูกตรวจสอบ หากมีข้อผิดพลาด toast error จะแสดงข้อความข้อผิดพลาดแรกและ callback `onFailure` จะถูกเรียก

**ดูเพิ่มเติม:** [Validation](/docs/7.x/validation#validation-rules) สำหรับรายการ validators ทั้งหมดที่มีให้ใช้


<div id="managing-form-data"></div>

## การจัดการข้อมูลฟอร์ม

<div id="initial-data"></div>

### ข้อมูลเริ่มต้น

มีสองวิธีในการตั้งค่าข้อมูลเริ่มต้นให้กับฟอร์ม

**ตัวเลือกที่ 1: Override getter `init` ในคลาสฟอร์มของคุณ**

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

getter `init` สามารถคืนค่าเป็น `Map` แบบ synchronous หรือ `Future<Map>` แบบ async Keys จะถูกจับคู่กับชื่อ field โดยใช้การ normalize แบบ snake_case ดังนั้น `"First Name"` จะจับคู่กับ field ที่มี key `"First Name"`

#### การใช้ `define()` ใน init

ใช้ helper `define()` เมื่อคุณต้องตั้งค่า **options** (หรือทั้งค่าและ options) สำหรับ field ใน `init` ซึ่งมีประโยชน์สำหรับ picker, chip และ radio fields ที่ options มาจาก API หรือแหล่งข้อมูล async อื่นๆ

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

`define()` รับ named parameters สองตัว:

| พารามิเตอร์ | คำอธิบาย |
|-----------|-------------|
| `value` | ค่าเริ่มต้นสำหรับ field |
| `options` | ตัวเลือกสำหรับ picker, chip หรือ radio fields |

``` dart
// ตั้งค่าเฉพาะ options (ไม่มีค่าเริ่มต้น)
"Category": define(options: categories),

// ตั้งค่าเฉพาะค่าเริ่มต้น
"Price": define(value: "100"),

// ตั้งค่าทั้งค่าและ options
"Country": define(value: "us", options: countries),

// ค่าธรรมดายังคงใช้ได้สำหรับ fields ง่ายๆ
"Name": "John",
```

Options ที่ส่งให้ `define()` สามารถเป็น `List`, `Map` หรือ `FormCollection` จะถูกแปลงเป็น `FormCollection` โดยอัตโนมัติเมื่อนำไปใช้

**ตัวเลือกที่ 2: ส่ง `initialData` ให้กับ form widget**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### การตั้งค่า Field Values

ใช้ `NyFormActions` เพื่อตั้งค่า field values จากที่ใดก็ได้:

``` dart
// ตั้งค่า field เดียว
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### การตั้งค่า Field Options

อัปเดต options บน picker, chip หรือ radio fields แบบไดนามิก:

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### การอ่านข้อมูลฟอร์ม

ข้อมูลฟอร์มเข้าถึงได้ผ่าน callback `onSubmit` เมื่อส่งฟอร์ม หรือผ่าน callback `onChanged` สำหรับการอัปเดตแบบ real-time:

``` dart
EditAccountForm(
  onSubmit: (data) {
    // data เป็น Map<String, dynamic>
    // {first_name: "Jane", last_name: "Doe", email: "jane@example.com"}
    print(data);
  },
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```

<div id="clearing-data"></div>

### การล้างข้อมูล

``` dart
// ล้าง fields ทั้งหมด
EditAccountForm.actions.clear();

// ล้าง field เฉพาะ
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### การอัปเดต Fields

``` dart
// อัปเดตค่า field
EditAccountForm.actions.updateField("First Name", "Jane");

// รีเฟรช UI ของฟอร์ม
EditAccountForm.actions.refresh();

// รีเฟรช form fields (เรียก fields() ใหม่)
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## ปุ่มส่งฟอร์ม

ส่ง `submitButton` และ callback `onSubmit` เมื่อสร้างฟอร์ม:

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

`submitButton` จะแสดงด้านล่าง form fields โดยอัตโนมัติ คุณสามารถใช้สไตล์ปุ่มในตัวหรือ widget ที่กำหนดเองได้

คุณยังสามารถใช้ widget ใดก็ได้เป็นปุ่มส่งฟอร์มโดยส่งเป็น `footer`:

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

## เลย์เอาต์ฟอร์ม

วาง fields เคียงข้างกันโดยห่อด้วย `List`:

``` dart
@override
fields() => [
  // Field เดี่ยว (เต็มความกว้าง)
  Field.text("Title"),

  // สอง fields ในแถวเดียว
  [
    Field.text("First Name"),
    Field.text("Last Name"),
  ],

  // Field เดี่ยวอีกตัว
  Field.textArea("Bio"),

  // Slider และ range slider ในแถวเดียว
  [
    Field.slider("Rating", style: FieldStyleSlider(min: 0, max: 10)),
    Field.rangeSlider("Budget", style: FieldStyleRangeSlider(min: 0, max: 1000)),
  ],

  // ฝัง widget ที่ไม่ใช่ field
  Field.widget(child: Divider()),

  Field.email("Email"),
];
```

Fields ใน `List` จะถูกแสดงใน `Row` ด้วยความกว้าง `Expanded` เท่ากัน ระยะห่างระหว่าง fields ถูกควบคุมโดยพารามิเตอร์ `crossAxisSpacing` บน `NyFormWidget`


<div id="field-visibility"></div>

## การมองเห็น Field

แสดงหรือซ่อน fields โดยใช้เมธอด `hide()` และ `show()` บน `Field` คุณสามารถเข้าถึง fields ภายในคลาสฟอร์มหรือผ่าน callback `onChanged`:

``` dart
// ภายใน subclass ของ NyFormWidget หรือ callback onChanged
Field nameField = ...;

// ซ่อน field
nameField.hide();

// แสดง field
nameField.show();
```

Fields ที่ซ่อนจะไม่ถูกแสดงใน UI แต่ยังคงอยู่ในรายการ field ของฟอร์ม


<div id="field-styling"></div>

## การจัดสไตล์ Field

แต่ละประเภท field มีคลาสย่อย `FieldStyle` ที่สอดคล้องกันสำหรับการจัดสไตล์:

| ประเภท Field | คลาสสไตล์ |
|------------|-------------|
| Text, Email, Password, Number, URL, TextArea, PhoneNumber, Currency, Mask, CapitalizeWords, CapitalizeSentences | `FieldStyleTextField` |
| Date, DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

ส่ง style object ให้กับพารามิเตอร์ `style` ของ field ใดก็ได้:

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

## NyFormWidget Static Methods

`NyFormWidget` มี static methods สำหรับโต้ตอบกับฟอร์มตามชื่อจากที่ใดก็ได้ในแอปของคุณ:

| เมธอด | คำอธิบาย |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | ส่งฟอร์มตามชื่อ |
| `NyFormWidget.stateRefresh(name)` | รีเฟรช UI state ของฟอร์ม |
| `NyFormWidget.stateSetValue(name, key, value)` | ตั้งค่า field ตามชื่อฟอร์ม |
| `NyFormWidget.stateSetOptions(name, key, options)` | ตั้งค่า field options ตามชื่อฟอร์ม |
| `NyFormWidget.stateClearData(name)` | ล้าง fields ทั้งหมดตามชื่อฟอร์ม |
| `NyFormWidget.stateRefreshForm(name)` | รีเฟรช form fields (เรียก `fields()` ใหม่) |

``` dart
// ส่งฟอร์มชื่อ "LoginForm" จากที่ใดก็ได้
NyFormWidget.submit("LoginForm", onSuccess: (data) {
  print(data);
});

// อัปเดตค่า field จากระยะไกล
NyFormWidget.stateSetValue("LoginForm", "Email", "new@email.com");

// ล้างข้อมูลฟอร์มทั้งหมด
NyFormWidget.stateClearData("LoginForm");
```

> **เคล็ดลับ:** แนะนำให้ใช้ `NyFormActions` (ดูด้านล่าง) แทนการเรียก static methods เหล่านี้โดยตรง เนื่องจากกระชับกว่าและมีโอกาสเกิดข้อผิดพลาดน้อยกว่า


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget Constructor Reference

เมื่อ extend จาก `NyFormWidget` เหล่านี้คือ constructor parameters ที่คุณสามารถส่งได้:

``` dart
LoginForm(
  Key? key,
  double crossAxisSpacing = 10,  // ระยะห่างแนวนอนระหว่าง row fields
  double mainAxisSpacing = 10,   // ระยะห่างแนวตั้งระหว่าง fields
  Map<String, dynamic>? initialData, // ค่าเริ่มต้นของ field
  Function(Field field, dynamic value)? onChanged, // callback เมื่อ field เปลี่ยน
  Widget? header,                // Widget เหนือฟอร์ม
  Widget? submitButton,          // Widget ปุ่มส่งฟอร์ม
  Widget? footer,                // Widget ใต้ฟอร์ม
  double headerSpacing = 10,     // ระยะห่างหลัง header
  double submitButtonSpacing = 10, // ระยะห่างหลังปุ่มส่งฟอร์ม
  double footerSpacing = 10,     // ระยะห่างก่อน footer
  LoadingStyle? loadingStyle,    // สไตล์ตัวแสดงการโหลด
  bool locked = false,           // ทำให้ฟอร์มเป็นแบบอ่านอย่างเดียว
  Function(dynamic data)? onSubmit,   // เรียกพร้อมข้อมูลฟอร์มเมื่อตรวจสอบสำเร็จ
  Function(dynamic error)? onFailure, // เรียกพร้อมข้อผิดพลาดเมื่อตรวจสอบล้มเหลว
)
```

callback `onChanged` จะรับ `Field` ที่เปลี่ยนและค่าใหม่:

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` มอบวิธีที่สะดวกในการโต้ตอบกับฟอร์มจากที่ใดก็ได้ในแอปของคุณ กำหนดเป็น static getter ในคลาสฟอร์มของคุณ:

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

### Actions ที่มีให้ใช้

| เมธอด | คำอธิบาย |
|--------|-------------|
| `actions.updateField(key, value)` | ตั้งค่าของ field |
| `actions.clearField(key)` | ล้าง field เฉพาะ |
| `actions.clear()` | ล้าง fields ทั้งหมด |
| `actions.refresh()` | รีเฟรช UI state ของฟอร์ม |
| `actions.refreshForm()` | เรียก `fields()` ใหม่และสร้างใหม่ |
| `actions.setOptions(key, options)` | ตั้ง options บน picker/chip/radio fields |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | ส่งพร้อมการตรวจสอบ |

``` dart
// อัปเดตค่า field
LoginForm.actions.updateField("Email", "new@email.com");

// ล้างข้อมูลฟอร์มทั้งหมด
LoginForm.actions.clear();

// ส่งฟอร์ม
LoginForm.actions.submit(
  onSuccess: (data) {
    print(data);
  },
);
```

### NyFormWidget Overrides

เมธอดที่คุณสามารถ override ใน subclass ของ `NyFormWidget`:

| Override | คำอธิบาย |
|----------|-------------|
| `fields()` | กำหนด form fields (จำเป็น) |
| `init` | ให้ข้อมูลเริ่มต้น (sync หรือ async) |
| `onChange(field, data)` | จัดการการเปลี่ยนแปลง field ภายใน |


<div id="all-field-types-reference"></div>

## อ้างอิงประเภท Field ทั้งหมด

| Constructor | พารามิเตอร์หลัก | คำอธิบาย |
|-------------|----------------|-------------|
| `Field.text()` | -- | การป้อนข้อความมาตรฐาน |
| `Field.email()` | -- | การป้อนอีเมลพร้อมประเภทแป้นพิมพ์ |
| `Field.password()` | `viewable` | รหัสผ่านพร้อมสลับการมองเห็น (ตัวเลือก) |
| `Field.number()` | `decimal` | การป้อนตัวเลข ทศนิยม (ตัวเลือก) |
| `Field.currency()` | `currency` (จำเป็น) | การป้อนรูปแบบสกุลเงิน |
| `Field.capitalizeWords()` | -- | การป้อนข้อความแบบ title case |
| `Field.capitalizeSentences()` | -- | การป้อนข้อความแบบ sentence case |
| `Field.textArea()` | -- | การป้อนข้อความหลายบรรทัด |
| `Field.phoneNumber()` | -- | หมายเลขโทรศัพท์จัดรูปแบบอัตโนมัติ |
| `Field.url()` | -- | การป้อน URL พร้อมประเภทแป้นพิมพ์ |
| `Field.mask()` | `mask` (จำเป็น), `match`, `maskReturnValue` | การป้อนข้อความพร้อม mask |
| `Field.date()` | -- | ตัวเลือกวันที่ |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | ตัวเลือกวันที่และเวลา |
| `Field.checkbox()` | -- | Boolean checkbox |
| `Field.switchBox()` | -- | Boolean toggle switch |
| `Field.picker()` | `options` (จำเป็น `FormCollection`) | เลือกรายการเดียวจากรายการ |
| `Field.radio()` | `options` (จำเป็น `FormCollection`) | กลุ่มปุ่ม radio |
| `Field.chips()` | `options` (จำเป็น `FormCollection`) | เลือกหลายรายการด้วย chips |
| `Field.slider()` | -- | Slider ค่าเดียว |
| `Field.rangeSlider()` | -- | Slider ช่วงค่า |
| `Field.custom()` | `child` (จำเป็น `NyFieldStatefulWidget`) | Stateful widget ที่กำหนดเอง |
| `Field.widget()` | `child` (จำเป็น `Widget`) | ฝัง widget ใดก็ได้ (ไม่ใช่ field) |
