<?php

namespace App\adms\Models;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Confirmar a chave atualizar senha. Cadastrar nova senha
 *
 * @author Celke
 */
class AdmsUpdatePassword
{

    /** @var string $key Recebe a chave para atualizar a senha */
    private string $key;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /** @var array|null $resultBd Recebe os registros do banco de dados */
    private array|null $resultBd;

    /** @var array $dataSave Salva as informações no banco de dados*/
    private array $dataSave;

    /** @var array|null $data Recebe as informações do formulário */
    private array|null $data;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Metodo faz a pesquisa da chave de recuperação da senha do usuário para verificar se a chave é valida
     * Recebe como parametro $key que será usada na pesquisa.
     * Retorna FALSE se tiver algum erro.
     * @return void
     */
    public function valKey(string $key): bool
    {
        $this->key = $key;
        $viewKeyUpPass = new \App\adms\Models\helper\AdmsRead();
        $viewKeyUpPass->fullRead("SELECT id
                                FROM adms_users
                                WHERE recover_password=:recover_password
                                LIMIT :limit", "recover_password={$this->key}&limit=1");
        $this->resultBd = $viewKeyUpPass->getResult();
        if ($this->resultBd) {
            $this->result = true;
            return true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Link inválido, solicite novo link <a href='" . URLADM . "recover-password/index'>clique aqui</a>!</p>";
            $this->result = false;
            return false;
        }
    }

    /**
     * Recebe as informações que serão validadas
     * Chama a função valInput para validar a senha
     * @param array|null $data
     * @return void
     */
    public function editPassword(array $data = null): void
    {
        $this->data = $data;
        $valEmptyField = new \App\adms\Models\helper\AdmsValEmptyField();
        $valEmptyField->valField($this->data);
        if ($valEmptyField->getResult()) {
            $this->valInput();
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo valida o campo "password" com o helper "AdmsValPassword"
     * Valida a chave com a função "valKey"
     * Chama a função "updatePassword"
     * Retorna FALSE se houver erro.
     * @return void
     */
    private function valInput(): void
    {
        $valPassword = new \App\adms\Models\helper\AdmsValPassword();
        $valPassword->validatePassword($this->data['password']);
        if ($valPassword->getResult()) {
            if ($this->valKey($this->data['key'])) {
                $this->updatePassword();
            } else {
                $this->result = false;
            }
        } else {
            $this->result = false;
        }
    }

    /**
     * Metodo envia as informações editadas para o banco de dados
     *
     * @return void
     */
    private function updatePassword():void
    {
        $this->dataSave['recover_password'] = null;
        $this->dataSave['password'] = password_hash($this->data['password'], PASSWORD_DEFAULT);
        $this->dataSave['modified'] = date("Y-m-d H:i:s");

        $upPassword = new \App\adms\Models\helper\AdmsUpdate();
        $upPassword->exeUpdate("adms_users", $this->dataSave, "WHERE id=:id", "id={$this->resultBd[0]['id']}");
        if($upPassword->getResult()){
            $_SESSION['msg'] = "<p class='alert-success'>Senha atualizada com sucesso!</p>";
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Senha não atualizada, tente novamente!</p>";
            $this->result = false;
        }
    }
}
