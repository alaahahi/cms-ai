<script>
export default {
  props: {
    show: Boolean,
    data: Object,
    parents:Array,
    editMode: Boolean,
  },
  data() {
    return {
      localData: { ...this.data },
      image: null, // <-- حقل الصورة
    };
  },
  methods: {
    expandShortHex(hex) {
    if (hex && hex.length === 4) {
      this.localData.color =  '#' + hex[1] + hex[1] + hex[2] + hex[2] + hex[3] + hex[3];
    }
    return hex;
  },
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.localData.icon = file; // نقوم بتخزين الأيقونة
      }
    }
  },
  watch: {
    data: {
      immediate: true,
      handler(newData) {
        this.localData = { ...newData };
      },
    },
  },
};
</script>

<template>
  <Transition name="modal">
    <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800">
            {{ editMode ? "تعديل التصنيف" : "إضافة تصنيف" }}
          </h3>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">الاسم بالعربية</label>
            <input type="text" v-model="localData.name_ar"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الاسم بالإنجليزية</label>
            <input type="text" v-model="localData.name_en"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الخصم (%)</label>
            <input type="number" v-model="localData.discount"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">اللون
              {{expandShortHex(localData.color)}}
            </label>
            <input type="color" v-model="localData.color"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الأيقونة</label>
            <input
              id="icon"
              type="file"
              accept="image/*"
              @change="handleImageUpload"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">التصنيف الأب</label>
            <select v-model="localData.parent_id"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
              <option value="">تصنيف أب</option>
              <!-- يجب جلب التصنيفات الرئيسية هنا -->
              <option v-for="parent in parents" :key="parent.id" :value="parent.id">
                {{ parent.name_ar }}
              </option>
            </select>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer my-2">
          <div class="flex flex-row">
            <div class="basis-1/2 px-4">
              <button @click="$emit('close')" class="px-4 py-2 w-full rounded bg-gray-500 text-white">تراجع</button>
            </div>
            <div class="basis-1/2 px-4">
              <button @click="$emit('a', localData)" class="px-4 w-full py-2 rounded bg-blue-600 text-white">حفظ</button>
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