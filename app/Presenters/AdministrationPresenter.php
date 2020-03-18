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

    public function renderClanky() :void {
        $this->template->prispevky = $this->database->table('prispevky')
            ->order('prispevkyID DESC');
    }

    public function renderGalerie() :void {
        $this->template->galerie = $this->database->table('fotogalerie')
            ->order('Fotogalerie_ID DESC');
    }
}