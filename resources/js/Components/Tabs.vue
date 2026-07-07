<script setup>
import { TABS } from '@/Constants/Tabs';

const props = defineProps({
    currentTab: String
});
</script>

<template>
    <ul
        class="inline-flex flex-wrap font-medium text-sm text-slate-600 border border-slate-300 rounded-md divide-x divide-slate-300">
        <li v-for="(value, key, index) in TABS" :key="key">
            <button class="py-2 px-4 cursor-pointer transition-colors hover:text-slate-900" :class="[
                { 'rounded-l-[5px]': index === 0, 'rounded-r-[5px]': index === TABS.length - 1, 'text-slate-900 bg-slate-100': props.currentTab === value }
            ]" @click="$emit('handleChangeTab', value)">
                <span class="capitalize">{{ value }}</span>
            </button>
        </li>
    </ul>
</template>