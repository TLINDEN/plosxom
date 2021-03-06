version  = $(shell cat VERSION)
pkg      = plosxom-core
plugins  = admin admin_backup admin_rpc categories feed links more page technorati tagcloud youtube
libfiles = IXR_Library.inc.php thumbnail.inc.php
plugfiles = $(shell cd ../plugins; \
		 find $(plugins) -name "*.php"  | grep -v '.svn'; \
		 find $(plugins) -name "*.nfo"  | grep -v '.svn'; \
		 find $(plugins) -name "*.txt"  | grep -v '.svn'; )
conffiles = $(shell cd ../plugins; find $(plugins) -name "*.conf" | grep -v '.svn')

ver:
	@echo "version " $(version)

all:
	@echo "Only manual installation supported at the moment!"


changelog:
	svn log -v https://plosxom.googlecode.com/svn/ > CHANGELOG


manifest: clean
	find . -type f|grep -v .svn | sed 's/\.\///' > MANIFEST


clean:
	rm -f *~ */*~ */*/*~



dist: manifest core patch

core:
	@echo packing $(pkg)-$(version)
	rm -rf ../$(pkg)-$(version)
	mkdir -p ../$(pkg)-$(version)
	cp -R * ../$(pkg)-$(version)/
	$(foreach plugfile,$(plugfiles), \
		cp ../plugins/$(plugfile) ../$(pkg)-$(version)/plugins/; \
	)
	$(foreach conffile,$(conffiles), \
		cp ../plugins/$(conffile) ../$(pkg)-$(version)/etc/; \
	)
	mkdir -p ../$(pkg)-$(version)/templates/shared
	$(foreach plugin,$(plugins), \
		cp -R ../plugins/$(plugin)/* ../$(pkg)-$(version)/templates/shared/;   \
	)
	rm -f ../$(pkg)-$(version)/templates/shared/*.php
	rm -f ../$(pkg)-$(version)/templates/shared/*.conf
	rm -f ../$(pkg)-$(version)/templates/shared/*.txt
	$(foreach libfile,$(libfiles), \
		mv ../$(pkg)-$(version)/plugins/$(libfile) ../$(pkg)-$(version)/lib/; \
	)
	find -d ../$(pkg)-$(version) -name .svn -exec rm -rf {} \;
	cd ../$(pkg)-$(version)/ && rm Makefile *.pl plugpack.sh
	cd .. && tar cpzf $(pkg)-$(version).tar.gz $(pkg)-$(version)

patch:
	rm -rf ../$(pkg)-$(version)-patch
	./diff.pl
	cd .. && tar cpzf $(pkg)-$(version)-patch.tar.gz $(pkg)-$(version)-patch
	@echo
	@echo Building done:
	@echo building plugin packs
	./plugpack.sh

