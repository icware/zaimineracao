<script setup>
import { onMounted, ref, watch, nextTick } from 'vue';
import { useLayout } from '@/layout/composables/layout';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/AuthStore';

const { isDarkTheme } = useLayout();
const auth = useAuthStore();
const router = useRouter();


</script>

<template>

    <div class="grid"></div>

</template>
