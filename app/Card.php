<?php

namespace App;

use App\Board;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;
use Watson\Validating\ValidatingTrait;

class Card extends Model implements Sortable
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
        return static::query()->where('column_id', $this->column_id);
    }

    public $timestamps = true;

    protected $fillable = ['label'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'column_id' => 'integer',
        'board_id'  => 'integer',
        'position'  => 'integer',
    ];

    use ValidatingTrait;

    protected $rules = [
        'label'     => 'required|filled',
        'board_id'  => 'required',
        'column_id' => 'required',
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
}
