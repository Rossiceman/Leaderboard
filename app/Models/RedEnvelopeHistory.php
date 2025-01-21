<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedEnvelopeHistory extends Model
{
    protected $fillable = ['user_id', 'attempt_count', 'grab_timestamp', 'amount'];

    // 紅包歷史紀錄與使用者之間的關聯
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
