<?php
    //DB接続設定
    $dsn = 'mysql:dbname=tb2*****db;host=localhost';
    $user = 'tb-2*****';
    $password = 'zY********';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    

    // テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS mission5_1"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date datetime,"
    . "password char(32)"
    .");";
    $stmt = $pdo->query($sql);

    $sql ='SHOW CREATE TABLE mission5_1';
    $result = $pdo -> query($sql);
    foreach ($result as $row){
        echo $row[1];
    }
    echo "<hr>";


    $date = date("Y/m/d H:i:s");
        
    $editName = "";
    $editComment = "";
    $editPassword = "";
        
    $hidden = "";

    // 送信ボタンが押されたとき
    if (isset($_POST["send"])){
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $password = $_POST["password"];
            
        // 名前、コメント、パスワードが空じゃなかったとき
        if (!empty($name) && !empty($comment) && !empty($password)){
            $number = $_POST["hidden"];

            //編集欄
            if (!empty($number)){
                $sql = 'UPDATE mission5_1 SET name=:name, comment=:comment, date=:date WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                $stmt -> bindParam(':id', $number, PDO::PARAM_INT);
                $stmt -> execute();

            //新規欄
            } else {
                $sql = $pdo -> prepare("INSERT INTO mission5_1 (name, comment, password, date) VALUES (:name, :comment, :password, :date)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> execute();
            }
        }

    //削除
    } else if(isset($_POST["delete"])){
        $deleteNo = $_POST["deleteNo"];
        $deletePass = $_POST["deletePass"];
        if (!empty($deleteNo) && !empty($deletePass)){
            $sql = 'DELETE FROM mission5_1 WHERE id=:id AND password=:deletePass';
            $stmt = $pdo -> prepare($sql);
            $stmt -> bindParam(':id', $deleteNo, PDO::PARAM_INT);
            $stmt -> bindParam(':deletePass', $deletePass, PDO::PARAM_STR);
            $stmt -> execute();
        }

    //編集
    } else if(isset($_POST["edit"])){
        $editNo = $_POST["editNo"];
        $editPass = $_POST["editPass"];
        if (!empty($editNo) && !empty($editPass)) {
            $sql = 'SELECT * FROM mission5_1 WHERE id=:id AND password=:editPass';
            $stmt = $pdo -> prepare($sql);
            $stmt->bindParam(':id', $editNo, PDO::PARAM_INT);
            $stmt->bindParam(':editPass', $editPass, PDO::PARAM_STR);
            $stmt -> execute();
            $row = $stmt->fetch();
            $editName = $row['name'];
            $editComment = $row['comment'];
            $editPassword = $row['password'];
            $hidden = $editNo;
        }
    }
?>      

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    <h1>好きな食べ物は何？？</h1>
    <form action="" method="post">
        <div>
            <input type="text" name="name" value="<?php echo $editName; ?>" placeholder="名前"><br>
            <input type="text" name="comment" value="<?php echo $editComment; ?>" placeholder="コメント"><br>
            <input type="text" name="password" value="<?php echo $editPassword; ?>" placeholder="パスワード">
            <input type="hidden" name="hidden" value="<?php echo $hidden; ?>">
            <input type="submit" name="send"><br><br>
        </div>
        <div>
            <input type="number"  name="deleteNo" placeholder="削除番号"><br>
            <input type="text" name="deletePass" placeholder="パスワード">
            <input type="submit" name="delete" value="削除"><br><br>
        </div>
        <div>
            <input type="number"  name="editNo" placeholder="編集番号"><br>
            <input type="text" name="editPass" placeholder="パスワード">
            <input type="submit" name="edit" value="編集"><br>
        </div>
    </form>
    <?php
        $sql = 'SELECT * FROM mission5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();

        if(count($results) >= 1) {
            // データを出力
            foreach ($results as $row) {
                echo $row['id']." ";
                echo $row['name']." ";
                echo $row['comment']." ";
                echo $row['date']."<br>";
                }
        }
        // １個もなかったら
        else {
            echo "データがありません";
        }
    ?>
</body>
</html>