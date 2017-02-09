/**
 * Created by huangrui on 2017/2/8.
 * 日历组件
 */
class CalenderUtls {
    constructor () {
        this.date = new Date()
        this.month = this.date.getMonth() + 1
        this.year = this.date.getFullYear()
        this.day = this.date.getDate()
    }
    // 日历布局
    getCalender ({year, month} = {month: this.month, year: this.year}) {
        return new Promise((resolve, reject) => {
            // 存储日历数据的数组
            let calenderArr = []
            // 获取本月的第一天是星期几 周日 0 周一 1...
            let firstDay = new Date(year, month - 1, 1).getDay() // 参数的月份是从0开始
            // 获取本月一共多少天
            let curMonthdays = new Date(year, month, 0).getDate() // 参数的月份从1开始
            let n = 0
            // 行
            for (let i = 0; i < 6; i++) {
                // 列
                for (let j = 0; j < 7; j++) {
                    if (j === firstDay || n > 0) {
                        n++
                    }
                    if (!n && j < firstDay) {
                        calenderArr.push('')
                    } else if (n && n <= curMonthdays) {
                        calenderArr.push(n)
                    } else if (n && n >= curMonthdays) {
                        calenderArr.push('')
                    }
                }
                if (n >= curMonthdays) {
                    break
                }
            }
            // 每一行的数据为一组
            let newCalenderData = []
            let trData = []
            calenderArr.forEach((item, index) => {
                trData.push(item)
                if ((index + 1) % 7 == 0) {
                    newCalenderData.push(trData)
                    trData = []
                }
            })
            resolve(newCalenderData)
        })
        // return newCalenderData
    }
    // 上个月
    preMonth () {
        return new Promise((resolve, reject) => {
            this.month--
            if (this.month == 0) {
                this.month = 12
                this.year--
            }
            resolve({month: this.month, year: this.year})
        })
    }
    // 下个月
    nextMonth () {
        return new Promise((resolve, reject) => {
            this.month++
            if (this.month == 13) {
                this.month = 1
                this.year++
            }
            resolve({month: this.month, year: this.year})
        })
    }

    // 获取近期12个月
    getLast12Months (year, month) {
        let last12Months = []
        for (var i = 0; i < 12; i++) {
            last12Months.push({year, month})
            if (month === 1) {
                year--
                month = 13
            }
            month--
        }
        return last12Months
    }
    // 前一天
    preDay () {
        return new Promise((resolve, reject) => {
            this.day--
            if (this.month > 1 && this.day == 0) {
                this.month --
                this.day = this.getMonthAllDays(this.year, this.month)
            } else if (this.month == 1 && this.day == 0) {
                this.year--
                this.month = 12
                this.day = this.getMonthAllDays(this.year, this.month)
            }
            resolve({year: this.year, month: this.month, day: this.day})
        })
    }
    // 后一天
    nextDay () {
        return new Promise((resolve, reject) => {
            let allDays = this.getMonthAllDays(this.year, this.month)
            this.day++
            if (this.month < 12 && this.day == allDays + 1) {
                this.month ++
                this.day = 1
            } else if (this.month == 12 && this.day == allDays + 1) {
                this.day = 1
                this.month = 1
                this.year++
            }
            resolve({year: this.year, month: this.month, day: this.day})
        })
    }
    // 获取当月总天数
    getMonthAllDays (year, month) {
        return new Date(year, month, 0).getDate()
    }
}
export default CalenderUtls
