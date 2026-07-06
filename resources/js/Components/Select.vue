<script setup>
defineProps({
    modelValue: {
        type: [String, Number, null],
        default: '',
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select an option',
    },
})

defineEmits(['update:modelValue'])
</script>

<template>
    <select :value="modelValue" @change="$emit('update:modelValue', $event.target.value)"
        class="border rounded px-3 py-2">
        <option disabled value="" hidden>
            {{ placeholder }}
        </option>

        <option v-for="option in options" :key="option['value']" :value="option['value']">
            {{ option['label'] }}
        </option>
    </select>
</template>