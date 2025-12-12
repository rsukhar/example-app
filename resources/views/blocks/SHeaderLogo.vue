<template>
    <div class="s-header-logo">
        <Link class="s-logo" :href="href">
            <FontAwesomeIcon icon="star" />
            <span>ExampleApp</span>
        </Link>
    </div>
</template>

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
const props = defineProps({
    href: String,
});
</script>

<style lang="scss">
.s-logo {
    display: flex;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 2px;
    align-items: center;
    font-weight: bold;

    span {
        height: 60px;
        line-height: 63px;
    }

    @include mobile() {
        font-size: 0;
        width: 100px;

        img {
            height: 20px;
            margin-right: 0;
        }
    }
}
</style>