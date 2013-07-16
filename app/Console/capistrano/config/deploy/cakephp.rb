# -*- coding: utf-8 -*-
# candycane
role :web, *%w[192.168.1.106]

set :repository,  "https://github.com/yandod/candycane.git"

set :use_sudo, false
default_run_options[:pty] = true
set :user, "devil"
set :ssh_options, {
  :keys_only => true,
  :auth_methods => %w(publickey),
  :keys => ["/var/www/html/devilspie/app/Config/devil_rsa"],
}

set :deploy_to, "/var/www/html/candycane"
set :deploy_via, :copy

desc "extra"
task :setup_extra, roles => :web do
end
