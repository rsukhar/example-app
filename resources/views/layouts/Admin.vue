<template>
    <SCanvas>
        <template #header>
            <SHeaderLogo href='/' class="mr-4" />
            <template v-if="authUser && authUser.username">
                <div class="s-headerlink">
                    <Link href="/users/">Список пользователей</Link>
                </div>

                <div class="s-headerlink right">
                    <Link :href="`/users/${authUser.username}/`">Мой аккаунт</Link>
                    <a @click="logout" href="javascript:void(0)">Выйти</a>
                </div>
            </template>

            <template v-if="!authUser">
                <div class="s-headerlink right">
                    <Link href="/login">Авторизация</Link>
                </div>
            </template>
        </template>

        <template #subheader v-if="menuLinks || subheaderTitle">
            <div class="s-subheader-title" v-if="subheaderTitle">{{ subheaderTitle }}</div>
            <div class="s-canvas-subheader-menu">
                <SHorizontalMenu :links="menuLinks" />
            </div>
        </template>
        <template #content>
            <slot/>
        </template>
    </SCanvas>
    <SFooter>
        <div class="s-footer-h">
            <span>&copy; ExampleApp, 2024</span>
        </div>
    </SFooter>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import SHeaderLogo from "../blocks/SHeaderLogo.vue";
import { SCanvas, SHorizontalMenu, SFooter } from 'startup-ui';

const props = defineProps({
    authUser: {
        type: Object,
        default() {
            return {};
        }
    },
    subheaderTitle: String,
    menuLinks: Array
});

function logout() {
    router.post("/logout/");
}
</script>

<style lang="scss">
.s-canvas-header {
    color: var(--s-white) !important;
}

.s-headerlink {
    display: flex;
    white-space: nowrap;
    gap: 2rem;

    svg {
        margin-right: 0.3em;
    }

    a:hover {
        color: var(--s-white);
    }
}
</style>
