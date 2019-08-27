var myApp = angular.module('myApp', []);
myApp.controller('myCtrl', function ($scope, $http, $filter) {
    $scope.SensorVersion = false;
    $scope.rsqType = true;
    $scope.statusData = [{
        num: 3,
        type: '所有'
    }, {
        num: 2,
        type: '已解决'
    }, {
        num: 0,
        type: '未解决'
    }];
    $scope.degreeData = [{
        num: '',
        type: '所有'
    }, {
        num: '高',
        type: '高'
    }, {
        num: '中',
        type: '中'
    }, {
        num: '低',
        type: '低'
    }];
    $scope.selectedDegree = '';
    // 折线图表
    $scope.alertEcharts = function (params) {
        $http({
            method: 'GET',
            url: '/alert/get-alert-count'
        }).then(function (data, status, headers, config) {
            var myChart = echarts.init(document.getElementById('alertEchart'));
            var option = {
                grid: {
                    bottom: 80,
                    top: 50,
                    left: 50,
                    right: 50
                },
                tooltip: {
                    trigger: 'axis',
                },
                dataZoom: [{
                        show: true,
                        realtime: true,
                        start: 80,
                        end: 100
                    },
                    {
                        type: 'inside',
                        realtime: true,
                        start: 80,
                        end: 100
                    }
                ],
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    axisLine: {
                        onZero: false
                    },
                    data: data.data.times.map(function (str) {
                        return str.replace(' ', '\n')
                    }),
                    axisTick: {
                        show: false
                    }
                }],
                yAxis: [{
                    name: '告警',
                    type: 'value',
                    axisTick: {
                        show: false
                    }
                }],
                series: [{
                        name: '告警',
                        type: 'line',
                        smooth: true,
                        showSymbol: false,
                        symbol: 'circle',
                        symbolSize: 3,
                        areaStyle: {
                            normal: {
                                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                    offset: 0,
                                    color: 'rgba(150,33,22,.8)'
                                }, {
                                    offset: 1,
                                    color: 'rgba(150,33,22,.5)'
                                }], false)
                            }
                        },
                        animation: true,
                        lineStyle: {
                            normal: {
                                width: 3
                            }
                        },
                        data: data.data.alert_count
                    }

                ]
            };
            myChart.setOption(option);
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    $scope.alertEcharts();
    // 默认是未解决
    $scope.selectedName = 0;
    $scope.setAriaID = function (item, $event) {
        $event.stopPropagation();
        if ($scope.ariaID == item.id) {
            $scope.ariaID = null;
        } else {
            $scope.ariaID = item.id;
        }
    };
    $scope.delAriaID = function ($event) {
        $event.stopPropagation();
        setTimeout(function () {
            $scope.ariaID = null;
        }, 10);
    };
    $scope.status_str = [{
        css: 'success',
        label: '新告警'
    }, {
        css: 'danger',
        label: '未解决'
    }, {
        css: 'default',
        label: '已解决'
    }];

    $scope.update = function (item) {
        var dataJson = {
            id: item.id,
            status: '2'
        };
        var params = JSON.stringify(dataJson);
        $.ajax({
            url: '/alert/do-alarm',
            method: 'PUT',
            data: params,
            dataType: 'json',
            success: function (data) {
                if (data.status == 'success') {
                    $scope.search();
                }
            }
        })
    };
    $scope.pages = {
        data: [],
        count: 0,
        maxPage: "...",
        pageNow: 1,
    };
    $scope.IDList = [];
    $scope.ItemList = {};
    $scope.getPage = function (pageNow) {
        pageNow = pageNow ? pageNow : 1;
        $scope.pageGeting = true;
        var postData = {};
        if ($scope.postData) {
            postData = angular.copy($scope.postData);
        };
        postData['page'] = pageNow;
        $scope.loading = zeroModal.loading(4);
        $http.post('/alert/page', postData).then(function success(rsp) {
            zeroModal.close($scope.loading);
            $scope.setPage(rsp.data);
        }, function err(rsp) {
            console.log(rsp);
        });
    };
    $scope.setPage = function (data) {
        angular.forEach(data.data, function (item, index) {
            switch (item.status) {
                case '0':
                    item.statusName = '新告警';
                    break;
                case '1':
                    item.statusName = '未解决';
                    break;
                case '2':
                    item.statusName = '已解决';
                    break;
                default:
                    break;
            }
        })
        $scope.pages = data;
        sessionStorage.setItem('alertPage', $scope.pages.pageNow);
    };
    // 点击跳转详情
    $scope.detail = function (item) {
        window.location.href = '/alert/' + item.id;
    };

    $scope.del = function (item, $event) {
        zeroModal.confirm({
            content: '确定删除这条告警吗？',
            okFn: function () {
                var postData = {
                    page: sessionStorage.getItem('alertPage'),
                    id: item.id
                };
                $http.post('/alert/del', postData).then(function success(rsp) {
                    $scope.setPage(rsp.data);
                }, function err(rsp) {});
            },
            cancelFn: function () {}
        });
    };

    $scope.showLength = function (str, length) {
        if (!length) {
            length = 60;
        }
        return str.substr(0, length) + '...';
    };

    $scope.myKeyup = function (e) {
        var keycode = window.event ? e.keyCode : e.which;
        if (keycode == 13) {
            e.target.blur();
            $scope.search();
        }
    };

    $('.timerange').daterangepicker({
        timePicker: true,
        timePickerIncrement: 10,
        startDate: moment().subtract(365, 'days'),
        endDate: moment(),
        locale: {
            applyLabel: '确定',
            cancelLabel: '取消',
            format: 'YYYY-MM-DD HH:mm',
            customRangeLabel: '指定时间范围'
        },
        ranges: {
            '今天': [moment().startOf('day'), moment().endOf('day')],
            '7日内': [moment().startOf('day').subtract(7, 'days'), moment().endOf('day')],
            '本月': [moment().startOf('month'), moment().endOf('day')],
            '今年': [moment().startOf('year'), moment().endOf('day')],
        }
    }, function (start, end, label) {
        $scope.searchData.startTime = start.unix();
        $scope.searchData.endTime = end.unix();
    });
    $scope.searchData = {
        client_ip: '',
        startTime: moment().subtract(365, 'days').unix(),
        endTime: moment().unix(),
        indicator: '',
        device_name: '',
        degree: '',
        category: '',
    };
    $scope.postData = {};
    $scope.search = function (key_change) {
        if ($scope.selectedName == '2' || $scope.selectedName == '3') {
            $scope.rsqType = false;
        } else {
            $scope.rsqType = true;
        };
        $scope.searchData.status = $scope.selectedName;
        $scope.searchData.degree = $scope.selectedDegree;
        $scope.postData = angular.copy($scope.searchData);
        $scope.getPage();
    };
    $scope.search();
    $scope.pageGeting = false;
});