<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/7/2018
 * Time: 3:08 PM
 */
class createSetVisit {
    //class properties
    protected $conn = NULL;
    protected $table_name = "art_clinic_obs";
    public $year_quarter_id, $hdepartment_id, $visit_date, $start_time, $end_time, $car_regno, $car_odo, $ap_clinic, $ap_supervisor, $User, $UpdateUser;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createSetVisitMethod() {
        $query = "INSERT  INTO  ".$this->table_name."                 
                            (year_quarter_id, hdepartment_id, visit_date, start_time, end_time, car_regno, car_odo, ap_clinic, ap_supervisor, [User], UpdateUser) 
                        VALUES (:year_quarter_id, :hdepartment_id, :visit_date, :start_time, :end_time, :car_regno, :car_odo, :ap_clinic, :ap_supervisor, :User, :UpdateUser)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind insert parameters with values
        $stmt->bindParam(":year_quarter_id", $this->year_quarter_id);
        $stmt->bindParam(":hdepartment_id", $this->hdepartment_id);
        $stmt->bindParam(":visit_date", $this->visit_date);
        $stmt->bindParam(":start_time", $this->start_time);
        $stmt->bindParam(":end_time", $this->end_time);
        $stmt->bindParam(":car_regno", $this->car_regno);
        $stmt->bindParam(":car_odo", $this->car_odo);
        $stmt->bindParam(":ap_clinic", $this->ap_clinic);
        $stmt->bindParam(":ap_supervisor", $this->ap_supervisor);
        $stmt->bindParam(":User", $this->User);
        $stmt->bindParam(":UpdateUser", $this->UpdateUser);
        //Execute statement
        $stmt->execute();

        if($stmt) {
            return array(
                'art_clinic_obs_id' => $this->conn->lastInsertId(),
                'message' => 'set visit created successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }
}