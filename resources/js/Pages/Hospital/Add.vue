<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref } from "vue";
import axios from 'axios';

const selectedDate = ref('');
const appointments = ref([]);
const bookedSlots = ref([]);
const isLoading = ref(false);

const props = defineProps({
    url:String,
    userDoctor:Array,
});

const form = useForm({
    user_id: '',
    card_id: '',
    date: '',
    start:'' ,
    end:'',
    note:'',
});

const submit = () => {
    isLoading.value=true; 
    form.post(route('hospital'));
};


const timeSlots = (() => {
  const slots = [];
  for (let i = 9; i <= 16; i += 1) {
    slots.push(`${i}:00-${i + 1}:00`);
  }
  return slots;
})();

const isSlotAvailable = (slot) => {
  if (!form.date) return false; // disable all slots if no date selected
  if (new Date(form.date).getDay() === 5) return false; // disable all slots if Friday

  // const today = new Date();

  // const selected = new Date(form.date);

  // if (selected < today) {
  //   return false; // disable slots for past dates
  // }

    const start = new Date(`${form.date} ${slot.split('-')[0]}:00`);
    const end = new Date(`${form.date} ${slot.split('-')[1]}:00`);
    const overlap = appointments.value.some((appointment) => {
    const apptStart = new Date(appointment.start);
    const apptEnd = new Date(appointment.end);

    return (start >= apptStart && start < apptEnd) || (end > apptStart && end <= apptEnd);
  });

  return !overlap && !bookedSlots.value.includes(slot);
};

const resetBookedSlots = () => {
  bookedSlots.value = [];
  if (form.date) {
    // Your HTTP request logic here
  }
};

const bookAppointment = (slot, type) => {
  appointments.value=[];
  const start = form.date+' '+slot.split('-')[0]+':00';
  const end =  form.date+' '+slot.split('-')[1]+':00';
  form.start = start
  form.end = end
  appointments.value.push({ start, end });
  bookedSlots.value.push(slot);
  const slots = slot.split('-')[0]; // "9:00"
  const hour = slots.split(':')[0]; // "9"
  resetBookedSlots()
  // Your HTTP request logic here
};

let timer = null;
const delay = 1000; // Delay in milliseconds
const userCard = ref(0);

const handleInput = (v) => {
  clearTimeout(timer); // Clear the previous timer

  timer = setTimeout(() => {
    checkCard(v); // Call the function to make the Axios request after the delay
  }, delay);
};

const checkCard = (v) => {
  axios.get('/api/checkCard?card_id='+v)
  .then(response => {
    userCard.value=response.data;
  })
  .catch(error => {
    userCard.value=0;
  })
};
</script>
<template>
      <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
         حجز وتثبيت موعد للمستخدمين 
      </h2>
    </template>
    <div v-if="$page.props.success">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-red-100 rounded-lg dark:bg-red-200 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-red-700 dark:text-red-800">
          {{ $page.props.success }}
        </div>
      </div>
    </div>
    <div class="max-w-8xl mx-auto sm:px-3 lg:px-4 mt-4">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2 lg:gap-4">
                <div class="px-4">
                  <select v-model="form.user_id" id="default" class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 mb-6 text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-red-500 dark:focus:border-red-500">
                    <option value="" disabled>يرجى اختيار طبيب</option>
                    <option v-for="(user, index) in userDoctor" :key="index" :value="user.id">{{ user.name }}</option>
                  </select>
                </div>
                <div class=" px-4">
                  <form class="items-center max-w-5xl">
                    <div>
                      <TextInput
                        @input="handleInput(form.card_id)"
                        v-model="form.card_id"
                        type="number"
                        id="simple-search"
                        class="w-full"
                        placeholder="رقم بطاقة المريض"
                        required
                      />
                      <span
                        v-if="userCard"
                      >
                      البطاقة تم تسجيلها قبل بأسم 
                      <span  className="text-red-600">
                        {{userCard.name}}
                      </span>
                        للمندوب
                      <span  className="text-red-600">
                        {{userCard.user?.name}}
                      </span>
                      أفراد العائلة
                      <span className="text-red-600">
                        {{userCard.family_name}}
                      </span>
                      </span>
                    </div>
                    
                  </form>
                </div>
                <div class=" px-5">
                <h5 class="py-3">اليوم</h5>
                <TextInput type="date" class="form-control w-full " v-model="form.date" @change="resetBookedSlots" />
                </div>

                <div class="  px-5">
                <h5 class="py-3">الموعد</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-8">
                    <div  v-for="(slot, index) in timeSlots" :key="index">
                    <button class="px-6 py-2 text-white bg-rose-500 rounded-md focus:outline-none w-full" :disabled="!isSlotAvailable(slot)" @click="bookAppointment(slot, 'vip')">
                        {{ slot }}
                    </button>
                    </div>
                </div>
                </div>
                <div class=" px-5">
                <h5 class="py-3">ملاحظة</h5>
                <TextInput type="text" class="form-control w-full " v-model="form.note"/>
                </div>
                <div class=" px-5 py-7 pt-12 ">
                <button type="date" class="px-6 py-2 text-white bg-blue-500 rounded-md focus:outline-none w-full"  @click="submit" :disabled="!form.start || !form.end || !form.user_id || !form.card_id">
                  <span v-if="!isLoading">حفظ</span>
                  <span v-else>جاري الحفظ...</span>
                </button>
                </div>
            </div>
          </div>
        </div>
    </div>
  </AuthenticatedLayout>
  </template>
  <style scoped>
    button:disabled{
        background-color: rgb(245, 135, 135) !important;
    }
  </style>