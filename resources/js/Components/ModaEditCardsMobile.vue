<script>
export default {
  props: {
    show: Boolean,
    data: Object,
    editMode: Boolean, // لتحديد وضع التحرير
  },
  data() {
    return {
      localData: { ...this.data }, // نسخ البيانات لتحريرها محليًا
    };
  },
  watch: {
    data: {
      immediate: true,
      handler(newData) {
        this.localData = { ...newData }; // تحديث البيانات المحلية عند تغيير `data`
      },
    },
  },
};
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <slot name="header">
            <h3 class="text-lg font-semibold text-gray-800">
              {{ editMode ? "تعديل البيانات" : "إضافة بيانات" }}
            </h3>
          </slot>
        </div>

        <!-- Body -->
        <div class="px-6 py-4">
          <div class="mb-4">
            <label for="card_number" class="block text-sm font-medium text-gray-700">رقم البطاقة</label>
            <input
              id="card_number"
              type="text"
              v-model="localData.card_number"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
          <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
            <input
              id="phone"
              type="text"
              v-model="localData.phone"
              dir="ltr"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
          <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
            <input
              id="address"
              type="text"
              v-model="localData.address"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
          <div class="mb-4">
            <label for="family_members_names" class="block text-sm font-medium text-gray-700">أسماء أفراد العائلة</label>
            <input
              id="family_members_names"
              type="text"
              v-model="localData.family_members_names"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
        </div>

       
        <div class="modal-footer my-2">
              <div class="flex flex-row">
                <div class="basis-1/2 px-4">             <button
                    class="modal-default-button py-3  bg-gray-500 rounded"
                    @click="$emit('close');"
                  >تراجع</button></div>
              <div class="basis-1/2 px-4">              <button
                    class="modal-default-button py-3  bg-rose-500 rounded col-6"
                    @click="$emit('a',localData);"
                  >نعم</button>
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
    margin-bottom: 25px;
    color: #000 !important;
    margin: 20px;
    padding-bottom: 20px;
    font-size: 24px;
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