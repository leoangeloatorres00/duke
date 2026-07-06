<script setup>
defineProps({
    headers: {
        type: Array,
        required: true
    },
    items: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <div class="max-w-7xl mx-auto border border-slate-200 rounded-md overflow-x-auto">

        <table class="w-full">
            <thead class="text-slate-900 text-left text-sm font-semibold border-b border-slate-300 whitespace-nowrap">
                <tr class="bg-slate-50">
                    <th v-for="header in headers" class="px-4 py-3.5">
                        {{ header.label }}
                    </th>
                </tr>
            </thead>

            <tbody class="text-sm divide-y divide-slate-200">
                <tr v-for="(item, index) in items" :key="index" class="hover:bg-slate-200 even:bg-slate-50">
                    <td v-for="header in headers" :key="header.key" class="px-4 py-4 text-slate-500 whitespace-nowrap">
                        <slot :name="`cell-${header.key}`" :value="item[header.key]" :row="item">
                            {{ item[header.key] }}
                        </slot>
                    </td>
                </tr>
                <tr v-if="items.length == 0" class="hover:bg-slate-200 even:bg-slate-50">
                    <td :colspan="headers.length" class="text-center px-4 py-4 text-slate-500 whitespace-nowrap">
                        No data available at this time.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>