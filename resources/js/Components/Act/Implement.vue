<template>
    <div>

        <template>
            <Cell width="2">

                <DatePicker v-if="implement.master" v-model="implement.date" placeholder="Дата обработки" :no-border="true"  @change="changeDate(implement)"/>
                <DatePicker v-else v-model="implement.date" placeholder="Дата обработки" :no-border="true"  @change="setMaster(implement)"/>
            </Cell>
            <Cell width="2">
                <template v-if="implement.master"> {{h.users[implement.master].name}}</template>
            </Cell>
            <Cell width="2" >{{implement.start_date}}</Cell>
            <Cell width="2">{{implement.end_date}}</Cell>
            <Cell width="1"><Button @click="confirmDelete = true">-</Button></Cell>
            <!--confRemove = true,reason = false-->
        </template>
<!--DELETING-->
        <Modal v-model="confirmDelete" :middle="true" className="prompt-deleting">
            <header class="h-modal-header">Причины удаления</header>

            <template v-for="del in implementDeleteReasons">
                <Radio v-model="implement.reason" :value="del.id" > {{del.name}} </Radio>
                <br/>
            </template>
            <textarea v-model="implement.note"/>

            <footer class="h-modal-footer">
                <button class="h-btn h-btn-warn" @click="canceling('confirmDelete')">Отменить</button>
                <button v-if="implement.reason" @click="deleteImplement(implement)" class="h-btn h-btn-blue" >Удалить</button>
            </footer>

        </Modal>
<!--CHANGING-->
        <Modal v-model="confirmChange" :middle="true" className="prompt-deleting">
            <header class="h-modal-header">Причины изменения</header>

            <template v-for="change in implementChangeReasons">
                <Radio v-model="implement.reason" :value="change.id" > {{change.name}} </Radio>
                <br/>
            </template>
            <textarea  v-model="implement.note"/>

            <footer class="h-modal-footer">
                <button class="h-btn h-btn-warn" @click="canceling('confirmChange')">Отменить</button>
                <button v-if="implement.reason" @click="updateImplement(implement)" class="h-btn h-btn-blue" >Изменить</button>
            </footer>

        </Modal>


    </div>
</template>

<script>
    import {mapActions, mapGetters} from 'vuex';
    import {bus} from "../../bus";


    export default {
        name: "Implements",
        props:['implement'],
        data(){
            return {
                confirmDelete: false,
                confirmChange: false,
                oldDate:'',
                table: {
                    id: '',
                    parent: '',
                    lid_id: '',
                    date: '',
                    master: '',
                    start_date: '',
                    end_date: '',
                    user: '',
                    created_at: '',
                    updated_at: '',
                    note: '',
                    reason: '',
                },

            }
        },
        computed:{
            ...mapGetters(['h','mapActs','implementDeleteReasons','implementChangeReasons']),
        },
        methods:{
            ...mapActions(['setMaster','removeMapImplement','deleteImplement','updateImplement']),
            changeDate(implement) {
                if (implement.master)
                    this.confirmChange = true;
            },
/*            cancelChange(){
                this.confirmChange = false;
                this.implement.date = this.oldDate;
            },*/
            canceling(confirmation){
                this.$set(this.$data,confirmation,false);
                this.implement.note = '';
                this.implement.reason = '';
                this.implement.date = this.oldDate;
            },
            createImplement(){

            },
        },

        mounted() {
            this.$set(this.$data,'oldDate',this.implement.date)
        }
    }
</script>

<style lang="scss">
    textarea {
        width: 100%;
        text-align: left;
        resize: vertical;
    }
    .prompt-deleting .h-notify-container {
        width: auto!important;
        top: 300px!important;
    }

</style>


