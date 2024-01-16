<template>
    <template v-if="curPlanName === 'test' && newPlanName === 'test'">
        <!-- Тестовый -> Тестовый -->
        <div class="g-button disabled">Текущий тариф</div>
    </template>
    <template v-else-if="curPlanName === 'test' && newPlanName !== 'test'">
        <!-- Тестовый -> Безлимит -->
        <div class="g-button" @click="popupIsShown = true">Подключить</div>
    </template>
    <template v-else-if="newPlanName === 'test'">
        <!-- Безлимит -> Тестовый  -->
        <div class="g-button outlined" @click="popupIsShown = true">Отключить безлимит</div>
    </template>
    <template v-else-if="curPlanName === newPlanName && curPlanName !== 'test'">
        <!-- Продление безлимита  -->
        <div class="g-button" @click="popupIsShown = true">
            Продлить на {{ curPlan.duration }} дней
        </div>
    </template>
    <template v-else-if="curPlan.subscriptionFee <= newPlan.subscriptionFee">
        <!-- Повышение безлимита  -->
        <div class="g-button outlined" @click="popupIsShown = true">Повысить за {{ $filters.formatNumber(upgradeFee) }} ₽</div>
    </template>
    <template v-else-if="curPlan.subscriptionFee > newPlan.subscriptionFee">
        <!-- Понижение безлимита  -->
        <div class="g-button outlined" @click="popupIsShown = true">Понизить тариф</div>
    </template>
    <el-dialog v-model="popupIsShown" :title="popupTitle" width="400px">
        <template v-if="newPlanName === 'test'">
            <!-- Безлимит -> Тестовый  -->
            <div class="g-stat">
                <div>Новый тариф</div>
                <div>{{ newPlan.title }}</div>
            </div>
            <p>Переключится автоматически после завершения безлимита</p>
        </template>
        <template v-else>
            <template v-if="curPlanName === 'test' && newPlanName !== curPlanName">
                <!-- Тестовый -> Безлимит  -->
                <div class="g-stat">
                    <div>Новый тариф</div>
                    <div>{{ newPlan.title }}</div>
                </div>
                <div class="g-stat">
                    <div>Будет активен до</div>
                    <div>{{ $filters.toLocalTime(newPlanExpiresAt) }}</div>
                </div>
                <div class="g-stat">
                    <div>С баланса спишется</div>
                    <div>{{ $filters.formatNumber(upgradeFee) }} ₽</div>
                </div>
            </template>
            <template v-else-if="curPlanName === newPlanName && curPlanName !== 'test'">
                <!-- Продление безлимита  -->
                <div class="g-stat">
                    <div>Текущий тариф</div>
                    <div>{{ curPlan.title }}</div>
                </div>
                <div class="g-stat">
                    <div>Будет продлен до</div>
                    <div>{{ $filters.toLocalTime(newPlanExpiresAt) }}</div>
                </div>
                <div class="g-stat">
                    <div>С баланса спишется</div>
                    <div>{{ $filters.formatNumber(upgradeFee) }} ₽</div>
                </div>
            </template>
            <template v-else-if="curPlan.subscriptionFee <= newPlan.subscriptionFee">
                <!-- Повышение безлимита  -->
                <div class="g-stat">
                    <div>Новый тариф</div>
                    <div>{{ newPlan.title }}</div>
                </div>
                <div class="g-stat">
                    <div>Будет активен до</div>
                    <div>{{ $filters.toLocalTime(curPlanExpiresAt) }}</div>
                </div>
                <div class="g-stat">
                    <div>С баланса спишется</div>
                    <div>{{ $filters.formatNumber(upgradeFee) }} ₽</div>
                </div>
            </template>
            <template v-else-if="curPlan.subscriptionFee > newPlan.subscriptionFee">
                <!-- Понижение безлимита  -->
                <div class="g-stat">
                    <div>Новый тариф</div>
                    <div>{{ newPlan.title }}</div>
                </div>
                <div class="g-stat">
                    <div>Будет активен до</div>
                    <div>{{ $filters.toLocalTime(curPlanExpiresAt) }}</div>
                </div>
            </template>
            <div class="g-button disabled" v-if="parseFloat(upgradeFee) > parseFloat(curBalance)">
                Не хватает средств. Пополните баланс
            </div>
            <div class="g-button" :class="{loading: isApplyingTransaction}" @click="applyTransaction" v-else>
                {{ isApplyingTransaction ? 'Применяется ...' : 'Применить' }}
            </div>
        </template>
    </el-dialog>
</template>

<script>

import { ElDialog, ElMessage } from "element-plus";
import dayjs from "dayjs";
import { router } from "@inertiajs/vue3";

export default {
    name: "PlanUpgradeButton",
    components: { ElDialog },
    props: {
        // Имя пользователя, для которого выполняется оплата
        username: String,
        // Логика тарифных планов
        plans: Object,
        // Текущий активный тариф
        curPlanName: String,
        // Дата истечение текущего тарифа
        curPlanExpiresAt: String,
        // Новый тариф
        newPlanName: String,
        // Текущее время на сервере
        nowTime: String,
        // Текущий баланс
        curBalance: [String, Number],
    },
    data() {
        return {
            // Показан ли попап с подтверждением переключения?
            popupIsShown: false,
            // Прямо сейчас создаем транзакцию?
            isApplyingTransaction: false,
        }
    },
    computed: {
        curPlan() {
            return this.plans[this.curPlanName];
        },
        newPlan() {
            return this.plans[this.newPlanName];
        },
        popupTitle() {
            if (this.curPlanName === this.newPlanName) {
                return 'Продление тарифа';
            } else if (this.curPlanName === 'test' || this.curPlan.subscriptionFee <= this.newPlan.subscriptionFee) {
                return 'Повышение тарифа';
            } else if (this.newPlanName === 'test' || this.curPlan.subscriptionFee > this.newPlan.subscriptionFee) {
                return 'Понижение тарифа';
            }
            return 'Изменение тарифа';
        },
        newPlanExpiresAt() {
            // Время, с которого будет действовать новый тариф
            const newPlanStartsAt = (this.curPlanName === 'test') ? this.nowTime : this.curPlanExpiresAt;
            return dayjs(newPlanStartsAt)
                .add(this.newPlan.duration, 'day')
                .format('YYYY-MM-DD HH:mm:ss');
        },
        upgradeFee() {
            if ((this.curPlanName === 'test' || this.curPlanExpiresAt < this.nowTime) || this.curPlanName === this.newPlanName) {
                // Повышение равно стоимости тарифа
                return this.newPlan.subscriptionFee;
            } else if (this.curPlan.subscriptionFee >= this.newPlan.subscriptionFee) {
                // Изменение ничего не стоит
                return 0;
            } else {
                // Неизрасходованное время, в часах
                const remainingTime = dayjs(this.curPlanExpiresAt).diff(this.nowTime, 'hour', true),
                    // Стоимость оставшегося времени по старому тарифу
                    remainingCost = remainingTime * (this.curPlan.subscriptionFee / 30 / 24),
                    // Стоимость оставшегося времени по новому тарифу
                    newRemainingCost = remainingTime * (this.newPlan.subscriptionFee / 30 / 24);
                return Math.ceil(newRemainingCost - remainingCost);
            }
        }
    },
    methods: {
        applyTransaction() {
            this.isApplyingTransaction = true;
            router.post(`/users/${this.username}/update-plan`, {
                curPlanName: this.curPlanName,
                curPlanExpiresAt: this.curPlanExpiresAt,
                newPlanName: this.newPlanName,
                newPlanExpiresAt: this.newPlanExpiresAt,
                upgradeFee: this.upgradeFee
            }, {
                onFinish: visit => this.isApplyingTransaction = this.popupIsShown = false,
                onError: errors => ElMessage.error(Object.values(errors).join("\n")),
            });
        }
    }
}
</script>

<style scoped>

</style>