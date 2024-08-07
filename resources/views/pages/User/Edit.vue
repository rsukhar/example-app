<template>
    <Head>
        <title>Редактирование аккаунта</title>
    </Head>

    <div class="g-titlebar">
        <h1>Редактирование аккаунта</h1>
        <Link href="/users/" class="right" v-if="canEditAll">
            <font-awesome-icon icon="rotate-left" />
            Вернуться к списку пользователей
        </Link>
        <Link :href="`/users/${values.username}`" class="right" v-else>
            <font-awesome-icon icon="rotate-left" />
            Вернуться к профилю
        </Link>
    </div>

    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit">
        <b-formrow title="Логин" :error="errors.username" v-if="canEditAll">
            <el-input type="text" v-model="form.username" />
        </b-formrow>
        <b-formrow title="Имя для отображения" :error="errors.first_name">
            <el-input type="text" v-model="form.first_name" />
        </b-formrow>
        <b-formrow title="Email" :error="errors.email">
            <el-input type="email" v-model="form.email" />
        </b-formrow>
        <b-formrow title="Пароль" hint="Введите для изменения пароля" :error="errors.password">
            <el-input type="password" v-model="form.password" />
        </b-formrow>
        <b-formrow class="type_switch" :error="errors.is_blocked" v-if="canEditAll">
            <el-switch v-model="form.is_blocked" active-text="Заблокирован" />
        </b-formrow>
        <b-formrow title="Роль" :error="errors.role" v-if="canEditAll">
            <el-radio-group v-model="form.role">
                <el-radio-button v-for="(oTitle, oValue) in userRoles" :key="oValue" :label="oTitle" :value="oValue" />
            </el-radio-group>
        </b-formrow>
        <b-formrow>
            <button class="g-button">Сохранить изменения</button>
        </b-formrow>
    </form>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import BFormrow from "../../blocks/BFormrow.vue";
import { ElDatePicker, ElInput, ElRadioButton, ElRadioGroup, ElSelect, ElOption, ElSwitch } from "element-plus";

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

function submit() {
    form.submit('put', `/users/${props.values.username}`);
}
</script>
