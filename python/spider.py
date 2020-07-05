import requests
import time
from DataBase.UserFund import UserFund
import threading
from fake_useragent import UserAgent
import json
import re
from bs4 import BeautifulSoup
from r import r


class Spider:
    def __init__(self):
        self.url = "http://fundgz.1234567.com.cn/js/{}.js"
        self.htmlUrl = 'http://fund.eastmoney.com/{}.html'

    def sleep(self):
        hour = self.tm_hour()
        if hour < 9:
            exit('时间未到')
        elif hour > 20:
            time.sleep(240)

    @staticmethod
    def tm_hour():
        return time.localtime().tm_hour

    def threads(self, t_group):
        thread = []
        for t in t_group:
            thread.append(threading.Thread(target=self.get, args=(t,), name=t))

        return thread

    def get(self, fundId):
        headers = {
            'User-Agent': self.headers()
        }
        api = requests.get(self.url.format(fundId), headers=headers)
        res = json.loads(re.match(".*?({.*}).*", api.text, re.S).group(1))
        actualNetWorth = ''
        actualNetWorthRatio = ''
        if self.tm_hour() > 20:
            html = requests.get(self.htmlUrl.format(fundId), headers=headers)
            soup = BeautifulSoup(html.text, 'lxml')
            dataItem = soup.find('dl', class_='dataItem02')
            dataNums = dataItem.find('dd', class_='dataNums').find_all('span')
            date = dataItem.dt.p.text[-11:-1]
            if date == time.strftime('%Y-%m-%d', time.localtime()):
                actualNetWorth = dataNums[0].text
                actualNetWorthRatio = dataNums[1].text

        data = {
            'fund_id': res['fundcode'],
            'fund_name': res['name'],
            'net_worth': res['dwjz'],
            'estimated_net_worth': res['gsz'],
            'estimated_net_worth_ratio': res['gszzl'],
            'actual_net_worth': actualNetWorth,
            'actual_net_worth_ratio': actualNetWorthRatio,
            'update_at': time.strftime('%Y-%m-%d %H:%I:%S', time.localtime())
        }
        r().hmset(res['fundcode'], data)

    @staticmethod
    def headers():
        return UserAgent().random

    def run(self):
        self.sleep()
        for t in self.threads(UserFund().fundIds()):
            t.start()
            t.join()


if __name__ == "__main__":
    Spider().run()
