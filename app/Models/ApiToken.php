<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OwenIt\Auditing\Contracts\Auditable;
class ApiToken extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $auditInclude = [
        'title',
        'content',
    ];

    protected $table ='apitokens';

    public function __construct() {
        $this->connection ='central_connection';
    }

}
