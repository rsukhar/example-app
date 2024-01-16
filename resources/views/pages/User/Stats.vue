<template>
    <Head title="Статистика переходов" />
    <div class="g-hwrapper" style="margin-bottom: 2rem">
        <el-radio-group size="small"
                        :model-value="stringPeriod"
                        @update:modelValue="(newValue) => setPeriod(newValue.split('-'))"
        >
            <el-radio-button v-for="(pValue, pTitle) in periodTitles" :key="pTitle" :label="pValue">
                {{ pTitle }}
            </el-radio-button>
        </el-radio-group>
        <el-date-picker
            type="daterange"
            :model-value="period"
            @update:modelValue="(newValue) => setPeriod(newValue)"
            range-separator="—"
            start-placeholder="Начало"
            end-placeholder="Конец"
            format="DD.MM.YYYY"
            value-format="YYYYMMDD"
            :clearable="false"
            style="flex-grow: 0"
        />
    </div>
    <div class="g-titlebar">
        <h2>Переходы по API-ссылке</h2>
    </div>
    <BStatchart :data="clickStats" />
</template>

<script>
import { ElDatePicker, ElRadioButton, ElRadioGroup, ElTable, ElTableColumn } from "element-plus";
import BStatchart from "../../blocks/BStatchart.vue";
import { router } from "@inertiajs/vue3";

export default {
    name: "Stats",
    components: { BStatchart, ElRadioButton, ElRadioGroup, ElDatePicker },
    props: {
        period: Array,
        periodTitles: Object,
        clickStats: Array,
    },
    data() {
        return {
            isLoading: false
        }
    },
    computed: {
        stringPeriod() {
            return this.period.join('-');
        },
        totalClicks(){
            return this.clickStats.reduce((acc, item) => acc + item.amount, 0);
        },
        avgMinutelyClicks(){
            return this.clickStats.reduce((acc, item) => acc + item.minutelyAmount, 0) / this.clickStats.length;
        },
        totalFee(){
            return this.clickStats.reduce((acc, item) => acc + item.fee, 0);
        },
        avgHourlyFee(){
            return this.totalFee / this.clickStats.length;
        }
    },
    methods: {
        setPeriod(newValue) {
            this.isLoading = true;
            router.get(location.pathname, {
                period: newValue.join('-')
            }, {
                preserveScroll: true,
                replace: true,
                onFinish: function() {
                    this.isLoading = false;
                }.bind(this),
            });
        },
    },
}
</script>

<style scoped>

</style>