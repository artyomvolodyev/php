<?php

class TeeImageUtils{
    public static function imageCreateFromFile($filename){
        $fileType = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ));
        switch($fileType){
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($filename);
                break;
            case 'png':
                return imagecreatefrompng($filename);
                break;
            case 'gif':
                return imagecreatefromgif($filename);
                break;
            default:
                throw new InvalidArgumentException('TeeImageUtils::imageCreateFromFile  file "'.$filename.'" is not valid jpg, png or gif image, identified file Type is: '.$fileType);
                break;
        }
    }
}