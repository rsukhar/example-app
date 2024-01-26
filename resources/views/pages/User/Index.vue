<template>
    <Head title="Пользователи" />

    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/">
            <Button label="Создать пользователя" />
        </Link>
    </div>

    <div class="b-filter">
        <div class="b-filter-field">
            <SelectButton
                :options="userRoles.slugs"
                aria-labelledby="basic"
                v-model="filter.role"
                @update:modelValue="(newValue) => setFilterValue('role', newValue)"
            >
                <template #option="slotProps">
                    {{ userRoles.values[slotProps.option] }}
                </template>
            </SelectButton>
        </div>
        <div class="b-filter-field type_text">
            <InputText
                type="text"
                size="large"
                placeholder="Поиск по имени пользователя или телефону"
                v-model="filter.q"
                @update:modelValue="(newValue) => setFilterValue('q', newValue, true)"
            />
        </div>
    </div>

    <DataTable :value="users.data" tableStyle="min-width: 50rem" :class="{ loading: loading }">
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
                        href="#"
                        @click="deleteUser(slotProps.data.username)"
                        title="Удалить"
                        v-if="slotProps.data.role !== 'admin'"
                        class="mr-4"
                    >
                        <i class="pi pi-trash"></i>
                    </a>
                    <Link :href="`/users/${slotProps.data.username}/edit/`">
                        <i class="pi pi-pencil"></i>
                    </Link>
                </div>
            </template>
        </Column>
    </DataTable>

    <Paginator
        :rows="5"
        :totalRecords="users.total"
        @page="(event) => changePage(event.page+1)"
    ></Paginator>

    <ConfirmDialog id="confirm" />
</template>

<script setup>
import { reactive, ref, inject } from 'vue';
import { router } from "@inertiajs/vue3";
import debounce from "lodash/debounce.js";

import SelectButton from "primevue/selectbutton";
import InputText from "primevue/inputtext";
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ConfirmDialog from 'primevue/confirmdialog';
import Paginator from 'primevue/paginator';

import { useConfirm } from "primevue/useconfirm";
const confirm = useConfirm();

import { useToast } from "primevue/usetoast";
const toast = useToast();

const $filters = inject('filters');
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

const url = location.pathname;
const filter = reactive({
    'role': props.initialFilter.role ?? '',
    ...props.initialFilter
});
const loading = ref(false);

function deleteUser(username) {
    confirm.require({
        message: `Вы действительно хотите удалить пользователя «${username}»?`,
        header: 'Подтверждение удаления',
        rejectLabel: 'Нет',
        acceptLabel: 'Да',
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

function setFilterValue(fieldName, newValue, delay = false) {
    filter[fieldName] = newValue;

    if (delay) {
        updateDebounced(this);
    } else {
        updatePage();
    }
}

const updateDebounced = debounce((that) => {
    that.updatePage();
}, 500);

function updatePage() {
    loading.value = true;

    const query = getQueryParams(filter);

    router.get(url, query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onFinish: function() {
            loading.value = false;
        },
    });
}

const currentPage = ref(1);
function changePage(page = 1) {
    if(page !== currentPage.value) {
        const query = getQueryParams(filter, {
            'page': page
        });

        router.get(url, query,{
            preserveState: true,
            preserveScroll: true,
            replace: true,
        });

        currentPage.value = page;
    }
}

function getQueryParams(filter, additional = null) {
    const query = {
        ...additional
    };
    Object.keys(filter).forEach((key) => {
        if (filter[key]) {
            query[key] = filter[key];
        }
    });

    return query;
}
</script>

<style lang="scss">
.b-filter {
    display: flex;
    margin-bottom: var(--base-margin);
    align-items: start;

    &-field {
        &.type_text {
            flex-grow: 1;
            max-width: 420px;

            input {
                width: 100%;
            }
        }

        & + & {
            margin-left: var(--base-margin);
        }
    }
}

.p-datatable {
    .p-datatable-thead > tr > th {
        background: #fff !important;
    }

    tr > td .table-right {
        display: flex;
        align-items: center;
        justify-content: end;
    }
}
</style>