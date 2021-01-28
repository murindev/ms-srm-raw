<template>
    <div class="mail">
        <div class="mail-header">
            <div class="mail-header-col">
                <span>Отправитель:</span>
                <Select v-model="site.model" :datas="sites" keyName="id" titleName="name"></Select>
            </div>
            <div class="mail-header-col">
                <span>Тип  письма:</span>
                <Select v-model="purpose.model" :datas="purpose.data" keyName="id" titleName="name" :autosize="true"/>
            </div>
            <div class="mail-header-col">
                <span>Получатель:</span>
                <input type="text" v-model="email.model">
            </div>
            <div class="mail-header-col">
                <Button color="primary" icon="h-icon-left" @click="confirmTest()">Отправить</Button>
            </div>

<!--            <div class="mail-header-col">-->
<!--                <Button color="primary" icon="h-icon-left" @click="conf()">confconfconf</Button>-->
<!--            </div>-->
<!--            <div class="mail-header-col">-->
<!--                <Button color="primary" icon="h-icon-left" @click="confirmT()">tttttt</Button>-->
<!--            </div>-->

        </div>
        <div class="mail-body">
            <table class="table">
                <thead>
                <tr>
                    <th v-for="(th,key) in table.thead">
                        <template v-if="key === 'chb'">
                            <Checkbox v-model="table.thead.chb" @change="checkControl()"/>
                        </template>
                        <template v-else>{{th}}</template>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(tr,trKey) in table.tbody">
                    <td v-for="(td,tdKey) in table.thead">
                        <template v-if="tdKey === 'chb'">
                            <Checkbox v-model="tr.chb"/>
                        </template>
                        <template v-else-if="tdKey === 'title'">{{tr.title}}</template>
                        <template v-else><input :disabled="!tr.chb" type="number" v-model.number="tr[tdKey]" @keyup="updatePrise(tr,trKey)"></template>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Итоговая сумма</th>
                    <th :colspan="Object.keys(table.thead).length - 2">
                        {{calc.sum}}
                    </th>
                </tr>
                </tfoot>
            </table>
            <Form :labelWidth="130">
                <FormItem label="Скидка">
                    <input type="number" v-model.number="discount.model">
                </FormItem>

                <FormItem label="Заголовок письма">
                    <input type="text" v-model="subject.model">
                </FormItem>

                <FormItem label="Обращение">
                    <input type="text" v-model="appeal.model">
                </FormItem>

                <FormItem label="Вводный текст">
                    <textarea v-model="introText.model"/>
                </FormItem>
                <FormItem label="Основной текст">
<!--                    <textarea v-model="bodyText.model"/>-->
                    <div contenteditable="true" ref="bodyText">У нас на исполнении более 150 государственных контрактов и более 400 юридических лиц на постоянном обслуживании. Также при единовременной оплате суммы всего договора, вам предоставляется <b>скидка</b> в размере <b>{{discount.model.toFixed(2)}}%</b> , что составляет <b>{{calc.disCount.toFixed(2)}}</b> рублей. Итого вы можете рассчитывать на годовое обслуживание всего за <b>{{calc.discountPrice.toFixed(2)}}</b> рублей!</div>
                </FormItem>
                <FormItem label="Подпись">
                    <input type="text"  v-model="manager.text">
                </FormItem>
                <FormItem label="Менеджер">
                    <Select v-model="manager.model" :datas="managers" keyName="id" titleName="name" @change="changeManager()"></Select>
                </FormItem>
            </Form>
        </div>
        <div class="mail-footer">

        </div>
        <Modal class="actModal" v-model="checkModal"  :hasCloseIcon="false" :closeOnMask="false" >
            <template v-if="site.model === 9">
                <commercial-ses-team :content="$data"/>
<!--                -->
            </template>
        </Modal>


    </div>

</template>

<script>
    import CommercialSesTeam from "./Letters/CommercialSesTeam";
    import {bus} from "../bus";
    export default {
        name: "CommercialLetters",
        props: ['user', 'sites', 'managers'],
        data() {
            return {
                site: {
                    model: null
                },
                purpose: {
                    model: null,
                    data: [
                        {id: 1, name: 'Простое письмо'},
                        {id: 2, name: 'Коммерческое предложение'},
                        {id: 3, name: 'Коммерческое краткое'},
                    ],
                },
                email: {
                    model: 'uretral@yandex.ru'
                },
                table: {
                    thead: {
                        chb: true,
                        title: 'Наименование',
                        multiplicity: 'Кратность',
                        square: 'Площадь',
                        square_price: 'Цена за м2',
                        service_price: 'Цена услуги (руб.)',
                        cost: 'Стоимость (руб.)',
                    },
                    tbody: {
                        deratization: {
                            chb: true,
                            title: 'Дератизация',
                            multiplicity: null,
                            square: null,
                            square_price: null,
                            service_price: null,
                            cost: null,
                        },
                        disinsection: {
                            chb: true,
                            title: 'Дезинсекция',
                            multiplicity: null,
                            square: null,
                            square_price: null,
                            service_price: null,
                            cost: null,
                        },
                        disinfection: {
                            chb: true,
                            title: 'Дезинфекция',
                            multiplicity: null,
                            square: null,
                            square_price: null,
                            service_price: null,
                            cost: null,
                        },
                        herbicide: {
                            chb: true,
                            title: 'Гербицидная обработка',
                            multiplicity: null,
                            square: null,
                            square_price: null,
                            service_price: null,
                            cost: null,
                        },
                    }
                },
                discount: {
                    model: 0
                },
                appeal: {
                    model: 'Уважаем'
                },
                introText: {
                    model: 'Настоящим представляем Вам коммерческое предложение по заключению договора на обслуживание и проведение истребительных мероприятий на следующих условиях: \n' +
                        'Объект – административное здание:'
                },
                bodyText: {
                    model: `У нас на исполнении более 150 государственных контрактов и более 400 юридических лиц на постоянном обслуживании. Также при единовременной оплате суммы всего договора, вам предоставляется скидка в размере 0.00% , что составляет 0.00 рублей. Итого вы можете рассчитывать на годовое обслуживание всего за 0.00 рублей!`
                },
                manager: {
                    model: this.user.id,
                    text: 'Руководитель отдела продаж,',
                    name: this.managers[this.user.id].name
                },
                isReady: false,
                sendData: {},
                checkModal: false,
                subject: {
                    model: 'Коммерческое предложение'
                }
            }
        },
        computed:{
            calc: function () {
                let res = 0
                for(let s in this.table.tbody)if(this.table.tbody[s].cost){
                    res += this.table.tbody[s].cost;
                }
                let perCent = res/100;
                return {
                    sum: res,
                    percent: perCent,
                    disCount: this.discount.model * perCent,
                    discountPrice: res - ( this.discount.model * perCent),
                }
            },
            historySave:(value) => {

            }
        },
        components: {
            CommercialSesTeam
        },
        methods: {
            changeManager(){
                this.manager.name = this.managers[this.manager.model].name
            },
            checkControl() {
                for (let i in this.table.tbody) {
                    this.$set(this.table.tbody[i], 'chb', !this.table.thead.chb)
                }
            },
            managerName(){
                let name = this.manager.model ? this.managers[manager.model].name : '';
                console.log(name);
                return name
            },
            updatePrise(tr,trKey){
                console.log(tr,trKey);
                if(this.table.tbody[trKey].square && this.table.tbody[trKey].square_price){
                    this.table.tbody[trKey].service_price = this.table.tbody[trKey].square * this.table.tbody[trKey].square_price
                }
                if(this.table.tbody[trKey].square && this.table.tbody[trKey].square_price && this.table.tbody[trKey].multiplicity){
                    this.table.tbody[trKey].cost = this.table.tbody[trKey].square * this.table.tbody[trKey].square_price * this.table.tbody[trKey].multiplicity
                }
            },

            async conf(){
                try {
                    let response = await axios.post('/sendletters/sesteam',{
                        to: 'paramparamparamparam', // email
                        subject: 'test test test test' // тема письма
                    });
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },
            confirmT(){
                console.log(this.$data);
            },
            async confirmTest(){
                this.site.model && this.email.model ? this.checkModal = true : this.checkModal = false;

                if (this.isReady) {
                    try {
                        let response = await axios.post('https://sesteam.ru/mail/letters',this.sendData);
                        console.log(response.data);
                    } catch (error) {
                        console.log(error);
                    }
                } else {
                    if (!this.site.model) {
                        this.$Notice['error']('Выберите сайт отправитель')
                        return false
                    }
                    if (!this.email.model) {
                        this.$Notice['error']('Выберите email получателя')
                        return false
                    }
                }
            },
            async send(ev){
                try {
                    let response = await axios.post('/sendletters/sesteam',{
                        site: this.site.model,
                        to: this.email.model,
                        subject: this.subject.model,
                        html: ev,
                        data: this.$data,
                });
                    console.log(response.data);
                } catch (error) {
                    console.log(error);
                }
            },

        },
        mounted() {
            bus.$on('edit_letter', (ev) => {
                this.checkModal = false
            })
            bus.$on('send_letter', (ev) => {
                this.send(ev);
            })
        },
        created() {
            document.addEventListener('keydown', this.onKeyDown);
        },
        // sesteam_edit
    }
</script>
