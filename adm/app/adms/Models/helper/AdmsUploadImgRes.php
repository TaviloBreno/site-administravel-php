<?php

namespace App\adms\Models\helper;

if(!defined('C8L6K7E')){
    header("Location: /");
    die("Erro: Página não encontrada<br>");
}

/**
 * Classe gernérica para redimensionar a image
 *
 * @author Celke
 */
class AdmsUploadImgRes
{
    /** @var array $imageData Recebe a informação da imagem*/
    private array $imageData;
    /** @var string $directory Recebe o caminho do diretorio*/
    private string $directory;
    /** @var string $name Recebe o nome da imagem*/
    private string $name;
    /** @var integer $width Recebe a largura da imagem*/
    private int $width;
    /** @var integer $height Recebe a altura da imagem*/
    private int $height;
    /** @var [type] $newImage Recebe o nome temporario da imagem*/
    private $newImage;
    /** @var boolean $result Recebe o resultado TRUE ou FALSE*/
    private bool $result;
    /** @var [type] $imgResize Recebe a informação da imagem redimensionada*/
    private $imgResize;

    /** @return boolean Recebe o resultado TRUE ou FALSE*/
    function getResult(): bool
    {
        return $this->result;
    }

    /**
     * Recebe as informações para fazer o upload da imagem
     * Chama o metodo valDirectory para validar o diretorio
     * @param array $imageData
     * @param string $directory
     * @param string $name
     * @param integer $width
     * @param integer $height
     * @return void
     */
    public function upload(array $imageData, string $directory, string $name, int $width, int $height): void
    {
        $this->imageData = $imageData;
        $this->directory = $directory;
        $this->name = $name;
        $this->width = $width;
        $this->height = $height;

        //var_dump($this->imageData);

        $this->valDirectory();
    }

    /**
     * Metodo faz a verificação se o diretorio existe
     * Se o diretorio não existir chama o metodo createDir para criar o diretorio
     * Se o diretorio existir chama o metodo uploadFile para prossegir com o upload
     * @return void
     */
    private function valDirectory(): void
    {
        if ((file_exists($this->directory)) and (!is_dir($this->directory))) {
            $this->createDir();
        } elseif (!file_exists($this->directory)) {
            $this->createDir();
        } else {
            $this->uploadFile();
        }
    }

    /**
     * Metodo tenta criar o diretorio e verifica se o mesmo existe
     * Caso o diretorio exista prossegue com o upload
     * Se não existir retorna FALSE
     * @return void
     */
    private function createDir(): void
    {
        mkdir($this->directory, 0755);
        if (!file_exists($this->directory)) {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem não realizado com sucesso. Tente novamente!</p>";
            $this->result = false;
        } else {
            $this->uploadFile();
        }
    }

    /**
     * Metodo verifica o tipo da imagem JPEG ou PNG
     * Chama o metodo uploadFileJpeg caso a imagem seja JPEG
     * Chama o metodo uploadFilePng caso a imagem seja PNG
     * Retorna FALSE se houver algum erro
     * @return void
     */
    private function uploadFile(): void
    {
        switch ($this->imageData['type']) {
            case 'image/jpeg':
            case 'image/pjpeg':
                $this->uploadFileJpeg();
                break;
            case 'image/png':
            case 'image/x-png':
                $this->uploadFilePng();
                break;
            default:
                $_SESSION['msg'] = "<p class='alert-danger'>Erro: Necessário selecionar imagem JPEG ou PNG!</p>";
                $this->result = false;
        }
    }

    /**
     * Metodo faz o upload da imagem JPEG
     * Recebe o caminho do diretorio, nome da imagem e as dimensões
     * Retorna FALSE se houver erro
     * @return void
     */
    private function uploadFileJpeg(): void
    {
        $this->newImage = imagecreatefromjpeg($this->imageData['tmp_name']);

        $this->redImg();

        // Enviar a imagem para servidor
        if (imagejpeg($this->imgResize, $this->directory . $this->name, 100)) {
            $_SESSION['msg'] = "<p class='alert-success'>Upload da imagem realizado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem não realizado com sucesso. Tente novamente!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo faz o upload da imagem PNG
     * Recebe o caminho do diretorio, nome da imagem e as dimensões
     * Retorna FALSE se houver erro
     * @return void
     */
    private function uploadFilePng(): void
    {
        $this->newImage = imagecreatefrompng($this->imageData['tmp_name']);

        $this->redImg();

        // Enviar a imagem para servidor
        if (imagepng($this->imgResize, $this->directory . $this->name, 1)) {
            $_SESSION['msg'] = "<p class='alert-success'>Upload da imagem realizado com sucesso!</p>";
            $this->result = true;
        } else {
            $_SESSION['msg'] = "<p class='alert-danger'>Erro: Upload da imagem não realizado com sucesso. Tente novamente!</p>";
            $this->result = false;
        }
    }

    /**
     * Metodo recebe as informações para fazer redimensionamento da imagem
     *
     * @return void
     */
    private function redImg(): void
    {
        // Obter a largura da image
        $width_original = imagesx($this->newImage);

        // Obter a altura da image
        $height_original = imagesy($this->newImage);

        // Criar uma imagem modelo com as dimensões definidas para nova imagem
        $this->imgResize = imagecreatetruecolor($this->width, $this->height);

        // Copiar e redimensionar parte da imagem enviada pelo usuário e interpola com a imagem tamanho modelo
        imagecopyresampled($this->imgResize, $this->newImage, 0, 0, 0, 0, $this->width, $this->height, $width_original, $height_original);
    }
}
