<?php

declare(strict_types=1);

namespace App\Presenters;

use Nette;


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
}
