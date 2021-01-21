<?php
/**
 * Created by Rajab E. Billy
 * Date: 7/22/2019
 * Time: 1:45 PM
 */

class artSupervisor {
    //Database and Object properties
    protected $conn = NULL;
    public $year_quarter_id, $facility_id;

    //Constructor method
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getArtSupervisors() {
        $query = "SELECT [artSupr].[art_person_id], [artP].[NameFirst]  AS [first_name], [artP].[NameLast] AS [last_name], [codeHD].[hfacility_id] AS [facility_id] FROM [dbo].[art_supervisor] [artSupr]
                            LEFT JOIN [art_person] [artP] ON [artP].[ID] = [artSupr].[art_person_id]
                            LEFT JOIN [art_clinic_obs] [artCO] ON [artCO].[ID] = [artSupr].[art_clinic_obs_id]
                            LEFT JOIN [code_hdepartment] [codeHD] ON [codeHD].[ID] = [artCO].[hdepartment_id]
                        WHERE [artCO].[year_quarter_id] = :year_quarter_id AND [codeHD].[hfacility_id] = :facility_id";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter_id);
        $stmt->bindParam(':facility_id', $this->facility_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}