# -*- coding: utf-8 -*-
import scrapy


class PasundanSpider(scrapy.Spider):
    name = "pasundan"
    allowed_domains = ["smapasundan3bandung.sch.id"]
    start_urls = (
        'http://www.smapasundan3bandung.sch.id',
    )

    def parse(self, response):
        return scrapy.FormRequest.from_response(
        	response,
        	formdata = {"log":"admin", "pwd":"admin"},
        	callback = self.after_login
        )

    def after_login(self, response):
    	print "------------------------\n"
    	print response.body
