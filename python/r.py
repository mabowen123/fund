import redis


class r:
    _instance = None
    _first = False

    def __init__(self):
        if self._first == False:
            self._first = True
            self.password = '%xIqZE)C0Ris[Xj{'
            self.host = '127.0.0.1'
            self.db = '0'
            self.port = '6379'
            self.expire = 60 * 60 * 24
            self.pool = redis.ConnectionPool(host=self.host, port=self.port, password=self.password, db=self.db)
            self.r = redis.StrictRedis(connection_pool=self.pool)

    def __new__(cls, *args, **kwargs):
        if cls._instance == None:
            cls._instance = super().__new__(cls)
        return cls._instance

    def hmset(self, key, value):
        self.r.hmset(key, value)
        self.r.expire(key, self.expire)

    def __del__(self):
        self.r.close()
