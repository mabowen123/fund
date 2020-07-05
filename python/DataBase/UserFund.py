from .BaseModel import BaseModel
from itertools import chain


class UserFund(BaseModel):
    def __init__(self):
        super().__init__()
        self.table = 'user_funds'

    def fundIds(self):
        sql = 'select distinct(fund_id) from {table}'.format(table=self.table)
        fundIds = super()._fetchall(sql)
        return list(chain.from_iterable(fundIds))
