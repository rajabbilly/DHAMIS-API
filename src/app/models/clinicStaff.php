<?php
/**
 * Created Rajab Enock Billy
 * Date: 08/05/2018
 * Time: 11:17
 */
class clinicStaff {
    //Database and Object Properties
    protected $conn = NULL;
    public $year_quarter_id;
    public $facility_id;

    //POST class properties
    private $table_name = "art_staff_obs";
    public $art_clinic_obs_id, $art_person_id, $worked, $User, $UpdateUser, $passed;

    //ART Person class properties
    public $NameFirst, $NameLast, $Affiliation, $Qualification, $Position, $Phone;




    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get all contactable staff from dha-mis based on health facility
    public function getContactableClinicStaffData() {
        $query = "SELECT DISTINCT artSO.art_person_id, artP.NameFirst, artP.NameLast, ccp.concept_name AS Qualification, artP.Phone, codeHD.hfacility_id   		
		              FROM art_staff_obs artSO 
					      LEFT JOIN art_clinic_obs artCO ON artCO.ID = artSO.art_clinic_obs_id 
					      LEFT JOIN art_person artP ON artP.ID = artSO.art_person_id	 
					      LEFT JOIN concept ccp ON ccp.ID = artP.Qualification 	 
					      LEFT JOIN code_hdepartment codeHD ON codeHD.ID = artCO.hdepartment_id       
		              WHERE artCO.year_quarter_id = :year_quarter_id AND codeHD.hfacility_id = :facility_id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    //Insert all values with the clinic staff ID
    public function insertClinicStaffArtObs() {
        //Query statement
        $query = "INSERT INTO ".$this->table_name." (art_clinic_obs_id, art_person_id, worked, [User], [TimeStamp], UpdateUser, UpdateTimeStamp, [passed]) 
                          VALUES (:art_clinic_obs_id, :art_person_id, :worked, :User, GETDATE(), :UpdateUser, GETDATE(), :passed)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":art_clinic_obs_id", $this->art_clinic_obs_id);
        $stmt->bindParam(":art_person_id", $this->art_person_id);
        $stmt->bindParam(":worked", $this->worked);
        $stmt->bindParam(":User", $this->User);
        $stmt->bindParam(":UpdateUser", $this->UpdateUser);
        $stmt->bindParam(":passed", $this->passed);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'art_staff_obs_id' => $this->conn->lastInsertId(),
                'message' => 'ART Clinic Staff added successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get Contactable Clinical Staff by art_clinic_obs_id
    public function getARTContactableClinicalStaff() {
        //Query statement
        $query = "SELECT [art_so].[art_person_id], [art_p].[NameFirst], [art_p].[NameLast], [ccp].[concept_name] AS [Qualification], [art_p].[Phone],   
                            CASE [art_so].[worked]
                                WHEN 0 THEN 'Absent'
                                WHEN 1 THEN 'Present'
                                ELSE 'No Value'
                            END AS Worked
			            FROM [dbo].[art_staff_obs] [art_so]
                                LEFT JOIN [art_person] [art_p] ON [art_p].[ID] = [art_so].[art_person_id]
                                LEFT JOIN [concept][ccp] ON [ccp].[ID] = [art_p].[Qualification]
                              WHERE [art_clinic_obs_id] = :art_clinic_obs_id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(":art_clinic_obs_id", $this->art_clinic_obs_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    //Get all art persons for clinic staff
    public function getAllARTPersonForClinicStaff() {
        //Query Statement
        $query = "SELECT ID, NameFirst, NameLast, Phone FROM art_person ORDER BY ID DESC";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }


    //Create ART Person for Clinic Staff
    public function createARTPersonForClinicStaff() {
        //Query statement
        $query = "INSERT INTO art_person (NameFirst, NameLast, Affiliation, Qualification, [Position], Phone, [User], [TimeStamp], UpdateUser, UpdateTimeStamp) 
                                  VALUES  (:NameFirst, :NameLast, :Affiliation, :Qualification, :Position, :Phone, :User, GETDATE(), :UpdateUser, GETDATE())";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(":NameFirst", $this->NameFirst);
        $stmt->bindParam(":NameLast", $this->NameLast);
        $stmt->bindParam(":Affiliation", $this->Affiliation);
        $stmt->bindParam(":Qualification", $this->Qualification);
        $stmt->bindParam(":Position", $this->Position);
        $stmt->bindParam(":Phone", $this->Phone);
        $stmt->bindParam(":User", $this->User);
        $stmt->bindParam(":UpdateUser", $this->UpdateUser);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'art_person_id' => $this->conn->lastInsertId(),
                'message' => 'ART Person created successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get Clinic Staff qualifications
    public function getQualifications () {
        //Query statement
        $query = "SELECT ID, concept_name AS Qualification FROM [dbo].[concept] WHERE [concept_ID_parent] = '881'";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
}