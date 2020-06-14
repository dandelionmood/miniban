<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class Widget extends Model implements Sortable
{
    use SoftDeletes;

    use SortableTrait;
    public $sortable = [
        'order_column_name'  => 'position',
        'sort_when_creating' => true,
    ];
    public function buildSortQuery()
    {
        return static::query()->where('card_id', $this->card_id);
    }

    public $timestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }



}
