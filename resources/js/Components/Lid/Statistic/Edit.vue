<template>

    <div>
        <!--
        <Modal className="statistic-modal" v-model="edit.modal" :closeOnMask="false">

            <div slot="header">
                <div class="statistic-form-btn" style="margin: 10px;">
                    <div style="margin-right: auto;">
                        <span class="h-tag h-tag-gray">{{edit.clonedRow.id}}</span>
                    </div>
                    <div style="margin-right: auto;">
                        <span class="h-tag h-tag-blue">{{edit.clonedRow.date}}</span>
                        <span class="h-tag h-tag-primary">{{edit.clonedRow.time}}</span>
                    </div>
                    <div style="margin-right: auto;">

                        <input type="text" v-model="search.model" style="display: block;">
                        &lt;!&ndash;                            <Search  v-model="search.model" position="front" trigger-type="input" style="display: block;"/>&ndash;&gt;

                    </div>

                    <Button icon="h-icon-close"
                            @click="editRow(edit.clonedRow,'close')">Закрыть
                    </Button>
                </div>
            </div>

            <div class="statistic-search-container" v-if="lidStatisticSearchCustomer && lidStatisticSearchCustomer.length">

                <table class="table statistic-table">

                    <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Должностное лицо</th>
                        <th>Организация</th>
                        <th>Телефон</th>
                        <th>email</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr v-for="item in lidStatisticSearchCustomer">
                        <td>{{item.name}}</td>
                        <td>{{item.management_name}}</td>
                        <td>{{item.organization}}</td>
                        <td style="white-space: nowrap">{{item.phone}}</td>
                        <td style="white-space: nowrap">{{item.email}}</td>
                    </tr>
                    </tbody>

                </table>
            </div>
            <Form :label-width="150" mode="twocolumn">

                <FormItem label="Сайт">
                    <Select v-if="h.companies" v-model="edit.clonedRow.site" :datas="table.site.select()" keyName="id"
                            titleName="name"/>
                </FormItem>

                <FormItem label="Менеджер">

                    <Select v-if="h.users" v-model="edit.clonedRow.manager" :datas="table.manager.select()"
                            :disabled="globalRole.indexOf( h.users[Number(manager)].role_id) === -1"
                            keyName="id" titleName="name"/>

                </FormItem>
                <FormItem label="Статус">
                    <Select v-model="edit.clonedRow.call_status" :datas="table.call_status.select" keyName="id"
                            titleName="name" @change="changeCallStatus(edit.clonedRow)"></Select>
                </FormItem>
                <FormItem label="Статус заявки">
                    <Select v-model="edit.clonedRow.app_status" :datas="table.app_status.select" keyName="id"
                            titleName="name"></Select>
                </FormItem>
                <FormItem label="Суть">
                    <textarea rows="3" v-autosize v-model="edit.clonedRow.cause"></textarea>
                </FormItem>
                <FormItem label="Адрес">
                    <textarea rows="3" v-autosize v-model="edit.clonedRow.address"></textarea>
                </FormItem>
                <FormItem label="Нал">
                    <input type="number" v-model.number="edit.clonedRow.cash">
                </FormItem>
                <FormItem label="Безнал">
                    <input type="number" v-model.number="edit.clonedRow.cashless">
                </FormItem>
                <FormItem label="Телефон">
                    &lt;!&ndash;                    <the-mask v-model="edit.clonedRow.phone" mask="+7(###) ### ## ##"/>&ndash;&gt;
                    <input type="text" v-model="edit.clonedRow.phone">
                </FormItem>
                <FormItem label="Имя">
                    <input type="text" v-model="edit.clonedRow.name">
                </FormItem>
                <FormItem label="№ договора">
                    <input type="text" v-model="edit.clonedRow.contract">
                </FormItem>
                <FormItem label="Выполнение">
                    <input type="text" v-model="edit.clonedRow.implement_date">
                </FormItem>

            </Form>

            <div class="statistic-form-btn">

                <Button color="primary" icon="h-icon-left" @click="saveRow(edit.clonedRow.id)">Сохранить</Button>

                <Poptip content="Вы действительно хотите удалить?" @confirm="deleteRow(edit.clonedRow.id)"
                        placement="left">
                    <Button color="red" icon="h-icon-close-min">Удалить</Button>
                </Poptip>
                <a v-if="edit.clonedRow.lid_id" target="_blank" :href="`/layout/lid/${edit.clonedRow.lid_id}/edit`">
                    <Button color="yellow" icon="h-icon-link">Открыть лид</Button>
                </a>

                <Poptip v-else-if="edit.clonedRow.call_status === 7" content="Создать лид?"
                        @confirm="createLid(edit.clonedRow)"
                        placement="left">
                    <Button color="primary" icon="h-icon-angle-right">Создать лид</Button>
                </Poptip>

                <Button v-else color="primary" disabled icon="h-icon-angle-right">1321</Button>

                <Button v-if="edit.clonedRow.call_status === 7 && !edit.clonedRow.lid_id"
                        @click="checkModal(edit.clonedRow.id)"
                        color="primary" icon="h-icon-outbox">Поиск лида
                </Button>
            </div>


        </Modal>
-->
    </div>

</template>

<script>
    // import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "Edit",
        computed: {
            // ...mapGetters(['lidStatisticSearchCustomer']),
        }
    }
</script>

