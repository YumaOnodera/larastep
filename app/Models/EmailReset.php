<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class EmailReset extends Model
{
    use HasFactory, MassPrunable;

    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'new_email',
        'token',
    ];

    /**
     * 整理可能モデルクエリの取得
     *
     * @return Builder
     */
    public function prunable(): Builder
    {
        $expiration = config('const.email_resets.expire');

        return static::where('created_at', '<', now()->subMinutes($expiration));
    }
}
