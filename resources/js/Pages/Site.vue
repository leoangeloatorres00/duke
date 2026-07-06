<script setup>
import { onMounted, ref, inject } from 'vue'

import Table from '@/Components/Table.vue'
import EditIcon from '@/Components/EditIcon.vue'
import DeleteIcon from '@/Components/DeleteIcon.vue'
import EditModal from '@/Components/EditModal.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import Pagination from '@/Components/Pagination.vue'

const editData = ref({})
const tableData = ref([])
const linksData = ref([]);
const deleteData = ref({})
const lastPage = ref(0)
const currentPage = ref(1)
const isEditModalOpen = ref(false)
const isDeleteModalOpen = ref(false)

const tab = inject('tab');
const user = inject('user');

const tableHeaders = [
    { key: 'site_id', label: 'Site ID' },
    { key: 'description', label: 'Site Name' },
    { key: 'active', label: 'Status' },
    { key: 'created_at', label: 'Created At' },
    { key: 'action', label: 'Action' }
];

onMounted(() => {
    getSiteList();
});

const getSiteList = (page = 1) => {
    const user_id = user.value.user_id
    const url = route(`${tab.value}.index`, { 'user_id': user_id, 'page': page });

    axios.get(url)
        .then(response => {
            const { data, links, current_page, last_page } = response.data;
            tableData.value = data
            linksData.value = links

            lastPage.value = last_page
            currentPage.value = current_page
        })
        .catch(error => {
            console.error(`Deleting ${tab.value}'s items:`, error);
        });
}

const confirmDelete = (item) => {
    isDeleteModalOpen.value = true;
    deleteData.value = {
        id: item.site_id,
    }
}

const confirmEdit = (item) => {
    isEditModalOpen.value = true;
    editData.value = {
        id: item.site_id,
    }
}

const closeEdit = () => {
    isEditModalOpen.value = false
    getSiteList()
}

const closeDelete = () => {
    isDeleteModalOpen.value = false
    getSiteList()
}

defineExpose({
    getSiteList
});
</script>

<template>
    <EditModal :show="isEditModalOpen" :data="editData" @close="closeEdit"></EditModal>

    <DeleteModal :show="isDeleteModalOpen" :data="deleteData" @close="closeDelete"></DeleteModal>

    <Table :headers="tableHeaders" :items="tableData">
        <template #cell-active="{ value }">
            <span :class="['border p-1 px-2 rounded-md text-white', value ? 'bg-green-400' : 'bg-red-400']">
                {{ value ? 'Active' : 'Inactive' }}
            </span>
        </template>

        <template #cell-created_at="{ value }">
            <span>
                {{ new Date(value).toISOString().split('T')[0] }}
            </span>
        </template>

        <template #cell-action="{ row }">
            <div class="flex gap-2 w-[100px]">
                <button>
                    <EditIcon @click="confirmEdit(row)"></EditIcon>
                </button>
                <button>
                    <DeleteIcon @click="confirmDelete(row)"></DeleteIcon>
                </button>
            </div>
        </template>
    </Table>

    <Pagination :links="linksData" :currentPage="currentPage" :lastPage="lastPage" @handleClick="getSiteList" />
</template>