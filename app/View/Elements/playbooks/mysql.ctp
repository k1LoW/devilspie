---
- hosts: all
  user: devil
  sudo: true
  tasks:
  - name: Install MySQL
    action: yum pkg=$item state=latest
    with_items:
      - mysql
      - mysql-devel
      - mysql-server
      - MySQL-python
  - name: Config MySQL
    action: template src=<?php echo $apppath; ?>/View/Elements/templates/mysql_my.cnf dest=/etc/my.cnf
    notify:
    - Restart MySQL
  - name: On MySQL
    action: command /sbin/chkconfig mysqld on
  - name: Check MySQL
    action: service name=mysqld state=started
  - name: Create Databse
    action: mysql_db db=<?php echo $dbname; ?> state=present
  handlers:
    - name: Restart MySQL
      action: service name=mysqld state=restarted
