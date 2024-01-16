<template>
    <div class="b-formrow" :class="className">
        <div class="b-formrow-title" @click="maybeFocusInput" :style="{width: titleWidth ? titleWidth + 'px' : null}">
            {{ title }}
        </div>
        <div class="b-formrow-input" ref="input">
            <slot />
            <div class="b-form-tooltip" v-if="hint && className.includes('type_switch')">
                <el-tooltip :content="hint" placement="bottom">
                    <font-awesome-icon icon="circle-question" />
                </el-tooltip>
            </div>
            <div class="b-formrow-hint" v-if="hint && !className.includes('type_switch')">{{ hint }}</div>
            <div class="b-formrow-error" v-if="error">
                {{ Array.isArray(error) ? error.join("") : error }}
            </div>
        </div>
    </div>
</template>

<script>
import { ElTooltip } from "element-plus";

export default {
    name: "BFormrow",
    components: { ElTooltip },
    props: {
        // eslint-disable-next-line vue/no-reserved-props
        class: String,
        title: String,
        titleWidth: {
            type: String,
            default: '220'
        },
        hint: String,
        error: [String, Array],
    },
    computed: {
        className() {
            return [this.class, this.error ? "error" : ""];
        },
    },
    methods: {
        maybeFocusInput() {
            const $input = this.$refs.input.querySelector("input, textarea");
            if ($input) $input.focus();
        },
    },
};
</script>

<style lang="scss">
.b-formrow {
    display: flex;
    margin-bottom: var(--base-margin);

    &-title {
        line-height: var(--field-height);
        text-align: left;
        flex-shrink: 0;
    }

    &-input {
        flex-grow: 1;
        text-align: left;
    }

    &-hint {
        font-size: 0.9rem;
        color: var(--text-light);
        margin-top: 5px;
    }

    &-error {
        color: var(--red);
    }

    div:not(&-description) + &-error {
        margin-top: 5px;
    }

    &.error .el-input {
        --el-border-color: var(--red);
    }

    &.type_switch {
        .b-formrow-title {
            font-size: 0;
        }
    }

    // Если лейблы слева
    .b-form:not(.label_top) & {
        &-input {
            .el-select {
                width: 100%;
            }
        }
    }

    // Если лейблы сверху
    .b-form.label_top & {
        flex-direction: column;

        &-title {
            width: auto !important;
        }
        .b-formrow-input .g-button {
            width: 100%;
        }
    }

    .b-form-grouptitle {
        margin: calc(var(--base-margin) * 2) 0 var(--base-margin) 250px;
    }

    .b-form-tooltip {
        display: inline-block;
        margin-left: calc(var(--base-margin) / 4);
        cursor: help;

        svg {
            color: var(--text-light);
            vertical-align: middle;
        }
    }
}
</style>
