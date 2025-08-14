<script setup>
import { ref } from 'vue'


const props = defineProps({
  show: Boolean,
  data: String
})
let form = ref({
  name: '',
  email: '',
  password: '',
  percentage: 10000
})

const emit = defineEmits(['close', 'a'])



</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="modal-mask ">
      <div class="modal-wrapper ">
        <div class="modal-container">
          <div class="modal-header">
            <slot name="header"></slot>
          </div>

          <div class="modal-body">
            المندوبين
          </div>
          <div>
            <form>
              <div className="flex flex-col">
                <div className="mb-4">

                  <label for="name">الأسم</label>

                  <input id="name" type="text"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.name" autofocus />

                  <span className="text-red-600" v-if="!form.name">
                    الأسم حقل مطلوب
                  </span>
                </div>
                <div className="mb-4">

                  <label for="email">اسم المستخدم</label>

                  <input id="email" type="email"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.email" />

                  <span className="text-red-600"
                    v-if="(!form.email) || (!form.email.includes('@')) || !form.email.includes('.com')">
                    اسم المستخدم حقل مطلوب ايميل مثل alaa@cms.com
                  </span>
                </div>
                <div className="mb-4">

                  <label for="password">كلمة المرور</label>

                  <input id="password" type="text"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.password" />

                  <span className="text-red-600" v-if="form.password.length < 8">
                    كلمة المرور حقل مطلوب من 8 ارقام او احرف انكليزية
                  </span>
                </div>

                <div className="mb-4">

                  <label for="percentage"> نسبة المبيعات</label>

                  <input id="number" type="number"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    v-model="form.percentage" />
                </div>

              </div>


            </form>
          </div>
          <div class="modal-footer my-2">
            <div class="flex flex-row">
              <div class="basis-1/2 px-4"> <button class="modal-default-button py-3  bg-gray-500 rounded"
                  @click="$emit('close');">تراجع</button></div>
              <div class="basis-1/2 px-4"> <button class="modal-default-button py-3  bg-rose-500 rounded col-6"
                  @click="$emit('a', form);">حفظ</button>
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
  padding: 20px 30px;
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