import Axios from "axios";
import {bus} from "../../bus";

const customerError = (mess,state = '') => {
    bus.$emit('error',{table:'Клиент',mess:mess,state:state});
};
const customerSuccess = (mess = 'Сохранено!') => {
    bus.$emit('success',{table:'Клиент',mess:mess});
};

const customerTable = {
    state: {
        customer:{}
    },
    getters: {
        customer(state){
            return state.customer
        }
    },
    mutations: {
        FETCH_CUSTOMER(state, payload) { state.customer = payload },
        updateCustomer(state, payload) {state.customer = payload}
    },
    actions: {
        async fetchCustomer({commit},id) {
            let obj = id ? {func: 'getByID',  id:id} : {func: 'emptyTable'};
             try {
                 let response = await Axios.post('/customer/async', obj);
                 commit('FETCH_CUSTOMER', response.data)
             } catch (error) {
                 console.log(error)
             }
        },
        async updateCustomer({commit},payload){
            try {
                let response = await Axios.post('/customer/async',{
                    func:'update',
                    id:payload.id,
                    values:payload.values
                });
                if(response.data){
                    this.dispatch('fetchCustomer',payload.id);
                    customerSuccess();
                }
            } catch (error) {  customerError('Данные клиента не сохранены',error) }
        },
        async createCustomer({commit},payload){
            try {
                let response = await Axios.post('/customer/async',{
                    func: 'getCreated',
                    values: payload
                });
                if(response.data){
                    commit('FETCH_CUSTOMER', response.data);
                    customerSuccess();
                } else  { customerError() } } catch (error) { customerError(error)}
        },
    }
};


export default customerTable;
