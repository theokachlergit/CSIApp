<?php enum enumTypeProduit: string
{
    case  Fromage = "Fromage";
    case Oeuf = "Oeuf";
    case Lait = "Lait";
    case Legume = "Legume";
    case Confiture = "Confiture";


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
