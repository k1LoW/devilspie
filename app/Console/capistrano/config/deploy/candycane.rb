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
  # :keys => [File.join(File.dirname(__FILE__), "..", "fiu_mahoroba_rsa")],
  :keys => ["/var/www/html/devilspie/app/Config/devil_rsa"],
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
end
