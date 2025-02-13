<template>
    <div class="b-header-logo">
        <Link class="b-logo" :href="href">
            <font-awesome-icon icon="star" />
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

<style scoped>
.b-logo {
    span {
        height: 60px;
        line-height: 63px;
    }
}
</style>