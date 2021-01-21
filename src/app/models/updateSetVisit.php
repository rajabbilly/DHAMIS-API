<?php
/**
 * Created by Rajab Billy
 * Date: 10/14/2018
 * Time: 10:58 AM
 */

class updateSetVisit {
    //Class properties
    private $conn = NULL;
    private $table_name = "art_clinic_obs";
    public $id, $year_quarter_id, $hdepartment_id, $visit_date, $start_time, $end_time, $car_regno, $car_odo, $ap_clinic, $ap_supervisor, $User, $UpdateUser;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Update set visit method
    public function updateSetVisitMethod() {
        $query = "UPDATE ".$this->table_name." SET 
                            year_quarter_id = :year_quarter_id, 
                            hdepartment_id = :hdepartment_id, 
                            visit_date = :visit_date, 
                            start_time = :start_time, 
                            end_time = :end_time, 
                            car_regno = :car_regno, 
                            car_odo = :car_odo, 
                            ap_clinic = :ap_clinic, 
                            ap_supervisor = :ap_supervisor, 
                            [User] = :User, 
                            UpdateUser = :UpdateUser 
                        WHERE ID = :id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameters
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':hdepartment_id', $this->hdepartment_id);
        $stmt->bindParam(':visit_date', $this->visit_date);
        $stmt->bindParam(':start_time', $this->start_time);
        $stmt->bindParam(':end_time', $this->end_time);
        $stmt->bindParam(':car_regno', $this->car_regno);
        $stmt->bindParam(':car_odo', $this->car_odo);
        $stmt->bindParam(':ap_clinic', $this->ap_clinic);
        $stmt->bindParam(':ap_supervisor', $this->ap_supervisor);
        $stmt->bindParam(':User', $this->User);
        $stmt->bindParam(':UpdateUser', $this->UpdateUser);
        $stmt->bindParam(':id', $this->id);

        //Execute query statement
        if ($stmt->execute()) {
            return array(
                'createdId' => $this->conn->lastInsertId(),
                'message' => 'set visit updated successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }



}