#!/usr/bin/perl
use Shell qw(cp);
use Data::Dumper;
sub install;

use Digest::MD5 qw(md5_base64 md5_hex);

sub md5;

my $base = "/home/scip/D/plosxom";
chdir($base);

my $dirs = `ls -d plosxom-core-?.??`;
chomp $dirs;
my($last, $current) = split /\s\s*/, $dirs;

chdir("$base/$last");
my %lastfiles = map { $_ => 1 } split/\n/, `find . -type f`;
chdir("$base/$current");
my %currentfiles = map { $_ => 1 } split/\n/, `find . -type f`;

my $patchdir = "$base/${current}-patch";
my $olddir   = "$base/${last}";

mkdir ($patchdir);

foreach my $file (keys %currentfiles) {
  print "processing $file\n";
  if (! exists $lastfiles{$file}) {
    print "   installing new file\n";
    install($file);
  }
  else {
    $lastmd5 = md5("$base/$last/$file");
    $curmd5  = md5("$base/$current/$file");
    if ($lastmd5 ne $curmd5) {
      print "   installing changed file\n";
      install($file);
    }
  }
}

print "done\n";

sub md5 {
  my($file) = @_;
  my $dig = new Digest::MD5;
  open F, "<$file" or die "Could not open file $file: $!\n";
  $dig->addfile(F);
  close F;
  return $dig->hexdigest();
}

sub install {
  my($file) = @_;
  my $dir = $file;
  $dir =~ s([^/]*$)();
  system("mkdir -p $patchdir/$dir");
  if ($dir =~ /(templates|etc)/ && $file =~ /\.(tpl|css)$/ && -e "$last/$file") {
    # dont patch, add .upgrade file
    my $newfile = $file;
    $newfile =~ s(.*/)();
    cp ("$base/$current/$file", "$patchdir/$dir/${newfile}.upgraded");
  }
  else {
    cp ("$base/$current/$file", "$patchdir/$dir");
  }
}
