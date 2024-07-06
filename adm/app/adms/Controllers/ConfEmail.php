<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller confirmar e-mail
 * @author Cesar <cesar@celke.com.br>
 */
class ConfEmail
{

    /** @var string|null $key Recebe a chave para confirmar o cadastro */
    private string|null $key;

    /**
     * Método confirmar e-mail
     * Receber da URL a chave para confirmar o e-mail
     * Se existir a chave instancia o método para validar a chave e confirmar o e-mail
     * Senão acessa  o ELSE e redireciona o usuário para a página de login
     * 
     * @return void
     */
    public function index(): void
    {
        $this->key = filter_input(INPUT_GET, "key", FILTER_DEFAULT);

        if (!empty($this->key)) {
            $this->valKey();
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário confirmar o e-mail, solicite novo link <a href='".URLADM."new-conf-email/index'>Clique aqui</a>!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instancia a MODELS responsável e confirmar o e-mail
     * Se o e-mail for confirmado redireciona para página de login
     * Senão acessa o ELSE e redireciona para página de login
     * 
     * @return void
     */
    private function valKey(): void
    {
        $confEmail = new \App\adms\Models\AdmsConfEmail();
        $confEmail->confEmail($this->key);
        if ($confEmail->getResult()) {
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        } else {
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
