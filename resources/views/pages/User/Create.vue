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
            <el-input type="text" v-model="form.username" />
        </b-formrow>

        <b-formrow title="Имя" :error="errors.first_name">
            <el-input type="text" v-model="form.first_name" />
        </b-formrow>

        <b-formrow title="Email" :error="errors.email">
            <el-input type="email" v-model="form.email" />
        </b-formrow>

        <b-formrow title="Пароль *" :error="errors.password">
            <el-input type="password" v-model="form.password" />
        </b-formrow>

        <b-formrow title="Роль" :error="errors.role">
            <el-radio-group v-model="form.role">
                <el-radio-button v-for="(oTitle, oValue) in userRoles" :key="oValue" :label="oTitle" :value="oValue" />
            </el-radio-group>
        </b-formrow>

        <b-formrow>
            <button class="g-button">Создать пользователя</button>
        </b-formrow>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BFormrow from "../../blocks/BFormrow.vue";
import { ElDatePicker, ElInput, ElRadioButton, ElRadioGroup, ElSelect, ElOption } from "element-plus";

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
