<template>
    <div class="b-filter">
        <div
            class="b-filter-field"
            v-for="(field, fieldName) in fields"
            :class="['type_' + field.type]"
            :key="fieldName"
        >
            <template v-if="field.type === 'radio'">
                <SelectButton
                    :options="field.options.slugs"
                    aria-labelledby="custom"
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                >
                    <template #option="slotProps">
                        {{ field.options.values[slotProps.option] }}
                    </template>
                </SelectButton>
            </template>

            <template v-else-if="field.type === 'select'">
                <Dropdown
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :options="field.options"
                    optionLabel="name"
                    :placeholder="field.placeholder"
                    class="w-full md:w-14rem"
                />
            </template>

            <template v-else-if="field.type === 'treeSelect'">
                <TreeSelect
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                    :options="field.options"
                    :placeholder="field.placeholder"
                    class="md:w-20rem w-full" />
            </template>

            <template v-else-if="field.type === 'text'">
                <InputText
                    type="text"
                    :placeholder="field.placeholder"
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue, 300)"
                />
            </template>

            <template v-else-if="field.type === 'checkbox'">
                <Checkbox
                    :id="fieldName"
                    :binary="true"
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                />
                <label :for="fieldName" class="ml-2">{{ field.text }}</label>
            </template>

            <template v-else-if="field.type === 'daterange'">
                <Calendar
                    selectionMode="range"
                    dateFormat="yy-mm-dd"
                    v-model="modelValue[fieldName]"
                    @update:modelValue="(newValue) => set(fieldName, newValue)"
                />
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";
import debounce from "lodash/debounce";

import InputText from 'primevue/inputtext';
import Checkbox from 'primevue/checkbox';
import SelectButton from 'primevue/selectbutton';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import TreeSelect from 'primevue/treeselect';

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

const emit = defineEmits(['update:modelValue']);

const localModelValue = computed({
    get() {
        return props.modelValue;
    },
    set(newValue) {
        emit('update:modelValue', newValue);
    },
});

function set(fieldName, newValue, delay = null) {
    localModelValue[fieldName] = newValue;

    if (delay) {
        updateDebounced(this);
    } else {
        updatePage();
    }
}

function updatePage() {
    const query = {};
    localModelValue.loading = true;

    Object.keys(props.fields).forEach((key) => {
        if (localModelValue[key]) {
            query[key] = localModelValue[key];
        }
    });

    router.get(props.url, query, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onFinish: function() {
            localModelValue.loading = false;
        }.bind(this),
    });
}

const updateDebounced = debounce((that) => {
    that.updatePage();
}, 500);
</script>

<style lang="scss">
.b-filter {
    display: flex;
    margin-bottom: var(--base-margin);
    align-items: start;

    &-field {
        &.type_text {
            flex-grow: 1;
            max-width: 420px;

            input {
                width: 100%;
            }
        }

        & + & {
            margin-left: var(--base-margin);
        }
    }
}
</style>
