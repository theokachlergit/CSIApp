<?php

class Database {

    private static ?PDO $pdo = null;

    public static function getConn()
    {
        if (Database::$pdo == null) {
            try {
                Database::$pdo = new PDO("mysql:host=localhost;dbname=projet_woofer;charset=utf8", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            } catch
            (PDOException $e) {
                die("Erreur de connexion à la base de données: " . $e->getMessage());
            }
        }
        return Database::$pdo;
    }
}

