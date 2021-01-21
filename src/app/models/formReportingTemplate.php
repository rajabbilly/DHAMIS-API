<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 11/12/2019
 * Time: 3:30 PM
 */

class  formReportingTemplate {
    //class properties
    protected $conn = NULL;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get all data sets that require form template
    public function getFormTemplates() {
        $query = "SELECT dataset_name, dataset_UID AS dataset_uid, template_name FROM [mst_form_reporting_template] ORDER BY ID ASC";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Execute query statement
        $stmt->execute();
        return $stmt;
    }
}