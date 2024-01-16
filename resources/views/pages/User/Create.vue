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
        <b-formrow title="Телега" :error="errors.username" hint="Имя пользователя без @">
            <el-input type="text" v-model="form.username" />
        </b-formrow>
        <b-formrow title="Email" :error="errors.email">
            <el-input type="text" v-model="form.email" />
        </b-formrow>
        <b-formrow title="Пароль" :error="errors.password">
            <el-input type="password" v-model="form.password" />
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
        <b-formrow title="Роль" :error="errors.role">
            <el-radio-group v-model="form.role">
                <el-radio-button v-for="(oTitle, oValue) in userRoles" :key="oValue" :label="oValue">
                    {{ oTitle }}
                </el-radio-button>
            </el-radio-group>
        </b-formrow>
        <b-formrow title="Кто пригласил" :error="errors.affiliate_id" v-if="!teamRoles.includes(form.role)">
            <el-select v-model="form.affiliate_id" filterable clearable>
                <el-option v-for="(affName, affId) in affiliates" :key="affId" :label="affName" :value="affId" />
            </el-select>
        </b-formrow>
        <b-formrow
            title="День рождения"
            class="type_date"
            :error="errors.birthday"
            v-if="teamRoles.includes(form.role)"
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
                   v-if="affiliateRoles.includes(form.role)">
            <el-input type="number" v-model="form['meta->affiliateBonus']" />
        </b-formrow>
        <b-formrow>
            <button class="g-button">Создать пользователя</button>
        </b-formrow>
    </form>
</template>

<script>
import { router } from "@inertiajs/vue3";
import BFormrow from "../../blocks/BFormrow.vue";
import { ElDatePicker, ElInput, ElRadioButton, ElRadioGroup, ElSelect, ElOption } from "element-plus";

export default {
    components: { ElDatePicker, ElRadioButton, ElRadioGroup, ElInput, BFormrow, ElSelect, ElOption },
    props: {
        userRoles: Object,
        teamRoles: Array,
        plans: Object,
        affiliateRoles: Array,
        affiliates: Object,
        errors: Object,
    },
    data() {
        return {
            form: {
                role: 'customer',
                plan: 'test'
            },
        };
    },
    methods: {
        submit() {
            router.post("/users", this.form);
        },
    },
};
</script>

<style scoped></style>
