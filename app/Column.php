<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Watson\Validating\ValidatingTrait;

class Column extends Model implements Sortable
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $hidden = ['position'];

    use SortableTrait;
    public $sortable = [
        'order_column_name'  => 'position',
        'sort_when_creating' => true,
    ];
    public function buildSortQuery()
    {
        return static::query()->where('board_id', $this->board_id);
    }

    public $timestamps = true;

    protected $fillable = ['label'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'board_id' => 'integer',
        'position' => 'integer',
    ];

    use ValidatingTrait;

    protected $rules = [
        'label' => 'required|filled',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('position', 'asc');
    }
}
