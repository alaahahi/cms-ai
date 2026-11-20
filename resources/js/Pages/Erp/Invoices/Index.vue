<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

defineProps({
  invoices: Array,
  subscriptions: Array,
  providerDues: Array,
  filters: Object,
});

const showForm = ref(false);
const form = useForm({
  subscription_id: '',
  amount: '',
  notes: '',
});

const submit = () => form.post(route('erp.invoices.store'), {
  onSuccess: () => { showForm.value = false; form.reset(); },
});

const sync = () => useForm({}).post(route('erp.invoices.sync'));
const pay = (id) => {
  const amount = window.prompt('مبلغ الدفع (اتركه فارغاً لكامل الفاتورة)');
  useForm({ amount: amount || null, payment_method: 'cash' }).post(route('erp.invoices.pay', id));
};
const payDue = (id) => useForm({}).post(route('erp.provider-dues.pay', id));
</script>

<template>
  <Head title="الفواتير" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">المحاسبة</h2>
          <p class="text-sm text-slate-500">الخطوة 5 — فواتير ومدفوعات ومستحقات</p>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-lg border border-slate-300 px-3 py-2 text-sm" @click="sync">مزامنة من Orders</button>
          <button type="button" class="rounded-lg bg-teal-600 px-3 py-2 text-sm text-white" @click="showForm = !showForm">فاتورة جديدة</button>
        </div>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <form v-if="showForm" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-3" @submit.prevent="submit">
        <select v-model="form.subscription_id" class="rounded-lg border-slate-300 dark:bg-slate-800" required>
          <option value="">الاشتراك</option>
          <option v-for="s in subscriptions" :key="s.id" :value="s.id">{{ s.subscription_number }}</option>
        </select>
        <input v-model="form.amount" type="number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="المبلغ" required />
        <input v-model="form.notes" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="ملاحظات" />
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white md:col-span-3" :disabled="form.processing">حفظ الفاتورة</button>
      </form>

      <div class="grid gap-4 xl:grid-cols-2">
        <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="border-b border-slate-100 px-4 py-3 font-semibold dark:border-slate-800">الفواتير</h3>
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800"><tr>
              <th class="px-4 py-2 text-right">الرقم</th><th class="px-4 py-2 text-right">المبلغ</th><th class="px-4 py-2 text-right">الحالة</th><th class="px-4 py-2 text-right"></th>
            </tr></thead>
            <tbody>
              <tr v-for="inv in invoices" :key="inv.id" class="border-t border-slate-100 dark:border-slate-800">
                <td class="px-4 py-2">{{ inv.invoice_number }}</td>
                <td class="px-4 py-2">{{ inv.amount }} {{ inv.currency }}</td>
                <td class="px-4 py-2">{{ inv.status }}</td>
                <td class="px-4 py-2">
                  <button v-if="inv.status !== 'paid'" type="button" class="text-teal-600 hover:underline" @click="pay(inv.id)">دفع</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="border-b border-slate-100 px-4 py-3 font-semibold dark:border-slate-800">مستحقات المزودين</h3>
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 dark:bg-slate-800"><tr>
              <th class="px-4 py-2 text-right">#</th><th class="px-4 py-2 text-right">المبلغ</th><th class="px-4 py-2 text-right">الحالة</th><th class="px-4 py-2 text-right"></th>
            </tr></thead>
            <tbody>
              <tr v-for="d in providerDues" :key="d.id" class="border-t border-slate-100 dark:border-slate-800">
                <td class="px-4 py-2">{{ d.id }}</td>
                <td class="px-4 py-2">{{ d.amount }}</td>
                <td class="px-4 py-2">{{ d.status }}</td>
                <td class="px-4 py-2">
                  <button v-if="d.status !== 'paid'" type="button" class="text-teal-600 hover:underline" @click="payDue(d.id)">دفع</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
