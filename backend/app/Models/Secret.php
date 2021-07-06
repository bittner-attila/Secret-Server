<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    public $timestamps = true;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = null;

    protected $fillable = [
        'hash',
        'secretText',
        'expiresAt',
        'remainingViews',
    ];

    public function decreaseRemainingViews()
    {
        if ($this->remainingViews > 0) {
            $this->remainingViews--;
            $this->save();
        }
    }

    public static function findActiveSecretByHash($hash)
    {
        return Secret::where('hash', $hash)
        ->where('remainingViews', '>', 0)        
        ->where(function($query){
            $query->where('expiresAt', '>', date('Y-m-d h:i:sa', strtotime('now')))
            ->orWhere('expiresAt', '=', null);
        })->first();
    }
}
