---
- hosts: all
  user: devil
  sudo: true
  tasks:
  - name: Set simple iptables
    action: raw bash < <(curl https://raw.github.com/k1LoW/sakuravps/master/iptables-simple.sh)
  - name: Save iptables
    action: command service iptables save
