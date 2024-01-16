<template>
    <Head :title="user.username" />

    <div class="b-dashboard">
        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">
                <div>Личные данные</div>
                <Link :href="`/users/${user.username}/edit/`">Редактировать</Link>
            </div>
            <div class="g-stat">
                <div>Телеграм</div>
                <div>{{ user.username }}</div>
            </div>
            <div class="g-stat">
                <div>Email</div>
                <div>{{ user.email }}</div>
            </div>
            <div class="g-stat" v-if="canViewAll">
                <div>Роль</div>
                <div>{{ roleTitle }}</div>
            </div>
            <div class="g-stat" v-if="user.is_blocked">
                <div>Статус</div>
                <div>
                    <div class="g-status red">Заблокирован</div>
                </div>
            </div>
            <div class="g-stat" v-if="user.birthday">
                <div>День рождения</div>
                <div>{{ user.birthday }}</div>
            </div>
            <div class="g-stat">
                <div>Дата регистрации</div>
                <div>{{ $filters.toLocalTime(user.created_at).split(' ')[0] }}</div>
            </div>
        </div>
        <div class="b-dashboard-item b-plan">
            <div class="b-plan-title">Текущий тариф — {{ activePlan.title }}</div>
            <div class="b-plan-description">
                До {{ activePlan.rateLimit }} запросов / минуту
                <template v-if="activePlan.threadCount">
                    <br>Оптимально до {{ activePlan.threadCount }} потоков
                </template>
                <template v-if=" ! activePlan.duration">
                    <br>Без ограничения по времени
                </template>
            </div>
            <div class="b-plan-terms" v-if="activePlan.subscriptionFee">
                <div>{{ $filters.formatNumber(activePlan.subscriptionFee) }} ₽</div>
                <div>/ {{ activePlan.duration }}
                    {{ $filters.ruPluralForm(activePlan.duration, ['день', 'дня', 'дней']) }}
                </div>
            </div>
            <div class="b-plan-terms" v-if="activePlan.apiRequestFee">
                <div>{{ $filters.formatNumber(activePlan.apiRequestFee, 3) }} ₽</div>
                <div>/ запрос</div>
            </div>
            <div class="b-plan-status" v-if="user.plan !== 'test'">
                Активен до: <strong>{{ $filters.toLocalTime(user.plan_expires_at) }}</strong>
            </div>
            <PlanUpgradeButton :username="user.username" :plans="plans" :cur-plan-name="user.plan"
                               :cur-plan-expires-at="user.plan_expires_at" :new-plan-name="user.plan"
                               :now-time="user.plan_expires_at" :cur-balance="user.balance"
                               v-if="user.plan !== 'test'" />
            <Link class="g-button outlined" :href="`/users/${user.username}/transactions/`" v-if="user.plan !== 'test'">
                Подключить безлимит
            </Link>
            <Link class="g-button" :href="`/users/${user.username}/transactions/`" v-else>Подключить безлимит</Link>
        </div>
        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">
                <div>Баланс</div>
                <Link :href="`/users/${user.username}/transactions/`">История операций</Link>
            </div>
            <div class="b-dashboard-item-bignumber">
                <font-awesome-icon icon="coins" />
                {{ $filters.formatNumber(user.balance, 3) }} ₽
            </div>
            <div class="g-topup" style="padding: 0">
                <div class="g-topup-label">Сумма пополнения:</div>
                <el-input v-model="topupAmount" />
                <a class="g-button" target="_blank"
                   :href="`/robokassa/pay?user=${user.username}&amount=${topupAmount}`">Пополнить</a>
            </div>
        </div>
        <div class="b-dashboard-item">
            <div class="b-dashboard-item-title">
                <div>Статистика за 24 часа</div>
                <Link :href="`/users/${user.username}/stats/`">Детальная статистика</Link>
            </div>
            <div class="g-stat">
                <div>Запросов к API, всего:</div>
                <div>{{ $filters.formatNumber(clicksPer24Hours) }}</div>
            </div>
            <div class="g-stat">
                <div>Максимально в минуту:</div>
                <div>{{ $filters.formatNumber(maxMinutelyClicks) }}</div>
            </div>
            <div class="g-stat">
                <div>Оптимальный тариф:</div>
                <div>{{ plans[bestPlan].title }}</div>
            </div>
        </div>
        <div class="b-dashboard-item for_notifications">
            <div class="b-dashboard-item-title">
                <div>Настройки оповещений</div>
                <div class="status" v-if="justUpdatedNofication">Изменения сохранены</div>
            </div>
            <el-switch :model-value="true" disabled
                       active-text="Оповещать за сутки, час и при окончании тарифа/денег" />
            <el-switch v-for="(oTitle, oKey) in notificationOptions" :key="oKey" :active-text="oTitle"
                       :model-value="notificationSettings[oKey]"
                       @click="(e) => setNotification(oKey, !notificationSettings[oKey])" />
        </div>
        <div class="b-dashboard-item" v-if="affiliateCode">
            <div class="b-dashboard-item-title">Ссылка для приглашения</div>
            <b-copytext>https://t.me/topsbor_bot?start={{ affiliateCode }}</b-copytext>
            <div style="margin-top: 1rem;">Приглашенные пользователи получат бонус: {{ affiliateBonus }} ₽</div>
        </div>
        <div class="b-dashboard-item" v-if="affiliateCode">
            <div class="b-dashboard-item-title">Приглашенные пользователи</div>
            <div class="g-stat">
                <div>Зарегистрировались</div>
                <div>
                    {{ referrals.length }}
                    {{ $filters.ruPluralForm(referrals.length, ['пользователь', 'пользователя', 'пользователей']) }}
                </div>
            </div>
            <div class="g-stat">
                <div>Оплатили</div>
                <div>{{ refPaymentSum }} ₽</div>
            </div>
            <ol>
                <li v-for="refUser of referrals" :key="refUser.username">
                    <Link :href="`/users/${refUser.username}/`">{{ refUser.username }}</Link>
                    <span v-if="refUser.paymentSum">
                    (оплатил: {{ $filters.formatNumber(refUser.paymentSum) }} ₽)
                </span>
                </li>
            </ol>
        </div>
    </div>

</template>

<script>
import BFilter from "../../blocks/BFilter.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import BCopytext from "../../blocks/BCopytext.vue";
import PlanUpgradeButton from "./Transaction/PlanUpgradeButton.vue";
import { ElInput, ElSwitch } from "element-plus";
import { router } from "@inertiajs/vue3";

export default {
    components: { ElSwitch, ElInput, BCopytext, FontAwesomeIcon, BFilter, PlanUpgradeButton },
    props: {
        user: Object,
        notificationOptions: Object,
        notificationSettings: Object,
        roleTitle: String,
        canViewAll: Boolean,
        plans: Object,
        // Общее число кликов за 24 часа
        clicksPer24Hours: Number,
        // Максимальное число кликов в минуту за 24 часа
        maxMinutelyClicks: Number,
        // Название оптимального тарифного плана
        bestPlan: String,
        affiliateCode: String,
        affiliateBonus: Number,
        referrals: Array,
        defaultTopupAmount: Number,
    },
    data() {
        return {
            topupAmount: this.defaultTopupAmount,
            justUpdatedNofication: false,
        }
    },
    computed: {
        activePlan() {
            return this.plans[this.user.plan];
        },
        refPaymentSum() {
            return this.referrals.reduce((result, referral) => result + referral.paymentSum ?? 0, 0);
        }
    },
    methods: {
        setNotification(type, newValue) {
            router.post(`/users/${this.user.username}/set_notification`, {
                type: type,
                value: newValue
            }, {
                preserveState: true,
                onSuccess: page => {
                    this.justUpdatedNofication = true;
                    setTimeout(() => this.justUpdatedNofication = false, 3000);
                }
            });
        }
    }
};
</script>

<style lang="scss">
.b-dashboard-item.for_notifications {
    .b-dashboard-item-title .status {
        font-size: 1rem;
        font-weight: normal;
        margin-left: auto;
        color: var(--green);
    }

    .el-switch {
        height: auto;

        &__label {
            height: auto;

            & span {
                line-height: 1.5;
            }
        }

        & + .el-switch {
            margin-top: 1.5rem;
        }
    }
}
</style>
