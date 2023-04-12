<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CPDWaiver extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'CPD_WAIVER_REASON';

    protected $primaryKey = 'WAIVER_REASON_ID';

    public $timestamps = false;
}