<template>
    <div>
        <Modal class="actModal" v-model="modal"  :hasCloseIcon="true">

            <table class="table" style="margin-top: 32px">
                <thead class="head">
                <tr>
                    <th colspan="7"><h4>Печать сопроводительных документов</h4></th>
                </tr>
                </thead>
                <thead class="head">
                <tr>
                    <th class="table-c-m"><Checkbox v-model="allMasters"/></th>
                    <th class="table-c-m">ID</th>
                    <th class="table-c-m">Мастер</th>
                    <th class="table-c-m"><Checkbox v-model="contractMasters"> Договор </Checkbox></th>
                    <th class="table-c-m"><Checkbox v-model="actMasters">Акт</Checkbox></th>
                    <th class="table-c-m">С</th>
                    <th class="table-c-m">По</th>
                </tr>
                </thead>


                <tfoot>
                <tr>
                    <th colspan="7"  class="ta-center" style="padding-top: 32px;"><Button color="blue">На печать</Button></th>
                </tr>
                </tfoot>

                <tbody>
                <tr v-for="master in mastersDocs">
                        <td class="table-c-m"><Checkbox v-model="master.get"/></td>
                        <td class="table-c-m">{{master.id}}</td>
                        <td>{{master.name}}</td>
                        <td class="table-c-m"><Checkbox v-model="master.contract"/></td>
                        <td class="table-c-m"><Checkbox v-model="master.act"/></td>
                        <td class="table-c-m"><DatePicker v-model="master.from" :type="'date'"/></td>
                        <td class="table-c-m"><DatePicker v-model="master.till" :type="'date'"/></td>
                </tr>
                </tbody>
            </table>

        </Modal>
    </div>

</template>

<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";

    export default {
        name: "logisticDocs",
        data() {
            return {
                allMasters: false,
                contractMasters: false,
                actMasters: false,
                mastersDocs: {}
            };
        },
        computed: {
            modal: {
                get(){ return this.$store.getters.docModal} ,
                set(value) {this.$store.commit('DOC_MODAL', value )}
            },
            ...mapGetters(['docModal','masters','h','logisticDate']),
        },
        watch: {
            modal(){
                let masters = {};
                let now = new Date().toISOString().split('T');
                for (let m in this.masters) if(this.masters[m].active) {
                    masters[this.masters[m].id] = {
                        get:false,
                        id: this.masters[m].id,
                        name: this.masters[m].name,
                        contract: false,
                        act: false,
                        from: this.logisticDate,
                        till: this.logisticDate,
                    }
                }
                this.$set(this.$data,'mastersDocs',masters)
            },
            allMasters(){this.toggleMasterDoc('get')},
            contractMasters(){this.toggleMasterDoc('contract')},
            actMasters(){this.toggleMasterDoc('act')},
        },
        methods: {
            ...mapActions(['aDocModal']),

            toggleMasterDoc(field){
                for(let a in this.mastersDocs) if(Object.keys(this.mastersDocs).length){
                    this.mastersDocs[a][field] = !this.mastersDocs[a][field];
                }
            },

        }
    }
</script>
