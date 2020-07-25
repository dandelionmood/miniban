<?php

namespace App\Http\Controllers\Api\V1;

use App\Board;
use App\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Board $board)
    {
        return $board->columns()->get(['id', 'label', 'board_id']);
    }
    
    /**
     * Display a details of the resource.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Board $board, Column $column)
    {
        return $column;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Board $board)
    {
        $column = new Column();
        $column->label = __('New column');
        $column->save();
        $board->columns()->save($column);

        return $column;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @param  \App\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board, Column $column)
    {
        $column->fill($request->input());
        $column->save();
        return $column;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @param  \App\Column  $column
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board, Column $column)
    {
        $column->delete();
        return $column;
    }

    public function reorder(Request $request, Board $board) 
    {
        Column::setNewOrder($request->input('ids'));
        // no return required
    }
}
