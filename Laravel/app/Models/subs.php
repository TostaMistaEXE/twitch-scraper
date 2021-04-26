<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subs extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table =  'subs1';
    protected $fillable = ['gifttype','gifter','recipient','streamer','plan'];
}
