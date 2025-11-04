<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import { ref, computed, onMounted } from "vue";
import { WebCamUI } from "vue-camera-lib";
import axios from 'axios';

// استعادة آخر نوع بطاقة تم اختياره من localStorage
const getLastSelectedCardId = () => {
  try {
    const savedData = localStorage.getItem('lastSelectedCardId');
    if (!savedData) return null;
    
    const data = JSON.parse(savedData);
    const savedDate = new Date(data.date);
    const now = new Date();
    
    // حساب الفرق بالأشهر
    const monthsDiff = (now.getFullYear() - savedDate.getFullYear()) * 12 + 
                       (now.getMonth() - savedDate.getMonth());
    
    // إذا تجاوز الشهر، حذف القيمة المحفوظة
    if (monthsDiff >= 1) {
      localStorage.removeItem('lastSelectedCardId');
      return null;
    }
    
    return data.cardId ? parseInt(data.cardId) : null;
  } catch (error) {
    // في حالة خطأ في قراءة البيانات، حذف القيمة
    localStorage.removeItem('lastSelectedCardId');
    return null;
  }
};

// حفظ نوع البطاقة المحدد في localStorage مع التاريخ
const saveLastSelectedCardId = (cardId) => {
  if (cardId) {
    const data = {
      cardId: cardId.toString(),
      date: new Date().toISOString()
    };
    localStorage.setItem('lastSelectedCardId', JSON.stringify(data));
  }
};

const form = ref({
  created: ref(getTodayDate()), // Set the initial value to today's date
  card_id: getLastSelectedCardId(), // استعادة آخر نوع بطاقة تم اختياره
});

function getTodayDate() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}
const props =  defineProps({
  usersType: Array,
  sales : Array,
  cards: Array,
  apiKey: String,
  third_title_ar: String,
  phone: String,
});


const userCard = ref(0);

const showHusband = ref(false);

const profileAdded = ref(0);
const errors = ref({});
const validationErrors = ref({});

const isLoading = ref(false);
const whatsappBatchId = ref(null);
const whatsappProgress = ref(null);
const progressInterval = ref(null);

// Validation functions
const validatePhoneNumber = (phone) => {
  // Allow empty phone number
  if (!phone || phone.trim() === '') {
    return null; // Valid - empty is allowed
  }
  
  // Remove spaces and special characters for validation
  const cleanPhone = phone.replace(/[\s\-\(\)\+]/g, '');
  
  // Check if it's all digits
  if (!/^\d+$/.test(cleanPhone)) {
    return 'رقم الهاتف يجب أن يحتوي على أرقام فقط';
  }
  
  // Must be exactly 10 digits
  if (cleanPhone.length !== 10) {
    return 'رقم الهاتف يجب أن يكون 10 أرقام بالضبط';
  }
  
  return null;
};

const validateForm = () => {
  validationErrors.value = {};
  let isValid = true;

  if (!form.value.card_id) {
    validationErrors.value.card_id = 'يجب اختيار نوع البطاقة';
    isValid = false;
  }

  if (!form.value.card_number) {
    validationErrors.value.card_number = 'رقم البطاقة مطلوب';
    isValid = false;
  }

  if (!form.value.name || form.value.name.trim() === '') {
    validationErrors.value.name = 'الاسم مطلوب';
    isValid = false;
  }

  if (!form.value.saler_id) {
    validationErrors.value.saler_id = 'يجب اختيار المندوب';
    isValid = false;
  }

  // Validate phone only if it's not empty
  if (form.value.phone_number && form.value.phone_number.trim() !== '') {
    const phoneError = validatePhoneNumber(form.value.phone_number);
    if (phoneError) {
      validationErrors.value.phone_number = phoneError;
      isValid = false;
    }
  }

  // الصورة غير مطلوبة - لا حاجة للتحقق منها

  return isValid;
};

const submit = () => {
  // Clear previous errors
  validationErrors.value = {};
  errors.value = {};
  
  // Validate form
  if (!validateForm()) {
    return;
  }

  isLoading.value = true;

  axios.post('/api/formRegistration', form.value)
  .then(response => {

    profileAdded.value = response.data.profile;
    whatsappBatchId.value = response.data.batch_id;
    
    // Start tracking WhatsApp progress
    if (whatsappBatchId.value) {
      startProgressTracking(whatsappBatchId.value);
    }
    
    // حفظ نوع البطاقة الحالي قبل إعادة تعيين النموذج
    const currentCardId = form.value.card_id;
    
    // Reset form but keep card_id
    form.value = {
      created: getTodayDate(),
      card_id: currentCardId, // الاحتفاظ بنوع البطاقة المحدد
      card_number: null,
      name: null,
      saler_id: null,
      address: null,
      family_name: null,
      phone_number: null,
      image: null,
    };
    
    // Reset image preview
    imagePreview.value = null;
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    isLoading.value = false;
  })
  .catch(error => {
    profileAdded.value = false;
    if (error.response && error.response.data) {
      errors.value = error.response.data;
    } else {
      errors.value = { general: ['حدث خطأ غير متوقع'] };
    }
    isLoading.value = false;
  })
};

const startProgressTracking = (batchId) => {
  const checkProgress = () => {
    axios.get(`/api/whatsapp-progress?batch_id=${batchId}`)
      .then(response => {
        whatsappProgress.value = response.data;
        
        // Stop tracking if completed
        if (response.data.status === 'completed' || response.data.status === 'failed') {
          if (progressInterval.value) {
            clearInterval(progressInterval.value);
            progressInterval.value = null;
          }
        }
      })
      .catch(error => {
        console.error('Error checking progress:', error);
        // Stop tracking on error
        if (progressInterval.value) {
          clearInterval(progressInterval.value);
          progressInterval.value = null;
        }
      });
  };
  
  // Check immediately
  checkProgress();
  
  // Check every 2 seconds
  progressInterval.value = setInterval(checkProgress, 2000);
  
  // Stop after 5 minutes max
  setTimeout(() => {
    if (progressInterval.value) {
      clearInterval(progressInterval.value);
      progressInterval.value = null;
    }
  }, 300000);
};

const photoHusband = (data) => {
  form.image = data.image_data_url;
  showHusband.value = false;
};
const photoWife = (data) => {
  form.wife_image = data.image_data_url;
  // showWife.value = false;
};
const handleImage = (e) => {
  const selectedImage = e.target.files[0]; // get first file
  if (selectedImage) {
    // Validate image size (max 5MB)
    if (selectedImage.size > 5 * 1024 * 1024) {
      validationErrors.value.image = 'حجم الصورة كبير جداً. الحد الأقصى 5 ميجابايت';
      return;
    }
    
    // Validate image type
    if (!selectedImage.type.startsWith('image/')) {
      validationErrors.value.image = 'الملف المحدد ليس صورة';
      return;
    }
    
    createBase64Image(selectedImage);
  }
};
const createBase64Image = (fileObject) => {
  const reader = new FileReader();

  reader.onload = (e) => {
   form.value.image = e.target.result;
   imagePreview.value = e.target.result;
   validationErrors.value.image = null;
  };
  reader.readAsDataURL(fileObject);
};
const handleImageWife = (e) => {
  const selectedImage = e.target.files[0]; // get first file
  createBase64ImageWife(selectedImage);
};
const createBase64ImageWife = (fileObject) => {
  const reader = new FileReader();

  reader.onload = (e) => {
    //form.wife_image = e.target.result;
    //this.uploadImage();
  };
  reader.readAsDataURL(fileObject);
};
let timer = null;
const delay = 1000; // Delay in milliseconds

const handleInput = (v,id) => {
  clearTimeout(timer); // Clear the previous timer

  timer = setTimeout(() => {
    checkCard(v,id); // Call the function to make the Axios request after the delay
  }, delay);
};
const checkCard = (v,id) => {
  axios.get('/api/checkCard?card_number='+v+'&card_id='+id)
  .then(response => {
    userCard.value=response.data;
  })
  .catch(error => {
    userCard.value=0;
  })
};
// Removed sendWhatsAppMessageArray - now handled by Job queue

const imagePreview = ref(null);

</script>

<template>
  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-white  leading-tight">
        العقد الإلكتروني
      </h2>
      <!-- <WebCamUI @photoTaken="photoHusband" v-if="showHusband" />
      <WebCamUI @photoTaken="photoWife" v-if="showWife" /> -->
    </template>
    <div v-if="profileAdded">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-green-300 rounded-lg dark:bg-green-300 text-center"
        role="alert"
      >
        <div class="ml-3 text-sm font-medium text-green-700 dark:text-green-800">
          تم ادخال البطاقة بنجاح رقم 
          {{ profileAdded.card_number }}
          بأسم الزبون
          {{ profileAdded.name }}
        </div>
      </div>
      
      <!-- WhatsApp Progress Indicator -->
      <div v-if="whatsappProgress" class="p-4 mb-4 bg-blue-50 rounded-lg border border-blue-200">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-sm font-medium text-blue-800">حالة إرسال رسالة الواتساب</h3>
          <span class="text-xs text-blue-600">{{ whatsappProgress.status === 'completed' ? 'مكتمل' : whatsappProgress.status === 'queued' ? 'في الانتظار' : 'قيد المعالجة' }}</span>
        </div>
        
        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
          <div 
            class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" 
            :style="{ width: whatsappProgress.total > 0 ? (whatsappProgress.completed / whatsappProgress.total * 100) + '%' : '0%' }"
          ></div>
        </div>
        
        <div class="text-xs text-blue-700">
          <span v-if="whatsappProgress.status === 'queued'">جاري إعداد الرسالة...</span>
          <span v-else-if="whatsappProgress.status === 'processing'">
            جاري الإرسال... ({{ whatsappProgress.completed }} / {{ whatsappProgress.total }})
          </span>
          <span v-else-if="whatsappProgress.status === 'completed'">
            ✅ تم إرسال الرسالة بنجاح
          </span>
          <span v-else-if="whatsappProgress.status === 'failed'">
            ❌ فشل إرسال الرسالة
          </span>
        </div>
      </div>
    </div>
    <div v-if="Object.keys(errors).length > 0 && !profileAdded">
      <div
        id="alert-2"
        class="p-4 mb-4 bg-red-50 border-r-4 border-red-400 rounded-lg"
        role="alert"
      >
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
          </div>
          <div class="mr-3">
            <h3 class="text-sm font-medium text-red-800 mb-2">حدثت أخطاء أثناء الإرسال:</h3>
            <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
              <li v-for="(messages, field) in errors" :key="field">
                <span v-if="typeof messages === 'string'">{{ messages }}</span>
                <span v-else-if="Array.isArray(messages)">{{ messages[0] }}</span>
                <span v-else>{{ JSON.stringify(messages) }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <form name="createForm" @submit.prevent="submit">
      <div class="flex flex-row">
        <div class="grow">
          <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div className="mb-4">
                      <InputLabel for="sales_id" value="البطاقة" />
                      <select
                        v-model="form.card_id"
                        id="userType"
                        @change="saveLastSelectedCardId(form.card_id)"
                        class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                      >
                        <option :value="null" disabled>اختر نوع البطاقة</option>
                        <option
                          v-for="(type ,index) in cards"
                          :key="index"
                          :value="type.id"
                        >
                          {{ type.name }}
                        </option>
                      </select>
                      <InputError v-if="validationErrors?.card_id" :message="validationErrors.card_id" />
                      <InputError v-if="errors?.card_id" :message="errors.card_id" />
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4" v-if="form.card_id">
                <div class="p-6 bg-white dark:bg-gray-900">
                  <h2 class="text-center text-xl py-3 mb-4 font-bold text-gray-800 border-b pb-2">معلومات البطاقة</h2>
                  
                  <!-- الصورة في صف منفصل أولاً -->
                  <div className="mb-6 w-full">
                      <InputLabel for="image" value="الصورة الشخصية (اختياري)" class="mb-3 text-lg font-semibold" />
                      <div class="flex flex-col items-start w-full">
                        <div v-if="imagePreview" class="mb-4 w-full border-2 border-gray-300 rounded-lg p-4 bg-gray-50">
                          <img 
                            :src="imagePreview" 
                            alt="معاينة الصورة"
                            class="max-w-full rounded-lg shadow-md object-contain mx-auto"
                            style="max-height: 80rem;"
                          />
                        </div>
                        <label 
                          class="cursor-pointer inline-flex items-center px-6 py-3 bg-rose-500 hover:bg-rose-600 text-white font-semibold rounded-lg transition duration-200 shadow-md hover:shadow-lg text-base"
                        >
                          <svg class="w-6 h-6 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                          </svg>
                          <span class="font-bold">اختر صورة للتحميل</span>
                          <input
                            @change="handleImage"
                            type="file"
                            accept="image/*"
                            class="hidden"
                            id="image-upload-input"
                          />
                        </label>
                        <p class="text-sm text-gray-600 mt-2 font-medium">الحد الأقصى لحجم الصورة: 5 ميجابايت - الصيغ المدعومة: JPG, PNG, GIF</p>
                      </div>
                      <InputError v-if="validationErrors?.image" :message="validationErrors.image" />
                      <InputError v-if="errors?.image" :message="errors.image" />
                    </div>
                  
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div className="mb-4">
                      <InputLabel for="card_number" value="رقم البطاقة *" />

                      <TextInput
                        id="card_number"
                        type="number"
                        class="mt-1 block w-full"
                        autofocus
                        @input="handleInput(form.card_number,form.card_id)"
                        v-model="form.card_number"
                        :class="{ 'border-red-500': validationErrors?.card_number || errors?.card_number }"
                      />
                      
                      <div 
                        v-if="userCard"
                        class="mt-2 p-3 bg-yellow-50 border-r-4 border-yellow-400 rounded"
                      >
                        <p class="text-sm text-yellow-800">
                          <span class="font-bold">تحذير:</span> البطاقة تم تسجيلها مسبقاً بأسم 
                          <span class="font-bold text-red-600">{{userCard.name}}</span>
                          للمندوب
                          <span class="font-bold text-red-600">{{userCard.user?.name}}</span>
                          <span v-if="userCard.family_name">، أفراد العائلة: <span class="font-bold text-red-600">{{userCard.family_name}}</span></span>
                        </p>
                      </div>
                      
                      <InputError v-if="validationErrors?.card_number" :message="validationErrors.card_number" />
                      <InputError v-if="errors?.card_number" :message="Array.isArray(errors.card_number) ? errors.card_number[0] : errors.card_number" />
                    </div>
                    
                    <div className="mb-4">
                      <InputLabel for="name" value="الأسم *" />

                      <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        :class="{ 'border-red-500': validationErrors?.name || errors?.name }"
                        placeholder="أدخل الاسم الكامل"
                      />
                      <InputError v-if="validationErrors?.name" :message="validationErrors.name" />
                      <InputError v-if="errors?.name" :message="Array.isArray(errors.name) ? errors.name[0] : errors.name" />
                    </div>

                    <div className="mb-4">
                      <InputLabel for="sales_id" value="المندوب *" />
                      <select
                        v-model="form.saler_id"
                        id="userType"
                        class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 mt-1"
                        :class="{ 'border-red-500': validationErrors?.saler_id || errors?.saler_id }"
                      >
                        <option :value="null" selected disabled>اختر المندوب</option>
                        <option
                          v-for="(type ,index) in sales"
                          :key="index"
                          :value="type.id"
                        >
                          {{ type.name }}
                        </option>
                      </select>
                      <InputError v-if="validationErrors?.saler_id" :message="validationErrors.saler_id" />
                      <InputError v-if="errors?.saler_id" :message="Array.isArray(errors.saler_id) ? errors.saler_id[0] : errors.saler_id" />
                    </div>
                    
                    <div className="mb-4">
                      <InputLabel for="phone_number" value="رقم الهاتف (اختياري)" />
                      <div class="relative">
                        <TextInput
                          id="phone_number"
                          type="tel"
                          class="mt-1 block w-full pr-10"
                          v-model="form.phone_number"
                          :class="{ 'border-red-500': validationErrors?.phone_number || errors?.phone_number }"
                          placeholder="07XXXXXXXX (10 أرقام) أو اتركه فارغاً"
                          maxlength="10"
                          @input="() => { if (validationErrors.phone_number) { const error = validatePhoneNumber(form.phone_number); validationErrors.phone_number = error || null; } }"
                          @blur="() => { const error = validatePhoneNumber(form.phone_number); if (error) validationErrors.phone_number = error; }"
                        />
                       
                      </div>
                      <p class="text-xs text-gray-500 mt-1">يمكن ترك الحقل فارغاً أو إدخال 10 أرقام بالضبط (مثال: 07501234567)</p>
                      <InputError v-if="validationErrors?.phone_number" :message="validationErrors.phone_number" />
                      <InputError v-if="errors?.phone_number && !validationErrors?.phone_number" :message="Array.isArray(errors.phone_number) ? errors.phone_number[0] : errors.phone_number" />
                    </div>
                    
                    <div className="mb-4">
                      <InputLabel for="address" value="العنوان" />

                      <TextInput
                        id="address"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.address"
                        placeholder="أدخل العنوان"
                      />
                      <InputError v-if="errors?.address" :message="Array.isArray(errors.address) ? errors.address[0] : errors.address" />
                    </div>

                    <div className="mb-4">
                      <InputLabel for="family_name" value="أفراد العائلة" />

                      <TextInput
                        id="family_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.family_name"
                        placeholder="أدخل أسماء أفراد العائلة"
                      />
                      <InputError v-if="errors?.family_name" :message="Array.isArray(errors.family_name) ? errors.family_name[0] : errors.family_name" />
                    </div>
                    
                    <div className="mb-4">
                      <InputLabel for="created" value="تاريخ البيع *" />

                      <TextInput
                        id="created"
                        type="date"
                        class="mt-1 block w-full"
                        v-model="form.created"
                      />
                      <InputError v-if="errors?.created" :message="Array.isArray(errors.created) ? errors.created[0] : errors.created" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="flex items-center justify-center gap-4 my-6 pb-12">
        <Link
          className="px-6 py-3 text-white bg-gray-500 hover:bg-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition duration-200 shadow-md hover:shadow-lg font-semibold"
          :href="route('formRegistration')"
        >
          <span class="flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            العودة
          </span>
        </Link>

        <button
          @click.prevent="submit"
          @keyup.enter="submit" 
          :disabled="isLoading"
          class="px-8 py-3 font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition duration-200 shadow-md hover:shadow-lg flex items-center"
        >
          <span v-if="!isLoading" class="flex items-center">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            حفظ البطاقة
          </span>
          <span v-else class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            جاري الحفظ...
          </span>
        </button>
      </div>
    </form>
  </AuthenticatedLayout>
</template>