<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';

const props = defineProps({
  visits: Array,
  subscriptions: Array,
});

const showForm = ref(false);
const form = useForm({
  visit_type: 'lab',
  profile_id: '',
  husband_hb: '',
  wife_hb: '',
  notes: '',
});

const submit = () => {
  form.post(route('erp.visits.store'), {
    onSuccess: () => {
      showForm.value = false;
      form.reset('husband_hb', 'wife_hb', 'notes');
      form.visit_type = 'lab';
    },
  });
};
</script>

<template>
  <Head title="الزيارات" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h2 class="text-xl font-semibold text-slate-800 dark:text-white">الزيارات</h2>
          <p class="text-sm text-slate-500">الخطوة 2 — تسجيل زيارة مختبر / طبيب</p>
        </div>
        <button type="button" class="rounded-lg bg-teal-600 px-4 py-2 text-sm text-white" @click="showForm = !showForm">زيارة جديدة</button>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div v-if="$page.props.flash?.success" class="rounded-lg bg-teal-50 px-4 py-3 text-sm text-teal-800">{{ $page.props.flash.success }}</div>

      <form v-if="showForm" class="grid gap-3 rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 md:grid-cols-3" @submit.prevent="submit">
        <select v-model="form.visit_type" class="rounded-lg border-slate-300 dark:bg-slate-800">
          <option value="lab">مختبر</option>
          <option value="doctor">طبيب</option>
        </select>
        <select v-model="form.profile_id" class="rounded-lg border-slate-300 dark:bg-slate-800" required>
          <option value="">اختر اشتراك (legacy profile)</option>
          <option v-for="s in subscriptions" :key="s.id" :value="s.legacy_profile_id || s.id">
            {{ s.subscription_number }} — {{ s.status }}
          </option>
        </select>
        <input v-if="form.visit_type === 'lab'" v-model="form.husband_hb" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="HB زوج" />
        <input v-if="form.visit_type === 'lab'" v-model="form.wife_hb" class="rounded-lg border-slate-300 dark:bg-slate-800" placeholder="HB زوجة" />
        <input v-model="form.notes" class="rounded-lg border-slate-300 dark:bg-slate-800 md:col-span-2" placeholder="ملاحظات" />
        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white" :disabled="form.processing">حفظ</button>
      </form>

      <div class="overflow-x-auto rounded-xl bg-white shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-slate-600 dark:bg-slate-800">
            <tr>
              <th class="px-4 py-3 text-right">#</th>
              <th class="px-4 py-3 text-right">النوع</th>
              <th class="px-4 py-3 text-right">الاشتراك</th>
              <th class="px-4 py-3 text-right">المزود</th>
              <th class="px-4 py-3 text-right">التاريخ</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="v in visits" :key="v.id" class="border-t border-slate-100 dark:border-slate-800">
              <td class="px-4 py-3">{{ v.id }}</td>
              <td class="px-4 py-3">{{ v.visit_type }}</td>
              <td class="px-4 py-3">{{ v.subscription?.subscription_number || v.legacy_profile_id || '-' }}</td>
              <td class="px-4 py-3">{{ v.provider?.name_ar || v.provider_id || '-' }}</td>
              <td class="px-4 py-3">{{ v.visited_at || v.created_at }}</td>
            </tr>
            <tr v-if="!visits?.length">
              <td colspan="5" class="px-4 py-8 text-center text-slate-500">لا توجد زيارات</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
