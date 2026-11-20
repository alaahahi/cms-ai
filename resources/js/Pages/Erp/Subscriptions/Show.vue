<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({ subscription: Object });
</script>

<template>
  <Head :title="`اشتراك ${subscription.subscription_number}`" />
  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between gap-3">
        <h2 class="text-xl font-semibold text-slate-800 dark:text-white">{{ subscription.subscription_number }}</h2>
        <Link :href="route('erp.subscriptions.index')" class="text-sm text-teal-600">رجوع</Link>
      </div>
    </template>

    <div class="space-y-4 px-4 py-6 sm:px-6 lg:px-8">
      <div class="grid gap-4 md:grid-cols-3">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <p class="text-xs text-slate-500">الحالة</p>
          <p class="mt-1 font-semibold">{{ subscription.status }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <p class="text-xs text-slate-500">النوع</p>
          <p class="mt-1 font-semibold">{{ subscription.type?.name_ar || subscription.type?.name_en }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
          <p class="text-xs text-slate-500">الهاتف</p>
          <p class="mt-1 font-semibold">{{ subscription.phone_number }}</p>
        </div>
      </div>

      <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900">
        <h3 class="mb-3 font-semibold">الأعضاء</h3>
        <ul class="space-y-2 text-sm">
          <li v-for="m in subscription.members" :key="m.id" class="flex justify-between border-b border-slate-100 py-2 dark:border-slate-800">
            <span>{{ m.name }}</span>
            <span class="text-slate-500">{{ m.relation }}</span>
          </li>
        </ul>
      </div>

      <div class="flex flex-wrap gap-2">
        <Link :href="route('erp.visits.index')" class="rounded-lg bg-slate-900 px-4 py-2 text-sm text-white">تسجيل زيارة</Link>
        <Link :href="route('erp.invoices.index')" class="rounded-lg bg-teal-600 px-4 py-2 text-sm text-white">إنشاء فاتورة</Link>
        <Link :href="route('erp.tickets.index')" class="rounded-lg border border-slate-300 px-4 py-2 text-sm">فتح تذكرة</Link>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
