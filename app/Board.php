<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Watson\Validating\ValidatingTrait;

class Board extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['label', 'slug'];

    use ValidatingTrait;
    protected $rules = [
        'label' => 'required|filled',
        'slug'  => 'required|unique',
    ];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function columns()
    {
        return $this->hasMany(Column::class)
            ->orderBy('position', 'asc');
    }  

    public function cards()
    {
        return $this->hasMany(Card::class)
            ->orderBy('column_id', 'asc')
            ->orderBy('position', 'asc');
    }
}
