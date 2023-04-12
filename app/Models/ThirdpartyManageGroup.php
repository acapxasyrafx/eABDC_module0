<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ThirdpartyManageGroup extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'THIRDPARTY_MANAGE_GROUP';

    protected $primaryKey = 'THIRDPARTY_MANAGE_GROUP_ID';

    public $timestamps = false;
}