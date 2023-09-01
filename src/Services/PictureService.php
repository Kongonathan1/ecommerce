<?php
namespace App\Services;

use Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $slash = DIRECTORY_SEPARATOR;
//  private $params = "C:{$this->slash}wamp{$this->slash}www{$this->slash}Symfony{$this->slash}sf6-Ecommerce{$this->slash}Elirafy{$this->slash}public{$this->slash}asset{$this->slash}uploads{$this->slash}img{$this->slash}";
    private $params = "Elirafy\public\asset\uploads\img";

    public function __condtruct()
    {}

    public function add(UploadedFile $picture, ?string $folder = '')
    {
        $picture_infos = getimagesize($picture);
        $mime = $picture_infos['mime'];

        $file = uniqid(rand(1, 5), true) . '.jpg';

        if($picture_infos === false) {
            throw new Exception("Le format d'image est invalide");
        }

 
        switch($mime) {
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
                break;
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
                break;
            default:
                throw new Exception("Le format d'image est invalide");
        }

        $rezided_image = imagecreatetruecolor($picture_infos[0], $picture_infos[1]);

        $path = $this->params;

        imagejpeg($rezided_image, $path);
        
        $picture->move($path, DIRECTORY_SEPARATOR . $file);

        return $file;

    }
    
    public function delete(string $filename, ?string $folder = '')
    {
        if($filename !== 'default.jpg') {
            $status = false;
            $path = $this->params . $folder;
            $image = $path . $filename;
            if(file_exists($path)) {
                unlink($image);
                $status = true;
            }
            return $status;
        }
        return false;
    }
}



