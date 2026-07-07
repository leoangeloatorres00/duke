<script setup>
import { inject } from 'vue'

import { useApi } from '@/Composables/useApi'

import { ROUTE } from '@/Constants/Routes'

import Modal from '@/Components/Modal.vue'
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    show: Boolean,
    data: Object,
});

const emit = defineEmits(['close'])

const { deleteData } = useApi();

const currentTab = inject('currentTab');

const ROUTE_MAP = {
    user: ROUTE.USER_DELETE_DATA,
    site: ROUTE.SITE_DELETE_DATA,
    equipment: ROUTE.EQUIPMENT_DELETE_DATA,
}

const submitDeleteData = async () => {
    const id = props.data[`${currentTab.value}_id`]
    const url = route(ROUTE_MAP[currentTab.value], id)

    const response = await deleteData(url);

    if (response.code == 200) {
        alert(response.message)
    }

    closeModal();
};

const closeModal = () => {
    emit('close');
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

                <DangerButton class="ms-3" @click="submitDeleteData">
                    Delete
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>