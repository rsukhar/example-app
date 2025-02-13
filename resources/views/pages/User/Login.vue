<template>
    <div class="b-login">
        <form class="b-form label_top" :class="{ loading: form.processing }" @submit.prevent="submit">
            <b-formrow title="Имя пользователя" :error="errors.username">
                <InputText type="username" v-model="form.username" />
            </b-formrow>
            <b-formrow title="Пароль" :error="errors.password">
                <InputText type="password" v-model="form.password" />
            </b-formrow>
            <b-formrow>
                <div class="g-hwrapper">
                    <ToggleSwitch v-model="form.remember" input-id="switch1" />
                    <label for="switch1">Запомнить меня</label>
                </div>
            </b-formrow>
            <b-formrow>
                <button class="g-button">Войти</button>
            </b-formrow>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BFormrow from "../../blocks/BFormrow.vue";

const props = defineProps({
    errors: {
        type: Object,
        default() {
            return {};
        },
    },
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login');
}
</script>

<style lang="scss" scoped>
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
