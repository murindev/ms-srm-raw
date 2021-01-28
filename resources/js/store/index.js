import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
import lidTable from "../store/modules/lid.table";
import helpers from "../store/modules/helpers";
import actsTable from "../store/modules/act.table";
import logTables from "../store/modules/log.tables";
import customerTable from "../store/modules/customer.table";
// import logistic from "../store/modules/logistic";
import map from "../store/modules/map";
import crew from "../store/modules/crew";
import crewSchedulingTable from "../store/modules/crew.scheduling.table";
import rules from "../store/modules/rules";
import logs from "../store/modules/logs";
import implementTable from "../store/modules/implement.table";
import expensesTables from "../store/modules/expenses.tables";
import lidStatisticsTable from "../store/modules/lid.statistics.table";
import settingsTable from "../store/modules/settings.table";
import messengerTable from "../store/modules/messenger.table";
import task from "../store/modules/tasks";



const index = new Vuex.Store({

    modules: {
        helpers,
        lidTable,
        actsTable,
        customerTable,
        logTables,
        // logistic,
        map,
        crew,
        crewSchedulingTable,
        rules,
        logs,
        implementTable,
        expensesTables,
        lidStatisticsTable,
        settingsTable,
        messengerTable,
        task,

    }
});

if (module.hot) {
    module.hot.accept([
        '../store/modules/lid.table',
        '../store/modules/helpers',
        '../store/modules/act.table',
        '../store/modules/customer.table',
        '../store/modules/log.tables',
        // '../store/modules/logistic',
        '../store/modules/map',
        '../store/modules/crew',
        '../store/modules/crew.scheduling.table',
        '../store/modules/rules',
        '../store/modules/logs',
        '../store/modules/implement.table',
        '../store/modules/expenses.tables',
        '../store/modules/lid.statistics.table',
        '../store/modules/settings.table',
        '../store/modules/messenger.table',
        '../store/modules/tasks',

    ], () => {
        const lidTable = require('../store/modules/lid.table').default;
        const helpers = require('../store/modules/helpers').default;
        const actsTable = require('../store/modules/act.table').default;
        const logTables = require('../store/modules/log.tables').default;
        const customerTable = require('../store/modules/customer.table').default;
        // const logistic = require('../store/modules/logistic').default;
        const map = require('../store/modules/map').default;
        const crew = require('../store/modules/crew').default;
        const crewSchedulingTable = require('../store/modules/crew.scheduling.table').default;
        const rules = require('../store/modules/rules').default;
        const logs = require('../store/modules/logs').default;
        const implementTable = require('../store/modules/implement.table').default;
        const expensesTables = require('../store/modules/expenses.tables').default;
        const lidStatisticsTable = require('../store/modules/lid.statistics.table').default;
        const settingsTable = require('../store/modules/settings.table').default;
        const messengerTable = require('../store/modules/messenger.table').default;
        const tasks = require('../store/modules/tasks').default;
        index.hotUpdate({
            modules: {
               helpers,lidTable,actsTable,customerTable,logTables,map,crew,crewSchedulingTable,rules,logs,implementTable,expensesTables,lidStatisticsTable,settingsTable,messengerTable,tasks // logistic,
            }
        })
    })
}
export default index;
