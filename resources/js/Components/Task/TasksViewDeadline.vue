<template>
    <div>
        <Table :datas="tasks" stripe checkbox>
            <TableItem title="id" prop="id" width="30"></TableItem>
            <TableItem title="Задача" prop="name" sort="auto"></TableItem>
            <TableItem title="Крайний срок" prop="deadline" sort="auto"></TableItem>
            <TableItem title="Постановщик" sort="auto">
                <template v-if="users" slot-scope="{data}">
                    {{users[data.director].name}}
                </template>
            </TableItem>
            <TableItem title="Исполнители">
                <template v-if="users" slot-scope="{data}">
                    {{data.implementer.map(i => users[i].name).join(', ')}}
                </template>
            </TableItem>
            <TableItem title="Соисполнители">
                <template v-if="users" slot-scope="{data}">
                    {{data.assists.map(i => users[i].name).join(', ')}}
                </template>
            </TableItem>
            <TableItem title="Кураторы">
                <template v-if="users" slot-scope="{data}">
                    {{data.curators.map(i => users[i].name).join(', ')}}
                </template>
            </TableItem>
        </Table>
    </div>

</template>

<script>
    import {mapState, mapMutations, mapActions, mapGetters} from 'vuex';
    export default {
        name: "TasksViewTable",
        props: ['users'],
        computed:{
            ...mapGetters(['tasks']),

        },
        methods: {
        }
    }
</script>
