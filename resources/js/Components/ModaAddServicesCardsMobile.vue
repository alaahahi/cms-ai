<script>
export default {
  props: {
    show: Boolean,
    data: Object,
    categories: Array,
    cards: Array,
    editMode: Boolean,
  },
  data() {
    return {
      localData: { ...this.data },
      image: null,
    };
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.localData.image = file;
      }
    },
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
      <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-auto">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-800">
            {{ editMode ? "تعديل الخدمة" : "إضافة خدمة" }}
          </h3>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">اسم الخدمة بالعربية</label>
            <input type="text" v-model="localData.service_name_ar" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">اسم الخدمة بالإنجليزية</label>
            <input type="text" v-model="localData.service_name_en" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الوصف بالعربية</label>
            <textarea v-model="localData.description_ar" class="input-style"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الوصف بالإنجليزية</label>
            <textarea v-model="localData.description_en" class="input-style"></textarea>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">السعر</label>
            <input type="number" v-model="localData.price" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">أيام العمل</label>
            <input type="text" v-model="localData.working_days" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">ساعات العمل</label>
            <input type="text" v-model="localData.working_hours" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">عدد المواعيد في اليوم</label>
            <input type="number" v-model="localData.appointments_per_day" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">تاريخ الانتهاء</label>
            <input type="date" v-model="localData.expir_date" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">العملة</label>
            <input type="text" v-model="localData.currency" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">شائع؟</label>
            <input type="checkbox" v-model="localData.is_popular"  />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">متاح حجز موعد في التطبيق</label>
            <input type="checkbox" v-model="localData.show_on_app"  />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">التخصص بالعربية</label>
            <input type="text" v-model="localData.specialty_ar" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">التخصص بالإنجليزية</label>
            <input type="text" v-model="localData.specialty_en" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">معدل التقييم</label>
            <input type="number" step="0.1" v-model="localData.review_rate" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">سنة الخبرة</label>
            <input type="number" v-model="localData.ex_year" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">الصورة</label>
            <input type="file" accept="image/*" @change="handleImageUpload" class="input-style" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">التصنيف</label>
            <select v-model="localData.category_id" class="input-style">
              <option value="">اختر تصنيف</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name_ar }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">البطاقة</label>
            <select v-model="localData.card_id" class="input-style">
              <option value="">اختر بطاقة</option>
              <option v-for="card in cards" :key="card.id" :value="card.id">
                {{ card.name_ar }}
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
              <button @click="$emit('submit', localData)" class="px-4 py-2 w-full rounded bg-blue-600 text-white">
                {{ editMode ? 'تحديث' : 'حفظ' }}
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
  .input-style {
  @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
}
  </style>