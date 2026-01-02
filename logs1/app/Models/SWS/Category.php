<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_categories';

    protected $primaryKey = 'cat_id';

    public $timestamps = false;

    protected $fillable = [
        'cat_id',
        'cat_name',
        'cat_description',
        'cat_created_at',
    ];

    protected $casts = [
        'cat_created_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'item_category_id', 'cat_id');
    }
}
