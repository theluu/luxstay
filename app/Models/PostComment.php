<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    protected $fillable = [
        'post_id', 'parent_id', 'author_name', 'author_email',
        'author_website', 'body', 'is_admin_reply', 'is_approved',
    ];

    protected $casts = [
        'is_approved'    => 'boolean',
        'is_admin_reply' => 'boolean',
    ];

    public function post(): BelongsTo      { return $this->belongsTo(Post::class); }
    public function parent(): BelongsTo    { return $this->belongsTo(PostComment::class, 'parent_id'); }
    public function replies(): HasMany     { return $this->hasMany(PostComment::class, 'parent_id'); }

    public function getGravatarAttribute(): string
    {
        return md5(strtolower(trim($this->author_email)));
    }

    protected $appends = ['gravatar'];

    public function scopeApproved($query)  { return $query->where('is_approved', true); }
    public function scopePending($query)   { return $query->where('is_approved', false); }
    public function scopeTopLevel($query)  { return $query->whereNull('parent_id'); }
}
