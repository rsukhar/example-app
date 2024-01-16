<template>
    <Head title="Редактирование страницы" />

    <div class="g-titlebar">
        <h1>Редактирование страницы «{{ page.title }}»</h1>
        <Link href="/pages/" class="right">
            <font-awesome-icon icon="rotate-left" />
            Вернуться
        </Link>
    </div>

    <form class="b-form" :class="{ loading: form.processing }" @submit.prevent="submit">

        <b-toggle title="Настройки">
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

        <div class="b-form-group" v-if="form.type === 'page'">
            <Editor
                v-bind:api-key="tinymceApiKey"
                :init="tinymceConfig"
                v-model="form.content"
            />
        </div>

        <div class="b-form-group">
            <button class="g-button">Сохранить статью</button>
        </div>
    </form>
</template>

<script>
import BToggle from "../../blocks/BToggle.vue";
import BFormrow from "../../blocks/BFormrow.vue";
import { ElInput, ElSelect, ElOption, ElSwitch, ElRadioButton, ElRadioGroup } from "element-plus";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { router } from "@inertiajs/vue3";
import Editor from "@tinymce/tinymce-vue";
import axios from "axios";

export default {
    name: "PageEdit",
    components: {
        BFormrow,
        BToggle,
        FontAwesomeIcon,
        ElInput,
        ElSelect,
        ElOption,
        ElSwitch,
        ElRadioButton,
        ElRadioGroup,
        Editor
    },
    props: {
        typeOptions: Object,
        accessOptions: Object,
        tinymceApiKey: String,
        page: Object,
        errors: Object
    },
    data() {
        return {
            form: {
                ...this.page,
            },
            tinymceConfig: {
                height: 500,
                selector: 'textarea',
                menubar: false,
                statusbar: false,
                plugins: [
                    'advlist', 'anchor', 'autolink', 'charmap', 'code', 'fullscreen',
                    'help', 'image', 'insertdatetime', 'link', 'lists', 'media',
                    'preview', 'searchreplace', 'table', 'visualblocks', 'fullscreen',
                ],
                toolbar: [
                    'styles | bullist numlist  | link image media code fullscreen'
                ],
                images_upload_handler: this.imageUploadHandler,
                automatic_uploads: true,
                convert_urls: false,
                style_formats: [
                    { title: 'Regular text', format: 'p', classes: '' },
                    { title: 'Quote', format: 'blockquote' },
                    { title: 'Code', format: 'pre' },
                    { title: 'Heading 1', format: 'h1' },
                    { title: 'Heading 2', format: 'h2' },
                    { title: 'Heading 3', format: 'h3' },
                    { title: 'Heading 4', format: 'h4' },
                    { title: 'Note', block: 'p', classes: 'g-alert note' },
                    { title: 'Attention', block: 'p', classes: 'g-alert attention' }
                ]
            }
        }
    },
    methods: {
        submit() {
            router.put(`/pages/${this.page.id}`, this.form);
        },
        imageUploadHandler(blobInfo, progress) {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                axios.post('/pages/' + this.page.id + '/image', formData)
                    .then(function(response) {
                        resolve(response.data.location);
                    })
                    .catch(function(error) {
                        reject('Ошибка при загрузке изображения: ' + error);
                    });
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.b-form-group {
    margin-top: var(--base-margin);
}
</style>