red   = \033[31m
green = \033[32m
bold  = \033[1m
reset = \033[0m

sub_dir  = xfabom01/
server   = http://www.stud.fit.vutbr.cz/~xfabom01/
key_file = res/generated_key
src_dir  = src/
dest_dir = ~/WWW/$(sub_dir)

# This makefile takes everything in 'src_dir' directory
# and uploads it into 'dest_dir' on FIT server mserlin
# under user xfabom01. Make sure the 'key_file' has
# permissions value of 600 or 400 (readable only by user)

# TL;DR $(src_dir) -> $(dest_dir)

update: | $(key_file)
	@tar -cf - -C $(src_dir) . | ssh -i $(key_file) -oStrictHostKeyChecking=no xfabom01@merlin.fit.vutbr.cz "tar -xf - -C $(dest_dir)"
	@echo "$(green)Updated server:$(reset) $(bold)$(server)$(sub_dir)$(reset)"

open: update
	@xdg-open $(server)$(sub_dir)

$(key_file):
	@echo "$(red)SSH Key '$(key_file)' doesn't exist. Download it from github repository and set up proper permissions.$(reset)"
	@false