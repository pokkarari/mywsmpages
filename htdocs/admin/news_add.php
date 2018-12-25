<?php
require_once '../util.inc.php';
require_once '../db.inc.php';

$title = "";
$message = "";
$posted =date("Y-m-d");
echo $posted;


// キャンセルボタン
if(isset($_POST["cancel"])){
    header("Location: index.php");
    exit;
}
// データの追加
if(isset($_POST["add"])){
    // ①バリデーション
    $posted = $_POST["posted"];
    $title = $_POST["title"];
    $message = $_POST["message"];

    $isValidated = true;

    if($posted === ""){
        // 日付がからの場合は、falseにはせずに、現在の日付を入れる
        $posted = date("Y-m-d");
    }
    elseif(!preg_match("/^\d{4}-\d{2}-\d{2}$/", $posted)){
        $isValidated = false;
        $errorPosted = "※日付は「0000-00-00」で入力してください";
    }

    if($title === ""){
        $isValidated = false;
        $errorTitle = "タイトルを入力してください";
    }

    if($message === ""){
        $isValidated = false;
        $errorMessage = "お知らせを入力してください";
    }

    // ②バリデーションOKの場合
    if($isValidated == true){
//         echo "バリデーションOK";
        // 1.データの追加
        try {
            // DB接続
            $pdo = db_init();

            //データの追加
            $sql = "INSERT INTO news
                    (posted, title, message)
                    VALUES
                    (?, ?, ?)";
            $stmt = $pdo -> prepare($sql);
            $stmt -> execute([$posted, $title, $message]);

        }
        catch(PDOException $e){
                echo $e -getMessage();
        }
        // 2.完了ページへ遷移
        header("Location: news_add_done.php");
        exit;
    }

}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>お知らせの追加 | Crescent Shoes 管理</title>
<link rel="stylesheet" href="css/admin.css">
</head>
<body>
<header>
  <div class="inner">
    <span><a href="index.php">Crescent Shoes 管理</a></span>
    <div id="account">
      admin
      [ <a href="logout.php">ログアウト</a> ]
    </div>
  </div>
</header>
<div id="container">
  <main>
    <h1>お知らせの追加</h1>
    <p>情報を入力し、「追加」ボタンを押してください。</p>
    <form action="" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th class="fixed">日付(任意)</th>
        <td>
        <?php if(isset($errorPosted)): ?>
          <div class="error"><?php h($errorPosted); ?></div>
         <?php endif; ?>
          <input type="date" name="posted" value="<?php h($posted); ?>">
        </td>
      </tr>
      <tr>
        <th class="fixed">タイトル</th>
        <td>
         <?php if(isset($errorTitle)): ?>
          <div class="error"><?php h($errorTitle); ?></div>
         <?php endif; ?>
          <input type="text" name="title" size="80" value="<?php h($title); ?>">
        </td>
      </tr>
      <tr>
        <th class="fixed">お知らせの内容</th>
        <td>
          <?php if(isset($errorMessage)): ?>
          <div class="error"><?php h($errorMessage); ?></div>
         <?php endif; ?>
          <textarea name="message" cols="80" rows="5"><?php h($message); ?></textarea>
        </td>
      </tr>
      <tr>
        <th class="fixed">画像(任意)</th>
        <td>
          <input type="file" name="image">
          <div>画像は64x64ピクセルで表示されます</div>
        </td>
      </tr>
    </table>
    <p>
      <input type="submit" name="add" value="追加">
      <input type="submit" name="cancel" value="キャンセル">
    </p>
    </form>
  </main>
  <footer>
    <p>&copy; Crescent Shoes All rights reserved.</p>
  </footer>
</div>
</body>
</html>