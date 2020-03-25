<?php


namespace App\Presenters;

use Nette;

final class AdministrationPresenter extends Nette\Application\UI\Presenter {

    private $database;
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
        $this->setLayout("AdministrationLayout");
    }

    public function beforeRender() {
        if (!$this->user->isLoggedIn()) {
            $this->redirect("Uzivatel:login");
        }
    }

    public function renderUzivatele() :void {
        $uzivatele = $this->database->query("SELECT * FROM uzivatele LEFT JOIN role ON role.ID = uzivatele.Role_ID");
        $this->template->uzivatele = $uzivatele;
    }

    public function renderClanky() :void {
        $prispevky = $this->database->query("SELECT * FROM prispevky LEFT JOIN uzivatele ON uzivatele.uzivateleID = prispevky.Uzivatele_ID LEFT JOIN kategorie_prispevky ON kategorie_prispevky.kategoriePrispevkyID = prispevky.Kategorie_Prispevky_ID");
        $this->template->prispevky = $prispevky;
    }

    public function renderGalerie() :void {
        $galerie = $this->database->query("SELECT * FROM fotogalerie LEFT JOIN uzivatele ON uzivatele.uzivateleID = fotogalerie.Uzivatele_ID ORDER BY Fotogalerie_ID DESC");
        $this->template->galerie = $galerie;
    }

    public function renderAkce() :void {
        $akce = $this->database->query("SELECT * FROM akce LEFT JOIN kategorie_akce ON kategorie_akce.kategorieAkceID = akce.Kategorie_Akce_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = akce.Uzivatele_ID ORDER BY akceID DESC");
        $this->template->akce = $akce;
    }
}