#!/usr/bin/perl
#
# generate a directory tree of postings sorted in folders
#
# It creates directories and files therein each with
# different timestamps.


my ($data, $factor) = @ARGV;

chdir ($data);

my $time = time;
my $post = join "", <DATA>;
my $counter = 0;
my $dcounter = 0;
my $bytes = 0;

foreach my $cnum (11 .. $factor) {
  mkdir("test$cnum");
  foreach my $pnum (11 .. $factor) {
    print "Adding $data/test$cnum/post$pnum.txt ... ";
    open T, ">test$cnum/post$pnum.txt";
    printf T $post, $pnum, "tag$cnum";
    close T;
    my($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime $time;
    $year += 1900;
    $mon++;
    $time -= 10000;
    my $stamp = sprintf "%4d%02d%02d%02d%02d", $year, $mon, $mday, $hour, $min;
    $bytes += -s "test$cnum/post$pnum.txt";
    system ("touch -t $stamp test$cnum/post$pnum.txt");
    print "done\n";
    $counter++;
  }
  $dcounter++;
}

print "$counter files ($bytes bytes) created in $dcounter directories\n";

__DATA__
Lorem Ipsum %s

Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo.

Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt, ut labore et dolore magnam aliquam quaerat voluptatem.

Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, qui dolorem eum fugiat, quo voluptas nulla pariatur?

At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga.

tag:%s
