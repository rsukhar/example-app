<template>
    <div class="b-filter">
        <div
            class="b-filter-field"
            v-for="(field, fieldName) in fields"
            :class="['type_' + field.type]"
            :key="fieldName"
        >
            <template v-if="field.type === 'radio'">
                <el-radio-group size="small"
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                >
                    <el-radio-button v-for="(oTitle, oValue) in field.options" :key="oValue" :label="oValue">{{ oTitle }}</el-radio-button>
                </el-radio-group>
            </template>
            <template v-else-if="field.type === 'select'">
                <el-select
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :placeholder="field.placeholder"
                >
                    <el-option :label="field.placeholder" value="" />
                    <el-option v-for="(oTitle, oName) in field.options" :key="oName" :label="oTitle" :value="oName" />
                </el-select>
            </template>
            <template v-else-if="field.type === 'treeSelect'">
                <el-tree-select
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :data="field.options"
                    :render-after-expand="false"
                    :placeholder="field.placeholder"
                    node-key="id"
                    :props="{ label: 'title' }"
                    filterable
                    check-strictly
                />
            </template>
            <template v-else-if="field.type === 'text'">
                <el-input
                    :placeholder="field.placeholder"
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue, 300)"
                />
            </template>
            <template v-else-if="field.type === 'checkbox'">
                <el-checkbox
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :label="field.text"
                    true-label="1"
                />
            </template>
            <template v-else-if="field.type === 'daterange'">
                <el-date-picker
                    type="daterange"
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    start-placeholder="Начало"
                    range-separator="—"
                    end-placeholder="Конец"
                    :clearable="false"
                />
            </template>
        </div>
    </div>
</template>

<script>
import { router } from "@inertiajs/vue3";
import debounce from "lodash/debounce";
import { ElRadioGroup, ElRadioButton, ElSelect, ElOption, ElTreeSelect } from "element-plus";
import { ElInput, ElCheckbox, ElDatePicker } from "element-plus";

export default {
    name: "BFilter",
    components: { ElRadioGroup, ElRadioButton, ElSelect, ElOption, ElTreeSelect, ElInput, ElCheckbox, ElDatePicker },
    props: {
        url: {
            type: String,
            default() {
                return location.pathname;
            },
        },
        modelValue: Object,
        fields: Object,
    },
    computed: {
        localModelValue: {
            get() {
                return this.modelValue;
            },
            set(newValue) {
                this.$emit("update:modelValue", newValue);
            },
        },
    },
    emits: ["update:modelValue"],
    methods: {
        set(fieldName, newValue, delay = null) {
            this.localModelValue[fieldName] = newValue;
            if (delay) {
                this.updateDebounced(this);
            } else {
                this.updatePage();
            }
        },
        updatePage() {
            const query = {};
            this.localModelValue.loading = true;
            Object.keys(this.fields).forEach((key) => {
                if (this.localModelValue[key]) query[key] = this.localModelValue[key];
            });
            router.get(this.url, query, {
                preserveState: true,
                preserveScroll: true,
                replace: true,
                onFinish: function() {
                    this.localModelValue.loading = false;
                }.bind(this),
            });
        },
        updateDebounced: debounce((that) => {
            that.updatePage();
        }, 500),
    },
};
</script>

<style lang="scss">
.b-filter {
    display: flex;
    margin-bottom: var(--base-margin);
    align-items: start;

    &-field {
        &.type_text {
            flex-grow: 1;
            max-width: 300px;
        }

        & + & {
            margin-left: var(--base-margin);
        }
    }
}
</style>
