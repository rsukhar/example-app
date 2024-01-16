<template>
    <Link href="/" :class="['b-logo', type ? `type_${type}` : '']">
        <img :src="imagePath" :alt="alt" />
    </Link>
</template>

<script>
export default {
    name: "BLogo",
    props: {
        type: String,
        alt: String,
    },
    computed: {
        imagePath() {
            return "/assets/logo" + (this.type ? `-${this.type}` : "") + ".svg";
        },
    },
};
</script>

<style lang="scss">
.b-logo {
    display: flex;
    img {
        max-width: 200px;
    }
}
</style>
