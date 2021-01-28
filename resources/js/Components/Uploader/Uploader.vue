<template>
    <div class="example-simple">

        <div class="upload">
            <ul>
                <li v-for="(file, index) in files" :key="file.id">
                    <span class="h-tag">{{file.name}}</span> -
                    <span class="h-tag h-tag-red" v-if="file.error">{{file.error}}</span>
                    <span class="h-tag h-tag-green" v-else-if="file.success">загружен</span>
                    <span class="h-tag h-tag-orange" v-else-if="file.active">загружается</span>
                    <span class="h-tag h-tag-orange" v-else-if="file.active">загружается</span>
                    <span class="h-tag" v-else></span>
                </li>
            </ul>
            <div class="example-btn">
                <file-upload
                        class="btn btn-primary"
                        post-action="/attachtask"
                        extensions="gif,jpg,jpeg,png,webp,svg"
                        accept="image/png,image/gif,image/jpeg,image/webp,image/svg"
                        :multiple="true"
                        :size="1024 * 1024 * 10"
                        v-model="files"
                        @input-filter="inputFilter()"
                        @input-file="inputFile()"
                        ref="upload">
                    <i class="fa fa-plus"></i>
                    Выбрать
                </file-upload>
                <button type="button" class="btn btn-success" v-if="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true">
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                    Загрузить
                </button>
                <button type="button" class="btn btn-danger"  v-else @click.prevent="$refs.upload.active = false">
                    <i class="fa fa-stop" aria-hidden="true"></i>
                    Загрузить
                </button>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
    .example-simple label.btn {
        margin-bottom: 0;
        margin-right: 1rem;
    }
</style>


<script>
    import FileUpload from 'vue-upload-component'

    export default {
        components: {
            FileUpload,
        },
        data() {
            return {
                files: [],
            }
        },

        methods: {
            inputFilter(newFile, oldFile, prevent) {
                if (newFile && !oldFile) {
                    // Before adding a file
                    // Filter system files or hide files
                    if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
                        return prevent()
                    }
                    // Filter php html js file
                    if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
                        return prevent()
                    }
                }
            },

            inputFile(newFile, oldFile) {
                // if (newFile && !oldFile) {
                //     // add
                //     console.log('add', newFile)
                // }
                // if (newFile && oldFile) {
                //     // update
                //     console.log('update', newFile)
                // }
                //
                // if (!newFile && oldFile) {
                //     // remove
                //     console.log('remove', oldFile)
                // }
            }
        }
    }
</script>
