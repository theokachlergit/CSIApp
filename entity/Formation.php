<?php
class Formation
{
    private $idFormation;
    private $libelleFormation;
    public function __construct() {}

    public function getFormation($pdo)
    {
        $statement = $pdo->prepare("SELECT * FROM formation");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
