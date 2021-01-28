<template>
    <div>


<!--        <Button class="settings" color="yellow" icon="h-icon-setting"/>-->
        <div class="cntItems"> <span class="h-tag ">{{csv.length}}</span> </div>
<!--        <Button class="clearFilter" color="red" >Сброс фильтра</Button>-->
<!--        <a class="createLid" href="/vue/lid/create">СОЗДАТЬ НОВЫЙ ЛИД</a>-->

        <Button class="roof" style="left: 300px" color="primary" icon="h-icon-left" @click="importCsv()">Импорт</Button>

        <input type="text" class="roof" style="left: 100px; width: 60px;" v-model="shsh">


        <input type="text" class="roof tt" style="left: 400px; width: 60px;" v-model="start">
        <input type="text" class="roof" style="left: 408px; width: 60px;" v-model="limit" >
        <Button color="primary" class="roof" style="left: 560px"  icon="h-icon-left" @click="save()">===>></Button>

        <div style="height: 350px; overflow-y: auto">
            <table class="table">

                <thead>
                <tr>
                    <th v-for="i in statHeadings">{{i.name}}</th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="row in csv" v-if="row">
                    <td>{{row['nr']}}</td>
                    <td>{{row['date']}}</td>
                    <td>{{row['time']}}</td>
                    <td>{{row['site']}}</td>
                    <td>{{statHeadings.manager.fields[row['manager']]}}</td>
                    <td>{{statHeadings.call_status.fields[row['call_status']]}}</td>
                    <td>{{row['cause']}}</td>
                    <td>{{row['address']}}</td>
                    <td>{{row['cash']}}</td>
                    <td>{{row['cashless']}}</td>
                    <td>{{row['phone']}}</td>
                    <td>{{row['name']}}</td>
                    <td>{{row['contract']}}</td>
                    <td>{{row['implement_date']}}</td>
                    <td>{{row['customer']}}</td>
                    <td>{{row['refusal']}}</td>
                </tr>
                </tbody>

                <!--            <tfoot>-->
                <!--            <tr>-->
                <!--                <th></th>-->
                <!--            </tr>-->
                <!--            </tfoot>-->
                <!--            -->

            </table>
        </div>

        <div style="height: 350px; overflow-y: auto">

            <table class="h-table w100">

                <thead class="h-table-head">
                <tr>
                    <th v-for="head in csvHead" style="white-space: nowrap">{{head}}</th>
                </tr>
                </thead>

                <tbody class="h-table-body">
                <tr v-for="(body,bodyKey) in csv">
                    <template v-for="b in body.col">
                        <td>
                            {{b}}
                        </td>
                    </template>

                </tr>
                </tbody>

            </table>
        </div>



    </div>

</template>

<script>

    import Axios from 'axios';
    // import csv from './Crm/stat.js'
    // const csv = require('./Crm/stat.json');
    export default {
        name: "StatisticLid",
        data(){
            return {
                shsh:'',
                // temporary parsing
                start: 10723,
                limit: 11000,//995 5994
                csv: {},
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
                statHeadings: {
                    nr: {name: 'col #'},
                    date: {name: 'Дата'},
                    time: {name: 'Время'},
                    site: {name: 'Сайт'},
                    manager: {
                        name: 'Менеджер',
                        type: 'select',
                        fields: {
                            9999:'Семен и Co',
                            2:'dd',
                            21:'Татьяна',
                            22:'Константин',
                            40:'Светлана',
                            20:'Андрей',
                        }
                    },
                    call_status: {
                        name: 'Статус звонка',
                        type: 'select',
                        fields: {
                            1:'спам',
                            2:'перезвонить',
                            3:'невыгодно',
                            4:'нецелевой',
                            5:'отказ',
                            6:'история',
                            7:'заказ',
                        }
                    },
                    cause: {name: 'Суть'},
                    address: {name: 'Адрес'},
                    cash: {name: 'Сумма нал'},
                    cashless: {name: 'Сумма безнал'},
                    phone: {name: 'Телефон'},
                    name: {name: 'Имя'},
                    contract: {name: 'Номер договора'},
                    implement_date: {name: 'Дата Выполнения'},
                    customer: {
                        name: 'Клиент',
                        type: 'select',
                        fields: ['Новый', 'База','Наработка']
                    },
                    refusal: {name: 'Отказ'},
                },
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

                statArray:[],
                tempArr:[],
                comp: {
                    "1": {
                        "id": 1,
                        "name": "mossanexpert.com",
                        "created_at": "2019-06-26 20:35:03",
                        "updated_at": "2019-06-26 20:35:54",
                        "key": "1"
                    },
                    "2": {
                        "id": 2,
                        "name": "моссэс.рф",
                        "created_at": "2019-06-26 20:36:31",
                        "updated_at": "2019-06-26 20:36:31",
                        "key": "2"
                    },
                    "3": {
                        "id": 3,
                        "name": "mos-ses.ru",
                        "created_at": "2019-06-26 20:36:41",
                        "updated_at": "2019-06-26 20:36:41",
                        "key": "3"
                    },
                    "4": {
                        "id": 4,
                        "name": "mgcd24.ru",
                        "created_at": "2019-06-26 20:36:53",
                        "updated_at": "2019-06-26 20:36:53",
                        "key": "4"
                    },
                    "5": {
                        "id": 5,
                        "name": "ddd1.ru",
                        "created_at": "2019-06-26 20:37:11",
                        "updated_at": "2019-06-26 20:37:11",
                        "key": "5"
                    },
                    "6": {
                        "id": 6,
                        "name": "mobilses.ru",
                        "created_at": "2019-06-26 20:37:20",
                        "updated_at": "2019-06-26 20:37:20",
                        "key": "6"
                    },
                    "7": {
                        "id": 7,
                        "name": "moskva-dez.ru",
                        "created_at": "2019-06-26 20:37:31",
                        "updated_at": "2019-06-26 20:37:31",
                        "key": "7"
                    },
                    "8": {
                        "id": 8,
                        "name": "borshevikoff.ru (off)",
                        "created_at": "2019-06-26 20:38:05",
                        "updated_at": "2019-06-26 20:38:05",
                        "key": "8"
                    },
                    "9": {
                        "id": 9,
                        "name": "sesteam.ru",
                        "created_at": "2019-06-26 20:40:47",
                        "updated_at": "2019-06-26 20:40:47",
                        "key": "9"
                    },
                    "10": {
                        "id": 10,
                        "name": "nsk.sesteam.ru",
                        "created_at": "2019-06-26 20:41:19",
                        "updated_at": "2019-06-26 20:41:19",
                        "key": "10"
                    },
                    "11": {
                        "id": 11,
                        "name": "kgd.sesteam.ru",
                        "created_at": "2019-06-26 20:41:32",
                        "updated_at": "2019-06-26 20:41:32",
                        "key": "11"
                    },
                    "12": {
                        "id": 12,
                        "name": "klopodav.ru",
                        "created_at": "2019-06-26 20:45:43",
                        "updated_at": "2019-06-26 20:45:43",
                        "key": "12"
                    }
                }
            }
        },
        methods: {

            async save(){
                for (let b of this.csv){
                    let ob =  {
                        address:b.address,
                        call_status:b.call_status,
                        cash:b.cash,
                        cashless:b.cashless,
                        cause:b.cause,
                        col:b.col,
                        contract:b.contract,
                        customer:b.customer,
                        date:b.date,
                        implement_date:b.implement_date,
                        manager:b.manager,
                        name:b.name,
                        nr:b.nr,
                        phone:b.phone,
                        refusal:b.refusal,
                        site:b.site,
                        time:b.time
                    }

                    await this.saveNext(b.nr,ob);

                }
            },
            async saveNext(nr,ob){
                try {
                    let response = await Axios.post('/statistic/crm/async',{
                        func: 'saveStat',
                        id: nr,
                        values: ob
                    });
                    if(response.data){
                        this.$set(this.$data,'shsh',response.data);
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            // PARSE CSV
            htmlSpecChars(val){
                if(val){
                    return  val.replace(/[^_0-9A-Za-zА-Яа-я ]/gim, ' ');
                } else {
                    return '';
                }

            },
            watchDate(val){
                // let col = val['col'][1].trim().replace(/[^+\d]/g, '.');
                // let raw = col.split('.');
                // val['col'][1] = `2020-0${raw[1]}-${raw[0].length < 2 ? '0'+raw[0] : raw[0]}`.replace(/[^-0-9]/gim,'');

                // val['col'][1] = `2020-0${raw[1]}-${raw[0].length < 2 ? '0'+raw[0] : raw[0]}`.replace(/[^-0-9]/gim,'');
                return val;
            },
            watchTime(val){
                if (/[0-9]/.test(val)){
                    let str = val.replace(/[^0-9]/gim,':');
                    let ar = str.split(':');
                    let cor = ar.map((a,k) => {
                        return a.length < 2 ? '0'+ a : a
                    })
                    let c0 = cor[0] !== undefined ? cor[0] : '00';
                    let c1 = cor[1]  !== undefined  ? cor[1] : '00';
                    return  c0+':'+c1;
                } else  {
                    return '';
                }
            },
            watchSite(val){
                if(/сэс/.test(val.toLowerCase())) { return 3;}
                else if(/мгцд/.test(val.toLowerCase())) { return 4;}
                else if(/ddd/.test(val.toLowerCase())) { return 5;}
                else if(/ддд/.test(val.toLowerCase())) { return 5;}
                else if(/борщ/.test(val.toLowerCase())) { return 8;}
                else if(/крот/.test(val.toLowerCase())) { return 8;}
                else if(/офф/.test(val.toLowerCase())) { return 8;}
                else if(/off/.test(val.toLowerCase())) { return 8;}
                else if(/sest/.test(val.toLowerCase())) { return 9;}
                else if(/сэст/.test(val.toLowerCase())) { return 9;}
                else {return 1;}
            },
            watchManager(val){
                if(val){
                    if(/тан/.test(val.toLowerCase())) { return 21;}
                    else if(/таня/.test(val.toLowerCase())) { return 21;}
                    else if(/кост/.test(val.toLowerCase())) { return 22;}
                    else if(/свет/.test(val.toLowerCase())) { return 40;}
                    else if(/андр/.test(val.toLowerCase())) { return 20;}

                    else {return 21;}

                } else {
                    return '';
                }
            },
            watchCallStatus(val){
                if(val){
                    // ['заказ', 'перезвонить','невыгодно', 'нецелевой']
                    if(/заказ/.test(val.toLowerCase())) { return 7;}
                    else if(/отказ/.test(val.toLowerCase())) { return 5;}
                    else if(/спам/.test(val.toLowerCase())) { return 1;}
                    else if(!val) { return '';}
                    else {return 6;}
                }  else {return 6;}

            },
            watchCause(val){
                if(val){
                    if(/подробно сколько и чего/.test(val.toLowerCase())) { return '';}
                    else if(!val) { return '';}
                    else {return val}
                } else {return ''}

            },
            watchAddress(val,val2){
                if(val){
                    if(/подробно сколько и чего/.test(val.toLowerCase())) { return '';}
                    else if(!val) { return '';}
                    else {return val}
                } else {return ''}

            },
            watchNamePhone(val){
                // if(val){
                let messArr = val.replace(/ [?*+^$[\]\\(){}|-]/g,' ').replace(/ \\r?\\n/,' ');
                     messArr = messArr.split(/ |A-ZА-Я/);
                    // let messArr2 = messArr.map(o => {
                    //     return o.split(/(?=[A-ZА-Я])/);
                    // });
                    // let messArr3 = messArr2.map(o => {
                    //     return o.split("\\r?\\n");
                    // });


                    // .split(/(?=[A-Z])/).join(" ");
                // } else {
                //     return false;
                // }

                let ob = {email: [], name: [], phone: []};
                for(let guess of messArr){
                    let w = this.htmlSpecChars(guess);
                    if(/@/.test(guess)){
                        ob.email.push(w) //.replace(/[^_0-9A-Za-z@]/gim, '')
                    }
                    else if(/[0-9]/.test(guess)){
                        ob.phone.push(w.replace(/[^0-9]/gim, ''))
                    }
                    else {
                        ob.name.push(w.replace(/[^_0-9A-Za-zА-Яа-я]/gim, ''))
                    }

                }



                return  ob;




            },
            preCompile(vals){
                let arr = vals.slice(this.start,this.limit);
                let dataKey = 0;
                let days = 0;
                let date = '';
                let res = arr.map((val,k) => {

                    if(!val['col'][3] && !val['col'][4] && !val['col'][5]) {
                        // return false;

                    }


                    if(val['col'][1].indexOf('п/п') !== -1) {
                        dataKey = k+1;
                        // return false;
                    }

                    if(k === dataKey && k !== 0){
                        this.watchDate(val);
                        date = val['col'][1];

                        // return false;
                    }

                    val['date'] = date;
                    val['time'] = this.watchTime(val['col'][2]);
                    val['site'] = this.watchSite(val['col'][1]);
                    val['manager'] = this.watchManager(val['col'][4]);
                    val['call_status'] = this.watchCallStatus(val['col'][15]);
                    val['cause'] = this.watchCause(val['col'][5]);
                    val['address'] = this.watchAddress(val['col'][6]);
                    val['cash'] = val['col'][7] ? val['col'][7].trim().replace(/[^+\d]/g, '') : '';
                    val['cashless'] = val['col'][8] ? val['col'][8].trim().replace(/[^+\d]/g, '') : '';
                    if (val['col'][3]){
                        let d = this.watchNamePhone(val['col'][3]);
                        // if(d){
                            val['phone'] = d.phone.join(' ');
                            val['name'] = d.name.join(' ') + ' '+ d.email.join(' ');
                        val['contract'] = val['col'][3];
                        // }

                    }


                    val['contract'] = val['col'][13];
                    val['implement_date'] = date +' '+val['col'][14];
/*                    val['customer'] = '';
                    val['refusal'] = '';*/


                    return val;

                })

                this.$set(this.$data,'csv',res)


            },

            precompile(vals){
                let arr = vals.slice(this.start,this.limit);
                let dataKey = 0;
                let date = '';
                let res = arr.map((val,k) => {
                    if(!val[0] && !val[1] && !val[2] && !val[3] && !val[4] && !val[5]){
                        return false;
                    }

                    if(val[1].indexOf('п/п') !== -1) {
                        dataKey = k+1;
                        return false;
                    }

                    if(k === dataKey && k !== 0){
                        this.watchDate(val);
                        date = val[1];

                        return false;
                    }

                    val[1] = date;


                    return val;






                });
                this.$set(this.$data,'csv',res)

            },



            //EOF PARSE CSV





            async importCsv(){
                try {
                    let response = await Axios.post('/statistic/crm/async',{
                        func: 'getStat',
                        values: {
                            limit:this.limit
                        }
                    });
                    if(response.data){
                        this.$set(this.$data,'tempArr',response.data);
                        this.preCompile(response.data);


                    }
                } catch (error) {
                    console.log(error);
                }
            },
        },
        mounted() {
            this.importCsv();
        }
    }
</script>


<style lang="scss">
    .settings {
        position: absolute;
        top: -56px;
        left: 44px;
        z-index: 1500;
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


</style>
