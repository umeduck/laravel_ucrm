<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, reactive, ref, computed } from 'vue';
import { Inertia } from "@inertiajs/inertia";
import dayjs from 'dayjs';

const props = defineProps({
  'items' : Array,
  'order' : Array,
})

onMounted(() => {
  console.log(props.items);
  console.log(props.order[0].id);
})
</script>

<template>
  <Head title="購買履歴 詳細画面" />
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">購買履歴 詳細画面</h2>
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
                        <div type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ dayjs(props.order[0].created_at).format("YYYY/MM/DD") }}
                        </div>
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="date" class="leading-7 text-sm text-gray-600">会員名</label>
                        <div type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ props.order[0].customer_name }}
                        </div>
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
                          <tr v-for="item in props.items">
                            <td>{{ item.item_id }}</td>
                            <td>{{ item.item_name }}</td>
                            <td>{{ item.item_price }} 円</td>
                            <td>{{ item.quantity }}</td>
                            <td>
                              {{ item.subtotal }} 円
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <br>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="date" class="leading-7 text-sm text-gray-600">合計</label>
                        <div type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ props.order[0].total }}
                        </div>
                      </div>
                    </div>
                    <div class="p-2 w-full">
                      <div class="relative">
                        <label for="date" class="leading-7 text-sm text-gray-600">ステータス</label>
                        <div v-if="props.order[0].status == true" type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          未キャンセル
                        </div>
                        <div v-if="props.order[0].status == false" type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          キャンセル済
                        </div>
                      </div>
                    </div>
                    <div v-if="props.order[0].status == false" class="p-2 w-full">
                      <div class="relative">
                        <label for="date" class="leading-7 text-sm text-gray-600">キャンセル日</label>
                        <div type="date" id="date" name="date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                          {{ dayjs(props.order[0].update_at).format("YYYY/MM/DD") }}
                        </div>
                      </div>
                    </div>
                  </div>
                  <div v-if="props.order[0].status == true" class="submit-button">
                    <Link as="button" :href="route('purchases.edit', {purchase: props.order[0].id})">編集する</Link>
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
.submit-button button {
  width: 100%;
  height: 3rem;
}
</style>