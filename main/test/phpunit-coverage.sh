cd ../tool/db
php sql-scripts.php -t
cd -

php \
-dxdebug.mode=coverage \
-dxdebug.client_host=127.0.0.1 \
-dxdebug.client_port=9003 \
-dxdebug.start_with_request=yes \
../server/vendor/bin/phpunit --coverage-text --coverage-html ./report