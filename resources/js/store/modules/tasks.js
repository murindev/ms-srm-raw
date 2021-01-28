import Axios from "axios";
console.log(moment().subtract(1, 'months').format('YYYY-MM-DD'));
const tasks = {
    state: {
        taskPeriod: {
            "start": null,//moment().subtract(1, 'months').format('YYYY-MM-DD'),
            "end": null,//moment().format('YYYY-MM-DD')
        },
        taskStatusCurrent: 'all',
        taskStatus: { 0: 'Открытые', 1: 'Завершенные', 'all': 'Все' },
        tasks: [],
        newTask:false,
        priorityData: {min: 'Обычный', mid: 'Средний', max: 'Высокий'},
        staffData: {implementer: 'Исполнители', assist: 'Соисполнители', curator: 'Кураторы'}
    },
    getters: {
        taskPeriod(state) {return state.taskPeriod},
        taskStatusCurrent(state) {return state.taskStatusCurrent},
        taskStatus(state) {return state.taskStatus},
        tasks(state) {return state.tasks},
        newTask(state) {return state.newTask},
        priorityData(state) {return state.priorityData},
        staffData(state) {return state.staffData}
    },
    mutations: {
        TASK_STATUS(state, payload) { state.taskStatusCurrent = payload},
        TASK_PERIOD(state, payload) { state.taskPeriod = payload},
        FETCH_TASKS(state, payload) {
            state.tasks = payload
        },
        UPDATE_TASK(state, payload) {
            // let taskIndex = state.tasks.findIndex(o => o.id === payload.id);
             state.tasks = state.tasks.map(o => {
                if (o.id === payload.id) {return payload}
                return o;
            });
        },
        UPDATE_COMMENT(state, payload) {
            let newTasks = {}
            for(let t in state.tasks) if(state.tasks.hasOwnProperty(t)) {
                if(state.tasks[t].id === payload.task_id){
                    payload['isNew'] = 1;
                    state.tasks[t].comments.push(payload);
                }
                newTasks[t] =  state.tasks[t];
            }
        },
        TASK_EXPAND(state, task_id) {
            let newTasks = {}
            for(let t in state.tasks) if(state.tasks.hasOwnProperty(t)) {
                if(state.tasks[t].id === task_id){
                    state.tasks[t]._expand = true;
                }
                newTasks[t] =  state.tasks[t];
            }
        },
        newTask(state, payload) { state.newTask = payload},
    },
    actions: {
        async readTask({commit,getters},payload){
            try {
                let response = await Axios.post('/task',{
                    func: 'read',
                    values: {
                        period:getters.taskPeriod,
                        status:getters.taskStatusCurrent === 'all' ? [0,1] : [Number(getters.taskStatusCurrent)],
                    }

                });
                if(response.data){
                    await commit('FETCH_TASKS',response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },
        async createTask({commit,getters,dispatch},payload){
            try {
                let response = await Axios.post('/task',{
                    func: 'create',
                    values: payload
                });
                if(response.data){
                    await commit('FETCH_TASKS',response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },
        async updateTask({commit,getters,dispatch},payload){
            // heyui.$Loading('Loading');
            try {
                let response = await Axios.post('/task',{
                    func: 'update',
                    values: payload
                });
                if(response.data){
                    await commit('UPDATE_TASK',response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },
        // STUFF
        async updateStuff({commit,getters,dispatch},payload){
            try {
                let response = await Axios.post('/task',{
                    func: 'updateStuff',
                    values: payload
                });
                if(response.data){
                    console.log(response.data);
                    await commit('FETCH_TASKS',response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },
        async test(){
            try {
                let response = await Axios.post('/task',{
                    func: 'test',
                    user : 1,
                    value: 'test param'
                });
                console.log('test response',response.data);
            } catch (error) {
                console.log(error);
            }
        },
        // COMMENT
        async commentCreate({commit,getters,dispatch},payload){
            try {
                let response = await Axios.post('/task',{
                    func: 'commentCreate',
                    values: payload
                });
                console.log('test response',response.data);
            } catch (error) {
                console.log(error);
            }
        },

        // INIT ACTIONS
/*        async fetchHelpers({commit,getters},payload){
            try {
                let response = await Axios.post('/tasks/async',{
                    func: 'helpers'
                });
                if(await response.data) {
                  await  commit('FETCH_HELPERS', response.data)
                }
            } catch (error) {
                console.log(error);
            }
        },*/

    }
};


export default tasks;
