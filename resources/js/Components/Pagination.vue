<script setup>
const props = defineProps({
    links: Array,
    lastPage: Number,
    currentPage: Number,
});
</script>

<template>
    <nav aria-label="Pagination" class="flex space-x-4 justify-center mt-8">
        <template v-for="(link, index) in links">
            <button v-if="index === 0" @click="$emit('handleClick', currentPage - 1)" :disabled="currentPage == 1"
                class="flex items-center justify-center shrink-0 bg-gray-100 w-9 h-9 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                    :class="`${currentPage == 1 ? 'fill-slate-400' : 'fill-slate-800'} size-3 rotate-180 overflow-visible`"
                    viewBox="0 0 451.846 451.847" aria-hidden="true">
                    <path
                        d="M345.441 248.292 151.154 442.573c-12.359 12.365-32.397 12.365-44.75 0-12.354-12.354-12.354-32.391 0-44.744L278.318 225.92 106.409 54.017c-12.354-12.359-12.354-32.394 0-44.748 12.354-12.359 32.391-12.359 44.75 0l194.287 194.284c6.177 6.18 9.262 14.271 9.262 22.366 0 8.099-3.091 16.196-9.267 22.373"
                        data-original="#000000" />
                </svg>
            </button>

            <button v-if="index === links.length - 1" @click="$emit('handleClick', currentPage + 1)"
                :disabled="currentPage == lastPage"
                class="flex items-center justify-center shrink-0 bg-gray-100 w-9 h-9 rounded-md hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg"
                    :class="`${currentPage == lastPage ? 'fill-slate-400' : 'fill-slate-800'} size-3 overflow-visible`"
                    viewBox="0 0 451.846 451.847" aria-hidden="true">
                    <path
                        d="M345.441 248.292 151.154 442.573c-12.359 12.365-32.397 12.365-44.75 0-12.354-12.354-12.354-32.391 0-44.744L278.318 225.92 106.409 54.017c-12.354-12.359-12.354-32.394 0-44.748 12.354-12.359 32.391-12.359 44.75 0l194.287 194.284c6.177 6.18 9.262 14.271 9.262 22.366 0 8.099-3.091 16.196-9.267 22.373"
                        data-original="#000000" />
                </svg>
            </button>

            <button v-if="index > 0 && index < links.length - 1" @click="$emit('handleClick', link.label)"
                :class="`flex items-center justify-center shrink-0 text-sm font-semibold w-9 h-9 rounded-md border ${link.active ? 'bg-slate-400 text-white' : 'text-slate-900'}`">
                {{ link.label }}
            </button>
        </template>
    </nav>
</template>