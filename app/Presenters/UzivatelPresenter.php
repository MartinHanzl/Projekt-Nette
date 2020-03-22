<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\uzivatelAuthenticator;

final class UzivatelPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    private $user;
    private $authenticator;

    public function __construct(Nette\Database\Context $database, Nette\Security\User $user, uzivatelAuthenticator $authenticator) {
        $this->database = $database;
        $this->user = $user;
        $this->authenticator = $authenticator;
        $this->user->setAuthenticator($this->authenticator);
    }

    protected function createComponentLoginForm() :Form {
        $form = new Form();
        $form->addEmail("email")
                ->setRequired("Zadejte prosím platnou emailovou adresu!")
                ->setHtmlAttribute("placeholder", "Emailová adresa");;
        $form->addPassword("heslo")
                ->setRequired("Zadejte prosím vaše heslo")
                ->setHtmlAttribute("placeholder", "Heslo");
        $form->addSubmit('btnLogin', 'Přihlášení');
        $form->onSuccess[] = [$this, 'prihlasFormSucceeded'];
        return $form;
    }

    public function prihlasFormSucceeded(Form $form, \stdClass $values) : void {
        try {
            $this->user->login($values->email, $values->heslo);
            $this->redirect("Homepage:default");
        }catch (Nette\Security\AuthenticationException $exception){
            $form->addError($exception->getMessage());
        }
    }

    protected function createComponentRegistrationForm() :Form {
        $form = new Form();
        $form->addText("jmeno")
                ->setRequired("Zadejte vaše jméno")
                ->setHtmlAttribute("placeholder", "Jméno");
        $form->addText("prijmeni")
            ->setRequired("Zadejte vaše příjmení")
            ->setHtmlAttribute("placeholder", "Příjmení");
        $form->addEmail("email")
                ->setRequired("Zadejte platnou emailovou adresu")
                ->setHtmlAttribute("placeholder", "Email");
        $form->addText("telefon")
                ->setRequired("Zadejte platné telefonní číslo")
                ->setHtmlAttribute("placeholder", "Telefon");
        $form->addPassword("heslo1")
                ->setRequired("Zadejte heslo")
                ->setHtmlAttribute("placeholder", "Heslo");
        $form->addPassword("heslo2")
            ->setRequired("Zadejte heslo znovu")
            ->setHtmlAttribute("placeholder", "Heslo znovu");
        $form->addSubmit("btnRegister", "Zaregistrovat se");
        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        return $form;
    }

    public function registrationFormSucceeded(Form $form, \stdClass $values) : void {
        $uzivatel = $this->database->table("uzivatele")->where("email", $values->email)->fetch();
        if(!$uzivatel) {
            if($values->heslo1 == $values->heslo2) {
                $form->addError("Zadaná hesla se neshodují");
                $heslo1 = password_hash($values->heslo1, PASSWORD_BCRYPT);
                $this->database->table('uzivatele')->insert(
                    [
                        "Jmeno"=>$values->jmeno,
                        "Prijmeni"=>$values->prijmeni,
                        "Email"=>$values->email,
                        "Telefon"=>$values->telefon,
                        "Heslo"=>$heslo1,
                        "Role_ID"=>1
                    ]
                );
                $this->redirect("Uzivatel:login");
            } else {
                $form->addError("Zadaná hesla se neshodují!");
            }
        } else {
            $form->addError("Tento email je již zabraný!");
        }
    }

    public function actionOdhlas(){
        $this->user->logout(true);
        $this->redirect("Uzivatel:login");
    }

    public function renderSelf() :void {
        $this->setLayout("AdministrationLayout");
        $this->template->uzivatel = $this->database->table('uzivatele')->where("uzivateleID", $this->user->getId());
        $this->template->uClanek = $this->database->table("prispevky")->where("Uzivatele_ID", $this->user->getId())
            ->order("prispevkyID DESC")
            ->limit(1);
    }

    public function renderStrange($id) :void {
        $this->setLayout("AdministrationLayout");
        $this->template->uzivatel = $this->database->table('uzivatele')->where("uzivateleID", $id);
    }

    public function actionUp($id) {
        $this->database->table("uzivatele")->where("uzivateleID", $id)->update(["Role_ID" => 2]);
        $this->redirect("Administration:uzivatele");
    }

    public function actionDown($id) {
        $this->database->table("uzivatele")->where("uzivateleID", $id)->update(["Role_ID" => 1]);
        $this->redirect("Administration:uzivatele");
    }
}