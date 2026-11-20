<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';

defineProps({
  kpis: Object,
  costAnalysis: Object,
  alerts: Array,
});

const formatNumber = (num) => new Intl.NumberFormat('ar-EG').format(num || 0);
const snapshot = () => useForm({}).post(route('erp.analytics.snapshot'));
</script>

<template>
  <Head title="التحليلات" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">التحليلات</h2>
          <p class="text-sm text-slate-500">الخطوة 6 — مؤشرات الأداء والتكاليف</p>
        </div>
        <button type="button" class="rounded-lg bg-teal-600 px-4 py-2 text-sm text-white" @click="snapshot">حفظ Snapshot</button>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div v-for="(value, key) in kpis" :key="key" class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <p class="text-xs text-slate-500">{{ key }}</p>
          <p class="mt-1 text-xl font-bold">{{ formatNumber(value) }}</p>
        </div>
      </div>

      <div class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="font-semibold">تحليل التكلفة</h3>
          <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><dt>الإيرادات</dt><dd class="font-semibold">{{ formatNumber(costAnalysis.revenue) }}</dd></div>
            <div class="flex justify-between"><dt>تكاليف طبية</dt><dd>{{ formatNumber(costAnalysis.medical_costs) }}</dd></div>
            <div class="flex justify-between"><dt>عمولات</dt><dd>{{ formatNumber(costAnalysis.commission_costs) }}</dd></div>
            <div class="flex justify-between border-t pt-2"><dt>الهامش</dt><dd class="font-bold text-teal-600">{{ formatNumber(costAnalysis.gross_margin) }}</dd></div>
          </dl>
        </div>
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="font-semibold">التنبيهات</h3>
          <ul v-if="alerts?.length" class="mt-4 space-y-2 text-sm">
            <li v-for="(a, i) in alerts" :key="i" class="rounded-lg bg-slate-50 px-3 py-2 dark:bg-slate-800">{{ a.message }}</li>
          </ul>
          <p v-else class="mt-4 text-sm text-slate-500">لا توجد تنبيهات</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
