---
- hosts: all
  user: devil
  sudo: true
  tasks:
  - name: Install PHP
    action: yum pkg=$item state=latest
    with_items:
      - php
      - php-pear
      - php-devel
      - php-dom
      - php-mbstring
      - php-mysql
      - php-pgsql
      - php-gd
  - name: Set date.timezone
    action: lineinfile dest=/etc/php.ini regexp='^;date.timezone =' line='date.timezone = Asia/Tokyo'
  - name: Restart Apache
    action: service name=httpd state=restarted
  - name: Set permission
    action: file path=/var/www/html owner=root group=root mode=0777 force=yes state=directory