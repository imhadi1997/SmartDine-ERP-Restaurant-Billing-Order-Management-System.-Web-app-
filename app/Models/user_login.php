<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class user_login extends Model{

    protected $table = 'users';

    public function user_check($username, $userpassword)
    {
        $check_user = DB::table($this->table)
            ->leftJoin('erp_type', 'users.type', '=', 'erp_type.id')
            ->where('users.user', $username)
            ->where('users.password', $userpassword)
            ->where('users.activation', true)
            ->first();
    
        return $check_user;
    }
}
