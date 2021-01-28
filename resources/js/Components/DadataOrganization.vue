<template>
    <div class="DadataOrganization">

        <Modal v-model="modal"  type="drawer-left" style="width:600px;">
            <table>
                <tr>
                    <td style="vertical-align: top;width:500px;">

                        <table class="h-table w100">
                            <tr>
                                <th colspan="2">
                                    <div class="visible-lg-inline-block">
                                        <AutoComplete
                                            style="width: 260px;"
                                            :option="autoCompleteOps[searchType]"
                                            type="object"
                                            v-model="table.organization"
                                            placeholder="... Организация / Инн / ОГРН"
                                            @change="onChange"
                                        ></AutoComplete>
                                    </div>


                                    <Button @click="searchByField('inn',Number(table.inn))">Проверить</Button>
                                    <Button  v-if="!searchCounts && Object.keys(table).length" color="green" @click="save()">Сохранить</Button>
                                    <Button  v-else-if="customer.id === table.id" color="green" @click="save()">Сохранить</Button>
                                </th>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <Row class="h-row-flex h-row-flex-center" type="flex"  :space-y="10">
                                        <Cell width="8"><Button class="no-br-bg" noBorder @click="searchType = 'organization'" :text-color="searchType === 'organization' ? 'blue' : 'gray'">Название</Button></Cell>
                                        <Cell width="8"><Button class="no-br-bg" noBorder @click="searchType = 'inn'" :text-color="searchType === 'inn' ? 'blue' : 'gray'">Инн/ОГРН</Button></Cell>
                                    </Row>
                                </td>

                            </tr>
                            <template v-if="table">
                                <tr><td style="width:150px;"> Логотип </td> <td><input type="text" v-model="table.icon" ></td></tr>
                                <tr>
                                    <td style="width:150px;"> Статус </td> <td>
                                    <Select v-model="table.status" titleName="name" keyName="value"
                                            :datas="h.customer"/>
                                </td>
                                </tr>
                                <tr v-if="table.status === 2"><td> Наименование краткое </td> <td><input type="text" v-model="table.organization" ></td></tr>
                                <tr v-if="table.status === 2"><td> Наименование </td>         <td><textarea class="txt100" v-model="table.organization_long" ></textarea></td></tr>
                                <tr v-if="table.status === 2"><td> Руководитель </td>         <td><input type="text" v-model="table.management_name" ></td></tr>
                                <tr v-if="table.status === 2"><td> Должность </td>            <td><input type="text" v-model="table.management_post" ></td></tr>
                                <tr><td> Контактное лицо </td>      <td><input type="text" v-model="table.name" ></td></tr>

                                <tr>
                                    <td> Телефоны</td>
                                    <td>
                                        <Row class="h-row-flex h-row-flex-space-between">
                                            <Cell width="14"><the-mask mask="+7 (###) ### ## ##"  v-model="table.phone" :masked="true"/></Cell>
                                            <Cell width="8"><input type="text"  v-model="table.phone_sub" placeholder="добавочный" ></Cell>
                                        </Row>
                                        <Row class="h-row-flex h-row-flex-space-between">
                                            <Cell width="14"><the-mask mask="+7 (###) ### ## ##"  v-model="table.phone_ext" :masked="true"/></Cell>
                                            <Cell width="8"><input type="text"  v-model="table.phone_ext_sub" placeholder="добавочный"></Cell>
                                        </Row>
                                    </td>
                                </tr>
                                <tr><td> Емайл </td>      <td><input class="half"  type="text" v-model="table.email" ><input type="text" class="half" v-model="table.emails" ></td></tr>


                                <tr>
                                    <td colspan="2">
                                        <AutoComplete
                                            :option="autoCompleteOps.address"
                                            type="object"
                                            v-model="foundAddress"
                                            @change="onChangeAddress"
                                            placeholder="... Вводите адрес"
                                        ></AutoComplete>
                                    </td>
                                </tr>
                                <tr><td> Адрес </td>   <td><textarea class="txt100" v-model="table.address"></textarea></td></tr>
                                <tr v-if="table.status === 2"><td> Адрес для договора </td>   <td><input type="text" v-model="table.address_source" ></td></tr>
                                <tr><td> ZIP </td>                  <td><input type="text" v-model="table.zip" ></td></tr>
                                <tr><td> Регион </td>               <td><input type="text" v-model="table.region" ></td></tr>
                                <tr><td> Долгота </td>              <td><input type="text" v-model="table.lat" ></td></tr>
                                <tr><td> Широта </td>               <td><input type="text" v-model="table.lon" ></td></tr>
                                <tr><td> Расстояние </td>               <td><input type="text" v-model="table.destination" ></td></tr>
                                <tr v-if="table.status === 2"><td> ОГРН </td>                 <td><input type="text" v-model="table.ogrn" ></td></tr>
                                <tr v-if="table.status === 2"><td> ИНН </td>                  <td><input type="text" v-model="table.inn" ></td></tr>
                                <tr v-if="table.status === 2"><td> КПП </td>                  <td><input type="text" v-model="table.kpp" ></td></tr>
                            </template>

                            <tr v-if="table.status === 2">
                                <th colspan="2">
                                        <AutoComplete
                                            :option="autoCompleteOps.bank"
                                            type="object"
                                            v-model="foundBank"
                                            @change="onChangeBank"
                                            placeholder="... Вводите адрес"
                                        ></AutoComplete>
                                </th>



                            </tr>

                            <tr v-if="table.status === 2"><td> Наименование банка </td>               <td><input type="text" v-model="table.bank_payment" ></td></tr>
                            <tr v-if="table.status === 2"><td> К/С </td>               <td><input type="text" v-model="table.bank_ca" ></td></tr>
                            <tr v-if="table.status === 2"><td> БИК </td>               <td><input type="text" v-model="table.bank_bic" ></td></tr>
                            <tr v-if="table.status === 2"><td> Р/Счет организации </td>               <td><input type="text" v-model="table.bank_account" ></td></tr>

                        </table>
                    </td>
                    <td style="vertical-align: top;width:700px;" >
                        <table class="h-table">
                            <tr>
                                <th style="width: 200px"><Select v-model="searchField" titleName="title" keyName="key" :datas="searchFields" @change="searchCustomer()" placeholder="Искать в базе ..."></Select></th>
                                <th style="width: 500px"><input v-model="searchValue" type="text" @keyup="searchCustomer()"></th>
                            </tr>
                            <tr v-for="res in searchResult">
                                <td colspan="2">
                                    <template v-for="(val,name) in res">
                                        <template v-for="i in searchFields" v-if="i.key === name && val">
                                            <template v-if="i.key === searchField"><Button color="yellow" @click="setCustomer(res)">{{val}}</Button></template>
                                            <template v-else>{{val}}</template>&nbsp;/&nbsp;
                                        </template>
                                    </template>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </Modal>
    </div>
</template>

<script>


    // const loadData = ;

    const  loadData = function(filter, callback){
        Axios.post('/dadata/async',{ func:this.orgId, query:filter })
            .then(response => {
                let list = [];
                for(let s in response.data.suggestions){
                    list.push({
                        key: s,
                        title: response.data.suggestions[s].value,
                        obj: response.data.suggestions[s].data
                    });
                }
                callback(list);
            })
    };


    import Axios from 'axios';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";

    export default {
        name: 'DadataOrganization',
        // props: ['customer_data'],

        data(){
            return {
                modal: false,
                searchType: 'organization',
                searchResult: [],
                searchCounts: true,

                searchField:'organization',
                searchValue:'',
                searchFields: [
                    {key:'name',title:'Контактное лицо'},
                    {key:'organization',title:'Организация'},
                    {key:'phone',title:'Телефон'},
                    {key:'address',title:'Адрес'},
                    {key:'email',title:'email'},
                    {key:'inn',title:'ИНН'},
                    {key:'kpp',title:'КПП'},
                    {key:'ogrn',title:'ОГРН'},
                    {key:'zip',title:'Почтовый индекс'},
                    {key:'bank_account',title:'Банковский счет'},
                ],

                found: {},
                foundBank: {},
                foundAddress: {},
                table: {
                    status: 1
                },
                autoCompleteOps: {
                    organization: {
                        orgId: 'organization',
                        loadData,
                        minWord: 1
                    },
                    inn: {
                        orgId: 'inn',
                        loadData,
                        minWord: 1
                    },
                    bank: {
                        orgId: 'bank',
                        loadData,
                        minWord: 1
                    },
                    address: {
                        orgId: 'address',
                        loadData,
                        minWord: 1
                    }
                }
            }
        },
        computed:{
            ...mapGetters(['lid','h','customer'])
        },
        components: {
            // TheMask
        },
        methods:{
           async  onChange(data, trigger){

                 if(trigger === 'picker' && data.value){
                     this.searchCounts = true;
                     this.searchResult = [];

                   await  this.$set(this.$data,'table',{
                         name : data.value.obj.name.name || '',
                         // icon : '',
                         status : 2,
                         organization : data.value.obj.name.short_with_opf || '',
                         organization_long : data.value.obj.name.full || '',
                         // phone : '',
                         // phone_ext : '',
                         address : data.value.obj.address.value || '',
                         // addresses : '',
                         address_source : data.value.obj.address.data.source || '',
                         // email : '',
                         // emails : '',
                         destination : '',
                         // city : '',
                         region : data.value.obj.address.data.region || '',
                         lat : data.value.obj.address.data.geo_lat || '',
                         lon : data.value.obj.address.data.geo_lon || '',
                         inn : data.value.obj.inn || '',
                         kpp : data.value.obj.kpp || '',
                         ogrn : data.value.obj.ogrn || '',
                         zip : data.value.obj.address.data.postal_code || '',
                         management_name : data.value.obj.management ? data.value.obj.management.name : '',
                         management_post : data.value.obj.management ? data.value.obj.management.post : '',
                     });
                   console.log(this.getDistance());
                 } else {
                     this.table = {};
                     this.searchResult = [];
                 }
            },
            onChangeBank(data, trigger){
                if(trigger && data.value){
                    this.$set(this.$data.table,'bank_payment',data.value.obj.name.payment);
                    this.$set(this.$data.table,'bank_ca',data.value.obj.correspondent_account);
                    this.$set(this.$data.table,'bank_bic',data.value.obj.bic);
                    this.$set(this.$data.table,'bank_account','');
                }
            },
            async onChangeAddress(data, trigger){
                if (trigger && data.value) {
                    await this.$set(this.$data.table, 'address', data.value.obj.value);
                    await this.$set(this.$data.table, 'region', data.value.obj.region);
                    await this.$set(this.$data.table, 'lat', data.value.obj.geo_lat);
                    await this.$set(this.$data.table, 'lon', data.value.obj.geo_lon);
                    this.getDistance();

                }
            },
            async getDistance() {
                if (!this.table.region || !this.table.lat || !this.table.lon) {return false}
                try {
                    let response = await Axios.post('/dadata/async', {
                        func: 'osm',
                        region: this.table.region,
                        flat: this.table.lat,
                        flon: this.table.lon
                    });
                    if ('distance' in response.data.properties) {
                        this.$Notice({
                            type: 'success',
                            title: response.data.properties.distance + ' км от офиса',
                            content: ''
                        });
                        return this.$set(this.table, 'destination', response.data.properties.distance)
                    } else {
                        return this.$set(this.table, 'destination', 'Расстояниене возможно определить')
                    }
                } catch (error) {console.log(error);}
            },
            async searchByField(field,value){
               this.searchField = 'inn';
                try {
                    let response = await Axios.post('/customer/async',{
                        func: 'searchByField',
                        field: field,
                        value: value
                    });
                    this.searchResult = response.data;
                    this.searchCounts = this.searchResult.length || false;
                } catch (error) {console.log(error);}
            },
            async searchCustomer(){
               try{
                    if(this.searchValue.length >= 2 ){
                        let response = await Axios.post('/customer/async',{
                            func:'search',
                            field: this.searchField,
                            value: this.searchValue
                        });
                        this.searchResult = [];
                        this.searchResult = response.data;
                    } else {
                        this.searchResult = [];
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            setCustomer(res){
               this.$set(this.$data,'table',res);
               this.searchCounts = false;
            },

            async save(){


                if(!this.table.id) {
                    await this.$store.dispatch('createCustomer',this.table);
                    this.table.id = this.customer.id
                }
                await this.$store.dispatch('updateCustomer',{id:this.table.id,values:this.table});
                await this.$store.dispatch('updateLid', {
                    id:this.lid.id,
                    values:{customer:this.table.id}
                });
                await this.$store.dispatch('fetchLid',this.lid.id);
                await this.$store.dispatch('fetchCustomer',this.table.id);
                this.modal = false;






/*               if(this.table.id && Number(this.table.id) > 0) {
                  await this.$store.dispatch('updateLid', {
                       id:this.lid.id,
                       values:{customer:this.table.id}
                   });
                   await this.$store.dispatch('fetchLid',this.lid.id);
                   await this.$store.dispatch('fetchCustomer',this.table.id);
                   this.modal = false;
               } else { // save
                   console.log('ggggggg44');
                 await this.$store.dispatch('createCustomer',this.table);
                 await this.$store.dispatch('saveLid',[{customer:id}])
               }*/
            },






            /// -----------
            async search(){
                try {
                    let response = await Axios.post('/dadata/async',{
                        func: this.searchType,
                        query: event.target.value
                    });
                    this.suggestions = response.data.suggestions;
                } catch (error) {
                    console.log(error);
                }
            },
            async ifExist(field,value){
                try {
                    let response = await Axios.post('/customer/async',{
                        func: 'check',
                        field: field,
                        value: value,
                    });
                    console.log(response.data);
                    this.$set(this.$data,'searchResult',response.data);
                } catch (error) {
                    console.log(error);
                }
            },
        },

        watch:{
            table(n,o) {
                if(Object.keys(n).length !== 0) {
                    this.modal = true;
                }
            },
            modal(val){
                if (!val){
                    this.table = {};
                    this.searchResult = [];
                } else {
                    this.$set(this,'table',this.customer);
                }
            }
        },
        created(){
            bus.$on('customer-modal', (a) => {
                this.modal = a;
            })
        },
        mounted() {


        }
    };
</script>
<style lang="scss">
    .main-header {
        z-index: 852!important;
    }
    .left-modal {
        width: 600px;
        overflow-x: hidden;
    }

    .h-autocomplete-input {
        color: green;
    }
</style>
