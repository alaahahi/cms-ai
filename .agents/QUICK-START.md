# 🚀 Quick Start للـ Agents

## للبدء فوراً:

### 1️⃣ اقرأ التعليمات
```bash
cat .agents/restructure-plan/INSTRUCTIONS.md
```

### 2️⃣ اقرأ الخطة حسب المرحلة

**Phase 1 (الحالية):**
```bash
# Section 4.1: Foundation & Infrastructure
less .agents/restructure-plan/restructure-plan.md
# ابحث عن: "المرحلة 1"
```

### 3️⃣ شغّل الاختبارات
```bash
cd /workspace
php artisan test --testsuite=Baseline
```

---

## 📂 هيكل المجلد

```
.agents/
├── README.md              ← نظرة عامة
├── QUICK-START.md         ← هذا الملف
└── restructure-plan/
    ├── INSTRUCTIONS.md    ← ابدأ من هنا! ⭐
    ├── restructure-plan.md (63 KB) ← الخطة الكاملة
    ├── SUMMARY.md         ← الملخص
    ├── TESTING_SETUP.md   ← دليل الاختبارات
    └── tests-README.md    ← شرح الـ 40 اختبار
```

---

## 🎯 المراحل

- ✅ **Phase 0:** مكتمل
- 👉 **Phase 1:** الحالية (Foundation)
  - Week 1: Auth & Permissions
  - Week 2: API Layer
  - Week 3: Service Layer

---

## 💡 نصائح سريعة

1. **اقرأ INSTRUCTIONS.md أولاً** - فيها كل التفاصيل
2. **استخدم grep للبحث**:
   ```bash
   grep -n "Subscription Module" .agents/restructure-plan/restructure-plan.md
   ```
3. **baseline tests لازم تظل تمرر** دائماً
4. **اتبع الترتيب** - المراحل لها dependencies

---

## ⚡ أوامر مفيدة

```bash
# قراءة الملخص السريع
cat .agents/restructure-plan/SUMMARY.md

# البحث في الخطة
grep -i "keyword" .agents/restructure-plan/*.md

# تشغيل الاختبارات
php artisan test

# حجم الملفات
du -h .agents/restructure-plan/*
```

---

**الملف الأهم:** `.agents/restructure-plan/INSTRUCTIONS.md` 👈 ابدأ من هنا!
