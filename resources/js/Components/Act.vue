<template>

    <tbody class="act-body">

    <tr :class="{'active': opened,'is-sub': is_sub}" class="first">
        <th><Button color="warn" size="xs" icon="h-icon-close" @click="removeConfirmAct(acts[id])"/></th>
        <th v-if="is_sub"/>
        <th v-else><Checkbox v-model="copyReady"/></th>
        <th>
            {{acts[id].act_nr}} ({{id}})
<!--            <template v-if="[1].indexOf(h.user.role_id) !== -1">({{id}})</template>-->
        </th>
        <th><span v-if="acts[id].creator">{{h.users[acts[id].creator].name}}</span>

            <Poptip content="Подтверждаете смену менеджера?" v-model="acts[id].floating" @confirm="acts[id].creator = 44, updateAct(acts[id])">

                <button class="h-btn h-btn-text-primary h-btn-no-border"><i class="h-icon-edit"></i></button>
            </Poptip>

        </th>

        <th><h-switch v-model="acts[id].floating" :disabled="!opened" :small="true"
                      @change="updateAct(acts[id])"/></th>

        <th><input v-if="acts[id].floating" type="date" v-model="acts[id].floating_date_from"
                   :disabled="!opened"  @focusout="updateAct(acts[id])"></th>

        <th><input v-if="acts[id].floating" type="date" v-model="acts[id].floating_date_to"
                   :disabled="!opened" @focusout="updateAct(acts[id])"></th>

        <th><h-switch v-model="acts[id].finished" :disabled="!opened" :small="true"
                      @change="updateFinished(acts[id])"/></th>

        <th >
            <div class="ta-left"><span>{{acts[id].address}}</span></div>
        </th>
<!--ACCOUNTANT-->
        <template v-if="[7].indexOf(h.user.role_id) !== -1">
            <th><h-switch v-model="acts[id].booking_act_transferred" :disabled="!opened" :small="true"
                          @change="updateAct(acts[id])"/></th>

            <th><h-switch v-model="acts[id].booking_act_signed" :disabled="!opened" :small="true"
                          @change="updateAct(acts[id])"/></th>

            <th>
                <template v-if="opened">
                    <cite v-if="acts[id].booking_act_file !== null"><a target="_blank" :href="'/storage/docs/'+acts[id].booking_act_file">Загружен</a> <a @click="uploadFile('booking_act_file',true)" class="x white">X</a></cite>
                    <template v-else>
                        <label :for="'booking_act_file_'+acts[id]" class="file sm">ФАЙЛ</label>
                        <input type="file" :id="'booking_act_file_'+acts[id]" @change="uploadFile('booking_act_file')">
                    </template>
                </template>
                <template v-else>
                    <h-switch :value="acts[id].booking_act_file !== null" disabled :small="true"/>
                </template>
            </th>

            <th><h-switch v-model="acts[id].implement_act_signed" :disabled="!opened" :small="true"
                          @change="updateAct(acts[id])"/></th>

            <th>
                <template v-if="opened">
                    <cite v-if="acts[id].implement_act_file !== null"><a target="_blank" :href="'/storage/docs/'+acts[id].implement_act_file">Загружен</a> <a @click="uploadFile('implement_act_file',true)" class="x white">X</a></cite>
                    <template>
                        <label :for="'implement_act_file_'+acts[id]" class="file sm">ФАЙЛ</label>
                        <input type="file" :id="'implement_act_file_'+acts[id]" @change="uploadFile('implement_act_file')">
                    </template>
                </template>
                <template v-else>
                    <h-switch :value="acts[id].implement_act_file !== null" disabled :small="true"/>
                </template>
            </th>
        </template>
<!--MANAGER-->
        <template v-if="[1,2,5].indexOf(h.user.role_id) !== -1">
            <th>
                <template v-for="implement in acts[id].implements">
                    {{implement.start_date}} <br/>
                </template>
            </th>
            <th>
                <template v-for="vol in acts[id].volumes">
                    <template v-if="vol.pest">
                        {{h.pests[vol.pest].name}} <br/>
                    </template>
                </template>
            </th>
            <th>
                <template v-for="vol in acts[id].volumes">
                    {{vol.square}}
                    <template v-if="vol.entity">
                        ({{h.square[vol.entity].name_short}}) <br/>
                    </template>
                </template>
            </th>
            <th>{{volumesSum.total}} ({{volumesSum.cash}} + {{volumesSum.cashless}})</th>

            <template v-if="acts[id].subact">
                <Select v-model="acts[id].subact_type" :datas="[{id:1,name:'Гарантийный'},{id:2,name:'Повторный'}]"
                        keyName="id" titleName="name"  @change="updateAct(acts[id])" :null-option="false"/>
            </template>
            <template v-else>
                <th>
                    <Button
                            :disabled="!opened"
                            size="s"
                            color="blue"
                            @click="createSubAct(acts[id])"
                    >Подакт</Button>
                </th>
            </template>

        </template>


        <th>
            <Button @click="opened = !opened"><i class="h-icon-down"/></Button>
        </th>

    </tr>

    <tr v-if="opened">
        <td :colspan="collspan(h.user.role_id)" class="" style="padding:20px 0 40px 0!important;">
            <!--FIRST ROW-->
            <Row :space-y="10" type="flex" class="h-row-flex-space-between no-wrap" >
                <Cell width="6"><label>Адрес</label>
                    <AutoComplete
                        :option="autoCompleteOps.address"
                        type="object"
                        v-model="acts[id].address"
                        @change="onChangeAddress"
                        placeholder="... вводите адрес"
                    />
                </Cell>
                <Cell width="2"><label>Долгота</label><input type="text"  v-model="acts[id].lat" @focusout="updateAct(acts[id])"/></Cell>
                <Cell width="2"><label>Широта</label><input type="text"  v-model="acts[id].lon" @focusout="updateAct(acts[id])"/></Cell>
                <Cell width="2"><label>Регион</label><input type="text"  v-model="acts[id].region" @focusout="updateAct(acts[id])"/></Cell>
                <Cell width="1"><label>Расстояние</label><input type="text"  v-model="acts[id].destination" @focusout="updateAct(acts[id])"/></Cell>
                <Cell width="3">

                    <label>Телефон</label>
                    <Row :space-y="10">
                        <Cell width="16">
                            <the-mask mask="+7 (###) ### ## ##" :masked="true" v-model="acts[id].phone" @input="acts[id].phone.length === 18 ? updateAct(acts[id]) : ''"  />
                        </Cell>
                        <Cell width="8"><input type="text"  v-model="acts[id].phone_sub" @focusout="updateAct(acts[id])"/></Cell>
                    </Row>
                </Cell>
                <Cell width="3">
                    <label>Телефон</label>
                    <Row :space-y="10">
                        <Cell width="16">
                            <the-mask mask="+7 (###) ### ## ##" :masked="true"  v-model="acts[id].phone_ext" @input="acts[id].phone_ext.length === 18 ? updateAct(acts[id]) : ''"/>
                        </Cell>
                        <Cell width="8"><input type="text"  v-model="acts[id].phone_ext_sub" @focusout="updateAct(acts[id])"/></Cell>
                    </Row>
                </Cell>

                <Cell width="3"><label>Имя контакта</label>
                    <AutoComplete
                        :option="autoCompleteOps.fio"
                        type="object"
                        v-model="acts[id].contact_name"
                        @change="onChangeFIO"
                        placeholder="... вводите имя"
                    />
                </Cell>

            </Row>
             <Row :space="10">
                 <Cell width="4"></Cell>
                 <Cell width="3"><label>Тип обьекта</label>
                 <Select  v-model="acts[id].property_type" :datas="propertyType" keyName="id" titleName="name" :null-option="false" @change="updateAct(acts[id])"></Select>
                 </Cell>
                 <Cell width="1" ><label>Карта</label>
                     <h-switch  v-model.lazy="acts[id].card_payment" :trueValue="1" :falseValue="0" :small="true" @change="updateAct(acts[id])" style="display: inline-flex;" />
                 </Cell>
                 <Cell width="2"><label>Мастер+ (вся сумма)</label>
                     <input  type="text" v-model.number="acts[id].overpay" @focusout="masterOverpay(acts[id])" >
                 </Cell>
                 <Cell width="2"><label>Мастер+ (остаток)</label>
                     <input  type="text" v-model.number="acts[id].overpay_rest" disabled >
                 </Cell>

                 <Cell width="2"><label>Субчику</label>
                     <input  v-model.number.lazy="acts[id].contractor_fee" disabled type="text" @focusout="updateAct(acts[id])">
                 </Cell>
                 <Cell width="2"><label>Субчику нал</label>
                     <input  v-model.number.lazy="acts[id].contractor_fee_cash" type="text" @focusout="contactorSave(acts[id])"  :disabled="payment === 2">
                 </Cell>
                 <Cell width="2"><label>Субчику безнал</label>
                     <input  v-model.number.lazy="acts[id].contractor_fee_cashless" type="text" @focusout="contactorSave(acts[id])"  :disabled="payment === 1">
                 </Cell>
                 <Cell width="2"><label>Отказ</label>
                     <input v-model.number.lazy="acts[id].pay_back" type="text" @focusout="updateAct(acts[id])" disabled>
                 </Cell>
                 <Cell width="2"><label>Отказ нал</label>
                     <input v-model.number.lazy="acts[id].pay_back_cash" type="text" @focusout="payBackSave(acts[id])" :disabled="payment === 2">
                 </Cell>
                 <Cell width="2"><label>Отказ безнал</label>
                     <input v-model.number.lazy="acts[id].pay_back_cashless" type="text" @focusout="payBackSave(acts[id])" :disabled="payment === 1">
                 </Cell>
<!--                 <Cell width="1"><label>Нал</label></Cell>-->
<!--                 <Cell width="1"><label>Безнал</label></Cell>-->
              </Row>


            <!--SECOND ROW-->
            <Row :space="10" class="h-row-flex-space-between no-wrap"><Cell width="24">&nbsp;</Cell></Row>
            <Row :space="10" class="h-row-flex-space-between no-wrap">
                <Cell width="2"><label>Предмет работ:</label></Cell>
                <Cell width="1"><label>Пл.:</label></Cell>
                <Cell width="1"><label>Пл. ф:</label></Cell>
                <Cell width="2"><label>Единица площади:</label></Cell>
                <Cell width="13"><label>Методы:</label></Cell>
                <Cell width="2"><label>Цена Нал</label></Cell>
                <Cell width="2"><label>Цена Безнал</label></Cell>

                <Cell width="1"><Button @click="createVolume({parent:id,lid_id:acts[id].parent})">+</Button></Cell>
            </Row>


            <template v-for="(volume,VID) in acts[id].volumes">

                <Row :space="10" class="h-row-flex-space-between no-wrap">
                    <Cell width="2"><Select v-model="volume.pest" :datas="h.pests" keyName="id" titleName="name" @change="updatePestVolume(VID)" :null-option="false"/></Cell>
                    <Cell width="1"><input type="text" v-model="volume.square" @focusout="updateVolume(volume)"></Cell>
                    <Cell width="1"><input type="text" v-model="volume.square_fact" @focusout="updateVolume(volume)"></Cell>
                    <Cell width="2"><Select v-model="volume.entity" :datas="h.square" keyName="id" titleName="name" @change="updateVolume(volume)" :null-option="false"/></Cell>
                    <Cell width="13"><Category
                        :option="{
                            title: 'Выбор методов',
                            keyName: 'id',
                            titleName: 'name',
                            dataMode: 'tree',
                            datas: setMethods(volume.pest)
                       }"
                        :multiple="true"
                        type="key"
                        v-model="volume.method"
                        @change="updateVolume(volume)"
                    /></Cell>
                    <!--Цена Нал-->
                    <Cell width="2"><input type="text" v-model.number="volume.price_fact"
                                           :disabled="payment == 2" @focusout="updatePrices(volume,acts[id])"></Cell>
                    <Cell width="2"><input type="text" v-model.number="volume.price_standard"
                                           :disabled="payment == 1" @focusout="updatePrices(volume,acts[id])"></Cell>
SS
                    <Cell width="1"><Button @click="removeConfirmVolume({id:VID,act:id})">-</Button></Cell>
                </Row>
            </template>


            <!--THIRD ROW-->
            <Row :space="10" class="h-row-flex-space-between no-wrap"><Cell width="24">&nbsp;</Cell></Row>
             <Row :space="10">
                 <Cell width="12">
                     <Row :space="10" class="h-row-flex-space-between no-wrap">
                         <Cell width="4"><label>Выбрать мастера:</label></Cell>
                         <Cell width="8"><label>Мастер:</label></Cell>
                         <Cell width="4"><label>Начало работ:</label></Cell>
                         <Cell width="4"><label>Окончание Работ:</label></Cell>
                         <Cell width="2">
                             <Button @click="createImplement({parent:id,lid_id:acts[id].parent,user:h.user.id})">+</Button>
                         </Cell>
                     </Row>
                     <template v-for="(implement,IID) in acts[id].implements">

                         <Row :space="10" class="h-row-flex-space-between no-wrap">
                             <Cell width="4">
                                 <DatePicker v-model="implement.date" placeholder="Дата обработки" :no-border="true" @change="initLogisticMap(implement)"/>
                             </Cell>
                             <Cell width="8">
                                 <template v-if="implement.master"> {{h.users[implement.master].name}}</template>
                             </Cell>
                             <Cell width="4" >{{implement.start_date}}</Cell>
                             <Cell width="4">{{implement.end_date}}</Cell>

                             <Cell width="2"><Button @click="removeImplement(implement)">-</Button></Cell>
                         </Row>
                     </template>
                 </Cell>
                 <Cell width="12">
                     <textarea v-model="acts[id].comment" class="txt100" placeholder="Комментарий..." @focusout="updateAct(acts[id])"/>
                 </Cell>
              </Row>

        </td>
    </tr>

    </tbody>

</template>

<script>

    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    import Axios from  'axios';

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

    export default {
        name:'Act',
        props: ['payment','id','activeAct','is_sub'],
        data() {
            return {
                date:'',
                opened: Number(this.id) === Number(this.activeAct),
                // copyReady: false,// !!this.$store.actsCopy.indexOf(this.id) this.actsCopy.indexOf(this.id) !== -1 ? true : false
                autoCompleteOps: {
                    address: {
                        orgId: 'address',
                        loadData,
                        minWord: 1
                    },
                    fio: {
                        orgId: 'fio',
                        loadData,
                        minWord: 1
                    }
                },
                implementDate:'',
                propertyType: [
                {id:1,name:'квартира'},
                {id:2,name:'дом'},
                {id:3,name:'офис'},
                {id:4,name:'клиника'},
                {id:5,name:'больница'},
                {id:6,name:'дом престарелых'},
                {id:7,name:'пр-во пищевое'},
                {id:8,name:'пр-во непищевое'},
                {id:9,name:'складские пом-я'},
                {id:10,name:'магазин пищевой'},
                {id:11,name:'магазин непищевой'},
                {id:12,name:'открытая территория'},
                {id:13,name:'школа'},
                {id:14,name:'детский сад'},
                {id:15,name:'фитнес клуб'},
                {id:16,name:'торговый центр'},
                {id:17,name:'бизнес центр'},
                {id:100,name:'другое'}
            ]
            }
        },
        computed: {
            ...mapGetters(['h', 'customer', 'acts','foreignImplement','actsCopy']),
            volumesSum(){
                let cash = 0;
                let cashless = 0;
                for (let price in this.acts[this.id].volumes) if(this.acts[this.id].volumes[price].price_fact || this.acts[this.id].volumes[price].price_standard){
                    cash +=  Number(this.acts[this.id].volumes[price].price_fact)
                    cashless +=  Number(this.acts[this.id].volumes[price].price_standard)
                }
                return {
                    cash,cashless,total:cash + cashless
                };
            },
            copyReady:{

                get () {
                    return this.$store.getters.actsCopy.indexOf(this.id) != -1;
                },
                set (value) {
                    this.$store.commit('ACT_COPY_MANAGE',{id:this.id,val:value})
                }
            }
        },
        created() {
           // this.copyReady = this.actsCopy.indexOf(this.id) != -1 ? true : false;
           // console.log('rr',this.actsCopy.indexOf(this.id) != -1 ? true : false);

        },
        mounted() {


        },

        methods: {
            async getDistance() {
                try {
                    if (this.acts[this.id].region && this.acts[this.id].lat && this.acts[this.id].lon) {
                        let response = await Axios.post('/dadata/async', {
                            func: 'osm',
                            region: this.acts[this.id].region,
                            flat: this.acts[this.id].lat,
                            flon: this.acts[this.id].lon
                        });
                        if ('distance' in response.data.properties) {
                            this.$Notice({
                                type: 'success',
                                title: response.data.properties.distance + ' км от офиса',
                                content: ''
                            });
                            await this.$set(this.acts[this.id], 'destination', response.data.properties.distance);
                            await this.updateAct(this.acts[this.id]);
                        } else {
                            return this.$set(this.acts[this.id], 'destination', 'Расстояниене невозможно определить')
                        }
                    } else {
                        this.$Notice({type: 'error', title: 'Ошибка - нехватает данных', content: ''})
                    }
                } catch (err) {
                    console.log(err);
                }
            },

            ...mapActions(['updateAct','updateVolume','removeConfirmVolume','createVolume','updateImplement','createImplement','removeImplement','fetchMap','removeConfirmAct','createSubAct','delete_finance','create_finance','update_finance','get_finance']),
            // permissions & views
            collspan(role){
                let res;
                switch (role) {
                    case 1:
                        res = 15;
                        break;
                    case 2:
                        res = 15;
                        break;
                    case 7:
                        res = 14;
                        break;
                    default:
                        res = 14;
                        break;
                }
                return res;
            },
            masterOverpay(act) {
                act.overpay = act.overpay ? act.overpay : 0;
                if(act.overpay){
                    act.overpay_rest = Number(act.overpay) - Object.keys(act['volumes']).reduce((a,k,o ) => {
                        return a + Number(act.volumes[k].price_fact) + Number(act.volumes[k].price_standard)
                    },0)
                } else {
                    act.overpay_rest = 0
                }

                this.$store.dispatch('updateAct',act)
            },
            async updateFinished(act){

                if(!act.finished){
                    let current = []
                    try {
                        let response = await Axios.post('/vuex/finance',{
                            func: 'get',
                            data: {
                                lid_id: act.parent
                            }
                        });

                        current = await response.data.filter((o) => {
                            return o.lid_id === act.parent && o.act_id === act.id && o.act_auto_payment === 1
                        })
                        console.log('current',current);
                    } catch (e) {
                        console.log(e);
                    }
                    if(current){
                        await current.forEach(ob => {
                             this.$store.dispatch('update_finance',{
                                amount: 0,
                                id: ob.id,
                            })
                        })
                    }

                   await this.$store.dispatch('updateAct',act)
                } else {
                    if(this.volumesSum.cash){
                       await this.$store.dispatch('create_finance',{
                            act_auto_payment: 1,
                            act_id:act.id,
                            amount:this.volumesSum.cash,
                            comment:"автоплатеж - полное выполнение акта",
                            form: 1,
                            lid_id: act.parent,
                            manager: this.h.user.id,
                            type:1,
                            customer: this.customer.id,
                        });
                    }
                    if(this.volumesSum.cashless){
                      await this.$store.dispatch('create_finance',{
                            act_auto_payment: 1,
                            act_id:act.id,
                            amount:this.volumesSum.cashless,
                            comment:"автоплатеж - полное выполнение акта",
                            form: 2,
                            lid_id: act.parent,
                            manager: this.h.user.id,
                            type:1,
                            customer: this.customer.id,
                        });
                    }
                    await this.$store.dispatch('updateAct',act)
                    await this.$store.dispatch('fetch_finance',{
                        lid_id: act.parent
                    })
                }
            },
            async updatePrices(volume,act){
                await this.$store.dispatch('updateVolume',volume)
                await this.masterOverpay(act);
                bus.$emit('volume_price_updated')

            },

            managers(){
                let u = {}
                for( let k in this.h.users){
                    if([2,4].indexOf(this.h.users[k].role_id) !== -1){
                        u[k] = this.h.users[k]
                    }
                }
                return u
            },
            // eof permissions & views
            async initLogisticMap(implement) {
                await this.$store.commit('LOGISTIC_DATE', implement.date);
                await this.$store.commit('FOREIGN_IMPLEMENT',implement);
                await this.fetchMap();
                await this.$store.dispatch('setMaster',implement);
                await this.$store.commit('MAP_SWITCH', true);
            },
            async onChangeAddress(data, trigger) {
                if (trigger && data.value) {
                    await this.$set(this.acts[this.id], 'address', data.value.title);
                    await this.$set(this.acts[this.id], 'region', data.value.obj.region);
                    await this.$set(this.acts[this.id], 'lat', data.value.obj.geo_lat);
                    await this.$set(this.acts[this.id], 'lon', data.value.obj.geo_lon);
                    await this.getDistance();
                }
            },
            async onChangeFIO(data, trigger) {
                if (trigger && data.value) {
                    await this.$set(this.acts[this.id], 'contact_name', data.value.title);
                    await this.updateAct(this.acts[this.id])
                }
            },
            contactorSave(act){
                act.contractor_fee = Number(act.contractor_fee_cash) + Number(act.contractor_fee_cashless)
                this.$store.dispatch('updateAct', act)
                bus.$emit('volume_price_updated')
            },
            payBackSave(act){
                act.pay_back = Number(act.pay_back_cash) + Number(act.pay_back_cashless)
                this.$store.dispatch('updateAct', act)
                bus.$emit('volume_price_updated')
            },
            setMethods(pestID){
                let res = [];
                if(!pestID){ return res }

                for(let methodKey of this.h.pests[pestID].methods){
                    res.push({id:this.h.methods[methodKey].id, name:this.h.methods[methodKey].name,});
                }
                return res;
            },
            updatePestVolume(id){
                this.$set(this.acts[this.id].volumes[id],'method',[]);
                this.updateVolume(this.acts[this.id].volumes[id])
            },

            // toggleAct(id){id in this.switcher ? delete this.switcher[id] : this.switcher[id] = '';},

            async uploadFile(field, del = false) {
                try {
                    if (!del) {
                        let file = event.target.files[0] || event.dataTransfer.files[0];
                        let formData = new FormData();
                        formData.append('file', file);
                        let response = await Axios.post('/attach', formData, {headers: {'Content-Type': 'multipart/form-data'}});
                        if (response.data.uploaded) {
                            await this.$store.dispatch('updateActField', {
                                id: this.id,
                                field: field,
                                value: response.data.fileName
                            });
                        }
                    } else {
                        this.$Confirm('Удалить файл?', '').then(() => {
                            this.$store.dispatch('updateActField', {id: this.id, field: field, value: undefined});/* this.$Message.success('Удалено');*/
                        }).catch(() => {
                            this.$Message.success('Отменено');
                        });
                    }
                } catch (error) {
                    console.log(error);
                }
            },

            async getSubActs(){
                let subActs = {};
                for (let act in acts) if( acts[act].subact === acts[this.id]) {
                    subActs[acts[act].id] = act
                }
                return subActs;
            },
        },
    };
</script>
<style lang="scss">
    .button {
        position: relative;}
    input[type=file] {
        color: transparent;
        background-color: transparent;
        position: absolute;
        left: 0;
        width: 100%;
        height: 100%;
        top: 0;
        opacity: 0;
        z-index: 100;
    }

</style>
