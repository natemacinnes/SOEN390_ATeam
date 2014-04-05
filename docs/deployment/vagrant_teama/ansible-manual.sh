#!/bin/sh
export ANSIBLE_SSH_ARGS="-o ForwardAgent=yes"
ansible-playbook -i provisioning/hosts --private-key=~/.vagrant.d/insecure_private_key -u vagrant -s provisioning/site.yml --step --start-at-task="$1" -vvvv
