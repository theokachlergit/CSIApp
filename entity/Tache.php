<?php
require '../enum/enumTypeActivite.php';
class Tache
{
    private TypeActivite $id;
    public function __construct(TypeActivite $id)
    {
        $this->id = $id;
    }

    public function effectuerTache(PDO $pdo)
    {
        $woofer = $_POST['email'];
        $id = $this->id->name;
        $statement = $pdo->prepare("INSERT INTO effectue (emailWoofer, activiteTache) VALUES (?; ?)");
        $statement->bindParam(1, $woofer, PDO::PARAM_STR);
        $statement->bindParam(2, $id, PDO::PARAM_STR);
        $statement->execute();
    }
}
