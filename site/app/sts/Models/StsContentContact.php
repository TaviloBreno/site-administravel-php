<?php

namespace Sts\Models;

// Redirecionar ou para o processamento quando o usuário não acessa o arquivo index.php
if (!defined('C7E3L8K9E5')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}

/**
 * Models responsável em buscar os dados da página contato
 *
 * @author Celke
 */
class StsContentContact
{
    /** @var array|null $data Recebe os registros do banco de dados */
    private array|null $data;

    /**
     * Instancia a classe genérica no helper responsável em buscar os registro no banco de dados.
     * Possui a QUERY responsável em buscar os registros no BD.
     * @return array|null Retorna o registro do banco de dados com informações para página Contato
     */
    public function index(): array|null
    {    
        $viewContentContact = new \Sts\Models\helper\StsRead();
        $viewContentContact->fullRead("SELECT title_contact, desc_contact, icon_company, title_company, desc_company, icon_address, title_address, desc_address, icon_email, title_email, desc_email, title_form
                            FROM sts_contents_contacts 
                            WHERE id=:id 
                            LIMIT :limit", "id=1&limit=1");
        $this->data = $viewContentContact->getResult();        

        return $this->data;
    }
}
