# تعليمات للـ Agents - خطة إعادة هيكلة نظام التأمين الصحي

## 📋 نظرة عامة

هذا المجلد يحتوي على **الخطة الكاملة** لإعادة هيكلة نظام إدارة التأمين الصحي من Laravel 9 التقليدي إلى نظام معياري (Modular) متقدم.

---

## 📂 الملفات الموجودة

### 1. **restructure-plan.md** (الملف الرئيسي - اقرأه أولاً!)
الخطة الشاملة باللغة العربية (3,000+ سطر):
- تدقيق الوضع الحالي (26 Model)
- البنية المستهدفة (6 وحدات)
- ERD diagrams
- تخطيط Migration لكل Module
- خريطة طريق 20 أسبوع
- استراتيجية الاختبار
- إدارة المخاطر

### 2. **SUMMARY.md**
ملخص تنفيذي للخطة:
- الإنجازات المكتملة
- إحصائيات المشروع
- الخطوات التالية

### 3. **TESTING_SETUP.md**
دليل إعداد وتشغيل الاختبارات:
- متطلبات التشغيل
- أوامر التنفيذ
- حل المشاكل

### 4. **tests-README.md**
شرح مفصل عن الـ 40+ اختبار baseline الموجودة

---

## 🎯 للـ Agents: كيف تستخدم هذه الخطة

### المرحلة الحالية: ✅ Phase 0 (مكتملة)
- الخطة جاهزة
- 40+ اختبار baseline موجودة
- Factories جاهزة
- PR مفتوح: https://github.com/alaahahi/cms-ai/pull/1

### المرحلة التالية: Phase 1 - Foundation & Infrastructure

#### خطوات التنفيذ:

**1. Week 1: Auth & Permissions**
```bash
# اقرأ من الخطة: Section 4.1.1
# المطلوب:
- تثبيت spatie/laravel-permission
- إنشاء Roles الـ 10 الأساسية
- إنشاء Permissions لكل module
- Migration من UserType إلى Roles
- إنشاء Policies
```

**2. Week 2: API Layer**
```bash
# اقرأ من الخطة: Section 4.1.2
# المطلوب:
- إنشاء /api/v1 structure
- Base API Controller
- API Resources
- Exception Handler
- Rate Limiting
```

**3. Week 3: Service Layer**
```bash
# اقرأ من الخطة: Section 4.1.3
# المطلوب:
- Base Service pattern
- نقل business logic من Controllers
- Database Transactions
- Events & Listeners
```

---

## 📖 الأقسام المهمة في الخطة

### للقراءة الأولى:
1. **Section 1.1-1.6:** تدقيق الوضع الحالي
2. **Section 2.1-2.4:** البنية المستهدفة
3. **Section 4:** خريطة الطريق (اقرأ المرحلة المطلوبة فقط)

### للرجوع إليها أثناء التنفيذ:
- **Section 3:** تخطيط Migration (عند تحويل أي Model)
- **Section 5:** استراتيجية الاختبار (عند كتابة Tests)
- **Section 6:** إدارة المخاطر (عند مشاكل)

---

## 🔍 البحث السريع في الخطة

استخدم grep للبحث:

```bash
# مثال: البحث عن معلومات عن Subscription Module
grep -n "وحدة الاشتراكات" .agents/restructure-plan/restructure-plan.md

# البحث عن Migration strategy
grep -n "Migration Path" .agents/restructure-plan/restructure-plan.md

# البحث عن Test cases محددة
grep -n "test_" .agents/restructure-plan/restructure-plan.md
```

---

## ⚠️ قواعد مهمة للـ Agents

### ✅ افعل:
1. **اقرأ الخطة أولاً** قبل البدء بأي مرحلة
2. **اتبع الترتيب** - المراحل لها dependencies
3. **اكتب اختبارات** لكل feature جديد
4. **استخدم Parallel Run** - القديم والجديد معاً
5. **Commit بشكل تدريجي** - كل feature لوحده
6. **استخدم Transactions** في الـ migrations
7. **اعمل Backup** قبل أي data migration

### ❌ لا تفعل:
1. ❌ لا تحذف الكود القديم فوراً
2. ❌ لا تعمل migration بدون validation
3. ❌ لا تنسخ الـ code بدون فهم
4. ❌ لا تتخطى المراحل
5. ❌ لا تعمل force push على main
6. ❌ لا تغير الـ API بدون versioning
7. ❌ لا تنشر بدون اختبارات

---

## 📊 الوحدات الست المستهدفة

### 1. Subscription Module (المشتركون)
```
Models: Subscription, SubscriptionType, SubscriptionMember, Document
Services: SubscriptionService, RenewalService
```

### 2. Provider Module (الأطباء)
```
Models: Provider, ProviderService, Visit, Contract
Services: CoverageVerificationService, VisitRecordingService
```

### 3. Sales Module (المبيعات)
```
Models: SalesRep, Sale, Commission, Target
Services: SalesService, CommissionCalculationService
```

### 4. Customer Service Module (خدمة العملاء)
```
Models: Ticket, TicketMessage, TicketCategory
Services: TicketService
```

### 5. Accounting Module (الحسابات)
```
Models: Invoice, Payment, ProviderDue
Services: InvoiceService, PaymentService, DoubleEntryService
```

### 6. Analytics Module (التحليلات)
```
Services: AnalyticsService, CostAnalysisService, ReportGenerationService
```

---

## 🧪 الاختبارات

### تشغيل Baseline Tests:
```bash
# كل الاختبارات
php artisan test

# Baseline فقط
php artisan test --testsuite=Baseline

# مع coverage
php artisan test --coverage --min=80
```

### بعد كل feature جديد:
```bash
# تأكد أن baseline tests ما زالت تمرر
php artisan test --testsuite=Baseline

# شغل الاختبارات الجديدة
php artisan test tests/Feature/NewModule/
```

---

## 🔄 Workflow المقترح

### عند البدء بمرحلة جديدة:

1. **اقرأ** القسم الخاص بالمرحلة من `restructure-plan.md`
2. **افتح branch** جديد: `feature/phase-X-module-name`
3. **اكتب tests أولاً** للـ feature الجديد
4. **نفذ** التغييرات
5. **شغل** كل الاختبارات (baseline + جديد)
6. **Commit** التغييرات
7. **Push** و افتح PR
8. **Review** و merge
9. **كرر** للـ feature التالي

---

## 📞 لو واجهت مشاكل

### مشكلة في Migration:
- اقرأ **Section 6.2:** خطة التراجع
- استخدم Rollback Plan الموجود

### مشكلة في Tests:
- اقرأ **tests-README.md** → Troubleshooting
- اقرأ **TESTING_SETUP.md** → Common Issues

### مشكلة في التصميم:
- ارجع للـ **ERD diagrams** في Section 2.2
- راجع **Module Mapping** في Section 3

---

## 🎯 الهدف النهائي

تحويل النظام من:
```
❌ Monolithic Laravel app
❌ كود في Controllers
❌ بدون اختبارات
❌ تكرار في المصادقة
❌ بدون API منظم
```

إلى:
```
✅ Modular Architecture (6 modules)
✅ Service Layer Pattern
✅ 80%+ Test Coverage
✅ Roles & Permissions (spatie)
✅ RESTful API v1
✅ Zero Downtime Migration
✅ Mobile-Ready
```

---

## 📅 Timeline

- **Phase 0:** ✅ مكتمل (التحضير)
- **Phase 1:** 3 أسابيع (Foundation)
- **Phase 2:** 8 أسابيع (Core Modules)
- **Phase 3:** 4 أسابيع (Accounting & Analytics)
- **Phase 4:** 2 أسابيع (Mobile Prep)
- **Phase 5-6:** 4 أسابيع (Testing & Deployment)

**Total: 20 أسبوع**

---

## ✅ Checklist للـ Agent قبل البدء

- [ ] قرأت `restructure-plan.md` Section 1 (Current State)
- [ ] قرأت Section 2 (Target Architecture)
- [ ] فهمت الـ 6 modules
- [ ] شغّلت baseline tests وكلها pass
- [ ] فهمت Migration Strategy (Section 4.1)
- [ ] جاهز للبدء بـ Phase 1!

---

**ملاحظة:** هذه خطة شاملة ومفصلة. خذ وقتك في القراءة والفهم قبل التنفيذ.

**Good Luck!** 🚀
