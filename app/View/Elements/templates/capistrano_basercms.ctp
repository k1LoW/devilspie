# -*- coding: utf-8 -*-
# candycane
role :web, *%w[<?php echo $hostname ?>]

set :repository,  "https://github.com/basercms/basercms.git"

set :use_sudo, false
default_run_options[:pty] = true
set :user, "devil"
set :ssh_options, {
  :keys_only => true,
  :auth_methods => %w(publickey),
  :keys => ["<?php echo $apppath; ?>Config/devil_rsa"],
}

set :deploy_to, "/var/www/html/basercms"
set :deploy_via, :copy

desc "データベース接続設定をアップロードします"
task :upload_databaseconfig, roles => :web do
  upload File.dirname(__FILE__) + "/#{stage}/database.php", "#{deploy_to}/current/app/config/", :via => :scp
end

desc "extra"
task :setup_extra, roles => :web do
  run <<-CMD
    #{sudo} chmod 777 #{deploy_to}/current/app/config
  CMD
  run <<-CMD
    #{sudo} chmod 777 #{deploy_to}/current/app/config/core.php
  CMD
  run <<-CMD
    #{sudo} chmod 777 #{deploy_to}/current/app/webroot/files
  CMD
  run <<-CMD
    #{sudo} chmod 777 #{deploy_to}/current/app/webroot/themed
  CMD
end