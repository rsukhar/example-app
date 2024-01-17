<template>
    <Head title="Пользователи" />

    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined">Создать пользователя</Link>
    </div>

    <b-filter
        v-model="filter"
        :fields="{
            role: {
                type: 'radio',
                options: userRoles
            },
            q: {
                type: 'text',
                placeholder: 'Поиск по имени пользователя или телефону',
                'titleWidth': '320'
            }
        }"
    />

    <DataTable :value="users.data" tableStyle="min-width: 50rem" :class="{ loading: filter.loading }">
        <Column header="Id">
            <template #body="slotProps">
                {{ slotProps.data.id }}
            </template>
        </Column>

        <Column header="Зарегистрирован">
            <template #body="slotProps">
                {{ $filters.toLocalTime(slotProps.data.created_at, 'в') }}
            </template>
        </Column>

        <Column header="Логин">
            <template #body="slotProps">
                <Link :href="`/users/${slotProps.data.username}/`">{{ slotProps.data.username }}</Link>
                <Tag severity="danger" value="Заблокирован" class="ml-4" v-if="slotProps.data.is_blocked"></Tag>
            </template>
        </Column>

        <Column header="Email">
            <template #body="slotProps">
                {{ slotProps.data.email ?? '-' }}
            </template>
        </Column>

        <Column header="Роль">
            <template #body="slotProps">
                {{ slotProps.data.role_name }}
            </template>
        </Column>

        <Column header="">
            <template #body="slotProps">
                <div class="table-right">
                    <a
                        @click="deleteUser(slotProps.data.username)"
                        title="Удалить"
                        class="g-actionicon"
                        v-if="slotProps.data.role !== 'admin'"
                    >
                        <i class="pi pi-trash"></i>
                    </a>
                    <Link :href="`/users/${slotProps.data.username}/edit/`" class="g-actionicon">
                        <i class="pi pi-user-edit"></i>
                    </Link>
                </div>
            </template>
        </Column>
    </DataTable>

    <b-pagination :links="users.links" :total="users.total" />

    <ConfirmDialog id="confirm" />
</template>

<script setup>
import { reactive, ref, inject } from 'vue';
import { router } from "@inertiajs/vue3";
import BFilter from "../../blocks/BFilter.vue";
import BPagination from "../../blocks/BPagination.vue";

import Tag from 'primevue/tag';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";

const confirm = useConfirm();
import { useToast } from "primevue/usetoast";

const toast = useToast();

const props = defineProps({
    users: Array,
    userRoles: Array,
    initialFilter: {
        type: Object,
        default() {
            return {};
        },
    },
});

const filter = reactive({ ...props.initialFilter });
const $filters = inject('filters');

function deleteUser(username) {
    confirm.require({
        message: `Вы действительно хотите удалить пользователя «${username}»?`,
        header: 'Подтверждение удаления',
        accept: () => {
            router.delete(`/users/${username}`, {
                onError: error => {
                    toast.add({
                        severity: 'error',
                        summary: 'Ошибка',
                        detail: error.message,
                        life: 3000
                    });
                }
            });
        }
    });
}
</script>

<style lang="scss">
.table-right {
    display: flex;
    align-items: center;
    justify-content: end;
}
</style>