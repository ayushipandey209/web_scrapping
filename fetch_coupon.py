from selenium import webdriver
import time

driver = webdriver.Chrome()

driver.get("https://www.cleartrip.com/all-offers/?categories=hotels")

time.sleep(5)
page_source = driver.page_source

with open("dom.txt", "w", encoding="utf-8") as file:
    file.write(page_source)

print("DOM saved to dom.txt")

driver.quit()
