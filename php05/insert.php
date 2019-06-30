<?php
session_start();
//1. POSTデータ取得
$name   = filter_input( INPUT_POST, "name" );
$email  = filter_input( INPUT_POST, "email" );
$naiyou = filter_input( INPUT_POST, "naiyou" );
$age    = filter_input( INPUT_POST, "age" );

//2. DB接続します
if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
    
    $file_name = $_FILES["upfile"]["name"];//ファイル名取得
    $tmp_path  = $_FILES["upfile"]["tmp_name"];//一時保管場所

    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name = date("YmdHis").md5(session_id()) . "." . $extension;
    // echo $file_name;
    // exit();
    // FileUpload [--Start--]
    $img="";
    $file_dir_path = "upload/".$file_name;
    if ( is_uploaded_file( $tmp_path ) ) {
        if ( move_uploaded_file( $tmp_path, $file_dir_path ) ) {
            chmod( $file_dir_path, 0644 );
            // $img = '<img src="'.$file_dir_path.'">';
        } else {
            // echo "Error:アップロードできませんでした。";
        }
    }

    
 }else{
    //  $img = "画像が送信されていません";
 }

//include "../../includes/funcs.php";
include "funcs.php";
$pdo = db_con();



//３．データ登録SQL作成
$sql = "INSERT INTO gs_an_table(name,email,naiyou,indate,age,img)VALUES(:name,:email,:naiyou,sysdate(),:age,:img)";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':name', $name, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':email', $email, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':naiyou', $naiyou, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':age', $age, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':img', $file_name, PDO::PARAM_STR); //Integer（数値の場合 PDO::PARAM_INT)

$status = $stmt->execute();

//４．データ登録処理後
if ($status == false) {
    sqlError($stmt);
} else {
    //５．index.phpへリダイレクト
    header("Location: index.php");
    exit;
}
?>
    <script>
        $(function() {
            //この中に処理を記述 開始

            //Ajax（非同期通信）//AJAX通信(ver1.5...)
            $.ajax({
                type: 'POST', //GET,POST
                url: 'ajax.php', //通信先URL
                data: { //Dataプロパティはデータを送信（渡す）役目
                    id: 1,
                    mode: 'hoge',
                    type: 'entry',
                    sleep: 4 //Timeoutテスト用
                },
                dataType: 'json', //text, html, xml, json, jsonp, script
                timeout: 3000,
                success: function(data) {
                    $("#ajax_status").html(data);
                    console.log(data);
                },
                error: function(error) {
                    console.log(error); //戻り値Allオブジェクト
                },
                complete: function() {
                    //成功＆エラー処理後に必ず実行したい処理を記述する
                    $("body").append("完了"); //戻り値Allオブジェクト
                }
            });


            //この中に処理を記述 終了
        });
    </script>
