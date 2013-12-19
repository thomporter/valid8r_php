# Valid8r Makefile - just copies resources from _src_files to where they belong.

MAKEFLAGS = --no-print-directory --always-make
MAKE = make $(MAKEFLAGS)

all:
	$(MAKE) build;

build:
	cp ./lib/Valid8r/Valid8r.php ./examples/vendor/Valid8r/;

