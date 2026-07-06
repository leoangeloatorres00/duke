<script setup>
import { inject } from 'vue'

import Modal from '@/Components/Modal.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    show: Boolean,
    data: Object,
});

const emit = defineEmits(['close'])

const tab = inject('tab');

const closeModal = () => {
    emit('close');
};

const deleteConfirmed = () => {
    const id = props.data.id
    const url = route(`${tab.value}.destroy`, id)

    axios.delete(url)
        .then(response => {
            closeModal();
        })
        .catch(error => {
            console.error(`Deleting ${tab.value}'s items:`, error);
        });
};
</script>

<template>
    <Modal max-width="md" :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Confirm Delete
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Are you sure you want to permanently delete this item? This action cannot be undone and all associated
                data will be lost.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>

                <DangerButton class="ms-3" @click="deleteConfirmed">
                    Delete
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>