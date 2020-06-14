<template>
    <div v-if="finishedLoading" 
        class="kanban-column col-12 col-xl-2 col-lg-4 col-md-5">
        <h4 class="column-handle">{{ column.label }}</h4>
        <div class="kanban-cards" 
            v-dragula="this.cards" drake="drakeCards">
            <miniban-card
                v-for="(card, index) in this.cards"
                v-bind:item="card"
                v-bind:index="index"
                v-bind:key="card.id"

                v-bind:boardId="column.board_id"
                v-bind:columnId="column.id"
                v-bind:cardId="card.id"
            ></miniban-card>
        </div>
    </div>
</template>

<script>
    export default {
        components: {

        },
        props: {
            columnId: Number,
            boardId: Number
        },
        data () {
            return {
                column: null,
                cards: null,
                finishedLoading: false
            }
        },
        methods: {    
            // whenever columns changes, this function will run
            watchCards: function (newCards) {
                // updating column order
                var newIds = _.map(newCards, e => e.id)
                axios.put(route('api.v1.boards.columns.cards.reorder', {
                    board: this.boardId, column: this.columnId, ids: newIds}))
            }
        },
        mounted() {
            const req_column = axios
                .get(route('api.v1.boards.columns.show', {
                    board: this.boardId, 
                    column: this.columnId
                }))
            const req_cards = axios
                .get(route('api.v1.boards.columns.cards.index', {
                    board: this.boardId, 
                    column: this.columnId
                }))

            axios.all([req_column, req_cards]).then(axios.spread((...resp) => {
                // use/access the results 
                this.column = resp[0].data
                this.cards = resp[1].data
                
                // set watchers on cards
                this.$watch('cards', this.watchCards)

                this.finishedLoading = true;
            }))
        }
    }
</script>

<style>
    .kanban-cards {
        min-height: 2rem;
    }
</style>