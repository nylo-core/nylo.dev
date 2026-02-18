# Contributing to {{ config('app.name') }}

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำเกี่ยวกับการมีส่วนร่วม")
- [เริ่มต้นใช้งาน](#getting-started "เริ่มต้นการมีส่วนร่วม")
- [สภาพแวดล้อมการพัฒนา](#development-environment "การตั้งค่าสภาพแวดล้อมการพัฒนา")
- [แนวทางการพัฒนา](#development-guidelines "แนวทางการพัฒนา")
- [การส่งการเปลี่ยนแปลง](#submitting-changes "วิธีการส่งการเปลี่ยนแปลง")
- [การรายงานปัญหา](#reporting-issues "วิธีการรายงานปัญหา")


<div id="introduction"></div>

## บทนำ

ขอบคุณที่พิจารณาการมีส่วนร่วมกับ {{ config('app.name') }}!

คู่มือนี้จะช่วยให้คุณเข้าใจวิธีการมีส่วนร่วมกับ micro-framework ไม่ว่าคุณจะแก้ไขบัก เพิ่มฟีเจอร์ หรือปรับปรุงเอกสาร การมีส่วนร่วมของคุณมีคุณค่าต่อชุมชน {{ config('app.name') }}

{{ config('app.name') }} แบ่งออกเป็นสาม repository:

| Repository | วัตถุประสงค์ |
|------------|---------|
| <a href="https://github.com/nylo-core/nylo" target="_BLANK">nylo</a> | แอปพลิเคชัน boilerplate |
| <a href="https://github.com/nylo-core/framework" target="_BLANK">framework</a> | คลาสหลักของ framework (nylo_framework) |
| <a href="https://github.com/nylo-core/support" target="_BLANK">support</a> | ไลบรารีสนับสนุนพร้อม widget, ตัวช่วย, ยูทิลิตี้ (nylo_support) |

<div id="getting-started"></div>

## เริ่มต้นใช้งาน

### Fork Repository

Fork repository ที่คุณต้องการมีส่วนร่วม:

- <a href="https://github.com/nylo-core/nylo/fork" target="_BLANK">Fork Nylo Boilerplate</a>
- <a href="https://github.com/nylo-core/framework/fork" target="_BLANK">Fork Framework</a>
- <a href="https://github.com/nylo-core/support/fork" target="_BLANK">Fork Support</a>

### Clone Fork ของคุณ

``` bash
git clone https://github.com/YOUR-USERNAME/nylo
git clone https://github.com/YOUR-USERNAME/framework
git clone https://github.com/YOUR-USERNAME/support
```

<div id="development-environment"></div>

## สภาพแวดล้อมการพัฒนา

### ความต้องการ

ตรวจสอบให้แน่ใจว่าคุณได้ติดตั้งสิ่งต่อไปนี้:

| ความต้องการ | เวอร์ชันขั้นต่ำ |
|-------------|-----------------|
| Flutter | 3.24.0 ขึ้นไป |
| Dart SDK | 3.10.7 ขึ้นไป |

### เชื่อมต่อ Package ในเครื่อง

เปิด Nylo boilerplate ในเอดิเตอร์ของคุณและเพิ่ม dependency override เพื่อใช้ repository framework และ support ในเครื่อง:

**pubspec.yaml**
``` yaml
dependency_overrides:
  nylo_framework:
    path: ../framework  # Path to your local framework repository
  nylo_support:
    path: ../support    # Path to your local support repository
```

รัน `flutter pub get` เพื่อติดตั้ง dependency

ตอนนี้การเปลี่ยนแปลงที่คุณทำกับ repository framework หรือ support จะสะท้อนใน Nylo boilerplate

### ทดสอบการเปลี่ยนแปลงของคุณ

รันแอป boilerplate เพื่อทดสอบการเปลี่ยนแปลง:

``` bash
flutter run
```

สำหรับการเปลี่ยนแปลง widget หรือตัวช่วย ให้พิจารณาเพิ่มเทสต์ใน repository ที่เหมาะสม

<div id="development-guidelines"></div>

## แนวทางการพัฒนา

### สไตล์โค้ด

- ทำตาม<a href="https://dart.dev/guides/language/effective-dart/style" target="_BLANK">คู่มือสไตล์ Dart</a> อย่างเป็นทางการ
- ใช้ชื่อตัวแปรและฟังก์ชันที่มีความหมาย
- เขียนคอมเมนต์ที่ชัดเจนสำหรับ logic ที่ซับซ้อน
- รวม documentation สำหรับ API สาธารณะ
- ทำให้โค้ดเป็นโมดูลและดูแลรักษาได้

### เอกสาร

เมื่อเพิ่มฟีเจอร์ใหม่:

- เพิ่ม dartdoc comment ให้กับคลาสและเมธอดสาธารณะ
- อัปเดตไฟล์เอกสารที่เกี่ยวข้องหากจำเป็น
- รวมตัวอย่างโค้ดในเอกสาร

### การทดสอบ

ก่อนส่งการเปลี่ยนแปลง:

- ทดสอบบนอุปกรณ์/simulator ทั้ง iOS และ Android
- ตรวจสอบความเข้ากันได้ย้อนหลังเท่าที่เป็นไปได้
- ระบุ breaking change อย่างชัดเจน
- รันเทสต์ที่มีอยู่เพื่อให้แน่ใจว่าไม่มีอะไรเสียหาย

<div id="submitting-changes"></div>

## การส่งการเปลี่ยนแปลง

### พูดคุยก่อน

สำหรับฟีเจอร์ใหม่ ควรพูดคุยกับชุมชนก่อน:

- <a href="https://github.com/nylo-core/nylo/discussions" target="_BLANK">GitHub Discussions</a>

### สร้าง Branch

``` bash
git checkout -b feature/your-feature-name
```

ใช้ชื่อ branch ที่อธิบายได้:
- `feature/collection-view-pagination`
- `fix/storage-null-handling`
- `docs/update-networking-guide`

### Commit การเปลี่ยนแปลงของคุณ

``` bash
git add .
git commit -m "Add: Your feature description"
```

ใช้ข้อความ commit ที่ชัดเจน:
- `Add: CollectionView grid mode support`
- `Fix: NyStorage returning null on first read`
- `Update: Improve router documentation`

### Push และสร้าง Pull Request

``` bash
git push origin feature/your-feature-name
```

จากนั้นสร้าง pull request บน GitHub

### แนวทาง Pull Request

- ให้คำอธิบายที่ชัดเจนเกี่ยวกับการเปลี่ยนแปลงของคุณ
- อ้างอิง issue ที่เกี่ยวข้อง
- รวมภาพหน้าจอหรือตัวอย่างโค้ดหากเป็นไปได้
- ให้แน่ใจว่า PR ของคุณจัดการเรื่องเดียวเท่านั้น
- ทำให้การเปลี่ยนแปลงมีจุดโฟกัสและเป็นอะตอมิก

<div id="reporting-issues"></div>

## การรายงานปัญหา

### ก่อนรายงาน

1. ตรวจสอบว่าปัญหามีอยู่แล้วบน GitHub หรือไม่
2. ตรวจสอบให้แน่ใจว่าคุณใช้เวอร์ชันล่าสุด
3. ลองจำลองปัญหาในโปรเจกต์ใหม่

### ที่ไหนควรรายงาน

รายงานปัญหาบน repository ที่เหมาะสม:

- **ปัญหา Boilerplate**: <a href="https://github.com/nylo-core/nylo/issues" target="_BLANK">nylo/issues</a>
- **ปัญหา Framework**: <a href="https://github.com/nylo-core/framework/issues" target="_BLANK">framework/issues</a>
- **ปัญหาไลบรารี Support**: <a href="https://github.com/nylo-core/support/issues" target="_BLANK">support/issues</a>

### เทมเพลต Issue

ให้ข้อมูลโดยละเอียด:

``` markdown
### Description
Brief description of the issue

### Steps to Reproduce
1. Step one
2. Step two
3. Step three

### Expected Behavior
What should happen

### Actual Behavior
What actually happens

### Environment
- Flutter: 3.24.x
- Dart SDK: 3.10.x
- nylo_framework: ^7.0.0
- OS: macOS/Windows/Linux
- Device: iPhone 15/Pixel 8 (if applicable)

### Code Example
```dart
// Minimal code to reproduce the issue
```
```

### การดูข้อมูลเวอร์ชัน

``` bash
# Flutter and Dart versions
flutter --version

# Check your pubspec.yaml for Nylo version
# nylo_framework: ^7.0.0
```
