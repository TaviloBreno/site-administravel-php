<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe genérica para validar a senha
 *
 * @author Celke
 */
class AdmsValPassword
{
    /** @var string $password Recebe a senha que deve ser validada */
    private string $password;

    /** @var bool $result Recebe true quando executar o processo com sucesso e false quando houver erro */
    private bool $result;

    /**
     * @return bool Retorna true quando executar o processo com sucesso e false quando houver erro
     */
    function getResult(): bool
    {
        return $this->result;
    }

    /** 
     * Verificar se a senha possui aspas simples " ' ", retorna erro.
     * Verificar se a senha possui espaço em branco " ", retorna erro.
     * Instancia o método para validar a quantidade de caracteres a senha possui
     * 
     * @param string $password Recebe a senha que deve ser validada.
     * 
     * @return void
     */
    public function validatePassword(string $password): void
    {
        $this->password = $password;

        if (stristr($this->password, "'")) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Caracter ( ' ) utilizado na senha inválido!</p>";
            $this->result = false;
        } else {
            if (stristr($this->password, " ")) {
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Proibido utilizar espaço em branco no campo senha!</p>";
                $this->result = false;
            } else {
                $this->valExtensPassword();
            }
        }
    }

    /** 
     * Verificar se a senha possui menos de 6 caracteres, retorna erro.
     * Instancia o método para validar os caracteres que a senha possui
     * 
     * @return void
     */
    private function valExtensPassword(): void
    {
        if (strlen($this->password) < 6) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: A senha deve ter no mínimo 6 caracteres!</p>";
            $this->result = false;
        } else {
            $this->valValuePassword();
        }
    }

    /** 
     * Verificar se a senha possui letra e números na senha.
     * 
     * @return void
     */
    private function valValuePassword(): void
    {
        if(preg_match('/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9-@#$%;*]{6,}$/', $this->password)){
            $this->result = true;
        }else{
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: A senha deve ter letras e números!</p>";
            $this->result = false;
        }
    }
}
