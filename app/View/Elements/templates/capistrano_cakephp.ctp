# -*- coding: utf-8 -*-
# candycane
role :web, *%w[<?php echo $hostname ?>]

set :repository,  "https://github.com/cakephp/cakephp.git"

set :use_sudo, false
default_run_options[:pty] = true
set :user, "devil"
set :ssh_options, {
  :keys_only => true,
  :auth_methods => %w(publickey),
  :keys => ["<?php echo $apppath; ?>Config/devil_rsa"],
}

set :deploy_to, "/var/www/html/cakephp"
set :deploy_via, :copy

desc "extra"
task :setup_extra, roles => :web do
end
