<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Prophecy\Promise\ReturnPromise;

class Product extends Model
{
    public function inStock()
    {
        return $this->stock()->where('in_stock', true)->exists();
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }

    public function track()
    {
        $this->stock->each->track();
    }
}
