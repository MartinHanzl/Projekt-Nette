<?php


namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;

final class AkcePresenter extends Nette\Application\UI\Presenter {
    private $database;
    public function __construct(Nette\Database\Context $database) {
        $this->setLayout("AdministrationLayout");
        $this->database = $database;
    }

    protected function createComponentAddAkce(): Form {
        $form = new Form();
        $form->addText("nadpis")
                ->setRequired("Zadejte nadpis událsoti")
                ->setHtmlAttribute("placeholder", "Název akce");
        $form->addText("datum")
                ->setRequired("Vybrete prosím čas konání události!")
                ->setType("date");
        $form->addText("cas")
                ->setRequired("Vybrete prosím čas konání události!")
                ->setType("time");
        $kategorie = [
            1 => "Závod",
            2 => "Nácvik",
            3 => "Školení",
            4 => "Ples",
            5 => "Exhibice",
            6 => "Informace",
        ];
        $form->addSelect("kategorie", '', $kategorie)
            ->setRequired("Vyberte prosím kategorii události!");
        $form->addTextArea("text")
                ->setHtmlId("summernote");
        $form->addSubmit("btnAddd", "Vytvořit událost");

        $form->onSuccess[] = [$this, 'addFormSucceeded'];
        return $form;
    }

    public function addFormSucceeded(Form $form, \stdClass $values) : void {
        $this->database->table("akce")->insert(
            [
                "Nadpis" => $values->nadpis,
                "Datum" => $values->datum,
                "Cas" => $values->text,
                "Popis" => $values->text,
                "Uzivatele_ID" => $this->user->getId(),
                "Kategorie_Akce_ID" => $values->kategorie
            ]
        );
        $this->redirect("Administration:clanky");
    }



}