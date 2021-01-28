<template>
<div>
    <a href="javascript:" @click="modalTask = true">
        <i class="fa fa-flag-o"></i>
        <span class="label-tasks label-danger">19</span>
        <span class="label-cnt label-warning">57</span>
    </a>
    <Modal className="taskModal" v-model="modalTask"  :hasCloseIcon="true" type="drawer-left" :width="1000">
        <div class="task-modal">
            <div class="task-aside">


                <Button :block="true" v-if="!newTask" color="blue" @click="$store.commit('newTask', true)" icon="h-icon-task">Добавить задачу</Button>
                <Button :block="true" v-else color="red" @click="$store.commit('newTask', false)" icon="h-icon-task">Отменить задачу</Button>

                <br/>



                <Tabs :datas="type.tabs" class-name="h-tabs-custom" v-model="type.current"  keyName="key" titleName="title" @change="changeTaskType()"></Tabs>




<!--                <div class="task-menu-item"><a href="javascript:">task-menu</a></div>-->
            </div>
            <div class="task-content">
                <div class="task-header">
<!--                    <div class="task-header-left">
                        <span class="title" v-html="view.currentTitle()"></span>
                    </div>-->
                    <div class="task-header-center">

                        <DateRangePicker v-model="taskPeriod" :format="'YYYY-MM-DD'" @confirm="readTask"/>
                        <SwitchList  v-model="taskStatusCurrent" :datas="taskStatus" @change="readTask"/>
                    </div>
                    <div class="task-header-right">
                        <Tabs :datas="view.tabs"  v-model="view.current"  keyName="key" titleName="title" @change="changeTaskView()"></Tabs>
                    </div>
                </div>
<!--Новая задача-->


                    <new-task v-if="newTask" :user="user" :users="h.users" :relation="0"/>


                <div class="task-body">
                    <tasks-view-table :user="user" :users="h.users"/>
                </div>
                <div class="task-footer">task-footer</div>
            </div>
        </div>

    </Modal>
</div>
</template>

<script>

    import NewTask from './Task/NewTask';
    import TasksViewTable from './Task/TasksViewTable';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "TaskTracker",
        components: {
            NewTask,TasksViewTable
        },
        props: ['user'],
        data() {
            return {
                modalTask: false,
                toggle: {
                  newTask:false,
                },
                type: {
                    tabs: [
                        {key: 'all', title: 'Все'},
                        {key: 'implement', title: 'Делаю'},
                        {key: 'assist', title: 'Помогаю'},
                        {key: 'charge', title: 'Поручил'},
                        {key: 'curate', title: 'Курирую'},
                    ],
                    current: 'all'
                },
                view:{
                    tabs: [
                        {key: 'list', title: 'Список'},
                        {key: 'timing', title: 'Сроки'},
                        {key: 'plan', title: 'Мой план'},
                        {key: 'calendar', title: 'Календарь'},
                        {key: 'gant', title: 'Гант'},
                    ],
                    current: 'list',
                    currentTitle: function () {
                        let current = this.tabs.find(o => o.key === this.current);

                        return current.title;

                    },//this.view.tabs.find(t => this.view.tabs.key == this.view.current)
                },
            };
        },
        computed:{
            taskStatusCurrent:{
                get(){return this.$store.getters.taskStatusCurrent},
                set(value){this.$store.commit('TASK_STATUS',value)},
            },
            taskPeriod:{
                get(){return this.$store.getters.taskPeriod},
                set(value){this.$store.commit('TASK_PERIOD',value)},
            },
            ...mapGetters(['h','tasks','newTask','taskStatus']),
        },
        methods: {
            ...mapMutations([]),
            ...mapActions(['readTask']),
            changeTaskType() {
                console.log(this.type.current);
            },
            changeTaskView() {
                console.log(this.view.current);
            },
            sendNotification(title, options) {
// Проверим, поддерживает ли браузер HTML5 Notifications
                if (!("Notification" in window)) {
                    alert('Ваш браузер не поддерживает HTML Notifications, его необходимо обновить.');
                }

// Проверим, есть ли права на отправку уведомлений
                else if (Notification.permission === "granted") {
// Если права есть, отправим уведомление
                    var notification = new Notification(title, options);

                    function clickFunc() {
                        alert('Пользователь кликнул на уведомление');
                    }

                    notification.onclick = clickFunc;
                }

// Если прав нет, пытаемся их получить
                else if (Notification.permission !== 'denied') {

                    Notification.requestPermission(function (permission) {
// Если права успешно получены, отправляем уведомление
                        if (permission === "granted") {
                            var notification = new Notification(title, options);

                        } else {
                            alert('Вы запретили показывать уведомления'); // Юзер отклонил наш запрос на показ уведомлений
                        }
                    });
                } else {
                    Notification.requestPermission(function(permission){
// переменная permission содержит результат запроса
                        console.log('Результат запроса прав:', permission);
                    });
// Пользователь ранее отклонил наш запрос на показ уведомлений
// В этом месте мы можем, но не будем его беспокоить. Уважайте решения своих пользователей.
                }
            },
            makeNotice(event){
                let arr = Array.of(...(Object.entries(event.result.to)).map(item => item[1]))
                if(arr.indexOf(this.user) !== -1){
                    this.$Notice({
                        timeout: 0,
                        icon: 'h-icon-bell',
                        title: event.result.history.action,
                        content: event.result.notice,
                        buttons: [
                            {
                                name: 'Смотреть',
                                color: 'primary',
                                type: 'confirm'
                            }
                        ],
                        events: {
                            confirm: e => {
                                this.modalTask = true;
                                this.$store.commit('TASK_EXPAND',event.result.task.id);
                                this.$Message('Go and take a look');
                                e.close();
                            },
                        }
                    });
                }
                if(event.result.task.director === this.user || arr.indexOf(this.user) !== -1){
                    this.$store.commit(event.result.commit, event.result.payload);
                }
            },
        },

        created(){
            this.$store.dispatch('fetchHelpers');
            Echo.join('task')
                .here(users => {
                    this.users = users;
                })
                .joining(user => {
                    this.users.push(user);
                })
                .leaving(user => {
                    this.users = this.users.filter(u => u.id !== user.id);
                })
                .listenForWhisper('typing', ({id, name}) => {
                    this.users.forEach((user, index) => {
                        if (user.id === id) {
                            user.typing = true;
                            this.$set(this.users, index, user);
                        }
                    });
                })
                .listen('TaskEvent', (event) => {
                    console.log('event',event);
                    this.makeNotice(event);





                    // this.$store.commit('UPDATE_MESSAGES', {user:this.user,row:event.row} )
                    // event.row.to_users.map(m => {
                    //     m == this.user ? this.playSound() : '';
                    // })
                    //
                    //
                    //
                    // this.users.forEach((user, index) => {
                    //     if (user.id === event.user.id) {
                    //         user.typing = false;
                    //         this.$set(this.users, index, user);
                    //     }
                    // });
                });
        },
        mounted() {
            this.$store.dispatch('fetchHelpers');
            this.$store.dispatch('readTask');
        }

    }
</script>
