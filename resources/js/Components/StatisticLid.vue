<template>
    <div>

        <Button class="settings roof" style="left: 54px" color="yellow" icon="h-icon-setting"
                @click="settingsModal.state = !settingsModal.state"/>
        <Button class="settings roof" style="left: 107px" color="red" @click="createRow()">Новая запись</Button>
        <DateFullRangePicker class="roof" style="left: 235px" v-model="period" :layout="datePicker.layout"
                             :startWeek="datePicker.startWeek" @change="changeData()"/>
<!--editRow(edit.clonedRow,'open')-->
        <Button v-if="Object.keys(edit.clonedRow).length" class="settings roof" style="left: 480px" color="primary"
                icon="h-icon-top" @click="$store.dispatch('lidStatisticEditRow',{ id: lidStatisticEditClonedRow, flag: 'open', manager: manager })">Редактировать
        </Button>


        <!--        <Button class="settings roof" style="left: 600px" color="red" @click="strato()">DBs</Button>-->
        <!--        <Button class="settings roof" style="left: 700px" color="red" @click="stratoRun()">stratoRun</Button>-->
        <!--        <input type="text" class="settings roof" style="left: 800px; width: 60px;" v-model="stratoIn">-->
        <!--        <input type="text" class="settings roof" style="left: 860px; width: 60px;" v-model="stratoOut">-->

        <Modal class="actModal" v-model="settingsModal.state" :hasCloseIcon="true">
            <Tabs :datas="settingsModal.tabs" v-model="settingsModal.activeTab"/>
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
                                    <td v-for="call in manager.calls" style="text-align: center;">
                                        {{call.total.length}}
                                    </td>
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

        <Modal class="actModal" v-model="findingLid.modal" :hasCloseIcon="true">
            <div slot="header">Поиск лида</div>
            <div>
                <table class="table">

                    <tbody>
                    <tr>
                        <th><p>id</p>
                        <th><input type="text" v-model="findingLid.id"></th>
                        <th>
                            <Button color="primary" :disabled="!findingLid.id" @click="findLid('id',findingLid.id)">
                                Найти
                            </Button>
                        </th>
                    </tr>
                    <tr>
                        <th> Договор</th>
                        <th><input type="text" v-model="findingLid.contract"></th>
                        <th>
                            <Button color="primary" :disabled="!findingLid.contract"
                                    @click="findLid('contract',findingLid.contract)">Найти
                            </Button>
                        </th>
                    </tr>
                    </tbody>

                </table>

                <table class="table" v-for="(lid,key) in findingLid.lid">

                    <thead>
                    <tr>
                        <th>Вариант</th>
                        <th>{{key+1}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>id</td>
                        <td>{{lid.id}}</td>
                    </tr>
                    <tr>
                        <td>Договор</td>
                        {{lid.contract}}
                        <td></td>
                    </tr>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th colspan="2">
                            <Button color="primary" icon="h-icon-left" @click="attachLid(findingLid.id,lid.id)">Ссдать
                                ссылку на лид
                            </Button>
                        </th>
                    </tr>
                    </tfoot>

                </table>

            </div>


        </Modal>

        <Modal class="actModal" v-model="actionsModal.modal" :hasCloseIcon="true">
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
                    <td>
                        <Button color="primary" icon="h-icon-left">89161131656</Button>
                    </td>
                </tr>
                </tbody>

            </table>
        </Modal>
        <edit />


        <div class="statistic-table-container">
            <table class="statistic-table">

                <thead>
                <tr>
                    <th v-for="i in table" :style="i.width ? 'max-width:' + i.width + 'px' : 'width:auto'">
                        {{i.name}}
                    </th>
                </tr>
                <tr>
                    <th v-for="(i,k) in table" v-if="k !== 'time'"
                        :style="i.width ? 'max-width:' + i.width + 'px' : 'width:auto'">
                        <template v-if="k === 'id'">
                            <input type="text" v-model="table.id.v">
                        </template>
                        <template v-else-if="k === 'date'">
                            <DatePicker v-model="table.date.v" :placeholder="'Дата'" @input="filterData()"/>
                        </template>
                        <template v-else-if="k === 'site'">
                            <Select v-if="h.companies" v-model="table.site.v" :datas="table.site.select()" keyName="id"
                                    titleName="name"/>
                        </template>
                        <template v-else-if="k === 'manager'">
                            <Select v-if="h.users" v-model="table.manager.v" :datas="table.manager.select()"
                                    keyName="id" titleName="name"></Select>
                        </template>
                        <template v-else-if="k === 'call_status'">
                            <Select v-model="table.call_status.v" :datas="table.call_status.select" keyName="id"
                                    titleName="name"></Select>
                        </template>
                        <template v-else-if="k === 'app_status'">
                            <Select v-model="table.app_status.v" :datas="table.app_status.select" keyName="id"
                                    titleName="name"></Select>
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
                            <input type="text" v-model="table.contract.v" style="width: 100px;">
                        </template>
                        <template v-else-if="k === 'implement_date'">
                            <input type="text" v-model="table.implement_date.v" style="width: 100px;">
                        </template>
                        <template v-else-if="k === 'set'">
                            <Button class="h-btn-s" icon="h-icon-close" disabled></Button>
                        </template>
                        <template v-else></template>
                    </th>
                </tr>
                </thead>

                <tbody v-if="statistics.length">
                <template v-for="(row, rowKey) in filterData()">
                    <!--@dblclick="activeRow = row.id" v-if="activeRow !== row.id" #ecf0f5-->
                    <!-- @dblclick="editRow(row,'open')"-->
                    <tr
                            :id="'tr'+row.id"
                            @dblclick="$store.dispatch('lidStatisticEditRow',{id: row, flag: 'open', manager: Number(manager) })"
                            @click="makeActive(row)"
                            :class="row.call_status === 10 ? 'row-deleted': ''"
                            :style="row.editing ? 'background: #ff000014' : ''"
                    >
                        <td class="indicator-nest">
                            <div class="indicator" v-if="row.editing">
                                <span class="h-tag h-tag-yellow">Редактирует {{h.users[row.editing].name}} </span>
                            </div>
                            {{row.id}}
                            <template v-if="Number(manager) === 1"> - {{rowKey}}</template>
                        </td>
                        <td class="no-wrap" rel="datetime">
                            <span class="h-tag h-tag-blue">{{row.date}}</span>
                            <span class="h-tag h-tag-primary">{{row.time}}</span>
                        </td>

                        <td v-if="h.companies && row.site">{{h.companies[row.site].name}}</td>
                        <td v-else></td>
                        <td v-if="h.users && row.manager " class="no-wrap">
                            <!--&& row.manager.isInteger-->
                            <span v-if="row.manager === 9999">g Семен и Co</span>
                            <span v-else>{{h.users[Number(row.manager)].name}}</span>
                        </td>
                        <td v-else></td>

                        <td v-if="row.call_status">
                            <span class="h-tag " :class="statusDesign(row.call_status)">{{table.call_status.select.filter(a => a.id === row.call_status)[0].name}}</span>
                        </td>
                        <td v-if="row.app_status">
                            <span class="h-tag " :class="statusDesign(row.app_status)">{{table.app_status.select.filter(a => a.id === row.app_status)[0].name}}</span>
                        </td>
                        <td v-else></td>

                        <td>
                            <div>{{row.cause}}</div>
                        </td>
                        <td>{{row.address}}</td>
                        <td>{{row.cash}}</td>
                        <td>{{row.cashless}}</td>
                        <td>
                            <!--                            <the-mask :value="row.phone" mask="+7(###) ### ## ##" disabled style="width: 110px"/>-->
                            {{row.phone}}
                        </td>
                        <td>{{row.name}}</td>
                        <td style="max-width: 100px;">{{row.contract}}</td>

                        <td>{{row.implement_date}}</td>
                        <!--                <td>{{row['customer']}}</td>-->
                        <!--                <td>{{row['refusal']}}</td>-->
                    </tr>

                </template>


                </tbody>
            </table>
        </div>


    </div>

</template>


<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import Edit from "../Components/Lid/Statistic/Edit";
    export default {
        name: "StatisticLid",
        props: ['manager', 'h'],
        data() {
            return {
                globalRole: [4],
                search: {
                    model: '',
                    result: []
                },
                edit: {
                    modal: false,
                    clonedRow: {},
                },

                findingLid: {
                    modal: false,
                    id: null,
                    contract: null,
                    lid: [],
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
                    modal: false

                },
                datePicker: {
                    startWeek: 1,
                    layout: ['year', 'quarter', 'month', 'week', 'date', 'customize'],
                },
                table: {
                    // nr: {name: 'col #'},
                    id: {
                        name: 'ID',
                        width: 70,
                        v: null,
                        f: function (el) {
                            return el['id'] === this.v;
                        },
                    },
                    date: {
                        name: 'Дата',
                        width: 150,
                        v: null,
                        f: function (el) {
                            return el['date'] === this.v;
                        },
                    },
                    site: {
                        name: 'Сайт',
                        width: 150,
                        transfer: {
                            update: (arg) => {
                                this.$store.dispatch('updateSettings', {
                                    manager: this.manager,
                                    type: 'statisticsSites',
                                    settings: arg
                                });
                            },
                        },
                        select: () => {
                            if (this.settingsSite.length) {
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
                        width: 150,
                        type: 'select',
                        select: () => {
                            if (this.settingsUsers.length) {
                                let s = this.settingsUsers.map(a => {
                                    return this.h.users[a]
                                });
                                s.push({id: 9999, name: 'Семен и Co'});
                                return s;
                            } else {
                                let s = Object.keys(this.h.users).map((a) => {
                                    return this.h.users[a]
                                });
                                s.push({id: 9999, name: 'Семен и Co'});
                                return s;
                            }
                        },
                        fields: {
                            9999: 'Семен и Co',
                            2: 'dd',
                            21: 'Татьяна',
                            22: 'Константин',
                            40: 'Светлана',
                            20: 'Андрей',
                        },
                        v: null,
                        f: function (el) {
                            return el['manager'] === this.v
                        }
                    },
                    call_status: {
                        name: 'Статус',
                        width: 150,
                        type: 'select',
                        select: [
                            {id: 1, name: 'спам'},
                            {id: 2, name: 'перезвонить'},
                            {id: 3, name: 'невыгодно'},
                            {id: 4, name: 'нецелевой'},
                            {id: 5, name: 'отказ'},
                            {id: 6, name: 'история'}, //
                            {id: 7, name: 'заказ'},
                            {id: 8, name: 'база'}, //
                            {id: 9, name: 'новая'}, //
                            {id: 10, name: 'на удаление'},
                        ],
                        v: null,
                        f: function (el) {
                            return el['call_status'] === this.v
                        }
                    },
                    app_status: {
                        name: 'Статус заявки',
                        width: 150,
                        type: 'select',
                        select: [
                            {id: 1, name: 'новая'},
                            {id: 2, name: 'база'},
                            {id: 3, name: 'рекоммендация'},
                        ],
                        v: null,
                        f: function (el) {
                            return el['app_status'] === this.v
                        }
                    },
                    cause: {
                        name: 'Суть',
                        width: 150,
                        v: null,
                        f: function (el) {
                            return el['cause'] && String(el['cause']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    address: {
                        name: 'Адрес',
                        width: 150,
                        max: true,
                        v: null,
                        f: function (el) {
                            return el['address'] && String(el['address']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    cash: {
                        name: 'Нал',
                        width: 75,
                        max: true,
                        v: null,
                        f: function (el) {
                            return el['cash'] && String(el['cash']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    cashless: {
                        name: 'Безнал',
                        width: 75,
                        v: null,
                        f: function (el) {
                            return el['cashless'] && String(el['cashless']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    phone: {
                        name: 'Телефон',
                        width: 110,
                        v: null,
                        f: function (el) {
                            return el['phone'] && String(el['phone']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    name: {
                        name: 'Имя',
                        width: 150,
                        v: null,
                        f: function (el) {
                            return el['name'] && String(el['name']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    contract: {
                        name: '№ договора',
                        width: 100,
                        v: null,
                        f: function (el) {
                            return el['contract'] && String(el['contract']).toLowerCase().indexOf(this.v.toLowerCase()) !== -1
                        }
                    },
                    implement_date: {
                        name: 'Выполнение',
                        width: 150,
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
                    /*                    set: {
                                            name: '',
                                            width: 150,
                                            v: null,
                                            f: function (el) {
                                                return el['set'] === this.v
                                            }
                                        },*/
                },
                conversion: [],


                ///////////////////////////////
                stratoIn: 0,
                stratoOut: 0,
                ///////////////////////////////

            }
        },
        components: {Edit},
        computed: {
            // ...mapGetters(['lidStatisticSearchCustomer','lidStatisticEditClonedRow','lidStatisticEditModal','lidStatisticEditing','lidStatisticField']),
            tFunc() {
                return 'tFunc';
            },
            period: {
                get() {
                    return this.$store.getters.lidStatisticsPeriod;
                },
                set(value) {
                    this.$store.commit('LID_STATISTICS_PERIOD', value);
                }
            },
            settingsSite: {
                get() {
                    return Object.keys(this.$store.getters.settings).indexOf('statisticsSites') !== -1 ? this.$store.getters.settings.statisticsSites.settings : []
                },
                set() {
                    return false
                }
            },
            settingsUsers: {
                get() {
                    return Object.keys(this.$store.getters.settings).indexOf('statisticsUsers') !== -1 ? this.$store.getters.settings.statisticsUsers.settings : []
                },
                set() {
                    return false
                }
            },
            settings: {
                get() {
                    return this.$store.getters.settings
                },
                set(value) {
                    this.$store.dispatch('readSettings', this.manager);
                }
            },
            statistics: {
                get() {
                    return this.$store.getters.lidStatistic;
                },
                set(value) {
                    this.$store.dispatch('readLidStatistics');
                }
            },
            editModal:{
                get(){
                    return this.$store.getters.lidStatisticEditModal
                },
                set(value){
                    this.$store.commit('LID_STATISTICS_EDIT_MODAL',value);
                }
            },
            editRow:{
                get(){
                    return this.$store.getters.lidStatisticEditModal
                },
                set(value){
                    this.$store.commit('LID_STATISTICS_EDIT_ROW',value);
                }
            }
        },
        watch: {
            'search.model'(n, o) {
                if (n || o) {
                    this.$store.dispatch('lidStatisticSearchCustomer', this.search.model)
                }
            }
        },

        methods: {
            ...mapActions(['updateSettings']),

            searchCustomer() {
                this.$store.dispatch('lidStatisticSearchCustomer', this.search.model)
                // if(this.search.model.length >= 2){
                //
                // }

            },
            statusDesign(id) {
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

            changeCallStatus() {

            },

            showConversion() {
                let managers = this.table.manager.select();

                this.conversion = managers.map(manager => {
                    let total = this.statistics.filter(f => {
                        return f.manager === manager.id
                    });
                    let calls = this.table.call_status.select.map(status => {
                        let perStatus = this.statistics.filter(f => {
                            return f.manager === manager.id && f.call_status === status.id
                        });

                        return {name: status.name, id: status.id, total: perStatus}

                    });

                    return {manager: manager.id, name: manager.name, calls: calls, total: total}
                });


                // console.log('this.conversion',this.conversion);

            },


            calculateConversion(m) {
                let order = m.calls.find(c => c.id === 7);
                let perCent = (order.total.length * 100) / m.total.length;
                return perCent ? Math.floor(perCent) : 0;
            },

            createRow() {
                this.$store.dispatch('createLidStatistics', this.manager);
            },
            async saveRow(id) {

                // let ob = this.statistics.find(a => a.id === id);
                await this.$store.dispatch('updateLidStatistics', this.edit.clonedRow);
                this.activeRow = await null;
                this.edit.modal = false;
            },
            deleteRow(id) {
                if (this.manager === 31) {
                    this.$store.dispatch('deleteLidStatistics', id);
                } else {
                    this.$Message['warn']("Удалять может только уполномоченное лицо. Просто установите статус записи 'На удаление");

                }

            },

            async createLid(row) {
                await this.$store.dispatch('creteLid', row);
                // await this.saveRow(row.id);
                // let item = await this.statistics.find(o => o.id === row.id);
                // console.log('item',item);
                // this.clonedRow = {...item}
            },

            changeData() {
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
                this.$refs.textarea.forEach((v, k) => {
                    this.$refs.textarea[k].style.minHeight = this.$refs.textarea[k].scrollHeight + 'px';
                })
            },

            checkModal(id) {
                this.$set(this.$data, 'findingLid', {
                    modal: true,
                    // id: null,
                    contract: null,
                    lid: [],
                    id: id
                });

            },

            attachLid(row_id, lid_id) {
                this.$store.dispatch('updateLidStatistics', {
                    id: row_id,
                    lid_id: lid_id,
                    cost_date: moment().format('YYYY-MM-DD')
                })
            },


            async findLid(key, val) {
                try {
                    let response = await axios.post('/lid_statistic/async', {
                        func: 'findLid',
                        values: {key, val}
                    });
                    if (response.data) {
                        this.findingLid.lid = response.data;
                    }
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },

            strato() {
                this.stratoIn = this.statistics.length
            },
            stratoRun() {
                for (let s in this.statistics) {
                    this.stratoHttp(this.statistics[s]);
                    this.$set(this.$data, 'stratoOut', s)
                }

                // let index = 0;
                // let col = await this.statistics[index];
                // await this.stratoHttp()
            },
            stratoHttp(payload) {
                try {
                    let response = axios.post('/manipulations', {
                        func: 'setStrato',
                        values: payload
                    });
                    // console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },

            makeActive(row) {
                document.querySelectorAll('.short-view').forEach(function (el) {
                    el.classList.remove('short-view');
                })
                let tr = document.querySelector('#tr' + row.id).classList.toggle('short-view');
                this.edit.clonedRow = {...row}

            },
            /*
            async editRow(row, flag) {
                try {
                    let response = await axios.post('/lid_statistic/async', {
                        func: 'checkEditing',
                        id: row.id,
                        flag: flag,
                        manager: Number(this.manager)
                    });
                    if (await response.data) {
                        if (response.data === Number(this.manager) && flag === 'open') {
                            this.edit.clonedRow = {...row};
                            this.edit.modal = true;
                        } else if (flag === 'close') {
                            this.edit.modal = false;
                            this.edit.clonedRow = {};
                        } else {
                            let name = await this.h.users[response.data].name
                            await this.$Message['warn'](`Эта строка редактируется пользователем <b>${name}</b>`)
                        }
                    }
                } catch (error) {
                    console.log(error);
                }
            }
*/
        },
        created() {

            this.$store.dispatch('readLidStatistics');
            this.$store.dispatch('readSettings', this.manager);

            Echo.join('statistic')
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
                .listen('StatisticEvent', (event) => {
                    switch (event.result.action) {
                        case 'update':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_UPDATE', event.result.row)
                            break;
                        case 'create':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_CREATE', event.result.row)
                            break;
                        case 'open':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_UPDATE', event.result.row)
                            break;
                        case 'close':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_UPDATE', event.result.row)
                            break;
                        case 'order':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_UPDATE', event.result.row)
                            this.$store.commit('LID_STATISTICS_REORDER')
                            break;
                        case 'createLid':
                            this.edit.clonedRow = event.result.row;
                            this.$store.commit('LID_STATISTICS_UPDATE', event.result.row)
                            break;

                    }

                    // this.makeNotice(event);


                    // this.$store.commit('UPDATE_MESSAGES', {user:this.user,row:event.row} )
                    // event.row.to_users.map(m => {
                    //     m == this.user ? this.playSound() : '';
                    // })
                    //
                    //
                    //
                    // this.users.forEach((user, index) => {
                    //     if (user.id === event.user.id) {
                    //         user.typing = false;
                    //         this.$set(this.users, index, user);
                    //     }
                    // });
                });


        },
    }
</script>


<style lang="scss">


    .row_green {
        background: green;
        color: white
    }

    .row_red {
        background: red;
        color: white
    }

    .row_yellow {
        background: yellow;
        color: black
    }

    .row_grey {
        background: grey;
        color: white
    }

    .row_default {
    }

        .settings {
            position: absolute;
            top: -56px;
            left: 44px;
            z-index: 1500;
        }

    /* .stat input {
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
     .stat td:nth-of-type(13) { width: 130px; max-width: 130px}*/

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
        color: #fff !important;
    }

    .cntItems {
        position: absolute;
        top: -51px;
        left: 240px;
        z-index: 1500;
        display: inline-flex;
        color: #000 !important;
    }

    .cntItems span {
        color: #000 !important;
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


</style>
