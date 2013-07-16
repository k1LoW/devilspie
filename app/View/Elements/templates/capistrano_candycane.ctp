# -*- coding: utf-8 -*-
# candycane
role :web, *%w[<?php echo $hostname ?>]

set :repository,  "https://github.com/yandod/candycane.git"

set :use_sudo, false
default_run_options[:pty] = true
set :user, "devil"
set :ssh_options, {
  :keys_only => true,
  :auth_methods => %w(publickey),
  :keys => ["<?php echo $apppath; ?>Config/devil_rsa"],
}

set :deploy_to, "/var/www/html/candycane"
set :deploy_via, :copy

desc "extra"
task :setup_extra, roles => :web do
  run <<-CMD
    #{sudo} chmod -R 777 #{deploy_to}/current/app/Config
  CMD
  run <<-CMD
    #{sudo} chmod -R 777 #{deploy_to}/current/app/Plugin
  CMD
  run <<-CMD
    #{sudo} mysql -u root -e'DROP DATABASE IF EXISTS <?php echo $dbname; ?>'
  CMD
  run <<-CMD
    #{sudo} mysql -u root -e'CREATE DATABASE IF NOT EXISTS <?php echo $dbname; ?>'
  CMD
  run <<-CMD
    #{sudo} mysql -u root <?php echo $dbname; ?> < #{deploy_to}/current/app/Config/sql/mysql.sql
  CMD
end
