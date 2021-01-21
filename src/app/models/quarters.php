<?php
/**
 * Created by Rajab Enock Billy
 * Date: 30/11/2017
 * Time: 22:53
 */
class Quarters {
    //Database and object properties
    protected $conn = NULL;
    protected $table_name = "code_year_quarter";
    public $previousQuarter;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Read all quarters from dha-mis
    public function getQuarters() {
        //Current year
        //$currentYear = date("Y");
        //SQL query
        $query = "SELECT 	[id]		
				,[year]		
				,[quarter]		
				,[quarter_startdate]		
				,[quarter_stopdate]		
				,[version_set] 		
			FROM  ".$this->table_name."                 
					ORDER BY quarter_startdate ASC";
        //Prepare statement statement
        $stmt = $this->conn->query($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    //Get the previous quarter ID
    public function getPreviousQuarterID() {
        $query = "SELECT TOP 1 year_quarter_id FROM art_clinic_obs ORDER BY visit_date DESC";
        //Prepare query statement
        $stmt = $this->conn->query($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    //Get the current quarter details
    public function getCurrentQuarterDetails() {
        $query = "SELECT TOP 1 ID, [year], quarter, quarter_startdate, quarter_stopdate, version_set                     
                      FROM code_year_quarter                        
                          WHERE ID > :previousQuarter                           
                  ORDER BY ID ASC";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':previousQuarter', $this->previousQuarter);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}