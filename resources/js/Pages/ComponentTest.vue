<script setup>
import GestLayout from '@/Layouts/GuestLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'

const emitTest = e => console.log(e);
</script>

<template>
  <GestLayout>
    <InputLabel>タイトル</InputLabel>
    <TextInput modelValue="初期値が入ります。" @update:modelValue="emitTest"></TextInput>
  </GestLayout>
</template>