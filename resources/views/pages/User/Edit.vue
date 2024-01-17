<template>
    <Head>
        <title>Редактирование аккаунта</title>
    </Head>

    <div class="g-titlebar">
        <h1>Редактирование аккаунта</h1>

        <Link href="/users/" class="right">
            <i class="pi pi-replay"></i>
            Вернуться к списку пользователей
        </Link>
    </div>

    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit" style="width: 800px;">
        <b-formrow title="Логин" :error="errors.username" hint="Имя пользователя без @">
            <InputText type="text" v-model="form.username" />
        </b-formrow>

        <b-formrow title="Email" :error="errors.email">
            <InputText type="text" v-model="form.email" />
        </b-formrow>

        <b-formrow title="Имя" :error="errors.name">
            <InputText type="text" v-model="form.name" />
        </b-formrow>

        <b-formrow title="День рождения" :error="errors.birthday">
            <Calendar v-model="form.birthday" dateFormat="dd.mm.yy" />
        </b-formrow>

        <b-formrow title="Роль" :error="errors.role">
            <SelectButton v-model="form.role" :options="userRoles.slugs" aria-labelledby="custom">
                <template #option="slotProps">
                    {{ userRoles.values[slotProps.option] }}
                </template>
            </SelectButton>
        </b-formrow>

        <b-formrow title="Пароль" :error="errors.password">
            <Password v-model="form.password" />
        </b-formrow>

        <b-formrow title="Заблокирован" :error="errors.is_blocked">
            <InputSwitch v-model="form.is_blocked" />
        </b-formrow>

        <b-formrow>
            <Button label="Сохранить изменения" type="submit" />
        </b-formrow>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BFormrow from '../../blocks/BFormrow.vue';

import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import InputSwitch from 'primevue/inputswitch';
import Button from 'primevue/button';
import SelectButton from 'primevue/selectbutton';
import Calendar from 'primevue/calendar';
import dayjs from "dayjs";

const props = defineProps({
    user: Object,
    userRoles: Array,
    errors: Object,
});

const form = useForm({
    ...props.user
});

function submit() {
    form.transform(data => {
        data.birthday = dayjs(data.birthday, 'YYYY-MM-DD HH:mm:ss Z').format('DD.MM.YYYY');
        return data;
    }).put(`/users/${props.user.username}`);
}
</script>
