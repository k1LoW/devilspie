# -*- coding: utf-8 -*-
require "capistrano/ext/multistage"
# require "capistrano_colors"
require "railsless-deploy"
require "rubygems"

set :application, "Devil's pie"
set :scm, :git
set :repository,  "git@github.com:fusic/csvhub.git"
set :branch, "master"

after "deploy", "setup_shared"
after "deploy", "link_tmp"
after "deploy", "link_files"
after "deploy", "change_permission"
after "deploy", "upload_databaseconfig"
after "deploy", "setup_extra"
after "deploy", "apc_clear"

desc "sharedディレクトリの必要なディレクトリ作成"
task :setup_shared, roles => :web do
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/cache ]; then mkdir -p #{shared_path}/tmp/cache; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/cache/models ]; then mkdir -p #{shared_path}/tmp/cache/models; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/cache/views ]; then mkdir -p #{shared_path}/tmp/cache/views; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/cache/persistent ]; then mkdir -p #{shared_path}/tmp/cache/persistent; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/logs ]; then mkdir -p #{shared_path}/tmp/logs; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/sessions ]; then mkdir -p #{shared_path}/tmp/sessions; fi
  CMD
  run <<-CMD
    if [ ! -d #{shared_path}/tmp/sessions ]; then mkdir -p #{shared_path}/tmp/sessions; fi
  CMD
  # files
  run <<-CMD
    if [ ! -d #{shared_path}/files ]; then mkdir -p #{shared_path}/files; fi
  CMD
end

desc "tmpディレクトリのシンボリックリンクを設定"
task :link_tmp, roles => :web do
  run <<-CMD
    rm -rf #{release_path}/app/tmp &&
    cd #{release_path} &&
    ln -nfs #{shared_path}/tmp #{release_path}/app
  CMD
end

desc "filesディレクトリのシンボリックリンクを設定"
task :link_files, roles => :web do
  run <<-CMD
    rm -rf #{release_path}/app/files &&
    cd #{release_path} &&
    ln -nfs #{shared_path}/files #{release_path}/app &&
    #{sudo} chmod -R 777 #{deploy_to}/current/app/files
  CMD
end

desc "アプリケーションの動作に必要なパーミッションの設定をします"
task :change_permission, roles => :web do
  run <<-CMD
    #{sudo} chmod -R 777 #{deploy_to}/current/app/tmp
  CMD
end

desc "データベース接続設定をアップロードします"
# https://gist.github.com/3084229
task :upload_databaseconfig, roles => :web do
  upload File.dirname(__FILE__) + "/#{stage}/database.php", "#{deploy_to}/current/app/Config/", :via => :scp
end

desc "APCのキャッシュをクリアします"
task :apc_clear, roles => :web do
  run <<-CMD
    #{sudo} service httpd graceful
  CMD
end

desc "extra"
task :setup_extra, roles => :web do
end
