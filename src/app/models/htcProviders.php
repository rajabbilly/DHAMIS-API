<?php
/**
 * Created Rajab Enock Billy
 * Date: 9/12/2018
 * Time: 1:26 PM
 */
class htcProviders {
    //Database and Object properties
    protected $conn = NULL;
    public $year_quarter_id;
    public $facility_id;
    //Object properties for insert HTC Providers
    public $art_clinic_obs_id, $htc_person_id, $htc_prov_id, $name_first_temp, $name_last_temp,
        $log_seen, $HIV_RDT, $Syph_RDT, $DBS_EID, $DBS_VL, $pt_done, $correct_pt_result, $User,
        $UpdateUser;

    //Constructor method to initialize the db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //get all hts providers from dhamis based on health facility
    public function getHTCProvidersData() {
        //Query statement
        $query = "SELECT 
                      CASE WHEN htcPO.htc_person_id IS NULL 
		                THEN 1286
		                ELSE htcPO.htc_person_id END AS htc_person_id,
	                  CASE WHEN htcPO.name_first_temp IS NULL 
		                THEN (SELECT htc_person.name_first FROM htc_person WHERE htc_person.ID = htcPO.htc_person_id)
		                ELSE htcPO.name_first_temp END AS name_first,
	                  CASE WHEN htcPO.name_last_temp IS NULL 
		                THEN (SELECT htc_person.name_last FROM htc_person WHERE htc_person.ID = htcPO.htc_person_id)
		                ELSE htcPO.name_last_temp END AS name_last,
	                  htcPO.htc_prov_id AS htc_prov_id,
	                  codeHD.hfacility_id 
	                FROM htc_person_obs htcPO 
		                  LEFT JOIN art_clinic_obs artCO ON artCO.ID = htcPO.art_clinic_obs_id
		                  LEFT JOIN code_hdepartment codeHD ON codeHD.ID = artCO.hdepartment_id  		
	                WHERE artCO.year_quarter_id = :year_quarter_id AND codeHD.hfacility_id = :facility_id
		                      /* AND htcPO.name_first_temp IS NOT NULL 
		                      AND htcPO.name_last_temp IS NOT NULL */";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind Parameter value from the sql query
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);

        //Execute query
        $stmt->execute();
        return $stmt;
    }

    public function insertHTCProviderData () {
        //Query statement
        $query = "INSERT INTO   htc_person_obs   
	                        (art_clinic_obs_id, htc_person_id, htc_prov_id, name_first_temp, name_last_temp, log_seen, HIV_RDT, Syph_RDT, DBS_EID, DBS_VL, pt_done, correct_pt_result, [User], [TimeStamp], UpdateUser, UpdateTimeStamp)
                        VALUES 
                            (:art_clinic_obs_id, :htc_person_id, :htc_prov_id, :name_first_temp, :name_last_temp, :log_seen, :HIV_RDT, :Syph_RDT, :DBS_EID, :DBS_VL, :pt_done, :correct_pt_result, :User, GETDATE(), :UpdateUser, GETDATE())";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind Parameter values from the sql query
        $stmt->bindParam(':art_clinic_obs_id', $this->art_clinic_obs_id);
        $stmt->bindParam(':htc_person_id', $this->htc_person_id);
        $stmt->bindParam(':htc_prov_id', $this->htc_prov_id);
        $stmt->bindParam(':name_first_temp', $this->name_first_temp);
        $stmt->bindParam(':name_last_temp', $this->name_last_temp);
        $stmt->bindParam(':log_seen', $this->log_seen);
        $stmt->bindParam(':HIV_RDT', $this->HIV_RDT);
        $stmt->bindParam(':Syph_RDT', $this->Syph_RDT);
        $stmt->bindParam(':DBS_EID', $this->DBS_EID);
        $stmt->bindParam(':DBS_VL', $this->DBS_VL);
        $stmt->bindParam(':pt_done', $this->pt_done);
        $stmt->bindParam(':correct_pt_result', $this->correct_pt_result);
        $stmt->bindParam(':User', $this->User);
        $stmt->bindParam(':UpdateUser', $this->UpdateUser);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'htc_providers_obs_id' => $this->conn->lastInsertId(),
                'message' => 'HTC Provider successfully attached to the visit!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    public function getHTCProvidersObsData () {
        //Query statement
        $query = "SELECT id, art_clinic_obs_id, htc_person_id AS htc_provider_id, htc_prov_id AS counselor_id, name_first_temp AS first_name, name_last_temp AS last_name,      
	                      CASE log_seen     
                            WHEN 0 THEN       
	                          'NO'      
                            WHEN 1 THEN     
	                          'YES'     
                            ELSE        
	                          'NO VALUE'      
                          END AS log_seen, HIV_RDT, Syph_RDT, DBS_EID, DBS_VL,      
                          CASE pt_done      
                            WHEN 0 THEN     
	                          'NO'      
                            WHEN 1 THEN     
	                          'YES'     
                            ELSE      
	                          'NO VALUE'            
                          END AS pt_done, correct_pt_result, [TimeStamp]            
                        FROM htc_person_obs
                      WHERE art_clinic_obs_id = :art_clinic_obs_id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values from the sql query
        $stmt->bindParam(':art_clinic_obs_id', $this->art_clinic_obs_id);
        //Execute query statement
        $stmt->execute();
        return $stmt;
    }

    public function getAllHTCProvidersFromDHAMIS () {
        //Query statement
        $query = "SELECT ID, htc_prov_id AS counselor_id, name_first AS first_name , name_last AS last_name FROM htc_person";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute statement
        $stmt->execute();
        //Return statement
        return $stmt;
    }

}