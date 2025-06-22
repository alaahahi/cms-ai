<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: Boolean,
  phone: Object,
  status: Number
})

const emit = defineEmits(['close'])

 const loading = ref(false)
const assignCount = ref(1)
const name = ref('')
const note = ref('')

 

// إرسال العملية (قبول أو رفض)
async function submitDecision(type) {
  try {
    const payload = {
      id: props.phone.id,
      type,              // نوع القرار: 'accept' أو 'reject'
      name: props.phone.name,  // اسم المندوب أو المستخدم
      note: props.phone.note,  // ملاحظة إضافية
    }

    await axios.post('/api/number-decision', payload)

    emit('updated') // إعادة تحميل البيانات في الصفحة الأم
    emit('close')   // إغلاق المودال
  } catch (error) {
    console.error('خطأ في إرسال البيانات:', error)
  }
}
</script>
<template>
    <Transition name="modal">
      <div v-if="show" class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header">
              <slot name="header" />
            </div>
  
            <div class="modal-body">
              <h2 class="text-center mb-4">قبول أو رفض العرض {{ phone.phone }} </h2>
  
              <div class="mb-4">
                <label>الاسم:</label>
                <input v-model="phone.name" type="text" class="w-full p-2 border rounded mt-1" />
              </div>
  
              <div class="mb-4">
                <label>ملاحظة:</label>
                <textarea v-model="phone.note" class="w-full p-2 border rounded mt-1"></textarea>
              </div>
  
              <div class="mb-4">
        
              </div>
  
             
  
              
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                
              <div class="mt-4">
                <button @click="submitDecision('accept')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                  قبول العرض
                </button>
                </div>
                <div class="mt-4">
                <button @click="submitDecision('followup')" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 w-full">
                  معاودة مره اخرى
                </button>
                </div>
                <div class="mt-4">
                <button @click="submitDecision('busy')" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 w-full">
                  مشغول
                </button>
                </div>
                <div class="mt-4">
                <button @click="submitDecision('reject')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 w-full">
                  رفض العرض
                </button>
                </div>
             
         
              </div>
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
          
                <div class="mt-4">
                <button @click="emit('close')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 w-full">
                    تراجع
                </button>
                </div>
                <div class="mt-4">
                <button @click="submitDecision('edit')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 w-full">
                   تعديل
                </button>
                </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </template>
  
  <style>
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: table;
    transition: opacity 0.3s ease;
  }
  
  .modal-wrapper {
    display: table-cell;
    vertical-align: middle;
  }
  
  .modal-container {
    width: 50%;
    min-width: 350px;
    margin: 0px auto;
    padding: 20px  30px;
    padding-bottom: 60px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    transition: all 0.3s ease;
    border-radius: 10px;
  }
  
  .modal-header h3 {
    margin-top: 0;
    color: #42b983;
  }
  
  .modal-body {
    margin: 20px 0;
  }
  
  .modal-default-button {
    float: right;
    width: 100%;
    color: #fff;
  }
  
  /*
   * The following styles are auto-applied to elements with
   * transition="modal" when their visibility is toggled
   * by Vue.js.
   *
   * You can easily play with the modal transition by editing
   * these styles.
   */
  
  .modal-enter-from {
    opacity: 0;
  }
  
  .modal-leave-to {
    opacity: 0;
  }
  
  .modal-enter-from .modal-container,
  .modal-leave-to .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }
  </style>