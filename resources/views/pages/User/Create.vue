<template>
    <Head>
        <title>Создание аккаунта</title>
    </Head>

    <div class="g-titlebar">
        <h1>Создание аккаунта</h1>
        <Link href="/users/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
    </div>

    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit">
        <b-formrow title="Логин *" :error="errors.username">
            <InputText type="text" v-model="form.username" />
        </b-formrow>

        <b-formrow title="Имя" :error="errors.first_name">
            <InputText type="text" v-model="form.first_name" />
        </b-formrow>

        <b-formrow title="Email" :error="errors.email">
            <InputText type="email" v-model="form.email" />
        </b-formrow>

        <b-formrow title="Пароль *" :error="errors.password">
            <InputText type="password" v-model="form.password" />
        </b-formrow>

        <b-formrow title="Роль" :error="errors.role">
            <RadioButtonGroup v-model="form.role">
                <div class="g-hwrapper" v-for="(oTitle, oValue) in userRoles" :key="oValue">
                    <RadioButton :input-id="`input-${oValue}`" :value="oValue"/>
                    <label :for="`input-${oValue}`">{{ oTitle }}</label>
                </div>
            </RadioButtonGroup>>
        </b-formrow>

        <b-formrow>
            <button class="g-button">Создать пользователя</button>
        </b-formrow>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BFormrow from "../../blocks/BFormrow.vue";

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

function submit() {
    form.submit('post', '/users');
}
</script>
