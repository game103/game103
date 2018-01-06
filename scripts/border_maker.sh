#!/bin/bash
cd /var/www/game103/images/icons/games/
FILES=/var/www/game103/images/icons/games/*
for f in $FILES
do
    echo $f
    NAME=$(basename $f)
    WIDTH=$(identify -format "%w" $f)
    BORDER=`expr 200 - $WIDTH`
    BORDER=`expr $BORDER + 1`
    BORDER=`expr $BORDER / 2`
    if (( $BORDER > 0 )); then
        BORDER_CONFIG="$BORDER"
        BORDER_CONFIG+="x"
        BORDER_CONFIG+="$BORDER"
        convert -bordercolor '#4E85DB' $f -border $BORDER_CONFIG bordered/$NAME
    fi
done
