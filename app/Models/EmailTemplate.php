<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $primaryKey = 'key';
    public    $incrementing = false;
    protected $keyType     = 'string';
    protected $fillable    = ['key', 'name', 'subject', 'body', 'variables'];
    protected $casts       = ['variables' => 'array'];
}
