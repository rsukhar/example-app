<template>
    <template v-if="layout === 'input'">
        <div class="b-copytext layout_input">
            <div class="b-copytext-text">
                <slot />
            </div>
            <font-awesome-icon :icon="copied ? 'check' : 'copy'"
                               :class="{success: copied}" title="Скопировать" @click="copy(innerText)" />
        </div>
    </template>
    <template v-else-if="layout === 'inline'">
        <div class="b-copytext layout_inline" title="Скопировать" @click="copy(innerText)" :class="{success: copied}">
            <div class="b-copytext-text">
                <slot />
            </div>
            <font-awesome-icon :icon="copied ? 'check' : 'copy'" />
        </div>
    </template>
</template>

<script setup>
import { computed, useSlots } from 'vue';
import { useClipboard } from '@vueuse/core';
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    href: String,
    size: {
        type: String,
        default: 'normal'
    },
    layout: {
        type: String,
        default: 'input' // input / inline
    }
});
let slots = useSlots();
const { text, copy, copied, isSupported } = useClipboard();
const innerText = computed(() => {
    return slots.default()[0].children;
});
</script>

<style lang="scss">
.b-copytext {
    display: flex;
    justify-content: space-between;
    &-text {
        & + svg {
            color: var(--primary);
            cursor: pointer;
            margin: 0 1rem 0 0;
            &:hover {
                color: var(--primary-dark);
            }
        }
    }
    &.layout_input {
        display: flex;
        align-items: center;
        border: 1px var(--border) solid;
        font-size: 1rem;
        border-radius: var(--border-radius);
        .b-copytext-text {
            padding: 0.5em 1em 0.5em 1.5em;
            color: var(--black);
            max-height: 100px;
            overflow-y: auto;
            font-weight: bold;
            overflow-wrap: break-word;
            word-break: break-all;
            & + svg {
                font-size: 1.2em;
            }
        }
    }
    &.layout_inline {
        display: inline-flex;
        align-items: center;
        color: var(--primary);
        cursor: pointer;
        &.success {
            color: var(--green) !important;
        }
        &:hover {
            color: var(--primary-dark);
        }
        .b-copytext-text {
            line-height: 1;
            border-bottom: 1px currentColor dashed;
            & + svg {
                font-size: 0.8em;
                margin-left: 0.5em;
            }
        }
    }
}
</style>