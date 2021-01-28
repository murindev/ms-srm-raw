<template>
    <div class="Map">

        <act-map/>
        <logistic-docs/>

        <div class="dash-panel">
            <Button><DatePicker v-model="lDate"  :no-border="true" /></Button>
            <Button type="info" v-if="newImplement.end_date" @click="confirmSetMaster">Назначить мастера</Button>
            <AutoComplete
                class="move-right"
                style="width: 400px;"
                :option="autoCompleteOps.search"
                type="object"
                v-model="mapAddress"
                @change="onChangeString"
                placeholder="... вводите адрес"
            />


            <Button class="move-right" @click="aDocModal(true)" color="primary">Документы</Button>
            <Button class="move-right" v-if="masterSchedule" @click="openMasterSchedule"> Расписание</Button>
            <Button class="move-right" type="warn" v-if="mapSwitch" @click="aMapSwitch(false)">Закрыть карту</Button>

        </div>

        <table class="w100" style="height: calc(100vh - 80px);">
            <tbody>
            <tr>
                <td>
                    <div id="map" style="width:100%; height:100%"></div>
                </td>
                <td style="width: 20px"/>
                <td style="width: 520px; vertical-align: top"  >
                    <template v-for="(master,masterID) in masters" v-if="master.active">
                        <div>
                            <a class="master-link"
                               @click="showRoute(masterID)"
                               >
                                {{master.name}}({{master.id}})
                                <span :style="`background: ${master.color}`"/>
                            </a>
                        </div>
                        <implement-day :master="master.id"/>
                    </template>


                </td>
            </tr>
            </tbody>

        </table>
    </div>
</template>

<script>




    import ImplementDay from "./ImplementDay";
    import ActMap from "./ActMap";
    import LogisticDocs from "./LogisticDocs";
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    import Axios from "axios";


    const  loadData = function(filter, callback){
        Axios.post('/dadata/async',{ func:this.orgId, query:filter.replace(/ /,'+') })
            .then(response => {
                let list = [];
                for(let s in response.data){
                    list.push({
                        key: s,
                        title: response.data[s].display_name,
                        obj: response.data[s]
                    });
                }
                callback(list);
            })
    };

    export default {
        name:'DGMap',
        data(){
            return {
                autoCompleteOps: {
                    search: {
                        orgId: 'searchByAddress',
                        loadData,
                        minWord: 5
                    }
                },
                mapAddress:''
            }
        },
        components:{
            ImplementDay,ActMap,LogisticDocs
        },
        computed:{
            lDate: {
                get(){ return this.$store.getters.logisticDate} ,
                set(value) {this.$store.dispatch('fetchLogisticDate', value )}
            },

            ...mapGetters(['DGMap','logisticDate','points','popups','schedule','markers','polylines','mapActs','mapLids','masters','h','newImplement','mapSwitch','foreignImplement','docModal','masterSchedule']),

        },
        methods: {
            ...mapActions(['showRoute','fetchHelpers','fetchMap','fetchLogisticDate','confirmSetMaster','aMapSwitch','showMapObject','aDocModal','printMasterSchedule']),
            async onChangeString(data, trigger) {
                if (trigger && data.value) {
                    this.showMapObject(data.value.obj)
                }
            },
            openMasterSchedule(){
                window.open(this.masterSchedule, '_blank')
            }
        },
        created() {
            this.fetchHelpers();
            this.fetchMap();
            bus.$on('success', function(a){ this.$Notice({type:'success',  title: a.table+': '+a.mess,  content:''}) });
            bus.$on('error', function(a){ this.$Notice({type:'error',  title: a.table+': '+a.mess,  content: a.state}) });
            bus.$on('fix-it',(a) => {
                // console.log(a);
                // let btn = `<Button @click=""></Button>`
                // a.state
                this.$Notice({
                    type:'warn',
                    title: a.table,
                    content: a.mess,
                    timeout:0,
                    buttons: [
                        {
                            name: 'Пропустить',
                            type: 'cancel'
                        },
                        {
                            name: 'Исправить',
                            color: 'primary',
                            type: 'confirm'
                        }
                    ],
                    events: {
                        confirm: e => {
                            bus.$emit('act-map', a.state);
                            this.$store.commit('ACT_MODAL',true);
                            e.close();
                        },
                        cancel: e => {
                            this.$Message('А исправлять придется)). Пусть и позже');
                            e.close();
                        }
                    }
                });
            });
        }
    };
</script>
