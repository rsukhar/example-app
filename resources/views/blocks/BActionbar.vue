<template>
    <Teleport to="body">
        <div class="b-actionbar">
            <slot />
        </div>
    </Teleport>
</template>

<script>
export default {
    name: "BActionbar",
}
</script>

<style>
.b-actionbar {
    position: fixed;
    bottom: 0;
    background: var(--bg);
    box-shadow: var(--shadow);
    width: 100%;
    z-index: 2000;
    padding: 1rem 2rem;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    & > *:not(:first-child) {
        margin-left: 2rem;
    }
}
</style>