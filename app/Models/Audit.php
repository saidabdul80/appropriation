<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Models\Audit as ModelsAudit;

class Audit extends ModelsAudit
{
    use HasFactory;
    public function __construct() {
        $this->connection ='central_connection';
    }
}
