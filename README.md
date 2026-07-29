# 手順書

# 起動及び作成方法  <br>
1.vimをインストールする <br>
  以下コードを実行します。<br>
```bash
sudo yum install vim -y
```

<br>

---
2.screenをインストールし、screen内に移動します。<br>
  以下コードを実行します。<br>
```bash
sudo yum install screen -y
```


```bash
screen
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

---


5.作業用ディレクトリ・compose.ymlを作成し起動します。<br>
以下コードを実行します。<br>


作業用ディレクトリの作成と移動<br>
```bash
mkdir dockertest
cd dockertest
```


compose.ymlの作成を作成<br>
```bash
vim compose.yml
```

内容<br>

<https://github.com/Yanagidani/2026_suiyou12gen/blob/main/compose.yml>


  起動をします。<br>
```bash
docker compose up
```


起動をさせたまま次のステップに進みます。

<br>

---
6.nginxを使用しWebに配信する。<br>
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
server {
    listen       0.0.0.0:80;
    server_name  _;
    charset      utf-8;

    root /var/www/public;

    location ~ \.php$ {
        fastcgi_pass  php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include       fastcgi_params;
    }

    location /image/ {
        root /var/www/upload;
    }
}
```


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
<https://github.com/Yanagidani/2026_suiyou12gen/blob/main/Dockerfile>


<br>

---
9.掲示板サイトのファイルを作成します。<br>
  以下コードを実行します。<br>
```bash
vim public/bbsimagetest.php
```


  内容<br>
<https://github.com/Yanagidani/2026_suiyou12gen/blob/main/public/bbsimagetest.php>


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

