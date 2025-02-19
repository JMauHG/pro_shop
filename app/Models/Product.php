<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'store_id',
        'name',
        'price',
        'stock',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
