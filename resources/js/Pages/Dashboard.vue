<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { ref, computed } from "vue";

const dateValue = ref({
  startDate: "",
  endDate: "",
});
const countComp = ref();
const formatter = ref({
  date: "D/MM/YYYY",
  month: "MM",
});
const options = ref({
  shortcuts: {
    today: "اليوم",
    yesterday: "البارحة",
    past: (period) => period + " قبل يوم",
    currentMonth: "الشهر الحالي",
    pastMonth: "الشهر السابق",
  },
  footer: {
    apply: "Terapkan",
    cancel: "Batal",
  },
});
const dDate = (date) => {
  return date >= new Date();
};
const getcountComp = async () => {
  const response = await fetch(
    `getcount?start=${dateValue.value.startDate}&end=${dateValue.value.endDate}`
  );
  countComp.value = await response.json();
};
getcountComp();

const props = defineProps({
  url: String,
  user: String,
  profile: String,
  comp: String,
  working: String,
  cardCompany: String,
  cardRegister: String,
  balance: String,
  numbersStats: Array,
  // إحصائيات جديدة
  cardsStats: Array,
  statusStats: Object,
  salesStats: Array,
  sourceStats: Array,
  monthlyStats: Array,
  whatsappStats: Object,
  appointmentStats: Object,
  additionalStats: Object,
});

// Helper function to format numbers
const formatNumber = (num) => {
  if (!num) return '0';
  return new Intl.NumberFormat('ar-EG').format(num);
};

// Helper function to get status name
const getStatusName = (status) => {
  const statusNames = {
    'pending_delivery': 'إنتظار تسليم الصندوق',
    'delivered': 'تم التسليم',
    'completed': 'مكتمل',
    'pending': 'معلق',
    'total': 'المجموع'
  };
  return statusNames[status] || status;
};

// Helper function to get source name in Arabic
const getSourceName = (source) => {
  const sourceNames = {
    'mobile': 'التطبيق',
    'pendingRequest': 'طلب معلق',
    'unknown': 'غير معروف',
    null: 'غير محدد'
  };
  return sourceNames[source] || source;
};
</script>

<template>
  <Head title="لوحة القيادة" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        لوحة القيادة
      </h2>
    </template>

    <!-- Dashboard الرئيسي -->
    <div class="py-12" v-if="$page.props.auth.user.type_id!=8&&$page.props.auth.user.type_id!=9">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <!-- إحصائيات عامة -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <!-- إجمالي البطاقات -->
          <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-blue-100 text-sm font-medium mb-1">إجمالي البطاقات</p>
                <p class="text-3xl font-bold">{{ formatNumber(statusStats?.total || 0) }}</p>
              </div>
              <div class="bg-white/20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
              </div>
            </div>
          </div>

          <!-- البطاقات المسلمة -->
          <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-green-100 text-sm font-medium mb-1">تم التسليم</p>
                <p class="text-3xl font-bold">{{ formatNumber(statusStats?.delivered || 0) }}</p>
              </div>
              <div class="bg-white/20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>

          <!-- البطاقات المكتملة -->
          <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-purple-100 text-sm font-medium mb-1">مكتمل</p>
                <p class="text-3xl font-bold">{{ formatNumber(statusStats?.completed || 0) }}</p>
              </div>
              <div class="bg-white/20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
          </div>

          <!-- البطاقات المعلقة -->
          <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-yellow-100 text-sm font-medium mb-1">معلق</p>
                <p class="text-3xl font-bold">{{ formatNumber(statusStats?.pending || 0) }}</p>
              </div>
              <div class="bg-white/20 rounded-full p-3">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- البطاقات بحسب النوع -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">البطاقات بحسب النوع</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="card in cardsStats" 
              :key="card.id"
              class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-600 mb-1">نوع البطاقة</p>
                  <p class="text-lg font-semibold text-gray-800">{{ card.name_ar || card.name_en }}</p>
                </div>
                <div class="bg-blue-100 rounded-full px-4 py-2">
                  <span class="text-blue-700 font-bold text-xl">{{ formatNumber(card.count) }}</span>
                </div>
              </div>
            </div>
          </div>
          <div v-if="!cardsStats || cardsStats.length === 0" class="text-center py-8 text-gray-500">
            <p>لا توجد بيانات</p>
          </div>
        </div>

        <!-- البطاقات بحسب الحالة -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">البطاقات بحسب الحالة</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 border-r-4 border-gray-400">
              <p class="text-sm text-gray-600 mb-1">إنتظار تسليم الصندوق</p>
              <p class="text-2xl font-bold text-gray-800">{{ formatNumber(statusStats?.pending_delivery || 0) }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border-r-4 border-blue-400">
              <p class="text-sm text-gray-600 mb-1">تم التسليم</p>
              <p class="text-2xl font-bold text-blue-700">{{ formatNumber(statusStats?.delivered || 0) }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-r-4 border-green-400">
              <p class="text-sm text-gray-600 mb-1">مكتمل</p>
              <p class="text-2xl font-bold text-green-700">{{ formatNumber(statusStats?.completed || 0) }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-r-4 border-yellow-400">
              <p class="text-sm text-gray-600 mb-1">معلق</p>
              <p class="text-2xl font-bold text-yellow-700">{{ formatNumber(statusStats?.pending || 0) }}</p>
            </div>
          </div>
        </div>

        <!-- أفضل المندوبين -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">أفضل المندوبين</h3>
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">#</th>
                  <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">اسم المندوب</th>
                  <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">عدد البطاقات</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="(sale, index) in salesStats" :key="sale.id" class="hover:bg-gray-50">
                  <td class="px-4 py-3 text-center text-gray-700">{{ index + 1 }}</td>
                  <td class="px-4 py-3 text-right text-gray-800 font-medium">{{ sale.name }}</td>
                  <td class="px-4 py-3 text-center">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                      {{ formatNumber(sale.count) }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-if="!salesStats || salesStats.length === 0" class="text-center py-8 text-gray-500">
            <p>لا توجد بيانات</p>
          </div>
        </div>

        <!-- البطاقات بحسب المصدر -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">البطاقات بحسب المصدر</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="source in sourceStats" 
              :key="source.source"
              class="border border-gray-200 rounded-lg p-4"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-sm text-gray-600 mb-1">المصدر</p>
                  <p class="text-lg font-semibold text-gray-800">{{ getSourceName(source.source) }}</p>
                </div>
                <div class="bg-indigo-100 rounded-full px-4 py-2">
                  <span class="text-indigo-700 font-bold text-xl">{{ formatNumber(source.count) }}</span>
                </div>
              </div>
            </div>
          </div>
          <div v-if="!sourceStats || sourceStats.length === 0" class="text-center py-8 text-gray-500">
            <p>لا توجد بيانات</p>
          </div>
        </div>

        <!-- إحصائيات WhatsApp -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">إحصائيات WhatsApp</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-green-50 rounded-lg p-4 border-r-4 border-green-400">
              <p class="text-sm text-gray-600 mb-1">إجمالي الرسائل</p>
              <p class="text-2xl font-bold text-green-700">{{ formatNumber(whatsappStats?.total || 0) }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 border-r-4 border-blue-400">
              <p class="text-sm text-gray-600 mb-1">تم الإرسال</p>
              <p class="text-2xl font-bold text-blue-700">{{ formatNumber(whatsappStats?.sent || 0) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-r-4 border-red-400">
              <p class="text-sm text-gray-600 mb-1">فشل الإرسال</p>
              <p class="text-2xl font-bold text-red-700">{{ formatNumber(whatsappStats?.failed || 0) }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-r-4 border-yellow-400">
              <p class="text-sm text-gray-600 mb-1">في الانتظار</p>
              <p class="text-2xl font-bold text-yellow-700">{{ formatNumber(whatsappStats?.pending || 0) }}</p>
            </div>
          </div>
          <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">اليوم</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(whatsappStats?.today || 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">هذا الأسبوع</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(whatsappStats?.this_week || 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">هذا الشهر</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(whatsappStats?.this_month || 0) }}</p>
            </div>
          </div>
        </div>

        <!-- إحصائيات المواعيد -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">إحصائيات المواعيد</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">إجمالي المواعيد</p>
              <p class="text-2xl font-bold text-gray-800">{{ formatNumber(appointmentStats?.total || 0) }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 border-r-4 border-green-400">
              <p class="text-sm text-gray-600 mb-1">مؤكد</p>
              <p class="text-2xl font-bold text-green-700">{{ formatNumber(appointmentStats?.confirmed || 0) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border-r-4 border-red-400">
              <p class="text-sm text-gray-600 mb-1">ملغي</p>
              <p class="text-2xl font-bold text-red-700">{{ formatNumber(appointmentStats?.cancelled || 0) }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border-r-4 border-yellow-400">
              <p class="text-sm text-gray-600 mb-1">في الانتظار</p>
              <p class="text-2xl font-bold text-yellow-700">{{ formatNumber(appointmentStats?.pending || 0) }}</p>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">اليوم</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(appointmentStats?.today || 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">هذا الأسبوع</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(appointmentStats?.this_week || 0) }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
              <p class="text-sm text-gray-600 mb-1">هذا الشهر</p>
              <p class="text-xl font-bold text-gray-800">{{ formatNumber(appointmentStats?.this_month || 0) }}</p>
            </div>
          </div>
        </div>

        <!-- إحصائيات إضافية -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">إحصائيات إضافية</h3>
          <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">إجمالي المستخدمين</p>
              <p class="text-2xl font-bold text-indigo-700">{{ formatNumber(additionalStats?.total_users || 0) }}</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-pink-50 to-pink-100 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">إجمالي أنواع البطاقات</p>
              <p class="text-2xl font-bold text-pink-700">{{ formatNumber(additionalStats?.total_cards || 0) }}</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">البطاقات النشطة</p>
              <p class="text-2xl font-bold text-teal-700">{{ formatNumber(additionalStats?.active_cards || 0) }}</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">إجمالي المحافظ</p>
              <p class="text-2xl font-bold text-orange-700">{{ formatNumber(additionalStats?.total_wallets || 0) }}</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-cyan-50 to-cyan-100 rounded-lg">
              <p class="text-sm text-gray-600 mb-2">إجمالي الرصيد</p>
              <p class="text-2xl font-bold text-cyan-700">{{ formatNumber(additionalStats?.total_balance || 0) }}</p>
            </div>
          </div>
        </div>

        <!-- الاتجاهات الشهرية (آخر 6 أشهر) -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h3 class="text-xl font-bold text-gray-800 mb-4 pb-3 border-b">الاتجاهات الشهرية (آخر 6 أشهر)</h3>
          <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <div 
              v-for="month in monthlyStats" 
              :key="month.month"
              class="border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition-shadow"
            >
              <p class="text-xs text-gray-500 mb-2">{{ month.month }}</p>
              <p class="text-2xl font-bold text-gray-800">{{ formatNumber(month.count) }}</p>
            </div>
          </div>
          <div v-if="!monthlyStats || monthlyStats.length === 0" class="text-center py-8 text-gray-500">
            <p>لا توجد بيانات</p>
          </div>
        </div>

        <!-- الإحصائيات القديمة (للتوافق) -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white dark:bg-gray-900">
            <div class="flex flex-col">
              <h2 class="mb-4 text-2xl font-bold">احصائيات
                -
                <span>
                  {{ countComp?.config.third_title_ar }}
                </span>
                -
                <span>
                  {{ countComp?.config.second_title_ar }}
                </span>
              </h2>

              <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div class="flex items-start rounded-xl bg-white p-4 shadow-lg">
                  <div
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-red-100 bg-red-50"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 text-red-400"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                      />
                    </svg>
                  </div>

                  <div class="mr-4">
                    <h2 class="font-semibold">البطاقات</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ profile }}</p>
                  </div>
                </div>
                <div
                  class="flex items-start rounded-xl bg-white p-4 shadow-lg"
                  v-if="$page.props.auth.user.type_id == 3"
                >
                  <div
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-red-100 bg-red-50"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 text-red-400"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                      />
                    </svg>
                  </div>

                  <div class="mr-4">
                    <h2 class="font-semibold">
                      البطاقات تم استلامها من الشركة
                    </h2>
                    <p class="mt-2 text-sm text-gray-500">{{ cardCompany }}</p>
                  </div>
                </div>
                <div
                  class="flex items-start rounded-xl bg-white p-4 shadow-lg"
                  v-if="$page.props.auth.user.type_id == 3"
                >
                  <div
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-red-100 bg-red-50"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 text-red-400"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                      />
                    </svg>
                  </div>

                  <div class="mr-4">
                    <h2 class="font-semibold">البطاقات تم تسجيلها</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ cardRegister }}</p>
                  </div>
                </div>
                <div
                  class="flex items-start rounded-xl bg-white p-4 shadow-lg"
                  v-if="$page.props.auth.user.type_id == 3"
                >
                  <div
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-red-100 bg-red-50"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 text-red-400"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                      />
                    </svg>
                  </div>
                  <div class="mr-4">
                    <h2 class="font-semibold">المحفظة</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ balance }}</p>
                  </div>
                </div>
              </div>
              <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div class="flex items-start rounded-xl bg-white p-4 shadow-lg" v-for="(user , index) in countComp?.data" :key="index">
                  <div
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-orange-100 bg-orange-50"
                  >
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-6 w-6 text-orange-400"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      stroke-width="2"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                      />
                    </svg>
                  </div>

                  <div class="mr-4">
                    <h2 class="font-semibold">{{ user?.user?.name }}</h2>
                    <p class="mt-2 text-sm text-gray-500">{{ user.count }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Dashboard للمستخدمين من نوع 8 و 9 -->
    <div v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white dark:bg-gray-900" style="border-radius: 8px">
          <div class="flex flex-row">
            <div class="basis-1/4">
              <button
                type="button"
                @click="getcountComp()"
                style="width: 70%"
                className="px-6 mb-12 mx-2 py-2 font-bold text-white bg-rose-500 rounded"
              >
                فلترة
              </button>
            </div>
            <div class="basis-3/4" style="direction: ltr">
              <vue-tailwind-datepicker
                overlay
                :options="options"
                :disable-date="dDate"
                i18n="ar"
                as-single
                use-range
                v-model="dateValue"
              />
            </div>
          </div>
          <div class="flex pt-5 items-center">
            <div class="mx-auto container align-middle">
              <div class="grid grid-cols-2 gap-2" style="display: flow-root">
                <div class="shadow rounded-lg py-3 px-5 bg-white">
                  <div class="flex flex-row justify-between items-center">
                    <div>
                      <h6 class="text-2xl">البطاقات تم ادخالها</h6>
                      <h4 class="text-black text-4xl font-bold text-rigth">
                        {{countComp?.count}}
                        <span class="text-green-600">+</span>
                      </h4>
                    </div>
                    <div>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="#14B8A6"
                        stroke-width="2"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                        />
                      </svg>
                    </div>
                  </div>
                  <div
                    class="text-left flex flex-row justify-start items-center"
                  >
                    <span class="mr-1">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="#14B8A6"
                        stroke-width="2"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                        />
                      </svg>
                    </span>
                  </div>
                </div>
                <div class="shadow rounded-lg py-3 px-5 bg-white" v-if="false">
                  <div class="flex flex-row justify-between items-center">
                    <div>
                      <h6 class="text-2xl">Serials viewed</h6>
                      <h4 class="text-black text-4xl font-bold text-left">
                        41
                      </h4>
                    </div>
                    <div>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-12 w-12"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="#EF4444"
                        stroke-width="2"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"
                        />
                      </svg>
                    </div>
                  </div>
                  <div
                    class="text-left flex flex-row justify-start items-center"
                  >
                    <span class="mr-1">
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="#EF4444"
                        stroke-width="{2}"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
                        />
                      </svg>
                    </span>
                    <p>
                      <span class="text-red-500 font-bold">12%</span> in 7 days
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="py-12" v-if="$page.props.auth.user.type_id==8||$page.props.auth.user.type_id==9">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white dark:bg-gray-900" style="border-radius: 8px">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">جميع الارقام</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.total}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام لم تم اسنادها</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.Unassigned}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام  يتم اسنادها</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.Assigned}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام قبول العرض</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.OfferAccepted}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام تم رفض العرض</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.OfferRejected}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام  معاودة مره اخرى</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.FollowUp}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام مشغولة</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.Busy}}</p>
              </div>
            </div>
          </div>

          <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-2xl shadow-sm sm:p-6 dark:bg-gray-800 dark:border-gray-700">
            <div class="flex items-center space-x-4">
              <div class="p-3 text-white bg-blue-600 rounded-full">
                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4H1m3 4H1m3 4H1m3 4H1m6.071.286a3.429 3.429 0 1 1 6.858 0M4 1h12a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Zm9 6.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z"/>
                </svg>
              </div>
              <div class="px-3">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">الارقام المعلقة </p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{numbersStats.Unknown}}</p>
              </div>
            </div>
          </div>

          </div>
        </div>
        </div>
    </div>
  </AuthenticatedLayout>
</template>
