<template>
    <div>

        <Button class="settings roof" style="left: 130px" color="yellow" icon="h-icon-setting" @click="settingsModal.state = !settingsModal.state"/>
        <Button class="settings roof" style="left: 200px" color="red" @click="createRow()">Новая запись</Button>
        <DateFullRangePicker class="roof" style="left: 345px" v-model="period" :layout="datePicker.layout" :startWeek="datePicker.startWeek" @change="changeData()"/>


<!--        <Button class="settings roof" style="left: 600px" color="red" @click="strato()">DBs</Button>-->
<!--        <Button class="settings roof" style="left: 700px" color="red" @click="stratoRun()">stratoRun</Button>-->
<!--        <input type="text" class="settings roof" style="left: 800px; width: 60px;" v-model="stratoIn">-->
<!--        <input type="text" class="settings roof" style="left: 860px; width: 60px;" v-model="stratoOut">-->

        <Modal class="actModal" v-model="settingsModal.state" :hasCloseIcon="true">
            <Tabs :datas="settingsModal.tabs" v-model="settingsModal.activeTab" />
            <br/>
            <div v-if="settingsModal.activeTab === 'site'">
                <Transfer
                        v-model="settingsSite"
                        :datas="Object.values(h.companies)" keyName="id"
                        @change="updateSettings({manager: manager, type: 'statisticsSites', settings: arguments[0]})"
                >
                    <template slot-scope="{option}" slot="item">{{option.name}}</template>
                </Transfer>
            </div>
            <div v-if="settingsModal.activeTab === 'manager'">
                <Transfer
                        v-model="settingsUsers"
                        :datas="Object.values(h.users)" keyName="id"
                        @change="updateSettings({manager: manager, type: 'statisticsUsers', settings: arguments[0]})"
                >
                    <template slot-scope="{option}" slot="item">{{option.name}}</template>
                </Transfer>
            </div>
            <div v-if="settingsModal.activeTab === 'status'">

            </div>
            <div v-if="settingsModal.activeTab === 'conversion'">

                <Button color="primary" icon="h-icon-left" @click="showConversion()">Рассчитать</Button>


                <table class="table" v-if="conversion">

                    <thead>
                    <tr>
                        <th>Менеджер</th>
                        <th>Звонков всего</th>
                        <th>Статусы</th>
                        <th>Конверсия</th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="manager in conversion">
                        <td>{{manager.name}}</td>
                        <td>{{manager.total.length}}</td>
                        <td>
                            <table class="table">

                                <thead>
                                <tr>
                                    <th v-for="call in manager.calls" style="text-align: center;">{{call.name}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr>
                                    <td v-for="call in manager.calls" style="text-align: center;">{{call.total.length}}</td>
                                </tr>
                                </tbody>

                            </table>

                        </td>
                        <td>{{calculateConversion(manager)}}%</td>

                    </tr>
                    </tbody>
<!--                    <tfoot>
                    <tr>
                        <th>2</th>
                        <th>2</th>
                        <th>2</th>
                        <th>2</th>
                    </tr>
                    </tfoot>-->



                </table>

            </div>
        </Modal>

        <Modal class="actModal" v-model="findingLid.modal"  :hasCloseIcon="true">
            <div slot="header">Поиск лида</div>
            <div>
                <table class="table">

                    <tbody>
                    <tr>
                        <th><p>id</p>
                        <th><input type="text" v-model="findingLid.id"></th>
                        <th><Button color="primary" :disabled="!findingLid.id" @click="findLid('id',findingLid.id)">Найти</Button></th>
                    </tr>
                    <tr>
                        <th> Договор </th>
                        <th><input type="text"  v-model="findingLid.contract"></th>
                        <th><Button color="primary" :disabled="!findingLid.contract" @click="findLid('contract',findingLid.contract)">Найти</Button></th>
                    </tr>
                    </tbody>

                </table>

                <table class="table" v-for="(lid,key) in findingLid.lid">

                    <thead>
                    <tr>
                        <th>Вариант</th><th>{{key+1}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>id</td><td>{{lid.id}}</td>
                    </tr>
                    <tr>
                        <td>Договор</td>{{lid.contract}}<td></td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th colspan="2"><Button color="primary" icon="h-icon-left" @click="attachLid(findingLid.id,lid.id)">Ссдать ссылку на лид</Button></th>
                    </tr>
                    </tfoot>

                </table>

            </div>


        </Modal>

        <Modal class="actModal" v-model="actionsModal.modal"  :hasCloseIcon="true">
            <table class="table">

                <thead>
                <tr>
                    <th colspan="3">Отправка писем</th>
                </tr>
                <tr>
                    <th>Тип письма</th>
                    <th>Сайт</th>
                    <th>Адресат</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>Коммерческое предложение</td>
                    <td>mossanexpert.com</td>
                    <td><Button color="primary" icon="h-icon-left">89161131656</Button></td>
                </tr>
                </tbody>

            </table>
        </Modal>


        <table class="table stat">

            <thead>
            <tr>
                <th v-for="i in table">{{i.name}}</th>
            </tr>
            <tr>
                <th v-for="(i,k) in table" v-if="k !== 'time'">
                    <template v-if="k === 'date'"><DatePicker v-model="table.date.v" :placeholder="'Дата'" @input="filterData()"/></template>
                    <template v-else-if="k === 'site'">
                        <Select v-if="h.companies" v-model="table.site.v" :datas="table.site.select()" keyName="id" titleName="name"/>
                    </template>
                    <template v-else-if="k === 'manager'">
                        <Select v-if="h.users" v-model="table.manager.v" :datas="table.manager.select()" keyName="id" titleName="name"></Select>
                    </template>
                    <template v-else-if="k === 'call_status'">
                        <Select  v-model="table.call_status.v" :datas="table.call_status.select" keyName="id" titleName="name"></Select>
                    </template>
                    <template v-else-if="k === 'cause'">
                        <input type="text" v-model="table.cause.v">
                    </template>
                    <template v-else-if="k === 'address'">
                        <input type="text" v-model="table.address.v">
                    </template>
                    <template v-else-if="k === 'cash'">
                        <input type="number" v-model="table.cash.v">
                    </template>
                    <template v-else-if="k === 'cashless'">
                        <input type="number" v-model="table.cashless.v">
                    </template>
                    <template v-else-if="k === 'phone'">
                        <input type="text" v-model="table.phone.v">
                    </template>
                    <template v-else-if="k === 'name'">
                        <input type="text" v-model="table.name.v">
                    </template>
                    <template v-else-if="k === 'contract'">
                        <input type="text" v-model="table.contract.v">
                    </template>
                    <template v-else-if="k === 'implement_date'">
                        <input type="text" v-model="table.implement_date.v">
                    </template>
                    <template v-else-if="k === 'set'">
                        <Button  class="h-btn-s" icon="h-icon-close" disabled></Button>
                    </template>
                    <template v-else></template>
                </th>
            </tr>
            </thead>

            <tbody  v-if="statistics.length">
            <template v-for="row in filterData()">

                <tr @dblclick="activeRow = row.id" v-if="activeRow !== row.id" :class="row.call_status === 10 ? 'row-deleted': ''">
                    <td class="no-wrap" rel="datetime">
                        <Button color="primary" icon="h-icon-setting" size="s" @click="actionsModal.modal = true"   >
                            {{row.date}} - {{row.time}}
<!--                        <span class="h-tag h-tag-blue">{{row.date}}</span>-->
<!--                        <span  class="h-tag h-tag-primary"></span>-->
                        </Button>
                    </td>

                    <td v-if="h.companies && row.site" >{{h.companies[row.site].name}}</td>
                    <td v-else></td>
                    <td v-if="h.users && row.manager " class="no-wrap">
                        <!--&& row.manager.isInteger-->
                        <span v-if="row.manager === 9999">g Семен и Co</span>
                        <span v-else>{{h.users[Number(row.manager)].name}}</span>
                    </td>
                    <td v-else></td>

                    <td v-if="row.call_status" >
                        <span class="h-tag " :class="statusDesign(row.call_status)">{{table.call_status.select.filter(a => a.id === row.call_status)[0].name}}</span>
                    </td>
                    <td v-else></td>

                    <td>{{row.cause}}</td>
                    <td>{{row.address}}</td>
                    <td>{{row.cash}}</td>
                    <td>{{row.cashless}}</td>
                    <td>{{row.phone}}</td>
                    <td>{{row.name}}</td>
                    <td>{{row.contract}}</td>

                    <td>{{row.implement_date}}</td>
                    <!--                <td>{{row['customer']}}</td>-->
                    <!--                <td>{{row['refusal']}}</td>-->
                    <td>
                        <Poptip content="Вы действительно хотите удалить?" @confirm="deleteRow(row.id)" placement="left">
                            <Button color="red" class="h-btn-xs" icon="h-icon-close-min"/>
                        </Poptip>
                        <a v-if="row.lid_id" target="_blank" :href="`/layout/lid/${row.lid_id}/edit`">
                            <Button color="yellow" class="h-btn-xs"  icon="h-icon-link"/>
                        </a>

                            <Poptip v-else-if="row.call_status === 7" content="Создать лид?" @confirm="createLid(row)" placement="left">
                                <Button color="primary" class="h-btn-xs"  icon="h-icon-angle-right" />
                            </Poptip>

                            <Button v-else color="primary" class="h-btn-xs" disabled icon="h-icon-angle-right"/>

                        <Button v-if="row.call_status === 7 && !row.lid_id" @click="checkModal(row.id)" color="primary" class="h-btn-xs" icon="h-icon-outbox"/>


                    </td>
                </tr>


                <tr @dblclick="saveRow(row.id)" v-if="activeRow === row.id">
                    <td class="no-wrap">
                        <span class="h-tag h-tag-blue">{{row.date}}</span>
                        <span  class="h-tag h-tag-primary">{{row.time}}</span>
                    </td>

                    <td v-if="h.companies" ><Select v-model="row.site" :datas="h.companies" keyName="id" titleName="name"></Select></td>

                    <td v-if="h.users && row.manager" class="no-wrap">
                        <span v-if="row['manager'] === 9999">Семен и Co</span><span v-else>{{h.users[Number(row['manager'])].name}}</span>
                    </td>

                    <td><Select  v-model="row.call_status" :datas="table.call_status.select" keyName="id" titleName="name"></Select></td>

                    <td><textarea v-model="row.cause"  @mouseenter="autosize" @input="autosize" ref="textarea"></textarea></td>

                    <td><textarea v-model="row.address"  @mouseenter="autosize" @input="autosize" ref="textarea"></textarea></td>

                    <td><input type="text" v-model="row.cash"></td>

                    <td><input type="text" v-model="row.cashless"></td>

                    <td><input type="text" v-model="row.phone"></td>
                    <td><input type="text" v-model="row.name"></td>
                    <td><input type="text" v-model="row.contract"></td>
                    <td><DatePicker v-model="row.implement_date" :placeholder="'Дата'" /></td>
                    <td></td>
                    <!--                <td>{{row['customer']}}</td>-->
                    <!--                <td>{{row['refusal']}}</td>-->
                    <td></td>
                </tr>
            </template>


            </tbody>
        </table>





    </div>

</template>

<script>

    import Axios from 'axios';
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "StatisticLid",
        props: ['manager','h'],
        data(){
            return {
                findingLid:{
                    modal:false,
                    id:null,
                    contract:null,
                    lid:[],
                    // id:null
                },
                activeRow: null,
                settingsModal: {
                    state: false,
                    tabs: {
                        site: 'Сайты',
                        manager: 'Менеджеры',
                        status: 'Статусы',
                        conversion: 'Конверсия'
                    },
                    activeTab: 'conversion',
                },
                actionsModal: {
                    modal:false

                },
                datePicker: {
                    startWeek: 1,
                    layout: ['year', 'quarter', 'month', 'week', 'date', 'customize'],
                },
                table: {
                    // nr: {name: 'col #'},
                    date: {
                        name: 'Дата',
                        v: null,
                        f: function (el) {
                            return el['date'] === this.v;
                        },
                    },
/*                    time: {
                        name: 'Время',
                        v: null,
                        f: function (el) {
                            return el['time'] === this.v
                        }
                    },*/
                    site: {
                        name: 'Сайт',
                        transfer : {
                            update:(arg) => {
                                this.$store.dispatch('updateSettings',{manager: this.manager, type: 'statisticsSites', settings: arg});
                            },
                        },
                        select : () => {
                            if(this.settingsSite.length){
                                return this.settingsSite.map(a => {
                                   return this.h.companies[a];
                                })
                            } else {
                                return Object.keys(this.h.companies).map((key) => {
                                    return this.h.companies[key];
                                });
                            }
                        },
                        v: null,
                        f: function (el) {
                            return el['site'] === this.v
                        }
                    },
                    manager: {
                        name: 'Менеджер',
                        type: 'select',
                        select : () => {
                            if(this.settingsUsers.length){
                                let s = this.settingsUsers.map(a => {return this.h.users[a]});
                                s.push({id: 9999, name: 'Семен и Co'});
                                return s;
                            } else {
                                let s = Object.keys(this.h.users).map((a) => {return this.h.users[a]});
                                s.push({id: 9999, name: 'Семен и Co'});
                                return s;
                            }
                        },
                        fields: {
                            9999:'Семен и Co',
                            2:'dd',
                            21:'Татьяна',
                            22:'Константин',
                            40:'Светлана',
                            20:'Андрей',
                        },
                        v: null,
                        f: function (el) {
                            return el['manager'] === this.v
                        }
                    },
                    call_status: {
                        name: 'Статус',
                        type: 'select',
                        select: [
                            {id: 1, name: 'спам'},
                            {id: 2, name: 'перезвонить'},
                            {id: 3, name: 'невыгодно'},
                            {id: 4, name: 'нецелевой'},
                            {id: 5, name: 'отказ'},
                            {id: 6, name: 'история'},
                            {id: 7, name: 'заказ'},
                            {id: 8, name: 'база'},
                            {id: 9, name: 'новая'},
                            {id: 10, name: 'на удаление'},
                        ],
                        v: null,
                        f: function (el) {
                            return el['call_status'] === this.v
                        }
                    },
                    cause: {
                        name: 'Суть',
                        v: null,
                        f: function (el) {
                            return el['cause'] && String(el['cause']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    address: {
                        name: 'Адрес',
                        v: null,
                        f: function (el) {
                            return el['address'] && String(el['address']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    cash: {
                        name: 'Нал',
                        v: null,
                        f: function (el) {
                            return el['cash'] && String(el['cash']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    cashless: {
                        name: 'Безнал',
                        v: null,
                        f: function (el) {
                            return el['cashless'] && String(el['cashless']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    phone: {
                        name: 'Телефон',
                        v: null,
                        f: function (el) {
                            return el['phone'] && String(el['phone']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    name: {
                        name: 'Имя',
                        v: null,
                        f: function (el) {
                            return el['name'] && String(el['name']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    contract: {
                        name: '№ договора',
                        v: null,
                        f: function (el) {
                            return el['contract'] && String(el['contract']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    implement_date: {
                        name: 'Выполнение',
                        v: null,
                        f: function (el) {
                            return el['implement_date'] === this.v
                        }
                    },
/*                    customer: {
                        name: 'Клиент',
                        type: 'select',
                        fields: ['Новый', 'База','Наработка'],
                        v: null,
                        f: function (el) {
                            return el['customer'] === this.v
                        }
                    },
                    refusal: {
                        name: 'Отказ',
                        v: null,
                        f: function (el) {
                            return el['refusal'] === this.v
                        }
                    },*/
                    set: {
                        name: '',
                        v: null,
                        f: function (el) {
                            return el['set'] === this.v
                        }
                    },
                },
                conversion:[],


                ///////////////////////////////
                stratoIn:0,
                stratoOut:0,
                ///////////////////////////////


/*
                csvHead: [
                    '#####',
                    'номер п/п',
                    'Время',
                    'Номер моб',
                    'Менеджер',
                    'Суть',
                    'чего обрабатываем!!',
                    'Сумма МСЭ',
                    'Сумма МСЭ Б.Н',
                    '',
                    '',
                    'Сумма МОС СЭС',
                    '',
                    '№ Договора',
                    'Дата выполнения',
                    'Отказ',
                    'Кол-во'
                ],
                // temporary parsing

                formOne: { // 3d сумма нал,сумма безнал, итого
                    name: {name: 'Дата',type:'text', fields: []},
                    name: {name: 'Новая',type:'text', fields: []},
                    name: {name: 'База',type:'text', fields: []},
                    name: {name: 'Наработка',type:'text', fields: []},
                    name: {name: 'Период',type:'text', fields: []},
                },
                formTwo: { // 3d менеджер,менеджер
                    name: {name: 'звонков',type:'text', fields: []},
                    name: {name: 'сумма нал',type:'text', fields: []},
                    name: {name: 'сумма безнал',type:'text', fields: []},
                    name: {name: 'Конверсия',type:'text', fields: []},
                    name: {name: 'Средний чек',type:'text', fields: []},
                    name: {name: 'итого',type:'text', fields: []},
                },
*/
            }
        },
        computed: {
            period:{
                get(){return this.$store.getters.lidStatisticsPeriod;},
                set(value){this.$store.commit('LID_STATISTICS_PERIOD',value);}
            },
            settingsSite: {
                get() {
                    return Object.keys(this.$store.getters.settings).indexOf('statisticsSites') !== -1 ? this.$store.getters.settings.statisticsSites.settings : []
                },
                set(){return false}

            },
            settingsUsers: {
                get() {
                    return Object.keys(this.$store.getters.settings).indexOf('statisticsUsers') !== -1 ? this.$store.getters.settings.statisticsUsers.settings : []
                },
                set(){return false}
            },
            settings: {
                get() {return this.$store.getters.settings},
                set(value) {this.$store.dispatch('readSettings', this.manager);}
            },
            statistics: {
                get(){return this.$store.getters.lidStatistic;},
                set(value){this.$store.dispatch('readLidStatistics');}
            },
        },

        methods: {
            ...mapActions(['updateSettings']),

            statusDesign(id){
                let cl = '';
                switch (id) {
                    case 7:
                        cl = 'row_green';
                        break;
                    case 5:
                        cl = 'row_red';
                        break;
                    case 10:
                        cl = 'row_red';
                        break;
                    case 2:
                        cl = 'row_yellow';
                        break;
                    case 1:
                        cl = 'row_grey';
                        break;
                    case 3:
                        cl = 'row_grey';
                        break;
                    case 4:
                        cl = 'row_grey';
                        break;
                    default:
                        cl = 'row_default';
                }
                return cl;
            },
/*    {id: 1, name: 'спам'},
    {id: 2, name: 'перезвонить'},
    {id: 3, name: 'невыгодно'},
    {id: 4, name: 'нецелевой'},
    {id: 5, name: 'отказ'},
    {id: 6, name: 'история'},
    {id: 7, name: 'заказ'},
    {id: 8, name: 'база'},
    {id: 9, name: 'новая'},*/

            showConversion(){
                let managers = this.table.manager.select();

                this.conversion = managers.map(manager => {
                    let total = this.statistics.filter(f => {
                        return f.manager === manager.id
                    });
                    let calls = this.table.call_status.select.map(status => {
                        let perStatus = this.statistics.filter(f => {
                            return f.manager === manager.id && f.call_status === status.id
                        });

                        return { name:status.name, id:status.id, total:perStatus}

                    });

                    return {manager: manager.id, name: manager.name, calls:calls, total: total }
                });


                // console.log('this.conversion',this.conversion);

            },



            calculateConversion(m){
                let order = m.calls.find(c => c.id === 7);
                let perCent = (order.total.length * 100)/m.total.length;
                return perCent ? Math.floor(perCent) : 0;
            },

            createRow(){
                this.$store.dispatch('createLidStatistics', this.manager);
            },
            saveRow(id){
                this.activeRow = null;
                let ob = this.statistics.find(a => a.id === id);
                this.$store.dispatch('updateLidStatistics',ob)
            },
            deleteRow(id){
                if(this.manager === 31){
                    this.$store.dispatch('deleteLidStatistics', id);
                } else {
                    this.$Message['warn']("Удалять может только уполномоченное лицо. Просто установите статус записи 'На удаление");

                }

            },

            createLid(row){
                this.$store.dispatch('creteLid', row);
            },

            changeData(){
                this.$store.dispatch('readLidStatistics');
            },

            blender(arr, filters) {

                return arr.filter(function (el) {
                    return filters.every(function (filter) {
                        return filter.f(el)
                    })
                })
            },
            filterData() {
                let stat = this.statistics;
                let arF = [];
                for (let k in this.table) if (this.table[k].v) {
                    arF.push(this.table[k])
                }
                return this.blender(stat, arF);
            },

            unique(key) {
                return [...new Set(this.lidStatistic.map(item => item[key]))];
            },
            autosize() {
                this.$refs.textarea.forEach((v,k) => {
                    this.$refs.textarea[k].style.minHeight = this.$refs.textarea[k].scrollHeight +'px';
                })
            },

            checkModal(id){
                this.$set(this.$data,'findingLid',{
                    modal:true,
                    id:null,
                    contract:null,
                    lid:[],
                    id:id
                });

            },

            attachLid(row_id,lid_id){
                this.$store.dispatch('updateLidStatistics',{
                    id:row_id,
                    lid_id:lid_id,
                    cost_date: moment().format('YYYY-MM-DD')
                })
            },


            async findLid(key,val){
                try {
                    let response = await Axios.post('/lid_statistic/async',{
                        func: 'findLid',
                        values: {key,val}
                    });
                    if(response.data){
                        this.findingLid.lid = response.data;
                    }
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },

            strato(){
                this.stratoIn = this.statistics.length
            },
             stratoRun(){
                for(let s in this.statistics){
                     this.stratoHttp(this.statistics[s]);
                    this.$set(this.$data,'stratoOut',s)
                }

                // let index = 0;
                // let col = await this.statistics[index];
                // await this.stratoHttp()
            },
             stratoHttp(payload){
                try {
                    let response =  Axios.post('/manipulations',{
                        func: 'setStrato',
                        values: payload
                    });
                    // console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },



        },
         created() {
             this.$store.dispatch('readLidStatistics');
             this.$store.dispatch('readSettings',this.manager);

        },
    }
</script>


<style lang="scss">

    .row_green {background:green;  color: white}
    .row_red {background:red;  color: white}
    .row_yellow {background:yellow;  color: black}
    .row_grey {background:grey;  color: white}
    .row_default {}
    .settings {
        position: absolute;
        top: -56px;
        left: 44px;
        z-index: 1500;
    }
    .stat input {
        width: 100%!important;
    }

    .stat th {
        text-align: center;
    }
    .stat td {
        border-right: 2px solid #e2e1e1!important;
        border-bottom: 2px solid #e2e1e1!important;
    }
    .stat td:last-of-type {
        border-right: none!important;
        border-bottom: 2px solid #e2e1e1!important;
    }
    .stat th {
        border-top: 2px solid #e2e1e1!important;
        border-bottom: 2px solid #e2e1e1!important;
    }


    .stat th:nth-of-type(1) { width: 160px; max-width: 160px}
    .stat td:nth-of-type(1) { width: 160px; max-width: 160px}

    .stat th:nth-of-type(2) { width: 120px; max-width: 120px}
    .stat td:nth-of-type(2) { width: 120px; max-width: 120px}

    .stat th:nth-of-type(3) { max-width: 150px;   width: 150px;}

    .stat th:nth-of-type(4) { max-width: 150px;   width: 150px;}

    .stat th:nth-of-type(5) { width: 200px; max-width: 200px}

    .stat th:nth-of-type(6) { width: 200px; max-width: 200px}

    .stat th:nth-of-type(7) { width: 100px; max-width: 100px}
    .stat td:nth-of-type(7) { width: 100px; max-width: 100px}

    .stat th:nth-of-type(8) { width: 100px; max-width: 100px}
    .stat td:nth-of-type(8) { width: 100px; max-width: 100px}

    .stat th:nth-of-type(9) { width: 120px; max-width: 120px}
    .stat td:nth-of-type(9) { width: 120px; max-width: 120px}

    .stat th:nth-of-type(10) { width: 120px; max-width: 120px}
    .stat td:nth-of-type(10) { width: 120px; max-width: 120px}

    .stat th:nth-of-type(11) { width: 120px; max-width: 120px}
    .stat td:nth-of-type(11) { width: 120px; max-width: 120px}

    .stat th:nth-of-type(12) { width: 120px; max-width: 120px}
    .stat td:nth-of-type(12) { width: 120px; max-width: 120px}

    .stat th:nth-of-type(13) { width: 130px; max-width: 130px}
    .stat td:nth-of-type(13) { width: 130px; max-width: 130px}

    .stat textarea {
        width: 100%;
        resize: vertical;
        text-align: left;
        display: block;
        height: auto;
        overflow: hidden;
    }




    .clearFilter {
        position: absolute;
        top: -56px;
        left: 100px;
        z-index: 1500;
    }
    .createLid {
        display: block;
        position: absolute;
        top: -49px;
        right: 303px;
        z-index: 1500;
        color: #fff;

    }
    .createLid:hover {
        text-decoration: none;
        color: #fff!important;
    }
    .cntItems {
        position: absolute;
        top: -51px;
        left: 240px;
        z-index: 1500;
        display: inline-flex;
        color: #000!important;
    }
    .cntItems span {
        color: #000!important;
    }
    .roof {
        position: absolute;
        top: -56px;
        z-index: 1500;
    }

    .h-transfer {
        width: 750px;
    }

    .h-transfer-list {
        min-height: 450px;
    }

    .h-modal .h-notify-content {
        margin: 20px;

    }
    table.h-table td {
        max-width: 200px;

    }

    table.tableSection {
        display: table;
        width: 100%;
        background: #fff;
        border-color: grey;
        margin-bottom: 40px;
        border-bottom: 20px solid white;
    }
    table.tableSection thead, table.tableSection tbody {
        float: left;
        width: 100%;
    }
    table.tableSection tbody {
        overflow: auto;
        height: 150px;
    }
    table.tableSection tr {
        width: 100%;
        display: table;
        text-align: left;
    }
    table.tableSection th, table.tableSection td {
        width: 20%;
    }
    table.tableSection th {
        text-align: center;
    }
    table.tableSection td {
        border-left: 1px solid #f4f4f4;
    }
    .row-deleted {
        opacity: 0.1;
    }

</style>
