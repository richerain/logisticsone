<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dtlr_users';
    
    protected $fillable = [
        'username',
        'email',
        'role',
        'branch_id',
        'password',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function branch()
    {
        return $this->belongsTo(DtlrBranch::class, 'branch_id');
    }

    public function createdDocuments()
    {
        return $this->hasMany(DtlrDocument::class, 'created_by');
    }

    public function updatedDocuments()
    {
        return $this->hasMany(DtlrDocument::class, 'updated_by');
    }

    public function documentLogs()
    {
        return $this->hasMany(DtlrDocumentLog::class, 'performed_by');
    }

    public function documentReviews()
    {
        return $this->hasMany(DtlrDocumentReview::class, 'reviewer_id');
    }
}