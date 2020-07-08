<template>
    <div class="container-fluid">
        <h1>基金计算器</h1>
        <el-input
            v-model="createData.fund_id"
            placeholder="基金代码"
            style="width:130px;"
            size="mini"
        />
        <el-input-number
            v-model="createData.share"
            size="mini"
            placeholder="持有份额"
            :controls="false"
            :min="0"
            :precision="2"
        />
        <el-input-number
            v-model="createData.amount"
            size="mini"
            placeholder="持有成本"
            :controls="false"
            :min="0"
            :precision="2"
        />
        <el-button
            type="primary"
            size="mini"
            :disabled="addStatus"
            @click="add"
        >新增
        </el-button>
        <el-table
            :data="data.list"
            :row-class-name="tableRowClassName"
            :summary-method="getSummaries"
            show-summary
            border
        >
            <el-table-column
                prop="fund_name"
                label="基金名称"
            />
            <el-table-column
                prop="fund_id"
                label="基金代码"
            />
            <el-table-column
                prop="estimated_net_worth_ratio"
                sortable
                label="预估涨幅(%)"
            />
            <el-table-column
                prop="actual_net_worth_ratio"
                label="实际涨幅(%)"
            />
            <el-table-column
                prop="share"
                sortable
                label="持有份额"
            >
                <template slot-scope="scope">
                    <el-input-number
                        v-model="scope.row.share"
                        :controls="false"
                        :min="0"
                        :precision="2"
                        size="mini"
                        @blur="edit(scope.row)"
                    />
                </template>
            </el-table-column>
            <el-table-column
                prop="amount"
                sortable
                label="持有成本"
            >
                <template slot-scope="scope">
                    <el-input-number
                        v-model="scope.row.amount"
                        :controls="false"
                        :min="0"
                        :precision="2"
                        size="mini"
                        @blur="edit(scope.row)"
                    />
                </template>
            </el-table-column>
            <el-table-column
                prop="estimated_earnings"
                sortable
                label="预估收益"
            />
            <el-table-column
                prop="actual_earnings"
                label="实际收益"
            />
            <el-table-column
                prop="cumulative_income"
                label="累计收益"
                sortable
            />
            <el-table-column
                prop="update_at"
                label="更新时间"
            />
            <el-table-column label="操作">
                <template slot-scope="scope">
                    <el-button
                        type="text"
                        @click="deleteRecord(scope.row.id)"
                    >
                        删除
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>
<script>
    import {confirm} from '../untils/message'

    export default {
        data() {
            return {
                timeVal: undefined,
                data: {
                    list: [],
                    amount: []
                },
                createData: {
                    fund_id: undefined,
                    amount: undefined,
                    share: undefined
                },
                addStatus: false,
                time: 60,
                standard_time: 60
            }
        },
        mounted() {
            this.fundList()
            this.timeVals()
            this.countdown()
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    window.clearInterval(window['timeVal'])
                } else {
                    if (window['timeVal'] !== undefined) {
                        window.clearInterval(window['timeVal'])
                    }

                    this.fundList()
                    this.timeVals()
                }
            })
        },
        methods: {
            timeVals() {
                window['timeVal'] = window.setInterval(() => {
                    setTimeout(() => {
                        this.fundList()
                    }, 0)
                }, this.standard_time * 1000)
            },
            countdown() {
                window.setInterval(() => {
                    setTimeout(() => {
                        this.time = this.time - 1
                    }, 0)
                }, 1000)
            },
            fundList() {
                axios.get('/fund').then(res => {
                    this.data.list = res.data.data.list
                    this.data.amount = res.data.data.amount
                    this.time = this.standard_time
                })
            },
            deleteRecord(id) {
                const _self = this
                confirm('确认删除吗', '警告', 'warning', function () {
                    axios.delete(`/fund/${id}`).then(() => {
                        _self.$message({'type': 'success', 'message': '已删除'})
                        _self.fundList()
                    })
                }, function () {
                    _self.$message({'type': 'success', 'message': '已取消'})
                })
            },
            edit(item) {
                const _self = this
                confirm('确认修改吗', '警告', 'warning', function () {
                    axios.post(`/fund/${item.id}`, item).then(() => {
                        _self.$message({'type': 'success', 'message': '已修改'})
                        _self.fundList()
                    })
                }, function () {
                    _self.$message({'type': 'success', 'message': '已取消'})
                })
            },
            tableRowClassName({row, rowIndex}) {
                if (row.estimated_net_worth_ratio > 0) {
                    return 'table-danger'
                } else if (row.estimated_net_worth_ratio < 0) {
                    return 'table-success'
                }
            },
            getSummaries() {
                const sums = []
                sums[0] = '合计'
                sums[5] = this.data.amount.amount
                sums[6] = this.data.amount.estimated_earnings + '/' + this.data.amount.estimated_earnings_rate + '%'
                sums[7] = this.data.amount.actual_earnings + '/' + this.data.amount.actual_earnings_rate + '%'
                sums[8] = this.data.amount.cumulative_income
                sums[9] = this.time + '秒后刷新'
                return sums
            },
            add() {
                this.addStatus = true
                axios.post(`/fund`, this.createData).then(() => {
                    this.addStatus = false
                    this.$message({'type': 'success', 'message': '已增加'})
                    this.createData = {
                        fund_id: undefined,
                        amount: undefined,
                        share: undefined
                    }
                    this.fundList()
                }).catch(() => {
                    this.addStatus = false
                })
            }
        }
    }
</script>
