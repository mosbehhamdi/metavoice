<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class taskResponce extends Model
{
    use HasFactory;
    protected $fillable = [
         'name','description','admin_id','worker_id','task_id','andminName','andminPhoto','taskTitle'
    ];
  



    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s', // Change your format
        'updated_at' => 'datetime:Y-m-d h:i:s',
        

    ];
    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => url('uploads/'.$value),
        );
    }
}
