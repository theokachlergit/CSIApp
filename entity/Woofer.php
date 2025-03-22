<?php 
session_start();

class Woofer extends Personne
{
    public int $id;
    public String $photo;
    public DateTime $date_debut;
    public DateTime $date_fin;

    public function __construct($id, $nom, $prenom, $email, $date_debut, $date_fin) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }
}