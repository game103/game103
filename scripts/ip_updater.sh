#!/bin/sh
# Update our nameserver
# This is the nameserver of cocoapup.dog which is the nameserver of game103.net
# TTL is 2 since google subtracts 1 and this is the shortest time we can have (needed since
# domain could change at any second)
ip=$(dig +short myip.opendns.com @resolver1.opendns.com)
date=$(date +%s)
cat > /etc/bind/db.game103.net <<- EOM
\$TTL   2
@       IN      SOA     cocoapup.dog. james.game103.net. (
                          $date         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
@	IN	NS	cocoapup.dog.	
@       IN      A       $ip
@	IN	MX	1	ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT1.ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT2.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT3.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT4.ASPMX.L.GOOGLE.COM.
www	IN	CNAME	game103.net.
email	IN	CNAME	ghs.google.com.
mail	IN	CNAME	ghs.google.com.
backup	IN	A	10.0.0.65
cocoa	IN	A	10.0.0.151
EOM
rndc reload
