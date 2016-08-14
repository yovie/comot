import feedparser
import xml.etree.ElementTree as ET
import lxml.html
from lxml import etree
from urlparse import urlparse, parse_qs

url_base = "https://news.google.com/news?q=?&output=rss"

def remove_tags(text):
	return ''.join(ET.fromstring(text).itertext())

news = feedparser.parse("https://news.google.com/news?q=apple&output=rss")

print news.feed.title
print news.feed.link
print news.feed.description
print news.feed.published
print news.feed.published_parsed

print "total ", len(news.entries)

for news in news.entries:
	print "\t", news.title
	print "\t\t", news.id
	print "\t\t", news.link
	# print "\t\t", news.description
	# print "\t\t", news.summary_detail
	print "\t\t", news.published
	print "\t\t", news.published_parsed

	url = urlparse(news.link)
	qs = parse_qs(url.query)
	print "\t\t", qs['url'][0]

	dom = lxml.html.parse(qs['url'][0])
	xpath = etree.XPathDocumentEvaluator(dom)

	links = xpath("//p/text()")

	print links

	# break