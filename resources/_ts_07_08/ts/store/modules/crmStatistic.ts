import {VuexModule, Module, Mutation, Action, MutationAction} from 'vuex-module-decorators'
import * as moment from "moment"
import * as Types from '../../types/crm.statistic'
import axios from 'axios'
import {IFieldValue} from "../../types/Global";
import {IAppStatus, ICallStatus, ICrmStatistic} from "../../types/crm.statistic";
import {ILid} from "../../types/lid";



@Module({
    namespaced: true
})
export default class CrmStatistic extends VuexModule {
    // State
    public period: Types.IPeriod = {
        start: moment().subtract(20, "days").format('YYYY-MM-DD'),
        end: moment().endOf('month').add(2, 'days').format('YYYY-MM-DD'),
        type: 'customize'
    }
    get getPeriod(){
        return this.period
    }
/*    public period: Types.IPeriod = {
        start: '2020-07-01',
        end: '2020-07-30',
        type: 'customize'
    }*/
    public table: ICrmStatistic[] = []
    public row: ICrmStatistic = {}
    public settings: Types.ISettings = {
        modal: false,
        tabs: {
            site: 'Сайты',
            manager: 'Менеджеры',
            status: 'Статусы',
            conversion: 'Конверсия'
        },
        activeTab: 'conversion',
    }
    public callStatus: ICallStatus[] = [
        {id: 1, name: 'спам', select: true, class: '', design: 'row_grey'},
        {id: 2, name: 'перезвонить', select: true, class: 'recall', design: 'row_yellow'},
        {id: 3, name: 'невыгодно', select: true, class: '', design: 'row_grey'},
        {id: 4, name: 'нецелевой', select: true, class: '', design: 'row_grey'},
        {id: 5, name: 'отказ', select: true, class: 'refused', design: 'row_red'},
        {id: 6, name: 'история', select: false, class: '', design: 'row_default'},
        {id: 7, name: 'заказ', select: true, class: 'ordered', design: 'row_green'},
        {id: 8, name: 'база', select: false, class: '', design: 'row_default'},
        {id: 9, name: 'новая', select: false, class: '', design: 'row_default'},
        {id: 10, name: 'на удаление', select: true, class: 'deleted', design: 'row_red'},
    ]
    get getCallStatus(){
        return this.callStatus
    }
    public appStatus: IAppStatus[] = [
        {id: 1, name: 'новая', design: 'row_grey'},
        {id: 2, name: 'база', design: 'row_yellow'},
        {id: 3, name: 'рекоммендация', design: 'row_grey'}
    ]
    public curatorsRole: number[] = [1,4]

    public globalSearch: boolean = false

    // MutationAction

    //PERIOD
    @Mutation setPeriod(payload: Types.IPeriod) {
        this.period = payload
    }

    // TABLE

    @Mutation setTable(payload: ICrmStatistic[]) {
        this.table = payload
    }

    @Action({commit: 'setTable'})
    async initTable() {
        let response = await axios.post('/vuex/crm-statistic', {func: 'init', data: this.period});
        return response.data
    }

    // ROW
    @Mutation addUpdatedTableRow(payload: Types.ICrmStatistic) {
        if (this.row && this.row.id === payload.id) {
            this.row = payload
        }

        this.table = this.table.map(o => {
            if (o.id === payload.id) {
                return payload
            }
            return o;
        })
    }
    @Mutation setUpdatedTableByLid(lid: ILid) {
        if (this.row && this.row.lid_id === lid.id) {
            this.row.lid = lid
        }

        this.table = this.table.map(o => {
            if (o.lid_id === lid.id) {
                o.lid = lid
                return o
            }
            return o;
        })
    }
    @Mutation setUpdateRow(payload: ICrmStatistic) {
        this.table = this.table.map(o => {
            if (o.id === payload.id) {
                return payload
            }
            return o;
        })
        if (this.row && this.row.id === payload.id) {
            this.row = payload
        }
    }
    @Action({commit: 'setUpdateRow'})
    public async updateRow(data: ICrmStatistic) {
        let response = await axios.post('/vuex/crm-statistic', {func: 'updateRow', data: data})
        return response.data
    }
    // new lid
    @Action({commit: 'setUpdateRow'}) public async newLid(payload: {id:number,data:ILid}) {
        let response = await axios.post('/vuex/crm-statistic',{func: 'newLid', id: payload.id, data: payload.data})
        return response.data
    }



// customer
    /*
    *   customer_id
    * */
// new customer
    /*
    *   customer_id
    * */
// customers lid
    /*
*   customer_id
*   lid_id
* */
// new lid
    /*
*   customer_id
*   new lid_id
* */





//------------------------------------
// TABLE
    // table -> fetch



    // table -> row
    @Mutation unshiftRow(payload: ICrmStatistic) {
        this.table.unshift(payload)
    }

    // table -> update

    @Action updateTableRow(payload: ICrmStatistic): void {
        axios.post('/lid_statistic/async', {func: 'update', values: payload})
        /*      try {
                let response = await axios.post('/lid_statistic/async',{
                  func: 'update',
                  values: payload
                })
                return response.data
              } catch (e) {console.log(e)}*/

    }


// ROW

    /** @method CrmStatistic.setInitTableRow
     * Инициирует обьет в соответствии с его дефолтными значениями*/
/*    @Mutation
    public initTableRow(payload: ICrmStatistic) {
        this.row = {...payload}
    }*/
    @Mutation
    public setInitTableRow(payload: ICrmStatistic) {
        this.table = this.table.map(o => {
            if (o.id === payload.id) {
                return payload
            }
            return o;
        })
        this.row = payload
    }
    /** @method CrmStatistic.initTableRow*/
    @Action({commit: 'setInitTableRow'}) public async initTableRow(payload: ICrmStatistic) {
        let response = await axios.post('/vuex/crm-statistic',{func: 'initTableRow', data: payload})
        return response.data
    }

    /** @method CrmStatistic.setTableRow */
    @Mutation
    public setTableRow(payload: any) {
        this.row = payload
    }

    @Mutation
    public clearTableRow() {
        this.row = {}
    }

    // @Mutation public addTableRowProperty

    @Action
    public eUpdateField(payload: IFieldValue) {
        axios.post('/vuex/crm-statistic', {func: 'eUpdateField', data: payload})
    }

//SETTINGS

    @Mutation setSettings(payload: any) { // Types.IKeyValue
        console.log('setSettings', payload);
        // this.setting[payload.key] = payload.value
    }

//EOF SETTINGS


//CUSTOMERS


/////////////////////////////////////////


    /**
     * @return /App\Http\Vuex\CrmStatisticVuex::fetchRow
     * @param payload
     */
    @Action({commit: 'addUpdatedTableRow'})
    async fetchRow(payload: string) {
        let response = await axios.post('/vuex/crm-statistic', {func: 'fetchRow', data: payload})
        return response.data
    }




    ///////////////////////////////
    @Action eUpdateRow(payload: ICrmStatistic) {
        axios.post('/vuex/crm-statistic', {func: 'eUpdateRow', data: payload})
    }

    @Action eCreateRow(payload: ICrmStatistic): void {
        axios.post('/vuex/crm-statistic', {func: 'eCreateRow', data: payload})
    }

    @Action  async eImprove(payload: string) {
        let response = await axios.post('/vuex/8888', {func: '8888', data: payload})
        return response.data
    }
}


