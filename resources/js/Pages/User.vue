<script setup>
import { onMounted, ref, inject } from 'vue'

import Table from '@/Components/Table.vue'
import EditIcon from '@/Components/EditIcon.vue'
import DeleteIcon from '@/Components/DeleteIcon.vue'
import EditModal from '@/Components/EditModal.vue'
import DeleteModal from '@/Components/DeleteModal.vue'
import Pagination from '@/Components/Pagination.vue'

const editData = ref({});
const tableData = ref([]);
const linksData = ref([]);
const deleteData = ref({});
const lastPage = ref(0)
const currentPage = ref(1)
const isEditModalOpen = ref(false);
const isDeleteModalOpen = ref(false);

const tab = inject('tab');
const user = inject('user');

const tableHeaders = [
    { key: 'first_name', label: 'First Name' },
    { key: 'last_name', label: 'Last Name' },
    { key: 'email_address', label: 'Email Address' },
    { key: 'user_type', label: 'Type' },
    { key: 'action', label: 'Action' }
];

onMounted(() => {
    getUserList();
});

const getUserList = () => {
    const user_id = user.value.user_id
    const url = route(`${tab.value}.index`, { 'user_id': user_id });

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
        id: item.user_id,
    }
}

const confirmEdit = (item) => {
    isEditModalOpen.value = true;
    editData.value = {
        id: item.user_id,
    }
}
</script>

<template>
    <EditModal :show="isEditModalOpen" :data="editData" @close="isEditModalOpen = false"></EditModal>

    <DeleteModal :show="isDeleteModalOpen" :data="deleteData" @close="isDeleteModalOpen = false"></DeleteModal>

    <Table :headers="tableHeaders" :items="tableData">
        <template #cell-action="{ row }">
            <div class="flex gap-2">
                <button>
                    <EditIcon @click="confirmEdit(row)"></EditIcon>
                </button>
                <button>
                    <DeleteIcon @click="confirmDelete(row)"></DeleteIcon>
                </button>
            </div>
        </template>
    </Table>

    <Pagination :links="linksData" :currentPage="currentPage" :lastPage="lastPage" @handleClick="getUserList" />
</template>