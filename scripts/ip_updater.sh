#!/bin/sh
# Update our nameserver
# This is the nameserver of cocoapup.dog which is the nameserver of game103.net
# TTL is 2 since google subtracts 1 and this is the shortest time we can have (needed since
# domain could change at any second)
ip=$(dig +short myip.opendns.com @resolver1.opendns.com)
date=$(date +%Y%m%d%S)
if [ -z "$ip" ]
then
	exit 1
fi
cat > /etc/bind/db.game103.net <<- EOM
\$TTL   2
@       IN      SOA     ns1.cocoapup.dog. james.game103.net. (
                          $date         ; Serial
                          43200         ; Refresh
                           7200         ; Retry
                        2419200         ; Expire
                          86400 )       ; Negative Cache TTL
@	IN	NS	ns1.cocoapup.dog.
@	IN	NS	ns2.cocoapup.dog.
@       IN      A       $ip
@	IN	MX	1	ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT1.ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT2.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT3.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT4.ASPMX.L.GOOGLE.COM.
www	IN	CNAME	game103.net.
email	IN	CNAME	ghs.google.com.
mail	IN	CNAME	email.game103.net.
backup	IN	A	192.168.1.19
EOM
rndc reload
