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
        $prispevky = $this->database->query("SELECT * FROM prispevky LEFT JOIN kategorie_prispevky ON kategorie_prispevky.kategoriePrispevkyID = prispevky.Kategorie_Prispevky_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = prispevky.Uzivatele_ID ORDER BY prispevkyID DESC LIMIT 3 ");
        $this->template->prispevky = $prispevky;
    }

    public function renderPrispevky() :void {
        $prispevky = $this->database->query("SELECT * FROM prispevky LEFT JOIN kategorie_prispevky ON kategorie_prispevky.kategoriePrispevkyID = prispevky.Kategorie_Prispevky_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = prispevky.Uzivatele_ID ORDER BY prispevkyID DESC");
        $this->template->prispevky = $prispevky;
    }

    public function renderPrispevek($id) :void {
        $prispevek = $this->database->query("SELECT * FROM prispevky LEFT JOIN uzivatele ON uzivatele.uzivateleID = prispevky.Uzivatele_ID LEFT JOIN kategorie_prispevky ON kategorie_prispevky.kategoriePrispevkyID = prispevky.Kategorie_Prispevky_ID WHERE prispevkyID = '$id';");
        $this->template->prispevek = $prispevek;//$this->database->table('prispevky')->where("prispevkyID", $id);
    }

    public function renderAkce() :void {
        $akce = $this->database->query("SELECT * FROM akce LEFT JOIN kategorie_akce ON kategorie_akce.kategorieAkceID = akce.Kategorie_Akce_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = akce.Uzivatele_ID ORDER BY Datum ASC");
        $this->template->akce = $akce;
    }

    public function renderAkceShow($id) :void {
        $akce = $this->database->query("SELECT * FROM akce LEFT JOIN kategorie_akce ON kategorie_akce.kategorieAkceID = akce.Kategorie_Akce_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = akce.Uzivatele_ID WHERE akce.akceID = '$id'");
        $this->template->akce = $akce;
    }

    public function renderGalerie() :void {
        $galerie = $this->database->query("SELECT * FROM fotogalerie LEFT JOIN uzivatele ON uzivatele.uzivateleID = fotogalerie.Uzivatele_ID ORDER BY Fotogalerie_ID DESC");
        $this->template->galerie = $galerie;
    }

    public function renderGalerieShow($id) :void {
        $this->template->foto = $this->database->table('fotografie')->where("Fotogalerie_Fotogalerie_ID", $id);
    }

    public function renderKategorie($id) :void {
        $prispevek = $this->database->query("SELECT * FROM prispevky LEFT JOIN kategorie_prispevky ON kategorie_prispevky.kategoriePrispevkyID = prispevky.Kategorie_Prispevky_ID LEFT JOIN uzivatele ON uzivatele.uzivateleID = prispevky.Uzivatele_ID WHERE prispevky.Kategorie_Prispevky_ID = '$id' ORDER BY prispevkyID DESC");
        $this->template->prispevky = $prispevek;
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
