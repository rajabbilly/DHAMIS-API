<?php
/**
 * Created by Rajab Enock Billy
 * Date: 11/06/2018
 * Time: 10:05
 */
class cohortSurvivalAnalysis {
    //Class properties
    protected $conn = NULL;
    protected $table_name = "mst_supervision_subreport_survival";
    public $facility_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getCohortSurvivalAnalysisData() {
        //Select query
        $query = "SELECT  mst_sss.hdepartment_id,						      
                          mst_sss.sort_weight, 							
                          mst_sss.sub_group_name, 
                          mst_sss.reg_year_quarter_id,						
                          code_yq.[year], 							
                          code_yq.quarter, 								
                          mst_sss.reg,	
                          code_hd.hfacility_id        																												
                      FROM ". $this->table_name ." mst_sss      									
                            LEFT JOIN code_hdepartment code_hd ON code_hd.ID = mst_sss.hdepartment_id 															
                                LEFT JOIN code_year_quarter code_yq ON code_yq.ID = mst_sss.reg_year_quarter_id																		
                                    LEFT JOIN code_hfacility code_hf ON code_hf.ID = code_hd.hfacility_id														
                            WHERE code_hd.hfacility_id = :facility_id 
                        ORDER BY mst_sss.sort_weight ASC";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);
        //Bind values (facility_id)
        $stmt->bindParam(':facility_id', $this->facility_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}