const DATETIME_FORMAT = 'YYYY-MM-DD H:mm:ss';
const moment = require('moment');
export default {
    data: () => ({
        pickerOptions: {
            firstDayOfWeek: 1,
            shortcuts: [
                {
                    text: 'Today',
                    onClick(picker) {
                        const start = moment().set({hours: 0, minutes: 0, seconds: 0}).format(DATETIME_FORMAT);
                        const end = moment().set({hours: 23, minutes: 59, seconds: 59}).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'Yesterday',
                    onClick(picker) {
                        const start = moment().subtract(1, 'days').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().subtract(1, 'days').set({
                            hours: 23,
                            minutes: 59,
                            seconds: 59
                        }).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'This week',
                    onClick(picker) {
                        const start = moment().startOf('isoWeek').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().endOf('day').set({
                            hours: 23,
                            minutes: 59,
                            seconds: 59
                        }).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'Previous week',
                    onClick(picker) {
                        const start = moment().subtract(1, 'weeks').startOf('isoWeek').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().subtract(1, 'weeks').endOf('isoWeek').set({
                            hours: 23,
                            minutes: 59,
                            seconds: 59
                        }).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'Last 30 Days',
                    onClick(picker) {
                        const start = moment().subtract(29, 'days').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().set({hours: 23, minutes: 59, seconds: 59}).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'This Month',
                    onClick(picker) {
                        const start = moment().startOf('month').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().endOf('month').set({
                            hours: 23,
                            minutes: 59,
                            seconds: 59
                        }).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
                {
                    text: 'Last Month',
                    onClick(picker) {
                        const start = moment().subtract(1, 'month').startOf('month').set({
                            hours: 0,
                            minutes: 0,
                            seconds: 0
                        }).format(DATETIME_FORMAT);
                        const end = moment().subtract(1, 'month').endOf('month').set({
                            hours: 23,
                            minutes: 59,
                            seconds: 59
                        }).format(DATETIME_FORMAT);
                        picker.$emit('pick', [start, end]);
                    }
                },
            ]
        },
    }),
}