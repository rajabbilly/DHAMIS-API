<?php
/**
 * Created Rajab Enock Billy
 * Date: 9/12/2018
 * Time: 2:02 PM
 */
header('Access-Control-Allow-Origin: *');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

//GET Supervision Main Report Data based on the assigned facilities
$app->get('/api/mainreport/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate Database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Supervision Main Report Object
    $mainReportObject = new supervisionMainReport($db);
    $ou_arr = explode(",", $args['ou']);

    //Set the mainReport_Arr
    $mainReport_Arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $mainReportObject->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if API Key matches the set
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $mainReportObject->getSupervisionMainReportData();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $mainReport_item = array(
                    'h_department_id' => $hdepartment_id,
                    'year_quarter_id' => $year_quarter_ID,
                    'next_quarter_year' => $next_quarter_year,
                    'next_quarter' => $next_quarter,
                    'prev_quarter_year' => $prev_quarter_year,
                    'prev_quarter' => $prev_quarter,
                    'current_year' => $current_year,
                    'current_quarter' => $current_quarter,
                    'team' => $team,
                    'facility_name' => $hfacility_name,
                    'site_code' => $Site_code,
                    'h_sector' => $hsector,
                    'district' => $district,
                    'version_set' => $version_set,
                    'supervision_date' => $supervision_date,
                    'next_supervision' => $next_supervision,
                    'service_start' => $service_start,
                    'zone' => $zone,
                    'sched_seq' => $sched_seq,
                    'coh_regimen5_init' => $coh_regimen5_init,
                    'coh_regimen5_subs' => $coh_regimen5_subs,
                    'viral_load_expected' => round($viral_load_expected, 0),
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
            }
            array_push($mainReport_Arr, $mainReport_item);
        } else {
            // { With the postulation that the key fails to match }
            $mainReport_Arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($mainReport_Arr);

});

//$_GET HTC Providers based on the assigned facilities
$app->get('/api/htcproviders/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate object quarters
    $quarterObject = new Quarters($db);
    $quarterStmt = $quarterObject->getPreviousQuarterID();

    //Get the previous quarter ID
    $previousQuarterId = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterId = $year_quarter_id;
    }
    //Initialize htcProviders Object
    $htcProvidersObject = new htcProviders($db);
    $htcProvidersObject->year_quarter_id = $previousQuarterId;
    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //Set htc Providers Arr
    $htcProviders_Arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $htcProvidersObject->facility_id = $ou_arr[$facility];
        //$_GET provided apiKey value
        $provided_apiKey = $args['apiKey'];
        //Verify if the API Key matches the set
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)){
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $htcProvidersObject->getHTCProvidersData();
            //Set the key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $htcProviders_item = array(
                    'htc_person_id' => $htc_person_id, /*== null ? 1286 : $htc_person_id*/
                    'firstName' => $name_first,
                    'lastName' => $name_last,
                    'htc_prov_id' => $htc_prov_id,
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
                array_push($htcProviders_Arr, $htcProviders_item);
            }
        } else {
            // { With the postulation that the key fails to match }
            $htcProviders_Arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($htcProviders_Arr);
});

//$_GET Cohort Survival Analysis based on assigned facilities
$app->get('/api/cohortsurvival/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Cohort Survival Analysis Object
    $cohortSurvivalObject = new cohortSurvivalAnalysis($db);
    $ou_arr = explode(",", $args['ou']);

    //Set the $cohortSurvival Array
    $cohortSurvival_arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $cohortSurvivalObject->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if API Key matches the set
        $apiKeys = $database->apiKey;

        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $cohortSurvivalObject->getCohortSurvivalAnalysisData();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $cohortSurvival_item = array(
                    'sort_weight' => round($sort_weight, 0),
                    'year_quarter' => $year.' Q'.$quarter,
                    'months_sub_group' => preg_replace('/[^0-9]/', '', $sub_group_name).' '.str_replace(preg_replace('/[^0-9]/', '', $sub_group_name).' month survival ','', $sub_group_name),
                    'interval_months' => preg_replace('/[^0-9]/', '', $sub_group_name),
                    'sub_group' => str_replace(preg_replace('/[^0-9]/', '', $sub_group_name).' month survival ','', $sub_group_name),
                    'total_reg' => round($reg, 0),
                    'hdepartment_id' => $hdepartment_id,
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
                array_push($cohortSurvival_arr, $cohortSurvival_item);
            }
        } else {
            // { With the postulation that the key fails to match }
            $cohortSurvival_arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($cohortSurvival_arr);
});

//$_GET Clinic Staff based on assigned facilities
$app->get('/api/clinicstaff/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate object quarters
    $quarterObject = new Quarters($db);
    $quarterStmt = $quarterObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterId = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterId = $year_quarter_id;
    }

    //Instantiate Clinic Staff Object
    $clinicStaffObject = new clinicStaff($db);
    $clinicStaffObject->year_quarter_id = $previousQuarterId;
    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //Set the clinic Staff Arr
    $clinicStaff_Arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $clinicStaffObject->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if API Key matches the set
        $apiKeys = $database->apiKey;

        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $clinicStaffObject->getContactableClinicStaffData();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $clinicStaff_item = array(
                    'art_person_id' => $art_person_id,
                    'firstName' => $NameFirst,
                    'lastName' => $NameLast,
                    'qualification' => $Qualification,
                    'phone' => $Phone,
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
                array_push($clinicStaff_Arr, $clinicStaff_item);
            }
        } else {
            // { With the postulation that the key fails to match }
            $clinicStaff_Arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($clinicStaff_Arr);
});

//$_GET Action Points based on assigned facilities
$app->get('/api/actionpoints/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate objetct quarters
    $quarterObject = new Quarters($db);
    $quarterStmt = $quarterObject->getPreviousQuarterID();

    //Get the previous quarter ID
    $previousQuarterId = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterId = $year_quarter_id;
    }

    $actionPointsObject = new actionPoints($db);
    $actionPointsObject->year_quarter_id = $previousQuarterId;
    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //Set action Points Arr
    $actionPoints_Arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $actionPointsObject->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if the API key matches the set
        $apiKeys = $database->apiKey;

        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $actionPointsObject->getPreviousQuarterActionPointsData();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $actionPoints_Item = array(
                    'ap_clinic' => $ap_clinic,
                    'ap_supervisor' => $ap_supervisor,
                    'year' => $year,
                    'quarter' => $quarter,
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
                array_push($actionPoints_Arr, $actionPoints_Item);
            }
        } else {
            // { With the postulation that the key fails to match }
            $actionPoints_Arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($actionPoints_Arr);
});

//$_GET Stock Report based on assigned facilities
$app->get('/api/stockreport/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Stock Report Object
    $stockReportObject = new stockReport($db);
    $ou_arr = explode(",", $args['ou']);

    //Set the stock Report Arr
    $stockReport_Arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $stockReportObject->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if API Key matches the set
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            $stmt = $stockReportObject->getPreviousQuarterStockReportData();
            //Set key value pairs
            $num = 1;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $stockReportItem = array(
                    'h_department_id' => $hdepartment_id,
                    'year_quarter_id' => $year_quarter_ID,
                    'version_set' => $version_set,
                    'team' => $team,
                    'year' => $Year,
                    'quarter' => $Quarter,
                    'year_quarter_next' => $year_quarter_next,
                    'year_quarter_prev' => $year_quarter_prev,
                    'district' => $district,
                    'zone' => $zone,
                    'site_code' => $Site_code,
                    'service_start' => $service_start,
                    'supply_item_id' => $supply_item_ID,
                    'hsector' => $hsector,
                    'scheduled_date' => $sched_date,
                    'inventory_unit' => strtoupper($inventory_unit),
                    'chk_expiry' => $chk_expiry,
                    'strength' => $strength,
                    'pack_size_unit' => $pack_size_unit,
                    'item_id' => $item_id,
                    'item_name' => $num.'  -  '.$item_name,
                    'sort_weight' => $sort_weight,
                    'units_instock' => $units_instock,
                    'visit_date' => $visit_date,
                    'facility_id' => $hfacility_id,
                    'date_time' => date('Y-m-d H:i:s')
                );
                $num += 1;
                array_push($stockReport_Arr, $stockReportItem);
            }
        } else {
            // { With the postulation that the key fails to match }
            $stockReport_Arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($stockReport_Arr);
});

//Get all quarters API
$app->get('/api/quarters/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instance database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Objetct Quarters
    $quarterListObject = new Quarters($db);
    //Set the quarter Arr
    $quarter_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        $stmt = $quarterListObject->getQuarters();
        //Set key value pairs
        while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($rows);
            $quarter_Item = array(
                "id" => $id,
                "year" => $year,
                "quarter" => $quarter,
                "quarter_startdate" => $quarter_startdate,
                "quarter_stopdate" => $quarter_stopdate,
                "version_set" => $version_set,
                'date_time' => date('Y-m-d H:i:s')
            );
            array_push($quarter_Arr, $quarter_Item);
        }
    } else {
        $quarter_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($quarter_Arr);
});

//Add art_clinic_obs record (Set Visit)
$app->post('/api/setvisit/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Object Set Visit
    $createSetVisitObject = new createSetVisit($db);

    //Set values to setVisit object properties
    $createSetVisitObject->year_quarter_id = $Request->getParsedBody()['year_quarter_id'];
    $createSetVisitObject->hdepartment_id = $Request->getParsedBody()['hdepartment_id'];
    $createSetVisitObject->visit_date = $Request->getParsedBody()['visit_date']." 00:00:00.000";
    $createSetVisitObject->start_time = "1899-12-30 ".$Request->getParsedBody()['start_time'].":00.000";
    $createSetVisitObject->end_time = "1899-12-30 ".$Request->getParsedBody()['end_time'].":00.000";
    $createSetVisitObject->car_regno = $Request->getParsedBody()['car_regno'];
    $createSetVisitObject->car_odo = substr($Request->getParsedBody()['car_odo'], 0, 6);
    $createSetVisitObject->ap_clinic = $Request->getParsedBody()['ap_clinic'];
    $createSetVisitObject->ap_supervisor = $Request->getParsedBody()['ap_supervisor'];
    $createSetVisitObject->User = substr($Request->getParsedBody()['user'], 0, 6);
    $createSetVisitObject->UpdateUser = substr($Request->getParsedBody()['update_user'], 0,6);

    //Message Array variable
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $createSetVisitObject->createSetVisitMethod();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message'],
                    "art_clinic_obs_id" => $stmt['art_clinic_obs_id']
                ];
            } else {
                $Message = [
                    "failure" => "failed to create set visit"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);

});

//Get set visit details by art_clinic_obs id
$app->get('/api/setvisit/get/{apiKey}/{artCObs}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get set visit object
    $getSetVisitObject = new getSetVisit($db);

    //Set values to getSetVisit object properties
    $getSetVisitObject->art_clinic_obs_id = $args['artCObs'];

    //set visit array
    $getSetVisit_arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        $stmt = $getSetVisitObject->getSetVisitMethod();
        //Set key value pairs
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $getSetVisit_item = array(
                "year_quarter_id" => $year_quarter_id,
                "hdepartment_id" => $hdepartment_id,
                "hfacility_name" => $hfacility_name,
                "visit_date" => $visit_date,
                "start_time" => substr($start_time, 11, 5),
                "end_time" => substr($end_time, 11, 5),
                "car_regno" => $car_regno,
                "car_odo" => $car_odo,
                "ap_clinic" => $ap_clinic,
                "ap_supervisor" => $ap_supervisor,
                "User" => $User,
                "UpdateUser" => $UpdateUser
            );
            array_push($getSetVisit_arr, $getSetVisit_item);
        }
    } else {
        $getSetVisit_arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getSetVisit_arr);
});

//Update set visit using the art_clinic_obs_id
$app->put('/api/setvisit/update/{apiKey}/{artCObs}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate updateSetVisitObject
    $updateSetVisitObject = new updateSetVisit($db);

    //set values to updateSetVisit object property
    $updateSetVisitObject->id = $Request->getAttribute('artCObs');
    $updateSetVisitObject->year_quarter_id = $Request->getParsedBody()['year_quarter_id'];
    $updateSetVisitObject->hdepartment_id = $Request->getParsedBody()['hdepartment_id'];
    $updateSetVisitObject->visit_date = $Request->getParsedBody()['visit_date']." 00:00:00.000";
    $updateSetVisitObject->start_time = "1899-12-30 ".$Request->getParsedBody()['start_time'].":00.000";
    $updateSetVisitObject->end_time = "1899-12-30 ".$Request->getParsedBody()['end_time'].":00.000";
    $updateSetVisitObject->car_regno = $Request->getParsedBody()['car_regno'];
    $updateSetVisitObject->car_odo = substr($Request->getParsedBody()['car_odo'], 0, 6);
    $updateSetVisitObject->ap_clinic = $Request->getParsedBody()['ap_clinic'];
    $updateSetVisitObject->ap_supervisor = $Request->getParsedBody()['ap_supervisor'];
    $updateSetVisitObject->User = substr($Request->getParsedBody()['user'], 0, 6);
    $updateSetVisitObject->UpdateUser = substr($Request->getParsedBody()['update_user'], 0,6);

    //Message Array variable
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $updateSetVisitObject->updateSetVisitMethod();
            if ($stmt['status'] == true){
                $Message = [
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to update set visit!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

/**************************************************************************************************************************************************/
//Record Synced Organisation Unit
$app->post('/api/syncedhistoryorgunits/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate recordOrgUnits Object
    $recordOrgUnitsObject = new organizationUnits($db);
    //Set values to recordOrgUnits object properties
    $recordOrgUnitsObject->org_unit_ID = $Request->getParsedBody()['org_unit_id'];
    $recordOrgUnitsObject->org_fullname = $Request->getParsedBody()['org_fullname'];
    $recordOrgUnitsObject->art_clinic_obs_ID = $Request->getParsedBody()['art_clinic_obs_id'];
    $recordOrgUnitsObject->year_quarter = $Request->getParsedBody()['year_quarter'];
    $recordOrgUnitsObject->User = substr($Request->getParsedBody()['user'], 0, 6);

    //Message Array variable
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $recordOrgUnitsObject->createSyncHistoryOrganisationUnits();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message']
                ];
            }
            else {
                $Message = [
                    "failure" => "failed to record organisation units synced history!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);

});

//Get Synced Organisation Units (All)
$app->get('/api/syncedhistoryorgunits/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get Synced History Org Units
    $getSyncedHistoryOrgUnitsObject = new organizationUnits($db);

    //get Synced History OrgUnits array
    $getSyncedHistoryOU_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getSyncedHistoryOrgUnitsObject->getSyncHistoryOrganisationUnits();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getSyncedHistoryOU_item = array(
                    "id" => $ID,
                    "org_units_id" => $OrgUnit_ID,
                    "org_fullname" => $OrgFullname,
                    "art_clinic_obs_id" => $art_clinic_obs_ID,
                    "year_quarter" => $year_quarter,
                    "user" => $User,
                    "time_stamp" => $TimeStamp
                );
                array_push($getSyncedHistoryOU_Arr, $getSyncedHistoryOU_item);
            }
        } catch (Exception $e) {
            $getSyncedHistoryOU_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }

    } else {
        $getSyncedHistoryOU_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getSyncedHistoryOU_Arr);
});

//Get Synced Organisation Unit by organisation Unit ID
$app->get('/api/syncedhistoryorgunits/get/{apiKey}/{ouID}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate getSingleSyncHisOrgUnit Object
    $getSingleSyncHisOrgUnitObject = new organizationUnits($db);

    //Set values to the getSingleSyncHisOrgUnit class properties
    $getSingleSyncHisOrgUnitObject->org_unit_ID = $Request->getAttribute('ouID');

    //get Single Synced History Organisation Unit Array variable
    $getSingleSyncHisOrgUnit_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getSingleSyncHisOrgUnitObject->getOneSyncHistoryOrganisationUnits();
            //Set key values pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getSingleSyncHisOrgUnit_item = array(
                    "id" => $ID,
                    "org_units_id" => $OrgUnit_ID,
                    "org_fullname" => $OrgFullname,
                    "art_clinic_obs_id" => $art_clinic_obs_ID,
                    "year_quarter" => $year_quarter,
                    "user" => $User,
                    "time_stamp" => $TimeStamp
                );
                array_push($getSingleSyncHisOrgUnit_Arr, $getSingleSyncHisOrgUnit_item);
            }
        } catch (Exception $e) {
            $getSingleSyncHisOrgUnit_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getSingleSyncHisOrgUnit_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getSingleSyncHisOrgUnit_Arr);
});

/**************************************************************************************************************************************************/
//Record Synced Supervision Sections
$app->post('/api/syncedhistorysection/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate recordSupervisionSection
    $recordSupervisionSectionObject = new supervisionSection($db);

    //Set values to the recordSupervisionSection object properties
    $recordSupervisionSectionObject->OrgUnit_ID = $Request->getParsedBody()['org_unit_id'];
    $recordSupervisionSectionObject->data_set_ID = $Request->getParsedBody()['data_set_id'];
    $recordSupervisionSectionObject->User = $Request->getParsedBody()['user'];

    //Message Array variable
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $recordSupervisionSectionObject->createSyncedHistoryForSupervisionSection();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to record synced supervision section!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get all Synced Supervision Sections
$app->get('/api/syncedhistorysection/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate getAllSyncedHistorySection Object
    $getAllSyncedHistorySectionObject = new supervisionSection($db);

    //getAllSyncedHistorySection Arr
    $getAllSyncedHistorySection_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getAllSyncedHistorySectionObject->getAllSyncHistorySupervisionSection();
            //Set key value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getAllSyncedHistorySection_item = array(
                    "id" => $ID,
                    "org_unit_id" => $OrgUnit_ID,
                    "data_set_id" => $data_set_ID,
                    "user" => $User,
                    "time_stamp" => $TimeStamp
                );
                array_push($getAllSyncedHistorySection_Arr, $getAllSyncedHistorySection_item);
            }
        } catch (Exception $e) {
            $getAllSyncedHistorySection_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getAllSyncedHistorySection_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getAllSyncedHistorySection_Arr);
});

//Get all synced supervision sections by Organisation Unit  ID
$app->get('/api/syncedhistorysection/get/{apiKey}/{ouID}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate getAllSyncedSection By Org Unit
    $getAllSyncedSectionByOrgUnitObject = new supervisionSection($db);

    //Set values to getAllSyncedSectionByOrgUnit Object properties
    $getAllSyncedSectionByOrgUnitObject->OrgUnit_ID = $Request->getAttribute('ouID');

    //getAllSyncedSectionByOrgUnit Arr
    $getAllSyncedSectionByOrgUnit_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getAllSyncedSectionByOrgUnitObject->getAllSyncHistorySupervisionSectionForSpecificOrgUnit();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getAllSyncedSectionByOrgUnit_item = array(
                    "id" => $ID,
                    "org_unit_id" => $OrgUnit_ID,
                    "data_set_id" => $data_set_ID,
                    "user" => $User,
                    "time_stamp" => $TimeStamp
                );
                array_push($getAllSyncedSectionByOrgUnit_Arr, $getAllSyncedSectionByOrgUnit_item);
            }
        } catch (Exception $e) {
            $getAllSyncedSectionByOrgUnit_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getAllSyncedSectionByOrgUnit_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getAllSyncedSectionByOrgUnit_Arr);
});

//Get all reporting periods
$app->get('/api/reportingperiod/get/{apiKey}/{yQuarter}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate reporting Period Object
    $reportingPeriodObject = new reportingPeriod($db);

    //Bind data value to the object property
    $reportingPeriodObject->year_quarter = $Request->getAttribute('yQuarter');

    //getAllReportingPeriods Array
    $getAllReportingPeriods_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $reportingPeriodObject->getReportingPeriods();
            //Set key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getAllReportingPeriods_item = array(
                    'id' => $ID,
                    'period_name' => $period_name,
                    'period_number' => $period_number,
                    'rep_period_id' => $rep_period_id,
                    'description' => $description,
                    'year_quarter' => $year_quarter
                );
                array_push($getAllReportingPeriods_Arr, $getAllReportingPeriods_item);
            }
        } catch (Exception $e) {
            $getAllReportingPeriods_Arr = [
                'Exception ->' => $e->getMessage()
            ];
        }
    } else {
        $getAllReportingPeriods_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getAllReportingPeriods_Arr);
});

//Populate the obs dimensions table for every cohort
$app->post('/api/cohortobsdimension/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate obsDimensionsObs Object
    $obsDimensionsObject = new obsDimensionsObs($db);

    //Retrieve the posted string
    $cohortPayload = $Request->getParsedBody()['cohort_payload'];
    $cohortPayload = explode(";", $cohortPayload);

    //Set values for obsDimensionsObs class properties
    $obsDimensionsObject->art_clinic_obs_id = $Request->getParsedBody()['art_clinic_obs_id'];
    $obsDimensionsObject->period_report = $cohortPayload[3];
    $obsDimensionsObject->sub_group = $cohortPayload[1];
    $obsDimensionsObject->User = substr($Request->getParsedBody()['user'], 0,6);

    //Message array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $obsDimensionsObject->createObsDimensionRecord();
            if ($stmt['status'] == true) {
                $Message = [
                    "obs_dimensions_id" => $stmt['obs_dimensions_id'],
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to create obs dimensions record!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Populate the obs table after retaining obs_dimensions_id
$app->post('/api/cohortobs/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate obs Object
    $obsObject = new obsDimensionsObs($db);

    //Retrieve the posted string
    $cohortPayload = $Request->getParsedBody()['cohort_payload'];
    $cohortPayload = explode(";", $cohortPayload);

    //Set values for obs class properties
    $obsObject->obs_dimensions_ID = $Request->getParsedBody()['obs_dimensions_id'];
    $obsObject->data_element = $cohortPayload[0];
    $obsObject->data_value = $cohortPayload[4];

    //Message array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $obsObject->createObsRecord();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to create obs record!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                "Exception ->" => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Populate art_staff_obs for clinic staff
$app->post('/api/clinicstaffobs/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate Database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate art Facility Clinic Staff Object
    $artFacilityClinicStaffObject = new clinicStaff($db);

    //Set values for art Facility Clinic Staff class properties
    $artFacilityClinicStaffObject->art_clinic_obs_id = $Request->getParsedBody()['art_clinic_obs_id'];
    $artFacilityClinicStaffObject->art_person_id = $Request->getParsedBody()['art_person_id'];
    $artFacilityClinicStaffObject->worked = $Request->getParsedBody()['worked'];
    $artFacilityClinicStaffObject->User = substr($Request->getParsedBody()['user'], 0,6);
    $artFacilityClinicStaffObject->UpdateUser = substr($Request->getParsedBody()['update_user'], 0,6);
    $artFacilityClinicStaffObject->passed = $Request->getParsedBody()['passed'];

    //Message Array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $artFacilityClinicStaffObject->insertClinicStaffArtObs();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to record clinic staff obs!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get Clinic Staff by art_clinic_obs_id
$app->get('/api/clinicstaffobs/get/{apiKey}/{artCO}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Clinic Staff Obs Object
    $clinicStaffObsObject = new clinicStaff($db);
    //Set values for the get Clinic Staff Obs
    $clinicStaffObsObject->art_clinic_obs_id = $Request->getAttribute('artCO');

    //getAllClinicStaffObs Array
    $getAllClinicStaffObs_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $clinicStaffObsObject->getARTContactableClinicalStaff();
            //Set key, value pairs
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getAllClinicStaffObs_item = array(
                    'art_person_id' => $art_person_id,
                    'first_name' => $NameFirst,
                    'last_name' => $NameLast,
                    'qualification' => $Qualification,
                    'phone' => $Phone,
                    'worked' => $Worked
                );
                array_push($getAllClinicStaffObs_Arr, $getAllClinicStaffObs_item);
            }
        } catch (Exception $e) {
            $getAllClinicStaffObs_Arr = [
                'Exception ->' => $e->getMessage()
            ];
        }
    } else {
        $getAllClinicStaffObs_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getAllClinicStaffObs_Arr);
});

//Get all clinic staff from art_persons
$app->get('/api/artperson/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate artPersonObject
    $artPersonObject = new clinicStaff($db);

    //get All ART Persons Array
    $getAllARTPersons_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $artPersonObject->getAllARTPersonForClinicStaff();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getAllARTPersons_item = array(
                    "id" => $ID,
                    "first_name" => $NameFirst,
                    "last_name" => $NameLast,
                    "phone" => $Phone
                );
                array_push($getAllARTPersons_Arr, $getAllARTPersons_item);
            }
        } catch (Exception $e) {
            $getAllARTPersons_Arr = [
                "Exception -> " => $e->getMessage()
            ];
        }
    } else {
        $getAllARTPersons_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getAllARTPersons_Arr);
});

//Add ART Person for Clinic Staff
$app->post('/api/artperson/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate art person object
    $artPersonObject = new clinicStaff($db);

    //Set values for art person class properties
    $artPersonObject->NameFirst = $Request->getParsedBody()['first_name'];
    $artPersonObject->NameLast = $Request->getParsedBody()['last_name'];
    $artPersonObject->Affiliation = $Request->getParsedBody()['affiliation'];
    $artPersonObject->Qualification = $Request->getParsedBody()['qualification'];
    $artPersonObject->Position = $Request->getParsedBody()['position'];
    $artPersonObject->Phone = $Request->getParsedBody()['phone'];
    $artPersonObject->User = substr($Request->getParsedBody()['user'], 0,6);
    $artPersonObject->UpdateUser = substr($Request->getParsedBody()['update_user'], 0,6);

    //Message Array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $artPersonObject->createARTPersonForClinicStaff();
            if ($stmt['status'] == true) {
                $Message = [
                    "art_person_id" => $stmt['art_person_id'],
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to create art person!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get ART Person qualifications
$app->get('/api/qualifications/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate qualification object
    $qualificationObject = new clinicStaff($db);

    //get Qualifications Array
    $getQualifications_Arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $qualificationObject->getQualifications();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getQualifications_item = array(
                    "id" => $ID,
                     "qualification" => $Qualification
                );
                array_push($getQualifications_Arr, $getQualifications_item);
            }
        } catch (Exception $e) {
            $getQualifications_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getQualifications_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getQualifications_Arr);
});

//Update facility services
$app->put('/api/facilityservice/update/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate update Service Object
    $updateServiceObject = new facilityServices($db);

    //Set value to update service property
    $updateServiceObject->hservice_paed = $Request->getParsedBody()['service_status'];
    $updateServiceObject->hfacility_id = $Request->getParsedBody()['facility_id'];
    $updateServiceObject->hservice = $Request->getParsedBody()['service_id'];

    //Message array variable
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $updateServiceObject->updateFacilityServices();
            if ($stmt['status'] == true) {
                $Message = [
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    "failure" => "failed to update health facility service!"
                ];
            }
        } catch (Exception $e) {
            $Message = [
                "Exception -> " => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get facility services
$app->get('/api/facilityservice/get/{apiKey}/{facility_id}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get Services Offered Object
    $getServicesOfferedObject = new facilityServices($db);

    //Set values to get services Offered class properties
    $getServicesOfferedObject->hfacility_id = $Request->getAttribute('facility_id');

    //get Services Offered array
    $getServicesOffered_Arr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getServicesOfferedObject->getFacilityServices();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getServicesOffered_item = array(
                    "service_name" => $concept_name,
                    "service_status" => $h_service
                );
                array_push($getServicesOffered_Arr, $getServicesOffered_item);
            }
        } catch (Exception $e) {
            $getServicesOffered_Arr = [
                "Exception -> " => $e->getMessage()
            ];
        }
    } else {
        $getServicesOffered_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getServicesOffered_Arr);
});

/************************************************************** BHT Endpoints ******************************************************************/
//List all facilities from DHAMIS
$app->get('/api/healthfacilities/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate getDHAHealthFacilities Object
    $getDHAHealthFacilitiesObject = new organizationUnits($db);

    //Set getDHAHealthFacilities Array
    $getDHAHealthFacilities_Arr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $getDHAHealthFacilitiesObject->getHealthFacilitiesFromDHAMIS();
            //Ste key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($getDHAHealthFacilities_Arr, $row);
            }
        } catch (Exception $e) {
            $getDHAHealthFacilities_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getDHAHealthFacilities_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getDHAHealthFacilities_Arr);
});

//Get HIV Care Clinic Data from a specific facility at a specific period
$app->get('/api/hivcareclinic/get/{apiKey}/{yearQuarter}/{ou}', function (Request $Request, Response $Response, array $args) {

    // /api/hivcareclinic/{yearQuarter}

    
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get Hiv Care Clinic Data Object
    $getHivCareClinicDataObject = new dhaARTSection($db);

    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //JSON Wrapper properties
    $wrapper_facilities = array();

    //$_GET provided apiKey value
    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    //Verify if the API Key matches the set
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        // JSON Sub Wrapper Properties
        $all_facilities = array();
        $year_quarter = null;
        for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
            // Set values null before the next iterations
            $getHivCareClinicValues_Arr = array();
            $facilities = array();
            $facility_code = null;

            //Assign the correct values from the endpoint
            $getHivCareClinicDataObject->facility_id = $ou_arr[$facility];
            $getHivCareClinicDataObject->year_quarter_id = $Request->getAttribute('yearQuarter');

            /* This is were the try methods was called  */
            $stmt = $getHivCareClinicDataObject->getHCCRegistration();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $year_quarter = $quarter;
                $facility_code = $hfacility_id;
                $getHivCareClinicValues_item = array(
                    'product-code' => $product_code,
                    'value' => (int)$data_value,
                    'concept-name' => $concept_name                );
                array_push($getHivCareClinicValues_Arr, $getHivCareClinicValues_item);
            } 
            $facilities = [
                'facility-code' => $facility_code,
                'values' => $getHivCareClinicValues_Arr
            ];
            array_push($all_facilities, $facilities);
        }
        $wrapper_facilities = array(
            'description' => 'ART HCC Migration for'.' '.$year_quarter,
            'reporting-period' => $year_quarter,
            'facilities' => $all_facilities
        );
    } else {
        $wrapper_facilities = [
            "error message" => "wrong API key"
        ];
    };

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($wrapper_facilities);
});

//Get ART Clinic Registration Data from a specific facility at a specific period
$app->get('/api/artclinic/get/{apiKey}/{yearQuarter}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get Hiv Care Clinic Data Object
    $getARTClinicDataObject = new dhaARTSection($db);

    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //JSON Wrapper properties
    $wrapper_facilities = array();

    //$_GET provided apiKey value
    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    //Verify if the API Key matches the set
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        // JSON Sub Wrapper Properties
        $all_facilities = array();
        $year_quarter = null;
        for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
            // Set values null before the next iterations
            $getARTClinic_Arr = array();
            $facilities = array();
            $facility_code = null;

            //Assign the correct values from the endpoint
            $getARTClinicDataObject->facility_id = $ou_arr[$facility];
            $getARTClinicDataObject->year_quarter_id = $Request->getAttribute('yearQuarter');

            /* This is were the try methods was called  */
            $stmt = $getARTClinicDataObject->getARTRegistration();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $year_quarter = $quarter;
                $facility_code = $hfacility_id;
                $getArtClinicValues_item = array(
                    'product-code' => $product_code,
                    'value' => (int)$data_value,
                    'concept-name' => $concept_name
                );
                array_push($getARTClinic_Arr, $getArtClinicValues_item);
            }
            $facilities = [
                'facility-code' => $facility_code,
                'values' => $getARTClinic_Arr
            ];
            array_push($all_facilities, $facilities);
        }
        $wrapper_facilities = array(
            'description' => 'ART Clinic Migration for'.' '.$year_quarter,
            'reporting-period' => $year_quarter,
            'facilities' => $all_facilities
        );
    } else {
        $wrapper_facilities = [
            "error message" => "wrong API key"
        ];
    };

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($wrapper_facilities);
});

//Get art Outcomes Primary & Secondary Data from a specific facility at a specific period
$app->get('/api/artoutcomesprimarysecondary/get/{apiKey}/{yearQuarter}/{ou}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate get Hiv Care Clinic Data Object
    $getARTOutcomesObject = new dhaARTSection($db);

    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //JSON Wrapper properties
    $wrapper_facilities = array();

    //$_GET provided apiKey value
    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    //Verify if the API Key matches the set
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        // JSON Sub Wrapper Properties
        $all_facilities = array();
        $year_quarter = null;
        for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
            // Set values null before the next iterations
            $getARTOutcomes_Arr = array();
            $facilities = array();
            $facility_code = null;

            //Assign the correct values from the endpoint
            $getARTOutcomesObject->facility_id = $ou_arr[$facility];
            $getARTOutcomesObject->year_quarter_id = $Request->getAttribute('yearQuarter');

            /* This is were the try methods was called  */
            $stmt = $getARTOutcomesObject->artOutcomesPrimarySecondary();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $year_quarter = $quarter;
                $facility_code = $hfacility_id;
                $getArtOutcomesValues_item = array(
                    'product-code' => $product_code,
                    'value' => (int)$data_value,
                    'concept-name' => $concept_name
                );
                array_push($getARTOutcomes_Arr, $getArtOutcomesValues_item);
            }
            $facilities = [
                'facility-code' => $facility_code,
                'values' => $getARTOutcomes_Arr
            ];
            array_push($all_facilities, $facilities);
        }
        $wrapper_facilities = array(
            'description' => 'ART Primary Secondary Outcomes Migration for'.' '.$year_quarter,
            'reporting-period' => $year_quarter,
            'facilities' => $all_facilities
        );
    } else {
        $wrapper_facilities = [
            "error message" => "wrong API key"
        ];
    };

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($wrapper_facilities);

});
/************************************************************** ./ BHT Endpoints ***************************************************************/

//Attach HTC Providers to a specific visit
$app->post('/api/htcprovidersobs/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate HTC Providers Object
    $htcProvidersObject = new htcProviders($db);

    //Set values for HTC Providers class properties
    $htcProvidersObject->art_clinic_obs_id = $Request->getParsedBody()['art_clinic_obs_id'];
    $htcProvidersObject->htc_person_id = $Request->getParsedBody()['htc_person_id'];
    $htcProvidersObject->htc_prov_id = $Request->getParsedBody()['counselor_id'];
    $full_name = $Request->getParsedBody()['full_name'];
    $full_name = explode(" ", $full_name);
    $htcProvidersObject->name_first_temp = $full_name[0];
    $htcProvidersObject->name_last_temp = $full_name[1];
    $htcProvidersObject->log_seen = $Request->getParsedBody()['log_seen'];
    $htcProvidersObject->HIV_RDT = $Request->getParsedBody()['hiv_rdt'];
    $htcProvidersObject->Syph_RDT = $Request->getParsedBody()['syph_rdt'];
    $htcProvidersObject->DBS_EID = $Request->getParsedBody()['dbs_eid'];
    $htcProvidersObject->DBS_VL = $Request->getParsedBody()['dbs_vl'];
    $htcProvidersObject->pt_done = $Request->getParsedBody()['pt_done'];
    $htcProvidersObject->correct_pt_result = $Request->getParsedBody()['correct_pt_result'];
    $htcProvidersObject->User = substr($Request->getParsedBody()['user'], 0,6);
    $htcProvidersObject->UpdateUser = substr($Request->getParsedBody()['update_user'], 0,6);

    //Message Array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $htcProvidersObject->insertHTCProviderData();
            if ($stmt['status'] == true) {
                $Message = [
                    "htc_providers_obs_id" => $stmt['htc_providers_obs_id'],
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    'failure' => 'failed to attache HTC Provider to the visit!'
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get attached HTC Providers to the visit
$app->get('/api/htcprovidersobs/get/{apiKey}/{artCO}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate HTC Providers Object
    $htcProvidersObject = new htcProviders($db);

    //Set values to htc providers object properties
    $htcProvidersObject->art_clinic_obs_id = $Request->getAttribute('artCO');

    //Get htc Providers Array
    $htcProviders_Arr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $htcProvidersObject->getHTCProvidersObsData();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $htcProviders_item = array(
                    'id' => $id,
                    'art_clinic_obs_id' => $art_clinic_obs_id,
                    'htc_provider_id' => $htc_provider_id,
                    'counselor_id' => $counselor_id,
                    'full_name' => $first_name." ".$last_name,
                    'log_seen' => $log_seen,
                    'HIV_RDT' => $HIV_RDT,
                    'Syph_RDT' => $Syph_RDT,
                    'DBS_EID' => $DBS_EID,
                    'DBS_VL' => $DBS_VL,
                    'pt_done' => $pt_done,
                    'correct_pt_result' => $correct_pt_result,
                    'time_stamp' => $TimeStamp
                );
                array_push($htcProviders_Arr, $htcProviders_item);
            }
        } catch (Exception $e) {
            $htcProviders_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $htcProviders_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($htcProviders_Arr);
});

//GET all HTC Providers from DHAMIS
$app->get('/api/dhamishtcproviders/get/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate htc Providers Object
    $htcProvidersObject = new htcProviders($db);

    //Get htc Providers Array
    $htcProviders_Arr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $htcProvidersObject->getAllHTCProvidersFromDHAMIS();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $htcProviders_item = array(
                    'id' => $ID,
                    'counselor_id' => $counselor_id,
                    'full_name' => $first_name." ".$last_name
                );
                array_push($htcProviders_Arr, $htcProviders_item);
            }
        } catch (Exception $e) {
            $htcProviders_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $htcProviders_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($htcProviders_Arr);
});

/***********************************************************************************************************************************************/

$app->post('/api/stockreport/add/{apiKey}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate stock Report Object
    $stockReportObject = new stockReport($db);

    //Set values for stock Report class properties
    $stockReportObject->art_clinic_obs_id = $Request->getParsedBody()['art_clinic_obs_id'];
    $stockReportObject->supply_item_ID = $Request->getParsedBody()['supply_item_id'];
    $stockReportObject->units_instock = $Request->getParsedBody()['total_usable'];
    $stockReportObject->expiry_date_min = date($Request->getParsedBody()['expiry_date']);
    $stockReportObject->units_exp6m = $Request->getParsedBody()['units_expiring'];
    $stockReportObject->User = substr($Request->getParsedBody()['user'], 0,6);

    //Message Array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $stockReportObject->postStockReportObservations();
            if ($stmt['status'] == true) {
                $Message = [
                    "art_drug_stocks_id" => $stmt['art_drug_stocks_id'],
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    'failure' => 'failed to add stock report data to this facility!'
                ];
            }
        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get the obs dimension id from a similar reporting period and sub group
$app->get('/api/obsdimensionsid/{apiKey}/{artCO}/{reportPD}/{subGP}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate obs dimensions Object
    $obsDimensionsObject = new obsDimensionsObs($db);

    //Set values to obs dimensions object properties
    $obsDimensionsObject->art_clinic_obs_id = $Request->getAttribute('artCO');
    $obsDimensionsObject->period_report = $Request->getAttribute('reportPD');
    $obsDimensionsObject->sub_group = $Request->getAttribute('subGP');

    //get Obs Dimensions Array
    $getObsDimensionsArr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $obsDimensionsObject->getObsDimensionsId();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $getObsDimensions_item = array(
                    'obs_dimensions_id' => $ID
                );
                array_push($getObsDimensionsArr, $getObsDimensions_item);
            }
        } catch (Exception $e) {
            $getObsDimensionsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $getObsDimensionsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getObsDimensionsArr);
});

/*******************************************************************NEW ENDPOINTS AS OF 20/02/2019***********************************************/
//Get the latest supervisors code
$app->get('/api/supervisorscode/get/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate supervisors code object
    $supervisorsCodeObject = new supervisorsCode($db);

    //Get supervisors code Array
    $getSupervisorsCodeArr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $supervisorsCodeObject->getLatestSupervisorsCode();
            //Set Key, Value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($getSupervisorsCodeArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
            }
        } catch (Exception $e) {
            $getSupervisorsCodeArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }

    } else {
        $getSupervisorsCodeArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($getSupervisorsCodeArr);
});

//Get HCC Newly Total Registered
$app->get('/api/hccnewlytotalregistered/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set HCC Total Registered Trends Array
    $hccTotalRegisteredTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCNewlyTotalRegisteredTrends();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($hccTotalRegisteredTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $hccTotalRegisteredTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $hccTotalRegisteredTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($hccTotalRegisteredTrendsArr);
});

//Get HCC Cumulative Total Registered
$app->get('/api/hcccumulativetotalregistered/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set HCC Total Registered Trends Array
    $hccTotalRegisteredTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCCumulativeTotalRegisteredTrends();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($hccTotalRegisteredTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $hccTotalRegisteredTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $hccTotalRegisteredTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($hccTotalRegisteredTrendsArr);
});

//Get HCC Newly Patients Enrolled First Time
$app->get('/api/hccnewlypatientsenrolledfirsttime/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCNewlyPatientsEnrolledFirstTime();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//Get HCC Cumulative Patients Enrolled First Time
$app->get('/api/hcccumulativepatientsenrolledfirsttime/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCCumulativePatientsEnrolledFirstTime();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//Get HCC Newly Patients re-enrolled
$app->get('/api/hccnewlypatientsreenrolled/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCNewlyPatientsReEnrolled();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//HCC Cumulative Patients re-enrolled
$app->get('/api/hcccumulativepatientsreenrolled/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCCumulativePatientsReEnrolled();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//HCC Newly Patients transferred in
$app->get('/api/hccnewlypatientstransferredin/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCNewlyPatientsTransferredIn();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//HCC Cumulative Patients transferred in
$app->get('/api/hcccumulativepatientstransferredin/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCCumulativePatientsTransferredIn();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});

//**************************************************************************************************************************************************
//HCC Cumulative Totals From Previous Quarter All Data Elements
$app->get('/api/hcccumulativetotalsfrompreviousquarterallelements/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }

    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i == 1; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->HCCCumulativeTotalsFromPreviousQuarterAllDataElements();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});
//**************************************************************************************************************************************************

//Get ART Clinic Newly Total Registered
$app->get('/api/artclinicnewlytotalregistered/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Total Registered Trends Array
    $artClinicTotalRegisteredTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicNewlyTotalRegisteredTrends();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTotalRegisteredTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTotalRegisteredTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTotalRegisteredTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTotalRegisteredTrendsArr);
});

//Get ART Clinic Cumulative Total Registered
$app->get('/api/artcliniccumulativetotalregistered/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Total Registered Trends Array
    $artClinicTotalRegisteredTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicCumulativeTotalRegisteredTrends();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTotalRegisteredTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTotalRegisteredTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTotalRegisteredTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTotalRegisteredTrendsArr);
});

//Get ART Clinic Newly Males All Ages
$app->get('/api/artclinicnewlymalesallages/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicNewlyMalesAllAges();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//Get ART Clinic Cumulative Males All Ages
$app->get('/api/artcliniccumulativemalesallages/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicCumulativeMalesAllAges();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//Get ART Clinic Newly Non-Pregnant Females All Ages
$app->get('/api/artclinicnewlynonpregnantfemales/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicNewlyNonPregnantFemales();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//Get ART Clinic Cumulative Non-Pregnant Females All Ages
$app->get('/api/artcliniccumulativenonpregnantfemales/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicCumulativeNonPregnantFemales();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//Get ART Clinic Newly Pregnant Females All Ages
$app->get('/api/artclinicnewlypregnantfemales/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicNewlyPregnantFemales();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//Get ART Clinic Cumulative Pregnant Females All Ages
$app->get('/api/artcliniccumulativepregnantfemales/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }
    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set ART Clinic Trends Array
    $artClinicTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i <= 4; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicCumulativePregnantFemales();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($artClinicTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $artClinicTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $artClinicTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artClinicTrendsArr);
});

//**************************************************************************************************************************************************
//ART Clinic Cumulative Totals From Previous Quarter All Data Elements
$app->get('/api/artcliniccumulativetotalsfrompreviousquarteralldataelements/get/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate Quarters object
    $quartersObject  = new Quarters($db);
    $quarterStmt = $quartersObject->getPreviousQuarterID();

    //Get the Previous Quarter ID
    $previousQuarterID = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)){
        extract($quarterRow);
        $previousQuarterID = $year_quarter_id;
    }

    //Instantiate historical data trends
    $historicalDataTrendsObject = new historicalDataTrends($db);

    //Create ou_arr for facility ids
    $ou_arr = explode(",", $args['ou']);

    //Set historical Data Trends Array
    $historicalDataTrendsArr = array();
    //Get provided API Key
    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;

    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
                //Assign the facility ID
                $historicalDataTrendsObject->health_facility_id = $ou_arr[$facility];
                $originalQuarterID = $previousQuarterID;
                for ($i = 1; $i == 1; $i++) {
                    $historicalDataTrendsObject->year_quarter = $previousQuarterID;
                    $stmt = $historicalDataTrendsObject->ARTClinicCumulativeTotalsFromPreviousQuarterAllDataElements();
                    //Set Key, Value pairs
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        array_push($historicalDataTrendsArr, $row + ['date_time' => date('Y-m-d H:i:s')]);
                    }
                    $previousQuarterID -= 1;
                }
                $previousQuarterID = $originalQuarterID;
            }
        } catch (Exception $e) {
            $historicalDataTrendsArr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $historicalDataTrendsArr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($historicalDataTrendsArr);
});
//**************************************************************************************************************************************************

//Post signatures from mobile application
$app->post('/api/signature/add/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate signature object
    $setSignature = new obsSignatures($db);

    //set values for signature properties class
    $setSignature->section = $Request->getParsedBody()['section'];
    $setSignature->ouid = $Request->getParsedBody()['ouid'];
    $setSignature->period = $Request->getParsedBody()['period'];
    $setSignature->name = $Request->getParsedBody()['name'];
    $setSignature->phoneNumber = $Request->getParsedBody()['phone'];
    $setSignature->cadre = $Request->getParsedBody()['cadre'];
    $setSignature->signature = $Request->getParsedBody()['signature'];
    $setSignature->createdAt = $Request->getParsedBody()['created'];

    //Message Array
    $Message = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $setSignature->submitSignatures();
            if($stmt['status'] == true) {
                $Message = [
                    "signature_id" => $stmt['recordId'],
                    "success" => $stmt['message']
                ];
            } else {
                $Message = [
                    'failure'=> 'Failed to record!'
                ];
            }

        } catch (Exception $e) {
            $Message = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $Message = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($Message);
});

//Get API endpoint for supervision cadres
$app->get('/api/cadres/get/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate cadres object
    $setCadres = new supervisionCadres($db);

    //Message Array
    $setCadres_arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $setCadres->getSupervisionCadres();
            //Set key value pairs
            while ($setCadres_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($setCadres_arr, $setCadres_row);
            }
        } catch (Exception $e) {
            $setCadres_arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $setCadres_arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($setCadres_arr);
});

//GET API endpoint for pulling art supervisors
$app->get('/api/artsupervisor/{apiKey}/{ou}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate object quarters
    $quarterObject = new Quarters($db);
    $quarterStmt = $quarterObject->getPreviousQuarterID();

    //Get the previous quarter ID
    $previousQuarterId = NULL;
    while ($quarterRow = $quarterStmt->fetch(PDO::FETCH_ASSOC)) {
        extract($quarterRow);
        $previousQuarterId = $year_quarter_id;
    }
    //Instantiate ART Supervisors Object
    $artSupervisors = new artSupervisor($db);
    $artSupervisors->year_quarter_id = $previousQuarterId;
    //$_GET ou_array
    $ou_arr = explode(",", $args['ou']);

    //set ART Supervisor Array
    $artSupervisors_arr = array();

    for ($facility = 0; $facility < sizeof($ou_arr); $facility++) {
        $artSupervisors->facility_id = $ou_arr[$facility];
        //$_GET The apiKey
        $provided_apiKey = $args['apiKey'];
        //Verify if API Key matches the set
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            try {
                $stmt = $artSupervisors->getArtSupervisors();
                //Set Key Value pairs
                while ($artSupervisors_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($artSupervisors_arr, $artSupervisors_row + ['date_time' => date('Y-m-d H:i:s')]);
                }
            } catch (Exception $e) {
                $artSupervisors_arr = [
                    'Exception -> ' => $e->getMessage()
                ];
            }
        } else {
            // { With the postulation that the key fails to match }
            $artSupervisors_arr = [
                "error message" => "wrong API key"
            ];
        }
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($artSupervisors_arr);
});

//*********************************************** Get Staff Qualification
$app->get('/api/staffqualifications/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate staff qualification object
    $qualificationObject = new staffQualification($db);

    //Message Array
    $qualification_arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $qualificationObject->getStaffQualifications();
            //Set key values pairs
            while ($qualification_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($qualification_arr, $qualification_row);
            }
        } catch (Exception $e) {
            $qualification_arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $qualification_arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($qualification_arr);
});

//*********************************************** Get dataSetGroupings
$app->get('/api/datasetgrouping/{apiKey}', function (Request $Request, Response $Response, array $args) {
    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate dataSetGrouping object
    $dataSetGroupingObject = new dataSetGroupings($db);

    //dataSetGroups Array
    $dataSetGroups_arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $group_item = array();
            $groups_stmt = $dataSetGroupingObject->getDataSetGroups();
            //Set key value pairs
            while ($groups_row = $groups_stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($group_item, $groups_row);
            }

            for ($group = 0; $group < sizeof($group_item); $group++) {
                //Group Values Array
                $groupValues_Arr = array();
                //DataSets Array
                $groupIndex = array();

                //Group ID
                $group_id = null;
                //Group Name
                $group_name = null;
                
                $dataSetGroupingObject->mst_concept_set_id = $group_item[$group]['ID'];
                //Assign the individual UIDs to a variable
                $dataSetGroup_UID = $group_item[$group]['mst__UID'];

                //Fire up the get Child Element BY Group method
                $stmt = $dataSetGroupingObject->getChildElementsByGroup();
                //Set key value pairs
                while ($row = $stmt ->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $group_id = $mst_concept_ID_set;
                    $group_name = $concept_Set_domain;
                    $dataSet_UID = $dataset_UID;
                    $dataSetName = $data_set;
                    $number = $sort_weight;

                    $groupsValues_item = array(
                        'uid' => $dataSet_UID,
                        'dataset' => $dataSetName,
                        'number' => $number
                    );
                    array_push($groupValues_Arr, $groupsValues_item);
                }

                $groupIndex = [
                    'group_id' => $dataSetGroup_UID,
                    'group' => $group_name,
                    'is_enabled' => true,
                    'datasets' => $groupValues_Arr
                ];
                array_push($dataSetGroups_arr, $groupIndex);
            }
        } catch (Exception $e) {
            $dataSetGroups_arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $dataSetGroups_arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($dataSetGroups_arr);
});

//*********************************************** Get formTemplates
// Endpoints to get all form templates
$app->get('/api/formtemplates/{apiKey}', function (Request $Request, Response $Response, array $args) {
    // Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    // Instantiate form Templates Object
    $formTemplatesObject = new formReportingTemplate($db);

    // Instantiate form Template Array for Messaging
    $formTemplate_arr = array();

    $provided_apiKey = $args['apiKey'];
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $formTemplatesObject->getFormTemplates();
            //Set key values pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($formTemplate_arr, $row  + ['date_time' => date('Y-m-d H:i:s')]);
            }
        } catch (Exception $e) {
            $formTemplate_arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $formTemplate_arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($formTemplate_arr);
});
