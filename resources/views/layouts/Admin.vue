<template>
    <div class="b-canvas">
        <header class="b-header">
            <Link class="b-logo" href="/">
                <img src="/assets/logo-white.svg"/>
                ExampleApp
            </Link>

            <template v-if="authUser && authUser.username">
                <div class="b-headerlink">
                    <Link href="/users/">Список пользователей</Link>
                </div>

                <div class="b-headerlink right">
                    <Link :href="`/users/${authUser.username}/`">Мой аккаунт</Link>
                    <a @click="logout" href="javascript:void(0)">Выйти</a>
                </div>
            </template>

            <template v-if="!authUser">
                <div class="b-headerlink right">
                    <Link href="/login">Авторизация</Link>
                </div>
            </template>
        </header>

        <div class="b-main">
            <section class="b-section">
                <div class="b-section-h">
                    <div class="b-content">
                        <slot/>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <div class="b-footer">
        <div class="b-footer-h">
            <span>&copy; ExampleApp, 2024</span>
        </div>
    </div>
</template>

<script setup>
import {router} from '@inertiajs/vue3';

const props = defineProps({
    authUser: {
        type: Object,
        default() {
            return {};
        }
    }
});

console.log(props);

function logout() {
    router.post("/logout/");
}
</script>

<style lang="scss">
.b-body {
    min-height: 100%;
}

.b-body #app {
    display: flex;
    flex-direction: column;
    min-height: 100%;
    background: var(--white);
}

.b-canvas {
    display: flex;
    flex-grow: 1;
    flex-direction: column;
    width: 100%;
}

.b-header {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    z-index: 1;
    align-items: center;
    justify-content: start;
    line-height: 60px;
    background-color: var(--primary);
    color: var(--white);
    width: 100%;
    margin: 0 auto;
    padding: 0 2rem;
    box-sizing: border-box;

    .b-logo {
        display: flex;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 2px;
        align-items: center;
        font-weight: bold;

        img {
            height: 30px;
            width: 30px;
            margin-right: 1rem;
        }
    }

    a {
        color: inherit;
    }

    @include tablet_desktop() {
        & > *:not(.right) + .right {
            margin-left: auto;
        }
    }
    @include mobile() {
        justify-content: space-between;
    }

    &:hover {
        z-index: 2;
    }

    @include mobile_tablet() {
        line-height: 40px;
        gap: 0.5rem;
        .b-logo {
            font-size: 0px;
            width: 20px;

            img {
                height: 20px;
                margin-right: 0;
            }
        }
    }
}

.b-headerlink {
    display: flex;
    white-space: nowrap;
    gap: 2rem;

    svg {
        margin-right: 0.3em;
    }

    a:hover {
        color: var(--white);
    }
}

.b-section {
    position: relative;
    margin: 0 auto;
    padding: 0 2rem;

    &-h {
        position: relative;
        z-index: 1;
        margin: 0 auto;
        padding: 2rem 0;
        display: flex;
        @include mobile() {
            flex-wrap: wrap;
        }

        &::after {
            content: "";
            display: block;
            clear: both;
        }
    }
}

.b-sidebar {
    width: 200px;
    margin-right: 4rem;

    .b-menu {
        display: flex;
        flex-direction: column;

        &-label {
            padding: 6px 0;
            white-space: normal;
            border-radius: var(--border-radius);

            &.active {
                color: var(--black);
                background-color: var(--bg);
            }
        }

        &-children {
            padding-left: 2rem;
        }
    }

    &:empty {
        display: none;
    }
}

.b-content {
    flex-grow: 1;
}

.b-footer {
    overflow: hidden;
    font-size: 0.85rem;
    padding: 0 1.5rem;
    line-height: 2rem;
    background-color: var(--white);

    a {
        font-weight: bold;
    }

    &-h {
        margin: 0 auto;
        max-width: 1200px;
        text-align: center;

        &:last-child > * {
            margin: 0.5rem;
        }
    }
}
</style>
