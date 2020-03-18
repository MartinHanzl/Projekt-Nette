<?php


namespace App;

use Nette;

use Nette\Database\Context;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;

class UzivatelAuthenticator implements Nette\Security\IAuthenticator
{
    private $database;

    private $passwords;

    public function __construct(Nette\Database\Context $database, Nette\Security\Passwords $passwords)
    {
        $this->database = $database;
        $this->passwords = $passwords;
    }

    public function authenticate(array $credentials): Nette\Security\IIdentity
    {
        [$email, $heslo] = $credentials;

        $row = $this->database->table('Uzivatele')
            ->where('email', $email)->fetch();

        if (!$row) {
            throw new Nette\Security\AuthenticationException('User not found.');
        }

        if (!$this->passwords->verify($heslo, $row->Heslo)) {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        if($row->Role_ID == 1) {
            $status = 1;
        }

        if($row->Role_ID == 2) {
            $status = 2;
        }

        return new Nette\Security\Identity($row->uzivateleID, $status, ['email' => $row->Email]);
    }
}