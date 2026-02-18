# Navigation Hub

---

<a name="section-1"></a>
- [บทนำ](#introduction "บทนำ")
- [การใช้งานเบื้องต้น](#basic-usage "การใช้งานเบื้องต้น")
  - [การสร้าง Navigation Hub](#creating-a-navigation-hub "การสร้าง Navigation Hub")
  - [การสร้าง Navigation Tab](#creating-navigation-tabs "การสร้าง Navigation Tab")
  - [การนำทางด้านล่าง](#bottom-navigation "การนำทางด้านล่าง")
    - [ตัวสร้างแถบนำทางแบบกำหนดเอง](#custom-nav-bar-builder "ตัวสร้างแถบนำทางแบบกำหนดเอง")
  - [การนำทางด้านบน](#top-navigation "การนำทางด้านบน")
  - [การนำทางแบบ Journey](#journey-navigation "การนำทางแบบ Journey")
    - [สไตล์ Progress](#journey-progress-styles "สไตล์ Progress ของ Journey")
    - [JourneyState](#journey-state "JourneyState")
    - [เมธอดตัวช่วยของ JourneyState](#journey-state-helper-methods "เมธอดตัวช่วยของ JourneyState")
    - [onJourneyComplete](#on-journey-complete "onJourneyComplete")
    - [buildJourneyPage](#build-journey-page "buildJourneyPage")
- [การนำทางภายใน tab](#navigating-within-a-tab "การนำทางภายใน tab")
- [แท็บ](#tabs "แท็บ")
  - [การเพิ่ม Badge ให้แท็บ](#adding-badges-to-tabs "การเพิ่ม Badge ให้แท็บ")
  - [การเพิ่ม Alert ให้แท็บ](#adding-alerts-to-tabs "การเพิ่ม Alert ให้แท็บ")
- [ดัชนีเริ่มต้น](#initial-index "ดัชนีเริ่มต้น")
- [การรักษา state](#maintaining-state "การรักษา state")
- [onTap](#on-tap "onTap")
- [การกระทำ State](#state-actions "การกระทำ State")
- [สไตล์การโหลด](#loading-style "สไตล์การโหลด")

<div id="introduction"></div>

## บทนำ

Navigation Hub เป็นศูนย์กลางที่คุณสามารถ**จัดการ**การนำทางสำหรับ widget ทั้งหมดของคุณ
พร้อมใช้งานทันที คุณสามารถสร้างเลย์เอาต์การนำทางแบบ bottom, top และ journey ได้ในเวลาไม่กี่วินาที

ลอง**จินตนาการ**ว่าคุณมีแอปและต้องการเพิ่มแถบนำทางด้านล่างเพื่อให้ผู้ใช้สามารถสลับระหว่างแท็บต่างๆ ในแอปของคุณ

คุณสามารถใช้ Navigation Hub เพื่อสร้างสิ่งนี้

มาดูกันว่าคุณสามารถใช้ Navigation Hub ในแอปของคุณได้อย่างไร

<div id="basic-usage"></div>

## การใช้งานเบื้องต้น

คุณสามารถสร้าง Navigation Hub โดยใช้คำสั่งด้านล่าง

``` bash
metro make:navigation_hub base
```

คำสั่งนี้จะนำคุณผ่านการตั้งค่าแบบ interactive:

1. **เลือกประเภทเลย์เอาต์** - เลือกระหว่าง `navigation_tabs` (bottom navigation) หรือ `journey_states` (sequential flow)
2. **ป้อนชื่อ tab/state** - ระบุชื่อสำหรับแท็บหรือ journey state โดยคั่นด้วยจุลภาค

สิ่งนี้จะสร้างไฟล์ภายใต้ไดเรกทอรี `resources/pages/navigation_hubs/base/` ของคุณ:
- `base_navigation_hub.dart` - widget hub หลัก
- `tabs/` หรือ `states/` - ประกอบด้วย widget ลูกสำหรับแต่ละแท็บหรือ journey state

นี่คือตัวอย่าง Navigation Hub ที่ถูกสร้างขึ้น:

``` dart
import 'package:flutter/material.dart';
import 'package:nylo_framework/nylo_framework.dart';
import '/resources/pages/navigation_hubs/base/tabs/home_tab_widget.dart';
import '/resources/pages/navigation_hubs/base/tabs/settings_tab_widget.dart';

class BaseNavigationHub extends NyStatefulWidget with BottomNavPageControls {
  static RouteView path = ("/base", (_) => BaseNavigationHub());

  BaseNavigationHub()
      : super(
            child: () => _BaseNavigationHubState(),
            stateName: path.stateName());

  /// State actions
  static NavigationHubStateActions stateActions = NavigationHubStateActions(path.stateName());
}

class _BaseNavigationHubState extends NavigationHub<BaseNavigationHub> {

  /// ตัวสร้างเลย์เอาต์
  @override
  NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();

  /// ควรรักษา state หรือไม่
  @override
  bool get maintainState => true;

  /// ดัชนีเริ่มต้น
  @override
  int get initialIndex => 0;

  /// หน้าการนำทาง
  _BaseNavigationHubState() : super(() => {
      0: NavigationTab.tab(title: "Home", page: HomeTab()),
      1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
  });

  /// จัดการ event การแตะ
  @override
  onTap(int index) {
    super.onTap(index);
  }
}
```

คุณจะเห็นว่า Navigation Hub มีแท็บ**สอง**แท็บ คือ Home และ Settings

เมธอด `layout` คืนค่าประเภทเลย์เอาต์สำหรับ hub โดยจะรับ `BuildContext` เพื่อให้คุณสามารถเข้าถึงข้อมูลธีมและ media queries เมื่อกำหนดค่าเลย์เอาต์ของคุณ

คุณสามารถสร้างแท็บเพิ่มเติมโดยเพิ่ม `NavigationTab` เข้าไปใน Navigation Hub

ขั้นแรก คุณต้องสร้าง widget ใหม่โดยใช้ Metro

``` bash
metro make:stateful_widget news_tab
```

คุณยังสามารถสร้าง widget หลายตัวพร้อมกันได้

``` bash
metro make:stateful_widget news_tab,notifications_tab
```

จากนั้น คุณสามารถเพิ่ม widget ใหม่เข้าไปใน Navigation Hub

``` dart
_BaseNavigationHubState() : super(() => {
    0: NavigationTab.tab(title: "Home", page: HomeTab()),
    1: NavigationTab.tab(title: "Settings", page: SettingsTab()),
    2: NavigationTab.tab(title: "News", page: NewsTab()),
});
```

เพื่อใช้ Navigation Hub ให้เพิ่มไปยัง router เป็น route เริ่มต้น:

``` dart
import 'package:nylo_framework/nylo_framework.dart';

appRouter() => nyRoutes((router) {
    ...
    router.add(BaseNavigationHub.path).initialRoute();
});

// หรือนำทางไปยัง Navigation Hub จากที่ใดก็ได้ในแอปของคุณ

routeTo(BaseNavigationHub.path);
```

มี**อีกมากมาย**ที่คุณสามารถทำกับ Navigation Hub มาดูฟีเจอร์บางอย่างกัน

<div id="bottom-navigation"></div>

### การนำทางด้านล่าง

คุณสามารถตั้งค่าเลย์เอาต์เป็นแถบนำทางด้านล่างโดยคืนค่า `NavigationHubLayout.bottomNav` จากเมธอด `layout`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
```

คุณสามารถปรับแต่งแถบนำทางด้านล่างโดยตั้งค่าคุณสมบัติดังนี้:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        backgroundColor: Colors.white,
        selectedItemColor: Colors.blue,
        unselectedItemColor: Colors.grey,
        elevation: 8.0,
        iconSize: 24.0,
        selectedFontSize: 14.0,
        unselectedFontSize: 12.0,
        showSelectedLabels: true,
        showUnselectedLabels: true,
        type: BottomNavigationBarType.fixed,
    );
```

คุณสามารถใช้สไตล์สำเร็จรูปกับแถบนำทางด้านล่างโดยใช้พารามิเตอร์ `style`

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
    style: BottomNavStyle.material(), // สไตล์ material เริ่มต้นของ Flutter
);
```

<div id="custom-nav-bar-builder"></div>

### ตัวสร้างแถบนำทางแบบกำหนดเอง

สำหรับการควบคุมแถบนำทางอย่างสมบูรณ์ คุณสามารถใช้พารามิเตอร์ `navBarBuilder`

สิ่งนี้ช่วยให้คุณสร้าง widget แบบกำหนดเองใดๆ ก็ได้ในขณะที่ยังคงได้รับข้อมูลการนำทาง

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav(
        navBarBuilder: (context, data) {
            return MyCustomNavBar(
                items: data.items,
                currentIndex: data.currentIndex,
                onTap: data.onTap,
            );
        },
    );
```

อ็อบเจกต์ `NavBarData` ประกอบด้วย:

| คุณสมบัติ | ชนิด | คำอธิบาย |
| --- | --- | --- |
| `items` | `List<BottomNavigationBarItem>` | รายการของแถบนำทาง |
| `currentIndex` | `int` | ดัชนีที่ถูกเลือกในปัจจุบัน |
| `onTap` | `ValueChanged<int>` | Callback เมื่อแท็บถูกแตะ |

นี่คือตัวอย่างแถบนำทางกระจกแบบกำหนดเองเต็มรูปแบบ:

``` dart
NavigationHubLayout.bottomNav(
    navBarBuilder: (context, data) {
        return Padding(
            padding: EdgeInsets.all(16),
            child: ClipRRect(
                borderRadius: BorderRadius.circular(25),
                child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 10, sigmaY: 10),
                    child: Container(
                        decoration: BoxDecoration(
                            color: Colors.white.withValues(alpha: 0.7),
                            borderRadius: BorderRadius.circular(25),
                        ),
                        child: BottomNavigationBar(
                            items: data.items,
                            currentIndex: data.currentIndex,
                            onTap: data.onTap,
                            backgroundColor: Colors.transparent,
                            elevation: 0,
                        ),
                    ),
                ),
            ),
        );
    },
)
```

> **หมายเหตุ:** เมื่อใช้ `navBarBuilder` พารามิเตอร์ `style` จะถูกเพิกเฉย

<div id="top-navigation"></div>

### การนำทางด้านบน

คุณสามารถเปลี่ยนเลย์เอาต์เป็นแถบนำทางด้านบนโดยคืนค่า `NavigationHubLayout.topNav` จากเมธอด `layout`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav();
```

คุณสามารถปรับแต่งแถบนำทางด้านบนโดยตั้งค่าคุณสมบัติดังนี้:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.topNav(
        backgroundColor: Colors.white,
        labelColor: Colors.blue,
        unselectedLabelColor: Colors.grey,
        indicatorColor: Colors.blue,
        indicatorWeight: 3.0,
        isScrollable: false,
        hideAppBarTitle: true,
    );
```

<div id="journey-navigation"></div>

### การนำทางแบบ Journey

คุณสามารถเปลี่ยนเลย์เอาต์เป็น journey navigation โดยคืนค่า `NavigationHubLayout.journey` จากเมธอด `layout`

สิ่งนี้เหมาะมากสำหรับ onboarding flow หรือฟอร์มหลายขั้นตอน

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
          indicator: JourneyProgressIndicator.segments(),
        ),
    );
```

คุณยังสามารถตั้งค่า `backgroundGradient` สำหรับเลย์เอาต์ journey:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    backgroundGradient: LinearGradient(
        colors: [Colors.blue, Colors.purple],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
    ),
    progressStyle: JourneyProgressStyle(
      indicator: JourneyProgressIndicator.linear(),
    ),
);
```

> **หมายเหตุ:** เมื่อตั้งค่า `backgroundGradient` แล้ว จะมีความสำคัญกว่า `backgroundColor`

หากคุณต้องการใช้เลย์เอาต์ journey navigation **widget** ของคุณควรใช้ `JourneyState` เนื่องจากมีเมธอดตัวช่วยมากมายเพื่อช่วยคุณจัดการ journey

คุณสามารถสร้าง journey ทั้งหมดโดยใช้คำสั่ง `make:navigation_hub` กับเลย์เอาต์ `journey_states`:

``` bash
metro make:navigation_hub onboarding
# เลือก: journey_states
# ป้อน: welcome, personal_info, add_photos
```

สิ่งนี้จะสร้าง hub และ widget journey state ทั้งหมดภายใต้ `resources/pages/navigation_hubs/onboarding/states/`

หรือคุณสามารถสร้าง widget journey แยกได้โดยใช้:

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

จากนั้นคุณสามารถเพิ่ม widget ใหม่เข้าไปใน Navigation Hub

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-progress-styles"></div>

### สไตล์ Progress ของ Journey

คุณสามารถปรับแต่งสไตล์ตัวแสดงความคืบหน้าโดยใช้คลาส `JourneyProgressStyle`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.linear(
                activeColor: Colors.blue,
                inactiveColor: Colors.grey,
                thickness: 4.0,
            ),
        ),
    );
```

คุณสามารถใช้ตัวแสดงความคืบหน้าต่อไปนี้:

- `JourneyProgressIndicator.none()`: ไม่แสดงผลอะไร - มีประโยชน์สำหรับซ่อนตัวแสดงในแท็บเฉพาะ
- `JourneyProgressIndicator.linear()`: แถบความคืบหน้าแบบเส้นตรง
- `JourneyProgressIndicator.dots()`: ตัวแสดงความคืบหน้าแบบจุด
- `JourneyProgressIndicator.numbered()`: ตัวแสดงความคืบหน้าแบบมีตัวเลข
- `JourneyProgressIndicator.segments()`: สไตล์แถบความคืบหน้าแบบแบ่งส่วน
- `JourneyProgressIndicator.circular()`: ตัวแสดงความคืบหน้าแบบวงกลม
- `JourneyProgressIndicator.timeline()`: ตัวแสดงความคืบหน้าแบบ timeline
- `JourneyProgressIndicator.custom()`: ตัวแสดงความคืบหน้าแบบกำหนดเองโดยใช้ฟังก์ชัน builder

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.custom(
            builder: (context, currentStep, totalSteps, percentage) {
                return LinearProgressIndicator(
                    value: percentage,
                    backgroundColor: Colors.grey[200],
                    color: Colors.blue,
                    minHeight: 4.0,
                );
            },
        ),
    ),
);
```

คุณสามารถปรับแต่งตำแหน่งและ padding ของตัวแสดงความคืบหน้าภายใน `JourneyProgressStyle`:

``` dart
@override
NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.journey(
    progressStyle: JourneyProgressStyle(
        indicator: JourneyProgressIndicator.dots(),
        position: ProgressIndicatorPosition.bottom,
        padding: EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    ),
);
```

คุณสามารถใช้ตำแหน่งตัวแสดงความคืบหน้าต่อไปนี้:

- `ProgressIndicatorPosition.top`: ตัวแสดงความคืบหน้าที่ด้านบนของหน้าจอ
- `ProgressIndicatorPosition.bottom`: ตัวแสดงความคืบหน้าที่ด้านล่างของหน้าจอ

#### การกำหนดสไตล์ Progress เฉพาะแท็บ

คุณสามารถแทนที่ `progressStyle` ระดับเลย์เอาต์ในแท็บแต่ละตัวโดยใช้ `NavigationTab.journey(progressStyle: ...)` แท็บที่ไม่มี `progressStyle` ของตัวเองจะสืบทอดค่าเริ่มต้นจากเลย์เอาต์ แท็บที่ไม่มีค่าเริ่มต้นจากเลย์เอาต์และไม่มีสไตล์เฉพาะแท็บจะไม่แสดงตัวแสดงความคืบหน้า

``` dart
_MyNavigationHubState() : super(() => {
    0: NavigationTab.journey(
        page: Welcome(),
    ),
    1: NavigationTab.journey(
        page: PhoneNumberStep(),
        progressStyle: JourneyProgressStyle(
            indicator: JourneyProgressIndicator.numbered(),
        ), // แทนที่ค่าเริ่มต้นของเลย์เอาต์สำหรับแท็บนี้เท่านั้น
    ),
    2: NavigationTab.journey(
        page: AddPhotosStep(),
    ),
});
```

<div id="journey-state"></div>

### JourneyState

คลาส `JourneyState` ขยายจาก `NyState` ด้วยฟังก์ชันเฉพาะ journey เพื่อทำให้การสร้าง onboarding flow และ journey หลายขั้นตอนง่ายขึ้น

เพื่อสร้าง `JourneyState` ใหม่ คุณสามารถใช้คำสั่งด้านล่าง

``` bash
metro make:journey_widget onboard_user_dob
```

หรือหากคุณต้องการสร้าง widget หลายตัวพร้อมกัน คุณสามารถใช้คำสั่งต่อไปนี้

``` bash
metro make:journey_widget welcome,phone_number_step,add_photos_step
```

นี่คือตัวอย่าง widget JourneyState ที่ถูกสร้างขึ้น:

``` dart
import 'package:flutter/material.dart';
import '/resources/pages/navigation_hubs/onboarding/onboarding_navigation_hub.dart';
import '/resources/widgets/buttons/buttons.dart';
import 'package:nylo_framework/nylo_framework.dart';

class Welcome extends StatefulWidget {
  const Welcome({super.key});

  @override
  createState() => _WelcomeState();
}

class _WelcomeState extends JourneyState<Welcome> {
  _WelcomeState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  @override
  get init => () {
    // ลอจิกการเริ่มต้นของคุณที่นี่
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          Expanded(
            child: Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
                  const SizedBox(height: 20),
                  Text('This onboarding journey will help you get started.'),
                ],
              ),
            ),
          ),

          // ปุ่มนำทาง
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              if (!isFirstStep)
                Flexible(
                  child: Button.textOnly(
                    text: "Back",
                    textColor: Colors.black87,
                    onPressed: onBackPressed,
                  ),
                )
              else
                const SizedBox.shrink(),
              Flexible(
                child: Button.primary(
                  text: "Continue",
                  onPressed: nextStep,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  /// ตรวจสอบว่า journey สามารถดำเนินไปยังขั้นตอนถัดไปได้หรือไม่
  @override
  Future<bool> canContinue() async {
    return true;
  }

  /// ถูกเรียกก่อนนำทางไปยังขั้นตอนถัดไป
  @override
  Future<void> onBeforeNext() async {
    // เช่น บันทึกข้อมูลลง session
  }

  /// ถูกเรียกเมื่อ journey เสร็จสมบูรณ์ (ที่ขั้นตอนสุดท้าย)
  @override
  Future<void> onComplete() async {}
}
```

คุณจะสังเกตว่าคลาส **JourneyState** ใช้ `nextStep` เพื่อนำทางไปข้างหน้าและ `onBackPressed` เพื่อย้อนกลับ

เมธอด `nextStep` ทำงานผ่านวงจรชีวิตการตรวจสอบทั้งหมด: `canContinue()` -> `onBeforeNext()` -> นำทาง (หรือ `onComplete()` หากเป็นขั้นตอนสุดท้าย) -> `onAfterNext()`

คุณยังสามารถใช้ `buildJourneyContent` เพื่อสร้างเลย์เอาต์แบบมีโครงสร้างพร้อมปุ่มนำทางที่เป็นตัวเลือก:

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
          const SizedBox(height: 20),
          Text('This onboarding journey will help you get started.'),
        ],
      ),
      nextButton: Button.primary(
        text: isLastStep ? "Get Started" : "Continue",
        onPressed: nextStep,
      ),
      backButton: isFirstStep ? null : Button.textOnly(
        text: "Back",
        textColor: Colors.black87,
        onPressed: onBackPressed,
      ),
    );
}
```

นี่คือคุณสมบัติที่คุณสามารถใช้ในเมธอด `buildJourneyContent`

| คุณสมบัติ | ชนิด | คำอธิบาย |
| --- | --- | --- |
| `content` | `Widget` | เนื้อหาหลักของหน้า |
| `nextButton` | `Widget?` | widget ปุ่มถัดไป |
| `backButton` | `Widget?` | widget ปุ่มย้อนกลับ |
| `contentPadding` | `EdgeInsetsGeometry` | padding สำหรับเนื้อหา |
| `header` | `Widget?` | widget ส่วนหัว |
| `footer` | `Widget?` | widget ส่วนท้าย |
| `crossAxisAlignment` | `CrossAxisAlignment` | การจัดตำแหน่งแกนตัดของเนื้อหา |

<div id="journey-state-helper-methods"></div>

### เมธอดตัวช่วยของ JourneyState

คลาส `JourneyState` มีเมธอดตัวช่วยและคุณสมบัติที่คุณสามารถใช้เพื่อปรับแต่งพฤติกรรมของ journey

| เมธอด / คุณสมบัติ | คำอธิบาย |
| --- | --- |
| [`nextStep()`](#next-step) | นำทางไปยังขั้นตอนถัดไปพร้อมการตรวจสอบ คืนค่า `Future<bool>` |
| [`previousStep()`](#previous-step) | นำทางไปยังขั้นตอนก่อนหน้า คืนค่า `Future<bool>` |
| [`onBackPressed()`](#on-back-pressed) | ตัวช่วยสำหรับนำทางไปยังขั้นตอนก่อนหน้า |
| [`onComplete()`](#on-complete) | ถูกเรียกเมื่อ journey เสร็จสมบูรณ์ (ที่ขั้นตอนสุดท้าย) |
| [`onBeforeNext()`](#on-before-next) | ถูกเรียกก่อนนำทางไปยังขั้นตอนถัดไป |
| [`onAfterNext()`](#on-after-next) | ถูกเรียกหลังนำทางไปยังขั้นตอนถัดไป |
| [`canContinue()`](#can-continue) | การตรวจสอบความถูกต้องก่อนนำทางไปยังขั้นตอนถัดไป |
| [`isFirstStep`](#is-first-step) | คืนค่า true หากเป็นขั้นตอนแรกใน journey |
| [`isLastStep`](#is-last-step) | คืนค่า true หากเป็นขั้นตอนสุดท้ายใน journey |
| [`currentStep`](#current-step) | คืนค่าดัชนีขั้นตอนปัจจุบัน (เริ่มจาก 0) |
| [`totalSteps`](#total-steps) | คืนค่าจำนวนขั้นตอนทั้งหมด |
| [`completionPercentage`](#completion-percentage) | คืนค่าเปอร์เซ็นต์ความสำเร็จ (0.0 ถึง 1.0) |
| [`goToStep(int index)`](#go-to-step) | ข้ามไปยังขั้นตอนเฉพาะตามดัชนี |
| [`goToNextStep()`](#go-to-next-step) | ข้ามไปยังขั้นตอนถัดไป (ไม่มีการตรวจสอบ) |
| [`goToPreviousStep()`](#go-to-previous-step) | ข้ามไปยังขั้นตอนก่อนหน้า (ไม่มีการตรวจสอบ) |
| [`goToFirstStep()`](#go-to-first-step) | ข้ามไปยังขั้นตอนแรก |
| [`goToLastStep()`](#go-to-last-step) | ข้ามไปยังขั้นตอนสุดท้าย |
| [`exitJourney()`](#exit-journey) | ออกจาก journey โดยการ pop root navigator |
| [`resetCurrentStep()`](#reset-current-step) | รีเซ็ต state ของขั้นตอนปัจจุบัน |
| [`onJourneyComplete`](#on-journey-complete) | Callback เมื่อ journey เสร็จสมบูรณ์ (override ในขั้นตอนสุดท้าย) |
| [`buildJourneyPage()`](#build-journey-page) | สร้างหน้า journey แบบเต็มจอพร้อม Scaffold |


<div id="next-step"></div>

#### nextStep

เมธอด `nextStep` นำทางไปยังขั้นตอนถัดไปพร้อมการตรวจสอบเต็มรูปแบบ มันทำงานผ่านวงจรชีวิต: `canContinue()` -> `onBeforeNext()` -> นำทางหรือ `onComplete()` -> `onAfterNext()`

คุณสามารถส่ง `force: true` เพื่อข้ามการตรวจสอบและนำทางโดยตรง

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        nextButton: Button.primary(
            text: isLastStep ? "Get Started" : "Continue",
            onPressed: nextStep, // ทำการตรวจสอบแล้วนำทาง
        ),
    );
}
```

เพื่อข้ามการตรวจสอบ:

``` dart
onPressed: () => nextStep(force: true),
```

<div id="previous-step"></div>

#### previousStep

เมธอด `previousStep` นำทางไปยังขั้นตอนก่อนหน้า คืนค่า `true` หากสำเร็จ, `false` หากอยู่ที่ขั้นตอนแรกแล้ว

``` dart
onPressed: () async {
    bool success = await previousStep();
    if (!success) {
      // อยู่ที่ขั้นตอนแรกแล้ว
    }
},
```

<div id="on-back-pressed"></div>

#### onBackPressed

เมธอด `onBackPressed` เป็นตัวช่วยที่เรียก `previousStep()` ภายใน

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyContent(
        content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
                ...
            ],
        ),
        backButton: isFirstStep ? null : Button.textOnly(
            text: "Back",
            textColor: Colors.black87,
            onPressed: onBackPressed,
        ),
    );
}
```

<div id="on-complete"></div>

#### onComplete

เมธอด `onComplete` จะถูกเรียกเมื่อ `nextStep()` ถูกทริกเกอร์ที่ขั้นตอนสุดท้าย (หลังจากผ่านการตรวจสอบแล้ว)

``` dart
@override
Future<void> onComplete() async {
    print("Journey completed");
}
```

<div id="on-before-next"></div>

#### onBeforeNext

เมธอด `onBeforeNext` จะถูกเรียกก่อนนำทางไปยังขั้นตอนถัดไป

เช่น หากคุณต้องการบันทึกข้อมูลก่อนนำทางไปยังขั้นตอนถัดไป คุณสามารถทำที่นี่

``` dart
@override
Future<void> onBeforeNext() async {
    // เช่น บันทึกข้อมูลลง session
    // session('onboarding', {
    //   'name': 'Anthony Gordon',
    //   'occupation': 'Software Engineer',
    // });
}
```

<div id="on-after-next"></div>

#### onAfterNext

เมธอด `onAfterNext` จะถูกเรียกหลังนำทางไปยังขั้นตอนถัดไป

``` dart
@override
Future<void> onAfterNext() async {
    // print('Navigated to the next step');
}
```

<div id="can-continue"></div>

#### canContinue

เมธอด `canContinue` จะถูกเรียกเมื่อ `nextStep()` ถูกทริกเกอร์ คืนค่า `false` เพื่อป้องกันการนำทาง

``` dart
@override
Future<bool> canContinue() async {
    // ดำเนินการตรวจสอบลอจิกของคุณที่นี่
    // คืนค่า true หาก journey สามารถดำเนินต่อได้ false หากไม่
    if (nameController.text.isEmpty) {
        showToastSorry(description: "Please enter your name");
        return false;
    }
    return true;
}
```

<div id="is-first-step"></div>

#### isFirstStep

คุณสมบัติ `isFirstStep` คืนค่า true หากเป็นขั้นตอนแรกใน journey

``` dart
backButton: isFirstStep ? null : Button.textOnly(
    text: "Back",
    textColor: Colors.black87,
    onPressed: onBackPressed,
),
```

<div id="is-last-step"></div>

#### isLastStep

คุณสมบัติ `isLastStep` คืนค่า true หากเป็นขั้นตอนสุดท้ายใน journey

``` dart
nextButton: Button.primary(
    text: isLastStep ? "Get Started" : "Continue",
    onPressed: nextStep,
),
```

<div id="current-step"></div>

#### currentStep

คุณสมบัติ `currentStep` คืนค่าดัชนีขั้นตอนปัจจุบัน (เริ่มจาก 0)

``` dart
Text("Step ${currentStep + 1} of $totalSteps"),
```

<div id="total-steps"></div>

#### totalSteps

คุณสมบัติ `totalSteps` คืนค่าจำนวนขั้นตอนทั้งหมดใน journey

<div id="completion-percentage"></div>

#### completionPercentage

คุณสมบัติ `completionPercentage` คืนค่าเปอร์เซ็นต์ความสำเร็จเป็นค่าตั้งแต่ 0.0 ถึง 1.0

``` dart
LinearProgressIndicator(value: completionPercentage),
```

<div id="go-to-step"></div>

#### goToStep

เมธอด `goToStep` ข้ามไปยังขั้นตอนเฉพาะตามดัชนีโดยตรง สิ่งนี้**ไม่**ทริกเกอร์การตรวจสอบ

``` dart
nextButton: Button.primary(
    text: "Skip to photos",
    onPressed: () {
        goToStep(2); // ข้ามไปยังขั้นตอนดัชนี 2
    },
),
```

<div id="go-to-next-step"></div>

#### goToNextStep

เมธอด `goToNextStep` ข้ามไปยังขั้นตอนถัดไปโดยไม่มีการตรวจสอบ หากอยู่ที่ขั้นตอนสุดท้ายแล้วจะไม่ทำอะไร

``` dart
onPressed: () {
    goToNextStep(); // ข้ามการตรวจสอบและไปยังขั้นตอนถัดไป
},
```

<div id="go-to-previous-step"></div>

#### goToPreviousStep

เมธอด `goToPreviousStep` ข้ามไปยังขั้นตอนก่อนหน้าโดยไม่มีการตรวจสอบ หากอยู่ที่ขั้นตอนแรกแล้วจะไม่ทำอะไร

``` dart
onPressed: () {
    goToPreviousStep();
},
```

<div id="go-to-first-step"></div>

#### goToFirstStep

เมธอด `goToFirstStep` ข้ามไปยังขั้นตอนแรก

``` dart
onPressed: () {
    goToFirstStep();
},
```

<div id="go-to-last-step"></div>

#### goToLastStep

เมธอด `goToLastStep` ข้ามไปยังขั้นตอนสุดท้าย

``` dart
onPressed: () {
    goToLastStep();
},
```

<div id="exit-journey"></div>

#### exitJourney

เมธอด `exitJourney` ออกจาก journey โดยการ pop root navigator

``` dart
onPressed: () {
    exitJourney(); // pop root navigator
},
```

<div id="reset-current-step"></div>

#### resetCurrentStep

เมธอด `resetCurrentStep` รีเซ็ต state ของขั้นตอนปัจจุบัน

``` dart
onPressed: () {
    resetCurrentStep();
},
```

<div id="on-journey-complete"></div>

### onJourneyComplete

getter `onJourneyComplete` สามารถถูก override ใน**ขั้นตอนสุดท้าย**ของ journey เพื่อกำหนดสิ่งที่จะเกิดขึ้นเมื่อผู้ใช้เสร็จสิ้น flow

``` dart
class _CompleteStepState extends JourneyState<CompleteStep> {
  _CompleteStepState() : super(
      navigationHubState: OnboardingNavigationHub.path.stateName());

  /// Callback เมื่อ journey เสร็จสมบูรณ์
  @override
  void Function()? get onJourneyComplete => () {
    // นำทางไปยังหน้าหลักหรือปลายทางถัดไป
    routeTo(HomePage.path);
  };

  @override
  Widget view(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.all(16),
      child: Column(
        children: [
          ...
          Button.primary(
            text: "Get Started",
            onPressed: onJourneyComplete, // ทริกเกอร์ callback เมื่อเสร็จสมบูรณ์
          ),
        ],
      ),
    );
  }
}
```

<div id="build-journey-page"></div>

### buildJourneyPage

เมธอด `buildJourneyPage` สร้างหน้า journey แบบเต็มจอที่ครอบด้วย `Scaffold` พร้อม `SafeArea`

``` dart
@override
Widget view(BuildContext context) {
    return buildJourneyPage(
      content: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Text('Welcome', style: Theme.of(context).textTheme.headlineMedium),
        ],
      ),
      nextButton: Button.primary(
        text: "Continue",
        onPressed: nextStep,
      ),
      backgroundColor: Colors.white,
    );
}
```

| คุณสมบัติ | ชนิด | คำอธิบาย |
| --- | --- | --- |
| `content` | `Widget` | เนื้อหาหลักของหน้า |
| `nextButton` | `Widget?` | widget ปุ่มถัดไป |
| `backButton` | `Widget?` | widget ปุ่มย้อนกลับ |
| `contentPadding` | `EdgeInsetsGeometry` | padding สำหรับเนื้อหา |
| `header` | `Widget?` | widget ส่วนหัว |
| `footer` | `Widget?` | widget ส่วนท้าย |
| `backgroundColor` | `Color?` | สีพื้นหลังของ Scaffold |
| `appBar` | `Widget?` | widget AppBar ที่เป็นตัวเลือก |
| `crossAxisAlignment` | `CrossAxisAlignment` | การจัดตำแหน่งแกนตัดของเนื้อหา |

<div id="navigating-within-a-tab"></div>

## การนำทางไปยัง widget ภายในแท็บ

คุณสามารถนำทางไปยัง widget ภายในแท็บโดยใช้ตัวช่วย `pushTo`

ภายในแท็บของคุณ คุณสามารถใช้ตัวช่วย `pushTo` เพื่อนำทางไปยัง widget อื่น

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage());
    }
    ...
}
```

คุณยังสามารถส่งข้อมูลไปยัง widget ที่คุณนำทางไป

``` dart
_HomeTabState extends State<HomeTab> {
    ...
    void _navigateToSettings() {
        pushTo(SettingsPage(), data: {"name": "Anthony"});
    }
    ...
}
```

<div id="tabs"></div>

## แท็บ

แท็บเป็นส่วนประกอบหลักของ Navigation Hub

คุณสามารถเพิ่มแท็บให้ Navigation Hub โดยใช้คลาส `NavigationTab` และ named constructor ของมัน

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.tab(
            title: "Home",
            page: HomeTab(),
            icon: Icon(Icons.home),
            activeIcon: Icon(Icons.home),
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

ในตัวอย่างด้านบน เราได้เพิ่มแท็บสองแท็บให้ Navigation Hub คือ Home และ Settings

คุณสามารถใช้แท็บชนิดต่างๆ ได้:

- `NavigationTab.tab()` - แท็บนำทางมาตรฐาน
- `NavigationTab.badge()` - แท็บพร้อมจำนวน badge
- `NavigationTab.alert()` - แท็บพร้อมตัวแสดง alert
- `NavigationTab.journey()` - แท็บสำหรับเลย์เอาต์ journey navigation

<div id="adding-badges-to-tabs"></div>

## การเพิ่ม Badge ให้แท็บ

เราทำให้การเพิ่ม badge ให้แท็บง่ายขึ้น

Badge เป็นวิธีที่ดีในการแสดงให้ผู้ใช้เห็นว่ามีสิ่งใหม่ในแท็บ

ตัวอย่างเช่น หากคุณมีแอปแชท คุณสามารถแสดงจำนวนข้อความที่ยังไม่ได้อ่านในแท็บแชท

เพื่อเพิ่ม badge ให้แท็บ คุณสามารถใช้ constructor `NavigationTab.badge`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.badge(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            initialCount: 10,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

ในตัวอย่างด้านบน เราได้เพิ่ม badge ให้แท็บ Chat พร้อมจำนวนเริ่มต้น 10

คุณยังสามารถอัปเดตจำนวน badge แบบ programmatic ได้

``` dart
/// เพิ่มจำนวน badge
BaseNavigationHub.stateActions.incrementBadgeCount(tab: 0);

/// อัปเดตจำนวน badge
BaseNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 5);

/// ล้างจำนวน badge
BaseNavigationHub.stateActions.clearBadgeCount(tab: 0);
```

โดยค่าเริ่มต้น จำนวน badge จะถูกจดจำ หากคุณต้องการ**ล้าง**จำนวน badge ในแต่ละ session คุณสามารถตั้งค่า `rememberCount` เป็น `false`

``` dart
0: NavigationTab.badge(
    title: "Chats",
    page: ChatTab(),
    icon: Icon(Icons.message),
    activeIcon: Icon(Icons.message),
    initialCount: 10,
    rememberCount: false,
),
```

<div id="adding-alerts-to-tabs"></div>

## การเพิ่ม Alert ให้แท็บ

คุณสามารถเพิ่ม alert ให้แท็บของคุณ

บางครั้งคุณอาจไม่ต้องการแสดงจำนวน badge แต่ต้องการแสดงตัวแสดง alert ให้ผู้ใช้

เพื่อเพิ่ม alert ให้แท็บ คุณสามารถใช้ constructor `NavigationTab.alert`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    NavigationHubLayout? layout(BuildContext context) => NavigationHubLayout.bottomNav();
    ...
    _MyNavigationHubState() : super(() => {
        0: NavigationTab.alert(
            title: "Chats",
            page: ChatTab(),
            icon: Icon(Icons.message),
            activeIcon: Icon(Icons.message),
            alertColor: Colors.red,
            alertEnabled: true,
            rememberAlert: false,
        ),
        1: NavigationTab.tab(
            title: "Settings",
            page: SettingsTab(),
            icon: Icon(Icons.settings),
            activeIcon: Icon(Icons.settings),
        ),
    });
```

สิ่งนี้จะเพิ่ม alert ให้แท็บ Chat ด้วยสีแดง

คุณยังสามารถอัปเดต alert แบบ programmatic ได้

``` dart
/// เปิดใช้งาน alert
BaseNavigationHub.stateActions.alertEnableTab(tab: 0);

/// ปิดใช้งาน alert
BaseNavigationHub.stateActions.alertDisableTab(tab: 0);
```

<div id="initial-index"></div>

## ดัชนีเริ่มต้น

โดยค่าเริ่มต้น Navigation Hub จะเริ่มที่แท็บแรก (ดัชนี 0) คุณสามารถเปลี่ยนได้โดยการ override getter `initialIndex`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    int get initialIndex => 1; // เริ่มที่แท็บที่สอง
    ...
}
```

<div id="maintaining-state"></div>

## การรักษา state

โดยค่าเริ่มต้น state ของ Navigation Hub จะถูกรักษาไว้

หมายความว่าเมื่อคุณนำทางไปยังแท็บ state ของแท็บจะถูกเก็บรักษา

หากคุณต้องการล้าง state ของแท็บทุกครั้งที่นำทางไป คุณสามารถตั้งค่า `maintainState` เป็น `false`

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    bool get maintainState => false;
    ...
}
```

<div id="on-tap"></div>

## onTap

คุณสามารถ override เมธอด `onTap` เพื่อเพิ่มลอจิกแบบกำหนดเองเมื่อแท็บถูกแตะ

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    @override
    onTap(int index) {
        // เพิ่มลอจิกแบบกำหนดเองที่นี่
        // เช่น ติดตาม analytics แสดงการยืนยัน ฯลฯ
        super.onTap(index); // ต้องเรียก super เสมอเพื่อจัดการการสลับแท็บ
    }
}
```

<div id="state-actions"></div>

## การกระทำ State

State actions เป็นวิธีในการโต้ตอบกับ Navigation Hub จากที่ใดก็ได้ในแอปของคุณ

นี่คือ state actions ที่คุณสามารถใช้:

``` dart
/// รีเซ็ตแท็บที่ดัชนีที่กำหนด
/// เช่น MyNavigationHub.stateActions.resetTabIndex(0);
resetTabIndex(int tabIndex);

/// เปลี่ยนแท็บปัจจุบันแบบ programmatic
/// เช่น MyNavigationHub.stateActions.currentTabIndex(2);
currentTabIndex(int tabIndex);

/// อัปเดตจำนวน badge
/// เช่น MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);
updateBadgeCount({required int tab, required int count});

/// เพิ่มจำนวน badge
/// เช่น MyNavigationHub.stateActions.incrementBadgeCount(tab: 0);
incrementBadgeCount({required int tab});

/// ล้างจำนวน badge
/// เช่น MyNavigationHub.stateActions.clearBadgeCount(tab: 0);
clearBadgeCount({required int tab});

/// เปิดใช้งาน alert สำหรับแท็บ
/// เช่น MyNavigationHub.stateActions.alertEnableTab(tab: 0);
alertEnableTab({required int tab});

/// ปิดใช้งาน alert สำหรับแท็บ
/// เช่น MyNavigationHub.stateActions.alertDisableTab(tab: 0);
alertDisableTab({required int tab});

/// นำทางไปยังหน้าถัดไปในเลย์เอาต์ journey
/// เช่น await MyNavigationHub.stateActions.nextPage();
Future<bool> nextPage();

/// นำทางไปยังหน้าก่อนหน้าในเลย์เอาต์ journey
/// เช่น await MyNavigationHub.stateActions.previousPage();
Future<bool> previousPage();
```

เพื่อใช้ state action คุณสามารถทำดังนี้:

``` dart
MyNavigationHub.stateActions.updateBadgeCount(tab: 0, count: 2);

MyNavigationHub.stateActions.resetTabIndex(0);

MyNavigationHub.stateActions.currentTabIndex(2); // สลับไปยังแท็บ 2

await MyNavigationHub.stateActions.nextPage(); // Journey: ไปยังหน้าถัดไป
```

<div id="loading-style"></div>

## สไตล์การโหลด

พร้อมใช้งานทันที Navigation Hub จะแสดง widget การโหลด**เริ่มต้น**ของคุณ (resources/widgets/loader_widget.dart) เมื่อแท็บกำลังโหลด

คุณสามารถปรับแต่ง `loadingStyle` เพื่ออัปเดตสไตล์การโหลด

| สไตล์ | คำอธิบาย |
| --- | --- |
| normal | สไตล์การโหลดเริ่มต้น |
| skeletonizer | สไตล์การโหลดแบบ skeleton |
| none | ไม่มีสไตล์การโหลด |

คุณสามารถเปลี่ยนสไตล์การโหลดได้ดังนี้:

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal();
// หรือ
@override
LoadingStyle get loadingStyle => LoadingStyle.skeletonizer();
```

หากคุณต้องการอัปเดต widget การโหลดในสไตล์ใดสไตล์หนึ่ง คุณสามารถส่ง `child` ให้ `LoadingStyle`

``` dart
@override
LoadingStyle get loadingStyle => LoadingStyle.normal(
    child: Center(
        child: Text("Loading..."),
    ),
);
```

ตอนนี้ เมื่อแท็บกำลังโหลด ข้อความ "Loading..." จะถูกแสดง

ตัวอย่างด้านล่าง:

``` dart
class _MyNavigationHubState extends NavigationHub<MyNavigationHub> {
    ...
    _MyNavigationHubState() : super(() async {

      await sleep(3); // จำลองการโหลด 3 วินาที

      return {
        0: NavigationTab.tab(
          title: "Home",
          page: HomeTab(),
        ),
        1: NavigationTab.tab(
          title: "Settings",
          page: SettingsTab(),
        ),
      };
    });

    @override
    LoadingStyle get loadingStyle => LoadingStyle.normal(
        child: Center(
            child: Text("Loading..."),
        ),
    );
    ...
}
```

<div id="creating-a-navigation-hub"></div>

## การสร้าง Navigation Hub

เพื่อสร้าง Navigation Hub คุณสามารถใช้ [Metro](/docs/{{$version}}/metro) ใช้คำสั่งด้านล่าง

``` bash
metro make:navigation_hub base
```

คำสั่งจะนำคุณผ่านการตั้งค่าแบบ interactive ที่คุณสามารถเลือกประเภทเลย์เอาต์และกำหนดแท็บหรือ journey state ของคุณ

สิ่งนี้จะสร้างไฟล์ `base_navigation_hub.dart` ในไดเรกทอรี `resources/pages/navigation_hubs/base/` พร้อม widget ลูกที่จัดระเบียบในโฟลเดอร์ย่อย `tabs/` หรือ `states/`
