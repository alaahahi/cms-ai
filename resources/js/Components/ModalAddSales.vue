<script setup>
import { ref, watch } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'


const props = defineProps({
  show: Boolean,
  data: Array,
  accounts: Array,
  cards: Array,
  card_id: String,
});
const form = ref({
  user: {
    percentage:0,
  },
  date:getTodayDate(),
  card:0,
  amount: 0,
  box:0,
  hospital:0,
  doctor:0,

});
function getTodayDate() {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}
const restform =()=>{
  form.value = {
  user: {
    percentage:0,
  },
  date:getTodayDate(),
  card:0,
  amount: 0,
  box:0,
  hospital:0,
  doctor:0,

};
}

// دالة لتعيين النسبة الثابتة لبطاقة العالمي
const setUniversalPercentage = () => {
  if (isUniversalCard()) {
    // إذا لم يكن هناك مستخدم محدد، انتظر حتى يتم اختياره
    if (!form.value.user || !form.value.user.id) {
      return;
    }
    // تعيين النسبة الثابتة إلى 15000
    form.value.user.percentage = 15000;
    // إعادة الحساب فوراً
    calculateAmount();
  }
};

// مراقبة فتح النافذة
watch(() => props.show, (newVal) => {
  if (newVal) {
    // إعادة تعيين النموذج عند فتح النافذة
    if (isUniversalCard()) {
      // تأخير بسيط لضمان تحديث form.value.user
      setTimeout(() => {
        if (form.value.user) {
          form.value.user.percentage = 15000;
          calculateAmount();
        }
      }, 200);
    }
  } else {
    // إعادة تعيين النموذج عند إغلاق النافذة
    restform();
  }
});

// مراقبة تغيير المندوب
watch(() => form.value.user, (newUser) => {
  if (newUser && newUser.id) {
    // إذا كانت بطاقة العالمي، تجاهل نسبة المندوب الأصلية وحدد 15000
    if (isUniversalCard()) {
      form.value.user.percentage = 15000;
    }
    calculateAmount();
  }
}, { deep: true, immediate: false });

// مراقبة تغيير card_id
watch(() => props.card_id, (newCardId) => {
  if (props.show) {
    if (isUniversalCard()) {
      if (form.value.user) {
        form.value.user.percentage = 15000;
        calculateAmount();
      }
    } else {
      // إذا لم تكن بطاقة العالمي، استخدم نسبة المندوب الأصلية
      calculateAmount();
    }
  }
}, { immediate: true });
// دالة للتحقق من بطاقة العالمي
const isUniversalCard = () => {
  if (!props.card_id || !props.cards || props.cards.length === 0) return false;
  
  // تحويل card_id إلى رقم للمقارنة
  const cardIdNum = Number(props.card_id);
  const selectedCard = props.cards.find(card => Number(card.id) === cardIdNum);
  
  if (!selectedCard) return false;
  
  const cardName = selectedCard.name || selectedCard.name_ar || selectedCard.name_en || '';
  const isUniversal = cardName.includes('العالمي') || 
                      cardName.toLowerCase().includes('alamy') || 
                      cardName.toLowerCase().includes('universal');
  
  return isUniversal;
};

// معالجة إدخال النسبة - منع تغييرها لبطاقة العالمي
const handlePercentageInput = () => {
  if (isUniversalCard()) {
    form.value.user.percentage = 15000;
  }
  calculateAmount();
};

// معالجة تغيير النسبة
const handlePercentageChange = () => {
  if (isUniversalCard()) {
    form.value.user.percentage = 15000;
  }
  calculateAmount();
};

const calculateAmount = () => {
  const totalCards = form.value.card ?? 0;

  // بطاقة العالمي - نسبة ثابتة 15000 لكل بطاقة (يجب التحقق منها أولاً قبل أي منطق آخر)
  if (isUniversalCard()) {
    const fixedPercentage = 15000;
    // تأكد من تعيين النسبة إلى 15000 دائماً
    if (form.value.user) {
      form.value.user.percentage = fixedPercentage;
    }
    // حساب المبلغ: عدد البطاقات × 15000
    form.value.amount = totalCards * fixedPercentage;
    // حساب الدخل للصندوق (يمكن تعديله حسب سعر بطاقة العالمي)
    const cardPrice = props.cards.find(c => c.id == props.card_id)?.price || 0;
    form.value.box = (cardPrice * totalCards) - form.value.amount;
    return; // إيقاف التنفيذ هنا - لا نريد تطبيق أي منطق آخر
  }

  // منطق البطاقات الأخرى (فقط إذا لم تكن بطاقة العالمي)
  const userPercentage = form.value.user.percentage ?? 0;

  form.value.amount = totalCards * userPercentage;

  if(props.card_id==1){
    form.value.amount = (form.value.card*userPercentage) ;
    form.value.box = (85000 * form.value.card);
  }
  if(props.card_id==2){
    if (totalCards > 0) {
      form.value.amount = 19000 + (totalCards - 1) * userPercentage;
      form.value.box = (65000 * form.value.card);

    } else {
      form.value.amount =0;
      form.value.box = 0;
    }
 
  }

  if(props.card_id==3){
    if (totalCards > 0) 
    {
      if(totalCards==1){
        form.value.amount = 19000 + (totalCards - 1) * userPercentage;
      }else if(totalCards==2){
        form.value.amount = 38000 + (totalCards - 2) * userPercentage;
      }else if(totalCards>2){
        form.value.amount = 38000 + (totalCards - 2) * userPercentage;
      }
      form.value.box = (85000 * form.value.card) - form.value.amount ;

    } else {
      form.value.amount =0;
      form.value.box = 0;
    }
 
  }
  

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
                          <label for="card" >التاريخ</label>
                          <input
                          id="card"
                          type="date"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.date"   />
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

                        <div className="mb-4 mx-5">
                          <label for="user_id" >البطاقة</label>
                          <select
                            :value="card_id"
                            id="user_id"
                            class="pr-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" disabled>
                            <option selected > البطاقة</option>
                            <option v-for="(user, index) in cards" :key="index" :value="user.id">{{ user.name }}</option>
                          </select>
                        </div>
                         </div>
                        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3 lg:gap-3">
                        <div className="mb-4 mx-5">
                        <label for="card" >نسبة المبيع للبطاقة</label>
                        <input
                          id="card"
                          type="number"
                          @input="handlePercentageInput"
                          @change="handlePercentageChange"
                          :disabled="isUniversalCard()"
                          :placeholder="isUniversalCard() ? '15000 (ثابت لبطاقة العالمي)' : ''"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                          v-model="form.user.percentage"   />
                        <small v-if="isUniversalCard()" class="text-blue-600 text-xs mt-1 block">
                          نسبة ثابتة: 15000 دينار لكل بطاقة
                        </small>
                        </div>
                        <div className="mb-4 mx-5">
                        <label for="card" >عدد البطاقة التي تم بيعها </label>
                        <input
                          id="card"
                          type="number"
                          @input="calculateAmount"
                          @change="calculateAmount"
                          min="0"
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                          v-model="form.card" />
                        </div>
                        <div className="mb-4 mx-5">
                        <label for="card" >مجموع نسبة المبيعات للمندوب</label>
                        <input
                          id="card"
                          type="number"
                          readonly
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm bg-gray-50 font-semibold"
                          v-model="form.amount" />
                        </div>
                        </div>
                        <div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-3 lg:gap-3">
                        <div className="mb-4 mx-5">
                        <label for="card" >الدخل للصندوق</label>
                        <input
                          id="card"
                          type="number"
                          readonly
                          class="mt-1 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm bg-gray-50 font-semibold"
                          v-model="form.box"   />
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