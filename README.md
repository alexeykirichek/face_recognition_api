# face_recognition

** АПИ для модуля распознавания лиц от ageitgey (https://github.com/ageitgey/face_recognition) **


### Примерный порядок установки и настройки для ОС Debian 10 (пользователь root)

apt-get update && apt-get upgrade

apt-get install git cmake g++ libjpeg-dev zlib1g-dev apache2 php libapache2-mod-php
apt install -y python3-pip

cd /var/
git clone https://github.com/alexeykirichek/face_recognition

cd face_recognition/
git clone https://github.com/davisking/dlib.git

cd dlib/
mkdir build; cd build; cmake ..; cmake --build .

cd ..
python3 setup.py install

pip3 install Pillow face_recognition

### Настройка Apache:
- порт (/etc/apache2/ports.conf)
- домен (DocumentRoot /var/face_recognition/public)
- сертификат (Let's Encrypt)

### Настройка прав:
chown -R www-data:www-data /var/face_recognition/images/
chown -R www-data:www-data /var/face_recognition/logs/
chown -R www-data:www-data /var/face_recognition/public/

### Добавление ссылок на папки и файлы:
ln -s /var/face_recognition/logs /var/face_recognition/public/logs
ln -s /var/face_recognition/images /var/face_recognition/public/images
ln -s /var/face_recognition/compare.py /var/face_recognition/public/compare.py
ln -s /var/face_recognition/verification.py /var/face_recognition/public/verification.py

** Включаем модуль headers и разрешаем кроссдоменные запросы на стороне сервера (a2enmod headers, Header set Access-Control-Allow-Origin "*") **

** Отправляем запросы на https://<ДОМЕН>:<ПОРТ>/api/ **
