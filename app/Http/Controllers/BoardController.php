<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Str;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('board.create', ['board' => new Board()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $board_data = $request->input();
        if (empty($board_data['slug'])) {
            $board_data['slug'] = Str::slug($board_data['label']);
        }

        $board = new Board;
        $board->fill($board_data);

        if ($board->save()) {
            $board->users()->attach($request->user()->id);
            return redirect()->route('board.show', $board);
        } else {
            return redirect()->route('board.create')
                ->withErrors($board->getErrors())
                ->withInput();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  Board  $board
     * @return Response
     */
    public function show(Board $board)
    {
        return view('board.show', ['board' => $board]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Board  $board
     * @return Response
     */
    public function edit(Board $board)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Board  $board
     * @return Response
     */
    public function update(Request $request, Board $board)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Board  $board
     * @return Response
     */
    public function destroy(Board $board)
    {
        //
    }
}
