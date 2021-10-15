<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public function sentTo(){
        return $this->belongsTo(User::class, 'id');
    }

    public function scopeNotRead($query){
        return $query->where('is_read',0);
    }
}
