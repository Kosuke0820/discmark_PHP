<?php
/** 共通で使うものを別ファイルにしておきましょう。*/

//関数
function ownerChk(){
    $dropdown = '';    
    if($_SESSION["kanri_flg"] == 1){
        $dropdown .= '<a href="discmark_user_list.php" class="text-dark"><button class="dropdown-item" type="button"><span class="fa fa-list-ul"></span>　User List</button></a>';
        $dropdown .= '<div class="dropdown-divider"></div>';
    }
    return $dropdown;
}

function ssidChk(){
if(!isset($_SESSION["chk_ssid"]) || $_SESSION['chk_ssid'] != session_id()){
    exit('LoginError');
}else{
    session_regenerate_id(true);
    $_SESSION['chk_ssid'] = session_id();
  }
}

//DB接続関数（PDO）
function db_con(){
    $serverurl = $_SERVER["HTTP_HOST"];
    if($serverurl == 'localhost'){
      $dsn = 'mysql:dbname=gs_db;charset=utf8;host=localhost';
      $username = 'root';
      $password = '';
    }else{
        $dsn = 'mysql:dbname=yamashita-ksk_gs_db;charset=utf8;host=mysql425.db.sakura.ne.jp';    
        $username = 'yamashita-ksk';
        $password = 'kou-08201893';
    }
    //DB接続
    try{
        $pdo = new PDO($dsn,$username,$password);
        return $pdo;
    }catch (PDOException $e) {
        exit('DBConnectError:'.$e->getMessage());
      }
}

//SQL処理エラー
function queryError($stmt){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

/**
* XSS
* @Param:  $str(string) 表示する文字列
* @Return: (string)     サニタイジングした文字列
*/
function h($str){
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}


?>