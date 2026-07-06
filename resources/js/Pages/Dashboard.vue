<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref, computed, provide } from 'vue';

import User from './User.vue';
import Site from './Site.vue';
import Equipment from './Equipment.vue';

import AddModal from '@/Components/AddModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const { props } = usePage();



const sitePage = ref({})
const equipmentPage = ref({})

const user = ref({})
const tab = ref(null)
const sites = ref([])
const addData = ref({})
const userType = ref(null)
const isAddModalOpen = ref(false)

const tabs = [
    {
        key: "user",
        label: "User"
    },
    {
        key: "site",
        label: "Site",
    },
    {
        key: "equipment",
        label: "Equipment",
    },
];

onMounted(() => {
    user.value = props.auth.user
    userType.value = user.value.user_type;

    if (userType.value === 'Admin') tab.value = 'site';
    if (userType.value !== 'Admin') tab.value = 'user';

    getSiteList();
})

const filterTabs = computed(() => {
    if (userType.value === 'Admin') tabs.shift();
    return tabs;
});

const getSiteList = (page = 1) => {
    const user_id = user.value.user_id
    const url = route(`site.index`, { 'user_id': user_id, 'page': page });

    axios.get(url)
        .then(response => {
            const { data } = response.data;
            sites.value = data
        })
        .catch(error => {
            console.error(`Deleting ${tab.value}'s items:`, error);
        });
}

const selectItem = (id) => {
    const item = tabs[id];
    tab.value = item.key

    if (tab.value === 'equipment') {
        getSiteList();
    }
};

const confirmAdd = () => {
    if (sites.value.length == 0 && tab.value == 'equipment') {
        alert("A site must be added first before adding equipment.");
        return
    }

    isAddModalOpen.value = true;
}

const closeAdd = () => {
    isAddModalOpen.value = false

    if (tab.value === 'site') {
        sitePage.value.getSiteList()
    }

    if (tab.value === 'equipment') {
        equipmentPage.value.getEquipmentList()
    }
}

provide('tab', tab)
provide('user', user)
</script>

<template>
    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl p-4 sm:p-8">
            <div className="p-8 bg-white shadow-sm rounded-lg">
                <AddModal :show="isAddModalOpen" :data="addData" @close="closeAdd"></AddModal>

                <div class="flex items-center justify-between">
                    <!-- Tabs -->
                    <ul role="tablist" aria-label="Dashboard sections"
                        class="inline-flex flex-wrap font-medium text-sm text-slate-600 rounded-md border border-slate-300 divide-x divide-slate-300">
                        <li v-for="(filterTab, index) in filterTabs" :key="index">
                            <button :class="`tab py-2 px-3.5 cursor-pointer transition-colors hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 
                                ${index === 0 ? 'rounded-l-[5px]' : index === tabs.length - 1 ? 'rounded-r-[5px]' : ''}
                                ${tab === filterTab.key ? 'text-slate-900 bg-slate-100' : ''}`"
                                @click="selectItem(index)">

                                {{ filterTab.label }}
                            </button>
                        </li>
                    </ul>

                    <SecondaryButton @click="confirmAdd()" v-if="tab !== 'user'">
                        Add Record
                    </SecondaryButton>
                </div>

                <div class="mt-6">
                    <User ref="userPage" v-if="tab === 'user'"></User>

                    <Site ref="sitePage" v-if="tab === 'site'"></Site>

                    <Equipment ref="equipmentPage" v-if="tab === 'equipment'"></Equipment>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
