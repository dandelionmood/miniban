<template>
    <div
        class="kanban-column col-12 col-xl-2 col-lg-4 col-md-5">
        <h4 class="column-handle">{{ column.label }}</h4>
        <div class="kanban-cards" 
            v-dragula="cards" drake="drakeCards">
            <miniban-card
                v-for="(card, index) in cards"
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
    import { mapState, mapActions, mapGetters } from "vuex";

    export default {
        props: {
            columnId: Number,
            boardId: Number
        },
        computed: {
            // ...mapState({
            //     // columns: state => state.columns,
            //     // cards: state => state.cards,
            //     pending: state => state.pending
            // }),
            column: function () {
                return this.$store.getters.getColumnById(this.columnId);
            },
            cards: function() {
                return this.$store.getters.getCardsForColumn(this.columnId);
            }
        }
    }
</script>

<style>
    .kanban-cards {
        min-height: 2rem;
    }
</style>