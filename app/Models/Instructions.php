<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructions extends Model
{



    
    protected $fillable = [
        'admin_id','worker_id','task_id','resp_id','description'
    ];

    use HasFactory;
}
