<?php
/**
 * Created by Rajab E. Billy
 * Date: 9/5/2019
 * Time: 9:32 AM
 */

class dataSetGroupings {
    //Database and object properties
    protected $conn = NULL;
    public $mst_concept_set_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Get data-set groupings
    public function getDataSetGroups() {
        $query = "SELECT ID, mst__UID FROM mst_concept_set";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Execute query
        $stmt->execute();
        return $stmt;
    }


    //Get child elements (data-sets) of the Groupings
    public function getChildElementsByGroup() {
        $query = "SELECT mst_concept_ID_set, dataset_UID, sort_weight, mst_concept_set.concept_Set_domain, mst_concept.description AS data_set
					      FROM mst_concept
								LEFT JOIN mst_concept_set ON mst_concept_set.ID = mst_concept.mst_concept_ID_set
						              WHERE mst_concept_ID_set = :mst_concept_set_id
							                ORDER BY sort_weight ASC";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);
        //Bind param values to the query statement
        $stmt->bindParam(':mst_concept_set_id', $this->mst_concept_set_id);
        //Execute statement
        $stmt->execute();
        return $stmt;
    }
}