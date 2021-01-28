<template>
    <div>
        <Modal class="actModal" v-model="settings.modal" :hasCloseIcon="true">
            <Tabs :datas="settings.tabs" v-model="settings.activeTab"/>
            <br/>
<!--            <div v-if="setting.activeTab === 'site'">
                <Transfer
                        v-model="settingsSite"
                        :datas="Object.values(h.companies)" keyName="id"
                        @change="updateSettings({manager: manager, type: 'statisticsSites', settings: arguments[0]})"
                >
                    <template slot-scope="{option}" slot="item">{{option.name}}</template>
                </Transfer>
            </div>
            <div v-if="setting.activeTab === 'manager'">
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
                    &lt;!&ndash;                    <tfoot>
                                        <tr>
                                            <th>2</th>
                                            <th>2</th>
                                            <th>2</th>
                                            <th>2</th>
                                        </tr>
                                        </tfoot>&ndash;&gt;


                </table>

            </div>-->
        </Modal>

    </div>
</template>

<script lang="ts">
    import { Component, Prop, Vue } from 'vue-property-decorator'
    import { namespace } from 'vuex-class'
    import * as Types from "../../../types/crm.statistic";
    const CrnStatistic = namespace('CrmStatistic')

    @Component
    export default class Settings extends Vue {
        // @Prop() settings!:object

        // SETTINGS
        get settings() { return this.$store.state.CrmStatistic.settings }
        set settings(value) {this.setSettings(value)}
        @CrnStatistic.Mutation public setSettings!: (payload: object) => void






        // @ex.Getter
        // private textUpper!: string
        // @ex.Mutation
        // private setText!: (newText: string) => void
        //
        // changeText (event: any): void {
        //     this.setText(event.target.value)
        // }
    }
</script>
