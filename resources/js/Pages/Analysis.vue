<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, onMounted } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';
import Chart from '@/Components/Chart.vue';
import ResultTable from '@/Components/ResultTable.vue';

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
      data.type = res.data.type;
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
            分析方法<br>
            <input type="radio" v-model="form.type" value="perDay" checked><span class="mr-4">日別</span>
            <input type="radio" v-model="form.type" value="perMonth"><span class="mr-4">月別</span>
            <input type="radio" v-model="form.type" value="perYear"><span class="mr-4">年別</span>
            <input type="radio" v-model="form.type" value="decile"><span class="mr-4">デシル分析</span><br>
            From: <input type="date" name="startDate" v-model="form.startDate">
            To: <input type="date" name="endDate" v-model="form.endDate"><br>
            <div class="submit-button">
              <button>分析する</button>
            </div>
          </form>

          <div v-show="data.data" class="w-full mx-auto overflow-auto ">
            <Chart :data="data" />
            <ResultTable :data="data" />
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