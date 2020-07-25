<template>
    <div v-if="this.pending.board">
        Loading...
    </div>
    <div v-else id="kanban-container" 
        class="row justify-content-center">
        <h2 v-if="this.board">{{ this.board.label }}</h2>
        <div class="col-12">
            <div class="row kanban-columns"
                v-dragula="this.columns" drake="drakeColumns">
                <miniban-column
                    v-for="(column, index) in this.columns"
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
    import { mapState, mapActions } from "vuex";

    export default {
        components: {
            MinibanColumn
        },
        props: {
            boardId: String
        },
        methods: {
            ...mapActions([
                "getBoard",
                "getColumns",
                "getCards"
            ]),
            // // whenever columns changes, this function will run
            // watchColumns: function (newColumns) {
            //     // updating column order
            //     var newIds = _.map(newColumns, e => e.id)
            //     axios.put(route('api.v1.boards.columns.reorder', {board: this.boardId, ids: newIds}))
            // },
            // watchBoard: function (newBoard) {
            //     // updating board data
            //     console.log(newBoard);
            // }
        },
        computed: mapState({
            board: state => state.board,
            columns: state => state.columns,

            pending: state => state.pending,
            error: state => state.error
        }),
        mounted() {
            this.getBoard({ params: {boardId: this.boardId}, data: {} })
            this.getColumns({ params: {boardId: this.boardId}, data: {} })
            this.getCards({ params: {boardId: this.boardId}, data: {} })
            
            // const req_board = axios
            //     .get(route('api.v1.boards.show', {board: this.boardId}))

            // const req_columns = axios
            //     .get(route('api.v1.boards.columns.index', {board: this.boardId}))

            // axios.all([req_board, req_columns]).then(axios.spread((...resp) => {
            //     // use/access the results 
            //     this.board = resp[0].data
            //     this.columns = resp[1].data

            //     // define watcher on board and columns to listen
            //     // for changes
            //     this.$watch('board', this.watchBoard)
            //     this.$watch('columns', this.watchColumns)

            //     this.finishedLoading = true
            // }))
        }
    }
</script>
