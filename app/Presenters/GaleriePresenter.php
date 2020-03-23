<?php


namespace App\Presenters;

Use Nette;

use Nette\Application\UI\Form;
use Nette\ComponentModel\IComponent;
use Nette\Utils\Image;

final class GaleriePresenter extends Nette\Application\UI\Presenter {

    private $database;
    public function __construct(Nette\Database\Context $database) {
        $this->setLayout("AdministrationLayout");
        $this->database = $database;
    }
    protected function createComponentAddGalerie(): Form {
        $form = new Form();
        $form->addText("nazev")
                ->setRequired("Zadejte prosím název fotogalerie!")
                ->setHtmlAttribute("placeholder", "Název fotogalerie");
        $form->addMultiUpload("obrazky")
                ->setRequired("Vybrete prosím jeden nebo více obrázků");
        $form->addSubmit("btnAdd", "Vytvořit fotogalerii");
        $form->onSuccess[] = [$this, 'addFormSucceeded'];
        return $form;
    }
    public function addFormSucceeded(Form $form, \stdClass $values) : void {
        $slozka = $values->nazev;
        $row = $this->database->table("fotogalerie")->insert(
            [
                "Nazev" => $values->nazev,
                "Pridano" => date("Y-m-d"),
                "Uzivatele_ID" => $this->user->getId(),
            ]
        );
        $lastID = $row->Fotogalerie_ID;
        foreach ($values->obrazky as $obrazek) {
            if($obrazek->isImage() && $obrazek->isOk()) {
                $file_ext = strtolower(mb_substr($obrazek->getSanitizedName(), strrpos($obrazek->getSanitizedName(), ".")));
                $filename = uniqid(rand(0,20), TRUE).$file_ext;
                $obrazek->move("./Galerie/" . $filename);
                $this->database->table("fotografie")->insert(
                  [
                      "Foto" => $filename,
                      "Fotogalerie_Fotogalerie_ID" => $lastID,
                  ]
                );
            }
        }
        $this->redirect("Administration:galerie");
    }

    public function renderEdit($id) :void {
        $this->setLayout("AdministrationLayout");
        $this->template->foto = $this->database->table("fotografie")->where("Fotogalerie_Fotogalerie_ID", $id);
    }

    public function actionVymazFoto($id) :void {
        $foto = $this->database->table("fotografie")->where("ID", $id);
        $foto->delete();
        $this->redirect("Administration:galerie");
    }

    public function actionVymazGalerii($id) {
        $foto = $this->database->table("fotografie")->where("Fotogalerie_Fotogalerie_ID", $id);
        $foto->delete();
        $galerie = $this->database->table("fotogalerie")->where("Fotogalerie_ID", $id);
        $galerie->delete();
        $this->redirect("Administration:galerie");
    }

}