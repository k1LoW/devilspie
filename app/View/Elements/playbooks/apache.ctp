---
- hosts: all
  vars:
    docroot: /var/www/html/
  user: devil
  sudo: true
  tasks:
  - name: Install Apache
    action: yum pkg=$item state=latest
    with_items:
      - httpd
      - httpd-devel
      - mod_ssl
  - name: Config Apache
    action: template src=<?php echo $apppath; ?>/View/Elements/templates/apache_virtualhost.conf dest=/etc/httpd/conf.d/virtualhost.conf
    notify:
    - Restart Apache
  - name: On Apache
    action: command /sbin/chkconfig httpd on
  - name: Check Apache
    action: service name=httpd state=started
  - name: Set permission
    action: file path=/var/www/html owner=root group=root mode=0777 force=yes state=directory
  handlers:
    - name: Restart Apache
      action: service name=httpd state=restarted