<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

defineProps({
  tickets: Array,
  categories: Array,
  filters: Object,
});

const showForm = ref(false);
const form = useForm({
  subject: '',
  description: '',
  priority: 'medium',
  category_id: '',
  message: '',
});

const submit = () => form.post(route('erp.tickets.store'), {
  onSuccess: () => { showForm.value = false; form.reset(); form.priority = 'medium'; },
});

const sync = () => useForm({}).post(route('erp.tickets.sync'));
const transition = (id, status) => useForm({ status }).post(route('erp.tickets.transition', id));
const addMessage = (id) => {
  const message = window.prompt('نص الرسالة');
  if (message) useForm({ message }).post(route('erp.tickets.messages', id));
};
</script>

<template>
  <Head title="التذاكر" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">خدمة العملاء</h2>
          <p class="text-sm text-slate-500">الخطوة 4 — التذاكر والمتابعة</p>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" @click="sync">مزامنة من الطلبات المعلقة</button>
          <button type="button" class="rounded-lg bg-teal-600 px-3 py-2 text-sm text-white" @click="showForm = !showForm">تذكرة جديدة</button>
        </div>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <form v-if="showForm" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-2" @submit.prevent="submit">
        <input v-model="form.subject" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="الموضوع" required />
        <select v-model="form.priority" class="rounded-lg border-slate-300 dark:bg-slate-800">
          <option value="low">منخفض</option>
          <option value="medium">متوسط</option>
          <option value="high">مرتفع</option>
        </select>
        <select v-model="form.category_id" class="rounded-lg border-slate-300 dark:bg-slate-800">
          <option value="">تصنيف</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name_ar || c.name_en }}</option>
        </select>
        <input v-model="form.message" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="أول رسالة" />
        <textarea v-model="form.description" class="rounded-lg border-slate-300 dark:bg-slate-800 md:col-span-2" rows="3" placeholder="الوصف" />
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white md:col-span-2" :disabled="form.processing">حفظ</button>
      </form>

      <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800">
            <tr>
              <th class="px-4 py-3 text-right">#</th>
              <th class="px-4 py-3 text-right">الموضوع</th>
              <th class="px-4 py-3 text-right">الأولوية</th>
              <th class="px-4 py-3 text-right">الحالة</th>
              <th class="px-4 py-3 text-right">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="t in tickets" :key="t.id" class="border-t border-slate-100 dark:border-slate-800">
              <td class="px-4 py-3">{{ t.id }}</td>
              <td class="px-4 py-3 font-medium">{{ t.subject }}</td>
              <td class="px-4 py-3">{{ t.priority }}</td>
              <td class="px-4 py-3">{{ t.status }}</td>
              <td class="px-4 py-3">
                <div class="flex flex-wrap gap-2">
                  <button type="button" class="text-teal-600 hover:underline" @click="addMessage(t.id)">رسالة</button>
                  <button type="button" class="text-amber-600 hover:underline" @click="transition(t.id, 'in_progress')">قيد المعالجة</button>
                  <button type="button" class="text-slate-700 hover:underline" @click="transition(t.id, 'resolved')">حل</button>
                  <button type="button" class="text-rose-600 hover:underline" @click="transition(t.id, 'closed')">إغلاق</button>
                </div>
              </td>
            </tr>
            <tr v-if="!tickets?.length">
              <td colspan="5" class="px-4 py-8 text-center text-slate-500">لا توجد تذاكر</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
