<template>
    <div>
        <Modal class="actModal" v-model="modal"  :hasCloseIcon="true">
            <table class="h-table acts-tbl">
                <tr class="head">
                    <th>#</th>
                    <th>ఋ</th>
                    <th>Срок с</th>
                    <th>Срок до</th>
                    <th>Работы завершены?</th>
                    <th>Адрес</th>
<!--                    <th>Бух. акт передан?</th>-->
                    <th>Ссылка на лид</th>
<!--                    <th>Бух. акт подписан?</th>-->
                    <th>№ договора</th>
                    <th>Бух. акт скан</th>
                    <th>Исп. акт подписан?</th>
                    <th>Исп. акт скан</th>
                    <th/>
                </tr>
                <tbody>

                <template v-for="(act,key) in points[geoKey]">

                    <tr :class="{'active': opened}" class="first">

                        <th>{{act.act_nr}}</th>

                        <th><h-switch v-model="act.floating" :disabled="!opened" :small="true"
                                      @change="promptUpdateMapAct(act,'это исключит акт из `плавающих` и поместит в разряд `неопределенных` ')" /></th>

                        <th><input v-if="act.floating" type="date" v-model="act.floating_date_from" :disabled="!opened" @change="updateMapAct(act)" ></th>

                        <th><input v-if="act.floating" type="date" v-model="act.floating_date_to" :disabled="!opened" @focusout="updateMapAct(act)"></th>

                        <th><h-switch v-model="act.finished" :disabled="!opened" :small="true" @change="updateMapAct(act)"/></th>

                        <th ><div class="ta-left">{{act.address}}</div></th>

<!--                        <th><h-switch v-model="act.booking_act_transferred" :disabled="!opened" :small="true" @change="updateMapAct(act)"/></th>-->
                        <th><a :href="`/layout/lid/${act.parent}/edit?open_act=${act.id}`" target="_blank">{{act.parent}}/{{act.id}}</a></th>

<!--                        <th><h-switch v-model="act.booking_act_signed" :disabled="!opened" :small="true" @change="updateMapAct(act)"/></th>-->
                        <th>{{mapLids[act.parent].contract}}</th>

                        <th>
                            <template v-if="opened">
                                <cite v-if="act.booking_act_file !== null"><a target="_blank" :href="'/storage/docs/'+act.booking_act_file">Загружен</a> <a @click="uploadFile('booking_act_file',true)" class="x white">X</a></cite>
                                <template v-else>
                                    <label :for="'booking_act_file_'+act.id" class="file sm">ФАЙЛ</label>
                                    <input type="file" :id="'booking_act_file_'+act.id" @change="uploadFile('booking_act_file')">
                                </template>
                            </template>
                            <template v-else>
                                <h-switch :value="act.booking_act_file !== null" disabled :small="true"/>
                            </template>
                        </th>

                        <th><h-switch v-model="act.implement_act_signed" :disabled="!opened" :small="true" @change="updateMapAct(act)"/></th>

                        <th>
                            <template v-if="opened">
                                <cite v-if="act.implement_act_file !== null"><a target="_blank" :href="'/storage/docs/'+act.implement_act_file">Загружен</a> <a @click="uploadFile('implement_act_file',true)" class="x white">X</a></cite>
                                <template>
                                    <label :for="'implement_act_file_'+act.id" class="file sm">ФАЙЛ</label>
                                    <input type="file" :id="'implement_act_file_'+act.id" @change="uploadFile('implement_act_file')">
                                </template>
                            </template>
                            <template v-else>
                                <h-switch :value="act.implement_act_file !== null" disabled :small="true"/>
                            </template>
                        </th>

                        <th>
                            <Button @click="opened = !opened"><i class="h-icon-down"/></Button>
                        </th>

                    </tr>


                    <tr v-if="opened">
                        <td colspan="12" class="" style="padding:20px 0 40px 0!important;">

                            <Row :space-y="10" type="flex" class="h-row-flex-space-between no-wrap" >
                                <Cell width="6"><label>Адрес</label>
                                    <AutoComplete :option="autoCompleteOps.address" type="object" v-model="act.address"
                                                  @change="onChangeAddress" placeholder="... вводите адрес"/>
                                </Cell>
                                <Cell width="2">
                                    <label>Долгота</label>
                                    <input type="text"  v-model="act.lat" @focusout="updateMapAct(act)"/>
                                </Cell>
                                <Cell width="2">
                                    <label>Широта</label>
                                    <input type="text"  v-model="act.lon" @focusout="updateMapAct(act)"/>
                                </Cell>
                                <Cell width="2">
                                    <label>Регион</label>
                                    <input type="text"  v-model="act.region" @focusout="updateMapAct(act)"/>
                                </Cell>
                                <Cell width="1">
                                    <label>Расстояние</label>
                                    <input type="text"  v-model="act.destination" @focusout="updateMapAct(act)"/>
                                </Cell>
                                <Cell width="3">

                                    <label>Телефон</label>
                                    <Row :space-y="10">
                                        <Cell width="16">
                                            <the-mask mask="+7 (###) ### ## ##" :masked="true" v-model="act.phone" @input="act.phone.length === 18 ? updateMapAct(act) : ''"  />
                                        </Cell>
                                        <Cell width="8"><input type="text"  v-model="act.phone_sub" @focusout="updateMapAct(act)"/></Cell>
                                    </Row>
                                </Cell>
                                <Cell width="3">
                                    <label>Телефон</label>
                                    <Row :space-y="10">
                                        <Cell width="16">
                                            <the-mask mask="+7 (###) ### ## ##" :masked="true"  v-model="act.phone_ext" @input="act.phone_ext.length === 18 ? updateMapAct(act) : ''"/>
                                        </Cell>
                                        <Cell width="8"><input type="text"  v-model="act.phone_ext_sub" @focusout="updateMapAct(act)"/></Cell>
                                    </Row>
                                </Cell>

                                <Cell width="3"><label>Имя контакта</label>
                                    <AutoComplete
                                        :option="autoCompleteOps.fio"
                                        type="object"
                                        v-model="act.contact_name"
                                        @change="onChangeFIO"
                                        placeholder="... вводите имя"
                                    />
                                </Cell>

            </Row>
             <Row :space="10">
                 <Cell width="14"></Cell>
                 <Cell width="3"><label>Тип обьекта</label>
                 <Select  v-model="act.property_type" :datas="propertyType" keyName="id" titleName="name" :null-option="false" @change="updateMapAct(act)"></Select>
                 </Cell>
                 <Cell width="1" ><label>Карта</label>
                     <h-switch  v-model.lazy="act.card_payment" :trueValue="1" :falseValue="0" :small="true" @change="updateMapAct(act)" style="display: inline-flex;" />
                 </Cell>
                 <Cell width="2"><label>Мастер+ (вся сумма)</label>
                     <input  type="text" v-model.number="act.overpay" @focusout="masterOverpay(act)" >
                 </Cell>
                 <Cell width="2"><label>Мастер+ (остаток)</label>
                     <input  type="text" v-model.number="act.overpay_rest" >
                 </Cell>

                 <Cell width="2"><label>Субчику</label>
                     <input  v-model.number.lazy="act.contractor_fee" type="text" @focusout="updateMapAct(act)">
                 </Cell>
                 <Cell width="2"><label>Отказ</label>
                     <input v-model.number.lazy="act.pay_back" type="text" @focusout="updateMapAct(act)">
                 </Cell>
<!--                 <Cell width="1"><label>Нал</label></Cell>-->
<!--                 <Cell width="1"><label>Безнал</label></Cell>-->
              </Row>

                            <Row :space="10" class="h-row-flex-space-between no-wrap"><Cell width="24">&nbsp;</Cell></Row>
                            <Row :space="10" class="h-row-flex-space-between no-wrap">
                                <Cell width="2"><label>Предмет работ:</label></Cell>
                                <Cell width="1"><label>Пл.:</label></Cell>
                                <Cell width="1"><label>Пл. ф:</label></Cell>
                                <Cell width="2"><label>Единица площади:</label></Cell>
                                <Cell width="13"><label>Методы:</label></Cell>
                                <Cell width="2"><label>Цена Безнал</label></Cell>
                                <Cell width="2"><label>Цена Нал</label></Cell>
                                <Cell width="1"><Button @click="createMapVolume({parent:act.id,lid_id:act.parent})">+</Button></Cell>
                            </Row>


                            <template v-for="(volume,VID) in  act.volumes">

                                <Row :space="10" class="h-row-flex-space-between no-wrap">
                                    <Cell width="2"><Select v-model="volume.pest" :datas="h.pests" keyName="id" titleName="name" @change="updatePestVolume(VID)" :null-option="false"/></Cell>
                                    <Cell width="1"><input type="text" v-model="volume.square" @focusout="updateVolume(volume)"></Cell>
                                    <Cell width="1"><input type="text" v-model="volume.square_fact" @focusout="updateVolume(volume)"></Cell>
                                    <Cell width="2"><Select v-model="volume.entity" :datas="h.square" keyName="id" titleName="name" @change="updateVolume(volume)" :null-option="false"/></Cell>
                                    <Cell width="13"><Category
                                        :option="{ title: 'Выбор методов', keyName: 'id', titleName: 'name', dataMode: 'tree', datas: setMethods(volume.pest) }"
                                        :multiple="true"
                                        type="key"
                                        v-model="volume.method"
                                        @change="updateVolume(volume)"
                                    /></Cell>
                                    <Cell width="2"><input type="text" v-model.number="volume.price_standard" @focusout="updatePrices(volume,act)"></Cell>
                                    <Cell width="2"><input type="text" v-model.number="volume.price_fact" @focusout="updatePrices(volume,act)"></Cell>
                                    <Cell width="1"><Button @click="removeMapVolume({id:VID})">-</Button></Cell>
                                </Row>
                            </template>


                            <Row :space="10" class="h-row-flex-space-between no-wrap"><Cell width="24">&nbsp;</Cell></Row>
                            <Row :space="10">
                                <Cell width="24">
                                    <Row :space="10" class="h-row-flex-space-between no-wrap">
                                        <Cell width="2"><label>Выбрать мастера:</label></Cell>
                                        <Cell width="2"><label>Мастер:</label></Cell>
                                        <Cell width="2"><label>Начало работ:</label></Cell>
                                        <Cell width="2"><label>Окончание Работ:</label></Cell>
                                        <Cell width="1">
                                            <Button @click="createMapImplement({parent:act.id,lid_id:act.parent,user:h.user.id,date:logisticDate})">+</Button>
                                        </Cell>
                                        <template v-for="masterRule in h.rules[3]">
                                            <Cell width="3">
                                                <label v-tooltip theme="white" :content="masterRule.name">{{masterRule.name_short}}</label>
                                            </Cell>
                                        </template>

                                    </Row>
                                    <template v-for="(implement,IID) in act.implements">

                                        <Row :space="10" class="h-row-flex-space-between no-wrap">

<!--                                            <Cell width="2">
                                                <DatePicker v-model="implement.date" placeholder="Дата обработки" :no-border="true" @change="setMaster(implement)"/>
                                            </Cell>
                                            <Cell width="2">
                                                <template v-if="implement.master"> {{h.users[implement.master].name}}</template>
                                            </Cell>
                                            <Cell width="2" >{{implement.start_date}}</Cell>
                                            <Cell width="2">{{implement.end_date}}</Cell>



                                            <Cell width="1"><Button @click="confRemove = true,reason = false">-</Button></Cell>-->
                                            <!--removeMapImplement(implement)-->
                                            <implement :implement="implement"/>
                                            <RulesMaster :implement="implement"/>


                                        </Row>
                                    </template>

                                </Cell>
                                <Cell width="12"><textarea v-model="act.comment" class="txt100" @focusout="updateMapAct(act)" placeholder="Комментарий..."/></Cell>
                            </Row>

                        </td>
                    </tr>


                </template>

                </tbody>
            </table>
        </Modal>

    </div>




</template>

<script>



    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    import Axios from  'axios';
    import RulesMaster from './Act/RulesMaster';
    import Implement from './Act/Implement';





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
        props: [],
        data() {
            return {
                geoKey:'',
                opened: true,
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
        components:{
            RulesMaster,Implement
        },
        computed: {
            modal: {
                get(){ return this.$store.getters.actModal} ,
                set(value) {this.$store.commit('ACT_MODAL', value )}
            },
            ...mapGetters(['DGMap','logisticDate','points','schedule','markers','polylines','mapActs','mapLids','masters','h']),
        },
        methods: {
            ...mapActions(['setMaster','updateMapAct','createMapVolume','removeMapVolume','activeImplement','createMapImplement'      ,'updateVolume','updateImplement','removeMapImplement','getDistance','createMasterRules']),

            // date manipulations


            // eof date manipulations





            // check

            masterOverpay(act) {
                act.overpay = act.overpay ? act.overpay : 0;
                if(act.overpay){
                    act.overpay_rest = Number(act.overpay) - Object.keys(act['volumes']).reduce((a,k,o ) => {
                        return a + Number(act.volumes[k].price_fact) + Number(act.volumes[k].price_standard)
                    },0)
                } else {
                    act.overpay_rest = 0
                }

                this.$store.dispatch('updateMapAct',act)
            },

            async updatePrices(volume,act){
                await this.$store.dispatch('updateVolume',volume)
                await this.masterOverpay(act);

            },


            checkVolumeMethods(){},


            promptUpdateMapAct(act,mess){
                this.$Confirm(mess,'Внимание!!!')
                    .then(() => {
                        this.$Message.success('Да');
                        this.updateMapAct(act);
                    })
                    .catch(() => {
                        this.$Message.error('Нет');
                    })
                //  @TODO-uretral:   Сделать возврат чекбокса
            },

            initLogisticMap(implement){bus.$emit('init-map',implement)},

            setMethods(pestID){
                let res = [];
                if(!pestID){ return res }
                for(let methodKey of this.h.pests[pestID].methods)  {
                    res.push({id:this.h.methods[methodKey].id, name:this.h.methods[methodKey].name,});
                }
                return res;
            },

            updatePestVolume(id){
                this.$set(this.acts[this.id].volumes[id],'method',[]);
                this.updateVolume(this.acts[this.id].volumes[id])
            },


            // eof rules


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
            async onChangeFIO(data, trigger) {
                if (trigger && data.value) {
                    await this.$set(this.acts[this.id], 'contact_name', data.value.title);
                    await this.updateAct(this.acts[this.id])
                }
            },
            async onChangeAddress(data, trigger) {
                if (trigger && data.value) {
                    console.log('data',data);
                 let distance =  await this.getDistance({
                        region: data.value.obj.region,
                        lat: data.value.obj.lat,
                        lon: data.value.obj.lon
                    });
                 console.log(distance);
/*/!*                    try {
                        let response = await Axios.post('/act/async')
                    }*!/
                    await this.$set(this.acts[this.id], 'address', data.value.title);
                    await this.$set(this.acts[this.id], 'region', data.value.obj.region);
                    await this.$set(this.acts[this.id], 'lat', data.value.obj.geo_lat);
                    await this.$set(this.acts[this.id], 'lon', data.value.obj.geo_lon);
                    await this.getDistance();*/
                }
            },


            // IMPLEMENTS
/*            createMapImplementWithRules(implement){
                this.$store.dispatch('createMapImplement',implement);
            },*/

            // eof IMPLEMENTS


            /*
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
*/
        },
        created() {
            bus.$on('act-map',(geoKey) => {
                this.geoKey = geoKey;
                this.actModal = true;
            })

        },
        mounted() {
            // JQ
            $(document).on('click','.master-rule',function(){
                $(this).parents('table').find('.master-rule-txt').show()
            });
            //

        }

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
    .h-notify-container {
        width: 100%;
        top: 50px!important;
    }
    input[type="text"] {
        width: 100%;
    }
    .h-notify .h-notify-close {
        right: 44px;
        top: 27px;
        color: white;
    }
    label {
        display: block;
    }
</style>
