<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller recuperar senha na página de login.
 * @author Cesar <cesar@celke.com.br>
 */
class RecoverPassword
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Método recuperar senha na página de login.
     * Receber os dados do formulário.
     * 
     * Se o usuário clicou no botão recuperar senha, instancia a MODELS para salvar os dados do usuário no banco de dados sobre o novo link para recuperar senha, se editar corretamente redireciona para a página de login, senão carrega o formulário novamente.
     * 
     * Se o usuário não clicar no botão acessa o ELSE e instancia o método "viewRecoverPass" para carrega o formulário.
     * 
     * @return void
     */
    public function index(): void
    {
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($this->dataForm['SendRecoverPass'])) {
            unset($this->dataForm['SendRecoverPass']);
            $recoverPass = new \App\adms\Models\AdmsRecoverPassword();
            $recoverPass->recoverPassword($this->dataForm);

            if ($recoverPass->getResult()) {
                $urlRedirect = URLADM . "login/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] =  $this->dataForm;
                $this->viewRecoverPass();
            }
        } else {
            $this->viewRecoverPass();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewRecoverPass(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/recoverPassword", $this->data);
        $loadView->loadViewLogin();
    }
}
