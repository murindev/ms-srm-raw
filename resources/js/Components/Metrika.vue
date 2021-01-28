<template>
    <div>
         <Row :space="10">
             <Cell width="3">
                 <DropdownMenu @click="trigger" :datas="sites"  class-name="h-text-dropdown">
                     <span v-html="currentSite"/>
                 </DropdownMenu>
             </Cell>
             <Cell width="4">
                 <DateRangePicker v-model="dateRange" @confirm="confirm()" :format="'YYYY-MM-DD'"/>
             </Cell>
             <Cell width="4">
                 <Checkbox v-model="robots">Без роботов</Checkbox>
             </Cell>
             <Cell width="4">всего: {{baseCount}} записей</Cell>
          </Row>
         <Row :space="10">
             <Cell width="24" v-if="base">
                 <Table :datas="base" stripe>
                     <TableItem :width="70" title="#" prop="$index"></TableItem>
                     <TableItem :width="150" title="Сайт" prop="site"/>
                     <TableItem :width="200" title="IP" prop="ip" sort="auto"/>
                     <TableItem :width="300" title="url" prop="url" sort="auto"/>
                     <TableItem :width="250" title="session" prop="session" sort="auto"/>
                     <TableItem :width="150" title="Дата/Время" prop="date_human"  sort="auto"/>
                     <TableItem title="Метка"  prop="user_agent"  sort="auto"/>
                 </Table>
             </Cell>
          </Row>


    </div>

</template>

<script>

    import Axios from 'axios';

    export default {
        name: "Metrika",
        data(){
            return {
                robots:false,
                currentSite:'Выбор сайта',
                currentBase: null,
                dateRange:{},
                sites:[
                    { title: 'sesteam.ru', key: 'sesteam' },
                    { title: 'mossanexpert.com', key: 'mossanexpert' },
                ],
                mongo:'',

                base:'',
                baseCount: 0
            }
        },
        watch:{
           async robots(val){

                let output = [];
                let list = this.mongo;
                if(val){
                    for (let i in this.mongo) if(this.mongo[i].user_agent && this.mongo[i].user_agent.indexOf('compatible') <= 0) {
                        await output.push(this.mongo[i])
                    }
                } else {
                    output = this.mongo;
                }

               await this.$set(this.$data,'base',output);
               await this.$set(this.$data,'baseCount',output.length);
            }
        },
        methods: {
            trigger(code, data, event) {
                this.currentSite = data.title;
                this.currentBase = data.key;
            },
            confirm(){ // /metrika/visit/async
                this.dateRange.end = this.dateRange.end || this.dateRange.start;
                if(this.currentBase){
                    this.getRequest({
                        base: this.currentBase,
                        start: this.dateRange.start,
                        end: this.dateRange.end,
                        func: 'get',
                    });
                } else {
                    this.$Message['warn']('Выберите сайт!!!')
                }
            },
            async getRequest(obj){
                try {
                    let response = await Axios.post('/metrika/visit/async',obj);
                    let data;
                    if(await response.data){
                        // this.robots ? data = await this.withOutRobots(response.data) : data = response.data;
                       await this.$set(this.$data,'mongo',response.data);
                       await this.$set(this.$data,'base',response.data);
                       await this.$set(this.$data,'baseCount',response.data.length);
                    }
                } catch (e) {
                    console.log(e);
                }
            },
            async withOutRobots(list){
                let output = [];
                for (let i in list) if(list[i].user_agent.indexOf('compatible') <= 0) {
                    // console.log(list[i].user_agent);
                  await output.push(list[i])
                }
                console.log('output',output.length);
                return output;
            }
        }
    }
</script>

<style lang="scss">
    .h-dropdown {
        z-index: 1050!important;
    }
    .h-row  {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
</style>
