<template>
    <div>
        <div class="task-table-comment" :class="comment.parent ? 'child': 'parent'">
            <div class="task-table-commentUser">
                <div class="task-table-commentAvatar">
                    <template v-if="users[comment.user].avatar">
                        <img :src="'/storage/'+users[comment.user].avatar" alt="avatar"/>
                    </template>
                    <template v-else>
                                        <span>
                                            {{avatar(comment.user)}}
                                        </span>
                    </template>
                </div>
            </div>
            <div class="task-table-commentComment"
                 :class="comment.hasOwnProperty('isNew') ? 'new' : ''"
                 :id="comment"
            >
                <a class="task-table-commentName" href="javascript:">{{users[comment.user].name}}</a>
                <a class="task-table-commentName"  v-if="!comment.parent" href="javascript:" @click="commentToggle(comment.task_id,comment.id)">Ответить</a>
                <a class="task-table-commentResponse" v-if="bottomTabs.comment.parent === comment.id && bottomTabs.comment.comment.length" @click="sendComment()" href="javascript:">Отправить</a>
                <div>
                    {{comment.comment}}
                </div>
            </div>
        </div>
        <div class="task-table-commentFooter">
            <textarea v-if="bottomTabs.comment.parent === comment.id" v-model="bottomTabs.comment.comment" placeholder="Ответ"/>
        </div>
    </div>
</template>


<script>
    export default {
        name: "TaskViewTableComment",
        props:['comment','users','user'],
        data(){
            return {
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
        methods:{
            avatar(user){
                return this.users[user].name.split(' ').map(o => o.charAt(0).toUpperCase()).join('');
            },
            commentToggle(taskID,parentID){
                this.bottomTabs.commentToggle = !this.bottomTabs.commentToggle;
                this.$set(this.bottomTabs, 'comment', {
                    parent:  this.bottomTabs.commentToggle ? parentID : null,
                    comment: '',
                    task_id:  this.bottomTabs.commentToggle ? taskID : null,
                    user: this.user
                })
            },
            sendComment(){
                this.$store.dispatch('commentCreate',this.bottomTabs.comment);
                this.$set(this.bottomTabs, 'comment', {
                    parent: 0,
                    comment: '',
                    task_id: null,
                    user: this.user
                })
            },
        }
    }
</script>

