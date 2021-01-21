<?php
/**
 * Created by Rajab Enock Billy
 * Date: 9/7/2018
 * Time: 10:05
 */
class supervisionMainReport {
    //Class properties
    protected $conn = null;
    protected $table_name = "mst_supervision_mainreport";
    public $facility_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getSupervisionMainReportData() {
        //Select query
        $query = "SELECT                    
                        [mst_smr].[hdepartment_id]                  
                        ,[mst_smr].[year_quarter_ID]                    
                        ,(SELECT [code_year_quarter].[year] FROM [code_year_quarter] WHERE [code_year_quarter].[ID] = [mst_smr].[year_quarter_id_next]) AS [next_quarter_year]                  
                        ,(SELECT [code_year_quarter].[quarter] FROM [code_year_quarter] WHERE [code_year_quarter].[ID] = [mst_smr].[year_quarter_id_next]) AS [next_quarter]                    
                        ,(SELECT [code_year_quarter].[year] FROM [code_year_quarter] WHERE [code_year_quarter].[ID] = [mst_smr].[year_quarter_id_prev]) AS [prev_quarter_year]                  
                        ,(SELECT [code_year_quarter].[quarter] FROM [code_year_quarter] WHERE [code_year_quarter].[ID] = [mst_smr].[year_quarter_id_prev]) AS [prev_quarter]                    
                        ,[mst_smr].[Year] AS [current_year]                 
                        ,[mst_smr].[Quarter] AS [current_quarter]                   
                        ,[mst_smr].[team]                   
                        ,[code_hf].[hfacility_name]                     
                        ,[mst_smr].[Site_code]                  
                        ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_smr].[hsector]) AS [hsector]                   
                        ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_smr].[district]) AS [district]                       
                        ,(SELECT [concept].[concept_name_short] FROM [concept] WHERE [concept].[ID] = [mst_smr].[version_set]) AS [version_set]                        
                        ,[mst_smr].[sched_date] AS [supervision_date]                   
                        ,[mst_smr].[visit_date_next] AS [next_supervision]                  
                        ,[mst_smr].[service_start]                  
                        ,[mst_smr].[sched_seq]
                        ,[mst_smr].[implement_date]
                        ,[mst_smr].[coh_regimen5_init]
                        ,[mst_smr].[coh_regimen5_subs]
                        ,(SELECT [concept].[concept_name] FROM [concept] WHERE [concept].[ID] = [mst_smr].[zone]) AS [zone]                 
                        ,[mst_smr].[Viral_load_due] AS [viral_load_expected]
                        ,[code_hd].hfacility_id    
 
                    FROM ".$this->table_name." [mst_smr]                    
                            LEFT JOIN [code_hdepartment][code_hd] ON [code_hd].ID = [mst_smr].[hdepartment_id]                  
                            LEFT JOIN [code_hfacility][code_hf] ON [code_hf].[ID] = [code_hd].[hfacility_id]                    
                        WHERE [code_hd].[hfacility_id] = :facility_id";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);
        //Bind values (hfacility_id)
        $stmt->bindParam(':facility_id', $this->facility_id);
        //Execute query
        $stmt->execute();
        return $stmt;
    }
}