<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class SettingPassword extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    protected $table = 'SETTING_PASSWORD';

    protected $primaryKey = 'SETTING_PASSWORD_ID';

    public $timestamps = false;
}