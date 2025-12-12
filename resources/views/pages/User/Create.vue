<template>
    <Head>
        <title>Создание аккаунта</title>
    </Head>

    <div class="g-titlebar">
        <h1>Создание аккаунта</h1>
        <Link href="/users/" class="right">
            <FontAwesomeIcon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
    </div>

    <SForm v-model="form" action="/users" titles-at-left>
        <SFormRow title="Логин *" name="username">
            <SInput/>
        </SFormRow>
        <SFormRow title="Имя" name="name">
            <SInput/>
        </SFormRow>
        <SFormRow title="Email" name="email">
            <SInput type="email"/>
        </SFormRow>
        <SFormRow title="Пароль *" name="password">
            <SInput type="password"/>
        </SFormRow>
        <SFormRow title="Роль" name="role">
            <SRadioGroup :options="userRoles" buttons/>
        </SFormRow>
        <SFormRow>
            <SButton style="width:210px">Сохранить изменения</SButton>
        </SFormRow>
    </SForm>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { SForm, SFormRow, SInput, SButton, SRadioGroup } from "startup-ui";

const props = defineProps({
    userRoles: Object,
    errors: Object,
});

const form = useForm({
    username: '',
    first_name: '',
    email: '',
    password: '',
    role: Object.keys(props.userRoles)[0] ?? ''
});
</script>
