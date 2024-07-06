<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

use PDO;
use PDOException;

/**
 * Classe gernérica para apagar registro no banco de dados
 *
 * @author Celke
 */
class AdmsDelete extends AdmsConn
{
    /** @var string $table Recebe o nome da tabela*/
    private string $table;
    /** @var string|null $terms Recebe os termos da pesquisa*/
    private string|null $terms;
    /** @var array $value Recebe os valores da pesquisa*/
    private array $value = [];
    /** @var string|null|boolean $result Recebe o resultado TRUE ou DALSE*/
    private string|null|bool $result;
    /**  @var object $delete Recebe o objeto quer será usado para deletar o registro do banco de dados*/
    private object $delete;
    /** @var string $query Recebe a query que será usada para deletar o registro do banco de dados*/
    private string $query;
    /** @var object $conn Recebe a conexão com o banco de dados*/
    private object $conn;

    /** @return string|null|boolean Recebe o resultado TRUE ou FALSE*/
    function getResult(): string|null|bool
    {
        return $this->result;
    }

    /**
     * Metodo recebe a query que será usada para apagar o registro do banco de dados
     * Chama o metodo exeInstruction para executar a query
     * @param string $table
     * @param string|null|null $terms
     * @param string|null|null $parseString
     * @return void
     */
    public function exeDelete(string $table, string|null $terms = null, string|null $parseString = null): void
    {
        $this->table = $table;
        $this->terms = $terms;

        parse_str($parseString, $this->value);

        $this->query = "DELETE FROM {$this->table} {$this->terms}";

        $this->exeInstruction();
    }

    /**
     * Metodo executa a query para apagar o registro do banco de dados
     * Retorna FALSE se houver algum erro.
     * @return void
     */
    private function exeInstruction(): void
    {
        
        $this->connection();
        try {
            $this->delete->execute($this->value);
            $this->result = true;
        } catch (PDOException $err) {
            $this->result = false;
        }
    }

    /**
     * Obtem a conexão com o banco de dados da classe pai "Conn".
     * Prepara uma instrução para execução e retorna um objeto de instrução.
     * 
     * @return void
     */
    private function connection(): void
    {
        $this->conn = $this->connectDb();
        $this->delete = $this->conn->prepare($this->query);
    }
}
