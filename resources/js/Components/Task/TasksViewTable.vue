<template>
    <div>
        <Modal class="transfer-modal" v-model="staffChanger.popup"  :hasCloseIcon="true">
            <Transfer v-model="staffChanger.model" :datas="staffChanger.datas"  keyName="id" v-on:transfer="staffChangerTransfer()">
                <template slot="sourceHeader">
                    <div class="h-transfer-header">Все пользователи</div>
                </template>
                <template slot="targetHeader">
                    <div class="h-transfer-header" v-if="staffChanger.role">{{staffData[staffChanger.role]}}</div>
                </template>
            </Transfer>
            <Row :space="20" v-height="50" type="flex" justify="end">
                <Cell>
                    <Button color="primary" icon="h-icon-left" @click="staffChanger.popup = false">Отмена</Button>
                    <Button color="primary" icon="h-icon-check" @click="updateStuff()">Сохранить?</Button>
                </Cell>
            </Row>


        </Modal>

        <Table :datas="tasks" stripe checkbox @select="selectCheckboxes">

            <TableItem title="id" prop="id" :width="30"></TableItem>
            <TableItem title="Задача" prop="name" sort="auto"></TableItem>
            <TableItem title="Открыта" prop="start_time" sort="auto"></TableItem>
            <TableItem title="Завершена?" :width="100" align="center">
                <template slot-scope="{data}" >
                    <h-switch v-model="data.status" :disabled="data.director !== user" :small="true"/>
                </template>
            </TableItem>

            <TableItem title="Крайний срок" prop="deadline" sort="auto" :render="invisible">
                <template slot-scope="{data}" >
                    <div v-html="checkDate(data.deadline)"></div>
                </template>
            </TableItem>
            <TableItem title="Постановщик" sort="auto">
                <template v-if="users" slot-scope="{data}">
                    {{users[data.director].name}}
                </template>
            </TableItem>
            <TableItem title="Исполнители">
                <template v-if="users" slot-scope="{data}" >

                    <Button size="xs"    v-if="user === data.director && !showUserNames(data,'implementer').length" @click="changeStaff('implementer',data)">+</Button>

                    <template v-for="name in showUserNames(data,'implementer')">
                        <a href="javascript:" class="d-block" v-if="data.director === user" @click="changeStaff('implementer',data)">{{name}}</a>
                        <span v-else class="d-block">{{name}}</span>
                    </template>
                </template>
            </TableItem>
            <TableItem title="Соисполнители">
                <template v-if="users" slot-scope="{data}">

                    <Button size="xs"    v-if="user === data.director && !showUserNames(data,'assist').length" @click="changeStaff('assist',data)">+</Button>

                    <template v-for="name in showUserNames(data,'assist')">
                        <a href="javascript:" class="d-block" v-if="data.director === user" @click="changeStaff('assist',data)">{{name}}</a>
                        <span v-else class="d-block">{{name}}</span>
                    </template>
                </template>
            </TableItem>
            <TableItem title="Кураторы">
                <template v-if="users" slot-scope="{data}">

                    <Button size="xs"    v-if="user === data.director && !showUserNames(data,'curator').length" @click="changeStaff('curator',data)">+</Button>

                    <template v-for="name in showUserNames(data,'curator')">
                        <a href="javascript:" class="d-block" v-if="data.director === user" @click="changeStaff('curator',data)">{{name}}</a>
                        <span v-else class="d-block">{{name}}</span>
                    </template>
                </template>
            </TableItem>

            <div class="task-table-inner" slot="expand" slot-scope="{index, data}">

                <Form>
                    <FormItem label="Описание задачи" :single="true">
                        <textarea rows="3" v-autosize  v-model="data.description" :disabled="data.director !== user"></textarea>
                    </FormItem>

                    <FormItem v-if="data.checks.length" label="Чек лист" :single="true">
                            <table class="task-table-checks">

                                <thead>
                                <tr>
                                    <th>Сделал</th>
                                    <th><template v-if="data.director === user">Проверил</template></th>
                                    <th>Подзадача</th>
                                </tr>
                                </thead>
                                <tbody>
                                <template v-for="check in data.checks">
                                <tr>
                                    <td><h-switch v-model="check.checked" :disabled="!checkUserImplementer(data.staff)" :small="true"/></td>
                                    <td><h-switch  v-if="data.director === user" v-model="check.proof" :disabled="!check.checked" :small="true"/></td>
                                    <td><input type="text" v-model="check.text" :disabled="data.director !== user"></td>
                                </tr>
                                </template>
                                </tbody>

                            </table>

                    </FormItem>
<!--&& data.director !== user-->



                </Form>

                <Tabs :datas="bottomTabs.datas" class-name="h-tabs-card" v-model="bottomTabs.current"></Tabs>

                <div class="task-table-tab" v-if="bottomTabs.current === 'comments'">
<!--COMMENTS-->
                    <template v-for="comment in data.comments" v-if="comment.parent === 0">

                        <TaskViewTableComment :comment="comment" :user="user" :users="users"/>
                        <template v-for="childComment in data.comments" v-if="childComment.parent === comment.id">
                            <TaskViewTableComment :comment="childComment" :user="user" :users="users"/>
                        </template>
                    </template>

                    <a href="javascript:" class="task-table-commentNew"
                       v-if="bottomTabs.commentToggle === false"
                       @click="commentToggle(data.id,0)"
                    >Комментарий</a>

                    <textarea v-model="bottomTabs.comment.comment" v-if="bottomTabs.comment.parent === 0" placeholder="Комментарий"/>

                    <Button size="s" v-if="bottomTabs.comment.comment.length && bottomTabs.comment.parent === 0" color="orange" icon="h-icon-left"  @click="commentToggle(null,null)">Отмена</Button>
                    <Button size="s" v-if="bottomTabs.comment.comment.length && bottomTabs.comment.parent === 0" color="primary" icon="h-icon-check"
                            @click="$store.dispatch('commentCreate',bottomTabs.comment)">Отправить</Button>

                </div>
                <div class="task-table-tab" v-if="bottomTabs.current === 'history'">
<!--HISTORY-->
                    <div class="task-table-history"  v-for="history in data.history">
                        <span>{{history.created_at}}</span>
                        <span>{{history.action}}</span>
                    </div>
                </div>

                <Loading :loading="data.loading"></Loading>
            </div>

        </Table>
    </div>

</template>

<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import TaskViewTableComment from "./TaskViewTableComment";
    export default {
        name: "TasksViewTable",
        props: ['user','users'],
        data(){
            return {
                staffChanger: {
                    task_id: '',
                    popup: false,
                    model: [],
                    datas: [],
                    role: ''
                },
                bottomTabs: {
                    datas: {comments:'Комментарии', history:'История'},
                    current: 'comments',
                    commentToggle: false,
                    comment: {
                        parent: null,
                        comment: '',
                    },
                }

            }
        },
        components:{TaskViewTableComment},
        watch: {},
        computed:{
            ...mapGetters(['tasks','staffData']),
        },
        methods: {
            commentToggle(taskID,parentID){
                this.bottomTabs.commentToggle = !this.bottomTabs.commentToggle;
                this.$set(this.bottomTabs, 'comment', {
                    parent:  this.bottomTabs.commentToggle ? parentID : null,
                    comment: '',
                    task_id:  this.bottomTabs.commentToggle ? taskID : null,
                    user: this.user
                })
            },
           async sendComment(){
               await this.$store.dispatch('commentCreate',this.bottomTabs.comment);
               await this.$set(this.bottomTabs, 'comment', {
                    parent: 0,
                    comment: '',
                    task_id: null,
                    user: this.user
                })
            },
            updateStuff(){
                this.staffChanger.popup = false;
                this.$store.dispatch('updateStuff',{
                    task_id: this.staffChanger.task_id,
                    model: this.staffChanger.model,
                    role: this.staffChanger.role,
                });
/*                this.staffChanger = {
                    task_id: '',
                    popup: false,
                    model: [],
                    datas: [],
                    role: ''
                }*/
            },
            open(data) {
                console.log(data);
                this.$set(data, '_expand', !data._expand);
            },
            showUserNames(data,role){
                return data.staff.map(o => {
                    if(o.role === role){
                        return this.users[o.user_id].name
                    }
                })
            },
            checkDate(datetime){
                let a = moment(datetime);
                let b = moment().format('YYYY-MM-DD HH:mm:ss');
                let c = a.diff(b, 'hour');
                let cl = '';
                if(c >= 0 && c <= 48){
                    cl = 'yellow';
                } else if (c < 0){
                    cl = 'red';
                } else {
                    cl = 'green';
                }
                let e = Math.abs(c);
                let days = Math.trunc(e/24);
                let hours = e - (days*24);
                return `<span class="h-tag h-tag-${cl}" style="white-space:nowrap">${moment(datetime).format('YYYY-MM-DD HH')} : ${days} д. ${hours} ч</span>`;
            },
            // userRole(staff){
            //
            // },
            checkUserImplementer(staff){
                let arUser =  staff.filter(u => u.user_id === this.user);
                return  arUser.length > 0
            },
            selectCheckboxes(data){
                let ids = data.map(m => m.id)
                this.tasks.forEach(o => { o._expand = ids.indexOf(o.id) !== -1})
            },
            invisible(){
                return '';
            },
            async changeStaff(staffType,data){
                let res = [];
                for (let f in this.users)
                    if (this.users.hasOwnProperty(f)) { //  && this.users[f].active
                       await res.push({
                            id: Number(f),
                            text: this.users[f].name,
                        });
                    }
                await this.$set(this.$data.staffChanger,'datas',res);
                await this.$set(this.$data.staffChanger,'model', data.staff.filter(f => f['role'] === staffType).map(f => Number(f.user_id)));
                await this.$set(this.$data.staffChanger,'task_id', data.id);
                await this.$set(this.$data.staffChanger,'role', staffType);
                await this.$set(this.$data.staffChanger,'popup', true);
            },
            staffChangerTransfer(date){
                console.log('model',date);

            },

            treeView(data){
                let tree = [];
                for(let t of data){
                    if(t.parent === 0){
                        t['children'] = []
                        tree.push(t);
                    } else {
                        let index = tree.findIndex(i => i.id == t.parent);
                        tree[index].push(t);
                    }
                }
            }
        },
        created(){
            this.$on('transfer', function (payload) {
                console.log('payload', payload);
            })
        }
    }
</script>

<style lang="scss">
    .transfer-modal {
        z-index: 999999999999999999;
    }
    .h-transfer {
        width: 620px;
        margin-bottom: 10px;
    }
    /*.h-table-tr-selected {}*/
    .h-table-expand-tr {
        background: rgba(69, 185, 132, 0.15);
    }


</style>
