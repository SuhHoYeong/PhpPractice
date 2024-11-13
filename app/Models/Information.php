<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    use HasFactory;

    protected $table = 'asms_information';
    protected $primaryKey = 'information_id';
    public $timestamps = false;

    protected $fillable = [
        'information_title', 
        'information_kbn', 
        'keisai_ymd', 
        'enable_start_ymd', 
        'enable_end_ymd', 
        'information_naiyo', 
        'delete_flg', 
        'create_user_cd', 
        'update_user_cd'
    ];
}
