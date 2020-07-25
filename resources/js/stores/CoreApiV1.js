import Vapi from "vuex-rest-api";

const CoreApiV1 = new Vapi({
  baseURL: '/api/v1',
  state: {
    board: null,
    columns: [],
    cards: []
  }
})
  .get({
    action: "getBoard",
    property: "board",
    path: ({ boardId }) => `/boards/${boardId}`
  })
  .get({
    action: "getColumns",
    property: "columns",
    path: ({ boardId }) => `/boards/${boardId}/columns`
  })
  .get({
    action: "getCards",
    property: "cards",
    path: ({ boardId, columnId }) => `/boards/${boardId}/cards`
  })
  .getStore();

export default CoreApiV1;