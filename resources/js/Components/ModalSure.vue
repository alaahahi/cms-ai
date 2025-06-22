<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: Boolean,
  userId: Number,
  status: Number
})

const emit = defineEmits(['close', 'updated'])

const numbers = ref([])
const loading = ref(false)
const assignCount = ref(1)
const name = ref('')
const note = ref('')

watch(() => props.show, async (value) => {
  if (value) {
    await fetchSure()
  }
})

async function fetchSure() {
  loading.value = true
  try {
    const { data } = await axios.get('/api/sure-numbers')
    numbers.value = data
  } catch (error) {
    console.error('Error fetching sure numbers:', error)
  } finally {
    loading.value = false
  }
}

// إرسال العملية (قبول أو رفض)
async function submitDecision(type) {
  try {
    const selectedNumbers = numbers.value.slice(0, assignCount.value)
    const payload = {
      user_id: props.userId,
      type, // 'accept' or 'reject'
      name: name.value,
      note: note.value,
      numbers: selectedNumbers.map(n => n.id)
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
              <h2 class="text-center mb-4">قبول أو رفض العرض</h2>
  
              <div class="mb-4">
                <label>الاسم:</label>
                <input v-model="name" type="text" class="w-full p-2 border rounded mt-1" />
              </div>
  
              <div class="mb-4">
                <label>ملاحظة:</label>
                <textarea v-model="note" class="w-full p-2 border rounded mt-1"></textarea>
              </div>
  
              <div class="mb-4">
                <label>عدد الأرقام التي تريد إسنادها:</label>
                <input
                  v-model.number="assignCount"
                  type="number"
                  min="1"
                  :max="numbers.length"
                  class="w-full p-2 border rounded mt-1"
                />
              </div>
  
              <div class="text-center text-gray-500 py-3" v-if="numbers.length > 0">
                عدد الأرقام التي لم يتم الإسناد للمندوب: {{ numbers.length - assignCount }}
              </div>
  
              <div v-if="loading">جاري تحميل الأرقام...</div>
              <ul v-else class="max-h-64 overflow-y-auto border rounded p-2 bg-gray-50 text-sm">
                <li v-for="number in numbers.slice(0, assignCount)" :key="number.id" class="py-1 border-b">
                  {{ number.phone }}
                </li>
              </ul>
  
              <div class="mt-4 flex justify-between">
                <button @click="submitDecision('accept')" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                  قبول العرض
                </button>
                <button @click="submitDecision('reject')" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                  رفض العرض
                </button>
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