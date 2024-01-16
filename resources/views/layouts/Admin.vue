<template>
    <div class="b-canvas">
        <header class="b-header">
            <Link class="b-logo" href="/" v-if="!authUser.username"><img src="/assets/logo-white.svg" />TopSbor</Link>
            <template v-if="authUser.username">
                <Link class="b-logo" href="/projects/">
                    <img src="/assets/logo-white.svg" />
                    TopSbor
                </Link>
                <b-navselect placeholder="Мои проекты" :links="projectSwitcher" v-if="projectSwitcher"
                             placeholder-link="/projects/">
                    <template #afterlist>
                        <Link href="/projects/" style="margin-top: 0.5rem">
                            <font-awesome-icon icon="list" />
                            Список проектов
                        </Link>
                    </template>
                </b-navselect>
                <div class="b-headerlink">
                    <Link href="/calc/">
                        <font-awesome-icon icon="calculator" />
                        Калькулятор
                    </Link>
                </div>
                <b-navselect :links="adminLinks" class="right" placeholder="Админка" v-if="adminLinks" />
                <div class="b-headerlink right">
                    <Link :href="`/users/${authUser.username}/transactions/`">
                        <font-awesome-icon icon="coins" />
                        {{ $filters.formatNumber(authUser.balance, 3) }} ₽
                    </Link>
                </div>
                <div class="b-navselect has_dropdown right">
                    <Link :href="`/users/${authUser.username}/`">
                        <span>{{ authUser.username }}</span>
                        <font-awesome-icon icon="caret-down" />
                    </Link>
                    <div class="b-navselect-list">
                        <Link :href="`/users/${authUser.username}/`">Мой аккаунт</Link>
                        <a @click="logout" href="javascript:void(0)">Выйти</a>
                    </div>
                </div>
            </template>
            <template v-else>
                <Link href="/login" class="right">Авторизация</Link>
            </template>
        </header>
        <div class="b-subheader" v-if="menuLinks || subheaderTitle">
            <div class="b-subheader-title" v-if="subheaderTitle">{{ subheaderTitle }}</div>
            <div class="b-subheader-menu">
                <BMenu :links="menuLinks" />
            </div>
        </div>
        <div class="b-main">
            <section class="b-section">
                <div class="b-section-h">
                    <aside class="b-sidebar" v-if="sideLinks && sideLinks.length">
                        <BMenu :links="sideLinks" />
                    </aside>
                    <div class="b-content">
                        <slot />
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="b-footer">
        <div class="b-footer-h">
            <span>&copy; TopSbor.com, 2023</span>
            <Link href="/oferta/">Оферта</Link>
            <a href="/help/" rel="nofollow" v-if="authUser.username">
                Документация
            </a>
            <a href="https://t.me/+3vFRBWOrp3s4M2Ey" rel="nofollow" target="_blank" v-if="authUser.username">
                Телеграм-группа
            </a>
            <a href="/legal/terms/" rel="nofollow" target="_blank" v-if="false"> Условия использования </a>
            <a href="/legal/privacy/" rel="nofollow" target="_blank" v-if="false"> Приватность </a>
            <a href="/contacts/" rel="nofollow" target="_blank" v-if="false"> Обратная связь </a>
        </div>
    </div>
</template>

<script>
import { router, usePage } from "@inertiajs/vue3";
import BMenu from "../blocks/BMenu.vue";
import BLogo from "../blocks/BLogo.vue";
import BNavselect from "../blocks/BNavselect.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

export default {
    components: { FontAwesomeIcon, BNavselect, BLogo, BMenu },
    props: {
        authUser: {
            type: Object,
            default() {
                return {};
            }
        },
        projectSwitcher: Array,
        adminLinks: Array,
        subheaderTitle: String,
        menuLinks: Array,
        sideLinks: Array,
    },
    methods: {
        logout() {
            router.post("/logout/");
        },
    },
};
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
    white-space: nowrap;
    @include mobile() {
        display: none;
    }

    svg {
        margin-right: 0.3em;
    }
}

.b-subheader {
    background-color: var(--primary-darkest);
    color: var(--white);
    width: 100%;
    padding: 0 2rem;
    box-sizing: border-box;

    &-title {
        margin: 2rem 0 1rem;
        font-size: var(--h2-font-size);
    }

    &-menu {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: start;
        margin-left: -1.5rem;
        overflow-x: auto;

        .b-menu {
            display: flex;
            align-items: center;
            justify-content: start;
            background-color: var(--primary-darkest);
            &-label {
                height: 60px;
                line-height: 60px;
                position: relative;
                color: var(--white);
                padding: 0 1.5rem;
                white-space: nowrap;
                &:hover {
                    background: var(--primary-dark);
                }
                &.active {
                    &:before {
                        content: '';
                        position: absolute;
                        bottom: 0;
                        left: 50%;
                        margin-left: -15px;
                        width: 0;
                        height: 0;
                        border-style: solid;
                        border-width: 0 15px 10px 15px;
                        border-color: transparent transparent #FFFFFF transparent;
                        transform: rotate(0deg);
                    }
                    &:hover {
                    }
                }
            }
            &-children {
                // TODO В перспективе можно сделать выпадающее меню
                display: none;
            }
        }
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

.g-telegram {
    display: flex;
    margin: 0 2rem;
    color: var(--white);

    svg {
        color: inherit;
    }

    &:hover {
        color: var(--primary-lightest);
    }
}

.no-padding {
    padding: 0;
}
</style>
