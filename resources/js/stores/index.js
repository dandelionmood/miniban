import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

import CoreApiV1 from "./CoreApiV1";
const store = new Vuex.Store({
  ...CoreApiV1
})

store.getters.getColumnById = (state) => (columnId) => {
  return state.columns.find(column => column.id === columnId)
}

store.getters.getCardsForColumn = (state) => (columnId) => {
  return state.cards.find(card => card.column_id === columnId)
}

console.log(store);

export default store;