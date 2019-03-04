#!/bin/sh
# Update our nameserver
# with the ip from the noip service
# Note that we are setting our ttl to 2 hours
# so every cache with these settings for game103.net should
# know to check back here after 2 hours
# Since we use dynamic dns, we aren't actually specifying
# the NS for game103.net as ns.game103.net in the .net TLD servers
# but we specify it here. Thus, to know we are authoritative
# for game103.net
# (we are ns.game103.net) a resolver will have to lookup
# ns.game103.net. Doing so will bring them back here, asking
# for the authoritative NS for game103.net, a loop. Thus,
# the resolver will ask for a glue, and receive the glue 
# of the IP we have for ns.game103.net. Then, the resolver
# will know this is ns.game103.net (since the ip it got
# it has for this server - reached by cocoa.myftp.biz - matched
# that of ns.game103.net), and proceed as this is authoritative.
ip=$(dig +short cocoa.myftp.biz)
date=$(date +%s)
cat > /etc/bind/db.game103.net <<- EOM
\$TTL   3600 
@       IN      SOA     ns.game103.net. james.game103.net. (
                          $date         ; Serial
                         604800         ; Refresh
                          86400         ; Retry
                        2419200         ; Expire
                         604800 )       ; Negative Cache TTL
@	IN	NS	ns.game103.net.	
@       IN      A       $ip
@	IN	MX	1	ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT1.ASPMX.L.GOOGLE.COM.
@	IN	MX	5	ALT2.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT3.ASPMX.L.GOOGLE.COM.
@	IN	MX	10	ALT4.ASPMX.L.GOOGLE.COM.
ns      IN      A       $ip
www	IN	CNAME	game103.net.
email	IN	CNAME	ghs.google.com.
backup	IN	A	10.0.0.65
cocoa	IN	A	10.0.0.151
EOM
rndc reload
