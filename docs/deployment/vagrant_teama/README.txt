1. Generate a new SSH key:
   # ssh-keygen

2. Upload the public key (~/.ssh/id_rsa.pub) to your GitHub account

3. Edit ~/.ssh/known_hosts and remove any previous entries for Vagrant VMs

4. Launch the Virtual Machine:
   # vagrant up
