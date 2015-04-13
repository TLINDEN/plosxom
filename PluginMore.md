# Introduction #

Displays a "read more" link when listing postings.

Requires: plosxom 1.06 or higher.

_Known incompatibilities: for now, technorati tags (using the
technorait plugin) disappear in list mode, because they usually
are written at the last line of a posting which, together with
all the other stuff after the read-more slug, will be removed._

# Download #

[version 1.00](http://plosxom.googlecode.com/files/more-1.00.zip)

# Install #

  * copy more.php to your plugin directory.
  * copy more.conf to your etc directory (optional).

That's it.

# Using #

To use, write a post file and on the position where you want to
have the "read more" link to appear write:

`<!--more-->`

Everything after this slug will be removed if you are in list
mode, that is a list of postings will currently displayed. If
in single post mode, nothing happens to the slug so that the
complete unaltered posting appears.