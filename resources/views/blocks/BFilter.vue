<template>
    <div class="b-filter">
        <div class="b-filter-field" v-for="(field, fieldName) in fields" :class="['type_' + field.type]"
             :key="fieldName">
            <div class="b-filter-field-title" v-if="field.title">{{ field.title }}</div>
            <template v-if="field.type === 'radio'">
                <RadioButtonGroup :model-value="modelValue[fieldName] ?? ''"
                                  @update:modelValue="(newValue) => set(fieldName, newValue)">
                    <div class="g-hwrapper" v-for="(oTitle, oValue) in field.options" :key="oValue">
                        <RadioButton :input-id="`input-${oValue}`" :value="oValue" />
                        <label :for="`input-${oValue}`">
                            <font-awesome-icon v-if="field.icons && field.icons[oValue]" :icon="field.icons[oValue]" />
                            {{ oTitle }}
                        </label>
                    </div>
                </RadioButtonGroup>
            </template>

            <template v-else-if="field.type === 'select'">
                <Select
                    :model-value="modelValue[fieldName] ?? ''"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :options="field.options"
                    :option-label="`label`"
                    :option-value="`value`"
                    :placeholder="field.placeholder"
                    filter
                />
            </template>

            <template v-else-if="field.type === 'treeSelect'">
                <TreeSelect :model-value="modelValue[fieldName]"
                            @update:modelValue="(newValue) => set(fieldName, newValue)"
                            :options="field.options"
                            :placeholder="field.placeholder" filter />

            </template>

            <template v-else-if="field.type === 'text'">
                <InputText :placeholder="field.placeholder" :model-value="modelValue[fieldName]"
                           @update:modelValue="(newValue) => set(fieldName, newValue, true)" :style="field.style" />
            </template>

            <template v-else-if="field.type === 'checkbox'">
                <div class="g-hwrapper">
                    <Checkbox :model-value="modelValue[fieldName]"
                              @update:modelValue="(newValue) => set(fieldName, newValue)"
                              :input-id="`input-${fieldName}`"
                              binary />
                    <label style="cursor: pointer" :for="`input-${fieldName}`">{{ field.text }}</label>
                </div>

            </template>

            <template v-else-if="field.type === 'checkboxes'">
                <CheckboxGroup :model-value="((modelValue[fieldName] ?? '')+'').split('|')"
                               @update:modelValue="(newValue) => set(fieldName, newValue.join('|'))">
                    <div class="g-hwrapper" v-for="(oTitle, oValue) in field.options" :key="oValue">
                        <Checkbox :input-id="`input-${oValue}`" :value="oValue" />
                        <label :for="`input-${oValue}`">{{ oTitle }}</label>
                    </div>
                </CheckboxGroup>

            </template>

            <template v-else-if="field.type === 'daterange'">
                <DatePicker :model-value="parseDatepickerDate(modelValue[fieldName])"
                            @update:modelValue="(newValue) => set(fieldName, newValue)"
                            selectionMode="range"
                            :manualInput="false"
                            style="flex-grow: 0;"
                            :hide-on-range-selection="true"
                            :placeholder="field.placeholder"
                />

            </template>
        </div>
    </div>

</template>

<script setup>
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from "lodash/debounce";
import { toLocalTime } from "../../js/helpers.js";

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

function parseDatepickerDate(range) {
    if (typeof (range) === 'object') {
        return range;
    } else if (range !== undefined) {
        const splitRange = range.split('-');
        const start = splitRange[0].split('.').reverse().join('-');
        const end = splitRange[1] ? splitRange[1].split('.').reverse().join('-') : false;

        return [new Date(start), end ? new Date(end) : null];
    }
}

const updatePage = function() {
    const query = {};
    localModelValue.value.loading = true;

    Object.keys(props.fields).forEach((key) => {
        if (localModelValue.value[key]) {
            if (props.fields[key].type === 'daterange') {
                if (typeof (localModelValue.value[key]) === 'object') {
                    const start = toLocalTime(localModelValue.value[key][0], ';').split(';')[0].trim();
                    const end = localModelValue.value[key][1] ? toLocalTime(localModelValue.value[key][1], ';').split(';')[0].trim() : false;
                    query[key] = end ? start + '-' + end : start;
                } else {
                    return localModelValue.value[key];
                }
                console.log();

            } else {
                query[key] = localModelValue.value[key];
            }
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
    margin-bottom: 1rem;
    align-items: center;

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
