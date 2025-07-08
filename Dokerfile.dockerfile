FROM php:8.1-fpm

# نصب افزونه‌های لازم برای PDO و MySQL
RUN docker-php-ext-install pdo_mysql

# نصب nginx
RUN apt-get update && apt-get install -y nginx

# کپی فایل‌های پروژه
COPY . /var/www/html
COPY nginx.conf /etc/nginx/sites-available/default

# تنظیمات nginx
RUN echo "server { \
  listen 80; \
  root /var/www/html/public; \
  index index.html index.php; \
  location / { \
  try_files \$uri \$uri/ /index.php?\$query_string; \
  } \
  location ~ \.php\$ { \
  include fastcgi_params; \
  fastcgi_pass 127.0.0.1:9000; \
  fastcgi_index index.php; \
  fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name; \
  } \
  location ~* \.(css|js|png|jpg|jpeg|gif|ico|woff|woff2|ttf|eot|svg)\$ { \
  try_files \$uri =404; \
  } \
  }" > /etc/nginx/sites-available/default

# راه‌اندازی nginx و php-fpm
CMD service nginx start && php-fpm