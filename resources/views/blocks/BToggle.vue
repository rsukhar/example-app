<template>
    <div class="b-toggle" :class="{ opened: isOpened }">
        <div class="b-toggle-title" @click="isOpened = !isOpened">
            {{ title }}
            <font-awesome-icon icon="chevron-down" />
        </div>
        <div class="b-toggle-body">
            <slot />
        </div>
    </div>
</template>

<script>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export default {
    name: "BToggle",
    components: { FontAwesomeIcon },
    props: {
        title: String,
        opened: Boolean
    },
    data() {
        return {
            isOpened: !!this.opened
        }
    }
}
</script>

<style lang="scss">
.b-toggle {
    border: 1px var(--border) solid;
    border-radius: var(--border-radius);
    &-title {
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        cursor: pointer;
        border-radius: var(--border-radius);
        &:hover {
            color: var(--primary);
        }
        svg {
            margin-left: auto;
            font-size: 16px;
        }
    }
    &-body {
        display: none;
        padding: 1rem 1.5rem;
        > *:last-child {
            margin-bottom: 0;
        }
    }
    &.opened &-body {
        display: block;
    }
}
</style>