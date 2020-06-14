<template>
    <div v-if="finishedLoading" 
        id="kanban-container" 
        class="row justify-content-center">
        <h2>{{ this.board.label }}</h2>
        <div class="col-12">
            <div class="row kanban-columns"
                v-dragula="this.columns" drake="drakeColumns">
                <miniban-column
                    v-for="(column, index) in this.columns"
                    v-bind:item="column"
                    v-bind:index="index"
                    v-bind:key="column.id"

                    v-bind:boardId="board.id"
                    v-bind:columnId="column.id"
                ></miniban-column>
            </div>
        </div>
    </div>
</template>

<script>
    import MinibanColumn from './MinibanColumn'
    
    export default {
        components: {
            MinibanColumn
        },
        props: {
            boardId: String
        },
        methods: {    
            // whenever columns changes, this function will run
            watchColumns: function (newColumns) {
                // updating column order
                var newIds = _.map(newColumns, e => e.id)
                axios.put(route('api.v1.boards.columns.reorder', {board: this.boardId, ids: newIds}))
            },
            watchBoard: function (newBoard) {
                // updating board data
                console.log(newBoard);
            }
        },
        data () {
            return {
                board: null,
                columns: null,
                finishedLoading: false
            }
        },
        mounted() {
            const req_board = axios
                .get(route('api.v1.boards.show', {board: this.boardId}))

            const req_columns = axios
                .get(route('api.v1.boards.columns.index', {board: this.boardId}))

            axios.all([req_board, req_columns]).then(axios.spread((...resp) => {
                // use/access the results 
                this.board = resp[0].data
                this.columns = resp[1].data

                // define watcher on board and columns to listen
                // for changes
                this.$watch('board', this.watchBoard)
                this.$watch('columns', this.watchColumns)

                this.finishedLoading = true
            }))
        }
    }
</script>
