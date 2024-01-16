<template>
    <div class="b-login">
        <form class="b-form label_top" :class="{ loading: form.processing }" @submit.prevent="submit">
            <b-formrow title="Имя пользователя в Telegram" :error="errors.username">
                <el-input type="username" v-model="form.username" />
            </b-formrow>
            <b-formrow title="Пароль" :error="errors.password" hint="От этого сервиса, а не от телеги">
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

<script>
import BFormrow from "../../blocks/BFormrow.vue";
import { useForm } from "@inertiajs/vue3";
import { ElInput, ElSwitch } from "element-plus";

export default {
    components: { BFormrow, ElSwitch, ElInput },
    layout: false,
    props: {
        errors: {
            type: Object,
            default() {
                return {};
            },
        },
    },
    data() {
        return {
            form: useForm({
                username: "",
                password: "",
                remember: false
            }),
        };
    },
    methods: {
        submit() {
            this.form.post("/login");
        },
    },
};
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
