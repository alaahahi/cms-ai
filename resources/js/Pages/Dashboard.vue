<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/inertia-vue3";
import VueTailwindDatepicker from "vue-tailwind-datepicker";
import { ref } from "vue";

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
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
        لوحة القيادة
      </h2>
    </template>

    <div class="py-12" v-if="$page.props.auth.user.type_id!=8&&$page.props.auth.user.type_id!=9">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white  dark:bg-gray-900">
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
                <div class="flex items-start rounded-xl bg-white p-4 shadow-lg"   v-for="(user , index) in countComp?.data" :key="index">
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
    <div v-if="$page.props.auth.user.type_id==8&&$page.props.auth.user.type_id==9">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div
          class="p-6 bg-white  dark:bg-gray-900"
          style="border-radius: 8px"
        >
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
        <div class="p-6 bg-white  dark:bg-gray-900" style="border-radius: 8px">
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
