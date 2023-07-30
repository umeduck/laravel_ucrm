<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { onMounted, reactive, ref, computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import InputError from '@/Components/InputError.vue';
import { getToday } from '@/common';
import MicroModal from '@/Components/MicroModal.vue';

const props = defineProps({
  'customers' : Array,
  'items' : Array,
  errors: Object
})

const form = reactive({ 
  date: null,
  customer_id: null,
  status: true,
  items: []
})

const itemList =ref([])

const quantity = [];
for(let i=0; i<10; i++){
  quantity.push(i)
}

onMounted(() => {
  form.date = getToday();
  props.items.forEach( item => {
    itemList.value.push({
      id: item.id,
      name: item.name,
      price: item.price,
      quantity: 0
    })
  })
})

const totalPrice = computed(() => {
  let total = 0
  itemList.value.forEach( item => {
    total += item.price * item.quantity
  })
  return total
})

const storePurchase = () => {
  itemList.value.forEach( item => {
    if (item.quantity > 0) {
      form.items.push({ id: item.id, quantity: item.quantity})
    }
  })
  Inertia.post('/purchases', form)
}

const setCustomerId = id => {
  form.customer_id = id
}

</script>

<template>
    <Head title="購入画面" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">購入画面</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <section class="text-gray-600 body-font relative">
                        <form @submit.prevent="storePurchase">
                            <div class="container px-5 py-24 mx-auto">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="date" class="leading-7 text-sm text-gray-600">日付</label>
                                                <input type="date" id="date" name="date" v-model="form.date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                <InputError :message="errors.date"></InputError>
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="customer" class="leading-7 text-sm text-gray-600">顧客</label>
                                                <MicroModal @update:customerId="setCustomerId" />
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
                                    </div>
                                    <div class="submit-button">
                                        <button>購入する</button>
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
    padding: 1rem 4rem;
    font-weight: bold;
    border: 2px solid #27acd9;
    color: #27acd9;
    transition: 0.6s;
}
.submit-button:hover {
	color: #fff;
	background: #27acd9;
}
</style>