Vagrant::Config.run do |config|
    config.vm.box = "ubuntu-11.10-amd64"
    config.vm.network "33.33.34.20"
    config.vm.share_folder "v-root", "/vagrant", ".", :nfs => true
end
