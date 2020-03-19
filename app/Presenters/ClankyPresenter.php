<?php


namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;

final class ClankyPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    public function __construct(Nette\Database\Context $database)
    {
        $this->setLayout("AdministrationLayout");
        $this->database = $database;
    }

    public function beforeRender() {
        if (!$this->user->isLoggedIn()) {
            $this->redirect("Uzivatel:login");
        }
    }

    protected function createComponentAddClanek() :Form {
        $form = new Form();
        $form->addText("nadpis")
            ->setRequired("Zadejte nadpis článku")
            ->setHtmlAttribute("placeholder", "Nadpis");
        $kategorie = [
            1 => "Závod",
            2 => "Nácvik",
            3 => "Školení",
            4 => "Ples",
            5 => "Exhibice",
            6 => "Informace",
        ];
        $form->addSelect("kategorie", '', $kategorie)
                ->setRequired("Vyberte prosím kategorii článku!");
        $form->addTextArea("text")
                ->setHtmlId("summernote");
        $form->addSubmit("btnAdd", "Zapsat příspěvek");
        $form->onSuccess[] = [$this, 'addFormSucceeded'];
        return $form;
    }
    public function addFormSucceeded(Form $form, \stdClass $values) : void {
        $this->database->table("prispevky")->insert(
            [
                "Nadpis" => $values->nadpis,
                "Text" => $values->text,
                "Datum" => date("Y-m-d"),
                "Uzivatele_ID" => $this->user->getId(),
                "Kategorie_Prispevky_ID" => $values->kategorie
            ]
        );
        $this->redirect("Administration:clanky");
    }

    public function actionVymaz($id){
        $this->database->table("prispevky")->where("prispevkyID", $id)->delete();
    }

}