<template>
    <div class="s-login">
        <SForm v-model="form" action="/login">
            <SFormRow title="Логин" name="username">
                <SInput />
            </SFormRow>
            <SFormRow title="Пароль" name="password">
                <SInput type="password" />
            </SFormRow>
            <SFormRow name="remember">
                <SSwitch>Запомнить меня</SSwitch>
            </SFormRow>
            <SFormRow>
                <SButton>Войти</SButton>
            </SFormRow>
        </SForm>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { SForm, SFormRow, SInput, SSwitch, SButton } from 'startup-ui';

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
</script>

<style lang="scss" scoped>
.s-login {
    max-width: 400px;
    margin: auto;

    & > .b-logo {
        margin: var(--s-base-margin) auto;
    }
}
</style>
