<script setup>
import { watch, inject, ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3';

import { useApi } from '@/Composables/useApi'
import { useSite } from '@/Composables/useSite'

import { TABS } from '@/Constants/Tabs'
import { USER } from '@/Constants/User';
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

const { patchData } = useApi();
const { getSiteData } = useSite();

const currentTab = inject('currentTab')
const currentUser = inject('currentUser');

const form = useForm({})
const sites = ref([])
const duplicateForm = ref({})

const ROUTE_MAP = {
    user: ROUTE.USER_UPDATE_DATA,
    site: ROUTE.SITE_UPDATE_DATA,
    equipment: ROUTE.EQUIPMENT_UPDATE_DATA,
}

const isFormUnchanged = computed(() =>
    JSON.stringify(duplicateForm.value) === JSON.stringify(form.data())
);

watch(() => props.show, (newValue, oldValue) => {
    if (newValue) {
        const data = props.data;
        setFormData(data);
        fetchSiteData()
    }
})

const setFormData = (data) => {
    const user_id = currentUser.value.user_id

    switch (currentTab.value) {
        case TABS.SITE:
            form.user_id = user_id
            form.active = data.active
            form.site_id = data.site_id
            form.description = data.description
            break;
        case TABS.USER:
            form.user_type = data.user_type
            form.last_name = data.last_name
            form.first_name = data.first_name
            break;
        case TABS.EQUIPMENT:
            form.user_id = user_id
            form.condition = data.condition
            form.description = data.description
            form.equipment_id = data.equipment_id
            form.serial_number = data.serial_number
            form.site_id = data.registered_equipment.site_id
            break;
    }

    duplicateForm.value = { ...form }
}

const fetchSiteData = async () => {
    const response = await getSiteData()

    sites.value = response.map((item) => {
        return { label: item.description, value: item.site_id }
    });
}

const submitEditData = async () => {
    const id = props.data[`${currentTab.value}_id`]
    const url = route(ROUTE_MAP[currentTab.value], id)

    form.clearErrors();

    try {
        const response = await patchData(url, form);

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
};
</script>

<template>
    <Modal max-width="md" :show="show" @close="closeModal" :closeable="!form.processing">
        <div class=" p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Edit Details
            </h2>

            <template v-if="currentTab === TABS.USER">
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

                    <Select v-model="form.user_type" :options="USER.TYPE" class="mt-1 block w-full"
                        placeholder="Select a status" />

                    <InputError class="mt-2" :message="form.errors.user_type" />
                </div>
            </template>

            <template v-if="currentTab === TABS.SITE">
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

            <template v-if="currentTab === TABS.EQUIPMENT">
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

                <div class="mt-4">
                    <InputLabel for="site" value="Site Name" />

                    <Select v-model="form.site_id" :options="sites" class="mt-1 block w-full"
                        placeholder="Select a site" />

                    <InputError class="mt-2" :message="form.errors.site_id" />
                </div>
            </template>

            <div class=" mt-6 flex justify-end">
                <SecondaryButton @click="closeModal" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" @click="submitEditData"
                    :class="{ 'opacity-25': form.processing || isFormUnchanged }"
                    :disabled="form.processing || isFormUnchanged">
                    Update
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>