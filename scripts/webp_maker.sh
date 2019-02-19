#!/bin/bash
FILES=`find /var/www/game103/images -regex ".*\.\(jpg\|gif\|png\|jpeg\)"`
for f in $FILES
do
    echo $f
    NAME=$(basename $f)
    EXTENSION="${NAME##*.}"
    NAME="${NAME%.*}"
    DIR=$(dirname $f)
    OUTPUT="$DIR/$NAME.webp"
    if [ $DIR != "/var/www/game103/images/icons/games/bordered" ] && [ $DIR != "/var/www/game103/images/archive" ];
    then
        if [ $EXTENSION = "png" ] || [ $EXTENSION = "jpg" ] || [ $EXTENSION = "jpeg" ] ;
        then
            cwebp $f -o $OUTPUT -z 6
        fi
        if [ $EXTENSION = "gif" ] ;
        then
            gif2webp $f -o $OUTPUT 
        fi
    fi
done
