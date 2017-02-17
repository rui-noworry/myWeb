/**
 * Created by huangrui on 2017/2/8.
 * 日历组件
 */
const CalenderUtls = {
    // 日历布局
    getCalender ({year, month}) {
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
                        calenderArr.push({year: year, month: month, day: n})
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
    },
    // 上个月
    preMonth ({year, month}) {
        return new Promise((resolve, reject) => {
            month--
            if (month == 0) {
                month = 12
                year--
            }
            resolve({month: month, year: year})
        })
    },
    // 下个月
    nextMonth ({year, month}) {
        return new Promise((resolve, reject) => {
            month++
            if (month == 13) {
                month = 1
                year++
            }
            resolve({month: month, year: year})
        })
    },
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
    },
    // 前一天
    preDay ({year, month, day}) {
        return new Promise((resolve, reject) => {
            day--
            if (month > 1 && day == 0) {
                month--
                day = this.getMonthAllDays(year, month)
            } else if (month == 1 && day == 0) {
                year--
                month = 12
                day = this.getMonthAllDays(year, month)
            }
            resolve({year: year, month: month, day: day})
        })
    },
    // 后一天
    nextDay ({year, month, day}) {
        return new Promise((resolve, reject) => {
            let allDays = this.getMonthAllDays(year, month)
            day++
            if (month < 12 && day == allDays + 1) {
                month++
                day = 1
            } else if (month == 12 && day == allDays + 1) {
                day = 1
                month = 1
                year++
            }
            resolve({year: year, month: month, day: day})
        })
    },
    // 获取当月总天数
    getMonthAllDays (year, month) {
        return new Date(year, month, 0).getDate()
    }
}
export default CalenderUtls
