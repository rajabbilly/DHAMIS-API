<?php
/**
 * Created by Rajab E Billy
 * Date: 7/22/2019
 * Time: 8:05 AM
 */
class supervisionCadres {
    //database and object properties
    protected $conn = NULL;
    protected $table_name = "mst_supervision_cadres";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSupervisionCadres() {
        $query = "SELECT ID AS cadres_id, cadre_dropdown_name, cadre_dropdown_value FROM mst_supervision_cadres";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Execute statement
        $stmt->execute();
        return $stmt;
    }
}