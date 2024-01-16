<template>
    <Head title="Баланс" />
    <div class="g-titlebar">
        <h1>Баланс: {{ $filters.formatNumber(balance, 3) }} ₽</h1>
        <div class="g-topup">
            <div class="g-topup-label">Сумма пополнения:</div>
            <el-input v-model="topupAmount" />
            <a class="g-button" target="_blank" :href="`/robokassa/pay?user=${username}&amount=${topupAmount}`">Пополнить</a>
        </div>
    </div>
    <div class="g-alert" v-if="status" :class="{success: status === 'success', error: status === 'fail'}">
        <div v-if="status === 'success'">Баланс успешно пополнен</div>
        <div v-else>Ошибка при пополнении баланса. Пожалуйста, обратитесь к администратору</div>
    </div>
    <h2>Тарифные планы</h2>
    <div class="b-plans">
        <div class="b-plan" v-for="(plan, planName) in plans" :key="planName">
            <div class="b-plan-title">
                {{ plan.title }}
                <div class="g-status green" v-if="planName === activePlan">Активный</div>
            </div>
            <div class="b-plan-description">
                До {{ plan.rateLimit }} запросов / минуту
                <template v-if="plan.threadCount">
                    <br>Оптимально до {{ plan.threadCount }} потоков
                </template>
                <template v-if=" ! plan.duration">
                    <br>Без ограничения по времени
                </template>
            </div>
            <div class="b-plan-terms" v-if="plan.subscriptionFee">
                <div>{{ $filters.formatNumber(plan.subscriptionFee) }} ₽</div>
                <div>/ {{ plan.duration }} {{ $filters.ruPluralForm(plan.duration, ['день', 'дня', 'дней']) }}</div>
            </div>
            <div class="b-plan-terms" v-if="plan.apiRequestFee">
                <div>{{ $filters.formatNumber(plan.apiRequestFee, 3) }} ₽</div>
                <div>/ запрос</div>
            </div>
            <PlanUpgradeButton :username="username" :plans="plans" :cur-plan-name="activePlan"
                               :cur-plan-expires-at="planExpiresAt" :new-plan-name="planName" :now-time="nowTime"
                               :cur-balance="balance" />
        </div>
        <div class="b-plan">
            <div class="b-plan-title">Индивидуальный</div>
            <div class="b-plan-description">
                Нужно больше? Напишите — <br>предложим решение
            </div>
            <div class="b-plan-terms">
            </div>
            <a class="g-button outlined" href="https://t.me/ruslansuhar" target="_blank">Написать в Telegram</a>
        </div>
    </div>
    <div class="g-hwrapper">
        <h2>История операций</h2>
        <div class="g-button outlined" v-if="canEditAll" @click="showForm()">Добавить транзакцию</div>
    </div>
    <div class="g-tablewrapper">
        <el-table :data="transactions" table-layout="auto" class="g-table">
            <el-table-column label="Дата">
                <template #default="{ row }">
                    {{ $filters.toLocalTime(row.created_at, 'в') }}
                </template>
            </el-table-column>
            <el-table-column label="Сумма, ₽">
                <template #default="{ row }">
                    <template v-if="row.type === 'rate'">
                        {{ $filters.formatNumber(row.amount, 3) }}
                    </template>
                    <template v-else>
                        {{ $filters.formatNumber(row.amount) }}
                    </template>
                </template>
            </el-table-column>
            <el-table-column label="Операция">
                <template #default="{ row }">
                    <div class="g-status green" v-if="row.type === 'payment'" style="margin-right: 0.5rem;">Оплата</div>
                    {{ row.note }}
                </template>
            </el-table-column>
            <el-table-column align="right" v-if="canEditAll">
                <template #default="{ row }">
                    <a
                        @click="deleteTransaction(row.id, row.amount)"
                        title="Удалить"
                        class="g-actionicon"
                    >
                        <font-awesome-icon icon="trash" />
                    </a>
                    <a class="g-actionicon" @click="showForm(row.id)">
                        <font-awesome-icon icon="pen-to-square" />
                    </a>
                </template>
            </el-table-column>
        </el-table>
    </div>
    <el-dialog v-model="formIsShown" width="700px" :title="formTitle">
        <div class="b-form">
            <b-formrow title="Дата и время" :error="errors.created_at" titleWidth="150">
                <el-input v-model="formValues.created_at" />
            </b-formrow>
            <b-formrow title="Сумма" :error="errors.amount" titleWidth="150">
                <el-input type="number" v-model="formValues.amount" />
            </b-formrow>
            <b-formrow title="Тип" :error="errors.type" titleWidth="150">
                <el-radio-group v-model="formValues.type">
                    <el-radio-button label="manual">Ручная транзакция</el-radio-button>
                    <el-radio-button label="payment">Получена оплата</el-radio-button>
                    <el-radio-button label="rate">Списание за услуги</el-radio-button>
                </el-radio-group>
            </b-formrow>
            <b-formrow title="Примечание" :error="errors.note" titleWidth="150">
                <el-input type="textarea" autosize v-model="formValues.note" />
            </b-formrow>
            <div class="g-button" @click="submitForm">{{ formButtonTitle }}</div>
        </div>
    </el-dialog>
</template>

<script>
import { ElTable, ElTableColumn, ElDialog, ElInput, ElMessageBox, ElRadioButton, ElRadioGroup } from "element-plus";
import BFormrow from "../../../blocks/BFormrow.vue";
import { router } from "@inertiajs/vue3";
import PlanUpgradeButton from "./PlanUpgradeButton.vue";

export default {
    name: "Index",
    components: { ElRadioButton, ElRadioGroup, ElInput, ElDialog, BFormrow, ElTableColumn, ElTable, PlanUpgradeButton },
    props: {
        username: String,
        balance: String,
        transactions: Array,
        canEditAll: Boolean,
        nowTime: String,
        plans: Object,
        activePlan: String,
        planExpiresAt: String,
        errors: Object,
        status: String,
        defaultTopupAmount: Number,
    },
    data() {
        return {
            formValues: this.getStaringValues(),
            // Сумма пополнения
            topupAmount: this.defaultTopupAmount,
            // Показана ли форма добавления-редактирования?
            formIsShown: false,
        }
    },
    computed: {
        formTitle() {
            return this.formValues.id ? 'Редактирование транзакции' : 'Добавление транзакции';
        },
        formButtonTitle() {
            return this.formValues.id ? 'Сохранить изменения' : 'Добавить';
        }
    },
    methods: {
        showForm(id) {
            let values = this.getStaringValues();
            if (id !== undefined) {
                for (let transaction of this.transactions) {
                    if (transaction.id === id) {
                        values = { ...transaction };
                        break;
                    }
                }
            }
            this.formValues = values;
            this.formIsShown = true;
        },
        getStaringValues() {
            return {
                created_at: this.nowTime,
                amount: 0,
                type: 'manual',
                note: ''
            };
        },
        submitForm() {
            if (this.formValues.id) {
                // Редактирование существующей транзакции
                router.put(`/transactions/${this.formValues.id}`, { ...this.formValues }, {
                    onSuccess: page => {
                        this.formIsShown = false;
                    }
                });
            } else {
                // Добавление новой транзакции
                router.post(`/users/${this.username}/transactions`, { ...this.formValues }, {
                    onSuccess: page => {
                        this.formIsShown = false;
                    }
                });
            }
        },
        deleteTransaction(id, amount) {
            ElMessageBox.confirm(
                `Вы действительно хотите удалить транзакцию на ${amount} руб?`,
                'Подтверждение удаления', {
                    confirmButtonText: 'Да',
                    cancelButtonText: 'Нет',
                    confirmButtonClass: 'g-button',
                    cancelButtonClass: 'g-button outlined'
                }
            ).then(() => {
                router.delete(`/transactions/${id}`);
            }).catch(() => {
            });
        }
    }
}
</script>

<style lang="scss">
.b-plans {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    margin-bottom: 4rem;
}

</style>