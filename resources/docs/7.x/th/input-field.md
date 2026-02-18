# InputField

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานพื้นฐาน](#basic-usage "การใช้งานพื้นฐาน")
- [การตรวจสอบความถูกต้อง](#validation "การตรวจสอบความถูกต้อง")
- รูปแบบต่างๆ
    - [InputField.password](#password "InputField.password")
    - [InputField.emailAddress](#email-address "InputField.emailAddress")
    - [InputField.capitalizeWords](#capitalize-words "InputField.capitalizeWords")
- [การมาสก์ข้อมูลป้อนเข้า](#masking "การมาสก์ข้อมูลป้อนเข้า")
- [ส่วนหัวและส่วนท้าย](#header-footer "ส่วนหัวและส่วนท้าย")
- [ช่องป้อนข้อมูลที่ล้างได้](#clearable "ช่องป้อนข้อมูลที่ล้างได้")
- [การจัดการสถานะ](#state-management "การจัดการสถานะ")
- [พารามิเตอร์](#parameters "พารามิเตอร์")


<div id="introduction"></div>

## บทนำ

widget **InputField** คือช่องป้อนข้อความขั้นสูงของ {{ config('app.name') }} พร้อมการรองรับในตัวสำหรับ:

- การตรวจสอบความถูกต้องพร้อมข้อความข้อผิดพลาดที่ปรับแต่งได้
- การสลับการแสดง/ซ่อนรหัสผ่าน
- การมาสก์ข้อมูลป้อนเข้า (เบอร์โทรศัพท์, บัตรเครดิต ฯลฯ)
- widget ส่วนหัวและส่วนท้าย
- ช่องป้อนข้อมูลที่ล้างได้
- การรวมเข้ากับการจัดการสถานะ
- ข้อมูลจำลองสำหรับการพัฒนา

<div id="basic-usage"></div>

## การใช้งานพื้นฐาน

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

## การตรวจสอบความถูกต้อง

ใช้พารามิเตอร์ `formValidator` เพื่อเพิ่มกฎการตรวจสอบ:

``` dart
InputField(
  controller: _controller,
  labelText: "Email",
  formValidator: FormValidator.rule("email|not_empty"),
  validateOnFocusChange: true,
)
```

ช่องป้อนข้อมูลจะตรวจสอบเมื่อผู้ใช้เลื่อนโฟกัสออกไป

### ตัวจัดการการตรวจสอบที่กำหนดเอง

จัดการข้อผิดพลาดการตรวจสอบโดยทางโปรแกรม:

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

ดูกฎการตรวจสอบทั้งหมดได้ในเอกสาร [การตรวจสอบความถูกต้อง](/docs/7.x/validation)

<div id="password"></div>

## InputField.password

ช่องรหัสผ่านที่ตั้งค่าไว้ล่วงหน้าพร้อมข้อความที่ซ่อนและปุ่มสลับการแสดง:

``` dart
final TextEditingController _passwordController = TextEditingController();

InputField.password(
  controller: _passwordController,
  labelText: "Password",
  formValidator: FormValidator.rule("not_empty|min:8"),
)
```

### การปรับแต่งการแสดงรหัสผ่าน

``` dart
InputField.password(
  controller: _passwordController,
  passwordVisible: true, // Show/hide toggle icon
  passwordViewable: true, // Allow user to toggle visibility
)
```

<div id="email-address"></div>

## InputField.emailAddress

ช่องอีเมลที่ตั้งค่าไว้ล่วงหน้าพร้อมแป้นพิมพ์อีเมลและ autofocus:

``` dart
final TextEditingController _emailController = TextEditingController();

InputField.emailAddress(
  controller: _emailController,
  formValidator: FormValidator.rule("email|not_empty"),
)
```

<div id="capitalize-words"></div>

## InputField.capitalizeWords

ทำให้อักษรตัวแรกของแต่ละคำเป็นตัวพิมพ์ใหญ่อัตโนมัติ:

``` dart
final TextEditingController _nameController = TextEditingController();

InputField.capitalizeWords(
  controller: _nameController,
  labelText: "Full Name",
)
```

<div id="masking"></div>

## การมาสก์ข้อมูลป้อนเข้า

ใช้มาสก์สำหรับข้อมูลที่มีรูปแบบ เช่น เบอร์โทรศัพท์หรือบัตรเครดิต:

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

| พารามิเตอร์ | คำอธิบาย |
|-----------|-------------|
| `mask` | รูปแบบมาสก์ที่ใช้ `#` เป็นตัวแทน |
| `maskMatch` | รูปแบบ regex สำหรับอักขระที่ถูกต้อง |
| `maskedReturnValue` | ถ้าเป็น true จะส่งค่าที่จัดรูปแบบแล้ว; ถ้าเป็น false จะส่งค่าดิบ |

<div id="header-footer"></div>

## ส่วนหัวและส่วนท้าย

เพิ่ม widget ด้านบนหรือด้านล่างช่องป้อนข้อมูล:

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

## ช่องป้อนข้อมูลที่ล้างได้

เพิ่มปุ่มล้างเพื่อล้างช่องป้อนข้อมูลอย่างรวดเร็ว:

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

## การจัดการสถานะ

ตั้งชื่อสถานะให้ช่องป้อนข้อมูลเพื่อควบคุมโดยทางโปรแกรม:

``` dart
InputField(
  controller: _controller,
  labelText: "Username",
  stateName: "username_field",
)
```

### การดำเนินการสถานะ

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

## พารามิเตอร์

### พารามิเตอร์ทั่วไป

| พารามิเตอร์ | ประเภท | ค่าเริ่มต้น | คำอธิบาย |
|-----------|------|---------|-------------|
| `controller` | `TextEditingController` | จำเป็น | ควบคุมข้อความที่กำลังแก้ไข |
| `labelText` | `String?` | - | ป้ายกำกับที่แสดงเหนือช่อง |
| `hintText` | `String?` | - | ข้อความตัวแทน |
| `formValidator` | `FormValidator?` | - | กฎการตรวจสอบ |
| `validateOnFocusChange` | `bool` | `true` | ตรวจสอบเมื่อโฟกัสเปลี่ยน |
| `obscureText` | `bool` | `false` | ซ่อนข้อมูลป้อนเข้า (สำหรับรหัสผ่าน) |
| `keyboardType` | `TextInputType` | `text` | ประเภทแป้นพิมพ์ |
| `autoFocus` | `bool` | `false` | โฟกัสอัตโนมัติเมื่อ build |
| `readOnly` | `bool` | `false` | ทำให้ช่องอ่านอย่างเดียว |
| `enabled` | `bool?` | - | เปิด/ปิดใช้งานช่อง |
| `maxLines` | `int?` | `1` | จำนวนบรรทัดสูงสุด |
| `maxLength` | `int?` | - | จำนวนอักขระสูงสุด |

### พารามิเตอร์การจัดรูปแบบ

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `backgroundColor` | `Color?` | สีพื้นหลังของช่อง |
| `borderRadius` | `BorderRadius?` | รัศมีขอบ |
| `border` | `InputBorder?` | ขอบเริ่มต้น |
| `focusedBorder` | `InputBorder?` | ขอบเมื่อโฟกัส |
| `enabledBorder` | `InputBorder?` | ขอบเมื่อเปิดใช้งาน |
| `contentPadding` | `EdgeInsetsGeometry?` | ระยะห่างภายใน |
| `style` | `TextStyle?` | สไตล์ข้อความ |
| `labelStyle` | `TextStyle?` | สไตล์ข้อความป้ายกำกับ |
| `hintStyle` | `TextStyle?` | สไตล์ข้อความตัวแทน |
| `prefixIcon` | `Widget?` | ไอคอนก่อนช่องป้อนข้อมูล |

### พารามิเตอร์การมาสก์

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `mask` | `String?` | รูปแบบมาสก์ (เช่น "###-####") |
| `maskMatch` | `String?` | regex สำหรับอักขระที่ถูกต้อง |
| `maskedReturnValue` | `bool?` | ส่งค่าที่มาสก์หรือค่าดิบ |

### พารามิเตอร์ฟีเจอร์

| พารามิเตอร์ | ประเภท | คำอธิบาย |
|-----------|------|-------------|
| `header` | `Widget?` | widget ด้านบนช่อง |
| `footer` | `Widget?` | widget ด้านล่างช่อง |
| `clearable` | `bool?` | แสดงปุ่มล้าง |
| `clearIcon` | `Widget?` | ไอคอนล้างที่กำหนดเอง |
| `passwordVisible` | `bool?` | แสดงปุ่มสลับรหัสผ่าน |
| `passwordViewable` | `bool?` | อนุญาตให้สลับการแสดงรหัสผ่าน |
| `dummyData` | `String?` | ข้อมูลจำลองสำหรับการพัฒนา |
| `stateName` | `String?` | ชื่อสำหรับการจัดการสถานะ |
| `onChanged` | `Function(String)?` | เรียกเมื่อค่าเปลี่ยน |
