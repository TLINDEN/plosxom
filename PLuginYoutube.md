# Introduction #

This is the youtube plugin which allows you to
post youtube videos to your blog by just entering
the video id without the hassle of digging through
youtube html source to find the player embed code.

This plugin also supports google videos and
sevenload videos to be posted. Usage is identical
for all of them. Sevenload doesn't support image
preview mode so far.

This plugin requires version 1.06 of plosxom whose
templates are using the smarty eval() function for
post texts. If you don't have 1.06 installed you
can modify yout post.tpl template to use eval(),
just replace

`$post.text`

with

`{eval var=$post.text`}

# Download #

[version 1.00](http://plosxom.googlecode.com/files/youtube-1.00.zip)

# Installation #

1. copy youtube.php to your plugins directory.

2. copy youtube.tpl to your current template directory.

That's all about it.

# Using #

To include a youtube video in a blog post, do the following:

1. visit the youtube video page you want to post

2. retrieve the video id, example:
```
  http://youtube.com/watch?v=OEXt9qb0zBI
                             ^^^^^^^^^^^  this is the video id
```

3. in a new blog post add one of the following versions:

  * plain ascii html link to the youtube page:
> > `{youtube mode="ascii" id="jt4XH65rOxA"`}
  * preview image with link to youtube page:
> > `{youtube mode="image" id="jt4XH65rOxA"`}
  * display inline video player in the blog post:
> > `{youtube id="jt4XH65rOxA"`}
  * the same as above but with custom dimensions:
> > `{youtube width="320" height="240" id="jt4XH65rOxA"`}

The same applies for sevenload and google videos. The plugin distinguishes the type of video using the video-id.