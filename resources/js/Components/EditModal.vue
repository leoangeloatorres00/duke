<script setup>
import { watch, inject, ref } from 'vue'
import { useForm } from '@inertiajs/vue3';

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

const tab = inject('tab')
const user = inject('user');
const form = useForm({})
const sites = ref([])

const types = [
    { label: 'SuperAdmin', value: 'SuperAdmin' },
    { label: 'Admin', value: 'Admin' },
]

const actives = [
    { label: 'Active', value: 1 },
    { label: 'Inactive', value: 0 },
]

const conditions = [
    { label: 'Working', value: "Working" },
    { label: 'Not Working', value: "Not Working" },
]


watch(() => props.show, (newValue, oldValue) => {
    if (newValue) {
        getDetailsById()
        getSiteList()
    }
})

const setFormData = (data) => {
    const user_id = user.value.user_id

    if (tab.value === 'site') {
        form.user_id = user_id
        form.description = data.description
        form.active = data.active
    }

    if (tab.value === 'user') {
        form.first_name = data.first_name
        form.last_name = data.last_name
        form.user_type = data.user_type
    }

    if (tab.value === 'equipment') {
        form.user_id = user_id
        form.serial_number = data.serial_number
        form.description = data.description
        form.condition = data.condition
        form.site_id = data.registered_equipment.site_id
    }
}

const getSiteList = (page = 1) => {
    const user_id = user.value.user_id
    const url = route(`site.index`, { 'user_id': user_id, 'page': page });

    axios.get(url)
        .then(response => {
            const { data } = response.data;
            sites.value = data.map((item) => {
                return { label: item.description, value: item.site_id }
            });
        })
        .catch(error => {
            console.error(`Deleting ${tab.value}'s items:`, error);
        });
}

const getDetailsById = () => {
    const id = props.data.id
    const url = route(`${tab.value}.show`, id);

    axios.get(url)
        .then(response => {
            const { data } = response
            setFormData(data)
        })
        .catch(error => {
            console.error(`Deleting ${tab.value}'s items:`, error);
        });
}

const closeModal = () => {
    emit('close');

    setTimeout(() => {
        form.errors.description = ''
        form.errors.active = ''
        form.errors.serial_number = ''
        form.errors.condition = ''
        form.errors.site_id = ''
    }, 1000)

};

const editConfirmed = () => {
    const url = route(`${tab.value}.update`, props.data.id)

    axios.patch(url, form)
        .then(response => {
            closeModal();
        })
        .catch(error => {
            const { errors } = error.response.data;

            const descriptionErrors = errors.description;
            const activeErrors = errors.active;
            const conditionErrors = errors.condition;
            const serialNumberErrors = errors.serial_number;
            const siteErrors = errors.site_id;
            const userTypeErrors = errors.user_type;

            form.errors.description = ''
            if (descriptionErrors && descriptionErrors.length > 0) {
                form.errors.description = descriptionErrors[0];
                // Output: "This name has already been taken."
            }

            form.errors.active = ''
            if (activeErrors && activeErrors.length > 0) {
                form.errors.active = activeErrors[0];
                // Output: "This name has already been taken."
            }

            form.errors.serial_number = ''
            if (serialNumberErrors && serialNumberErrors.length > 0) {
                form.errors.serial_number = serialNumberErrors[0];
                // Output: "This name has already been taken."
            }

            form.errors.condition = ''
            if (conditionErrors && conditionErrors.length > 0) {
                form.errors.condition = conditionErrors[0];
                // Output: "This name has already been taken."
            }

            form.errors.site_id = ''
            if (siteErrors && siteErrors.length > 0) {
                form.errors.site_id = siteErrors[0];
                // Output: "This name has already been taken."
            }

            form.errors.user_type = ''
            if (userTypeErrors && userTypeErrors.length > 0) {
                form.errors.user_type = userTypeErrors[0];
                // Output: "This name has already been taken."
            }
        });
};
</script>

<template>
    <Modal max-width="md" :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Details
            </h2>

            <template v-if="tab === 'user'">
                <div class="mt-4">
                    <InputLabel for="first_name" value="First Name" />

                    <TextInput id="first_name" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.first_name" />

                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>
                <div class="mt-4">
                    <InputLabel for="last_name" value="Last Name" />

                    <TextInput id="last_name" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.last_name" />

                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
                <div class="mt-4">
                    <InputLabel for="user_type" value="User Type" />

                    <Select v-model="form.user_type" :options="types" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.user_type" />
                </div>
            </template>

            <template v-if="tab === 'site'">
                <div class="mt-4">
                    <InputLabel for="description" value="Site Name" />

                    <TextInput id="description" type="text" class="mt-1 block w-full" required autofocus
                        v-model="form.description" />

                    <InputError class="mt-2" :message="form.errors.description" />
                </div>
                <div class="mt-4">
                    <InputLabel for="status" value="Status" />

                    <Select v-model="form.active" :options="actives" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.active" />
                </div>
            </template>

            <template v-if="tab === 'equipment'">
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

                    <Select v-model="form.condition" :options="conditions" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.condition" />
                </div>

                <div class="mt-4">
                    <InputLabel for="site" value="Site Name" />

                    <Select v-model="form.site_id" :options="sites" class="mt-1 block w-full"
                        placeholder="Select a site" />

                    <InputError class="mt-2" :message="form.errors.site_id" />
                </div>
            </template>

            <div class=" mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" @click="editConfirmed">
                    Update
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>