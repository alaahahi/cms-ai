<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'

const props = defineProps({
  show: Boolean,
  userId: Number
})

const emit = defineEmits(['close', 'updated'])

const numbers = ref([])
const loading = ref(false)
const assignCount = ref(1)

watch(() => props.show, async (value) => {
  if (value) {
    await fetchUnassigned()
  }
})

async function fetchUnassigned() {
  loading.value = true
  try {
    const { data } = await axios.get('/api/unassigned-numbers')
    numbers.value = data
  } catch (error) {
    console.error('Error fetching unassigned numbers:', error)
  } finally {
    loading.value = false
  }
}

async function assignNumbers() {
  const selectedNumbers = numbers.value.slice(0, assignCount.value)
  console.log( selectedNumbers)
  try {
    await axios.post('/api/assign-numbers', {
      user_id: props.userId,
      numbers: selectedNumbers.map(number => number.id)
    })

    numbers.value = numbers.value.slice(assignCount.value)
    emit('updated')
    emit('close')
  } catch (error) {
    console.error('Error assigning numbers:', error)
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
            <h2 class="text-center mb-4">إسناد أرقام هاتف للمندوب
              {{ userId }}


            </h2>
            <div class="mb-4"  >
              <label>عدد الأرقام التي تريد إسنادها:</label>
              <input
                v-model.number="assignCount"
                type="number"
                min="1"
                :max="numbers.length"
                class="w-full p-2 border rounded mt-1"
              />
            </div>
            <div class="text-center text-gray-500 py-3" v-if="numbers.length > 0">عدد الأرقام التي لم يتم الإسناد للمندوب {{ numbers.length - assignCount }}</div>

            <div v-if="loading">جاري تحميل الأرقام...</div>
            <ul v-else class="max-h-64 overflow-y-auto border rounded p-2 bg-gray-50 text-sm">
              <li v-for="number in numbers.slice(0, assignCount)" :key="number.id" class="py-1 border-b">
                {{ number.phone }}
              </li>
              <li v-if="numbers.length === 0" class="text-center text-gray-500">لا توجد أرقام حالياً</li>
            </ul>
          </div>

          <div class="modal-footer my-4 flex justify-between">
            <button @click="$emit('close')" class="bg-gray-500 text-white px-4 py-2 rounded">تراجع</button>
            <button @click="assignNumbers" class="bg-rose-500 text-white px-4 py-2 rounded">تأكيد الإسناد</button>
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