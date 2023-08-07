<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { reactive, onMounted } from 'vue';
import { getToday } from '@/common';
import axios from 'axios';

onMounted(() => {
  form.startDate = getToday();
  form.endDate = getToday();
});

const form = reactive({
  startDate: null,
  endDate: null,
  type: 'perDay'
});

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
      // data.value = res.data
      console.log(res.data);
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
  height: 2.2rem;
}
</style>