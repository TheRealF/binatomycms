<?php
require_once 'lib/htmlpurifier/library/HTMLPurifier.auto.php';

class InputChecker {

  public static function isHexColor($input){
    return preg_match("/^#[0-9A-F]{6}$/i", $input);
  }


  public static function isStringWithSpaces($input){
    return preg_match("/^[a-zA-Z\s]+$/", $input);
  }

  public static function isStringAlphanumericWithSpaces($input){
    return preg_match("/^(?!\s*$)[a-zA-Z0-9\s]+$/", $input);

  }

  //Italian => con accenti
  public static function isItalianStringWithSpaces($input){
    return preg_match("/^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9\s]+$/", $input);
  }

  public static function isItalianString($input){
    return preg_match("/^[a-zàèéìóòùA-ZÀÈÉÌÓÒÙ0-9]+$/", $input);
  }

  public static function isNumber($input){
    return is_numeric($input);
  }


  public static function purifyHTML($input){
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($input);
  }

}

?>
