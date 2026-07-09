<script setup>
import { watch, ref } from 'vue'
import { useForm } from '@inertiajs/vue3';

import { useApi } from '@/Composables/useApi'
import { useEquipment } from '@/Composables/useEquipment';

import { ROUTE } from '@/Constants/Routes';

import Modal from '@/Components/Modal.vue'
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    show: Boolean,
    data: Object,
});

const emit = defineEmits(['close'])

const { postData } = useApi();
const { getEquipmentData, getRegisteredEquipmentData } = useEquipment();

const form = useForm({
    search: ''
})
const equipments = ref([])
const allEquipments = ref([])
const registeredEquipments = ref([])

watch(() => props.show, (newValue, oldValue) => {
    if (newValue) {
        fetchEquipmentData()
    }
})

watch(() => form.search, (newValue, oldValue) => {
    if (newValue) {
        equipments.value = [...allEquipments.value].filter(item =>
            item.label.toLowerCase().includes(newValue)
        );
        return
    }

    equipments.value = [...allEquipments.value].slice(0, 2)
})

const fetchEquipmentData = async () => {
    const site_id = props.data.site_id

    const [equipmentResults, registeredEquipmentsResult] = await Promise.all([
        getEquipmentData(),
        getRegisteredEquipmentData(site_id)
    ])

    registeredEquipments.value = registeredEquipmentsResult

    const filteredRegEquipment = equipmentResults.filter(equipment =>
        registeredEquipmentsResult.some(regEquipment => regEquipment.equipment_id === equipment.equipment_id && regEquipment.is_own)
    )

    const filteredFreeEquipment = equipmentResults.filter(equipment =>
        !registeredEquipmentsResult.some(regEquipment => regEquipment.equipment_id === equipment.equipment_id)
    )

    const filteredEquipment = [...filteredRegEquipment, ...filteredFreeEquipment].sort((a, b) => a.equipment_id - b.equipment_id)

    allEquipments.value = filteredEquipment.map(item => {
        const isChecked = registeredEquipmentsResult.some(regEquipment => regEquipment.equipment_id === item.equipment_id && regEquipment.is_own)

        return { label: item.description, value: item.equipment_id, check: isChecked }
    });

    equipments.value = [...allEquipments.value].slice(0, 10)
}

const submitAddEquipmentData = async () => {
    const url = route(ROUTE.REG_EQUIPMENT_UPDATE_DATA)

    const params = {
        site_id: props.data.site_id,
        equipment_ids: allEquipments.value.map(item => item.value),
        check_status: allEquipments.value.map(item => item.check)
    }

    try {
        const response = await postData(url, params);

        if (response.code == 200) {
            alert(response.message)
        }

        closeModal()
    } catch (error) {
        console.log(error);
    }
};

const closeModal = () => {
    emit('close');

    setTimeout(() => {
        form.reset();
    }, 1000)
}
</script>

<template>
    <Modal max-width="md" :show="show" @close="closeModal" :closeable="!form.processing">
        <div class=" p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Add Equipment
            </h2>
            <div class="mt-4">
                <TextInput id="search" type="text" placeholder="Search equipment..." class="mt-1 block w-full" required
                    autofocus v-model="form.search" />
            </div>
            <div class="mt-4">
                <p class="text-xs">Showing {{ equipments.length }} of {{ allEquipments.length }} equipments.</p>
                <p class="text-xs">Search to find additional equipments.</p>
            </div>
            <div class="mt-4 flex flex-col gap-2">
                <div v-for="(equipment, index) in equipments"
                    class="even:bg-slate-200 odd:bg-slate-50  text-sm p-3 rounded-md flex flex-col gap-3 border sm:items-center sm:flex-row"
                    role="alert">
                    <Checkbox v-model:checked="equipment.check" />
                    <span class="ms-2 text-sm text-gray-600">{{ equipment.label }}</span>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Cancel
                </SecondaryButton>

                <PrimaryButton class="ms-3" @click="submitAddEquipmentData" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Add
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>