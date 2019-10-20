<?php

namespace App\Http\Controllers\Core;

use Image;
use App\Http\Controllers\Controller;

class ImagemController {

    public function geraImagemBase64($file, $width = null, $extensao = null){

        if( isset( $file ) ){
            $a = array('jpeg', 'png', 'jpg', 'gif', 'svg');

            if (in_array($extensao, $a, true)) {

                // Cria objeto da imagem
                $img = \Image::make($file);

                if( !is_null( $width ) ){

                    if( $img->width() > $width){
                        // Redimensiona preservando as proporções
                        $img->resize($width, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }
                }
                // Converte para base64
                // return (string) $img->encode('data-url',20);
                return base64_encode($img->encode($extensao, 100));
                
            }else{

                throw new \Exception("Tipo de extensão da imagem não suportada", 1);
                
            }
           
        }else{
            
            throw new \Exception("Imagem inexistente", 1);
            
        }
        

    }

    public function geraImagemThumbBase64( $file, $extensao ){

        if( isset( $file ) ){

            $a = array('jpeg', 'png', 'jpg', 'gif', 'svg');

            if (in_array($extensao, $a, true)) {

               // Cria objeto da imagem
                $img = \Image::make($file);

                // Redimensiona preservando as proporções
                $img->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                // Corta a imagem para ficar 150x85
                $img->crop(150, 85, 0, 0);

                // Converte para base64
                return base64_encode($img->encode( $extensao ));
                
            }else{

                throw new \Exception("Tipo de extensão da imagem não suportada", 1);
                
            }
           
        }else{
            
            throw new \Exception("Imagem inexistente", 1);
        }        

    }

    public static function getWidth( $imagem ){

        if( isset( $imagem ) ){

            // Cria objeto da imagem
            $img = \Image::make($imagem);

            // Retorna valor
            return $img->width();

        }

        return 0;
    }

    public static function getHeight( $imagem ){

        if( isset( $imagem ) ){

            // Cria objeto da imagem
            $img = \Image::make($imagem);

            // Retorna valor
            return $img->height();

        }

        return 0;
    }

    public static function getResponse( $imagem, $formato = 'png' ){

        // Cria a imagem
        $img = \Image::make($imagem);        

        // Cria a resposta
        $response = \Response::make($img->encode($formato));
        $response->header('Content-Type', 'image/'.$formato);
        
        return $response;

    }

}

