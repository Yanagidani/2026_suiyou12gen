# 手順書

# 起動及び作成方法  <br>
1.vimをインストールする <br>
  以下コードを実行します。<br>
```bash
sudo yum install vim -y
```

<br>

---
2.screenをインストールします。<br>
  以下コードを実行します。<br>
```bash
sudo yum install screen -y
```


---
3.Dockerのインストールと自動起動化をします。<br>
  以下コードを実行します。<br>
```bash
sudo yum install -y docker
sudo systemctl start docker
sudo systemctl enable docker
```


デフォルトのユーザーをdockerグループに追加します。<br>
```bash
sudo usermod -a -G docker ec2-user
```

<br>

---
4.Docker Composeをインストールします。<br>
  以下コードを実行します。<br>
```bash
DOCKER_CONFIG=${DOCKER_CONFIG:-$HOME/.docker}
mkdir -p $DOCKER_CONFIG/cli-plugins
curl -SL https://github.com/docker/compose/releases/download/v5.1.2/docker-compose-linux-x86_64 -o $DOCKER_CONFIG/cli-plugins/docker-compose
chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose
```


インストールできたかの確認をします。<br>
```bash
docker compose version
```


v0.34.1 もしくはそれ以上のバージョンが表示されたらOKです。<br>

  起動をします。<br>
```bash
docker compose up
```


起動をさせたまま次のステップに進みます。

<br>

---
5.nginxを使用しWebに配信する。<br>
  設定ファイル用のディレクトリ・ファイル・内容の作成をします。<br>
  以下コードを実行します。<br>


  設定ファイル用のディレクトリ<br>
```bash
mkdir nginx
mkdir nginx/conf.d
```


  設定ファイルを作成<br>
```bash
vim nginx/conf.d/default.conf
```


  内容<br>
```bash
リポジトリ内の内容を張り付け
```


---
6.compose.yml編集します。<br>
  以下コードを実行します。<br>
```bash
vim compose.yml
```



リポジトリ内の内容を張り付け


---
7.dockerを再起動します。<br>
  起動したままの場合はctrl+cで終了させ、以下コードを実行します。<br>
```bash
docker compose up
```

<br>

---
8.MySQLサーバーにmysqlコマンドで接続します。<br>
  以下コードを実行します。<br>
```bash
vim Dockerfile
```


  内容<br>

リポジトリ内の内容を張り付け


<br>

---
9.掲示板サイトのファイルを作成します。<br>
  以下コードを実行します。<br>
```bash
vim public/bbsimagetest.php
```


  内容<br>

リポジトリ内の内容を張り付け



---
10.PHPからMySQLサーバーに接続します。<br>
  以下コードを実行します。<br>
```bash
docker compose exec mysql mysql example_db
```

<br>

---
11.テーブルを作成し、カラムを追加します。<br>
  以下コードを実行します。<br>
```bash
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
<br>
  画像を保存するパス収納用カラムの追加<br>

```bash
ALTER TABLE `bbs_entries` ADD COLUMN image_filename TEXT DEFAULT NULL;
```
<br>

---
12.WebブラウザからサイトのURLを検索します。<br>
```bash
http://”ec2インスタンスのパブリックIPアドレス”/bbsimagetest.php
```

