<template>
    <div class="task-new">
        <div class="task-new-header">
            <!--Новая задача-->
            <div class="task-new-header_title">
                <div>
                    <input type="text" v-model="newTask.task.name" placeholder="Введите название задачи"/>
                </div>
                <div>
                    <span>приоритет:</span>
                    <SwitchList v-model="newTask.task.priority" :datas="priorityData"/>
                </div>

            </div>
        </div>
        <div class="task-new-body">
            <div class="task-new-body_desc">
                <div class="task-new-body_descLeft">
                    <textarea v-model="newTask.task.description" placeholder="Описание задачи"></textarea>
                    <div class="task-new-body_descLeftBtns">
                        <file-upload
                                class="h-btn h-btn-circle h-btn-icon-circle"
                                post-action="/attachtask"
                                :multiple="true"
                                :drop="true"
                                :drop-directory="true"
                                v-model="files"
                                ref="upload"
                        >
                            <i class="h-icon-plus" v-on:input-file="checkFiles"></i>
                        </file-upload>
                        <Button :icon-circle="true"  icon="h-icon-upload" @click.prevent="$refs.upload.active = true"></Button>
                        <Button :icon-circle="true"  icon="h-icon-complete" @click="addCheck()"></Button>
                    </div>
                </div>
                <div class="task-new-body_descMiddle"></div>
                <div class="task-new-body_descRight"
                     :class="$refs.upload && $refs.upload.dropActive ? 'active':''">
                    <span class="h-icon-task"></span>
                    <table  v-if="files.length">
                        <tbody>
                        <tr v-for="(file, index) in files" :key="file.id">
                            <td class="name"><span>{{file.name}}</span></td>
                            <td class="space"></td>
                            <td class="status">
                                <!--                            <span class="h-tag">{{file.size}}</span>-->
                                <span class="h-tag h-tag-red" v-if="file.error">{{file.error}}</span>
                                <span class="h-tag h-tag-green" v-else-if="file.success">загружен</span>
                                <span class="h-tag h-tag-orange" v-else-if="file.active">загружается</span>
                                <span class="h-tag h-tag-orange" v-else-if="file.active">загружается</span>
                                <span class="h-tag h-tag-red" v-else> загрузите </span>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                </div>


            </div>

            <div class="task-new-body_check" v-for="(check,key) in newTask.checks">
               <span class="check-nr">{{key+1}}</span>
                <input type="text" v-model="newTask.checks[key].text"/>
                <Button color="gray" :icon-circle="true" icon="h-icon-trash" @click="removeCheck(key)"/>
            </div>
            <div class="task-new-staff">
                <div class="task-new-staff_item" v-if="users">
                    <span class="task-new-staff_itemName">Постановщик</span>
                    <div class="task-new-staff_itemSelect">
                        <input type="text" v-model="users[user].name" disabled>
                    </div>
                </div>
                <div class="task-new-staff_item">
                    <span class="task-new-staff_itemName">Исполнители</span>
                    <div class="task-new-staff_itemSelect">
                            <Select v-if="users" v-model="staff.implementer"
                                    :datas="activeUsers" keyName="id" titleName="name" :multiple="true"></Select>
                    </div>
                    <div class="task-new-staff_itemBtns">
                        <Button noBorder v-if="!staff.assist.length"
                                @click="toggle.assist = !toggle.assist">Соисполнители</Button>
                        <Button noBorder v-if="!staff.curator.length"
                                @click="toggle.curator = !toggle.curator">Кураторы</Button>
                    </div>
                </div>

                <div class="task-new-staff_item" v-if="toggle.assist || staff.assist.length">
                    <span class="task-new-staff_itemName">Соисполнители</span>
                    <div class="task-new-staff_itemSelect">
                        <Select v-if="users" v-model="staff.assist" :datas="users" keyName="id" titleName="name" :multiple="true"></Select>
                    </div>

                </div>
                <div class="task-new-staff_item" v-if="toggle.curator || staff.curator.length">
                    <span class="task-new-staff_itemName">Кураторы</span>
                    <div class="task-new-staff_itemSelect">
                        <Select v-if="users" v-model="staff.curator" :datas="users" keyName="id" titleName="name" :multiple="true"></Select>
                    </div>

                </div>
                <div class="task-new-staff_item">
                    <span class="task-new-staff_itemName">Начать с</span>
                    <div class="task-new-staff_itemSelected">
                        <DatePicker v-model="newTask.task.start_time" type="datetime" format="YYYY-MM-DD HH:mm:ss"></DatePicker>
                    </div>
                </div>
                <div class="task-new-staff_item">
                    <span class="task-new-staff_itemName">Крайний срок</span>
                    <div class="task-new-staff_itemSelected">
                        <DatePicker v-model="newTask.task.deadline" type="datetime" format="YYYY-MM-DD HH:mm:ss"></DatePicker>
                    </div>
                </div>
            </div>

        </div>
        <div class="task-new-footer">
            <Button color="primary" icon="h-icon-left" @click="createTask()">Поставить задачу</Button>
        </div>
    </div>


</template>

<script>
    import FileUpload from 'vue-upload-component';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "NewTask",
        props: ['user','users','relation'],
        components:{
            FileUpload
        },
        data(){
            return {
                newTask: {
                    task: {
                        parent: this.$props.relation,
                        name: null,
                        description: null,
                        priority:'min',
                        director: this.$props.user,
                        start_time: moment().format('YYYY-MM-DD HH:mm:ss'),
                        deadline: null,
                        status: 0
                    },
                    files: [],
                    staff:[],
                    checks: [],
                    history:{
                        subject:'task',
                        action: 'Создана задача'
                    }
                },
                files: [],
                staff: {
                    implementer: [],
                    assist: [],
                    curator: [],
                },
                toggle: {
                    assist: false,
                    curator: false,
                }
            }
        },
        computed: {
            activeUsers(){
                let res = {}
                for (let u in this.users)
                    if (this.users.hasOwnProperty(u) && this.users[u].active) {
                        res[u] = this.users[u]
                    }
                return res;
            },
            ...mapGetters(['priorityData'])
        },
        methods:{
            test(){
                console.log('sda', this.$props.users);
                this.$props.users.filter = function( obj, predicate) {
                    console.log('obj',obj);
                    console.log('predicate',predicate);
                    let result = {}, key;
                    for (key in obj) {

                        if (obj.hasOwnProperty(key) && !predicate(obj[key])) {
                            result[key] = obj[key];
                        }
                    }
                    return result;
                };
            },
            addCheck(){
                this.newTask.checks.push({text:''});
            },
            removeCheck(key) {
                this.$set(this.newTask, 'checks', this.newTask.checks.filter((o,k) => k !== key));
            },
            checkChecks(){
                this.$set(this.newTask, 'checks', this.newTask.checks.filter((o,k) => o.text !== ''));
            },
            formatStaff(){
                let staff = [];
                for (let s in this.staff){
                    for(let i of this.staff[s]){
                        staff.push( {user_id:i,role:s});
                    }
                }
                this.$set(this.newTask, 'staff', staff);
            },
            checkFiles() {
                if (this.files.length) {
                    let arr = this.files.map(o => {
                        if (Object.keys(o.response).length) {
                            return {
                                link:o.response.url,
                                ext:o.response.fileName.split(',')[1],

                            }
                        }
                    });
                    console.log('arr',arr);
                    this.$set(this.newTask, 'files', arr)
                } else {
                    this.$set(this.newTask, 'files', []);
                }
            },
            async createTask(){
                await this.checkFiles();
                await this.formatStaff();
                await this.checkChecks();
                this.$store.dispatch('createTask',this.newTask);
            }
        }
    }
</script>
