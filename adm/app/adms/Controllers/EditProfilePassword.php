<?php

namespace App\adms\Controllers;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Controller editar senha do perfil
 * @author Cesar <cesar@celke.com.br>
 */
class EditProfilePassword
{

    /** @var array|string|null $data Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = [];

    /** @var array $dataForm Recebe os dados do formulario */
    private array|null $dataForm;

    /**
     * Método editar senha do perfil
     * Receber os dados do formulário.
     * 
     * Quando o usuário clicar no botão "editar" do formulário da página editar imagem do perfil. Acessa o IF e instância o método "AdmsEditProfilePassword".
     * Senão, instancia a MODELS e recupera os dados do perfil do usuário no banco de dados.
     * 
     * Existindo o usuário no banco de dados, recebe os dados do perfil e instancia o método viewEditProfPass.
     * Senão redireciona o usuário para página de login
     * 
     * @return void
     */
    public function index(): void
    {

        $this->dataForm = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($this->dataForm['SendEditProfPass'])) {
           $this->editProfPass();
        } else {
            $viewProfPass = new \App\adms\Models\AdmsEditProfilePassword();
            $viewProfPass->viewProfile();
            if ($viewProfPass->getResult()) {
                $this->data['form'] = $viewProfPass->getResultBd();
                $this->viewEditProfPass();
            } else {
                $urlRedirect = URLADM . "login/index";
                header("Location: $urlRedirect");
            }
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewEditProfPass(): void
    {
        $loadView = new \Core\ConfigView("adms/Views/users/editProfilePassword", $this->data);
        $loadView->loadView();
    }

    /**
     * Editar senha do perfil.
     * Se o usuário clicou no botão, instancia a MODELS responsável em receber os dados e editar no banco de dados.
     * Verifica se editou corretamente o perfil no banco de dados.
     * Se o usuário não clicou no botão redireciona para página de login.
     *
     * @return void
     */
    private function editProfPass(): void
    {
        if (!empty($this->dataForm['SendEditProfPass'])) {
            unset($this->dataForm['SendEditProfPass']);
            $editProfPass = new \App\adms\Models\AdmsEditProfilePassword();
            $editProfPass->update($this->dataForm);
            if ($editProfPass->getResult()) {
                $urlRedirect = URLADM . "view-profile/index";
                header("Location: $urlRedirect");
            } else {
                $this->data['form'] = $this->dataForm;
                $this->viewEditProfPass();
            }
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Perfil não encontrado!</p>";
            $urlRedirect = URLADM . "login/index";
            header("Location: $urlRedirect");
        }
    }
}
