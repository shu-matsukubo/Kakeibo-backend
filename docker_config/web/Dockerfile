### ベースとなるOSを選択
# phpアパッチを利用
FROM php:8.4-apache

### 環境変数周りを設定
# デフォルトのコマンド実行引数を設定
CMD ["apache2-foreground"]

# タイムゾーンを東京に
ENV TZ=Asia/Tokyo
# ドキュメントルートの設定を'/var/www/public'に
ENV APACHE_DOCUMENT_ROOT='/var/www/public/'

# Composerインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

### OS用の拡張パッケージをインストール
# 最初にアップデート（パッケージが古い可能性があるとのこと）
RUN apt-get update

# zip
RUN apt-get install -y zip

# unzip
RUN apt-get install -y unzip

# vimエディタ
RUN apt-get install -y vim

### php用の拡張パッケージをインストール（php標準）
# mysql
RUN docker-php-ext-install pdo_mysql

### ファイル記載変更などの細かい調整
# modRewrite有効化
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

# タイムゾーンの修正
RUN ln -sf  /usr/share/zoneinfo/$TZ /etc/localtime

# ドキュメントルートの修正
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf

# framework配下がないとエラーになってしまうため、作成
RUN mkdir -p /var/www/storage/framework/cache/data
RUN mkdir -p /var/www/storage/framework/sessions
RUN mkdir -p /var/www/storage/framework/views
RUN mkdir -p /var/www/storage/framework/testing
RUN chmod -R 777 /var/www/storage
