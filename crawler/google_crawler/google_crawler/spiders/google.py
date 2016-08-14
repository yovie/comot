# -*- coding: utf-8 -*-
import scrapy

class GoogleSpider(scrapy.Spider):
    name = "google"
    allowed_domains = ["news.google.com"]
    start_urls = (
        "https://news.google.com/news/section?cf=all&pz=1&topic=w&siidp=1f6e9ef991e21a49b1c9d1702d1ecebcef2a&ict=ln",
    )

    def parse(self, response):
        filename = response.url.split("/")[-2]
        links_title = response.xpath("//a/text()").extract();
        links_url = response.xpath("//a/@href").extract();
        for idx, title in enumerate(links_title):
        	print title
        	print "\t", links_url[idx]
