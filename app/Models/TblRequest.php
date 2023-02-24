<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TblRequest extends Model
{
    use HasFactory;
    protected $table ='tbl_request';

    public function __construct() {
        $this->connection ='central_connection';
    }


}
