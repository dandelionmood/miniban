<template>
    <div v-if="finishedLoading"
        class="card mb-2 shadow-sm">
        <div class="card-body">
            <p class="card-text">{{ card.label }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary">View</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                </div>
                <small class="text-muted">9 mins</small>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            boardId: Number,
            columnId: Number,
            cardId: Number
        },
        data () {
            return {
                card: null,
                finishedLoading: false
            }
        },
        mounted() {
            // const req_card = axios
            //     .get(route('api.v1.boards.columns.cards.show', {
            //         board: this.boardId, 
            //         column: this.columnId,
            //         card: this.cardId
            //     }))

            // axios.all([req_card]).then(axios.spread((...resp) => {
            //     this.card = resp[0].data
            //     this.finishedLoading = true
            //     // use/access the results 
            // }))
        }
    }
</script>
