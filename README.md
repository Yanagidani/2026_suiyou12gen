# 手順書

# 起動及び作成方法  <br>
1.gitcloneをしてリポジトリの内容を持ってきます。 <br>
  以下コードを実行します。<br>
```bash
sudo yum install vim git -y
git clone <https://github.com/Yanagidani/2026_suiyou12gen.git>
cd 2026_suiyou12gen
```

<br>


---
2.screenをインストールし・screenの移動と仕様を変更します。<br>
  以下コードを実行します。<br>
```bash
sudo yum install screen -y
```

  screenを起動します。<br>

```bash
screen
```


  screenの仕様を変更します。<br>
```bash
vim ~/.vimrc
```


  このファイルを編集し、vimを使いやすいように設定をします。


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


  一度exitでログアウトして入り直し設定を反映させる


---
4.Docker Composeをインストールし・起動します。<br>
  以下コードを実行します。<br>
```bash
mkdir -p ~/.docker/cli-plugins
ARCH=$(uname -m | sed 's/x86_64/amd64/;s/aarch64/arm64/')
BUILDX_URL=$(curl -s https://api.github.com/repos/docker/buildx/releases/latest | grep "browser_download_url.*linux-$ARCH" | cut -d '"' -f 4)
curl -L $BUILDX_URL -o ~/.docker/cli-plugins/docker-buildx
chmod +x ~/.docker/cli-plugins/docker-buildx
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
---
5.PHPからMySQLサーバーに接続します。<br>
  以下コードを実行します。<br>
```bash
docker compose exec mysql mysql example_db
```

<br>

---
6.テーブルを作成し、カラムを追加します。<br>
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
7.WebブラウザからサイトのURLを検索します。<br>
```bash
http://”ec2インスタンスのパブリックIPアドレス”/bbsimagetest.php
```

