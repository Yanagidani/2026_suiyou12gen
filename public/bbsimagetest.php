<?php
$dbh = new PDO('mysql:host=mysql;dbname=example_db', 'root', '');

$error_message = '';
$form_body = '';

if (isset($_POST['body'])) {
   $form_body = $_POST['body'];
   $image_filename = null;
   $is_valid = true;

   if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {
     $mime_type = mime_content_type($_FILES['image']['tmp_name']);
     
     if (preg_match('/^image\//', $mime_type) !== 1) {
       $error_message = 'このファイルは使用できません';
       $is_valid = false;
     }

     if ($is_valid) {
       $pathinfo = pathinfo($_FILES['image']['name']);
       $extension = $pathinfo['extension'];
       $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.' . $extension;
       $filepath =  '/var/www/upload/image/' . $image_filename;
       move_uploaded_file($_FILES['image']['tmp_name'], $filepath);
     }
   }

   if ($is_valid) {
     $insert_sth = $dbh->prepare("INSERT INTO bbs_entries (body, image_filename) VALUES (:body, :image_filename)");
     $insert_sth->execute([
       ':body' => $_POST['body'],
       ':image_filename' => $image_filename,
     ]);
     header("HTTP/1.1 302 Found");
     header("Location: ./bbsimagetest.php");
     return;
   }
}

$select_sth = $dbh->prepare('SELECT * FROM bbs_entries ORDER BY created_at DESC');
$select_sth->execute();
?>

<form method="POST" action="./bbsimagetest.php" enctype="multipart/form-data">
   <textarea name="body" required><?= htmlspecialchars($form_body) ?></textarea>
   <div style="margin: 1em 0; display: flex; align-items: center; gap: 10px;">
     <input type="file" accept="image/*" name="image" onchange="if(this.files[0] && this.files[0].size >= 5 * 1024 * 1024){ alert('ファイルサイズが5MBを超えています。'); this.value = ''; }">
     <?php if (!empty($error_message)): ?>
       <span style="color: red; font-weight: bold; font-size: 0.9em;"><?= htmlspecialchars($error_message) ?></span>
     <?php endif; ?>
   </div>
   <button type="submit">送信</button>
</form>

<hr>

<?php foreach($select_sth as $entry): ?>
   <dl style="margin-bottom: 1em; padding-bottom: 1em; border-bottom: 1px solid #ccc;">
     <dt>ID</dt>
     <dd><?= $entry['id'] ?></dd>
     <dt>日時</dt>
     <dd><?= $entry['created_at'] ?></dd>
     <dt>内容</dt>
     <dd>
       <?= nl2br(htmlspecialchars($entry['body'])) ?>
       <?php if(!empty($entry['image_filename'])): ?>
       <div>
         <img src="/image/<?= $entry['image_filename'] ?>" style="max-height: 10em;">
       </div>
       <?php endif; ?>
     </dd>
   </dl>
<?php endforeach ?>
