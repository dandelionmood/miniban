<?php

namespace App\Observers;

use App\Board;
use App\Card;
use App\Column;

class BoardObserver
{
    /**
     * Handle the board "created" event.
     *
     * @param  \App\Board  $board
     * @return void
     */
    public function created(Board $board)
    {
        $default_columns = collect([
            __('Todo'),
            __('Ongoing'),
            __('Done'),
        ])->map(function ($label) {
            return new Column(['label' => $label]);
        });

        $columns = $board->columns()->saveMany($default_columns);

        // @TODO remove when possible to create cards freely
        $columns->map(function($col) use($board) {
            foreach(range(1, 3) as $i ) {
                $card = new Card([
                    'label' => uniqid('card-')
                ]);
                $col->cards()->save($card);
            }
        });
    }

    /**
     * Handle the board "updated" event.
     *
     * @param  \App\Board  $board
     * @return void
     */
    public function updated(Board $board)
    {
        //
    }

    /**
     * Handle the board "deleted" event.
     *
     * @param  \App\Board  $board
     * @return void
     */
    public function deleted(Board $board)
    {
        //
    }

    /**
     * Handle the board "restored" event.
     *
     * @param  \App\Board  $board
     * @return void
     */
    public function restored(Board $board)
    {
        //
    }

    /**
     * Handle the board "force deleted" event.
     *
     * @param  \App\Board  $board
     * @return void
     */
    public function forceDeleted(Board $board)
    {
        //
    }
}
