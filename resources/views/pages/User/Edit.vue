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
        <b-formrow title="Телега" :error="errors.username" hint="Имя пользователя без @" v-if="canEditAll">
            <el-input type="text" v-model="form.username" />
        </b-formrow>
        <b-formrow title="Email" :error="errors.email">
            <el-input type="text" v-model="form.email" />
        </b-formrow>
        <b-formrow title="Пароль" hint="Введите для изменения пароля" :error="errors.password">
            <el-input type="password" v-model="form.password" />
        </b-formrow>
        <b-formrow class="type_switch" :error="errors.is_blocked" v-if="canEditAll">
            <el-switch v-model="form.is_blocked" active-text="Заблокирован" />
        </b-formrow>
        <b-formrow title="Тарифный план" :error="errors.plan">
            <el-select v-model="form.plan" filterable clearable>
                <el-option v-for="(planTitle, planName) in plans" :key="planName" :label="planTitle"
                           :value="planName" />
            </el-select>
        </b-formrow>
        <b-formrow title="Тариф действует до" hint="По GMT-времени" :error="errors.plan_expires_at"
                   v-if="form.plan !== 'test'">
            <el-date-picker v-model="form.plan_expires_at" type="datetime" value-format="YYYY-MM-DD HH:mm:ss" />
        </b-formrow>
        <b-formrow title="Роль" :error="errors.role" v-if="canEditAll">
            <el-radio-group v-model="form.role">
                <el-radio-button v-for="(oTitle, oValue) in userRoles" :key="oValue" :label="oValue">
                    {{ oTitle }}
                </el-radio-button>
            </el-radio-group>
        </b-formrow>
        <b-formrow title="Кто пригласил" :error="errors.affiliate_id"
                   v-if="canEditAll && !teamRoles.includes(form.role)">
            <el-select v-model="form.affiliate_id" filterable clearable>
                <el-option v-for="(affName, affId) in affiliates" :key="affId" :label="affName" :value="affId" />
            </el-select>
        </b-formrow>
        <b-formrow
            title="День рождения"
            class="type_date"
            :error="errors.birthday"
            v-if="canEditAll && teamRoles.includes(form.role)"
        >
            <el-date-picker v-model="form.birthday" type="date" />
        </b-formrow>
        <b-formrow title="Код для приглашения"
                   hint="Этот код можно дать новым пользователя для возможности регистрации в системе"
                   :error="errors['meta->affiliateCode']"
                   v-if="affiliateRoles.includes(form.role)">
            <el-input type="text" v-model="form['meta->affiliateCode']" />
        </b-formrow>
        <b-formrow title="Бонус для рефералов"
                   hint="Какая сумма будет на счете у приглашенных пользователей"
                   :error="errors['meta->affiliateBonus']"
                   v-if="canEditAll && affiliateRoles.includes(form.role)">
            <el-input type="number" v-model="form['meta->affiliateBonus']" />
        </b-formrow>
        <b-formrow>
            <button class="g-button">Сохранить изменения</button>
        </b-formrow>
    </form>
</template>

<script>
import { router } from "@inertiajs/vue3";
import BFormrow from "../../blocks/BFormrow.vue";
import { ElDatePicker, ElInput, ElRadioButton, ElRadioGroup, ElSelect, ElOption, ElSwitch } from "element-plus";

export default {
    components: { ElDatePicker, ElRadioButton, ElRadioGroup, ElInput, BFormrow, ElSelect, ElOption, ElSwitch },
    props: {
        id: Number,
        // Это первоначальные значения. Текущие — в this.form
        values: Object,
        canEditAll: Boolean,
        userRoles: Object,
        teamRoles: Array,
        affiliateRoles: Array,
        affiliates: Object,
        errors: Object,
        // Тарифные планы: name => title
        plans: Object,
    },
    data() {
        return {
            form: {
                ...this.values,
                // Преобразуем int-ключ строку, чтобы select-контрол правильно работал с точным сравнением
                affiliate_id: this.values.affiliate_id ? this.values.affiliate_id + "" : null,
            },
        };
    },
    methods: {
        submit() {
            router.put(`/users/${this.values.username}`, this.form);
        },
    },
};
</script>

<style scoped></style>
