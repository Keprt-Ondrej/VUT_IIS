red   = \033[31m
green = \033[32m
bold  = \033[1m
reset = \033[0m

server   = http://www.stud.fit.vutbr.cz/~xfabom01/
key_file = res/generated_key
src_dir  = src/
dest_dir = ~/WWW/


update: | $(key_file)
	@tar -cf - -C $(src_dir) . | ssh -i $(key_file) -oStrictHostKeyChecking=no xfabom01@merlin.fit.vutbr.cz "tar -xf - -C $(dest_dir)"
	@echo "$(green)Updated server:$(reset) $(bold)$(server)$(reset)"

open: update
	@xdg-open $(server)

$(key_file):
	@echo "$(red)SSH Key '$(key_file)' doesn't exist. Download it from github repository.$(reset)"
	@false