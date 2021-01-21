<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/8/2018
 * Time: 12:57 PM
 */

class getSetVisit {
    //class properties
    protected $conn = NULL;
    protected $table_name = "art_clinic_obs";
    public $art_clinic_obs_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSetVisitMethod() {
        $query = "SELECT year_quarter_id, hdepartment_id, code_hfacility.hfacility_name, visit_date, start_time, end_time, car_regno, car_odo, ap_clinic, ap_supervisor, art_clinic_obs.[User], art_clinic_obs.UpdateUser FROM art_clinic_obs
                        LEFT JOIN code_hdepartment ON code_hdepartment.ID =  art_clinic_obs.hdepartment_id 
                        LEFT JOIN code_hfacility ON code_hfacility.ID = code_hdepartment.hfacility_id
                    WHERE art_clinic_obs.ID = :ID";
        //prepare the query statement
        $stmt = $this->conn->prepare($query);
        //bind values
        $stmt->bindParam(':ID', $this->art_clinic_obs_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}