<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/8/2018
 * Time: 7:08 PM
 */

class organizationUnits {
    //class properties
    protected $conn = NULL;
    protected $table_name = "mst_setOrganization_units";
    public $org_unit_ID, $org_fullname, $art_clinic_obs_ID, $year_quarter, $User;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Create synchronization history for organisation uniys
    public function createSyncHistoryOrganisationUnits() {
        $query = "INSERT INTO ".$this->table_name." (OrgUnit_ID, OrgFullname, art_clinic_obs_ID, year_quarter, [User]) 
                                VALUES (:OrgUnit_ID, :OrgFullname, :art_clinic_obs_ID, :year_quarter, :User)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":OrgUnit_ID", $this->org_unit_ID);
        $stmt->bindParam(":OrgFullname", $this->org_fullname);
        $stmt->bindParam(":art_clinic_obs_ID", $this->art_clinic_obs_ID);
        $stmt->bindParam(":year_quarter", $this->year_quarter);
        $stmt->bindParam(":User", $this->User);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'createdId' => $this->conn->lastInsertId(),
                'message' => 'organisation unit synced history recorded successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get all synchronized history for organisation units
    public function getSyncHistoryOrganisationUnits() {
        $query = "SELECT ID, OrgUnit_ID, OrgFullname, art_clinic_obs_ID, year_quarter, [User], [TimeStamp] FROM ".$this->table_name;
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //Get synced history for organization unit by Org. Units ID
    public function getOneSyncHistoryOrganisationUnits() {
        $query = "SELECT ID, OrgUnit_ID, OrgFullname, art_clinic_obs_ID, year_quarter, [User], [TimeStamp] FROM ".$this->table_name." WHERE OrgUnit_ID = :org_unit_ID";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":org_unit_ID", $this->org_unit_ID);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //Get all facilities from DHAMIS
    public function getHealthFacilitiesFromDHAMIS() {
        //Query statement
        $query = "SELECT DISTINCT
                                ID AS id,     
                                hfacility_name AS facility_name,      
                                (SELECT concept.concept_name FROM concept WHERE concept.ID = code_hfacility.district) AS district,    
                                (SELECT concept.concept_name FROM concept WHERE concept.ID = code_hfacility.hfactype) AS facility_type,   
                                Site_code AS site_code      
                              FROM code_hfacility 
                            WHERE Voided = 0";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute query statement
        $stmt->execute();
        return $stmt;
    }

}