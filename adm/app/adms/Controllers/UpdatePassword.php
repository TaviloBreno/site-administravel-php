<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar nova senha na página de login.
 * @author Cesar <cesar@celke.com.br>
 */
class UpdatePassword
{

    /** @var string|null $key Recebe a chave para cadastrar nova senha */
    private string|null $key;

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    
    /**
     * Método editar nova senha na página de login.
     * Receber a chave que vem na URL.
     * Receber os dados do formulário.
     * 
     * Se existir a chave na URL e o usuário não clicou no botão "SendUpPass". Instancia o método para validar a chave.
     * Senão instancia o método para editar a senha.
     *
     * @return void
     */
    public function index(): void
    {

        $this->key = filter_input(INPUT_GET, "key", FILTER_DEFAULT);
        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if ((!empty($this->key)) and (empty($this->dataForm['SendUpPass']))) {
            $this->validateKey();
        } else {
            $this->updatePassword();
        }
    }

    /**
     * Validar a chave.
     * Instancia a MODELS responsável em validar a chave, se a chave for válida carrega o formulário.
     * Senão, redireciona para a página de login.
     *
     * @return void
     */
    private function validateKey(): void
    {
        $valKey = new \App\adms\Models\AdmsUpdatePassword();
        $valKey->valKey($this->key);
        if ($valKey->getResult()) {
            $this->viewUpdatePassword();
        } else {
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Editar a senha.
     * Se o usuário não clicou no botão "SendUpPass" no formulário, redireciona para a página de login.
     * Acessa o IF quando usuário clicou no botão "SendUpPass" no formulário. Instancia a MODELS e envia os dados da nova senha do usuário. Se editar redireciona para a página de login. Senão, carrega o formulário.
     *
     * @return void
     */
    private function updatePassword(): void
    {
        if (!empty($this->dataForm['SendUpPass'])) {
            unset($this->dataForm['SendUpPass']);
            $this->dataForm['key'] = $this->key;
            $upPassword = new \App\adms\Models\AdmsUpdatePassword();
            $upPassword->editPassword($this->dataForm);
            if ($upPassword->getResult()) {
                $urlRedirect = URLADM . "login/index";
                header("Location: $urlRedirect");
            } else {
                $this->viewUpdatePassword();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Link inválido, solicite novo link <a href='" . URLADM . "recover-password/index'>clique aqui</a>!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewUpdatePassword(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/login/updatePassword", $this->data);
        $loadView->loadViewLogin();
    }
}
