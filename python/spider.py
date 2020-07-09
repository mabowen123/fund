import requests
import time
from DataBase.UserFund import UserFund
import threading
import json
import re
from bs4 import BeautifulSoup
from r import r
import random


class Spider:
    def __init__(self):
        self.url = "http://fundgz.1234567.com.cn/js/{}.js?rt={}"
        self.htmlUrl = 'http://fund.eastmoney.com/{}.html'
        self.marketUrl = "http://api.money.126.net/data/feed/1399001,1399006,0000001?callback=_ntes_quote_callback{}"

    def sleep(self):
        hour = self.tm_hour
        if hour >= 20:
            time.sleep(30)

    @property
    def tm_hour(self):
        return time.localtime().tm_hour

    def threads(self, t_group):
        thread = []
        for t in t_group:
            thread.append(threading.Thread(target=self.get, args=(t,), name=t))

        return thread

    @property
    def timestamps(self):
        return int(round(time.time() * 1000))

    def get(self, fundId):
        headers = {
            'User-Agent': self.headers
        }
        actualNetWorth = ''
        actualNetWorthRatio = ''

        res = r().hmget('fund', fundId)
        if self.tm_hour <= 15 or not res:
            api = requests.get(self.url.format(fundId, self.timestamps), headers=headers)
            res = json.loads(re.match(".*?({.*}).*", api.text, re.S).group(1))
        else:
            res = {
                'fundcode': res['fund_id'],
                'name': res['fund_name'],
                'dwjz': res['net_worth'],
                'gsz': res['estimated_net_worth'],
                'gszzl': res['estimated_net_worth_ratio'],
                'gztime': res['update_at'],
                'actual_net_worth': res['actual_net_worth'],
                'actual_net_worth_ratio': res['actual_net_worth_ratio']
            }
            if res['gztime'][:10] == time.strftime('%Y-%m-%d', time.localtime()):
                actualNetWorth = res['actual_net_worth']
                actualNetWorthRatio = res['actual_net_worth_ratio']

        if self.tm_hour >= 20 and (not actualNetWorth or not actualNetWorthRatio):
            html = requests.get(self.htmlUrl.format(fundId), headers=headers)
            soup = BeautifulSoup(html.text, 'lxml')
            dataItem = soup.find('dl', class_='dataItem02')
            if dataItem != None:
                dataNums = dataItem.find('dd', class_='dataNums').find_all('span')
                date = dataItem.dt.p.text[-11:-1]
                if date == time.strftime('%Y-%m-%d', time.localtime()):
                    actualNetWorth = dataNums[0].text
                    actualNetWorthRatio = dataNums[1].text
                    res['gztime'] = time.strftime('%Y-%m-%d %H:%I:%S', time.localtime())

        data = {
            'fund_id': res['fundcode'],
            'fund_name': res['name'],
            'net_worth': res['dwjz'],
            'estimated_net_worth': res['gsz'],
            'estimated_net_worth_ratio': res['gszzl'],
            'actual_net_worth': actualNetWorth,
            'actual_net_worth_ratio': actualNetWorthRatio,
            'update_at': res['gztime']
        }
        r().hmset(res['fundcode'], data, 'fund')

    @property
    def headers(self):
        headers = [
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
            "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/534.16 (KHTML, like Gecko) Chrome/10.0.648.133 Safari/534.16,"
            "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0",
            "Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36 OPR/26.0.1656.60",
            "Opera/8.0 (Windows NT 5.1; U; en)",
            "Mozilla/5.0 (Windows NT 5.1; U; en; rv:1.8.1) Gecko/20061208 Firefox/2.0.0 Opera 9.50",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; en) Opera 9.50",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/534.57.2 (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36",
            "Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko",
            "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)",
            "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)",
            "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 SE 2.X MetaSr 1.0",
            "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; SE 2.X MetaSr 1.0)",
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.122 UBrowser/4.0.3214.0 Safari/537.36",
            "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (iPad; U; CPU OS 4_2_1 like Mac OS X; zh-cn) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
            "Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5",
            "Mozilla/5.0 (Linux; U; Android 2.2.1; zh-cn; HTC_Wildfire_A3333 Build/FRG83D) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10",
            "Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13"
        ]
        return random.choice(headers)

    def market(self):
        res = requests.get(self.marketUrl.format(self.timestamps))
        res = json.loads(re.match(".*?({.*}).*", res.text, re.S).group(1))
        for item in res.values():
            data = {
                'name': item['name'],
                'time': item['time'],
                'percent': item['percent'],
                'price': item['price']
            }
            r().hmset(item['code'], data, 'market')

    def run(self):
        self.sleep()
        self.market()
        for t in self.threads(UserFund().fundIds()):
            t.start()
            t.join()


if __name__ == "__main__":
    Spider().run()
