<?php enum enumTypeProduit
{
    case  Fromage;
    case Oeuf;
    case Lait;
    case Legume;
    case Confiture;


    private function StringOfProduit() {
        switch ($this) {
            case $this->Fromage :
                return "Fromage";
                break;
            case $this->Oeuf :
                return "Oeuf";
                break;
            case $this->Lait :
                return "Lait";
                break;
            case $this->Legume :
                return "Legume";
                break;    
            case $this->Confiture :
                return "Confiture";
                break;
            default:
                return "Error, choice invalid";
                break;
        }
    }
}
