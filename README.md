# 手順書

# 起動及び作成方法  <br>
1.vimをインストールする <br>
  以下コードを実行します。<br>
`sudo yum install vim -y`

<br>

2.screenをインストールします。<br>
  以下コードを実行します。<br>
`sudo yum install screen -y`

<br>

3.Dockerのインストールと自動起動化をします。<br>
  以下コードを実行します。<br>
```
sudo yum install -y docker
sudo systemctl start docker
sudo systemctl enable docker
```
  デフォルトのユーザーをdockerグループに追加します。<br>
`sudo usermod -a -G docker ec2-user`

<br>

4.Docker Composeをインストールします。<br>
  以下コードを実行します。<br>
```
DOCKER_CONFIG=${DOCKER_CONFIG:-$HOME/.docker}
mkdir -p $DOCKER_CONFIG/cli-plugins
curl -SL https://github.com/docker/compose/releases/download/v5.1.2/docker-compose-linux-x86_64 -o $DOCKER_CONFIG/cli-plugins/docker-compose
chmod +x $DOCKER_CONFIG/cli-plugins/docker-compose
```
  インストールできたかの確認をします。<br>
`docker compose version`
  v0.34.1 もしくはそれ以上のバージョンが表示されたらOKです。<br>

  起動をします。<br>
`docker compose up`
  起動をさせたまま次のステップに進みます。

<br>

5.nginxを使用しWebに配信する。<br>
  設定ファイル用のディレクトリ・ファイル・内容の作成をします。<br>
  以下コードを実行します。<br>

<br>
  設定ファイル用のディレクトリ<br>
```
mkdir nginx
mkdir nginx/conf.d
```
<br>
  設定ファイルを作成<br>
`vim nginx/conf.d/default.conf`

<br>
  内容<br>
```
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

<br>

6.compose.yml編集します。<br>
  以下コードを実行します。<br>
`vim compose.yml`<br>
```
services:
  web:
    image: nginx:latest
    ports:
      - 80:80
    volumes:
      - ./nginx/conf.d/:/etc/nginx/conf.d/
      - ./public/:/var/www/public/
      - image:/var/www/upload/image/
    depends_on:
      - php
  php:
    container_name: php
    build:
      context: .
      target: php
    volumes:
      - ./public/:/var/www/public/
      - image:/var/www/upload/image/
  mysql:
    container_name: mysql
    image: mysql:8.4
    environment:
      MYSQL_DATABASE: example_db
      MYSQL_ALLOW_EMPTY_PASSWORD: 1
      TZ: Asia/Tokyo
    volumes:
      - mysql:/var/lib/mysql
    command: >
      mysqld
      --character-set-server=utf8mb4
      --collation-server=utf8mb4_unicode_ci
      --max_allowed_packet=4MB
volumes:
  mysql:
  image:
```

<br>

7.dockerを再起動します。<br>
  起動したままの場合はctrl+cで終了させ、以下コードを実行します。<br>
`docker compose up`

<br>


8.MySQLサーバーにmysqlコマンドで接続します。<br>
  以下コードを実行します。<br>
`vim Dockerfile`
  内容
```
FROM php:8.4-fpm-alpine AS php

RUN docker-php-ext-install pdo_mysql

RUN install -o www-data -g www-data -d /var/www/upload/image/
```

<br>

9.掲示板サイトのファイルを作成します。<br>
  以下コードを実行します。<br>
`vim public/bbsimagetest.php`
  内容<br>
```
ここに内容を記述
```


<br>

10.PHPからMySQLサーバーに接続します。<br>
  以下コードを実行します。<br>
`docker compose exec mysql mysql example_db`

<br>

11.テーブルを作成し、カラムを追加します。<br>
  以下コードを実行します。<br>
```
CREATE TABLE `bbs_entries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `body` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
```
<br>
  画像を保存するパス収納用カラムの追加<br>
```
ALTER TABLE `bbs_entries` ADD COLUMN image_filename TEXT DEFAULT NULL;
```
<br>

12.WebブラウザからサイトのURLを検索します。<br>
  `http://”ec2インスタンスのパブリックIPアドレス”/bbsimagetest.php`
