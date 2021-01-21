<?php
/**
 * Created by Rajab Billy
 * Date: 2/19/2019
 * Time: 4:51 PM
 */

class historicalDataTrends {
    protected $conn = NULL;
    public $year_quarter, $health_facility_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function HCCNewlyTotalRegisteredTrends() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 47
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare the query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Cumulative Total Registered
    public function HCCCumulativeTotalRegisteredTrends() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered] 
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 47
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Newly Patients Enrolled First Time
    public function HCCNewlyPatientsEnrolledFirstTime() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]
                         
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 49
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Cumulative Patients Enrolled First Time
    public  function HCCCumulativePatientsEnrolledFirstTime () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 49
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Newly Patients re-enrolled
    public function HCCNewlyPatientsReEnrolled() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 50
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Cumulative Patients re-enrolled
    public function HCCCumulativePatientsReEnrolled () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 50
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Newly Patients transferred in
    public function HCCNewlyPatientsTransferredIn() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 51
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //HCC Cumulative Patients transferred in
    public function HCCCumulativePatientsTransferredIn () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 51
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }


    /*******************************************************************************************/
    // HCC Cumulative registrations for the previous quarter
    public function HCCCumulativeTotalsFromPreviousQuarterAllDataElements () {
        $query = "SELECT [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT [concept].[ID] FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_id],
                        (SELECT [concept].[concept_name] FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
								AND (([obs].[data_element]) In (47,49,50,51,62,64,65,66,57,56,55,61,67))
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294
                                ORDER BY 
                    CASE [obs].[data_element]
                    WHEN 47 THEN 1
                    WHEN 49 THEN 2
                    WHEN 50 THEN 3
                    WHEN 51 THEN 4
                    WHEN 62 THEN 5
                    WHEN 64 THEN 6
                    WHEN 65 THEN 7
                    WHEN 66 THEN 8
                    WHEN 57 THEN 9
                    WHEN 56 THEN 10
                    WHEN 55 THEN 11
                    WHEN 61 THEN 12
                    WHEN 67 THEN 13
                    ELSE 14
                    END";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
    /*******************************************************************************************/

    //ART Clinic Newly Total Registered
    public function ARTClinicNewlyTotalRegisteredTrends() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 5
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Cumulative Total Registered
    public function ARTClinicCumulativeTotalRegisteredTrends () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 5
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Newly Males All Ages
    public function ARTClinicNewlyMalesAllAges() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered] 
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 14
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Cumulative Males All Ages
    public function ARTClinicCumulativeMalesAllAges() {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 14
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Newly Non-Pregnant Females All Ages
    public function ARTClinicNewlyNonPregnantFemales () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered] 
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 16
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
    //Done

    //ART Clinic Cumulative Non-Pregnant Females All Ages
    public function ARTClinicCumulativeNonPregnantFemales () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 16
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Newly Pregnant Females All Ages
    public function ARTClinicNewlyPregnantFemales () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Newly - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [newly_registered]    
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 393
                                AND [obs].[data_element] = 17
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }

    //ART Clinic Cumulative Pregnant Females All Ages
    public function ARTClinicCumulativePregnantFemales () {
        $query = "SELECT TOP 1 [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT CONCAT ('Cumulative - ', [concept].[concept_name]) FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered] 
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND [obs].[data_element] = 17
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }


    /*******************************************************************************************/
    //ART Clinic Cumulative registrations for the previous quarter
    public function ARTClinicCumulativeTotalsFromPreviousQuarterAllDataElements() {
        $query = "SELECT [code_year_quarter].[year] AS [year],
                        [code_year_quarter].[quarter] AS [quarter],
                        CONCAT ([code_year_quarter].[year], ' Q',[code_year_quarter].[quarter]) AS [year_quarter],
                        [code_hdepartment].[hfacility_id] AS [facility_id],
                        [code_hfacility].[hfacility_name] AS [facility_name],
                        (SELECT [concept].[ID] FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_id],
                        (SELECT [concept].[concept_name] FROM [dbo].[concept] WHERE [concept].[ID] = [obs].[data_element]) AS [concept_name],
                        (CAST([obs].[data_value] AS INT)) AS [cumulative_registered]
                    FROM [dbo].[obs_dimensions] 
                        LEFT JOIN [dbo].[obs] ON [obs].[obs_dimensions_ID] = [obs_dimensions].[ID] 
                        LEFT JOIN [dbo].[art_clinic_obs] ON [art_clinic_obs].[ID] = [obs_dimensions].[art_clinic_obs_id] 
                        LEFT JOIN [dbo].[code_hdepartment] ON [code_hdepartment].[ID] = [art_clinic_obs].[hdepartment_id]
                        LEFT JOIN [dbo].[code_year_quarter] ON [code_year_quarter].[ID] = [art_clinic_obs].[year_quarter_id]
                        LEFT JOIN [dbo].[code_hfacility] ON [code_hfacility].[ID] = [code_hdepartment].[hfacility_id]
		                    WHERE 
                                [sub_group] = 398
                                AND [period_report] = 394
                                AND (([obs].[data_element]) In (5,2109,2110,2111,10,2217,11,12,14,16,17,39,40,19,31,32,88,86,87,43,1675,44,45,89,91,92,93,94))                             
                                AND [art_clinic_obs].[year_quarter_id] = :year_quarter_id
                                AND [code_hdepartment].[hfacility_id] = :health_facility_id
                                AND [code_hdepartment].[hservice] = 294
                                ORDER BY
                    CASE [obs].[data_element]
                        WHEN  5     THEN 1
                        WHEN  2109  THEN 2
                        WHEN  2110  THEN 3
                        WHEN  2111  THEN 4
                        WHEN  10    THEN 5
                        WHEN  2217  THEN 6
                        WHEN  11    THEN 7
                        WHEN  12    THEN 8
                        WHEN  14    THEN 9
                        WHEN  16    THEN 10
                        WHEN  17    THEN 11
                        WHEN  39    THEN 12
                        WHEN  40    THEN 13
                        WHEN  19    THEN 14
                        WHEN  31    THEN 15
                        WHEN  32    THEN 16
						WHEN  88    THEN 17
						WHEN  86    THEN 18
						WHEN  87    THEN 19
						WHEN  43    THEN 20
						WHEN  1675  THEN 21
						WHEN  44    THEN 22
						WHEN  45    THEN 23
						WHEN  89    THEN 24
						WHEN  91    THEN 25
						WHEN  92    THEN 26
						WHEN  93    THEN 27
						WHEN  94    THEN 28
					ELSE 29
                   END";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind values
        $stmt->bindParam(':year_quarter_id', $this->year_quarter);
        $stmt->bindParam(':health_facility_id', $this->health_facility_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
    /*******************************************************************************************/
}