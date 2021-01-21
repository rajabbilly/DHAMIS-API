<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/24/2018
 * Time: 1:14 PM
 */

class obsDimensionsObs {
    //Class properties
    //obs dimensions
    private $conn = NULL;
    private $table_name = "obs_dimensions";
    public $ID, $art_clinic_obs_id, $period_report, $sub_group, $User;

    //obs
    private $table_name_obs = "obs";
    public $obs_dimensions_ID, $data_element, $data_value;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Create obs dimension record
    public function createObsDimensionRecord() {
        //Query statement
        $query = "INSERT INTO ".$this->table_name." (art_clinic_obs_id, period_report, sub_group, [User], [TimeStamp]) VALUES (:art_clinic_obs_id, :period_report, :sub_group, :User, GETDATE())";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":art_clinic_obs_id", $this->art_clinic_obs_id);
        $stmt->bindParam(":period_report", $this->period_report);
        $stmt->bindParam(":sub_group", $this->sub_group);
        $stmt->bindParam(":User", $this->User);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'obs_dimensions_id' => $this->conn->lastInsertId(),
                'message' => 'Obs dimensions created successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Create obs record
    public function createObsRecord() {
        //Query statement
        $query = "INSERT INTO ".$this->table_name_obs." (obs_dimensions_ID, data_element, data_value) VALUES (:obs_dimensions_ID, :data_element, :data_value)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":obs_dimensions_ID", $this->obs_dimensions_ID);
        $stmt->bindParam(":data_element", $this->data_element);
        $stmt->bindParam(":data_value", $this->data_value);
        //Execute statement
        $stmt->execute();

        if ($stmt){
            return array(
                'obs_id' => $this->conn->lastInsertId(),
                'message' => 'Obs recorded successfully',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get obs dimensions id function
    public function getObsDimensionsId() {
        //Query statement
        $query = "SELECT ID FROM [dbo].[obs_dimensions]                                                                 
                            WHERE [art_clinic_obs_id] = :art_clinic_obs_id                                                   
                            AND [period_report] = :period_report                                                                 
                            AND [sub_group] = :sub_group";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":art_clinic_obs_id", $this->art_clinic_obs_id);
        $stmt->bindParam(":period_report", $this->period_report);
        $stmt->bindParam(":sub_group", $this->sub_group);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return $stmt;
        } else {
            return false;
        }
    }
}