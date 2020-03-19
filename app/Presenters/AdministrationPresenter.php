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
        $this->template->uzivatele = $this->database->table('uzivatele')
            ->order('uzivateleID DESC');
    }

    public function renderClanky() :void {
        $this->template->prispevky = $this->database->table('prispevky')
            ->order('prispevkyID DESC');
    }

    public function renderGalerie() :void {
        $this->template->galerie = $this->database->table('fotogalerie')
            ->order('Fotogalerie_ID DESC');
    }

    public function renderAkce() :void {
        $this->template->akce = $this->database->table('akce')
            ->order('akceID DESC');
    }
}