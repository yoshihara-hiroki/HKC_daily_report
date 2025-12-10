<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## デバッグ方法
開発環境の移行完了、おめでとうございます！
ご要望の通り、新しいPCでプロジェクトをセットアップする際の手順書を作成しました。

これを `README.md` に追記するか、`SETUP.md` などの別ファイルとして保存しておくと、チームメンバーや未来の自分への共有に便利です。

-----

# 開発環境セットアップ手順 (Docker / Nginx + PHP-FPM版)

本プロジェクトは **Docker Compose** を使用した独自環境（Nginx + PHP-FPM）で構築されています。
Laravel Sail は使用していません。

## 1\. 前提条件

  * **Docker Desktop** がインストールされ、起動していること。
  * Windowsの場合、**WSL2** 環境での実行を推奨します。
  * Git がインストールされていること。

## 2\. セットアップ手順

ターミナル（WSL2推奨）を開き、以下の手順を実行してください。

### 2-1. リポジトリのクローン

```bash
git clone https://github.com/yoshihara-hiroki/HKC_daily_report.git
cd HKC_daily_report
```

### 2-2. 環境変数の設定

`.env.example` をコピーして `.env` を作成し、Docker環境用に修正します。

```bash
cp .env.example .env
```

`.env` ファイルを開き、データベース等の接続先を Docker サービス名に合わせて修正してください。

```ini
APP_URL=http://localhost

# DBホストを '127.0.0.1' ではなく 'mysql' (サービス名) に設定
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=hkc_daily_report
DB_USERNAME=sail
DB_PASSWORD=password

# Redisホストを 'redis' (サービス名) に設定
REDIS_HOST=redis
```

### 2-3. Dockerコンテナの起動

イメージをビルドし、コンテナをバックグラウンドで起動します。

```bash
docker compose up -d --build
```

### 2-4. PHP依存関係のインストールと初期設定

コンテナ内（`app` サービス）でコマンドを実行します。

```bash
# Composerパッケージのインストール
docker compose exec app composer install

# パーミッションの調整（必要な場合）
docker compose exec app chmod -R 777 storage bootstrap/cache

# アプリケーションキーの生成
docker compose exec app php artisan key:generate

# シンボリックリンクの作成
docker compose exec app php artisan storage:link
```

### 2-5. データベースの構築

マイグレーションとシーダー（初期データ投入）を実行します。

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### 2-6. フロントエンドのビルドと起動

Vite 開発サーバーを起動します。このコマンドを実行するとターミナルが占有されるため、開発中は開きっぱなしにするか、別のターミナルを開いてください。

```bash
# Nodeパッケージのインストール
docker compose exec app npm install

# 開発サーバーの起動
docker compose exec app npm run dev
```

## 3\. アクセス確認

ブラウザで以下のURLにアクセスして動作を確認してください。

  * **アプリケーション**: [http://localhost](https://www.google.com/search?q=http://localhost)
  * **PhpMyAdmin**: [http://localhost:8080](https://www.google.com/search?q=http://localhost:8080)
      * User: `sail`
      * Password: `password`

-----

## 4\. よく使うコマンド一覧

Sail コマンドの代わりに `docker compose` コマンドを使用します。

| 操作 | コマンド |
| :--- | :--- |
| **コンテナ起動** | `docker compose up -d` |
| **コンテナ停止** | `docker compose down` |
| **Artisanコマンド** | `docker compose exec app php artisan <コマンド>` |
| **Composer** | `docker compose exec app composer <コマンド>` |
| **npmインストール** | `docker compose exec app npm install` |
| **Vite起動** | `docker compose exec app npm run dev` |
| **DB接続(CLI)** | `docker compose exec mysql mysql -u sail -ppassword` |

### Tips: エイリアスの設定

毎回 `docker compose exec app ...` と打つのは長いため、`.bashrc` や `.zshrc` にエイリアスを登録しておくと便利です。

```bash
# 例: 'd' コマンドで docker compose exec app を呼び出す
alias d='docker compose exec app'
```

**使用例:**

  * `d php artisan migrate`
  * `d composer install`