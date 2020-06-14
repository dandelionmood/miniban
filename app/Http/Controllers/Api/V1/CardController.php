<?php

namespace App\Http\Controllers\Api\V1;

use App\Board;
use App\Card;
use App\Column;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Board $board, Column $column)
    {
        return $column->cards()->get(['id', 'label']);
    }

    /**
     * Display a details of the resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Board $board, Column $column, Card $card)
    {
        return $card;
    }

    /**
     *  Reorder cards
     *  @param  Request $request [description]
     *  @param  Board $board [description]
     *  @param  Column $column [description]
     *  @return [type] [description]
     */
    public function reorder(Request $request, Board $board, Column $column) 
    {
        $cardsId = $request->input('ids');
        if( $cardsId ) {
            Card::whereIn('id', $cardsId)
                ->get()
                ->each(function($card) use($column) {
                    $card->column()->associate($column);
                    $card->save();
                });
            Card::setNewOrder($cardsId);
        }
        // no return required
    }
}