FROM debian:wheezy
RUN apt-get update && apt-get install -y wget curl ca-certificates procps locales zip apt-transport-https git sudo make libpng-dev
RUN curl -O https://www.dotdeb.org/dotdeb.gpg && sudo apt-key add dotdeb.gpg
RUN echo 'deb http://packages.dotdeb.org wheezy all' > /etc/apt/sources.list.d/php.list
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash -
RUN apt-get update && apt-get install -y nodejs php-pear php5 php5-cli php5-common php5-curl php5-dev php5-gd php5-imagick php5-imap php5-intl php5-mcrypt php5-apcu php5-sqlite php5-fpm php5-xdebug php5-mysqlnd
RUN wget https://repo.percona.com/apt/percona-release_0.1-4.wheezy_all.deb -O /tmp/percona.deb
RUN dpkg -i /tmp/percona.deb && rm /tmp/percona.deb && apt-get update
RUN apt-get install -y percona-server-client-5.6 php5-mysql
ADD configs/xdebug.ini /etc/php5/mods-available/xdebug.ini
ADD configs/xdebug-cli /usr/local/bin/xdebug-cli
RUN mkdir /.macos_conigs
ADD configs/mac_xdebug.ini /.macos_conigs/mac_xdebug.ini
ADD configs/mac_xdebug-cli /.macos_conigs/mac_xdebug-cli
RUN chmod a+x /usr/local/bin/xdebug-cli
ADD configs/www.conf /etc/php5/fpm/pool.d/www.conf
ADD configs/start /usr/local/bin/start
RUN chmod a+x /usr/local/bin/start
RUN echo 'www-data ALL=(ALL) NOPASSWD: ALL' > /etc/sudoers.d/10_www_data
RUN mkdir /app && chown www-data:www-data /app
RUN mkdir /var/www
ADD configs/bash-history-user /var/www/.bashrc
ADD configs/bash-history-root /root/.bashrc
RUN cd /tmp && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN cd /tmp && php -r "if (hash_file('SHA384', 'composer-setup.php') === '48e3236262b34d30969dca3c37281b3b4bbe3221bda826ac6a9a62d6444cdb0dcd0615698a5cbe587c3f0fe57a54d8f5') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN cd /tmp && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN cd /tmp && php -r "unlink('composer-setup.php');"
RUN npm install -g gulp
USER www-data
WORKDIR /app
CMD sudo /usr/local/bin/start
