// 知識ゼロから2か月のインターンシップで作った制作物（web掲示板）です。HTML、PHP、SQLを使用しています。

<?php

$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード名';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// 日時
$date = date("Y/n/j G:i:s");

// テーブル指定[ test2 ]
$sql = "CREATE TABLE IF NOT EXISTS test5"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "date DATETIME"
.");";
$stmt = $pdo->query($sql);

// パスワード
$pass = "pass";

// 投稿機能
if(isset($_POST["submit"]) && empty($_POST["Eedit"]) && ($_POST["Tps"] == $pass)){

$sql = $pdo -> prepare("INSERT INTO test5 (name, comment, date) VALUES (:name, :comment, :date)");
$sql -> bindParam(':name', $name, PDO::PARAM_STR);
$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
$sql -> bindParam(':date', $date, PDO::PARAM_STR);
$name = $_POST["name"];
$comment = $_POST["comment"];
$sql -> execute();

}


// 削除機能
if(isset($_POST["delete"]) && ($_POST["Dps"] == $pass)){

    if(!empty($_POST["deleteNo"])){
    
    $deleteNo = $_POST["deleteNo"];
    $id = $deleteNo;
    $sql = 'delete from test5 where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    }
}


// 編集機能

if(isset($_POST["edit"]) && ($_POST["Eps"] == $pass)){

$editNo = $_POST["editNo"];

$id = $editNo ; // idがこの値のデータだけを抽出したい、とする
$sql = 'SELECT * FROM test5 WHERE id=:id ';
$stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
$stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
$stmt->execute();                             // ←SQLを実行する。
$results = $stmt->fetchAll(); 
    
    foreach ($results as $row){
    $newnum = $row['id'];
    $newname = $row['name'];
    $newcomment = $row['comment'];
    }
    
}

// 編集からの投稿

if(isset($_POST["submit"]) && !empty($_POST["Eedit"]) && ($_POST["Tps"] == $pass)){

$Eedit = $_POST["Eedit"];

$id = $Eedit;
$name = $_POST["name"];
$comment = $_POST["comment"];
$sql = 'UPDATE test5 SET name=:name,comment=:comment WHERE id=:id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':name', $name, PDO::PARAM_STR);
$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();


}

// SELECT文：入力したデータレコードを抽出し、表示する
$sql = 'SELECT * FROM test5';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
    
    foreach ($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].',';
    echo $row['date'];
    echo "<hr>";
    }

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>簡易掲示板</title>
  </head>

  <body>
<form action="" method="post">
          【投稿】<br>
          <input type="text" name="name" placeholder="名前" value="<?php if(isset($newname)){echo $newname;} ?>"><br><br>
          <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($newcomment)){echo $newcomment;} ?>"><br><br>
          <input type="text" name="Tps" placeholder="パスワード"><br><br>
          <input type="submit" name="submit" value = "送信">
          <br><br><br>
          
          【削除】<br>
          <input type="text" name="deleteNo" placeholder="削除対象番号"><br><br>
          <input type="text" name="Dps" placeholder="パスワード"><br><br>
		  <input type="submit" name="delete" value="削除">
		  <br><br><br>
		  
		  【編集】<br>
		  <input type="text" name="editNo" placeholder="編集対象番号"><br><br>
		  <input type="text" name="Eps" placeholder="パスワード"><br><br>
          <input type="submit" name="edit" value="編集">
          <input type="hidden" name="Eedit" value="<?php if(!empty($editNo)){echo $editNo;} ?>">
          <br><br>
      </form>
    </body> 
</html>
