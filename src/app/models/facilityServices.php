<?php
/**
 * Created by Rajab Enock Billy
 * Date: 10/30/2018
 * Time: 5:55 PM
 */
class facilityServices {
    //Database and Object properties
    protected $conn = NULL;
    private $table_name = "code_hdepartment";
    public $hservice_paed, $hfacility_id, $hservice;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //method to update Facility Services
    public function updateFacilityServices () {
        //Update query statement
        $query = "UPDATE [code_hdepartment] SET [hservice_paed] = :hservice_paed      
                          WHERE [hfacility_id] = :hfacility_id AND [hservice] = :hservice";
        //Prepare query statemet
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':hservice_paed', $this->hservice_paed);
        $stmt->bindParam(':hfacility_id', $this->hfacility_id);
        $stmt->bindParam(':hservice', $this->hservice);

        //Execute query statement
        if ($stmt->execute()){
            return array(
                'message' => 'Facility service updated successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }

    //Get facility services
    public function getFacilityServices () {
        //Select query
        $query = "SELECT concept.concept_name, 
                            CASE code_hdepartment.hservice_paed
                                WHEN 1 THEN 'Offered'
                                ELSE 'Not Offered'
                            END AS h_service
                      FROM code_hdepartment 
                        LEFT JOIN code_hfacility ON code_hfacility.ID = code_hdepartment.hfacility_id 
                        LEFT JOIN concept ON concept.ID = code_hdepartment.hservice
                      WHERE hfacility_id = :facility_id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind parameter values
        $stmt->bindParam(':facility_id', $this->hfacility_id);

        //Execute the statement
        $stmt->execute();
        return $stmt;
    }
}