<template>
    <Head title="Пользователи" />
    <confirm-dialog></confirm-dialog>
    <div class="g-titlebar">
        <h1>Пользователи</h1>
        <Link href="/users/create/" class="g-button outlined">Создать пользователя</Link>
    </div>

    <b-filter
        v-model="filter"
        :fields="{
            role: {
                type: 'radio',
                options: {
                    '': 'Все',
                    ...userRoles
                }
            },

            q: {
                type: 'text',
                placeholder: 'Поиск по имени пользователя',
                style: 'width: 100%; max-width: 350px;'
            }
        }"
    />

    <div class="g-tablewrapper" v-if="Object.keys(users.data).length || filter.length">
        <table class="g-table">
            <thead>
            <tr>
                <td>Пользователь</td>
                <td>Email</td>
                <td>Имя</td>
                <td>Регистрация</td>
                <td></td>
            </tr>
            </thead>

            <tbody>
            <tr v-for="user in users.data" :key="user.id">
                <td>
                    <Link :href="`/users/${user.username}/`">{{ user.username }}</Link>
                    <div class="g-status red" v-if="user.is_blocked" style="margin-left: 1rem">Заблокирован</div>
                </td>
                <td>{{ user.email }}</td>
                <td>{{ user.first_name }}</td>
                <td>{{ toLocalTime(user.created_at).split(' ')[0] }}</td>
                <td>
                    <a @click="deleteUser(user.username)" title="Удалить" class="g-actionicon"
                       v-if="user.role !== 'admin'">
                        <font-awesome-icon icon="trash" />
                    </a>
                    <Link :href="`/users/${user.username}/edit/`" class="g-actionicon">
                        <font-awesome-icon icon="pen-to-square" />
                    </Link>
                </td>
            </tr>
            </tbody>

        </table>

    </div>

    <b-pagination :links="users.links" :per-page="users.per_page"  :total="users.total" :from="users.from"
                  :to="users.to" />
</template>

<script setup>
import { reactive } from 'vue';
import { router } from '@inertiajs/vue3';
import BPagination from "../../blocks/BPagination.vue";
import { toLocalTime } from "../../../js/helpers.js";
import { useConfirm } from "primevue";
import BFilter from "../../blocks/BFilter.vue";


const props = defineProps({
    users: Object,
    userRoles: Object,
    initialFilter: {
        type: Object,
        default() {
            return {};
        },
    },
});

const filter = reactive({
    ...props.initialFilter
});

const confirm = useConfirm();

function deleteUser(username) {

    confirm.require({
        message: `Вы действительно хотите удалить пользователя «${username}»?`,
        header: 'Подтверждение удаления',
        rejectProps: {
            label: 'Нет',
            severity: 'secondary1',
            outlined: true,
            classList: 'g-button outlined'
            //  class: 'g-button outlined',
        },
        acceptProps: {
            label: 'Да',
            classList: 'g-button'
        },
        accept: () => {
            router.delete(`/users/${username}`);
        },
        reject: () => {
        }
    });
}
</script>

<style scoped>
</style>