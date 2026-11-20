# 👋 مرحباً Agent!

## 🎯 أنت هنا لتنفيذ خطة إعادة هيكلة نظام التأمين الصحي

---

## ⚡ البدء السريع (3 خطوات فقط)

### 1️⃣ اقرأ التعليمات
```bash
cat .agents/restructure-plan/INSTRUCTIONS.md
```

### 2️⃣ اقرأ الخطة
```bash
less .agents/restructure-plan/restructure-plan.md
```

### 3️⃣ شغّل الاختبارات
```bash
php artisan test --testsuite=Baseline
```

---

## 📚 الملفات المتاحة

| الملف | الحجم | الوصف |
|------|------|--------|
| **INSTRUCTIONS.md** ⭐ | 8 KB | **ابدأ من هنا!** تعليمات كاملة |
| restructure-plan.md | 63 KB | الخطة الشاملة (عربي) |
| SUMMARY.md | 14 KB | الملخص التنفيذي |
| TESTING_SETUP.md | 7 KB | دليل الاختبارات |
| tests-README.md | 7 KB | شرح الـ 40 اختبار |

**المجلد:** `.agents/restructure-plan/`

---

## 🎯 المطلوب الآن

**Phase 1 - Foundation & Infrastructure** (3 أسابيع)

#### Week 1: Auth & Permissions
- تثبيت `spatie/laravel-permission`
- إنشاء 10 Roles
- Migration من UserType

#### Week 2: API Layer
- إنشاء `/api/v1`
- Base Controllers
- API Resources

#### Week 3: Service Layer
- نقل business logic
- Events & Listeners

**اقرأ التفاصيل في:** `restructure-plan.md` Section 4.1

---

## ⚠️ قواعد مهمة

✅ **افعل:**
- اقرأ الخطة أولاً
- اكتب tests للـ features الجديدة
- استخدم Parallel Run
- Commit بشكل تدريجي

❌ **لا تفعل:**
- لا تحذف الكود القديم فوراً
- لا تتخطى المراحل
- لا تنشر بدون اختبارات

---

## 📞 محتاج مساعدة؟

1. **INSTRUCTIONS.md** - تعليمات مفصلة
2. **restructure-plan.md** - الخطة الكاملة
3. **Section 6** - إدارة المخاطر وحل المشاكل

---

## ✅ Checklist

- [ ] قرأت INSTRUCTIONS.md
- [ ] قرأت Section 1-2 من الخطة
- [ ] شغّلت baseline tests
- [ ] فهمت Phase 1
- [ ] جاهز للبدء!

---

**البداية:** `.agents/restructure-plan/INSTRUCTIONS.md`

**Good Luck!** 🚀
