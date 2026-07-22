# 手順書

# 起動方法  <br>
1.git cloneでリポジトリ内容を持ってきます。<br>
  以下コードを実行します。<br>
`docker compose up`

<br>

2.MySQLサーバーにmysqlコマンドで接続します。<br>
  以下コードを実行します。<br>
`docker compose exec mysql mysql example_db`

<br>

3.テーブルを作成します。<br>
  以下コードを実行します。<br>
```
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
<br>

4.WebブラウザからサイトのURLを検索します。<br>
  `http://”ec2インスタンスのパブリックIPアドレス”/bbsimagetest.php`
