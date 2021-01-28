<template>
    <table class="statistic-act-table">
        <tbody>
        <tr>
            <td v-for="i in 15"></td>
        </tr>

        <tr>
            <td colspan="15">
<!-- comment / cause -->
                <Form mode="block">
                    <FormItem label="Комментарий к звонку">
                        <textarea  v-if="rowCrmStatistic.act" rows="3" v-autosize v-model.lazy="rowCrmStatistic.act.comment"
                                   @focusout="updateActField('comment',rowCrmStatistic.act.comment)"></textarea>
                        <textarea v-else rows="3" v-autosize v-model.lazy="rowCrmStatistic.cause"
                                  @focusout="updateCrmStatisticField('cause',rowCrmStatistic.cause)"></textarea>
                    </FormItem>
                </Form>
            </td>
        </tr>
        <tr class="statistic-act-table_head">
            <td colspan="3"><span>Телефон</span></td>
            <td colspan="2"><span>Имя /<br/> Тип обьекта</span></td>
            <td colspan="6" style="position: relative"><span>Адрес</span>

                <div class="space-between" style="position: absolute;top: 0;left: 60px;width: 575px;">
                    <Poptip v-if="row.customer" content="Это заменит данные контакта данными клиента. Продолжить?"
                            @confirm="emitUpdateActContacts()">
                        <Button color="primary" icon="h-icon-user"/>
                    </Poptip>
                    <dadata-input :entity="'address'" @dadata="setAddress" placeholder="... Улица / дом / корпус / квартира" style="width: 100%;"/>
                </div>

            </td>
            <td colspan="1"><span>Долгота/<br/>Широта</span></td>
            <td colspan="1"><span>Регион/<br/>Расстояние</span></td>
            <td colspan="1"><span>Карта /<br/> Мастер+</span></td>
            <td colspan="1"><span>Субчику /<br/> Отказ</span></td>
<!--            <td colspan="1"><span>Нал/<br/>Безнал</span></td>-->
<!--            <td colspan="1"><span>Нал_сумма/<br/>Б_н_сумма</span></td>-->
        </tr>
        <tr>
            <td colspan="3">
                <template v-if="rowCrmStatistic.act">
                    <div class="form_first_item">
                        <div class="h-input-group">
                            <input type="text" v-model="rowCrmStatistic.act.phone" v-mask="'+7 (###) ### ## ##'"
                                   class="searchPhone" @keyup="phoneSearch(rowCrmStatistic.act.phone)"
                                   @focusout="updateActField('phone',rowCrmStatistic.act.phone)"/>
                            <span class="h-input-addon">доб:</span>
                            <input type="text" v-model.lazy="rowCrmStatistic.act.phone_sub" style="max-width: 70px"
                                   @focusout="updateActField('phone_sub',rowCrmStatistic.act.phone_sub)">
                        </div>
                    </div>
                    <div class="form_second_item">
                        <div class="h-input-group">
                            <the-mask v-model.lazy="rowCrmStatistic.act.phone_ext" mask="+7 (###) ### ## ##"
                                      @focusout="updateActField('phone_ext',rowCrmStatistic.act.phone_ext)"
                            />
                            <span class="h-input-addon">доб:</span>
                            <input type="text" v-model.lazy="rowCrmStatistic.act.phone_ext_sub" style="max-width: 70px"
                                   @focusout="updateActField('phone_ext_sub',rowCrmStatistic.act.phone_ext_sub)"
                            >
                        </div>
                    </div>
                </template>

                <template v-else>

                    <div class="form_first_item">
                        <div class="h-input-group">
                            <input type="text" v-model="rowCrmStatistic.phone" v-mask="'+7 (###) ### ## ##'"
                                   class="searchPhone" @keyup="phoneSearch(rowCrmStatistic.phone)"
                                   @focusout="updateCrmStatisticField('phone',rowCrmStatistic.phone)"
                            />
                            <span class="h-input-addon">доб:</span>
                            <input type="text" v-model.lazy="rowCrmStatistic.phone_sub" style="max-width: 70px"
                                   @focusout="updateCrmStatisticField('phone_sub',rowCrmStatistic.phone_sub)"
                            >
                        </div>
                    </div>
                    <div class="form_second_item">
                        <div class="h-input-group">
                            <input type="text" v-model.lazy="rowCrmStatistic.phone_ext" v-mask="'+7 (###) ### ## ##'"
                                   @focusout="updateCrmStatisticField('phone_ext',rowCrmStatistic.phone_ext)"
                            />
                            <span class="h-input-addon">доб:</span>
                            <input type="text" v-model.lazy="rowCrmStatistic.phone_ext_sub" style="max-width: 70px"
                                   @focusout="updateCrmStatisticField('phone_ext_sub',rowCrmStatistic.phone_ext_sub)"
                            >
                        </div>
                    </div>

                </template>
            </td>
            <td colspan="2">
                <div class="form_first_item">
                    <!--name-->
                    <input type="text" v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.contact_name"
                           @focusout="updateActField('contact_name',rowCrmStatistic.act.contact_name)">

                    <input type="text" v-else v-model.lazy="rowCrmStatistic.name"
                           @focusout="updateCrmStatisticField('name',rowCrmStatistic.name)">
                </div>
                <div class="form_second_item">
                    <!--property_type-->
                    <Select v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.property_type" :datas="propertyType"
                            keyName="id" titleName="name"
                            @change="updateActField('property_type',rowCrmStatistic.act.property_type)"/>

                    <Select v-else v-model.lazy="rowCrmStatistic.property_type" :datas="propertyType" keyName="id"
                            titleName="name"
                            @change="updateCrmStatisticField('property_type',rowCrmStatistic.property_type)"/>
                </div>
            </td>
            <td colspan="6">
                <div class="form_first_item">
                    <!--address-->
                    <textarea rows="3" v-if="rowCrmStatistic.act" v-autosize v-model.lazy="rowCrmStatistic.act.address"
                              @focusout="updateActField('address',rowCrmStatistic.act.address)"></textarea>

                    <textarea rows="3" v-else v-autosize v-model.lazy="rowCrmStatistic.address"
                              @focusout="updateCrmStatisticField('address',rowCrmStatistic.address)"></textarea>

                </div>
                <div class="form_second_item"></div>
            </td>
            <td>
                <div class="form_first_item">
                    <!--lat-->
                    <input v-if="rowCrmStatistic.act" type="text" v-model.lazy="rowCrmStatistic.act.lat"
                           @focusout="updateActField('lat',rowCrmStatistic.act.lat)">

                    <input v-else type="text" v-model.lazy="rowCrmStatistic.lat"
                           @focusout="updateCrmStatisticField('lat',rowCrmStatistic.lat)">
                </div>
                <div class="form_second_item">
                    <!--lon-->
                    <input v-if="rowCrmStatistic.act" type="text" v-model.lazy="rowCrmStatistic.act.lon"
                           @focusout="updateActField('lon',rowCrmStatistic.act.lon)">

                    <input v-else type="text" v-model.lazy="rowCrmStatistic.lon"
                           @focusout="updateCrmStatisticField('lon',rowCrmStatistic.lon)">
                </div>
            </td>
            <td colspan="1">
                <div class="form_first_item">
                    <!--region-->
                    <input type="text" v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.region"
                           @focusout="updateActField('region',rowCrmStatistic.act.region)">

                    <input type="text" v-else v-model.lazy="rowCrmStatistic.region"
                           @focusout="updateCrmStatisticField('region',rowCrmStatistic.region)">
                </div>
                <div class="form_second_item">
                    <!--destination-->
                    <input type="text" v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.destination"
                           @focusout="updateActField('destination',rowCrmStatistic.act.destination)">

                    <input type="text" v-else v-model.lazy="rowCrmStatistic.destination"
                           @focusout="updateCrmStatisticField('destination',rowCrmStatistic.destination)">
                </div>
            </td>

            <td colspan="1">
                <div class="form_first_item">
                    <!--card_payment~-->
                    <h-switch v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.card_payment" :trueValue="1" :falseValue="0" :small="true"
                              @change="updateActField('card_payment',rowCrmStatistic.act.card_payment)"/>
                    <h-switch v-else v-model.lazy="rowCrmStatistic.card_payment" trueValue="1" falseValue="0" :small="true"
                              @change="updateCrmStatisticField('card_payment',rowCrmStatistic.card_payment)"/>
                </div>
                <div class="form_second_item">

                    <!--overpay-->
                    <input v-if="rowCrmStatistic.act" v-model.number.lazy="rowCrmStatistic.act.overpay" type="text"
                           @focusout="updateActField('overpay',rowCrmStatistic.act.overpay)"  >

                    <input v-else v-model.numbe.lazyr="rowCrmStatistic.overpay" type="text"
                           @focusout="updateCrmStatisticField('overpay',rowCrmStatistic.overpay)">
                </div>
            </td>

            <td colspan="1">
                <div class="form_first_item">
                    <!--contractor_fee-->
                    <input v-if="rowCrmStatistic.act" v-model.number.lazy="rowCrmStatistic.act.contractor_fee" type="text"
                           @focusout="updateActField('contractor_fee',rowCrmStatistic.act.contractor_fee)"  >

                    <input v-else v-model.number.lazy="rowCrmStatistic.contractor_fee" type="text"
                           @focusout="updateCrmStatisticField('contractor_fee',rowCrmStatistic.contractor_fee)">
                </div>
                <div class="form_second_item">
                    <!--pay_back-->
                    <input v-if="rowCrmStatistic.act" v-model.number.lazy="rowCrmStatistic.act.pay_back" type="text"
                           @focusout="updateActField('pay_back',rowCrmStatistic.act.pay_back)"  >

                    <input v-else v-model.number.lazy="rowCrmStatistic.pay_back" type="text"
                           @focusout="updateCrmStatisticField('pay_back',rowCrmStatistic.pay_back)">
                </div>
            </td>

<!--            <td colspan="1">
                <div class="form_first_item">
                    &lt;!&ndash;cash_tag&ndash;&gt;
                    <h-switch v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.cash_tag" :trueValue="1" :falseValue="0" :small="true"
                              @change="updateActField('cash_tag',rowCrmStatistic.act.cash_tag)">нал</h-switch>
                    <h-switch v-else v-model.lazy="rowCrmStatistic.cash_tag" trueValue="1" falseValue="0" :small="true"
                              @change="updateCrmStatisticField('cash_tag',rowCrmStatistic.cash_tag)">нал</h-switch>
                </div>
                <div class="form_second_item">
                    &lt;!&ndash;cash_tag&ndash;&gt;
                    <h-switch v-if="rowCrmStatistic.act" v-model.lazy="rowCrmStatistic.act.cashless_tag" :trueValue="1" :falseValue="0" :small="true"
                              @change="updateActField('cashless_tag',rowCrmStatistic.act.cashless_tag)">б\н</h-switch>
                    <h-switch v-else v-model.lazy="rowCrmStatistic.cashless_tag" trueValue="1" falseValue="0" :small="true"
                              @change="updateCrmStatisticField('cashless_tag',rowCrmStatistic.cashless_tag)">б\н</h-switch>
                </div>
            </td>-->

<!--            <td colspan="1">
                <div class="form_first_item">
                    &lt;!&ndash;cash_tag&ndash;&gt;
                    <input type="text" v-if="rowCrmStatistic.act" v-model.number="rowCrmStatistic.act.cash"
                           @change="updateActField('cash',rowCrmStatistic.act.cash)"/>
                    <input type="text" v-else v-model.number="rowCrmStatistic.cash"
                           @change="updateCrmStatisticField('cash',rowCrmStatistic.cash)"/>
                </div>
                <div class="form_second_item">
                    &lt;!&ndash;cash&ndash;&gt;
                    <input type="text" v-if="rowCrmStatistic.act" v-model.number="rowCrmStatistic.act.cashless"
                           @change="updateActField('cashless',rowCrmStatistic.act.cashless)"/>
                    <input type="text" v-else v-model.number="rowCrmStatistic.cashless"
                           @change="updateCrmStatisticField('cashless',rowCrmStatistic.cashless)"/>
                </div>
            </td>-->
        </tr>
        <tr><td v-for="i in 15" class="slice"></td></tr>

        </tbody>
    </table>
</template>

<script lang="ts">
    import { Component, Prop, Vue} from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as moment from 'moment';
    import {IAct, IPaid, IPropertyType} from "../../../types/act";
    import {ICrmStatistic} from "../../../types/crm.statistic";
    import {IFieldValue} from "../../../types/Global";
    const Act = namespace('Act')
    const CrmStatistic = namespace('CrmStatistic')
    import { bus } from "../../../bus";
    import {ICustomer} from "../../../types/customer";
    import DadataInput from "../../autocomplete/DadataInput.vue";

    @Component({
        components: {
            DadataInput
        }
    })
    export default class CrmStatisticAct extends Vue {

        @Act.State('row') public rowAct!: IAct
        @Act.State('propertyType') public propertyType!: IPropertyType
        @Act.State('paid') public paid!: IPaid
        @Act.Action('eUpdateRow') public eUpdateRowAct!: (payload: IAct) => void
        @CrmStatistic.State('row') public rowCrmStatistic!: ICrmStatistic
        /** @this CrmStatistic.eUpdateRow */
        @CrmStatistic.Action('updateRow') public statistic_updateRow!: (payload: ICrmStatistic) => void
        @CrmStatistic.Mutation setTableRow!: (payload: object) => void
        get row() {return this.$store.state.CrmStatistic.row}
        set row(value) {this.setTableRow(value)}

        public phoneSearch(str: string) {
            bus.$emit('phoneSearch',str)
        }

//act
        updateActField(field: string, value:any) {
            this.eUpdateRowAct({
                id: this.rowCrmStatistic?.act?.id,
                [field as string]: value,
            })
        }
        updateActCustomer() {}
        createAct() {}

        emitUpdateActContacts() {
            this.statistic_updateRow({
                id: this.row.id,
                phone: this.row.customer.phone,
                phone_sub: this.row.customer.phone_sub,
                phone_ext: this.row.customer.phone_ext,
                phone_ext_sub: this.row.customer.phone_ext_sub,
                name: this.row.customer.name,
                address: this.row.customer.address,
                lat: this.row.customer.lat,
                lon: this.row.customer.lon,
                region: 'Москва',// this.row.customer.region,
                destination: this.row.customer.destination,
            })
        }
        setAddress(payload: any){
            this.statistic_updateRow({
                id: this.row.id,
                address : payload.title,
                lat : payload.data.geo_lat,
                lon : payload.data.geo_lon,
                destination : payload.destination
            })
        }

//CrmStatistic
        updateCrmStatisticField(field: string, value:any) {
            this.statistic_updateRow({
                id: this.rowCrmStatistic.id,
                [field as string]: value,
            })
        }
        created(){
            console.log('in act');

            bus.$on('act_price_fact',(val:number) => {
                console.log('val', val);
                this.statistic_updateRow({
                    id: this.rowCrmStatistic.id,
                    cash_tag: val ? 1 :  0,
                })
            })
        }


    }
</script>
