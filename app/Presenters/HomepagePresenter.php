<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI;
use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SendmailMailer;

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
        $this->template->prispevek = $this->database->table('prispevky')->where("prispevkyID", $id);
    }

    public function renderAkce() :void {
        $this->template->akce = $this->database->table('akce')
            ->order('akceID DESC');
    }

    public function renderAkceShow($id) :void {
        $this->template->akce = $this->database->table("akce")->where("akceID", $id);
    }

    public function renderGalerie() :void {
        $this->template->galerie = $this->database->table('fotogalerie')
            ->order('Fotogalerie_ID DESC');
    }

    protected function createComponentContactForm() :Form {
        $form = new Form;
        $form->addEmail('email')
                ->setHtmlAttribute('placeholder', 'Vaše emailová adresa')
                ->setRequired("Zadejte platnou emailovou adresu!");
        $form->addText('predmet')
                ->setHtmlAttribute('placeholder', 'Předmět emailu')
                ->setRequired("Zadejte předmět emailu!");
        $form->addTextArea('text')
                ->setHtmlAttribute('placeholder', 'Text emailu')
                ->setRequired("Zadejte text emailové zprávy!");
        $form->addSubmit('emailSend', 'Odeslat email');
        $form->onSuccess[] = [$this, 'emailFormSucceeded'];
        return $form;
    }

    public function emailFormSucceeded(UI\Form $form, \stdClass $values): void {
        $values = $form->getValues();
        $email = $values["email"];
        $predmet = $values["predmet"];
        $text = $values["text"];
        $mail = new Message();
        $mail->setFrom($email)
                ->addTo("martas.hanzl@email.cz")
                ->setSubject($predmet)
                ->setBody($text);
        $mailer = new SendmailMailer;
        $mailer->send($mail);
    }
}
