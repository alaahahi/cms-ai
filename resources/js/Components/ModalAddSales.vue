<script setup>
import { ref, watch } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'


const props = defineProps({
  show: Boolean,
  data: Array,
  accounts: Array,
});
const form = ref({
  user: {
    percentage:0,
  },
  date:new Date(),
  card:0,
  amount: 0,
  box:0,
  hospital:0,
  doctor:0,

});
const restform =()=>{
  form.value = {
  user: {
    percentage:0,
  },
  date:new Date(),
  card:0,
  amount: 0,
  box:0,
  hospital:0,
  doctor:0,

};
}
const calculateAmount = () => {
  form.value.amount = form.value.user.percentage * form.value.card;
  form.value.hospital = 7000 * form.value.card;
  form.value.doctor = 20000 * form.value.card;
  form.value.box = (75000 * form.value.card)-form.value.amount-form.value.hospital-form.value.doctor;



  
};

const calculateBox = () => {
  form.value.box = (75000 * form.value.card)-form.value.amount;
  
};
const calculateHospital = () => {
  form.value.hospital = 7000 * form.value.card;
  
};
const calculateDoctor = () => {
  form.value.doctor = 20000 * form.value.card;
  
};

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
                        <h2 class="text-center pb-5">
                        إضافة مبيعات البطاقة عبر المندوب
                        </h2>
                        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3 lg:gap-3">
                        <div className="mb-4 mx-5">
                          <label for="account_id" >حساب</label>
                          <select
                            v-model="form.account_id"
                            id="account_id"
                            class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <template v-for="(user, index) in [accounts]" :key="index" >
                              <option :value="user.id" selected>{{ user?.name }}</option>
                            </template>
                          </select>
                        </div>
                        <div className="mb-4 mx-5">
                          <label for="card" >التاريخ</label>
                          <VueDatePicker v-model="form.date"></VueDatePicker>

                        </div>
   
                        <div className="mb-4 mx-5">
                          <label for="user_id" >المندوب</label>
                          <select
                            v-model="form.user"
                            id="user_id"
                            class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected disabled>تحديد المندوب</option>
                            <option v-for="(user, index) in data" :key="index" :value="user">{{ user.name }}</option>
                          </select>
                        </div>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3 lg:gap-3">
                        <div className="mb-4 mx-5">
                        <label for="card" >نسبة المبيع للبطاقة</label>
                        <input
                          id="card"
                          type="number"
                          @input="calculateAmount"

                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.user.percentage"   />
                        </div>
                        <div className="mb-4 mx-5">
                        <label for="card" >عدد البطاقة التي تم بيعها </label>
                        <input
                          id="card"
                          type="number"
                          @input="calculateAmount"
 
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.card" />
                        </div>
                        <div className="mb-4 mx-5">
                        <label for="card" >المجموع نسبة المبيعات للمندوب</label>
                        <input
                          id="card"
                          type="number"
                        
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.amount" />
                        </div>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3 lg:gap-3">
                        <div className="mb-4 mx-5">
                        <label for="card" >الدخل للصندوق</label>
                        <input
                          id="card"
                          type="number"
                          @input="calculateAmount"

                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.box"   />
                        </div>
                        <div className="mb-4 mx-5">
                        <label for="card" >نسبة للصندوق الأطباء</label>
                        <input
                          id="card"
                          type="number"
                          @input="calculateAmount"
 
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.hospital" />
                        </div>
                        <div className="mb-4 mx-5">
                          <label for="card" >نسبة للصندوق المشفى</label>
                          <input
                          id="card"
                          type="number"
                          @input="calculateAmount"
 
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.doctor" />
                        </div>
                        </div>
            </div>
  
            <div class="modal-footer my-2">
              <div class="flex flex-row">
                <div class="basis-1/2 px-4"> 
                  <button class="modal-default-button py-3  bg-gray-500 rounded"
                    @click="$emit('close');">تراجع</button>
                  </div>
              <div class="basis-1/2 px-4">
                <button class="modal-default-button py-3  bg-rose-500 rounded col-6"  @click="$emit('a',form);restform();" :disabled="!(form.user.percentage && form.card)">نعم</button>
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