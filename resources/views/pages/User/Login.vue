<template>
    <div class="b-login">
        <form class="b-form label_top" :class="{ loading: form.processing }" @submit.prevent="submit">
            <b-formrow title="Имя пользователя" :error="errors.username">
                <el-input type="username" v-model="form.username" />
            </b-formrow>
            <b-formrow title="Пароль" :error="errors.password">
                <el-input type="password" v-model="form.password" />
            </b-formrow>
            <b-formrow>
                <el-switch v-model="form.remember" active-text="Запомнить меня" />
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
import { ElInput, ElSwitch } from "element-plus";

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
