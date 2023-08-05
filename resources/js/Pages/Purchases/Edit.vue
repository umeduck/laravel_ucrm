<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref, computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import InputError from '@/Components/InputError.vue';
import dayjs from 'dayjs';

const props = defineProps({
  'items' : Array,
  'order' : Array,
  errors: Object
})

const itemList =ref([])

const form = reactive({ 
  id: props.order[0].id,
  date: dayjs(props.order[0].created_at).format('YYYY-MM-DD'),
  customer_id: props.order[0].customer_id,
  status: props.order[0].status,
  items: itemList.value
})

const quantity = [];
for(let i=0; i<10; i++){
  quantity.push(i)
}

onMounted(() => {
  console.log(props.items);
  console.log(props.order);
  props.items.forEach( item => {
    if(item.quantity > 0){
      itemList.value.push({
        id: item.id,
        name: item.name,
        price: item.price,
        quantity: item.quantity
      })
    }
    
  })
})

const totalPrice = computed(() => {
  let total = 0
  itemList.value.forEach( item => {
    total += item.price * item.quantity
  })
  return total
})

const updatePurchase = id => {
  // itemList.value.forEach( item => {
  //   if (item.quantity > 0) {
  //     form.items.push({ id: item.id, quantity: item.quantity})
  //   }
  // })
  Inertia.put(route('purchases.update', {purchase: id}), form)
}
</script>

<template>
  <Head title="購入履歴 編集画面" />
  <AuthenticatedLayout>
  <template #header>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      購入履歴 編集画面
    </h2>
  </template>
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <section class="text-gray-600 body-font relative">
          <form @submit.prevent="updatePurchase(form.id)">
            <div class="container px-5 py-24 mx-auto">
              <div class="lg:w-1/2 md:w-2/3 mx-auto">
                <div class="flex flex-wrap -m-2">
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                      <input disabled type="date" id="date" name="date" :value="form.date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                      <InputError :message="errors.date"></InputError>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label for="customer" class="leading-7 text-sm text-gray-600">会員名</label>
                      <input disabled type="text" id="customer_name" name="customer_name" :value="props.order[0].customer_name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                      <InputError :message="errors.customer"></InputError>
                    </div>
                  </div>
                  <div class="p-2 w-full">
                    商品・サービス<br>
                    <table class="p-2 w-full">
                      <thead>
                        <tr class="bg-gray-100">
                          <th>Id</th>
                          <th>商品名</th>
                          <th>金額</th>
                          <th>数量</th>
                          <th>小計</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="item in itemList">
                          <td>{{ item.id }}</td>
                          <td>{{ item.name }}</td>
                          <td>{{ item.price }} 円</td>
                          <td>
                            <select name="quantity" v-model="item.quantity">
                              <option v-for="q in quantity" :value="q">{{ q }}</option>
                            </select>
                          </td>
                          <td>
                            {{ item.price * item.quantity }} 円
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <br>
                  <div class="total-price">
                    合計 {{ totalPrice }} 円<br>
                  </div>
                  <div class="p-2 w-full">
                    <div class="relative">
                      <label form="status" class="leading-7 text-sm text-gray-600">ステータス</label>
                      <input type="radio" id="status" name="status" v-model="form.status" value="1">未キャンセル
                      <input type="radio" id="status" name="status" v-model="form.status" value="0">キャンセルする
                      <InputError :message="errors.customer"></InputError>
                    </div>
                  </div>
                </div>
                <div class="submit-button">
                  <button>更新する</button>
                </div>
              </div>
            </div>
          </form>
        </section>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
</template>


<style>
table{
    border-collapse:  collapse;
}
th, td{
  border:1px solid #000000;
  text-align: center;
}
.total-price{
    text-align: center;
    width: 100%;
    font-size: 25px;
    border: 1px solid red;
    padding: 10px 0;
    margin: 10px 70px;
}
.submit-button{
    display: block;
    text-align: center;
    text-decoration: none;
    width: 220px;
    margin: 10px auto 0;
    font-weight: bold;
    border: 2px solid #27acd9;
    color: #27acd9;
    transition: 0.6s;
}
.submit-button:hover {
	color: #fff;
	background: #27acd9;
}
.submit-button button {
  width: 100%;
  height: 3rem;
}
</style>