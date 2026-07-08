<script setup>
import { onMounted, ref, provide } from 'vue';
import { usePage } from '@inertiajs/vue3';

import { getFullName } from '@/utils';

import { useApi } from '@/Composables/useApi'

import { USER } from '@/Constants/User'
import { TABS } from '@/Constants/Tabs'
import { ROUTE } from '@/Constants/Routes'
import { HEADERS } from '@/Constants/TableHeaders';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Tabs from '@/Components/Tabs.vue';
import Table from '@/Components/Table.vue';
import Badge from '@/Components/Badge.vue';
import AddIcon from '@/Components/AddIcon.vue'
import EditIcon from '@/Components/EditIcon.vue'
import DeleteIcon from '@/Components/DeleteIcon.vue'
import Pagination from '@/Components/Pagination.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

import AddModal from '@/Components/AddModal.vue';
import EditModal from '@/Components/EditModal.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import AddEquipmentModal from '@/Components/AddEquipmentModal.vue';

const { props } = usePage();

const { getData: fetchData } = useApi();

const modalData = ref({});
const currentTab = ref(null);
const currentUser = ref(props.auth.user);

const isAddModalOpen = ref(false);
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isAddEquipmentModalOpen = ref(false);

const pages = ref({});
const items = ref([]);
const headers = ref([]);

const HEADER_MAP = {
    user: HEADERS.USER,
    site: HEADERS.SITE,
    equipment: HEADERS.EQUIPMENT,
}

const ROUTE_MAP = {
    user: ROUTE.USER_DATA,
    site: ROUTE.SITE_DATA,
    equipment: ROUTE.EQUIPMENT_DATA,
}

onMounted(async () => {
    currentTab.value = getInitialTabData()

    changeTableHeader(currentTab.value);
});

const getInitialTabData = () => {
    if (currentUser.value.user_type === USER.ADMIN) {
        const { USER, ...remainingTabs } = TABS;
        return Object.values(remainingTabs)[0]
    }
    return Object.values(TABS)[0]
}

const getTableHeader = () => {
    headers.value = HEADER_MAP[currentTab.value];
}

const getTableData = async (page = 1) => {
    const user_id = currentUser.value.user_id;

    const url = route(ROUTE_MAP[currentTab.value]);
    const params = { user_id: user_id, page: page };

    try {
        const response = await fetchData(url, params);

        items.value = response.data

        pages.value = {
            links: response.links,
            lastPage: response.last_page,
            currentPage: response.current_page,
        }
    } catch (err) {
        console.log(err)
    }
}

const changeTableHeader = (value) => {
    currentTab.value = value;

    getTableHeader();

    getTableData();
}

const openAddModal = async () => {
    // const sites = await getSiteData();

    // if (sites.length == 0 && currentTab.value == TABS.EQUIPMENT) {
    //     alert("A site must be added first before adding equipment.");
    //     return
    // }

    isAddModalOpen.value = true;
}

const openEditModal = (item) => {
    isEditModalOpen.value = true;
    modalData.value = item;
}

const openDeleteModal = (item) => {
    isDeleteModalOpen.value = true;
    modalData.value = item;
}

const openAddEquipmentModal = (item) => {
    isAddEquipmentModalOpen.value = true;
    modalData.value = item;
}

const closeModal = () => {
    isAddModalOpen.value = false;
    isEditModalOpen.value = false;
    isDeleteModalOpen.value = false;
    isAddEquipmentModalOpen.value = false;

    setTimeout(() => {
        getTableData();
    }, 100)
}

provide('currentTab', currentTab)
provide('currentUser', currentUser)
</script>

<template>
    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl p-4 sm:p-8">
            <div className="p-8 bg-white shadow-sm rounded-lg">
                <AddModal :show="isAddModalOpen" @close="closeModal" />

                <EditModal :show="isEditModalOpen" :data="modalData" @close="closeModal" />

                <DeleteModal :show="isDeleteModalOpen" :data="modalData" @close="closeModal" />

                <AddEquipmentModal :show="isAddEquipmentModalOpen" :data="modalData" @close="closeModal" />

                <div class="flex justify-between">
                    <Tabs :currentTab="currentTab" @handleChangeTab="changeTableHeader" />

                    <SecondaryButton @click="openAddModal" v-if="currentTab !== TABS.USER">
                        Add Record
                    </SecondaryButton>
                </div>

                <div class="mt-6">
                    <Table :headers="headers" :items="items">
                        <template #cell-owner="{ item }">
                            <span>{{ getFullName(item.user) }}</span>
                        </template>

                        <template #cell-active="{ value }">
                            <Badge :text="value ? 'Active' : 'Inactive'" :variant="value ? 'success' : 'danger'" />
                        </template>

                        <template #cell-condition="{ value }">
                            <Badge :text="value" :variant="value === 'Working' ? 'success' : 'danger'" />
                        </template>

                        <template #cell-action="{ item }">
                            <div class="flex gap-2 w-[100px]">
                                <button>
                                    <EditIcon @click="openEditModal(item)" aria-label="Open edit modal" />
                                </button>
                                <button>
                                    <DeleteIcon @click="openDeleteModal(item)" aria-label="Open delete modal" />
                                </button>
                                <button v-if="currentTab === 'site'">
                                    <AddIcon @click="openAddEquipmentModal(item)"
                                        aria-label="Open add equipment modal" />
                                </button>
                            </div>
                        </template>
                    </Table>
                </div>

                <Pagination :pages="pages" @handleClick="getTableData" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>