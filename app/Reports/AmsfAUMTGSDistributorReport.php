<?php
namespace App\Reports;
use App\Reports\koolsetting;
class AmsfAUMTGSDistributorReport extends AmsfAUMTGSDistributorReportPDF
{
    protected function settings()
    {
      $ks = new koolsetting;
      return $ks->ksetup();
    }
}
