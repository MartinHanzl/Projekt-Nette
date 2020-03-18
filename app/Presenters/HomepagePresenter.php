<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;

final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private $database;
    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }
    public function renderDefault() :void {
        $this->template->prispevky = $this->database->table('prispevky')
            ->order('prispevkyID DESC')
            ->limit(3);
    }

    public function renderPrispevky() :void {
        $this->template->prispevky = $this->database->table('prispevky')
            ->order('prispevkyID DESC');
    }

    public function renderPrispevek($id) :void {
        $this->template->prispevky = $this->database->table('prispevky')->get($id);
    }

    public function renderAkce() :void {
        $this->template->akce = $this->database->table('akce')
            ->order('akceID DESC');
    }

    protected function createComponentContactForm() :Form {
        $form = new Form;
        $form->addEmail('email')
                ->setHtmlAttribute('placeholder', 'Vaše emailová adresa')
                ->setRequired("Zadejte platnou emailovou adresu!");
        $form->addText('predmet')
                ->setHtmlAttribute('placeholder', 'Předmět emailu')
                ->setRequired("Zadejte předmět emailu!");
        $form->addSubmit('login', 'Registrovat');
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }
}
