<template>
    <div class="b-copytext" :class="['size_' + size]">
        <div class="b-copytext-text">
            <slot />
        </div>
        <template v-if="isSupported">
            <font-awesome-icon icon="copy" class="b-copytext-copy" title="Скопировать в буфер обмена"
                               @click="copy($slots.default()[0].children)"
                               v-if="!copied" />
            <font-awesome-icon icon="check" class="b-copytext-success" v-else />
        </template>
    </div>
</template>

<script>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { useClipboard } from "@vueuse/core";

export default {
    name: "BCopytext",
    components: { FontAwesomeIcon },
    props: {
        href: String,
        size: {
            type: String,
            default: 'normal'
        }
    },
    setup() {
        const { copy, copied, isSupported } = useClipboard();
        return { copy, copied, isSupported };
    }
}
</script>

<style lang="scss">
.b-copytext {
    border: 1px var(--border) solid;
    font-size: 1rem;
    border-radius: var(--border-radius);
    display: flex;
    align-items: center;

    &-text {
        font-weight: bold;
        padding: 0.5em 1em 0.5em 1.5em;
        color: var(--black);
        overflow-wrap: break-word;
        word-break: break-all;
        max-height: 100px;
        overflow-y: auto;
    }

    &-copy {
        margin: 0 1rem 0 0;
        font-size: 1.5em;
        color: var(--primary);
        cursor: pointer;

        &:hover {
            color: var(--primary-dark);
        }
    }

    &-success {
        margin: 0 1rem 0 0;
        color: var(--green);
        font-size: 1.5em;
    }
}
</style>