<?php
session_start();

class Woofer extends Personne
{
    private String $adresse;
    private String $photo;
    public DateTime $date_debut;
    public DateTime $date_fin;

    public function __construct($adresse, $photo, $date_debut, $date_fin)
    {
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->adresse = $adresse;
        $this->photo = $photo;
    }
}
