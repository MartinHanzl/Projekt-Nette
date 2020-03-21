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
        $datum = date("Y-m-d");
        $form = new Form();
        $form->addHidden("Datum")
                ->setValue($datum);
        $form->addHidden("Uzivatele_ID")
                ->setValue($this->user->getId());
        $form->addText("Nadpis")
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
        $form->addSelect("Kategorie_Prispevky_ID", '', $kategorie)
                ->setRequired("Vyberte prosím kategorii článku!");
        $form->addTextArea("text")
                ->setHtmlId("summernote");
        $form->addSubmit("btnAdd", "Zapsat příspěvek");
        $form->onSuccess[] = [$this, 'addFormSucceeded'];
        return $form;
    }

    public function actionEdit($id) :void {
        $post = $this->database->table('prispevky')->where("prispevkyID", $id);
        if(!$post) {
            $this->error("Příspěvek nebyl nalezen");
        }
        $this["addClanek"]->setDefaults($post->toArray());
    }

    public function addFormSucceeded(Form $form, array $values) : void {
        $postID = $this->getParameter("id");
        if($postID) {
            $prispevek = $this->database->table("prispevky")->where("prispevkyID", $postID);
            $prispevek->update($values);
        } else {
            $prispevek = $this->database->table("prispevky")->insert($values);
        }
        $this->redirect("Administration:clanky");
    }

    public function actionVymaz($id){
        $this->database->table("prispevky")->where("prispevkyID", $id)->delete();
    }

}