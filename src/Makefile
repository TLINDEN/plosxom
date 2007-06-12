version = $(shell cat VERSION)
pkg     = plosxom-core

all:
	@echo "Only manual installation supported at the moment!"


manifest: clean
	find . -type f|grep -v .svn | sed 's/\.\///' > MANIFEST


clean:
	rm -f *~ */*~ */*/*~


dist: manifest
	@echo packageing $(pkg)-$(version)
	rm -rf ../$(pkg)-$(version)
	mkdir -p ../$(pkg)-$(version)
	cp -R * ../$(pkg)-$(version)/
	cd ../$(pkg)-$(version)/ && rm -rf .svn */.svn */*/.svn */*/*.svn Makefile *.pl
	cd .. && tar cpzf $(pkg)-$(version).tar.gz $(pkg)-$(version)
	rm -rf ../$(pkg)-$(version)-patch
	./diff.pl
	cd .. && tar cpzf $(pkg)-$(version)-patch.tar.gz $(pkg)-$(version)-patch
	@echo
	@echo Building done:
	@md5sum ../$(pkg)-$(version).tar.gz

