#!/usr/bin/perl
my $data = shift;
die "usage: $0 <datadir>\n" if(!$data);
my %tags;
opendir DIR, "$data";
while (my $ent = readdir(DIR)) {
  next if($ent =~ /^\./);
  if (-d "$data/$ent") {
    opendir CAT, "$data/$ent";
    while (my $file = readdir(CAT)) {
      next if($file =~ /^\./);
      open FILE, "<$data/$ent/$file";
      my $post = join "", <FILE>;
      close FILE;
      while($post =~ /tag:([a-zA-Z0-9]+)/g) {
	  $tags{$1}++;
      }
    }
  }
}
foreach my $tag(sort keys %tags) {
  print "$tag = $tags{$tag}\n";
}
