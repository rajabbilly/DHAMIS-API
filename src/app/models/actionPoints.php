<?php
/**
 * Created by Rajab Enock Billy
 * Date: 15/05/2018
 * Time: 15:05
 */

class actionPoints {
    //Database and Object properties
    protected $conn = NULL;
    public $year_quarter_id;
    public $facility_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get action points from the previous quarter
    public function getPreviousQuarterActionPointsData() {
        $query = "SELECT artCO.ap_clinic , artCO.ap_supervisor, codeYQ.[year], codeYQ.quarter, codeHD.hfacility_id 			
		              FROM art_clinic_obs artCO    			
				          LEFT JOIN code_hdepartment codeHD ON codeHD.ID = artCO.hdepartment_id				
				          LEFT JOIN code_year_quarter codeYQ ON codeYQ.ID = artCO.year_quarter_id    		
		              WHERE artCO.year_quarter_id = :year_quarter_id AND codeHD.hfacility_id = :facility_id";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);

        //Execute query
        $stmt->execute();
        return $stmt;
    }
}