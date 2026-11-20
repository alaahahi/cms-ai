<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  reps: Array,
  sales: Array,
  commissions: Array,
  targets: Array,
  filters: Object,
});

const showSale = ref(false);
const showTarget = ref(false);

const saleForm = useForm({
  sales_rep_id: props.reps?.[0]?.id || '',
  amount: '',
  cards_count: 1,
  notes: '',
});

const targetForm = useForm({
  sales_rep_id: props.reps?.[0]?.id || '',
  year: new Date().getFullYear(),
  month: new Date().getMonth() + 1,
  target_amount: '',
  target_cards: 0,
});

const submitSale = () => saleForm.post(route('erp.sales.store'), { onSuccess: () => { showSale.value = false; saleForm.reset('amount', 'notes'); } });
const submitTarget = () => targetForm.post(route('erp.sales.targets.store'), { onSuccess: () => { showTarget.value = false; } });
const payCommission = (id) => useForm({}).post(route('erp.sales.commissions.pay', id));
</script>

<template>
  <Head title="المبيعات" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">المبيعات والعمولات</h2>
          <p class="text-sm text-slate-500">الخطوة 3 من فلو ERP</p>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-lg bg-slate-900 px-3 py-2 text-sm text-white" @click="showTarget = !showTarget">هدف</button>
          <button type="button" class="rounded-lg bg-teal-600 px-3 py-2 text-sm text-white" @click="showSale = !showSale">بيع جديد</button>
        </div>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <form v-if="showSale" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-4" @submit.prevent="submitSale">
        <select v-model="saleForm.sales_rep_id" class="rounded-lg border-slate-300 dark:bg-slate-800" required>
          <option v-for="r in reps" :key="r.id" :value="r.id">{{ r.user?.name || ('مندوب #' + r.id) }}</option>
        </select>
        <input v-model="saleForm.amount" type="number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="المبلغ" required />
        <input v-model="saleForm.cards_count" type="number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="عدد البطاقات" />
        <button type="submit" class="rounded-lg bg-teal-600 text-sm text-white" :disabled="saleForm.processing">حفظ البيع</button>
      </form>

      <form v-if="showTarget" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-5" @submit.prevent="submitTarget">
        <select v-model="targetForm.sales_rep_id" class="rounded-lg border-slate-300 dark:bg-slate-800">
          <option v-for="r in reps" :key="r.id" :value="r.id">{{ r.user?.name || ('مندوب #' + r.id) }}</option>
        </select>
        <input v-model="targetForm.year" type="number" class="rounded-lg border-slate-300 dark:bg-slate-800" />
        <input v-model="targetForm.month" type="number" min="1" max="12" class="rounded-lg border-slate-300 dark:bg-slate-800" />
        <input v-model="targetForm.target_amount" type="number" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="هدف المبلغ" required />
        <button type="submit" class="rounded-lg bg-slate-900 text-sm text-white" :disabled="targetForm.processing">حفظ الهدف</button>
      </form>

      <div class="grid gap-4 xl:grid-cols-2">
        <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="border-b border-slate-100 px-4 py-3 font-semibold dark:border-slate-800">المبيعات</h3>
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800"><tr>
              <th class="px-4 py-2 text-right">#</th><th class="px-4 py-2 text-right">المندوب</th><th class="px-4 py-2 text-right">المبلغ</th><th class="px-4 py-2 text-right">التاريخ</th>
            </tr></thead>
            <tbody>
              <tr v-for="s in sales" :key="s.id" class="border-t border-slate-100 dark:border-slate-800">
                <td class="px-4 py-2">{{ s.id }}</td>
                <td class="px-4 py-2">{{ s.sales_rep?.user?.name || s.sales_rep_id }}</td>
                <td class="px-4 py-2">{{ s.amount }}</td>
                <td class="px-4 py-2">{{ s.sale_date }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <h3 class="border-b border-slate-100 px-4 py-3 font-semibold dark:border-slate-800">العمولات</h3>
          <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800"><tr>
              <th class="px-4 py-2 text-right">#</th><th class="px-4 py-2 text-right">المبلغ</th><th class="px-4 py-2 text-right">الحالة</th><th class="px-4 py-2 text-right"></th>
            </tr></thead>
            <tbody>
              <tr v-for="c in commissions" :key="c.id" class="border-t border-slate-100 dark:border-slate-800">
                <td class="px-4 py-2">{{ c.id }}</td>
                <td class="px-4 py-2">{{ c.amount }}</td>
                <td class="px-4 py-2">{{ c.status }}</td>
                <td class="px-4 py-2">
                  <button v-if="c.status !== 'paid'" type="button" class="text-teal-600 hover:underline" @click="payCommission(c.id)">دفع</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
