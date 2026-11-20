<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
  providers: Array,
  services: Array,
});

const form = useForm({
  subscription_number: '',
  provider_service_id: '',
});

const verify = () => form.post(route('erp.providers.verify'));
</script>

<template>
  <Head title="المزودين" />
  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white">المزودين والتغطية</h2>
        <p class="text-sm text-slate-500">الخطوة 2 — شبكة الخدمة</p>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>
      <div v-if="$page.props.flash?.coverageResult" class="rounded-lg bg-slate-100 px-4 py-3 text-sm dark:bg-slate-800">
        نتيجة التغطية: {{ JSON.stringify($page.props.flash.coverageResult) }}
      </div>

      <form class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-3" @submit.prevent="verify">
        <input v-model="form.subscription_number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="رقم الاشتراك / البطاقة" required />
        <select v-model="form.provider_service_id" class="rounded-lg border-slate-300 dark:bg-slate-800" required>
          <option value="">اختر خدمة مزود</option>
          <option v-for="s in services" :key="s.id" :value="s.id">
            #{{ s.id }} — {{ s.name_ar || s.name_en || s.name }} ({{ s.provider?.name_ar || s.provider?.name }})
          </option>
        </select>
        <button type="submit" class="rounded-lg bg-teal-600 px-4 py-2 text-sm text-white" :disabled="form.processing">تحقق التغطية</button>
      </form>

      <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800">
            <tr>
              <th class="px-4 py-3 text-right">#</th>
              <th class="px-4 py-3 text-right">الاسم</th>
              <th class="px-4 py-3 text-right">التخصص</th>
              <th class="px-4 py-3 text-right">الخدمات</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="p in providers" :key="p.id" class="border-t border-slate-100 dark:border-slate-800">
              <td class="px-4 py-3">{{ p.id }}</td>
              <td class="px-4 py-3 font-medium">{{ p.name_ar || p.name }}</td>
              <td class="px-4 py-3">{{ p.specialty_ar || p.specialty }}</td>
              <td class="px-4 py-3">{{ p.services?.length || 0 }}</td>
            </tr>
            <tr v-if="!providers?.length">
              <td colspan="4" class="px-4 py-8 text-center text-slate-500">لا يوجد مزودون — سيتم المزامنة تلقائياً من النظام القديم</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
