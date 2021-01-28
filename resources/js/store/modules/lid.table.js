import Axios from "axios";
import {bus} from "../../bus";


const lidError = (mess, state = '') => {
    bus.$emit('error', {table: 'Лид', mess: mess, state: state});
};
const lidSuccess = (mess = 'Сохранено!') => {
    bus.$emit('success', {table: 'Лид', mess: mess});
};

const lidTable = {
    state: {
        lid: {},
        finance:[],
        debit: {
            total: 0,
            cash:0,
            cashless:0
        },
        credit: {
            total: 0,
            cash:0,
            cashless:0
        },
        acts_total: {
            total: 0,
            cash:0,
            cashless:0
        },
        bindActs:{}

    },
    getters: {
        lid(state) {
            return state.lid
        },
        finance(state) {
            return state.finance
        },
        debit(state) {
            return state.debit
        },
        credit(state) {
            return state.credit
        },
        acts_total(state) {
            return state.credit
        },
    },
    mutations: {
        FETCH_LID(state, payload) {
            state.lid = payload
        },
        BIND_ACTS(state, payload) {
            state.bindActs = payload
        },
        saveLid: (state, payload) => {
            state.lid = payload
        },
        FETCH_FINANCE(state, payload) {
            state.finance = payload
        },
        FETCH_TOTAL(state, payload) {
            let debit = {total: 0, cash:0, cashless:0}
            let credit = {total: 0, cash:0, cashless:0}
            let acts = {total: 0, cash:0, cashless:0}
            let payoff = {total: 0, cash:0, cashless:0}
            let payBack = {total: 0, cash:0, cashless:0}

            payload.forEach((obj) => {
                if(obj.type === 1) { // debit
                    obj.form === 1 ? debit.cash += obj.amount : debit.cashless += obj.amount
                    debit.total += obj.amount
                } else  {
                    obj.form === 1 ? credit.cash += obj.amount : credit.cashless += obj.amount
                    credit.total += obj.amount
                }
            })

            Object.keys(state.bindActs).forEach((a) => {
                payoff.total += Number(state.bindActs[a].contractor_fee) + Number(state.bindActs[a].overpay_rest)
                payoff.cash += Number(state.bindActs[a].contractor_fee_cash) + Number(state.bindActs[a].overpay_rest)
                payoff.cashless += Number(state.bindActs[a].contractor_fee_cashless)

                payBack.total += Number(state.bindActs[a].pay_back)
                payBack.cash += Number(state.bindActs[a].pay_back_cash)
                payBack.cashless += Number(state.bindActs[a].pay_back_cashless)

                Object.keys(state.bindActs[a].volumes).forEach(key => {
                    acts.total +=  (Number(state.bindActs[a].volumes[key].price_fact) +  Number(state.bindActs[a].volumes[key].price_standard))
                    acts.cash +=  Number(state.bindActs[a].volumes[key].price_fact)
                    acts.cashless += Number(state.bindActs[a].volumes[key].price_standard)
                })
            })


            state.lid.price_total = Number(state.lid.price_cash) + Number(state.lid.price_cashless)
// volumes
            state.lid.volumes_total = acts.total
            state.lid.volumes_cash = acts.cash
            state.lid.volumes_cashless = acts.cashless

// receipts
            state.lid.receipts_total = debit.total
            state.lid.receipts_cash = debit.cash
            state.lid.receipts_cashless = debit.cashless

// totals to pay back
            let paysBacTotal = payoff.total + payBack.total
            let paysBacCash = payoff.cash + payBack.cash
            let paysBacCashless = payoff.cashless + payBack.cashless

            console.log('paysBacTotal',paysBacTotal);


// payoffs
            state.lid.payoff_total = paysBacTotal
            state.lid.payoff_cash = paysBacCash
            state.lid.payoff_cashless = paysBacCashless
// payed
            state.lid.payed_off_total = credit.total
            state.lid.payed_off_cash = credit.cash
            state.lid.payed_off_cashless = credit.cashless
// to pay
            state.lid.to_payoff_total = Number(paysBacTotal) - Number(credit.total)
            state.lid.to_payoff_cash = Number(paysBacCash) - Number(credit.cash)
            state.lid.to_payoff_cashless = Number(paysBacCashless) - Number(credit.cashless)


            if(state.lid.payment_system === 1){
                state.lid.exp_total = Number(state.lid.price_total) - Number(state.lid.receipts_total)
                state.lid.exp_cash = Number(state.lid.price_cash) - Number(state.lid.receipts_cash)
                state.lid.exp_cashless = Number(state.lid.price_cashless) - Number(state.lid.receipts_cashless)
                state.lid.balance = Number(state.lid.price_total) - Number(state.lid.payoff_total)
            } else {
                state.lid.exp_total = Number(state.lid.volumes_total) - Number(state.lid.receipts_total)
                state.lid.exp_cash = Number(state.lid.volumes_cash) - Number(state.lid.receipts_cash)
                state.lid.exp_cashless = Number(state.lid.volumes_cashless) - Number(state.lid.receipts_cashless)
                state.lid.balance = Number(state.lid.volumes_total) - Number(state.lid.payoff_total)
            }



            state.debit = debit
            state.credit = credit
            state.acts_total = acts
        },

    },
    actions: {

        async mongo({state, dispatch, commit,rootGetters},payload){

            let acts = rootGetters.acts;
            let lid = state.lid;
            lid['acts'] = acts;

            try {
                let response = await Axios.post('/lid/mongo',{
                    func: 'sync',
                    id: state.lid.id,
                    object: lid
                });

                console.log(response.data);
            } catch (error) {
                console.log(error);
            }
        },

        async fetchLid({commit,dispatch,rootGetters}, id) {
            try {
                let response = await Axios.post('/lid/async', {func: 'get', id});
               await commit('FETCH_LID', response.data);
               await dispatch('fetchActs',id);

            } catch (error) {console.log(error)}
        },
        async saveLid({commit}, payload) {
            try {
                let response = await Axios.post('/lid/async', {
                    func: 'update',
                    id: payload.id,
                    values: payload
                });
                if (response.data) {
                    this.dispatch('fetchLid', payload.id);
                    lidSuccess();
                }
            } catch (error) {lidError('Состояние лида не сохранено', error)}
        },
        async updateLid({commit}, payload) {
            try {
                let response = await Axios.post('/lid/async', {
                    func: 'update',
                    id: payload.id,
                    values: payload.values,
                });

                if (response.data) {
                    this.dispatch('fetchLid', payload.id);
                    lidSuccess();
                }
            } catch (error) {lidError('Состояние лида не сохранено', error)}
        },
        async cloneLid({commit,dispatch},payload){
            try {
                let response = await Axios.post('/lid/async',{
                    func: 'clone',
                    id: payload
                });
                if(await response.data){
                    window.location.href = '/layout/lid/'+response.data+'/edit';
                }
            } catch (e) {
                console.log(e);
            }
        },
        async deleteLid({commit,dispatch,rootGetters}, payload) {
            try {
                let response = await Axios.post('/lid/async', {
                    func: 'delete',
                    id: payload.id,
                });
                if (await response.data) {
                    await dispatch('createLog', {
                        log: {
                            id: payload.id,
                            model: 'lid',
                            state: 'delete',
                            user: rootGetters.h.user.id,
                            lid_id: payload.id,
                            field: '',
                            object: {
                                lid: rootGetters.lid,
                                acts: rootGetters.acts
                            },
                        },
                        mess: {
                            success: 'Лид удален',
                            error: 'Лид удалить не удалось',
                        },
                    });
                     window.location.href = '/vue/lid';
/*                    await bus.$on('the-trigger', function ()  {
                        window.location.href = '/vue/lid';
                    });*/
                }
            } catch (error) {lidError('Состояние лида не сохранено', error)}
        },



        async fetch_finance({state, dispatch, commit,rootGetters},data){
            try {
                let response = await Axios.post('/vuex/finance',{
                    func: 'get',
                    data: data
                });
                await commit('FETCH_FINANCE',response.data)
                await commit('BIND_ACTS',rootGetters.acts);
                commit('FETCH_TOTAL',response.data)
            } catch (error) {
                console.log(error);
            }
        },
        async get_finance({state, dispatch, commit,rootGetters},data){
            try {
                let response = await Axios.post('/vuex/finance',{
                    func: 'get',
                    data: data
                });
                if(response.data){
                    return response.data
                }
            } catch (error) {
                console.log(error);
            }
        },
        async create_finance({state, dispatch, commit,rootGetters},data){
            try {
                let response = await Axios.post('/vuex/finance',{
                    func: 'createRow',
                    data: data
                });
                commit('FETCH_FINANCE',response.data)
                commit('FETCH_TOTAL',response.data)
            } catch (error) {
                console.log(error);
            }
        },
        async update_finance({state, dispatch, commit,rootGetters},data){
            try {
                let response = await Axios.post('/vuex/finance',{
                    func: 'readUpdateRow',
                    data: data
                });
                commit('FETCH_FINANCE',response.data)
                commit('FETCH_TOTAL',response.data)
            } catch (error) {
                console.log(error);
            }
        },
        async delete_finance({state, dispatch, commit,rootGetters},data){
            try {
                let response = await Axios.post('/vuex/finance',{
                    func: 'readUpdateRow',
                    data: data
                });
                commit('FETCH_FINANCE',response.data)
                commit('FETCH_TOTAL',response.data)
            } catch (error) {
                console.log(error);
            }
        },



    }
};

//  @TODO-uretral:   Удаление лидов с чисткой сопутствующих актов/обьемов/выполнений
//  @TODO-uretral:   Удаление актов с чисткой сопутствующих обьемов/выполнений


export default lidTable;
