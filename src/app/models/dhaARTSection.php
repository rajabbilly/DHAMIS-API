<?php
/**
 * Created by Rajab Enock Billy
 * Date: 11/3/2018
 * Time: 5:14 PM
 */

class dhaARTSection {
    //Class properties
    protected $conn = NULL;
    public $year_quarter_id, $facility_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get HCC Data by year_quarter and health facility ID
    public function getHCCRegistration () {
        //Query statement
        $query = "SELECT      
			            CONCAT (code_year_quarter.[year], 'Q', code_year_quarter.quarter) AS quarter,          
			            code_hdepartment.hfacility_id,     
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS concept_name,
			            data_value,         
			            (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code
	                  FROM obs 
			                INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
			                LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID 
			                LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id  
			                LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id 
			                LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id  
	                    WHERE concept_set.concept_ID_set = 494 AND art_clinic_obs.year_quarter_id = :year_quarter_id AND code_hdepartment.hfacility_id = :facility_id    
	                      ORDER BY product_code";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }

    /*********************************************************************************************************************************************/

    //Get ART Clinic Data for a specific facility at a specific time period
    public function getARTRegistration () {
        //Query statement
        $query = "SELECT      
			            CONCAT (code_year_quarter.[year], 'Q', code_year_quarter.quarter) AS quarter,          
			            code_hdepartment.hfacility_id,   
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS concept_name,  
			            data_value,         
			            (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code
	                  FROM obs 
			                INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
			                LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID 
			                LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id  
			                LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id 
			                LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id  
	                    WHERE concept_set.concept_ID_set = 1674 AND art_clinic_obs.year_quarter_id = :year_quarter_id AND code_hdepartment.hfacility_id = :facility_id    
	                      ORDER BY product_code";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }

    /*********************************************************************************************************************************************/

    //Get data for art Outcomes Primary & Secondary for s specific facility
    public function artOutcomesPrimarySecondary () {
        //Query statement
        $query = "SELECT      
			            CONCAT (code_year_quarter.[year], 'Q', code_year_quarter.quarter) AS quarter,          
			            code_hdepartment.hfacility_id,     
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs.data_element) AS concept_name,
			            data_value,         
			            (CAST(obs.data_element AS VARCHAR) +'-'+ LOWER(LEFT(
			            (SELECT concept.concept_name FROM concept WHERE concept.ID = obs_dimensions.period_report), 1))) AS product_code
	                  FROM obs 
			                INNER JOIN concept_set ON obs.data_element = concept_set.concept_ID_member
			                LEFT JOIN obs_dimensions ON obs_dimensions.ID = obs.obs_dimensions_ID 
			                LEFT JOIN art_clinic_obs ON art_clinic_obs.ID = obs_dimensions.art_clinic_obs_id  
			                LEFT JOIN code_hdepartment ON code_hdepartment.ID = art_clinic_obs.hdepartment_id 
			                LEFT JOIN code_year_quarter ON code_year_quarter.ID = art_clinic_obs.year_quarter_id  
	                    WHERE concept_set.concept_ID_set = 1699 AND art_clinic_obs.year_quarter_id = :year_quarter_id AND code_hdepartment.hfacility_id = :facility_id    
	                      ORDER BY product_code";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }
}