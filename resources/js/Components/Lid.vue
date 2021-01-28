<template>
    <div class="Lid">
        <div v-if="mapSwitch">
            <d-g-map></d-g-map>
        </div>

        <div v-else>
            <Row>
                <Cell width="8" type="flex" v-if="lid">
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10">
                        <Cell width="6">
                            <Poptip
                                    content="Данный лид и все его акты будут УДАЛЕНЫ, Вы будете перенаправлены на страницу списка лидов. Вы хотите продолжить? "
                                    placement="right" @confirm="deleteLid(lid)">
                                <Button color="red">Удалить лид</Button>
                            </Poptip>
                        </Cell>

                        <Cell width="6">
                            <Poptip
                                    content="Данный лид и все его акты будут скопированы и  автоматически откроются на этой странице. Вы хотите продолжить? "
                                    placement="right"
                                    @confirm="cloneLid(lid.id)">

                                <Button color="primary">Копировать лид</Button>
                            </Poptip>
                        </Cell>

                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10">
                        <Cell width="6">Договор:</Cell>
                        <Cell width="6"><input type="text" class="w100" @focusout="saveLid()" v-model="lid.contract"/>
                        </Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10">
                        <Cell width="6">Дата обращения:</Cell>
                        <Cell width="6"><input type="text" class="w100" disabled v-model="lid.date_start"/></Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="h.companies">
                        <Cell width="6">Сайт:</Cell>
                        <Cell width="6">
                            <Select v-if="h.companies" v-model="lid.site" keyName="id" titleName="name"
                                    :datas="h.companies" @change="saveLid"/></Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="h.users">


                        <Cell width="6">Мэнеджер стартер:</Cell>
                        <Cell width="6">
                            <input v-if="lid.manager_starter" type="text" class="w100" disabled
                                   :value="h.users[lid.manager_starter].name"/>
                        </Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="h.users">
                        <Cell width="6">Мэнеджер:</Cell>
                        <Cell width="6">
                            <Select v-model.number="lid.manager" :datas="managers()" keyName="id" titleName="name"
                                    :null-option="false" @change="saveLid"/>
                        </Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="h.servicing">
                        <Cell width="6">Тип обслуживания:</Cell>
                        <Cell width="6"><Select v-if="h.servicing" v-model="lid.servicing" titleName="name" keyName="id"
                                                :datas="h.servicing" @change="saveLid"/></Cell>
                    </Row>
                    <Row class="h-row-flex h-row-flex-middle" :space-y="10">
                        <Cell width="6">
                            <Button color="blue" @click="eventCreator('customer-modal',true) ">Клиент:</Button>
                        </Cell>
                        <Cell width="6">
                            <Select v-if="h.customer" disabled v-model="customer.status" titleName="name"
                                    keyName="value" :datas="h.customer" @change="saveLid"/>
                        </Cell>
                        <DadataOrganization/>
                        <!--:customerData="customer"-->
                    </Row>
                    <template v-if="lid.customer">

                        <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10"
                             v-if="customer.status">
                            <Cell width="6">Организация:</Cell>
                            <Cell width="18">
                                <input disabled type="text" class="w100" v-model="customer.organization"/>
                            </Cell>
                        </Row>
                        <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="customer.name">
                            <Cell width="6">Контактное лицо:</Cell>
                            <Cell width="6"><input type="text" class="w100" disabled v-model="customer.name"/></Cell>
                        </Row>
                        <Row class="h-row-flex h-row-flex-middle" :space-y="10">
                            <Cell width="6">Телефоны:</Cell>
                            <Cell width="6">
                                <the-mask mask="+7 (###) ### ## ##" disabled="" v-model="customer.phone"
                                          :masked="true"/>
                            </Cell>
                            <Cell width="3" v-if="customer.phone_sub"><input type="text" class="w100" disabled
                                                                             v-model="customer.phone_sub"></Cell>
                            <Cell width="6" v-if="customer.phone_ext">
                                <the-mask mask="+7 (###) ### ## ##" disabled="" :masked="true"
                                          v-model="customer.phone_ext"/>
                            </Cell>
                            <Cell width="3" v-if="customer.phone_ext_sub"><input type="text" class="w100" disabled
                                                                                 v-model="customer.phone_ext_sub">
                            </Cell>
                        </Row>
                        <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="customer">
                            <Cell width="6">Email:</Cell>
                            <Cell width="6"><input type="text" class="w100" disabled v-model="customer.email"/></Cell>
                        </Row>
                        <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="customer">
                            <Cell width="6"><a @click="showGeo = !showGeo">Адрес:</a></Cell>
                            <Cell width="15"><input type="text" class="w100" disabled v-model="customer.address"/>
                            </Cell>
                            <Cell width="3"><input type="text" class="w100" disabled v-model="customer.destination"/>
                            </Cell>
                        </Row>
                        <Row class="h-row-flex h-row-flex-middle" :space-y="10" v-if="customer && showGeo">
                            <Cell width="6"/>
                            <Cell width="4"><input type="text" class="w100" disabled v-model="customer.region"/></Cell>
                            <Cell width="4"><input type="text" class="w100" disabled v-model="customer.lat"/></Cell>
                            <Cell width="4"><input type="text" class="w100" disabled v-model="customer.lon"/></Cell>
                        </Row>
                    </template>

                    <Row class="h-row-flex h-row-flex-middle" :space-y="10"/>
                </Cell>
                <Cell width="1">
                    <Row :space="10">&nbsp;</Row>
                </Cell>
                <Cell width="15">
                    <Row :space="10" type="flex" justify="space-between" align="top">&nbsp;
                        <Cell width="12">
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24"><h5>Настройки оплаты</h5></Cell>
                            </Row>
                            <!--Тип цены payment_system-->
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Система оплаты:</Cell>
                                <Cell width="14">
                                    <Select v-if="lid"
                                            v-model="lid.payment_system"
                                            :datas="[{id:1,name:'Фиксированная'},{id:2,name:'Накопительная'}]"
                                            keyName="id" titleName="name" @change="saveLid()"/>
                                </Cell>
                            </Row>
                            <!--Форма оплаты:-->
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Форма оплаты:</Cell>
                                <Cell width="14">
                                    <Select v-if="lid"
                                            v-model="lid.customer_payment"
                                            :datas="[{id:1,name:'Наличный расчет'},{id:2,name:'Безналичный расчет'},{id:3,name:'Смешанная'}]"
                                            keyName="id" titleName="name" @change="saveLid()"/>
                                </Cell>
                            </Row>


                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Порядок (пред\пост):</Cell>
                                <Cell width="14">
                                    <div style="display: flex;justify-content: space-between">
                                        <Select v-if="lid" v-model="lid.payment_rule"
                                                :datas="h.payment_rules" keyName="id" titleName="name"
                                                @change="paymentRuleSave()"/>
                                        <input type="text" class="w100" v-model.number="lid.rules_prepayment"
                                               @focusout="paymentRulePercents()"
                                               :disabled="[2].indexOf(lid.payment_rule) !== -1">
                                        <input type="text" class="w100" v-model.number="lid.rules_postpayment"
                                               @focusout="paymentRulePercents()"
                                               :disabled="[1,5,6].indexOf(lid.payment_rule) !== -1">
                                    </div>


                                </Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Условия постоплаты:</Cell>
                                <Cell width="14">
                                    <div style="display: flex;justify-content: space-between">
                                        <input type="text" class="w100" v-model="lid.payment_condition"
                                               @focusout="saveLid()">
                                        <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                        <span>дней</span>
                                    </div>

                                </Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Чистая цена:</Cell>
                                <Cell width="14">
                                    <input v-if="lid.balance >= 0" type="text" class="w100" v-model="lid.balance"
                                           disabled style="font-weight: bold; color: black">
                                    <input v-else type="text" class="w100" v-model="lid.balance" disabled
                                           style="font-weight: bold; color: red;">
                                </Cell>
                            </Row>

                        </Cell>
                        <!--LEFT-->
                        <Cell width="11">
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24"><h5>Документы по договору</h5></Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Дата начала/завершения:</Cell>
                                <Cell width="7">
                                    <DatePicker v-model="lid.date_from" @change="saveLid()" type="date"/>
                                </Cell>
                                <Cell width="7">
                                    <DatePicker v-model="lid.date_to" @change="saveLid()" type="date"/>
                                </Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">передан/подписан:</Cell>
                                <Cell width="14"
                                      style="display: flex;justify-content: space-between; align-items: center;">
                                    <h-switch v-model="lid.contract_transferred" :small="true" @change="saveLid()"/>
                                    <h-switch v-model="lid.contract_signed" :small="true" @change="saveLid()"/>

                                    <cite v-if="lid.contract_file !== null">
                                        <a target="_blank" :href="'/storage/docs/'+lid.contract_file">Загружен</a>
                                        <a @click="uploadFile('contract_file',true)" class="x white">X</a>
                                    </cite>
                                    <template v-else>
                                        <label for="contract_file" class="file sm"
                                               style="display: inline-flex!important;">ФАЙЛ</label>
                                        <input type="file" id="contract_file" @change="uploadFile('contract_file')">
                                    </template>
                                </Cell>
                            </Row>
                        </Cell>
                    </Row>
                    <!--Поступления-->
                    <Row :space="10" type="flex" justify="space-between" align="top">&nbsp;
                        <Cell width="12">
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24">&nbsp;</Cell>
                            </Row>

                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10"><h5>Поступления</h5></Cell>
                            </Row>
                            <!--heading-->
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10"></Cell>
                                <Cell width="4"> Всего</Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"> Нал</Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"> Безнал</Cell>
                            </Row>
                            <!---->
                            <Row v-if="lid.payment_system === 1" class="h-row-flex h-row-flex-middle" type="flex"
                                 justify="center" :space-y="10">
                                <Cell width="10">Цена контракта:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.price_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.price_cash"
                                                       @focusout="saveLid()"
                                                       :disabled="lid.customer_payment === 2"></Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.price_cashless"
                                                       @focusout="saveLid()"
                                                       :disabled="lid.customer_payment === 1"></Cell>
                            </Row>
                            <Row v-else class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Общая стоимость актов:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.volumes_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.volumes_cash" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.volumes_cashless" disabled>
                                </Cell>
                            </Row>


                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Поступления:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.receipts_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.receipts_cash" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.receipts_cashless"
                                                       disabled></Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Остаток:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.exp_total" disabled></Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.exp_cash" disabled></Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.exp_cashless" disabled>
                                </Cell>
                            </Row>


                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24">
                                    <table class="table statistic-act-table" style="background: #3c8dbc2b">
                                        <tbody>
                                        <tr @dblclick=" notPayed(),financeShow = !financeShow">
                                            <td>
                                                <Button v-if="[1,3].indexOf(lid.customer_payment) !== -1"
                                                        color="primary" size="s" @click="newRow(1,1)">+Нал.
                                                </Button>
                                                <span v-else>Нал.</span>
                                            </td>
                                            <td>
                                                <Button v-if="[2,3].indexOf(lid.customer_payment) !== -1"
                                                        color="primary" size="s" @click="newRow(2,1)">+Безнал.
                                                </Button>
                                                <span v-else>Безнал.</span>
                                            </td>
                                            <td>Акт</td>
                                            <td>Дата</td>
                                            <td colspan="5">Примечание</td>
                                            <td>
                                                <Button color="primary"
                                                        :icon="financeShow ? 'h-icon-top':'h-icon-down'"
                                                        @click="financeShow = !financeShow"></Button>
                                            </td>
                                        </tr>
                                        <tr>

                                        </tr>
                                        <template v-for="(col,key) in finance"
                                                  v-if="Number(col.type) === 1 && financeShow">

                                            <tr>
                                                <!--Нал-->
                                                <td>
                                                    <template v-if="col.form === 1">
                                                        <input v-if="editDebitID === col.id" type="text" class="w100"
                                                               v-model.number="col.amount" >
                                                        <span v-else>{{col.amount}}</span>
                                                    </template>
                                                </td>
                                                <!--Безнал-->
                                                <td>
                                                    <template v-if="col.form === 2">
                                                        <input v-if="editDebitID === col.id" type="text" class="w100"
                                                               v-model.number="col.amount" disabled>
                                                        <span v-else>{{col.amount}}</span>
                                                    </template>
                                                </td>
                                                <!--Акт-->
                                                <td>
                                                    <template v-if="editDebitID === col.id">
                                                    </template>
                                                    <template v-else>
                                                        <template v-if="col.act_id.length <= 1">
                                                            <span class="h-tag"
                                                                  v-for="actId in col.act_id">{{actId}}</span>
                                                        </template>

                                                    </template>
                                                </td>
                                                <!--Дата-->
                                                <td>
                                                    <DatePicker v-if="editDebitID === col.id" v-model="col.created_at"
                                                                format="YYYY-MM-DD"/>
                                                    <template v-else>
                                                        {{col.created_at.slice(0,-9)}}
                                                    </template>

                                                </td>
                                                <td colspan="5" style="text-align: left;">
                                                    <template>{{col.comment}}</template>
                                                </td>
                                                <!--Примечание-->
                                                <td>
                                                    <template v-if="editDebitID === col.id">
                                                        <Button color="primary" size="s" icon="h-icon-complete"
                                                                @click="updateRow(col),modalActs = null"/>
                                                    </template>
                                                    <template v-else>
                                                        <Button color="blue" size="s" icon="h-icon-edit"
                                                                @click="editDebitID = col.id,modalActs = key"/>
                                                    </template>
                                                </td>
                                            </tr>
                                            <tr v-if="col.act_id.length > 1">
                                                <td colspan="10" style="border-top: none;">
                                                    <span class="h-tag" v-for="actId in col.act_id">{{actId}}</span>
                                                </td>
                                            </tr>

                                            <tr v-if="modalActs === key">
                                                <td colspan="9">

                                                    <Select :datas="actsPrices" :multiple="true" v-model="col.act_id" @change="sumActs(col.act_id)"/>

                                                </td>
                                                <td colspan="1">
                                                    <Button color="primary" @click="setSelectActs(key)">+ / -</Button>
                                                </td>
                                            </tr>
                                            <tr v-if="modalActs === key">
                                                <td colspan="10"><textarea v-model="col.comment"></textarea></td>
                                            </tr>

                                        </template>


                                        </tbody>
                                    </table>
                                    <!--
                                                                 <table class="table">

                                                                     <thead>
                                                                     <tr>
                                                                         <th>
                                                                             <span v-if="lid.customer_payment === 2" >Нал</span>
                                                                             <Button v-else color="primary" icon="h-icon-plus" size="xs">Нал</Button>
                                                                         </th>
                                                                         <th>
                                                                             <span v-if="lid.customer_payment === 1">Безнал</span>
                                                                             <Button v-else color="primary" icon="h-icon-plus" size="xs">Безнал</Button>
                                                                         </th>
                                                                         <th>Акт</th>
                                                                         <th>Дата</th>
                                                                         <th>Примечание</th>
                                                                     </tr>
                                                                     </thead>

                                                                     <tbody>
                                                                     <tr>
                                                                         <td></td>
                                                                         <td></td>
                                                                         <td></td>
                                                                         <td></td>
                                                                         <td></td>
                                                                     </tr>
                                                                     </tbody>

                                                                 </table>
                                                                 -->
                                </Cell>
                            </Row>

                        </Cell>
                        <!--Выплаты-->
                        <Cell width="11">
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24">&nbsp;</Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24"><h5>Выплаты</h5></Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10"></Cell>
                                <Cell width="4"> Всего</Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"> Нал</Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"> Безнал</Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Выплатить:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payoff_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payoff_cash" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payoff_cashless" disabled>
                                </Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Выплачено:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payed_off_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payed_off_cash" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.payed_off_cashless"
                                                       disabled></Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="10">Остаток:</Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.to_payoff_total" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.to_payoff_cash" disabled>
                                </Cell>
                                <Cell width="1"></Cell>
                                <Cell width="4"><input type="text" class="w100" v-model="lid.to_payoff_cashless"
                                                       disabled></Cell>
                            </Row>
                            <Row class="h-row-flex h-row-flex-middle" type="flex" justify="center" :space-y="10">
                                <Cell width="24">
                                    <table class="table statistic-act-table" style="background: rgba(120,131,134,0.17)">
                                        <tbody>
                                        <tr @dblclick="financeShow = !financeShow">

                                            <td>
                                                <Button v-if="[1,3].indexOf(lid.customer_payment) !== -1"
                                                        color="primary" size="s" @click="newRow(1,2)">+Нал.
                                                </Button>
                                                <span v-else>Нал.</span>
                                            </td>
                                            <td>
                                                <Button v-if="[2,3].indexOf(lid.customer_payment) !== -1"
                                                        color="primary" size="s" @click="newRow(2,2)">+Безнал.
                                                </Button>
                                                <span v-else>Безнал.</span>
                                            </td>
                                            <td>Акт</td>
                                            <td>Дата</td>
                                            <td colspan="5">Назначение</td>
                                            <td></td>
                                        </tr>
                                        <tr v-for="col in finance" v-if="col.type === 2 && financeShow">

                                            <td>
                                                <template v-if="col.form === 1">
                                                    <input v-if="editCreditID === col.id" type="text" class="w100"
                                                           v-model.number="col.amount">
                                                    <span v-else>{{col.amount}}</span>
                                                </template>
                                            </td>
                                            <td>
                                                <template v-if="col.form === 2">
                                                    <input v-if="editCreditID === col.id" type="text" class="w100"
                                                           v-model.number="col.amount">
                                                    <span v-else>{{col.amount}}</span>
                                                </template>
                                            </td>
                                            <!--Акт-->
                                            <td>
                                                <template v-if="editCreditID === col.id">
                                                    <Select :datas="Object.keys(acts)" :multiple="true"
                                                            v-model="col.act_id"/>
                                                </template>
                                                <template v-else>
                                                    <span class="h-tag" v-for="i in col.act_id">{{i}}</span>
                                                </template>
                                            </td>
                                            <td>
                                                <DatePicker v-if="editCreditID === col.id" v-model="col.created_at"
                                                            format="YYYY-MM-DD"/>
                                                <template v-else>
                                                    {{col.created_at.slice(0,-9)}}
                                                </template>
                                            </td>
                                            <!--comment-->
                                            <td colspan="5" style="text-align: left;">
                                                <textarea v-if="editCreditID === col.id"
                                                          v-model="col.comment"></textarea>
                                                <template v-else>{{col.comment}}</template>
                                            </td>
                                            <td>
                                                <template v-if="editCreditID === col.id">
                                                    <Button color="primary" size="s" icon="h-icon-complete"
                                                            @click="updateRow(col)"/>
                                                </template>
                                                <template v-else>
                                                    <Button color="blue" size="s" icon="h-icon-edit"
                                                            @click="editCreditID = col.id"/>
                                                </template>
                                            </td>
                                        </tr>
                                        <tr v-for="i in 10"></tr>
                                        </tbody>
                                    </table>
                                    <!--
                                    <table class="table">

                                        <thead>
                                        <tr>
                                            <th>
                                                <span v-if="lid.customer_payment === 2" >Нал</span>
                                                <Button v-else color="primary" icon="h-icon-plus" size="xs"
                                                        @click="create_finance({lid_id:lid.id,})"

                                                >Нал</Button>
                                            </th>
                                            <th>
                                                <span v-if="lid.customer_payment === 1">Безнал</span>
                                                <Button v-else color="primary" icon="h-icon-plus" size="xs">Безнал</Button>
                                            </th>
                                            <th>Акт</th>
                                            <th>Дата</th>
                                            <th>Примечание</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        </tbody>

                                    </table>
                                    -->
                                </Cell>
                            </Row>
                        </Cell>
                    </Row>

                </Cell>

            </Row>
            <Row :space="30">
                <Cell width="24">
                    <textarea v-model="lid.comment" class="txt100" placeholder="Комментарий" @focusout="saveLid"/>
                </Cell>
            </Row>
            <template v-if="actsCopy.length">
                <Button class="top-btn" color="primary" @click="copyActs()">Копировать вабранные акты</Button>

            </template>
            <table class="h-table acts-tbl" v-if="Object.keys(h).length">
                <tr class="head">
                    <th>x</th>
                    <th>
                        <Checkbox v-model="checkAll" @change="ACT_CHECK_ALL(checkAll = !checkAll)"/>
                    </th>
                    <th>#</th>
                    <th>Создан</th>
                    <th>ఋ</th>
                    <th>Срок с</th>
                    <th>Срок до</th>
                    <th>Завершен?</th>
                    <th>Адрес</th>

                    <template v-if="[7].indexOf(h.user.role_id) !== -1">
                        <th>Бух. акт передан?</th>
                        <th>Бух. акт подписан?</th>
                        <th>Бух. акт скан</th>
                        <th>Исп. акт подписан?</th>
                        <th>Исп. акт скан</th>
                    </template>
                    <template v-if="[1,2,5].indexOf(h.user.role_id) !== -1">
                        <th>Дата выполнения</th>
                        <th>Услуга</th>
                        <th>Метраж</th>
                        <th>Сумма</th>
                        <th>Гарантия</th>
                    </template>

                    <th>
                        <Button :transparent="true" icon="h-icon-plus"
                                @click="createAct({ parent:id, creator:h.user.id, act_nr:countRootActs()})"/>
                    </th>
                </tr>
                <template v-for="act in reverseActs()">
                    <template v-if="!acts[act].subact">
                        <act :id="acts[act].id" :activeAct="open_act" :payment="lid.customer_payment"/>
                        <template v-for="a in getSubActs(acts[act].id)">
                            <act :payment="lid.customer_payment" :id="a.id"
                                 :activeAct="open_act" :is_sub="true"/>
                        </template>
                    </template>
                </template>
            </table>

        </div>
    </div>
</template>


<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    import {bus} from "../bus";
    import DadataOrganization from "../Components/DadataOrganization";
    import Act from "./Act";
    // import LogisticMap from "./LogisticMap";
    import DGMap from "./DGMap";
    import Axios from "axios";

    export default {
        name: 'Lid',
        props: ['id', 'salt', 'open_act'],
        data() {
            return {
                t: [15, 30, 45, 60],
                showGeo: false,
                selected: false,
                checkAll: false,
                editDebitID: 0,
                editCreditID: 0,
                financeShow: false,
                modalActs: false,

            }
        },
        components: {
            Act,
            DadataOrganization,
            // LogisticMap
            DGMap
        },
        computed: {

            ...mapGetters(['lid', 'h', 'customer', 'acts', 'lidActions', 'lidStatuses', 'actActions', 'actStatuses', 'mapSwitch', 'newImplement', 'actsCopy', 'finance']),
            actsPrices: {
                get() {
                    let arr = [];
                    let finArr = [];

                    this.finance.forEach((fin, k) => {
                        if (k != this.modalActs) {
                            fin.act_id.forEach(act => finArr.push(act))
                        }
                    });


                    Object.keys(this.acts).map((i) => {
                        let price_fact = 0;
                        let price_standard = 0
                        Object.keys(this.acts[i].volumes).map((v) => {
                            price_fact = Number(price_fact) + Number(this.acts[i].volumes[v].price_fact);
                            price_standard = Number(price_standard) + Number(this.acts[i].volumes[v].price_standard);
                        })
                        if (finArr.indexOf(this.acts[i].id) == -1) {
                            arr.push({
                                key: this.acts[i].id,
                                title: `${this.acts[i].id} (${price_fact} / ${price_standard})`
                            })
                        }

                    });

                    return arr;
                }
            },


            debit: {
                get() {
                    let debits = this.$store.getters.debit;
                    return debits
                },
                set(value) {
                    // this.$store.dispatch('fetch_finance',{lid_id: this.lid.id})
                }
            },
            credit: {
                get() {
                    let credits = this.$store.getters.credit;
                    return credits
                },
                set(value) {
                    // this.$store.dispatch('fetch_finance',{lid_id: this.lid.id})
                }
            },
            acts_total: {
                get() {
                    return this.$store.getters.acts_total;
                },
                set(value) {
                    // this.$store.dispatch('fetch_finance',{lid_id: this.lid.id})
                }
            },
        },

        methods: {
            ...mapActions(['createAct', 'cloneLid', 'collectSubActs', 'mongo', 'copyActs', 'deleteLid', 'fetch_finance', 'create_finance', 'update_finance']),
            ...mapMutations(['ACT_CHECK_ALL', 'FETCH_TOTAL']),


            actsPricesLeft() {
                let arr = [];
                let finArr = [];

                this.finance.map((fin) => fin.act_id.map(act => finArr.push(act)));


                Object.keys(this.acts).map((i) => {
                    let price_fact = 0;
                    let price_standard = 0
                    Object.keys(this.acts[i].volumes).map((v) => {
                        price_fact = Number(price_fact) + Number(this.acts[i].volumes[v].price_fact);
                        price_standard = Number(price_standard) + Number(this.acts[i].volumes[v].price_standard);
                    })
                    if (finArr.indexOf(this.acts[i].id) == -1) {
                        arr.push({
                            key: this.acts[i].id,
                            title: `${this.acts[i].id} (${price_fact} / ${price_standard})`
                        })
                    }

                });
                console.log('arr', arr);
                return arr;
            },

            sumActs(acts) {
                let sum = 0;
                let currentPayment = this.finance[this.modalActs];
                acts.forEach(a => {
                    console.log('a',a);
                    let volumes = this.acts[a].volumes;

                    if(currentPayment.form == 2){
                        sum = sum + Object.keys(volumes).reduce((acc,v) => acc + Number(volumes[v].price_standard),0)
                    } else {
                        sum = sum + Object.keys(volumes).reduce((acc,v) => acc + Number(volumes[v].price_fact),0)
                    }
                });
                currentPayment.amount = sum;
            },

            managers() {
                let u = {}
                for (let k in this.h.users) {
                    if ([2, 4].indexOf(this.h.users[k].role_id) !== -1) {
                        u[k] = this.h.users[k]
                    }
                }
                return u
            },

            /*            notPayed() {
                            let resultObj = {...this.acts};
                            this.finance.map(fin => {
                                fin.act_id.map(sub => {
                                    if(Object.keys(this.acts).indexOf(sub.toString()) !== -1) {
                                       delete resultObj[Number(sub)]
                                    }
                                })
                            });
                            console.log('resultArr',resultObj);
                            return resultObj;

                        },*/
            notPayed() {
                // let resultObj = {...this.acts};
                let testFin = []
                let resultObj = {}
                let actsId = Object.keys(this.acts);

                this.finance.map(fin => {
                    fin.act_id.map(sub => {
                        if (actsId.indexOf(String(sub)) != -1) {
                            resultObj[sub] = sub
                        }
                        testFin.push(sub.toString())
                    })
                });


                console.log('acts', actsId);
                console.log('finance', testFin);
                console.log('resultArr', resultObj);
                return resultObj;

            },

            setSelectActs(key) {
                if (this.finance[key].act_id.length > 0) {
                    this.finance[key].act_id = [];
                } else {
                    this.finance[key].act_id = Object.keys(this.acts);
                }
            },


            async newRow(form, type) {

                this.$set(this.$data, 'financeShow', true);
                await this.create_finance({
                    amount: 0,
                    form: form,
                    type: type,
                    lid_id: this.lid.id,
                    contract: this.lid.contract,
                    customer: this.customer.id,
                    manager: this.h.user.id,
                })
            },
            async updateRow(col) {
                this.$set(this.$data, 'financeShow', true);
                await this.update_finance(col)
                await this.saveLid()
                this.editDebitID = 0
                this.editCreditID = 0
            },
            async insertRow() {
                await this.update_finance({
                    amount: this.amount,
                    form: this.form,
                    type: this.type,
                    lid_id: this.lid.id,
                    // act_id: this.,
                    contract: this.lid.contract,
                    customer: this.customer.id,
                    manager: this.h.user.id,
                })
                this.amount = 0
                this.type = 1
                this.form = 1
            },

            async saveLid() {
                await this.$store.dispatch('saveLid', this.lid)
                this.init();
            },
            updateCustomer() {
                this.$store.dispatch('updateCustomer', this.customer)
            },
            getSubActs(actID) {
                let subActs = {};
                for (let a in this.acts) if (this.acts[a].subact == actID) {
                    subActs[this.acts[a].id] = this.acts[a];
                }
                return subActs;
            },
            reverseActs() {
                let arr = [];
                for (let i in this.acts) {
                    arr.push(i)
                }
                return arr.reverse();
            },
            countRootActs() {
                let res = 1;
                for (let a in this.acts) if (!this.acts[a].subact) {
                    res++;
                }
                return res;
            },
            async paymentRuleSave() {
                switch (this.lid.payment_rule) {
                    case 1:
                        break;
                        this.lid.rules_prepayment = 100;
                        this.lid.rules_postpayment = 0;
                    case 2:
                        this.lid.rules_prepayment = 0;
                        this.lid.rules_postpayment = 100;
                        break;
                    default:
                        this.lid.rules_prepayment = 100;
                        this.lid.rules_postpayment = 0;
                        break;
                }
                this.saveLid()
            },
            async paymentRulePercents() {
                this.lid.rules_postpayment = Number(this.lid.rules_prepayment) - Number(this.lid.rules_postpayment);
                this.saveLid()
            },
            async uploadFile(field, del = false) {
                try {
                    if (!del) {
                        let file = event.target.files[0] || event.dataTransfer.files[0];
                        let formData = new FormData();
                        formData.append('file', file);
                        let response = await Axios.post('/attach', formData, {headers: {'Content-Type': 'multipart/form-data'}});
                        if (response.data.uploaded) {
                            await this.$store.dispatch('updateLid', {
                                id: this.lid.id,
                                values: {contract_file: response.data.fileName}
                            });
                        }
                    } else {
                        this.$Confirm('Удалить файл?', '').then(() => {
                            this.$store.dispatch('updateLid', {id: this.lid.id, values: {contract_file: null}});/* this.$Message.success('Удалено');*/
                        }).catch(() => {
                            this.$Message.success('Отменено');
                        });
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            async init() {
                await this.$store.dispatch('fetchLid', this.id);
                await this.$store.dispatch('fetchHelpers');
                await this.$store.dispatch('fetchActs', this.id);
                await this.$store.dispatch('fetchCustomer', this.lid.customer);
                await this.$store.dispatch('fetchLidActions', this.id);
                await this.$store.dispatch('fetchLidStatuses', this.id);
                await this.$store.dispatch('fetchActActions', this.id);
                await this.$store.dispatch('fetchActStatuses', this.id);
                await this.$store.dispatch('fetch_finance', {lid_id: this.lid.id});
            },
            eventCreator(ev, val = false) {
                bus.$emit(ev, val);
            },
        },


        created() {
            console.log('created');
            bus.$on('success', function (a) {
                this.$Notice({type: 'success', title: a.table + ': ' + a.mess, content: ''})
            });
            bus.$on('error', function (a) {
                this.$Notice({type: 'error', title: a.table + ': ' + a.mess, content: a.state})
            })
            bus.$on('trig', (a) => {
                this.$set(this.lid, 'customer', a);
            });
            bus.$on('con-firm', (a) => {
                // console.log(a);
                // let btn = `<Button @click=""></Button>`
                // a.state
                this.$Notice({
                    type: 'warn',
                    title: a.table,
                    content: a.mess,
                    timeout: 0,
                    buttons: [
                        {
                            name: 'Отменить',
                            type: 'cancel'
                        },
                        {
                            name: 'Продолжить',
                            color: 'primary',
                            type: 'confirm'
                        }
                    ],
                    events: {
                        confirm: e => {
                            this.$store.dispatch(a.method, a.payload);
                            e.close();
                        },
                        cancel: e => {
                            this.$Message('Отменено');
                            e.close();
                        }
                    }
                });
            });
            bus.$on('volume_price_updated', () => {
                this.init();
            })
        },
        mounted() {
            this.init();
        }
    };
</script>

<style lang="scss">
    .lid input[type="text"] {
        width: 100%;
    }

    .lid-left {

    }

    .h-poptip {
        width: 225px;
    }

    .top-btn {
        position: fixed;
        top: 10px;
        left: 66px;
        box-shadow: 0 0 100px -4px #000000;
        border: 2px solid white !important;
        z-index: 1000;
    }


</style>
