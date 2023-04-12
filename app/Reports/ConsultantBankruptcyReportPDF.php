<?php
namespace App\Reports;
class ConsultantBankruptcyReportPDF extends \koolreport\KoolReport
{
    use \koolreport\laravel\Friendship;
    use \koolreport\clients\FontAwesome;
    use \koolreport\amazing\Theme;
    use \koolreport\export\Exportable;
    use \koolreport\excel\ExcelExportable;
    use \koolreport\inputs\Bindable;
    use \koolreport\inputs\POSTBinding;
    //use \koolreport\cloudexport\Exportable;
    function defaultParamValues()
    {
           $start_date = "";//date('Y-m-d', strtotime('first day of this month'));
           $end_date = "";//date('Y-m-d', strtotime('last day of this month'));
        return array(
            "dateRange"=>array(
                $start_date,
                $end_date
            ),
           // "SCHEMEID" => 0,
          "DISTRIBUTORIDS" => array(0),
         // "TERMINATIONTYPEID" => 0,
          //"SUBID" => 0,
        );
    }
    function bindParamsToInputs()
    {
        return array(
          "dateRange"=>"dateRange",
           "DISTRIBUTORIDS" => "DISTRIBUTORIDS",
           //"SCHEMEID",
           // "TERMINATIONTYPEID",
            //"SUBID",
        );
    }
    function setup()
    {
      $query_params = array();
      $query_params1 = array();
      $query_params2 = array();
      if($this->params["DISTRIBUTORIDS"] != array(0) )
      {
          $query_params[":DISTRIBUTOR_ID"] = $this->params["DISTRIBUTORIDS"];
      }
    
     
    //   if(isset($this->params["dateRange"][0]))
    //   {
    //       $query_params[":start"] = $this->params["dateRange"][0];
    //   }
    //   if(isset($this->params["dateRange"][1]))
    //   {
    //       $query_params[":end"] = $this->params["dateRange"][1];
    //   }
        $this->src("mysql")
        ->query("Select CONSULTANT.CONSULTANT_ID AS CID,CONSULTANT.CONSULTANT_NAME AS CNAME,CONSULTANT.CONSULTANT_NRIC AS NRIC,CONSULTANT.CONSULTANT_FIMM_NO AS FIMMNO,CONSULTANT_LICENSE.CONSULTANT_TYPE_ID AS TYPEID
        from consultant_management.CONSULTANT AS CONSULTANT
        LEFT JOIN consultant_management.CONSULTANT_LICENSE AS CONSULTANT_LICENSE
        ON CONSULTANT_LICENSE.CONSULTANT_ID = CONSULTANT.CONSULTANT_ID
        JOIN consultant_management.BANKRUPTCY AS BANKRUPTCY
        ON BANKRUPTCY.CONSULTANT_ID = CONSULTANT.CONSULTANT_ID
        WHERE 1 = 1
        ".(($this->params["DISTRIBUTORIDS"]!=array(0))?"and CONSULTANT_LICENSE.DISTRIBUTOR_ID in ( :DISTRIBUTOR_ID)":"")."
        group by  CONSULTANT_LICENSE.CONSULTANT_ID")
        ->params($query_params)
        ->pipe($this->dataStore("CONSULTANTACTIVEREPORT"));

        $this->src("mysql")
        ->query("select  CONSULTANT_LICENSE.CONSULTANT_LICENSE_ID AS CONSULTANT_LICENSE_ID,CONSULTANT_LICENSE.CONSULTANT_ID AS CONSULTANT_ID,CONSULTANT_LICENSE.DISTRIBUTOR_ID AS DISTRIBUTOR_ID,CONSULTANT_LICENSE.CONSULTANT_TYPE_ID AS CONSULTANT_TYPE_ID,DISTRIBUTOR.DIST_NAME AS DIST_NAME
        from consultant_management.CONSULTANT_LICENSE AS CONSULTANT_LICENSE
         JOIN distributor_management.DISTRIBUTOR AS DISTRIBUTOR
        ON DISTRIBUTOR.DISTRIBUTOR_ID = CONSULTANT_LICENSE.DISTRIBUTOR_ID
        ")
        ->pipe($this->dataStore("CONSULTANTLICENSE"));

        $this->src("mysql")
        ->query("select DISTRIBUTOR_TYPE.DIST_ID AS DIST_ID,DISTRIBUTORTYPE.DIST_TYPE_NAME AS DIST_TYPE_NAME
        from distributor_management.DISTRIBUTOR_TYPE AS DISTRIBUTOR_TYPE
        LEFT JOIN admin_management.DISTRIBUTOR_TYPE AS DISTRIBUTORTYPE
        ON DISTRIBUTORTYPE.DISTRIBUTOR_TYPE_ID = DISTRIBUTOR_TYPE.DIST_TYPE
        ")
        ->pipe($this->dataStore("DISTRIBUTORTYPE"));

        $this->src("mysql")
        ->query("select CONSULTANT_TYPE.CONSULTANT_TYPE_ID AS SCHEMEID,CONSULTANT_TYPE.TYPE_SCHEME AS TYPE_SCHEME
        from admin_management.CONSULTANT_TYPE AS CONSULTANT_TYPE
        ")
        ->pipe($this->dataStore("CONSULTANTSCHEME"));

        $this->src("mysql")
        ->query("select SETTING_GENERAL.SETTING_GENERAL_ID AS TERMINATIONTYPEID,SETTING_GENERAL.SET_PARAM AS TERMINATIONTYPENAME
        from admin_management.SETTING_GENERAL AS SETTING_GENERAL
        WHERE SETTING_GENERAL.SETTING_GENERAL_ID IN (296,297,298,299,300,301,302,303,304,799)
        ")
        ->pipe($this->dataStore("CONSULTANTTERMINATION"));


        $this->src("mysql")
        ->query("select DISTRIBUTOR.DISTRIBUTOR_ID AS DISTRIBUTORID,DISTRIBUTOR.DIST_NAME AS DIST_NAME
        from distributor_management.DISTRIBUTOR AS DISTRIBUTOR
        ")
        ->pipe($this->dataStore("CONSULTANTDISTRIBUTOR"));
  }
}
