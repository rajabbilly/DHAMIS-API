<?php
/**
 * Created by Rajab Bnock Billy
 * Date: 2/18/2019
 * Time: 12:32 PM
 */

class  supervisorsCode {
    //Class properties
    protected $conn = NULL;
    protected $table_name = 'mst_supervisors_code';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get the latest supervisors code
    public function getLatestSupervisorsCode() {
        $query = "SELECT TOP 1 [year_quarter_id], [supervisors_key], [TimeStamp] AS [date_time] FROM ".$this->table_name." ORDER BY [TimeStamp] DESC";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
}