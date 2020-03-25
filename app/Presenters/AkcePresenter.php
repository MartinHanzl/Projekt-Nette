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
        $form->addText("Nadpis")
                ->setRequired("Zadejte nadpis událsoti")
                ->setHtmlAttribute("placeholder", "Název akce");
        $form->addText("Datum")
                ->setRequired("Vybrete prosím čas konání události!")
                ->setType("date");
        $form->addText("Cas")
                ->setHtmlAttribute("placeholder", "Čas konání (formát --:--)")
                ->setRequired("Vybrete prosím čas konání události!");
        $kategorie = [
            1 => "Závod",
            2 => "Nácvik",
            3 => "Školení",
            4 => "Ples",
            5 => "Exhibice",
            6 => "Informace",
        ];
        $form->addSelect("Kategorie_Akce_ID", '', $kategorie)
            ->setRequired("Vyberte prosím kategorii události!");
        $form->addTextArea("Popis")
                ->setHtmlId("summernote");
        $form->addHidden("Uzivatele_ID")
                ->setValue($this->user->getId());
        $form->addSubmit("btnAddd", "Uložit a publikovat událost");

        $form->onSuccess[] = [$this, 'addFormSucceeded'];
        return $form;
    }

    public function actionEdit($id) :void{
        $post = $this->database->table('akce')->where("akceID", $id)->fetch();
        if(!$post) {
            $this->error("Událost nebyla nalezena!");
        }
        $this["addAkce"]->setDefaults($post->toArray());
    }

    public function addFormSucceeded(Form $form, array $values) : void {
        $postID = $this->getParameter("id");
        if($postID) {
            $akce = $this->database->table("akce")->where("akceID", $postID);
            $akce->update($values);
            $this->flashMessage("Událost byla úspěšně upravena!", 'success');
        } else {
            $this->database->table("akce")->insert($values);
            $this->flashMessage("Událost byla úspěšně vytvořena!", "success");
        }
        $this->redirect("Administration:akce");
    }

    public function actionVymaz($id) :void {
        $akce = $this->database->table("akce")->where("akceID", $id);
        $akce->delete();
        $this->flashMessage("Událost byla úspěšně vymazána!", 'warning');
        $this->redirect("Administration:akce");
    }

}