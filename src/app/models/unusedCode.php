<?php


class unusedCode {
    //Get HCC Data from all facilities
    /* public function getHCCRegistrationFromAllFacilities() {
        //Query statements
        $query = "SELECT
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = code_hfacility.district) AS district,
                            code_hfacility.hfacility_name AS site,
                            CONCAT (code_year_quarter.[year], ' Q', code_year_quarter.quarter) AS quarter,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report) AS reporting_period,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.sub_group) AS sub_groups,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS data_element,
                            data_value,
                            concept_set.concept_ID_set AS concept_set,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = concept_set.concept_ID_member) AS concept_set_member,
                            (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
						    (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code,
						    obs.data_element AS ID
                        FROM obs
							INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
                            INNER JOIN concept AS concept_1 ON concept_set.concept_ID_set = concept_1.ID
                            LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID
                            LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id
                            LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id
                            LEFT JOIN code_hfacility ON code_hfacility.ID = code_hdepartment.hfacility_id
                            LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id
                        WHERE concept_set.concept_ID_set = 494 AND art_clinic_obs.year_quarter_id = :year_quarter_id
                        ORDER BY code_hfacility.ID";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);

        //Execute statement
        $stmt->execute();
        return $stmt;
    } */

    //Get ART Clinic Data from all facilities
    /*public function getARTRegistrationFromAllFacilities () {
        //Query statement
        $query = "SELECT
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = code_hfacility.district) AS district,
                            code_hfacility.hfacility_name AS site,
                            CONCAT (code_year_quarter.[year], ' Q', code_year_quarter.quarter) AS quarter,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report) AS reporting_period,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.sub_group) AS sub_groups,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS data_element,
                            data_value,
                            concept_set.concept_ID_set AS concept_set,
                            (SELECT concept.concept_name FROM concept WHERE concept.ID = concept_set.concept_ID_member) AS concept_set_member,
                            (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
							(SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code,
							obs.data_element AS ID
                        FROM obs
							INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
                            INNER JOIN concept AS concept_1 ON concept_set.concept_ID_set = concept_1.ID
                            LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID
                            LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id
                            LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id
                            LEFT JOIN code_hfacility ON code_hfacility.ID = code_hdepartment.hfacility_id
                            LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id
                        WHERE concept_set.concept_ID_set = 1674 AND art_clinic_obs.year_quarter_id = :year_quarter_id
                        ORDER BY code_hfacility.ID";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }*/

    //Get data for art Outcomes Primary & Secondary from all facilities
    /* public function artOutcomesPrimarySecondaryFromAllFacilities () {
         //Query statement
         $query = "SELECT
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = code_hfacility.district) AS district,
                             code_hfacility.hfacility_name AS site,
                             CONCAT (code_year_quarter.[year], ' Q', code_year_quarter.quarter) AS quarter,
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report) AS reporting_period,
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.sub_group) AS sub_groups,
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS data_element,
                             data_value,
                             concept_set.concept_ID_set AS concept_set,
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = concept_set.concept_ID_member) AS concept_set_member,
                             (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
                             (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code,
                             obs.data_element AS ID
                         FROM obs
                             INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
                             INNER JOIN concept AS concept_1 ON concept_set.concept_ID_set = concept_1.ID
                             LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID
                             LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id
                             LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id
                             LEFT JOIN code_hfacility ON code_hfacility.ID = code_hdepartment.hfacility_id
                             LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id
                         WHERE concept_set.concept_ID_set = 1699 AND art_clinic_obs.year_quarter_id = :year_quarter_id
                         ORDER BY code_hfacility.ID";
         //Prepare query statement
         $stmt = $this->conn->prepare($query);
         //Bind parameter values
         $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);

         //Execute query statement
         $stmt->execute();
         return $stmt;
     }*/

    // ROUTER METHODS

    //Get HIV Care Clinic Data from all facilities
    /*$app->get('/api/hivcareclinic/get/{apiKey}/{yearQuarter}', function (Request $Request, Response $Response, array $args) {

    //Instantiate database
    $database = new Database();
    $db = $database->getConnection();

    //Instantiate hccDataAllFacilities Object
    $hccDataAllFacilitiesObject = new dhaARTSection($db);

    //Set values for hcc Data All Facilities class property
    $hccDataAllFacilitiesObject->year_quarter_id = $Request->getAttribute('yearQuarter');

    //hccDataAllFacilities Array
    $hccDataAllFacilities_Arr = array();

    $provided_apiKey = $Request->getAttribute('apiKey');
    $apiKeys = $database->apiKey;
    if (in_array($provided_apiKey, $apiKeys)) {
        // { With the postulation that we got the right keys }
        //Execute the query statement
        try {
            $stmt = $hccDataAllFacilitiesObject->getHCCRegistrationFromAllFacilities();
            //Set key, value pairs
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($hccDataAllFacilities_Arr, $row);
            }
        } catch (Exception $e) {
            $hccDataAllFacilities_Arr = [
                'Exception -> ' => $e->getMessage()
            ];
        }
    } else {
        $hccDataAllFacilities_Arr = [
            "error message" => "wrong API key"
        ];
    }

    //Display the json format
    header('Content-Type: application/json');
    echo json_encode($hccDataAllFacilities_Arr);
});*/

    //Get ART Clinic Registration from all facilities
    /*$app->get('/api/artclinic/get/{apiKey}/{yearQuarter}', function (Request $Request, Response $Response, array $args) {
        //Instantiate database
        $database = new Database();
        $db = $database->getConnection();

        //Instantiate artClinicAllFacility Object
        $artClinicAllFacilityObject = new dhaARTSection($db);

        //Set values to art Clinic All Facility class property
        $artClinicAllFacilityObject->year_quarter_id = $Request->getAttribute('yearQuarter');

        //Get ART Clinic Array
        $getARTClinic_Arr  = array();

        $provided_apiKey = $Request->getAttribute('apiKey');
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            try {
                $stmt = $artClinicAllFacilityObject->getARTRegistrationFromAllFacilities();
                //Set key, value pairs
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($getARTClinic_Arr, $row);
                }
            } catch (Exception $e) {
                $getARTClinic_Arr = [
                    'Exception -> ' => $e->getMessage()
                ];
            }
        } else {
            $getARTClinic_Arr = [
                "error message" => "wrong API key"
            ];
        }

        //Display the json format
        header('Content-Type: application/json');
        echo json_encode($getARTClinic_Arr);
    });*/

    //Get art Outcomes Primary and Secondary from all facilities
    /*$app->get('/api/artoutcomesprimarysecondary/get/{apiKey}/{yearQuarter}', function (Request $Request, Response $Response, array $args) {

        //Instantiate database
        $database = new Database();
        $db = $database->getConnection();

        //Instantiate artOutcomesAllFacilities Object
        $artOutcomesAllFacilitiesObject = new dhaARTSection($db);

        //Set values to get ART outcomes object class properties
        $artOutcomesAllFacilitiesObject->year_quarter_id = $Request->getAttribute('yearQuarter');

        //get ART Outcomes Array
        $getARTOutcomes_Arr = array();

        $provided_apiKey = $Request->getAttribute('apiKey');
        $apiKeys = $database->apiKey;
        if (in_array($provided_apiKey, $apiKeys)) {
            // { With the postulation that we got the right keys }
            //Execute the query statement
            try {
                $stmt = $artOutcomesAllFacilitiesObject->artOutcomesPrimarySecondaryFromAllFacilities();
                //Set key, value pairs
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($getARTOutcomes_Arr, $row);
                }
            } catch (Exception $e) {
                $getARTOutcomes_Arr = [
                    'Exception -> ' => $e->getMessage()
                ];
            }
        } else {
            $getARTOutcomes_Arr = [
                "error message" => "wrong API key"
            ];
        }

        //Display the json format
        header('Content-Type: application/json');
        echo json_encode($getARTOutcomes_Arr);
});*/
}