<script setup>
const props = defineProps({
    text: String,
    variant: {
        type: String,
        default: 'success',
        validator: (value) => {
            return ['success', 'danger'].includes(value);
        }
    }
});

const background = {
    success: 'bg-green-400',
    danger: 'bg-red-400'
}

</script>

<template>
    <span
        :class="`px-2 py-1 rounded-md text-xs font-medium inline-flex items-center ${background[props.variant]} text-white border border-slate-200`">
        {{ props.text }}
    </span>
</template>