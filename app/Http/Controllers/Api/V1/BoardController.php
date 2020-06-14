<?php

namespace App\Http\Controllers\Api\V1;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BoardController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  Board  $board
     * @return Response
     */
    public function show(Board $board)
    {
        return $board;
    }
}