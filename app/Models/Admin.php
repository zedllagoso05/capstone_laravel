<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
      protected $table = 'admin';
    protected $fillable = [
    'user_id',
    'admin_first_name',
    'admin_last_name',
    'admin_middle_name',
    'admin_email',
    'contact_number',
];
}
