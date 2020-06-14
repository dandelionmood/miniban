/**
 *  Kanban management class.
 *  Depends on both $ and Dragula.
 */
class MinibanKanban {
  
  constructor(globalContainerSelector, options) {
    const self = this;

    self.globalContainer = null;
    self.globalContainerSelector = globalContainerSelector;
    self.boardId = null;
    self.options = options;

    self._drakeColumns = null;
    self._drakeCards = null;

    self._initialize();
  }

  /**
   *  Initialization routine.
   */
  _initialize() {
    var self = this;

    // We make sure to initialize only on relevant pages.
    self.globalContainer = $(self.globalContainerSelector);
    if( self.globalContainer.length > 0 ) {
        
        // Board ID is a pre-requisite.
        self.boardId = self.globalContainer.data('id');
        if( !self.boardId ) { 
          self._log("No board ID found.", 'error');
          return false;
        }
        
        // Let's query the API and fill in the DOM.
        $.getJSON( route('board.column.index', {board: self.boardId}) + '?callback=?', 
          function( data ) {
            
            self._initializeHmi( data );

            self.drakeCards = dragula($('.kanban-cards', self.globalContainer).toArray())
              .on('drop', function(el, target, source, sibling) {
                var parentColumn = $(el).parents('.kanban-column:eq(0)')
                var i = $('.kanban-card', parentColumn).index(el) + 1;
                self._log('Card new position is #' + i);
                var data = $(el).data('data');
                data.position = i;
                $(el).data('data', data);
                // NEED TO RECORD CHANGES
              });

            self.drakeColumns = dragula($('.kanban-columns', self.globalContainer).toArray(), {
                direction: 'horizontal',
                moves: function (el, source, handle, sibling) {
                    // column handle is it title
                    return (handle.tagName === 'H4'); 
                }
            })
            .on('drop', function(el, target, source, sibling) {
              var i = $('.kanban-column', self.globalContainer).index(el) + 1;
              self._log('Column new position is #' + i);
              var data = $(el).data('data');
              data.position = i;
              $(el).data('data', data);
              // NEED TO RECORD CHANGES
            });

            self.initialized = true;
            self._log("Initialization status " + self.initialized);

            // Probably need to change that to a more elegant solution.
            setInterval(function() {
              // Let's query the API and fill in the DOM.
              $.getJSON( 
                route('board.column.index', {board: self.boardId}) + '?callback=?', 
                function( data ) {
                  self._log("Refreshed HMI.");
                  self._refreshHmi( data );
                }
              );
            }, 5000);

          });

        return true;
    }

    return false;
  }

  _initializeHmi(data) {
    var self = this;
    
    var c12 = $('<div></div>').addClass('col-12');
    var bkc = $('<div></div>').addClass('row kanban-columns');
    self.globalContainer.append(
      c12.append(bkc)
    );

    $(data).each(function(i, columnData) {
      self._appendDomColumn(columnData);
    });
  }

  _appendDomColumn(columnData) {
    const self = this;
    var columnDom = self._createDomColumn(columnData);
    if( typeof columnData.cards !== 'undefined' && columnData.cards.length > 0 ) {
      $(columnData.cards).each(function(j, cardData) {
        self._appendDomCard(columnDom, cardData);
      });
    }
    $('.kanban-columns', self.globalContainer).append(columnDom);
    return columnDom;
  }

  _appendDomCard(columnDom, cardData) {
    const self = this;
    var cardDom = self._createDomCard(cardData);
    $('.kanban-cards', columnDom).append(cardDom);  
    return cardDom;
  }

  _refreshHmi(data) {
    var self = this;
    self._refreshHmiColumns(data);
    self._refreshHmiCards(data);
  }

  _refreshHmiColumns(data) {

    const self = this;
    var columnsNeedsReordering = false;
    var columnStillExisting = [];

    $(data).each(function(i, column) {
      
      // Has the column changed?
      // -----------------------
      var currentColumn = self._findDomColumn(column.id);

      // This is a new column
      if( currentColumn.length === 0 ) {
        self._appendDomColumn(column);
        // Reordering will be required
        columnsNeedsReordering = true;
      }

      // Eg. Position could be different -> reposition
      if( columnsNeedsReordering === false 
        && self._readData(currentColumn, 'position') !== column.position ) {
        self._log('Column #' + self._readData(currentColumn, 'position') 
          + ' is now #' + column.position);
        self._updateData(currentColumn, 'position', column.position);
        columnsNeedsReordering = true;
      }
      // Data (title) could be different -> update content
      currentColumn.text = column.label;
      columnStillExisting.push(column.id);

    });

    // handling columns deleting
    $('.kanban-column', self.globalContainer).each(function(i2, col) {
      var col = $(col);
      if( columnStillExisting.includes(self._readData(col, 'id')) === false ) {
        col.remove();
      }
    });

    // handling columns reordering
    if( columnsNeedsReordering === true ) {
      self._log('Reordering columns.');
      self._sortCollectionByPosition(
        $('.kanban-columns', self.globalContainer)
      );
    }

  }

  _refreshHmiCards(data) {
    const self = this;

    $(data).each(function(i, column) {

      var cardsStillExisting = [];
      var cardsNeedReordering = false;
      var currentColumn = self._findDomColumn(column.id);
      
      $(column.cards).each(function(j, card) {

        var currentCard = self._findDomCard(column.id, card.id);

        // This is a new column
        if( currentCard.length === 0 ) {
          self._appendDomCard(currentColumn, card);
          // Reordering will be required
          cardsNeedReordering = true;
        }

        // Eg. Position could be different -> reposition
        if( cardsNeedReordering === false 
          && self._readData(currentCard, 'position') !== card.position ) {
          self._log('Card #' + self._readData(currentCard, 'position') 
            + ' is now #' + card.position);
          self._updateData(currentCard, 'position', card.position);
          cardsNeedReordering = true;
        }
        // Data (title) could be different -> update content
        // @TODO Need to be handle ALL data
        currentCard.find('.kanban-card-label:eq(0)').text(card.label);
        cardsStillExisting.push(card.id);
      });

      var columnCards = $('.kanban-card', currentColumn);

      // handling cards deleting for the column
      columnCards.each(function(i2, car) {
        var car = $(car);
        if( cardsStillExisting.includes(self._readData(car, 'id')) === false ) {
          car.remove();
        }
      });

      // handling cards reordering
      if( cardsNeedReordering === true ) {
        self._log('Reordering cards.');
        self._sortCollectionByPosition(
          columnCards
        );
      }

    });

  }



  _findDomColumn(columnId) {
    return $('#column_' + columnId, this.globalContainer);
  }
  _createDomColumn(column) {
    const columnElement = $('<div></div>')
      .attr('id', 'column_' + column.id)
      .data('data', column)
      .addClass('kanban-column col-12 col-xl-2 col-lg-4 col-md-5')
      .append(
        $('<div></div>').addClass('clearfix')
          .append($('<button/>')
            .on('click', function() { columnElement.remove(); })
            .addClass('float-right btn btn-danger btn-mini')
            .text('✖️'))
          .append($('<h4></h4>').text(column.label))
      )
      .append($('<div></div>').addClass('kanban-cards'));
    return columnElement;
  }

  _findDomCard(columnId, cardId) {
    var domColumn = this._findDomColumn(columnId);
    return $('#card_' + cardId, domColumn);
  }
  _createDomCard(card) {
    const cardElement = $('<div></div>')
      .attr('id', 'card_' + card.id)
      .data('data', card)
      .addClass('kanban-card card mb-2 shadow-sm')
      .append(
        $('<div></div>').addClass('card-body')
          .append(
            $('<div></div>').addClass('kanban-card-label card-text').text(card.label)
          )
      )
      .append(
        $('<div></div>').addClass('card-footer')
          .append(
            $('<button />').addClass('btn btn-small btn-danger')
              .on('click', function() { cardElement.remove(); })
              .text('✖️')
          )
      );
    return cardElement;
  }


  _updateData(element, attribute, value) {
    const d = element.data('data');
    d[attribute] = value;
    element.data('data', d);
    return element;
  }

  _readData(element, attribute) {
    const self = this;
    const d = element.data('data');
    if( typeof d === 'undefined' ) {
      debugger;
    }
    return d[attribute];
  }

  _sortCollectionByPosition(container) {
    const self = this;
    var children  = container.children();
    children.sort(function(a, b){
      return +self._readData($(a), 'position') - +self._readData($(b), 'position');
    });
    children.detach().appendTo(container);
  }

  _log(msg, level) {
    if( this.options.debugMode === true ) {
        level = ( level ) ? level : 'log';
        console[level]('[KanbanMiniban] ' + msg);
    }
  }

}

new MinibanKanban('#kanban-container', {
  debugMode: true
});