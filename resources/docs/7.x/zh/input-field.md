# InputField

---

<a name="section-1"></a>
- [简介](#introduction "简介")
- [基本用法](#basic-usage "基本用法")
- [验证](#validation "验证")
- 变体
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [输入掩码](#masking "输入掩码")
- [头部和底部](#header-footer "头部和底部")
- [可清除输入](#clearable "可清除输入")
- [状态管理](#state-management "状态管理")
- [参数](#parameters "参数")


<div id="introduction"></div>

## 简介

**InputField** 组件是 {{ config('app.name') }} 的增强文本字段，内置支持：

- 带有可自定义错误消息的验证
- 密码可见性切换
- 输入掩码（电话号码、信用卡等）
- 头部和底部组件
- 可清除输入
- 状态管理集成
- 用于开发的模拟数据

<div id="basic-usage"></div>

## 基本用法

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

## 验证

使用 `formValidator` 参数添加验证规则：

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

当用户将焦点移开时，字段将进行验证。

### 自定义验证处理程序

以编程方式处理验证错误：

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

查看[验证](/docs/7.x/validation)文档了解所有可用的验证规则。

<div id="password"></div>

## InputField.password

预配置的密码字段，带有文本隐藏和可见性切换：

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### 自定义密码可见性

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

预配置的邮箱字段，带有邮箱键盘和自动聚焦：

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

自动将每个单词的首字母大写：

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## 输入掩码

为格式化数据（如电话号码或信用卡）应用输入掩码：

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

| 参数 | 描述 |
|-----------|-------------|
| `mask` | 使用 `#` 作为占位符的掩码模式 |
| `maskMatch` | 有效输入字符的正则表达式 |
| `maskedReturnValue` | 如果为 true，返回格式化的值；如果为 false，返回原始输入 |

<div id="header-footer"></div>

## 头部和底部

在输入字段的上方或下方添加组件：

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

## 可清除输入

添加清除按钮以快速清空字段：

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

## 状态管理

为输入字段指定状态名称以编程方式控制它：

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### 状态操作

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

## 参数

### 通用参数

| 参数 | 类型 | 默认值 | 描述 |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | 必需 | 控制正在编辑的文本 |
| `labelText` | `String?` | - | 字段上方显示的标签 |
| `hintText` | `String?` | - | 占位文本 |
| `formValidator` | `FormValidator?` | - | 验证规则 |
| `validateOnFocusChange` | `bool` | `true` | 焦点变化时验证 |
| `obscureText` | `bool` | `false` | 隐藏输入（用于密码） |
| `keyboardType` | `TextInputType` | `text` | 键盘类型 |
| `autoFocus` | `bool` | `false` | 构建时自动聚焦 |
| `readOnly` | `bool` | `false` | 使字段只读 |
| `enabled` | `bool?` | - | 启用/禁用字段 |
| `maxLines` | `int?` | `1` | 最大行数 |
| `maxLength` | `int?` | - | 最大字符数 |

### 样式参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | 字段背景颜色 |
| `borderRadius` | `BorderRadius?` | 边框圆角 |
| `border` | `InputBorder?` | 默认边框 |
| `focusedBorder` | `InputBorder?` | 聚焦时的边框 |
| `enabledBorder` | `InputBorder?` | 启用时的边框 |
| `contentPadding` | `EdgeInsetsGeometry?` | 内部填充 |
| `style` | `TextStyle?` | 文本样式 |
| `labelStyle` | `TextStyle?` | 标签文本样式 |
| `hintStyle` | `TextStyle?` | 提示文本样式 |
| `prefixIcon` | `Widget?` | 输入前的图标 |

### 掩码参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `mask` | `String?` | 掩码模式（例如 "###-####"） |
| `maskMatch` | `String?` | 有效字符的正则表达式 |
| `maskedReturnValue` | `bool?` | 返回掩码值还是原始值 |

### 功能参数

| 参数 | 类型 | 描述 |
|-----------|------|-------------|
| `header` | `Widget?` | 字段上方的组件 |
| `footer` | `Widget?` | 字段下方的组件 |
| `clearable` | `bool?` | 显示清除按钮 |
| `clearIcon` | `Widget?` | 自定义清除图标 |
| `passwordVisible` | `bool?` | 显示密码切换 |
| `passwordViewable` | `bool?` | 允许密码可见性切换 |
| `dummyData` | `String?` | 用于开发的模拟数据 |
| `stateName` | `String?` | 状态管理名称 |
| `onChanged` | `Function(String)?` | 值变化时调用 |
