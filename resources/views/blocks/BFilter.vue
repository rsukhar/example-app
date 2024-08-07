<template>
    <div class="b-filter">
        <div
            class="b-filter-field"
            v-for="(field, fieldName) in fields"
            :class="['type_' + field.type]"
            :key="fieldName"
        >
            <template v-if="field.type === 'radio'">
                <el-radio-group
                    size="small"
                    :model-value="modelValue[fieldName] ?? ''"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                >
                    <el-radio-button v-for="(oTitle, oValue) in field.options" :key="oValue" :label="oTitle" :value="oValue" />
                </el-radio-group>
            </template>

            <template v-else-if="field.type === 'select'">
                <el-select
                    :model-value="modelValue[fieldName] ?? ''"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :placeholder="field.placeholder"
                >
                    <el-option :label="field.placeholder" value="" />
                    <el-option v-for="(oTitle, oName) in field.options" :key="oName" :label="oTitle" :value="oName" />
                </el-select>
            </template>

            <template v-else-if="field.type === 'text'">
                <el-input
                    :placeholder="field.placeholder"
                    :model-value="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue, true)"
                    :style="field.style"
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

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from "lodash/debounce";
import {
    ElRadioGroup,
    ElRadioButton,
    ElSelect,
    ElOption,
    ElTreeSelect,
    ElInput,
    ElCheckbox,
    ElDatePicker
} from "element-plus";

const props = defineProps({
    url: {
        type: String,
        default() {
            return location.pathname;
        },
    },
    modelValue: Object,
    fields: Object,
});

const emit = defineEmits(["update:modelValue"]);

const localModelValue = computed({
    get() {
        return props.modelValue;
    },
    set(newValue) {
        emit("update:modelValue", newValue);
    },
});

function set(fieldName, newValue, delay = false) {
    localModelValue.value[fieldName] = newValue;

    if (delay) {
        updateDebounced(updatePage);
    } else {
        updatePage();
    }
}

const updatePage = function() {
    const query = {};
    localModelValue.value.loading = true;

    Object.keys(props.fields).forEach((key) => {
        if (localModelValue.value[key]) {
            query[key] = localModelValue.value[key];
        }
    });

    router.get(props.url, query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onFinish: function() {
            localModelValue.value.loading = false;
        },
    });
}

const updateDebounced = debounce((fn) => {
    fn();
}, 500);
</script>

<style lang="scss">
.b-filter {
    display: flex;
    flex-wrap: wrap;
    gap: var(--base-margin);
    align-items: start;
    margin-bottom: 1rem;

    &-field {
        &.type_text {
            width: 300px;
        }
        &.type_select, &.type_treeSelect {
            width: 200px;
        }
    }
}
</style>
