<?php
/**
 * Created by Rajab Enock Billy
 * Date: 4/11/2019
 * Time: 7:38 PM
 */
class obsSignatures {
    protected $conn = NULL;
    public $section, $ouid, $period, $name, $phoneNumber, $cadre, $signature, $createdAt;

    //Constructor function
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Public function to submit Signatures
    public function submitSignatures () {
        $query = "INSERT INTO mst_setSignature_records (section, ouid, period, name, phoneNumber, cadre, signature, createdAt)   
                        VALUES (:section, :ouid, :period, :name, :phoneNumber, :cadre, :signature, :createdAt)";
        //Prepare query statement
        $stmt = $this->conn->prepare($query);

        //Bind parameter values
        $stmt->bindParam(":section", $this->section);
        $stmt->bindParam(":ouid", $this->ouid);
        $stmt->bindParam(":period", $this->period);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":phoneNumber", $this->phoneNumber);
        $stmt->bindParam(":cadre", $this->cadre);
        $stmt->bindParam(":signature", $this->signature);
        $stmt->bindParam(":createdAt", $this->createdAt);
        //Execute statement
        $stmt->execute();

        //Response after execution
        if ($stmt) {
            return array(
                'recordId' => $this->conn->lastInsertId(),
                'message' => 'Signature recorded successfully!',
                'status' => true
            );
        } else {
            return false;
        }
    }
}