<?php

$command = 'bash /var/www/devilspie/app/Console/shells/ansible.sh 10.0.0.55 vagrantroot';
exec($command, $output, $result);