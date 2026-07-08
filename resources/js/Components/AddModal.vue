<script setup>
import { watch, inject, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3';

import { useApi } from '@/Composables/useApi'

import { TABS } from '@/Constants/Tabs';
import { SITE } from '@/Constants/Site';
import { ROUTE } from '@/Constants/Routes';
import { EQUIPMENT } from '@/Constants/Equipment';

import Modal from '@/Components/Modal.vue'
import Select from '@/Components/Select.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
    show: Boolean,
    data: Object,
});

const emit = defineEmits(['close'])

const { postData } = useApi();

const currentTab = inject('currentTab');
const currentUser = inject('currentUser');

const form = useForm({})

const ROUTE_MAP = {
    site: ROUTE.SITE_ADD_DATA,
    equipment: ROUTE.EQUIPMENT_ADD_DATA,
}

const isFormUnchanged = computed(() =>
    !Object.values(form.data()).some(value => {
        return value !== null && value !== undefined && String(value).trim() !== "";
    })
);

watch(() => props.show, (newValue, oldValue) => {
    if (newValue) {
        setFormData()
    }
})

const setFormData = () => {
    switch (currentTab.value) {
        case TABS.SITE:
            form.defaults({
                description: '',
                active: ''
            })

            break;
        case TABS.EQUIPMENT:
            form.defaults({
                description: '',
                serial_number: '',
                condition: '',
            })
            break;
    }
}

const submitAddData = async () => {
    const url = route(ROUTE_MAP[currentTab.value]);

    const user_id = currentUser.value.user_id
    form.user_id = user_id;

    form.clearErrors();

    try {
        const response = await postData(url, form);

        if (response.code == 200) {
            alert(response.message)
        }

        closeModal()
    } catch (error) {
        const { errors } = error.response.data;

        if (errors) {
            for (const [key, value] of Object.entries(errors)) {
                form.errors[key] = value[0];
            }
        }
    }
};

const closeModal = () => {
    emit('close');

    setTimeout(() => {
        form.resetAndClearErrors();
    }, 1000)
}
</script>

<template>
    <Modal max-width="md" :show="show" @close="closeModal" :closeable="!form.processing">
        <div class=" p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Add New Record
            </h2>

            <template v-if="currentTab === 'site'">
                <div class="mt-4">
                    <InputLabel for="description" value="Site Name" />

                    <TextInput id="description" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.description" />

                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="mt-4">
                    <InputLabel for="status" value="Status" />

                    <Select v-model="form.active" :options="SITE.STATUS" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.active" />
                </div>
            </template>

            <template v-if="currentTab === 'equipment'">
                <div class="mt-4">
                    <InputLabel for="serial_number" value="Serial Number" />

                    <TextInput id="serial_number" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.serial_number" />

                    <InputError class="mt-2" :message="form.errors.serial_number" />
                </div>
                <div class="mt-4">
                    <InputLabel for="description" value="Equipment Name" />

                    <TextInput id="description" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.description" />

                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="mt-4">
                    <InputLabel for="status" value="Status" />

                    <Select v-model="form.condition" :options="EQUIPMENT.STATUS" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.condition" />
                </div>
            </template>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" @click="submitAddData"
                    :class="{ 'opacity-25': form.processing || isFormUnchanged }"
                    :disabled="form.processing || isFormUnchanged">
                    Create
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>