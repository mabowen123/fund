import pymysql


class BaseModel:
    def __init__(self):
        self.__host = 'mysql'
        self.__user = 'root'
        self.__password = 'mysql!@*Root'
        self.__port = 3306
        self.__dbname = 'fund'
        self.__conn = pymysql.connect(host=self.__host, user=self.__user, password=self.__password,
                                      port=self.__port, db=self.__dbname, charset='utf8')
        self.__cur = self.__conn.cursor()

    def __del__(self):
        self.__conn.close()

    def _fetchall(self, sql):
        self.__cur.execute(sql)
        return self.__cur.fetchall()


BaseModel()
