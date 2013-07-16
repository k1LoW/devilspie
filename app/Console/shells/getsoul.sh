#!/bin/bash
# $1 hostname
# $2 root pass
# $3 app path

echo $1 > ~/ansible_hosts
export ANSIBLE_HOSTS=~/ansible_hosts

expect -c "
set timeout 10
spawn ansible all -m user -a \"name=devil\" -u root --ask-pass
expect \"SSH password:\"
send \"$2\n\"
expect eof; exit 0
"

expect -c "
set timeout 10
spawn ansible all -m lineinfile -a \"dest=/etc/sudoers state=present regexp='^## Same thing without a password' line='devil ALL=(ALL) NOPASSWD: ALL' backrefs=yes\" -u root --ask-pass
expect \"SSH password:\"
send \"$2\n\"
expect eof; exit 0
"

expect -c "
set timeout 10
spawn ansible all -m authorized_key -a \"user=devil key=\\\"{{ lookup('file', '$3Config/devil_rsa.pub') }}\\\"\" -u root --ask-pass
expect \"SSH password:\"
send \"$2\n\"
expect eof; exit 0
"

# echo "ssh devil@$1 -o PreferredAuthentications=publickey -o IdentitiesOnly=yes -i $3Config/devil_rsa"