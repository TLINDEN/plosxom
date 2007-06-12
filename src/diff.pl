#!/usr/bin/perl
use Shell qw(cp);
use Data::Dumper;
sub install;
sub md5;

my $base = "/home/scip/D/plosxom";
chdir "..";
my $dirs = `ls -d plosxom-core*`;
chomp $dirs;
my($last, $current) = split /\s\s*/, $dirs;

chdir("$base/$last");
my %lastfiles = map { $_ => 1 } split/\n/, `find -type f`;
chdir("$base/$current");
my %currentfiles = map { $_ => 1 } split/\n/, `find -type f`;

my $patchdir = "$base/${current}-patch";
mkdir ($patchdir);

foreach my $file (keys %currentfiles) {
  print "processing $file\n";
  if (! exists $lastfiles{$file}) {
    install($file);
  }
  else {
    $lastmd5 = md5("$base/$last/$file");
    $curmd5  = md5("$base/$current/$file");
    if ($lastmd5 ne $curmd5) {
      install($file);
    }
  }
}

print "done\n";

sub md5 {
  my($file) = @_;
  my($md5, undef) = split /\s\s*/, `md5sum $file`;
  return $md5;
}

sub install {
  my($file) = @_;
  my $dir = $file;
  $dir =~ s([^/]*$)();
  system("mkdir -p $patchdir/$dir");
  if ($dir =~ /(templates|etc)/) {
    # dont patch, add .upgrade file
    my $newfile = $file;
    $newfile =~ s(.*/)();
    cp ("$base/$current/$file", "$patchdir/$dir/${newfile}.upgraded");
  }
  else {
    cp ("$base/$current/$file", "$patchdir/$dir");
  }
}
