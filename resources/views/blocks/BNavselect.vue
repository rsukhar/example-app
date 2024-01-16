<template>
    <div class="b-navselect has_dropdown" v-if="hasMultipleLinks">
        <Link :href="activeUrl">
            <span>{{ activeLabel }}</span>
            <font-awesome-icon icon="caret-down" v-if="hasMultipleLinks" />
        </Link>
        <div class="b-navselect-list" v-if="hasMultipleLinks">
            <Link v-for="link in links" :key="link.label" :href="link.url" :class="{ active: link.active }">
                {{ link.label }}
            </Link>
            <slot name="afterlist"></slot>
        </div>
    </div>
    <div class="b-navselect" v-else-if="links.length === 1">
        <Link :href="links[0].url">
            <span>{{ links[0].label }}</span>
        </Link>
    </div>
</template>

<script>
export default {
    name: "BNavselect",
    props: {
        // В формате [{label: '', url: '', active: ''}]
        links: Object,
        placeholder: {
            type: String,
            default() {
                return "Перейти к";
            },
        },
        placeholderLink: String,
    },
    computed: {
        hasMultipleLinks() {
            return true; //this.links.length > 1;
        },
        activeLabel() {
            if ( ! this.links.length === 1) return this.links[0].label;
            for (let link of this.links) {
                if (link.active) return link.label;
            }
            return this.placeholder;
        },
        activeUrl() {
            for (let link of this.links) {
                if (link.active) return link.url;
            }
            return this.placeholderLink ?? this.$page.url;
        },
    },
};
</script>

<style lang="scss">
.b-navselect {
    flex-shrink: 0;
    position: relative;
    border-radius: var(--border-radius);
    a {
        display: block;
        text-decoration: none;
        white-space: nowrap;
        &:hover {
            color: inherit;
        }
    }
    > a {
        display: inline-block;
        font-size: 1.1rem;
        padding: 0 20px;
        vertical-align: top;
        @include mobile(){
            padding: 0 10px;
        }
        svg {
            margin-left: 20px;
        }
    }
    &-list {
        position: absolute;
        overflow: hidden;
        visibility: hidden;
        min-width: 100%;
        opacity: 0;
        border-radius: var(--border-radius);
        z-index: 3;
        & > a {
            padding: 0 20px;
            line-height: 3rem;
            &:hover {
                background-color: var(--primary-darkest);
            }
        }
    }
    &.active,
    &:hover {
        background-color: var(--primary-dark);
        .b-navselect-list {
            visibility: visible;
            opacity: 1;
            background-color: var(--primary-dark);
        }
    }
    &.has_dropdown {
        .b-navselect-list {
            border-radius: 0 0 var(--border-radius) var(--border-radius);
        }
        &.active,
        &:hover {
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }
    }
    &.right &-list {
        right: 0;
    }
}
</style>
