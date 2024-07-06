<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

use PDO;
use PDOException;

/**
 * Classe gernérica para editar registro no banco de dados
 *
 * @author Celke
 */
class AdmsUpdate extends AdmsConn
{
    /** @var string $table Recebe o nome da tabela*/
    private string $table;
    /** @var string|null $terms Recebe os termos da query*/
    private string|null $terms;
    /** @var array $data Recebe as informações que estarão na query*/
    private array $data;
    /** @var array $value Recebe os valores que deve ser atribuidos nos link da QUERY com bindValue*/
    private array $value = [];
    /** @var string|null|boolean $result Retorna o resuultado TRUE ou FALSE*/
    private string|null|bool $result;
    /** @var object $update Recebe a informação que será atualizada no banco de dados*/
    private object $update;
    /** @var string $query Recebe a QUERY preparada*/
    private string $query;
    /** @var object $conn Recebe a conexão com o banco de dados*/
    private object $conn;

    /** @return string|null|boolean Retorna TRUE ou FALSE*/
    function getResult(): string|null|bool
    {
        return $this->result;
    }

    /**
     * Metodo recebe o nome da tabela, a informação que será atualizada no banco de dados e os termos
     * Chama o metodo exeReplaceValues para criar a QUERY
     * @param string $table
     * @param array $data
     * @param string|null|null $terms
     * @param string|null|null $parseString
     * @return void
     */
    public function exeUpdate(string $table, array $data, string|null $terms = null, string|null $parseString = null): void
    {
        $this->table = $table;
        $this->data = $data;
        $this->terms = $terms;

        parse_str($parseString, $this->value);

        $this->exeReplaceValues();
    }

    /**
     * Metodo configura a QUERY com as informações que serão editadas no banco de dados
     * Chama o metodo exeInstruction para executar a QUERY
     * @return void
     */
    private function exeReplaceValues(): void
    {
        foreach ($this->data as $key => $value) {
            $values[] = $key . "=:" . $key;
        }
        $values = implode(', ', $values);

        $this->query = "UPDATE {$this->table} SET {$values} {$this->terms}";

        $this->exeInstruction();
    }

    /**
     * Metodo faz a execução da QUERY e atualiza as informações no banco de dados
     * Caso tenha algum erro retorna NULL
     * @return void
     */
    private function exeInstruction(): void
    {
        $this->connection();
        try {
            $this->update->execute(array_merge($this->data, $this->value));
            $this->result = true;
        } catch (PDOException $err) {
            $this->result = null;
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
        $this->update = $this->conn->prepare($this->query);
    }
}
