<?php
/**
 * Created Rajab Enock Billy
 * Date: 10/23/2018
 * Time: 9:31 AM
 */

class reportingPeriod {
    private $conn = NULL;
    private $table_name = "mst_reporting_period";
    public $id, $period_name, $period_number, $rep_period_id, $description, $year_quarter;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getReportingPeriods() {
        $query = "SELECT ID, period_name, period_number, rep_period_id, description, year_quarter FROM ".$this->table_name." WHERE year_quarter = :year_quarter";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter', $this->year_quarter);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}