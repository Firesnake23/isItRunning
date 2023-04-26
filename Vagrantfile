Vagrant.configure("2") do |config|
  config.vm.box = "pm3980/lamp81"
  config.vm.box_version = "1.0.0"

  config.vm.network "forwarded_port", guest: 80, host: 8080, auto_correct: true 
  config.vm.synced_folder ".", 
                          "/var/www",
                          mount_options: ["dmode=775"],
                          owner: "www-data",
                          group: "www-data"
end
