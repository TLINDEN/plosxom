#!/bin/sh

cd ../plugins

for plugin in `ls -d *`; do
    version=`cat $plugin/VERSION`
    dir="$plugin-$version"
    mkdir -p $dir
    cp $plugin/* $dir/
    zip -r $dir.zip $dir
    mv $dir.zip ../
    rm -rf $dir
    echo "$plugin $version packed to $dir.zip"
done