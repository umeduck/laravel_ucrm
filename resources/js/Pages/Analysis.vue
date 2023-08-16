<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, onMounted } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';

onMounted(() => {
  form.startDate = getToday();
  form.endDate = getToday();
});

const form = reactive({
  startDate: null,
  endDate: null,
  type: 'perDay'
});

const data = reactive({});

const getData = async () => {
  try{
    await axios.get('/api/analysis/',{
      params:{
        startDate:form.startDate,
        endDate:form.endDate,
        type: form.type
      }
    })
    .then(res => {
      data.data = res.data.data;
      data.labels = res.data.labels;
      data.totals = res.data.totals;
    });
  }catch(e){
    console.log(e.message);
  }
};
</script>

<template>
  <Head title="データ分析" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg py-10 px-10">
          <form @submit.prevent="getData">
            From: <input type="date" name="startDate" v-model="form.startDate">
            To: <input type="date" name="endDate" v-model="form.endDate"><br>
            <div class="submit-button">
              <button>分析する</button>
            </div>
          </form>

          <div v-show="data.data" class="w-full mx-auto overflow-auto ">
            <Chart :data="data" />
          </div>
          

          <div v-show="data.data" class="lg:w-2/3 w-full mx-auto overflow-auto">
            <table class="table-auto w-full text-left whitespace-no-wrap">
              <thead>
                <tr>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">年月日</th>
                  <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">金額</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="data in data.data" :key="data.date">
                  <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ data.date  }}</td>
                  <td class="border-t-2 border-b-2 border-gray-200 px-4 py-3">{{ data.total }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style>
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
  height: 4.2rem;
}
</style>