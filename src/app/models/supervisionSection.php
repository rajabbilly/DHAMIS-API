<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/11/2018
 * Time: 10:02 AM
 */

class supervisionSection {
    //Class properties
    protected $conn = NULL;
    protected $table_name = "mst_setOrganization_sections";
    public $ID, $OrgUnit_ID, $data_set_ID, $User, $TimeStamp;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Create synced history for supervision sections
    public function createSyncedHistoryForSupervisionSection() {
        $query = "INSERT INTO ".$this->table_name." (OrgUnit_ID, data_set_ID, [User])     
                                VALUES (:OrgUnit_ID, :data_set_ID, :User)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(":OrgUnit_ID", $this->OrgUnit_ID);
        $stmt->bindParam(":data_set_ID", $this->data_set_ID);
        $stmt->bindParam(":User", $this->User);
        //Execute query statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'lastInsertedId' => $this->conn->lastInsertId(),
                'message' => 'recorded synced supervision section successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get all synced history for supervision sections
    public function getAllSyncHistorySupervisionSection() {
        $query = "SELECT ID, OrgUnit_ID, data_set_ID, [User], [TimeStamp] FROM ".$this->table_name;
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //Get all synced history for a specific facility
    public function getAllSyncHistorySupervisionSectionForSpecificOrgUnit() {
        $query = "SELECT ID, OrgUnit_ID, data_set_ID, [User], [TimeStamp] FROM ".$this->table_name." WHERE OrgUnit_ID = :OrgUnit_ID";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(":OrgUnit_ID", $this->OrgUnit_ID);
        //Execute query statement
        $stmt->execute();
        return $stmt;
    }
}