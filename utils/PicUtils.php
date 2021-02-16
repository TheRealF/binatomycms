<?php


class PicUtils {


  //Preso spunto da: https://www.w3schools.com/php/php_file_upload.asp
  public static function uploadPic($uploadedPic, $picMode, $blogName){
    $targetDir = "";
    $error = array();
    self::deleteOldPics($picMode, $blogName);

    $blogName = str_replace(" ", "+", urldecode($blogName));

    //Controlla se l'immagine da caricare è lo sfondo o il titolo del blog
    switch ($picMode) {
      case 'blog':
      $targetDir = "assets/img/blogs/bg_blog/";
      break;
      case 'post':
      $targetDir = "assets/img/blogs/bg_post/";
      break;
      default:
      exit();
      break;
    }

    $targetFile = $targetDir . $uploadedPic['name'];
    $finalTarget = $targetDir . $blogName . "_" . time() . ".webp";

    $imgResource;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if ($uploadedPic["size"] > 1000000) {
      return 2;  //Il file è troppo grande
    }

    // Controllo tipo file (approccio consigliato anche in https://www.php.net/manual/en/function.getimagesize.php)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $valideMIMEs = array("image/png", "image/jpeg", "image/gif");
    $fileMIME = finfo_file($finfo, $uploadedPic["tmp_name"]);
    switch ($fileMIME) {
      case 'image/png':
      $imgResource = imagecreatefrompng($uploadedPic["tmp_name"]);
      break;
      case 'image/jpeg':
      $imgResource = imagecreatefromjpeg($uploadedPic["tmp_name"]);
      break;
      case 'image/gif':
        $imgResource = imagecreatefromgif($uploadedPic["tmp_name"]);
        break;
        default:
        return 1;  //Il file non è un'immagine
        break;
      }


      if ($imageFileType != "jpeg" && $imageFileType != "jpg" && $imageFileType != "png"
      && $imageFileType != "gif") {
        return 3;  //Il file non rispetta i formati accettati
      }


      if (imagewebp($imgResource, $finalTarget, 90)) {
        return 0;  //L'upload è andato a buon fine
      } else {
        return 4;  //Errore generico
      }
    }

    //Restituisce il percorso preciso per un'immagine di sfondo (di un blog o di un post)
    public static function getBgPicPath($mode, $bgname){
      $folderPath = "";
      $bgname = str_replace(" ", "+", urldecode($bgname));
      switch ($mode) {
        case 'blog':
        $folderPath = "assets/img/blogs/bg_blog/";
        break;
        case 'post':
        $folderPath = "assets/img/blogs/bg_post/";
        break;
        default:
        return false;
        break;
      }
      $picPath = glob($folderPath . $bgname . "_*.webp");
      if (!empty($picPath)){
        return $picPath[0];
      }
      return "";
    }

    //Cancella un'immagine di sfondo (di un blog o di un post)
    public static function deleteOldPics($mode, $bgname){
      $bgname = str_replace(" ", "+", urldecode($bgname));
      $folderPath = "";
      switch ($mode) {
        case 'blog':
        $folderPath = "assets/img/blogs/bg_blog/";
        break;
        case 'post':
        $folderPath = "assets/img/blogs/bg_post/";
        break;
        default:
        return false;
        break;
      }
      $oldPics = glob($folderPath . $bgname . "_*.webp");
      foreach ($oldPics as $pic) {
        if(!unlink($pic)){
          return false;
        }
      }
    }

  }

  ?>
