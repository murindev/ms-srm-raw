<template>
    <div>
        <a @click="chatWindow = !chatWindow">
            <i class="fa fa-bell-o"></i>
            <span v-if="newMessagesCntTotal" class="label  label-warning">{{newMessagesCntTotal}}</span>
        </a>
            <Modal className="chatModal" v-model="chatWindow" :hasCloseIcon="false">

            <Layout :siderFixed="true" :siderCollapsed="siderCollapsed" class="chatW">

                <div class="h-layout-sider h-layout-sider-theme-dark">
                    <div class="layout-logo"></div>
                    <ul class="h-menu h-menu-dark h-menu-mode-normal" style="margin-top: 20px;" v-if="h.users">
                        <li class="h-menu-li"
                            v-for="u in menuDatas(h.users)"
                            @click="chatMembers(u.key)"
                            :class="addressee.indexOf(u.key) !== -1 ? 'h-menu-li-selected' : ''"
                        >

                            <div :content="u.title" placement="left" class="h-menu-show">
                                <span class="h-menu-show-icon">
                                    <em :class="u.here ? 'active' : ''">{{u.icon()}}</em>
                                    <sup v-if="newMessages(u.key) !== 0" class="h-badge-count h-badge-count-show" >{{newMessages(u.key)}}</sup>
                                </span>
                                <span class="h-menu-show-desc">{{u.title}}</span>
                            </div>

                        </li>
                    </ul>

                    <p style="color: white; text-align: center;">ГРУППЫ</p>

                    <ul class="h-menu h-menu-dark h-menu-mode-normal" style="margin-top: 20px;" v-if="h.users">
                        <li class="h-menu-li group" v-for="(group,key) in groups(messages)" @click="chatGroupMembers(group)">
<!-- :class="addressee.indexOf(user.key) !== -1 ? 'h-menu-li-selected' : ''"-->
                            <div placement="left" class="h-menu-show">
                                <span class="h-menu-show-desc" style="position: relative">
                                    <sup v-if="Object.keys(newMessagesCnt).indexOf(key) !== -1 && newMessagesCnt[key] !== 0"
                                            class="h-badge-count h-badge-count-show">
                                        {{newMessagesCnt[key]}}
                                    </sup>
                                    <span class="h-menu-show-icon" v-for="user in group">
                                        <em :class="user.here ? 'active' : ''">{{user.icon}}</em>
                                    </span>
                                </span>
                            </div>

                        </li>
                    </ul>

                </div>
                <Layout :headerFixed="true">

                    <Content>
                        <HHeader theme="white">
                            <div>
                                <Button :icon=" siderCollapsed ? 'h-icon-right' : 'h-icon-left' " noBorder
                                        style="font-size: 20px"
                                        @click="siderCollapsed=!siderCollapsed"></Button>
                            </div>
                            <div class="currentUser" v-if="addressee.length && h.users">
                                <template v-for="u in addressee">
                                    <span class="h-tag h-tag-bg-blue" v-if="u == user"> Вы </span>
                                    <span class="h-tag h-tag-bg-green" v-else>
                                        {{h.users[Number(u)].name}}
                                    </span>
                                </template>
                            </div>
                            <Button
                                    :color="conferencing ?'red':''"
                                    icon="h-icon-users"
                                    @click="makeConferention()"
                            >
                                Конференция
                            </Button>
                            <div class="userFromList">
                                <Select v-if="h.users" v-model="allUsers.model" :datas="allUsers.datas(h.users)"
                                        keyName="id" titleName="name"/>
                            </div>
                        </HHeader>
                        <ul class="messages" v-chat-scroll="{always: false, smooth: true}" v-if="messages && h.users">

                            <template v-for="(message,key) in messages[addressee.join('::')]">

                                    <li class="message">
                                        <div :class="message.from_user === user ? 'to' : 'from'">
                                            {{message.message}}
                                            <div class="message_footer" v-if="h.users && message.from_user">
                                                <span>{{h.users[message.from_user].name}}</span>
                                                <span>{{message.created_at}}</span>
                                            </div>
                                        </div>
                                    </li>

                            </template>

                        </ul>


                        <HFooter>
                            <textarea
                                    v-model="newMessage"
                                    @keyup.enter="sendMessage"
                                    @keyup="sendTypingEvent"
                            ></textarea>
                            <!--                            <Button color="primary" icon="h-icon-outbox"></Button>-->
                            <!--@mouseenter="autosize" @input="autosize" ref="messBox"-->
                        </HFooter>
                    </Content>
                </Layout>

            </Layout>

        </Modal>
    </div>

</template>

<script>

    import ChatForm from './ChatForm';
    import ChatMessages from './ChatMessages';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";


    export default {
        name: "Messenger",
        props: ['user'],
        data() {
            return {
                // users
                users: [],
                // groups:[],
                allUsers: {
                    model: '',
                    datas: function (users) {
                        let arr = [];
                        for (let key in users) if (users[key].active && Number(key) !== this.user) {
                            arr.push(users[key]);
                        }
                        return arr;
                    }
                },
                userFromList: {
                    model: '',
                },
                //
                newMessage: '',

                chatWindow: false,

                // messages: [],


                siderCollapsed: false,
                menuDatas: function (users) {
                    let arr = [];
                    for (let key in users) if (users[key].active && Number(key) !== this.user) {
                        arr.push({
                            count: key,
                            title: users[key].name,
                            key: users[key].id,
                            icon: function () {
                                let initialsArr = users[key].name.replace(/\s+/g, ' ').trim().split(' ');
                                let initials = '';
                                for (let i in initialsArr) {
                                    initials += initialsArr[i].charAt(0);
                                }
                                return initials.toUpperCase();
                            },
                            here: this.users.find((u) => Number(u.id) === Number(key)) ? true : false

                        });
                    }
                    return arr;
                },
                datas: [
                    {icon: 'h-icon-home'},
                    {
                        title: 'Component',
                        icon: 'h-icon-complete',
                        route: {name: 'Component'}
                    },
                    {title: 'Breadcrumb', icon: 'h-icon-star'}
                ],
                groups: function (messages){
                    let list = {};
                    for(let g in messages) {
                        let groupIds = g.split('::');
                        if (groupIds.length > 2) {
                            let group = {}
                            for (let id of groupIds) {
                                group[id] = {
                                    name: this.h.users[id].name,
                                    icon: this.icon(this.h.users[id].name),
                                    here: this.users.find(u => u.id == id) ? true : false
                                }
                            }
                            list[g] = group;
                        }

                    }

                    return list
                },



            }
        },
        computed: {
            ...mapGetters(['h', 'conferencing','addressee','messages','newMessagesCnt','newMessagesCntTotal','currentGroup'])
        },
        components: {
            ChatMessages, ChatForm
        },
        watch: {
            siderFixed() {
                if (!this.siderFixed) {
                    this.headerFixed = false;
                }
            },
            chatWindow(val){
                if(val && Object.keys(this.newMessagesCnt).indexOf(this.currentGroup) !== -1 && this.newMessagesCnt[this.currentGroup] > 0) {
                    this.nullMessages()
                }
            },
            currentGroup(val){
                if(this.chatWindow && Object.keys(this.newMessagesCnt).indexOf(this.currentGroup) !== -1 && this.newMessagesCnt[this.currentGroup] > 0){
                    this.nullMessages()
                }
            }

        },
        async mounted() {
           await this.$store.dispatch('fetchHelpers');
           await this.$store.dispatch('getAddressee');
           // await
        },
        created() {
            this.$store.dispatch('fetchMessages',{
                func:'fetch',
                from:this.user
            });

            Echo.join('chat')
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
                .listen('MessageSent', (event) => {



                    this.$store.commit('UPDATE_MESSAGES', {user:this.user,row:event.row} )
                    event.row.to_users.map(m => {
                        m == this.user ? this.playSound() : '';
                    })



                    this.users.forEach((user, index) => {
                        if (user.id === event.user.id) {
                            user.typing = false;
                            this.$set(this.users, index, user);
                        }
                    });
                });
        },


        methods: {

            nullMessages(){
                let arrId = [];
                for(let message of this.messages[this.currentGroup]){
                    for (let user in message.addressee)
                        if (user == this.user && message.addressee[user].seen == 1) {
                            arrId.push(message.addressee[user].id);
                        }
                }
                this.$store.dispatch('messageSeen',arrId)
            },


            newMessages(userKey) {
                let groupkey = [userKey,this.user].sort();
                if(Object.keys(this.newMessagesCnt).indexOf(groupkey.join('::')) !== -1) {
                    return  this.newMessagesCnt[groupkey.join('::')];
                } else {
                    return 0;
                }


            },

            makeConferention() {
                this.$store.dispatch('setConferencing', !this.conferencing)
                this.$store.dispatch('setAddressee', [])
                // this.addressee = [];
            },
            chatGroupMembers(group){
                this.$store.dispatch('setAddressee', Object.keys(group));
                this.$store.dispatch('setConferencing', true)
            },
            chatMembers(id) {

                let index = this.addressee.indexOf(id);

                if (this.conferencing) {
                    if (index === -1) {
                        this.addressee.push(id);
                        this.$store.dispatch('setAddressee', this.addressee)
                    } else {
                        this.addressee.splice(index, 1);
                        this.$store.dispatch('setAddressee', this.addressee)
                    }
                } else {
                    let addresseeArray = [this.user,id].sort();
                    this.$store.dispatch('setAddressee', addresseeArray)
                }

            },


            sendMessage() {
                // this.messages.push(message);
                if (this.addressee.length) {
                    axios.post('/messages', {
                        user: this.user,
                        message: this.newMessage,
                        from_user: this.user,
                        to_users: this.addressee
                    }).then(response => {
                        console.log(response.data);
                    });
                } else {
                    this.$Message['error']('Выберите по крайней мере одного пользователя')
                }


                // this.$emit('messagesent', {
                //     user: this.user,
                //     message: this.newMessage,
                //     from_user: this.user,
                //     to_users: this.addressee
                // });

                this.newMessage = ''
            },

            icon (name) {
                let initialsArr = name.replace(/\s+/g, ' ').trim().split(' ');
                let initials = '';
                for (let i in initialsArr) {
                    initials += initialsArr[i].charAt(0);
                }
                return initials.toUpperCase();
            },
            sendTypingEvent() {
                Echo.join('chat')
                    .whisper('typing', this.user);
            },

            fetchMessages() {
                axios.get('/messages').then(response => {
                    this.messages = response.data;
                });
            },

            addMessage(message) {
                this.messages.push(message);

                axios.post('/messages', message).then(response => {
                    console.log(response.data);
                });
            },

            autosize() {
                this.$refs.messBox.style.minHeight = this.$refs.messBox.scrollHeight + 'px';
            },
            isVisible(el) {
                let elTop = el.offsetTop;
                let rect = el.getBoundingClientRect();

                return !(elTop <= rect.top || rect.bottom <= 0);
            },
            spyMessage(message){
                if (Object.keys(message.addressee).indexOf(String(this.user)) !== -1){
                 return  message.addressee[this.user].seen
                } else {
                    return 1;
                }
            },
            messageObserver() {
                const options = {
                    root: document.querySelector('.messages'),
                    rootMargin: '0px 0px 0px 0px',
                    threshold: 0.5
                };

                let i = 0;

                const iObserver = new IntersectionObserver((entries) => console.log(`intersection ${i++}`), options);

                iObserver.observe(document.querySelector('#observer'));

            },
            playSound(){
                var sound = new Audio();
                sound.pause();
                sound.currentTime = 0;
                sound.src = '/sounds/aska.mp3';
                sound.play();
/*                function playSound(){

                }*/
            }
        },

    }
</script>

<style lang="scss">
    .message {
        /*background: #2196F3;*/
        transition: background 0.4s;
    }

    .red {
        background: #ff0000;
    }

    .navbar-nav > li > div > a {
        padding-top: 15px;
        padding-bottom: 15px;
        line-height: 20px;
        color: #fff;
    }

    .navbar-nav > li > div > a:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .nav > li > div > a {
        position: relative;
        display: block;
        padding-left: 15px;
        padding-right: 15px;
        /*padding: 10px 15px;*/
    }

    .nav > li > div > a .label {
        position: absolute;
        top: 9px;
        right: 7px;
        text-align: center;
        font-size: 9px;
        padding: 2px 3px;
        line-height: .9;
    }

    .chatModal .h-notify-container {

    }

    .chatW {
        width: 1400px;
    }

    .h-layout.h-layout-header-fixed {
        padding-top: 0 !important;
    }

    .h-layout-header h-layout-header-theme-white {
        max-height: 80px;
    }

    .h-layout-header {
        /*height: 42px!important;*/
        /*line-height: 42!important;*/
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .h-layout-footer {
        height: 100px;
        padding-top: 20px;
    }

    .h-layout-content {
        max-height: calc(100vh - 140px);
    }

    textarea {
        width: 102%;
        height: 100%;
        resize: vertical;
        text-align: left;
        /*display: block;*/
        padding-right: 20px;
        margin: 0 8px;

        /*height: 60px;*/
        background: #dcf8c6;
        border: none;
        /*overflow: hidden;*/
    }

    .userFromList {
        min-width: 170px;
    }

    .currentUser {
        font-weight: bold;
    }


    .h-menu-show-count {
        left: 12px;
    }

    .h-menu-show-desc {
        vertical-align: middle;
    }

    .h-menu-show-icon {
        width: 24px;
        height: 24px;
        display: inline-block;
        position: relative;
        vertical-align: middle;

    }

    .h-menu-show-icon em {
        width: 24px;
        height: 24px;
        font-style: normal;
        font-size: 12px;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
        border: 2px solid white;
        border-radius: 50%;
        background: white;
        color: black;
        position: absolute;
        opacity: .5;
    }

    .h-menu-show-icon em.active {
        opacity: 1;
    }

    .h-badge-count-show {
        position: absolute;
        top: -8px;
        left: -8px;
        z-index: 2;
    }

    .messages {
        background: #e5ddd5;
        padding: 24px;
        max-height: calc(100vh - 300px);
        overflow-y: auto;
        border: 2px solid #d0cdcd;
    }

    .chatModal .h-notify-container {
        top: 50px !important;
        bottom: 50px !important;
    }

    .message {
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 20px;

    }

    .message_footer {
        width: 100%;
        box-sizing: border-box;
        display: flex;
        justify-content: flex-end;
        opacity: 0.45;
        font-size: 11px;

    }
    .message_footer span {
        padding-left: 20px;
    }

    .message_header:after {

    }

    .h-menu-li.group{}
    .h-menu-li.group:hover{
        background: #000;
    }

    .h-menu-li.group.active {
        background: #45b984;
    }
    .from {
        width: 85%;
        margin-right: 15%;
        padding: 10px;
        background: #fff;
        border-radius: 4px;
    }

    .to {
        width: 85%;
        margin-left: 15%;
        padding: 10px;
        background: #dcf8c6;
        border-radius: 4px;
    }

</style>
