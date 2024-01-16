<template>
    <Head title="Добавление страницы" />

    <div class="g-titlebar">
        <h1>Добавление страницы</h1>
        <Link href="/pages/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться
        </Link>
    </div>

    <form class="b-form b-form-pages" :class="{ loading: form.processing }" @submit.prevent="submit">
        <b-toggle title="Настройки" class="b-form-pages__toggle-title opened">
            <div class="b-form-group">
                <b-formrow title="Заголовок" :error="errors.title" hint="Для вывода на странице в title браузера">
                    <el-input type="text" v-model="form.title" required />
                </b-formrow>
                <b-formrow title="Краткий заголовок" :error="errors.label" hint="Для меню (можно не заполнять)">
                    <el-input type="text" v-model="form.label" />
                </b-formrow>
                <b-formrow title="Тип" :error="errors.type">
                    <el-radio-group v-model="form.type">
                        <el-radio-button v-for="(typeValue, typeKey) in typeOptions" :key="typeKey" :label="typeKey">
                            {{ typeValue }}
                        </el-radio-button>
                    </el-radio-group>
                </b-formrow>
                <b-formrow title="Полный URL" :error="errors.url" v-if="form.type === 'page'" hint="В формате /url/">
                    <el-input type="text" v-model="form.url" required />
                </b-formrow>
                <b-formrow title="Родительский раздел" :error="errors.parent_id">
                    <el-tree-select v-model="form.parent_id" :data="parentOptions" :render-after-expand="false"
                                    node-key="id" :props="{ label: 'title' }" filterable check-strictly />
                </b-formrow>
                <b-formrow title="Ограничение доступа" :error="errors.access" v-if="form.type === 'page'">
                    <el-select v-model="form.access" filterable clearable>
                        <el-option v-for="(accessValue, accessKey) in accessOptions" :key="accessKey"
                                   :label="accessValue"
                                   :value="accessKey" />
                    </el-select>
                </b-formrow>
                <b-formrow title="Публикация" :error="errors.is_published">
                    <el-switch size="large" v-model="form.is_published" active-text="Опубликовать страницу" required />
                </b-formrow>
            </div>
        </b-toggle>

        <div class="b-form-group">
            <button class="g-button">Создать статью</button>
        </div>
    </form>
</template>

<script>
import BFormrow from "../../blocks/BFormrow.vue";
import BToggle from "../../blocks/BToggle.vue";
import { ElInput, ElTreeSelect, ElSelect, ElOption, ElSwitch, ElRadioButton, ElRadioGroup } from "element-plus";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { router } from "@inertiajs/vue3";

export default {
    name: "PageCreate",
    components: {
        BFormrow,
        BToggle,
        FontAwesomeIcon,
        ElInput,
        ElTreeSelect,
        ElSelect,
        ElOption,
        ElSwitch,
        ElRadioButton,
        ElRadioGroup
    },
    props: {
        typeOptions: Object,
        accessOptions: Object,
        parentOptions: Object,
        errors: Object
    },
    data() {
        return {
            form: {
                type: 'page',
                access: 'user',
                is_published: false,
            },
        }
    },
    methods: {
        submit() {
            router.post('/pages', this.form);
        },
    }
}
</script>

<style lang="scss" scoped>
.b-form-group {
    margin-top: var(--base-margin);
}

</style>