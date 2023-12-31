# Laravel10 Filament3

-   WEB https://github.com/MilesWuCode/nuxt3-demo
-   CMS https://github.com/MilesWuCode/laravel10-filament3
-   API https://github.com/MilesWuCode/laravel10-api

## 初始化

```sh
# 安裝composer套件
composer install

# 環境變數
cp .env.example .env

# shell加入sail
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'

# database...wip
mysql -u root -p
CREATE DATABASE IF NOT EXISTS `laravel10_filament3`;
GRANT ALL PRIVILEGES ON  `laravel10_filament3`.* TO 'sail'@'%';

# 容器啓動
sail up -d

# minio新增bucket/Access Policy更改public
AWS_*=...

# appkey
sail php artisan k:g

# 使用laravel10-api專案的資料庫
# 建立資料庫/檢查帳號權限
# 資料庫遷移
sail php artisan migrate

# 新增管理員
sail php artisan make:filament-user

# 新增預設權限
sail php artisan db:seed DefaultSeeder
sail php artisan db:seed PolicySeeder

# 關閉
sail down
```

## 常用指令

```sh
# 新增管理員
php artisan make:filament-user

# 資源
php artisan make:filament-resource

# 使用軟刪除
php artisan make:filament-resource Customer --soft-deletes

# 檢視頁ViewRecord
php artisan make:filament-page ViewUser --resource=UserResource --type=ViewRecord

# 新增關聯資源(軟刪除,附加)
php artisan make:filament-relation-manager CategoryResource posts title --soft-deletes --attach

# 更新
composer update
php artisan filament:upgrade
```

## 語系檔案

```sh
php artisan vendor:publish --tag=filament-panels-translations

php artisan vendor:publish --tag=filament-actions-translations

php artisan vendor:publish --tag=filament-forms-translations

php artisan vendor:publish --tag=filament-notifications-translations

php artisan vendor:publish --tag=filament-tables-translations

php artisan vendor:publish --tag=filament-translations
```

---

-   開發時使用圖示 https://heroicons.com/
