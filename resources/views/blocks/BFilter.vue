<template>
    <div class="b-filter">
        <div class="b-filter-field" v-for="(field, fieldName) in fields" :class="['type_' + field.type]"
             :key="fieldName">
            <div class="b-filter-field-title" v-if="field.title">{{ field.title }}</div>
            <template v-if="field.type === 'radio'">
                <div type="button" class="p-radiobutton-group">
                    <div class="p-radiobutton-group-item" v-for="(oTitle, oValue) in field.options" :key="oValue">
                        <input type="radio" name="radiogroup" :id="`input-${oValue}`" :value="oValue"
                               :model-value="modelValue[fieldName] ?? ''"
                               :checked="modelValue[fieldName] === oValue || oValue === ''"
                               @change="(event) => set(fieldName, event.target.value)">
                        <label :for="`input-${oValue}`">
                            <font-awesome-icon v-if="field.icons && field.icons[oValue]" :icon="field.icons[oValue]" />
                            {{ oTitle }}
                        </label>
                    </div>
                </div>
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

    <!--    <RadioButtonGroup type="button" unstyled class="p-radiobutton-group">
            <div class="p-radiobutton-group-item">
                <input type="radio" name="radiogroup" id="r1" checked>
                <label for="r1">Option 1</label>
            </div>
        </RadioButtonGroup>-->
    <!--
        <div class="radio-group" role="group" aria-label="Basic radio toggle button group">
            <div class="radio-group__item">
                <input type="radio" name="radiogroup" id="r1" checked>
                <label for="r1">Option 1</label>
            </div>
            <div class="radio-group__item">
                <input type="radio" name="radiogroup" id="r2">
                <label for="r2">Option 2</label>
            </div>
            <div class="radio-group__item">
                <input type="radio" name="radiogroup" id="r3">
                <label for="r3">Long Special Option </label>
            </div>
        </div>-->
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

// radiobutton group

input[type="radio"] {
    appearance: none;
    width: 100%;
    height: 100%;
    border-radius: 0;
}

.radio-group {
    display: flex;
    padding: 16px;

    &__item {
        min-width: 96px;
        overflow: hidden;
        display: grid;
        cursor: pointer;
        flex-shrink: 0;

        &:last-child {
            border-radius: 0 4px 4px 0;
        }

        &:first-child {
            border-radius: 4px 0 0 4px;
        }

        label {
            grid-area: 1/1;
            padding: 8px 16px;
            text-align: center;
        }

        input {
            grid-area: 1/1;
            background-color: #eee;

            &:hover {
                background-color: #ddd;
            }

            &:checked {
                background-color: #8a3ffc;

                & + label {
                    color: white;
                    cursor: default;
                }
            }
        }
    }
}
</style>
