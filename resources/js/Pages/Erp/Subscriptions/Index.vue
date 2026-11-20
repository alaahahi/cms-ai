<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  subscriptions: Array,
  types: Array,
  filters: Object,
});

const showForm = ref(false);
const form = useForm({
  name: '',
  phone_number: '',
  card_number: '',
  card_id: props.types?.[0]?.legacy_card_id || props.types?.[0]?.id || null,
  address: '',
  family_name: '',
  source: 'erp',
});

const submit = () => {
  form.post(route('erp.subscriptions.store'), {
    onSuccess: () => {
      showForm.value = false;
      form.reset();
    },
  });
};
</script>

<template>
  <Head title="الاشتراكات" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">الاشتراكات</h2>
          <p class="text-sm text-slate-500">الخطوة 1 من فلو ERP</p>
        </div>
        <button type="button" class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700" @click="showForm = !showForm">
          {{ showForm ? 'إغلاق' : 'اشتراك جديد' }}
        </button>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <form v-if="showForm" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-3" @submit.prevent="submit">
        <input v-model="form.name" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="اسم المشترك" required />
        <input v-model="form.phone_number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="الهاتف" required />
        <input v-model="form.card_number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="رقم البطاقة" required />
        <select v-model="form.card_id" class="rounded-lg border-slate-300 dark:bg-slate-800">
          <option :value="null">نوع الاشتراك</option>
          <option v-for="t in types" :key="t.id" :value="t.legacy_card_id || t.id">{{ t.name_ar || t.name_en || t.name }}</option>
        </select>
        <input v-model="form.family_name" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="أفراد العائلة (مفصولة بفاصلة)" />
        <input v-model="form.address" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="العنوان" />
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white md:col-span-3" :disabled="form.processing">حفظ الاشتراك</button>
      </form>

      <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
            <tr>
              <th class="px-4 py-3 text-right">الرقم</th>
              <th class="px-4 py-3 text-right">النوع</th>
              <th class="px-4 py-3 text-right">الهاتف</th>
              <th class="px-4 py-3 text-right">الحالة</th>
              <th class="px-4 py-3 text-right">الأعضاء</th>
              <th class="px-4 py-3 text-right"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in subscriptions" :key="item.id" class="border-t border-slate-100 dark:border-slate-800">
              <td class="px-4 py-3 font-medium">{{ item.subscription_number }}</td>
              <td class="px-4 py-3">{{ item.type?.name_ar || item.type?.name_en || '-' }}</td>
              <td class="px-4 py-3">{{ item.phone_number }}</td>
              <td class="px-4 py-3">
                <span class="rounded-full bg-teal-50 px-2 py-1 text-xs text-teal-700">{{ item.status }}</span>
              </td>
              <td class="px-4 py-3">{{ item.members?.length || 0 }}</td>
              <td class="px-4 py-3">
                <Link :href="route('erp.subscriptions.show', item.id)" class="text-teal-600 hover:underline">عرض</Link>
              </td>
            </tr>
            <tr v-if="!subscriptions?.length">
              <td colspan="6" class="px-4 py-8 text-center text-slate-500">لا توجد اشتراكات بعد</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
