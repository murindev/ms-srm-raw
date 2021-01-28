<template>
    <div class="container chats">
         <Row :space="10">
             <Cell width="16">
                 <div>
                     <div class="card card-default">
                         <div class="card-header">Chats</div>

                         <div class="card-body" v-if="h.users">
                             <chat-messages :users="h.users" :messages="messages"></chat-messages>
                         </div>
                         <div class="card-footer" v-if="h.users">
                             <chat-form @messagesent="addMessage" :user="manager"></chat-form>
                         </div>
                     </div>
                 </div>
             </Cell>
             <Cell width="8">
                 <div>
                     <ul class="list-group" v-if="h.users">
                         <li class="list-group-item" v-for="user in h.users" v-if="user.active">
                             {{ user.name }} <span v-if="user.typing" class="badge badge-primary">typing...</span>
                         </li>
                     </ul>
                 </div>
             </Cell>
          </Row>

        <div class="row">


        </div>
    </div>
</template>

<script>

    import ChatForm from './ChatForm';
    import ChatMessages from './ChatMessages';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    export default {
        name: "Chat",
        props:['manager','tokens'],
        data() {
            return {
                messages: [],
                users: [],
            }
        },
        computed: {
            ...mapGetters(['h'])
        },
        components: {
            ChatMessages,ChatForm
        },
        mounted() {
            this.$store.dispatch('fetchHelpers');
        },
        created() {
            // this.$store.getters('h');
            this.fetchMessages();

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
                    this.messages.push({
                        message: event.message.message,
                        user: event.user
                    });

                    this.users.forEach((user, index) => {
                        if (user.id === event.user.id) {
                            user.typing = false;
                            this.$set(this.users, index, user);
                        }
                    });
                });
        },


        methods: {
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
            }
        }
    }
</script>


