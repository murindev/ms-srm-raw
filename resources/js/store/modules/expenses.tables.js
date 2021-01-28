import Axios from "axios";

const expensesTables = {
    state: {
        fixedDate:  {
            "start": moment().startOf('month').format('YYYY-MM'),
            "end": moment().endOf('month').format('YYYY-MM'),
            "type": "month"
        },
        dailyDate: moment().format('YYYY-MM-DD'),
        expensesHelpersFixed: [],
        expensesHelpersDaily: [],
        expensesFixed: [],
        expensesDaily: [],
    },
    getters: {
        fixedDate(state) {
            return state.fixedDate
        },
        dailyDate(state) {
            return state.dailyDate
        },
        expensesHelpersFixed(state) {
            return state.expensesHelpersFixed
        },
        expensesHelpersDaily(state) {
            return state.expensesHelpersDaily
        },
        expensesFixed(state) {
            return state.expensesFixed
        },
        expensesDaily(state) {
            return state.expensesDaily
        },
    },
    mutations: {
        FIXED_DATE(state, payload) { state.fixedDate = payload},
        DAILY_DATE(state, payload) { state.dailyDate = payload},
        EXPENSES_HELPERS(state, payload) {
            state.expensesHelpersFixed =  payload.filter(ob => ob.type === 'fixed');
            state.expensesHelpersDaily =  payload.filter(ob => ob.type === 'daily');
        },
        EXPENSES(state, payload) {
            state.expensesFixed =  payload.filter(ob => ob.type === 'fixed');
            state.expensesDaily=  payload.filter(ob => ob.type === 'daily');
        },
        EXPENSE_FIXED_VALUE(state, payload) {
            state.expensesFixed[payload.key].value = payload.value
        },
        EXPENSE_DAILY_VALUE(state, payload) {
            state.expensesDaily[payload.key].value = payload.value
        }
    },
    actions: {
        // HELPERS
        readExpensesHelpers({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'helperRead'
            })
                .then(response => commit('EXPENSES_HELPERS', response.data))
                .catch(error => console.log(error));
        },
        createExpensesHelpers({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'helperCreate',
                values:payload
            })
                .then(response => dispatch('readExpensesHelpers'))
                .catch(error => console.log(error));
        },
        updateExpensesHelpers({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'helperUpdate',
                id: payload.id,
                values:payload
            })
                .then(response => dispatch('readExpensesHelpers'))
                .catch(error => console.log(error));
        },
        deleteExpensesHelpers({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'helperDelete',
                id: payload
            })
                .then(response => dispatch('readExpensesHelpers'))
                .catch(error => console.log(error));
        },

        // EXPENSES
        readExpenses({commit,dispatch,getters},payload) {
            return Axios.post('/expenses/async', {
                func: 'read',
                value:payload
            })
                .then(response => commit('EXPENSES', response.data))
                .catch(error => console.log(error));
        },
        createExpenses({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'create',
                values: payload
            })
                .then(response => dispatch('readExpenses',payload.date))
                .catch(error => console.log(error));
        },
        updateExpenses({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'update',
                id:payload.id,
                values:payload
            })
                .then(response => dispatch('readExpenses',payload.date),dispatch('fetchCrewByImplement',payload.date))
                .catch(error => console.log(error));
        },
        deleteExpenses({commit,dispatch},payload) {
            return Axios.post('/expenses/async', {
                func: 'delete',
                id: payload.id,
            })
                .then(response => dispatch('readExpenses', payload.date))
                .catch(error => console.log(error));
        },

    }
};

export default expensesTables;
