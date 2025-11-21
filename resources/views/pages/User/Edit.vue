<template>
    <Head>
        <title>Редактирование аккаунта</title>
    </Head>

    <div class="g-titlebar">
        <h1>Редактирование аккаунта</h1>
        <Link href="/users/" class="right" v-if="canEditAll">
            <FontAwesomeIcon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
        <Link :href="`/users/${values.username}`" class="right" v-else>
            <FontAwesomeIcon icon="rotate-left" />
            Вернуться к профилю
        </Link>
    </div>

    <SForm v-form="form" :action="`/users/${props.values.username}`" titles-at-left>
        <SFormRow title="Логин *" name="username">
            <SInput />
        </SFormRow>
        <SFormRow title="Имя для отображения" name="first_name">
            <SInput/>
        </SFormRow>
        <SFormRow title="Email" name="email">
            <SInput type="email"/>
        </SFormRow>
        <SFormRow title="Пароль *" name="password" hint="Введите для изменения пароля">
            <SInput type="password"/>
        </SFormRow>
        <SFormRow name="is_blocked" v-if="canEditAll">
            <SSwitch>Заблокирован</SSwitch>
        </SFormRow>
        <SFormRow title="Роль" name="role" v-if="canEditAll">
            <SRadioGroup :options="userRoles" buttons/>
        </SFormRow>
        <SFormRow>
            <SButton style="width:210px">Сохранить изменения</SButton>
        </SFormRow>
    </SForm>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { SForm, SFormRow, SInput, SSwitch, SButton, SRadioGroup } from "startup-ui";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    id: Number,
    // Это первоначальные значения. Текущие — в this.form
    values: Object,
    canEditAll: Boolean,
    userRoles: Object,
    errors: Object,
});

const form = useForm({
    ...props.values,
    // Пустое значение пароля
    password: ''
});
</script>
