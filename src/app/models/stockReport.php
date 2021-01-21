<?php
/**
 * Created by Rajab Enock Billy
 * Date: 01/12/2017
 * Time: 00:06
 */

class stockReport {
    //database and object properties
    protected $conn = NULL;
    protected $table_name = "mst_supervision_supply_item_sql";
    public $facility_id;
    //Parameters for the insert statement
    public $art_clinic_obs_id, $supply_item_ID, $units_instock, $units_exp6m, $expiry_date_min, $User;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //get the previous quarter stock report from DHAMIS site specific data
    public function getPreviousQuarterStockReportData() {
        $query = "SELECT                        
                      [mst_ssis].[hdepartment_id]			              				
                      ,[mst_ssis].[year_quarter_ID]								
                      ,(SELECT [concept].[concept_name_short] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[version_set]) AS [version_set]		      				
                      ,[mst_ssis].[team]		            		
                      ,[mst_ssis].[Year]		                  		
                      ,[mst_ssis].[Quarter]	
                      ,(SELECT CONCAT([year],' Q',[quarter]) FROM code_year_quarter WHERE code_year_quarter.ID = [mst_ssis].[year_quarter_id_next]) AS year_quarter_next
                      ,(SELECT CONCAT([year],' Q',[quarter]) FROM code_year_quarter WHERE code_year_quarter.ID = [mst_ssis].[year_quarter_id_prev]) AS year_quarter_prev
                      ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[district]) AS [district]
                      ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[zone]) AS [zone]
                      ,[mst_ssis].[Site_code]
                      ,[mst_ssis].[service_start]
                      ,[mst_ssis].[supply_item_ID]
                      ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[hsector]) AS [hsector]
                      ,[mst_ssis].[sched_date]		                    			
                      ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[inventory_unit]) AS [inventory_unit]			  
                      ,[mst_ssis].[chk_expiry]				          					
                      ,CASE WHEN [mst_ssis].[strength] IS NULL THEN ' ' ELSE [mst_ssis].[strength] END AS [strength]                           		
                      ,CONCAT([mst_ssis].[pack_size],' ',(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_ssis].[pack_unit])) AS [pack_size_unit]						
                      ,[mst_ssis].[pack_unit] AS [item_id]	              
                      ,[mst_ssis].[item_name]									
                      ,[mst_ssis].[sort_weight]	
                      ,CASE WHEN [mst_ssis].[units_instock] IS NULL THEN 0 ELSE [mst_ssis].[units_instock] END AS [units_instock]         				
                      ,[mst_ssis].[visit_date]						
                      ,[code_hd].[hfacility_id]			              		
                FROM ".$this->table_name." [mst_ssis] 												
				        LEFT JOIN [code_hdepartment][code_hd] ON [code_hd].[ID] = [mst_ssis].[hdepartment_id]														
		                WHERE [code_hd].[hfacility_id] = :facility_id              													
            ORDER BY   [mst_ssis].[inventory_unit], [mst_ssis].[sort_weight]";
        //prepare query statement
        $stmt = $this->conn->prepare($query);

        //bind year_quarter_id
        $stmt->bindParam(':facility_id', $this->facility_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }

    //Populate the art drug stocks table for the set visit
    public function postStockReportObservations () {
        //Query statement
        $query = "INSERT INTO art_drug_stocks 
						        (art_clinic_obs_id, supply_item_ID, units_instock, units_exp6m, expiry_date_min, [User], [TimeStamp])
		              VALUES 	(:art_clinic_obs_id, :supply_item_ID, :units_instock, :units_exp6m, :expiry_date_min, :User, GETDATE())";

        //Prepare query statememt
        $stmt = $this->conn->prepare($query);

        //Bind Parameter values from the sql query
        $stmt->bindParam(':art_clinic_obs_id', $this->art_clinic_obs_id);
        $stmt->bindParam(':supply_item_ID', $this->supply_item_ID);
        $stmt->bindParam(':units_instock', $this->units_instock);
        $stmt->bindParam(':expiry_date_min', $this->expiry_date_min);
        $stmt->bindParam(':units_exp6m', $this->units_exp6m);
        $stmt->bindParam(':User', $this->User);
        //Execute statement
        $stmt->execute();

        if ($stmt) {
            return array(
                'art_drug_stocks_id' => $this->conn->lastInsertId(),
                'message' => 'Stock report values inserted successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }
}