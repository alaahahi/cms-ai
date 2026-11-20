# 🤖 Agents Workspace

هذا المجلد مخصص لـ **Cursor Agents** للوصول السريع للخطط والتعليمات.

---

## 📁 المجلدات الموجودة

### 1. `/restructure-plan/` 
**خطة إعادة هيكلة نظام التأمين الصحي**

الملفات:
- `restructure-plan.md` - الخطة الشاملة (3,000+ سطر باللغة العربية)
- `SUMMARY.md` - الملخص التنفيذي
- `TESTING_SETUP.md` - دليل الاختبارات
- `tests-README.md` - شرح الاختبارات
- `INSTRUCTIONS.md` - **ابدأ من هنا!**

**للبدء:**
```bash
# اقرأ التعليمات أولاً
cat .agents/restructure-plan/INSTRUCTIONS.md

# ثم اقرأ الخطة
cat .agents/restructure-plan/restructure-plan.md
```

---

## 🎯 للـ Agents: Quick Start

### إذا كنت agent تنفيذ:
1. اقرأ `.agents/restructure-plan/INSTRUCTIONS.md`
2. اقرأ المرحلة المطلوبة من `restructure-plan.md`
3. تأكد من تشغيل baseline tests
4. ابدأ التنفيذ

### إذا كنت agent مراجعة:
1. اقرأ `.agents/restructure-plan/SUMMARY.md`
2. راجع التغييرات حسب الخطة
3. تأكد من وجود tests

---

## 📊 الحالة الحالية

- ✅ **Phase 0:** مكتمل (الخطة + Baseline Tests)
- ⏳ **Phase 1:** قيد الانتظار (Foundation & Infrastructure)
- ⏳ **Phase 2-6:** لاحقاً

**PR الحالي:** https://github.com/alaahahi/cms-ai/pull/1

---

## 🔍 بحث سريع

```bash
# البحث عن موضوع معين في الخطة
grep -r "keyword" .agents/restructure-plan/

# مثال: البحث عن Subscription Module
grep -n "Subscription" .agents/restructure-plan/restructure-plan.md
```

---

## ⚠️ ملاحظة مهمة

هذا المجلد `.agents/` **غير مخصص للـ commit** في Git (يجب إضافته لـ .gitignore).

الخطة الرسمية موجودة في:
- `/workspace/docs/`
- Pull Request #1

هذا المجلد فقط لتسهيل الوصول للـ agents أثناء العمل.

---

**Happy Coding!** 🚀
