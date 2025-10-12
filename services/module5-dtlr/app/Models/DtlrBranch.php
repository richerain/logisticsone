<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrBranch extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dtlr_branches';
    
    protected $fillable = [
        'name',
        'location'
    ];

    public function users()
    {
        return $this->hasMany(DtlrUser::class, 'branch_id');
    }

    public function documents()
    {
        return $this->hasMany(DtlrDocument::class, 'current_branch_id');
    }
}