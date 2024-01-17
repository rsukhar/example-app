<template>
    <div class="b-login">
        <form class="b-form label_top" :class="{ loading: form.processing }" @submit.prevent="submit">
            <b-formrow title="Имя пользователя" :error="errors.username" titleWidth="320">
                <InputText type="text" v-model="form.username" />
            </b-formrow>

            <b-formrow title="Пароль" :error="errors.password" hint="От этого сервиса">
                <Password v-model="form.password" />
            </b-formrow>

            <b-formrow hint="Запомнить меня">
                <InputSwitch v-model="form.remember" />
            </b-formrow>

            <b-formrow>
                <Button label="Войти" type="submit" />
            </b-formrow>
        </form>
    </div>
</template>

<script setup>
import BFormrow from '../../blocks/BFormrow.vue';
import { useForm } from '@inertiajs/vue3';

import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import InputSwitch from 'primevue/inputswitch';
import Button from 'primevue/button';

const props = defineProps({
    errors: {
        type: Object,
        default() {
            return {};
        },
    }
});

const form = useForm({
    username: '',
    password: '',
    remember: false
});

function submit() {
    form.post('/login');
}
</script>

<style lang="scss">
.b-login {
    display: flex;
    flex-direction: column;
    max-width: 400px;
    margin: auto;

    & > .b-logo {
        margin: var(--base-margin) auto;
    }
}
</style>
