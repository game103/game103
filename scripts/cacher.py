import urllib2
import re
import os
import ssl
from os import listdir
from os.path import isfile, join

# output the navbar
os.system("php /var/www/game103/scripts/generate_navbar.php > /var/www/game103/navbar.html")
os.system("php /var/www/game103/scripts/generate_navbar.php --preview > /var/www/game103/navbar-preview.html")

pages = {}
base_url = 'https://game103.net'
# This is shared between all calls to crawl, so we never crawl the
# same page twice (like passing by reference)
pages = {}
# pages we don't want to include
bad_pages = { '/random': '/random', '/blog': '/blog' }

# crawl a root url (/<something>)
# this will populate pages
def crawl(root):
	try:
		print "Crawling " + root + "..."
		# create the request
		add_on = "?no_cache=1"
		if "?" in root:
			add_on = "&no_cache=1"
		request = urllib2.Request(base_url + root + add_on, headers={"Accept" : "application/xml"})
		context = ssl.SSLContext(ssl.PROTOCOL_TLSv1)
		# read
		u = urllib2.urlopen(request, context=context)
		pages[root] = u.read()
		# Find all relative links that are not to a file (see valid characters in regex)
		links = re.findall(r"href\s*=\s*[\"'](\/[A-Za-z\d!_\-\/]*)[\"']", pages[root])
		# for every link we find
		for link in links:
			# we don't want to crawl links we've already been to
			if not link in pages and not link in bad_pages:
				# add ws if we want to 
				crawl(link)
				if re.match('^\/games[^?]*$|^\/videos[^?]*$^\/everything[^?]*$|^\/apps[^?]*$|^\/resources[^?]*$', link):
					crawl(link + '?ws=1')
	except Exception as e:
		print e
		# Occurs if there is a 500 error
		bad_pages[root] = root

crawl('/');

os.chdir('/var/www/game103/cache')

page_urls = []
for page_location in pages:
	page_url = page_location.replace('/', '-').replace('?', '-')
	f = open(page_url + ".html", "w")
	f.write(pages[page_location])
	f.close()
	page_urls.append(page_url + ".html")

print "Looking for old files..."

# Remove uncached files
onlyfiles = [f for f in listdir("/var/www/game103/cache") if isfile(join("/var/www/game103/cache", f))]
for filename in onlyfiles:
	if not filename in page_urls:
		print "removing " + filename
		os.remove(filename)
