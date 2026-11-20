<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
  kpis: Object,
  costAnalysis: Object,
  alerts: Array,
  flow: Array,
});

const formatNumber = (num) => new Intl.NumberFormat('ar-EG').format(num || 0);
</script>

<template>
  <Head title="لوحة ERP" />

  <AuthenticatedLayout>
    <template #header>
      <div>
        <h2 class="text-xl font-semibold leading-tight text-slate-800 dark:text-white">لوحة تدفق العمل ERP</h2>
        <p class="mt-1 text-sm text-slate-500">اشتراك → زيارة → مبيعات → تذاكر → محاسبة → تحليلات</p>
      </div>
    </template>

    <div class="space-y-6 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800 dark:bg-teal-900/30 dark:text-teal-200">
        {{ $page.props.flash.success }}
      </div>

      <section class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <p class="text-xs text-slate-500">اشتراكات نشطة</p>
          <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ formatNumber(kpis.active_subscriptions) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <p class="text-xs text-slate-500">زيارات</p>
          <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-white">{{ formatNumber(kpis.visits_total) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <p class="text-xs text-slate-500">تذاكر مفتوحة</p>
          <p class="mt-1 text-2xl font-bold text-amber-600">{{ formatNumber(kpis.open_tickets) }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <p class="text-xs text-slate-500">فواتير غير مدفوعة</p>
          <p class="mt-1 text-2xl font-bold text-rose-600">{{ formatNumber(kpis.unpaid_invoices) }}</p>
        </div>
      </section>

      <section>
        <h3 class="mb-3 text-sm font-semibold text-slate-700 dark:text-slate-200">مسار العمل</h3>
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
          <Link
            v-for="step in flow"
            :key="step.key"
            :href="route(step.route)"
            class="group rounded-xl bg-slate-900 p-5 text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-slate-800"
          >
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-xs text-teal-300">الخطوة {{ step.step }}</p>
                <h4 class="mt-1 text-lg font-semibold">{{ step.title }}</h4>
                <p class="mt-2 text-sm text-slate-300">{{ step.description }}</p>
              </div>
              <span class="rounded-full bg-teal-500/20 px-3 py-1 text-xs text-teal-200">فتح</span>
            </div>
          </Link>
        </div>
      </section>

      <section class="grid gap-4 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">تحليل التكلفة</h3>
          <dl class="mt-4 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">الإيرادات</dt><dd class="font-semibold">{{ formatNumber(costAnalysis.revenue) }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">تكاليف طبية</dt><dd>{{ formatNumber(costAnalysis.medical_costs) }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">عمولات</dt><dd>{{ formatNumber(costAnalysis.commission_costs) }}</dd></div>
            <div class="flex justify-between border-t border-slate-100 pt-2 dark:border-slate-700">
              <dt class="font-medium">الهامش</dt>
              <dd class="font-bold text-teal-600">{{ formatNumber(costAnalysis.gross_margin) }}</dd>
            </div>
          </dl>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
          <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-100">التنبيهات</h3>
          <ul v-if="alerts?.length" class="mt-4 space-y-2">
            <li
              v-for="(alert, idx) in alerts"
              :key="idx"
              class="rounded-lg px-3 py-2 text-sm"
              :class="{
                'bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-200': alert.level === 'warning',
                'bg-rose-50 text-rose-800 dark:bg-rose-900/30 dark:text-rose-200': alert.level === 'danger',
                'bg-sky-50 text-sky-800 dark:bg-sky-900/30 dark:text-sky-200': alert.level === 'info',
              }"
            >
              {{ alert.message }}
            </li>
          </ul>
          <p v-else class="mt-4 text-sm text-slate-500">لا توجد تنبيهات حالياً</p>
        </div>
      </section>
    </div>
  </AuthenticatedLayout>
</template>
