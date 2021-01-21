<?php
/**
 * Created by Rajab E. Billy
 * Date: 8/15/2019
 * Time: 10:39 AM
 */

class  staffQualification {
    //Class properties
    protected $conn = NULL;
    private $table_name = "[dbo].[concept]";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get all staff qualifications from DHAMIS
    public function getStaffQualifications() {
        //Query statement
        $query = "SELECT [concept_name] AS [qualification] FROM [dbo].[concept]  
                        WHERE (([concept].[concept_ID_parent]) In (880,881,885,1494,1495,1498,1623,1624))
                            ORDER BY [concept_ID_parent], [sort_weight];";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }
}