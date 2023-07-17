<script setup>
import { getToday } from '@/common';
import { Inertia } from '@inertiajs/inertia';
import { onMounted, reactive, ref, computed } from 'vue';

const props = defineProps({
  'customers' : Array,
  'items' : Array
})
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

const form = reactive({ 
  date: null,
  customer_id: null,
  status: true,
  items: []
})

const itemList =ref([
])

const quantity = [];
for(let i=0; i<10; i++){
  quantity.push(i)
}

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
</script>

<template>
  <form @submit.prevent="storePurchase">
    日付<br>
    <input type="date" name="date" v-model="form.date"><br>
    会員名<br>
    <select name="customer" v-model="form.customer_id">
      <option v-for="customer in customers" :value="customer.id" :key="customer.id">
        {{ customer.id }} : {{ customer.name }}
      </option>
    </select>
    <br><br>
    商品・サービス<br>
    <table>
      <thead>
        <tr>
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
    <br>
    合計 {{ totalPrice }} 円<br>

    <button>登録する</button>
  </form>
</template>