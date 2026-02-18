# 表单

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- 入门
  - [创建表单](#creating-forms "创建表单")
  - [显示表单](#displaying-a-form "显示表单")
  - [提交表单](#submitting-a-form "提交表单")
- 字段类型
  - [文本字段](#text-fields "文本字段")
  - [数字字段](#number-fields "数字字段")
  - [密码字段](#password-fields "密码字段")
  - [邮箱字段](#email-fields "邮箱字段")
  - [URL 字段](#url-fields "URL 字段")
  - [文本区域字段](#text-area-fields "文本区域字段")
  - [电话号码字段](#phone-number-fields "电话号码字段")
  - [单词首字母大写](#capitalize-words-fields "单词首字母大写")
  - [句首字母大写](#capitalize-sentences-fields "句首字母大写")
  - [日期字段](#date-fields "日期字段")
  - [日期时间字段](#datetime-fields "日期时间字段")
  - [掩码输入字段](#masked-input-fields "掩码输入字段")
  - [货币字段](#currency-fields "货币字段")
  - [复选框字段](#checkbox-fields "复选框字段")
  - [开关字段](#switch-box-fields "开关字段")
  - [选择器字段](#picker-fields "选择器字段")
  - [单选按钮字段](#radio-fields "单选按钮字段")
  - [标签字段](#chip-fields "标签字段")
  - [滑块字段](#slider-fields "滑块字段")
  - [范围滑块字段](#range-slider-fields "范围滑块字段")
  - [自定义字段](#custom-fields "自定义字段")
  - [组件字段](#widget-fields "组件字段")
- [FormCollection](#form-collection "FormCollection")
- [表单验证](#form-validation "表单验证")
- [管理表单数据](#managing-form-data "管理表单数据")
  - [初始数据](#initial-data "初始数据")
  - [设置字段值](#setting-field-values "设置字段值")
  - [设置字段选项](#setting-field-options "设置字段选项")
  - [读取表单数据](#reading-form-data "读取表单数据")
  - [清除数据](#clearing-data "清除数据")
  - [更新字段](#finding-and-updating-fields "更新字段")
- [提交按钮](#submit-button "提交按钮")
- [表单布局](#form-layout "表单布局")
- [字段可见性](#field-visibility "字段可见性")
- [字段样式](#field-styling "字段样式")
- [NyFormWidget 静态方法](#ny-form-widget-static-methods "NyFormWidget 静态方法")
- [NyFormWidget 构造函数参考](#ny-form-widget-constructor-reference "NyFormWidget 构造函数参考")
- [NyFormActions](#ny-form-actions "NyFormActions")
- [所有字段类型参考](#all-field-types-reference "所有字段类型参考")

<div id="introduction"></div>

## 简介

{{ config('app.name') }} v7 提供了围绕 `NyFormWidget` 构建的表单系统。您的表单类继承 `NyFormWidget` 并且**本身就是**组件 -- 不需要单独的包装器。表单支持内置验证、多种字段类型、样式和数据管理。

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

## 创建表单

使用 Metro CLI 创建新表单：

``` bash
metro make:form LoginForm
```

这会创建 `lib/app/forms/login_form.dart`：

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

表单继承 `NyFormWidget` 并覆盖 `fields()` 方法来定义表单字段。每个字段使用命名构造函数，如 `Field.text()`、`Field.email()` 或 `Field.password()`。`static NyFormActions get actions` getter 提供了从应用的任何位置与表单交互的便捷方式。


<div id="displaying-a-form"></div>

## 显示表单

由于您的表单类继承了 `NyFormWidget`，它**本身就是**组件。直接在您的组件树中使用它：

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

## 提交表单

有三种方式提交表单：

### 使用 onSubmit 和 submitButton

构建表单时传递 `onSubmit` 和 `submitButton`。{{ config('app.name') }} 提供可作为提交按钮使用的预构建按钮：

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

可用的按钮样式：`Button.primary`、`Button.secondary`、`Button.outlined`、`Button.textOnly`、`Button.icon`、`Button.gradient`、`Button.rounded`、`Button.transparency`。

### 使用 NyFormActions

使用 `actions` getter 从任何位置提交：

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

### 使用 NyFormWidget.submit() 静态方法

通过名称从任何位置提交表单：

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

提交时，表单会验证所有字段。如果有效，`onSuccess` 将被调用，并传入字段数据的 `Map<String, dynamic>`（键为字段名的 snake_case 版本）。如果无效，默认会显示 toast 错误，并调用 `onFailure`（如已提供）。


<div id="field-types"></div>

## 字段类型

{{ config('app.name') }} v7 通过 `Field` 类上的命名构造函数提供 22 种字段类型。所有字段构造函数共享这些通用参数：

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `key` | `String` | 必需 | 字段标识符（位置参数） |
| `label` | `String?` | `null` | 自定义显示标签（默认为标题格式的 key） |
| `value` | `dynamic` | `null` | 初始值 |
| `validator` | `FormValidator?` | `null` | 验证规则 |
| `autofocus` | `bool` | `false` | 加载时自动聚焦 |
| `dummyData` | `String?` | `null` | 测试/开发数据 |
| `header` | `Widget?` | `null` | 字段上方显示的组件 |
| `footer` | `Widget?` | `null` | 字段下方显示的组件 |
| `titleStyle` | `TextStyle?` | `null` | 自定义标签文字样式 |
| `hidden` | `bool` | `false` | 隐藏字段 |
| `readOnly` | `bool?` | `null` | 使字段只读 |
| `style` | `FieldStyle?` | 因类型而异 | 字段特定样式配置 |
| `onChanged` | `Function(dynamic)?` | `null` | 值变更回调 |

<div id="text-fields"></div>

### 文本字段

``` dart
Field.text("Name")

Field.text("Name",
  value: "John",
  validator: FormValidator.notEmpty(),
  autofocus: true,
)
```

样式类型：`FieldStyleTextField`

<div id="number-fields"></div>

### 数字字段

``` dart
Field.number("Age")

// Decimal numbers
Field.number("Score", decimal: true)
```

`decimal` 参数控制是否允许小数输入。样式类型：`FieldStyleTextField`

<div id="password-fields"></div>

### 密码字段

``` dart
Field.password("Password")

// With visibility toggle
Field.password("Password", viewable: true)
```

`viewable` 参数添加显示/隐藏切换。样式类型：`FieldStyleTextField`

<div id="email-fields"></div>

### 邮箱字段

``` dart
Field.email("Email", validator: FormValidator.email())
```

自动设置邮箱键盘类型并过滤空格。样式类型：`FieldStyleTextField`

<div id="url-fields"></div>

### URL 字段

``` dart
Field.url("Website", validator: FormValidator.url())
```

设置 URL 键盘类型。样式类型：`FieldStyleTextField`

<div id="text-area-fields"></div>

### 文本区域字段

``` dart
Field.textArea("Description")
```

多行文本输入。样式类型：`FieldStyleTextField`

<div id="phone-number-fields"></div>

### 电话号码字段

``` dart
Field.phoneNumber("Mobile Phone")
```

自动格式化电话号码输入。样式类型：`FieldStyleTextField`

<div id="capitalize-words-fields"></div>

### 单词首字母大写

``` dart
Field.capitalizeWords("Full Name")
```

将每个单词的首字母大写。样式类型：`FieldStyleTextField`

<div id="capitalize-sentences-fields"></div>

### 句首字母大写

``` dart
Field.capitalizeSentences("Bio")
```

将每个句子的首字母大写。样式类型：`FieldStyleTextField`

<div id="date-fields"></div>

### 日期字段

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

打开日期选择器。默认情况下，字段显示一个清除按钮，允许用户重置值。设置 `canClear: false` 隐藏它，或使用 `clearIconData` 更改图标。样式类型：`FieldStyleDateTimePicker`

<div id="datetime-fields"></div>

### 日期时间字段

``` dart
Field.datetime("Check in Date")

Field.datetime("Appointment",
  firstDate: DateTime(2025),
  lastDate: DateTime(2030),
  dateFormat: DateFormat('yyyy-MM-dd HH:mm'),
  initialPickerDateTime: DateTime.now(),
)
```

打开日期和时间选择器。您可以直接将 `firstDate`、`lastDate`、`dateFormat` 和 `initialPickerDateTime` 设置为顶级参数。样式类型：`FieldStyleDateTimePicker`

<div id="masked-input-fields"></div>

### 掩码输入字段

``` dart
Field.mask("Phone", mask: "(###) ###-####")

Field.mask("Credit Card", mask: "#### #### #### ####")

Field.mask("Custom Code",
  mask: "AA-####",
  match: r'[\w\d]',
  maskReturnValue: true, // Returns the formatted value
)
```

掩码中的 `#` 字符由用户输入替换。使用 `match` 控制允许的字符。当 `maskReturnValue` 为 `true` 时，返回的值包含掩码格式。

<div id="currency-fields"></div>

### 货币字段

``` dart
Field.currency("Price", currency: "usd")
```

`currency` 参数为必需，决定货币格式。样式类型：`FieldStyleTextField`

<div id="checkbox-fields"></div>

### 复选框字段

``` dart
Field.checkbox("Accept Terms")

Field.checkbox("Agree to terms",
  header: Text("Terms and conditions"),
  footer: Text("You must agree to continue."),
  validator: FormValidator.booleanTrue(message: "You must accept the terms"),
)
```

样式类型：`FieldStyleCheckbox`

<div id="switch-box-fields"></div>

### 开关字段

``` dart
Field.switchBox("Enable Notifications")
```

样式类型：`FieldStyleSwitchBox`

<div id="picker-fields"></div>

### 选择器字段

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

`options` 参数需要一个 `FormCollection`（不是原始列表）。查看 [FormCollection](#form-collection) 了解详情。样式类型：`FieldStylePicker`

#### 列表磁贴样式

您可以使用 `PickerListTileStyle` 自定义选择器底部弹窗中项目的显示方式。默认情况下，底部弹窗显示纯文本磁贴。使用内置预设添加选择指示器，或提供完全自定义的构建器。

**单选样式** — 显示单选按钮图标作为前导组件：

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

**勾选样式** — 选中时显示勾选图标作为尾随组件：

``` dart
Field.picker("Category",
  options: FormCollection.from(["Electronics", "Clothing", "Books"]),
  style: FieldStylePicker(
    listTileStyle: PickerListTileStyle.checkmark(activeColor: Colors.green),
  ),
)
```

**自定义构建器** — 完全控制每个磁贴的组件：

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

两种预设样式还支持 `textStyle`、`selectedTextStyle`、`contentPadding`、`tileColor` 和 `selectedTileColor`：

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

### 单选按钮字段

``` dart
Field.radio("Newsletter",
  options: FormCollection.fromMap({
    "Yes": "Yes, I want to receive newsletters",
    "No": "No, I do not want to receive newsletters",
  }),
)
```

`options` 参数需要一个 `FormCollection`。样式类型：`FieldStyleRadio`

<div id="chip-fields"></div>

### 标签字段

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

通过标签组件允许多选。`options` 参数需要一个 `FormCollection`。样式类型：`FieldStyleChip`

<div id="slider-fields"></div>

### 滑块字段

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

样式类型：`FieldStyleSlider` -- 配置 `min`、`max`、`divisions`、颜色、值显示等。

<div id="range-slider-fields"></div>

### 范围滑块字段

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

返回一个 `RangeValues` 对象。样式类型：`FieldStyleRangeSlider`

<div id="custom-fields"></div>

### 自定义字段

使用 `Field.custom()` 提供您自己的有状态组件：

``` dart
Field.custom("My Field",
  child: MyCustomFieldWidget(),
)
```

`child` 参数需要一个继承 `NyFieldStatefulWidget` 的组件。这让您完全控制字段的渲染和行为。

<div id="widget-fields"></div>

### 组件字段

使用 `Field.widget()` 在表单中嵌入任何组件，而不将其作为表单字段：

``` dart
Field.widget(child: Divider())

Field.widget(child: Text("Section Header", style: TextStyle(fontSize: 18)))
```

组件字段不参与验证或数据收集。它们纯粹用于布局。


<div id="form-collection"></div>

## FormCollection

选择器、单选和标签字段的选项需要 `FormCollection`。`FormCollection` 为处理不同的选项格式提供了统一的接口。

### 创建 FormCollection

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

`FormCollection.from()` 自动检测数据格式并委托给适当的构造函数。

### FormOption

`FormCollection` 中的每个选项都是一个具有 `value` 和 `label` 属性的 `FormOption`：

``` dart
FormOption option = FormOption(value: "us", label: "United States");
print(option.value); // "us"
print(option.label); // "United States"
```

### 查询选项

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

## 表单验证

使用 `validator` 参数和 `FormValidator` 为任何字段添加验证：

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

提交表单时，所有验证器都会被检查。如果任何验证失败，将显示第一个错误消息的 toast，并调用 `onFailure` 回调。

**另请参阅：** 有关可用验证器的完整列表，请查看[验证](/docs/7.x/validation#validation-rules)页面。


<div id="managing-form-data"></div>

## 管理表单数据

<div id="initial-data"></div>

### 初始数据

有两种方式设置表单的初始数据。

**选项 1：在表单类中覆盖 `init` getter**

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

`init` getter 可以返回同步的 `Map` 或异步的 `Future<Map>`。键使用 snake_case 标准化与字段名匹配，因此 `"First Name"` 映射到键为 `"First Name"` 的字段。

#### 在 init 中使用 `define()`

当您需要在 `init` 中为字段设置**选项**（或同时设置值和选项）时，使用 `define()` 辅助方法。这对于选项来自 API 或其他异步源的选择器、芯片和单选字段非常有用。

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

`define()` 接受两个命名参数：

| 参数 | 描述 |
|-----------|-------------|
| `value` | 字段的初始值 |
| `options` | 选择器、芯片或单选字段的选项 |

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

传递给 `define()` 的选项可以是 `List`、`Map` 或 `FormCollection`。应用时会自动转换为 `FormCollection`。

**选项 2：将 `initialData` 传递给表单组件**

``` dart
EditAccountForm(
  initialData: {
    "first_name": "John",
    "last_name": "Doe",
  },
)
```

<div id="setting-field-values"></div>

### 设置字段值

使用 `NyFormActions` 从任何位置设置字段值：

``` dart
// Set a single field value
EditAccountForm.actions.updateField("First Name", "Jane");
```

<div id="setting-field-options"></div>

### 设置字段选项

动态更新选择器、标签或单选字段的选项：

``` dart
EditAccountForm.actions.setOptions("Category", FormCollection.from(["New Option 1", "New Option 2"]));
```

<div id="reading-form-data"></div>

### 读取表单数据

表单数据通过提交时的 `onSubmit` 回调访问，或通过 `onChanged` 回调进行实时更新：

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

### 清除数据

``` dart
// Clear all fields
EditAccountForm.actions.clear();

// Clear a specific field
EditAccountForm.actions.clearField("First Name");
```


<div id="finding-and-updating-fields"></div>

### 更新字段

``` dart
// Update a field value
EditAccountForm.actions.updateField("First Name", "Jane");

// Refresh the form UI
EditAccountForm.actions.refresh();

// Refresh form fields (re-calls fields())
EditAccountForm.actions.refreshForm();
```


<div id="submit-button"></div>

## 提交按钮

构建表单时传递 `submitButton` 和 `onSubmit` 回调：

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

`submitButton` 会自动显示在表单字段下方。您可以使用任何内置按钮样式或自定义组件。

您也可以使用任何组件作为提交按钮，将其作为 `footer` 传递：

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

## 表单布局

通过将字段包裹在 `List` 中来并排放置字段：

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

`List` 中的字段以等宽 `Expanded` 的 `Row` 渲染。字段之间的间距由 `NyFormWidget` 上的 `crossAxisSpacing` 参数控制。


<div id="field-visibility"></div>

## 字段可见性

使用 `Field` 上的 `hide()` 和 `show()` 方法以编程方式显示或隐藏字段。您可以在表单类内部或通过 `onChanged` 回调访问字段：

``` dart
// Inside your NyFormWidget subclass or onChanged callback
Field nameField = ...;

// Hide the field
nameField.hide();

// Show the field
nameField.show();
```

隐藏的字段不会在 UI 中渲染，但仍然存在于表单的字段列表中。


<div id="field-styling"></div>

## 字段样式

每种字段类型都有对应的 `FieldStyle` 子类用于样式设置：

| 字段类型 | 样式类 |
|------------|-------------|
| Text、Email、Password、Number、URL、TextArea、PhoneNumber、Currency、Mask、CapitalizeWords、CapitalizeSentences | `FieldStyleTextField` |
| Date、DateTime | `FieldStyleDateTimePicker` |
| Picker | `FieldStylePicker` |
| Checkbox | `FieldStyleCheckbox` |
| Switch Box | `FieldStyleSwitchBox` |
| Radio | `FieldStyleRadio` |
| Chip | `FieldStyleChip` |
| Slider | `FieldStyleSlider` |
| Range Slider | `FieldStyleRangeSlider` |

将样式对象传递给任何字段的 `style` 参数：

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

## NyFormWidget 静态方法

`NyFormWidget` 提供了静态方法，可通过名称从应用的任何位置与表单交互：

| 方法 | 描述 |
|--------|-------------|
| `NyFormWidget.submit(name, onSuccess:, onFailure:, showToastError:)` | 通过名称提交表单 |
| `NyFormWidget.stateRefresh(name)` | 刷新表单的 UI 状态 |
| `NyFormWidget.stateSetValue(name, key, value)` | 通过表单名称设置字段值 |
| `NyFormWidget.stateSetOptions(name, key, options)` | 通过表单名称设置字段选项 |
| `NyFormWidget.stateClearData(name)` | 通过表单名称清除所有字段 |
| `NyFormWidget.stateRefreshForm(name)` | 刷新表单字段（重新调用 `fields()`） |

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

> **提示：** 建议使用 `NyFormActions`（见下方）而不是直接调用这些静态方法 -- 更简洁且不易出错。


<div id="ny-form-widget-constructor-reference"></div>

## NyFormWidget 构造函数参考

继承 `NyFormWidget` 时，您可以传递以下构造函数参数：

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

`onChanged` 回调接收更改的 `Field` 及其新值：

``` dart
LoginForm(
  onChanged: (Field field, dynamic value) {
    print("${field.key} changed to: $value");
  },
)
```


<div id="ny-form-actions"></div>

## NyFormActions

`NyFormActions` 提供了从应用的任何位置与表单交互的便捷方式。在表单类中将其定义为静态 getter：

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

### 可用操作

| 方法 | 描述 |
|--------|-------------|
| `actions.updateField(key, value)` | 设置字段的值 |
| `actions.clearField(key)` | 清除特定字段 |
| `actions.clear()` | 清除所有字段 |
| `actions.refresh()` | 刷新表单的 UI 状态 |
| `actions.refreshForm()` | 重新调用 `fields()` 并重建 |
| `actions.setOptions(key, options)` | 设置选择器/标签/单选字段的选项 |
| `actions.submit(onSuccess:, onFailure:, showToastError:)` | 带验证提交 |

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

### NyFormWidget 覆盖

您可以在 `NyFormWidget` 子类中覆盖的方法：

| 覆盖 | 描述 |
|----------|-------------|
| `fields()` | 定义表单字段（必需） |
| `init` | 提供初始数据（同步或异步） |
| `onChange(field, data)` | 内部处理字段变更 |


<div id="all-field-types-reference"></div>

## 所有字段类型参考

| 构造函数 | 关键参数 | 描述 |
|-------------|----------------|-------------|
| `Field.text()` | -- | 标准文本输入 |
| `Field.email()` | -- | 带键盘类型的邮箱输入 |
| `Field.password()` | `viewable` | 带可选可见性切换的密码 |
| `Field.number()` | `decimal` | 数字输入，可选小数 |
| `Field.currency()` | `currency`（必需） | 货币格式化输入 |
| `Field.capitalizeWords()` | -- | 标题格式文本输入 |
| `Field.capitalizeSentences()` | -- | 句首格式文本输入 |
| `Field.textArea()` | -- | 多行文本输入 |
| `Field.phoneNumber()` | -- | 自动格式化的电话号码 |
| `Field.url()` | -- | 带键盘类型的 URL 输入 |
| `Field.mask()` | `mask`（必需）、`match`、`maskReturnValue` | 掩码文本输入 |
| `Field.date()` | -- | 日期选择器 |
| `Field.datetime()` | `firstDate`, `lastDate`, `dateFormat`, `initialPickerDateTime` | 日期和时间选择器 |
| `Field.checkbox()` | -- | 布尔复选框 |
| `Field.switchBox()` | -- | 布尔开关 |
| `Field.picker()` | `options`（必需 `FormCollection`） | 从列表中单选 |
| `Field.radio()` | `options`（必需 `FormCollection`） | 单选按钮组 |
| `Field.chips()` | `options`（必需 `FormCollection`） | 多选标签 |
| `Field.slider()` | -- | 单值滑块 |
| `Field.rangeSlider()` | -- | 范围值滑块 |
| `Field.custom()` | `child`（必需 `NyFieldStatefulWidget`） | 自定义有状态组件 |
| `Field.widget()` | `child`（必需 `Widget`） | 嵌入任何组件（非字段） |

