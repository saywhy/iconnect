var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("myCtrl", function ($scope, $http, $filter) {
    rootScope = $scope;
    $scope.init = function () {
        $scope.colorType = {
            high: '#962116',
            mid: '#F5BF41',
            low: '#4AA46E',
            rgbaHigh10: 'rgba(150,33,22,1)',
            rgbaHigh8: 'rgba(150,33,22,.8)',
            rgbaHigh2: 'rgba(150,33,22,.2)',
            rgbaMid: 'rgba(245,191,65,1)',
            rgbaLow10: 'rgba(74,164,110,1)',
            rgbaLow8: 'rgba(74,164,110,.8)',
            rgbaLow2: 'rgba(74,164,110,.2)'
        };
        $scope.showpop = false; // 隐藏弹窗
        $scope.getData(); // 获取数据
        $scope.sysState();
        $scope.flowInfo();
        $scope.flowTotal();
        $scope.safetyEquipment(); //安全设备
        $scope.riskAssets(); // 风险资产
        $scope.untreatedAlarm(); // 未处理告警
        $scope.threatType(); // 威胁类型
        $scope.top5(); //风险资产top5
        $scope.newAlert(); //最新警告
        $scope.alarmsColor = false;
        $scope.assetsColor = false;
    };

    //  hover
    $scope.mouserover = function (item) {
        angular.forEach($scope.topinfo, function (key, value) {
            key.colorType = false;
            if (key.name == "告警总数" || key.name == "风险资产") {
                if (key.name == item.name) {
                    key.colorType = true;
                };
            };
        });
    };
    $scope.mouseleave = function (item) {
        angular.forEach($scope.topinfo, function (key, value) {
            key.colorType = false;
        });
    };
    $scope.numHandle = function (params) {
        if (params <= 999) {
            params = params.toString(); // 
        } else if (999 < params && params <= 99999) {
            params = params / 1000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'K'; // k 千
        } else if (99999 < params && params <= 9999999) {
            params = params / 100000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'M'; // m 兆
        } else if (9999999 < params && params <= 999999999) {
            params = params / 10000000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'G'; // g 吉（咖）
        } else if (999999999 < params && params <= 99999999999) {
            params = params / 1000000000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'T'; //t 太（拉）
        } else if (params > 99999999999 && params <= 99999999999999) {
            params = params / 100000000000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'P'; //p 拍（它）
        } else if (params > 99999999999 && params <= 99999999999999) {
            params = params / 100000000000000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'E'; //e 艾
        } else if (params > 99999999999999 && params <= 99999999999999999) {
            params = params / 100000000000000000;
            params = params.toString().split(".")[0] + '.' + params.toString().split(".")[1].substr(0, 1) + 'Z'; //z 泽
        }
        return params;
    };

    // 获取总揽信息
    $scope.getData = function (params) {
        $http({
            method: 'GET',
            url: '/alert/show-tabs'
        }).then(function (data, status, headers, config) {
            // 告警数量
            $scope.untreated_alarm_count_total = $scope.numHandle(data.data.untreated_alarm_count_total);
            // 风险资产
            $scope.risk_dev_count_total = $scope.numHandle(data.data.risk_dev_count_total);
            $scope.topinfo = [{
                    name: '安全评分',
                    num: data.data.safety_score,
                    type: 1,
                    id: 'safety',
                    head: 'fa fa-shield',
                    colorType: false
                },
                {
                    name: '告警总数',
                    num: $scope.untreated_alarm_count_total,
                    type: 0,
                    id: 'alarmCount',
                    head: 'fa fa-exclamation-triangle',
                    colorType: false
                },
                {
                    name: '风险资产',
                    num: $scope.risk_dev_count_total,
                    head: 'fa fa-database',
                    type: 0,
                    id: 'risk',
                    colorType: false
                },
                {
                    name: '日志总数',
                    num: '',
                    type: 1,
                    id: 'logCount',
                    head: 'fa fa-building',
                    colorType: false
                }
            ];
            //获取总日志数量
            $http({
                method: 'GET',
                url: '/alert/get-last365-total-logs'
            }).then(function (data, status, headers, config) {
                angular.forEach($scope.topinfo, function (key, value) {
                    if (key.name == "日志总数") {
                        key.num = $scope.numHandle(data.data);
                    };
                    // 数字增长
                    // var options = {
                    //     useEasing: true,
                    //     useGrouping: true,
                    //     separator: ',',
                    //     decimal: '.',
                    // };
                    // var demo = new CountUp(key.id, 0, key.num, 0, 3.5, options);
                    // if (!demo.error) {
                    //     demo.start();
                    // } else {
                    //     console.error(demo.error);
                    // }
                });
            }, function (error, status, headers, config) {
                console.log(error);
            });
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 跳转告警页面
    $scope.goto = function (item) {
        if (item.name == '告警总数') {
            window.location.href = '/alert/index';
        }
        // 风险资产
        if (item.name == "风险资产") {
            window.location.href = '/risk/index';
        }
    };

    // 第二排 左边图表--系统状态
    $scope.sysState = function (params) {
        $http({
            method: 'GET',
            url: '/alert/system-state'
        }).then(function (data, status, headers, config) {
            // 预警设备
            $scope.warningNum = [];
            data.data.cpu.id = 'sysEchartCpu';
            data.data.cpu.time = data.data.cpu.time.reverse();
            data.data.cpu.value = data.data.cpu.value.reverse();
            data.data.disk.id = 'sysEchartDisk';
            data.data.disk.time = data.data.disk.time.reverse();
            data.data.disk.value = data.data.disk.value.reverse();
            data.data.mem.id = 'sysEchartMem';
            data.data.mem.time = data.data.mem.time.reverse();
            data.data.mem.value = data.data.mem.value.reverse();
            $scope.sysEchartData = data.data;
            //  初始化健康和预警数量
            $scope.healthyNum = 0;
            $scope.alarmNum = 0;
            $scope.dataArray = [];
            for (var i in data.data) {
                $scope.healthyNum++;
            };
            angular.forEach(data.data, function (key, value) {
                $scope.dataObj = {};
                //data等价于array[index]
                if (key.if_alarm) {
                    $scope.dataObj = {
                        value: 10,
                        name: value,
                        itemStyle: {
                            normal: {
                                color: 'rgba(214,72,71,.8)'
                            }
                        }
                    }
                    $scope.alarmNum++;
                } else {
                    $scope.dataObj = {
                        value: "10",
                        name: value,
                        itemStyle: {
                            normal: {
                                color: 'rgba(131,186,174,.8)'
                            }
                        }
                    }
                    // 判断预警设备
                    $scope.warningNum.push(key);
                };
                $scope.dataArray.push($scope.dataObj);
            });
            $scope.system = [{
                    name: '预警',
                    color: 'box-block-red',
                    num: $scope.alarmNum
                },
                {
                    name: '健康',
                    color: 'box-block-green',
                    num: $scope.healthyNum - $scope.alarmNum
                },
                {
                    name: '离线',
                    color: 'box-block-gray',
                    num: 0
                }
            ];
            var myChart = echarts.init(document.getElementById('sys'));
            var option = {
                series: [{
                        name: '访问来源',
                        type: "pie",
                        silent: 'true', //不响应hover事件
                        radius: ["50%", "75%"],
                        center: ["50%", "50%"],
                        hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
                        legendHoverLink: false, //是否启用图例 hover 时的联动高亮。
                        hoverOffset: 0, //高亮扇区的偏移距离。
                        avoidLabelOverlap: false,
                        label: {
                            normal: {
                                show: false,
                                position: "center"
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data: $scope.dataArray
                    },
                    {
                        name: '姓名',
                        type: 'pie',
                        radius: '50%',
                        center: ['50%', '50%'],
                        silent: 'true', //不响应hover事件
                        hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
                        legendHoverLink: false, //是否启用图例 hover 时的联动高亮。
                        hoverOffset: 0, //高亮扇区的偏移距离。
                        labelLine: {
                            normal: {
                                show: false
                            }
                        },
                        data: [11],

                        itemStyle: {
                            normal: {
                                color: 'rgba(131,186,174,1)'
                            }
                        }
                    }
                ]
            };

            myChart.setOption(option);

        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 第二排 中间图表--流量信息
    //告警日志
    $scope.flowInfo = function (params) {
        //获取总日志数量
        $http({
            method: 'GET',
            url: '/alert/get-last24-alarms'
        }).then(function (data, status, headers, config) {
            $scope.alarmsDataName = [];
            $scope.alarmsDataNum = [];
            var objData = JSON.parse(data.data).alarms[0];
            for (var key in objData) {
                $scope.alarmsDataName.push(key);
                $scope.alarmsDataNum.push(objData[key]);
            };
            var myChart = echarts.init(document.getElementById('flowinfo'));
            var option = {
                grid: {
                    left: 45,
                    right: 30,
                    top: 15,
                    bottom: 25
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#7588E4',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                xAxis: {
                    type: 'category',
                    data: $scope.alarmsDataName,
                    boundaryGap: false,
                    splitLine: {
                        show: true,
                        interval: 0, //0：表示全部显示不间隔；auto:表示自动根据刻度个数和宽度自动设置间隔个数
                        maxInterval: 3600 * 24 * 1000
                    },
                    axisTick: {
                        show: false
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                series: [{
                    name: '告警日志',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: $scope.alarmsDataNum,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: $scope.colorType.rgbaHigh8
                            }, {
                                offset: 1,
                                color: $scope.colorType.rgbaHigh2
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: $scope.colorType.rgbaHigh10
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 3
                        }
                    }
                }]
            };
            myChart.setOption(option);
            // 当相应准备就绪时调用
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 实时日志
    $scope.flowTotal = function (params) {
        $http({
            method: 'GET',
            url: '/alert/get-last24-logs'
        }).then(function (data, status, headers, config) {

            $scope.dataArray = [];
            $scope.dataNum = [];
            angular.forEach(data.data, function (key, value) {
                $scope.dataArray.push(key[0]);
                $scope.dataNum.push(key[1]);
            });
            var myChart = echarts.init(document.getElementById('flowtotal'));
            var option = {
                grid: {
                    left: 45,
                    right: 30,
                    top: 15,
                    bottom: 25
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#7588E4',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                xAxis: {
                    type: 'category',
                    data: $scope.dataArray,
                    boundaryGap: false,
                    splitLine: {
                        show: true,
                        interval: 0, //0：表示全部显示不间隔；auto:表示自动根据刻度个数和宽度自动设置间隔个数
                        maxInterval: 3600 * 24 * 1000,
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 10
                        }
                    }
                },
                series: [{
                    name: '实时日志',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: $scope.dataNum,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: $scope.colorType.rgbaLow8
                            }, {
                                offset: 1,
                                color: $scope.colorType.rgbaLow2
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: $scope.colorType.rgbaLow10
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 3
                        }
                    }
                }]
            };
            myChart.setOption(option);
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 第二排右边图表 -- 安全设备
    $scope.safetyEquipment = function (params) {
        //获取安全设备数量
        $http({
            method: 'GET',
            url: '/alert/safety-equipment'
        }).then(function (data, status, headers, config) {
            $scope.safetyNum = data.data.safety_equipment_count;
            // 告警总数
            $scope.logsNum2 = data.data.all_safety_equipment_alert_count;
            //离线设备
            $scope.offlineNum = data.data.offline_equipment;
            $scope.datavalue = [];
            angular.forEach(data.data.safety_equipment, function (item, index) {
                var itemObj = {};
                itemObj.value = item.alerm_count;
                itemObj.name = 'Top' + (index + 1);
                itemObj.head = item.name;
                itemObj.logs_count = $scope.numHandle(item.logs_count);
                $scope.datavalue.push(itemObj);
            });
            var myChart = echarts.init(document.getElementById('safetyequipment'));
            var option = {
                tooltip: {
                    trigger: 'item',
                    formatter: function (params) {
                        return '安全名称：' + params.data.head + '</br>' + '告警总数：' + params.data.value + '</br>' + '日志总数：' + params.data.logs_count
                    }
                },
                series: [{
                    type: 'pie',
                    radius: ['50%', '70%'],
                    center: ["50%", "50%"],
                    hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
                    legendHoverLink: true, //是否启用图例 hover 时的联动高亮。
                    hoverOffset: 0, //高亮扇区的偏移距离。
                    avoidLabelOverlap: false,
                    avoidLabelOverlap: false,
                    label: {
                        normal: {
                            show: false,
                            position: 'center'
                        },
                        emphasis: {
                            show: true,
                            textStyle: {
                                fontSize: '30',
                                fontWeight: 'bold'
                            }
                        }
                    },
                    labelLine: {
                        normal: {
                            show: false
                        }
                    },
                    data: $scope.datavalue
                }]
            };
            myChart.setOption(option);
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };

    // 第三排 左边 -- 风险资产
    $scope.riskAssets = function (params) {
        $http({
            method: 'GET',
            url: '/alert/risk-assets'
        }).then(function (data, status, headers, config) {
            $scope.riskData = [];
            $scope.riskHigh = [];
            $scope.riskMiddle = [];
            $scope.riskLow = [];
            angular.forEach(data.data, function (key, vaule) {
                $scope.riskData.push(key.statistics_time);
                $scope.riskHigh.push(key.alert_details.high);
                $scope.riskMiddle.push(key.alert_details.medium);
                $scope.riskLow.push(key.alert_details.low);
            });
            var myChart = echarts.init(document.getElementById('riskassets'));
            var option = {
                tooltip: {
                    trigger: 'axis',
                    axisPointer: { // 坐标轴指示器，坐标轴触发有效
                        type: 'shadow' // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    top: '5%',
                    containLabel: true
                },
                xAxis: {
                    type: 'category',
                    data: $scope.riskData,
                    axisTick: {
                        show: false
                    }
                },
                yAxis: [{
                    type: 'value',
                    axisTick: {
                        show: false
                    }
                }],
                series: [{
                        name: '高危',
                        type: 'bar',
                        barWidth: 20,
                        stack: '搜索引擎',
                        itemStyle: {
                            normal: {
                                barBorderRadius: [0, 0, 4, 4], //柱形图圆角，初始化效果
                                color: $scope.colorType.high
                            }
                        },
                        data: $scope.riskHigh
                    },
                    {
                        name: '中危',
                        type: 'bar',
                        stack: '搜索引擎',
                        itemStyle: {
                            normal: {
                                color: $scope.colorType.mid
                            }
                        },
                        data: $scope.riskMiddle
                    },
                    {
                        name: '低危',
                        type: 'bar',
                        stack: '搜索引擎',
                        itemStyle: {
                            normal: {
                                barBorderRadius: [4, 4, 0, 0], //柱形图圆角，初始化效果
                                color: $scope.colorType.low
                            }
                        },
                        data: $scope.riskLow
                    }
                ]
            };
            myChart.setOption(option);
            // 当相应准备就绪时调用
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 第三排 中间 -- 未处理告警
    $scope.untreatedAlarm = function (params) {
        $http({
            method: 'GET',
            url: '/alert/untreated-alarm-type'
        }).then(function (data, status, headers, config) {
            angular.forEach(data.data, function (key, value) {
                if (key.degree == '低') {
                    $scope.lowNum = key.total_count
                }
                if (key.degree == '高') {
                    $scope.highNum = key.total_count
                }
                if (key.degree == '中') {
                    $scope.mediumNum = key.total_count
                }
            });
            var myChart = echarts.init(document.getElementById('untreatedalarm'));
            var option = {
                tooltip: {
                    trigger: 'item',
                    formatter: "{b}:{c}({d}%)"
                },
                series: [{
                    name: '未处理告警',
                    type: 'pie',
                    radius: '65%',
                    center: ['50%', '50%'],
                    hoverAnimation: false, //是否开启 hover 在扇区上的放大动画效果。
                    hoverOffset: 0, //高亮扇区的偏移距离。
                    selectedMode: 'single',
                    data: [{
                            value: $scope.highNum,
                            name: '高危',
                            itemStyle: {
                                normal: {
                                    color: $scope.colorType.high
                                }
                            }
                        },
                        {
                            value: $scope.mediumNum,
                            name: '中危',
                            itemStyle: {
                                normal: {
                                    color: $scope.colorType.mid
                                }
                            }
                        },
                        {
                            value: $scope.lowNum,
                            name: '低危',
                            itemStyle: {
                                normal: {
                                    color: $scope.colorType.low
                                }
                            }
                        }
                    ],
                    itemStyle: {
                        normal: {
                            label: {
                                show: true,
                                formatter: '{b} : {c} \n ({d}%)'
                            },
                            labelLine: {
                                show: true
                            }
                        },
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }]
            };
            myChart.setOption(option);
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };
    // 第三排 右边 --威胁类型
    $scope.threatType = function (params) {
        $http({
            method: 'GET',
            url: '/alert/threat-type'
        }).then(function (data, status, headers, config) {
            $scope.threatTypeName = [];
            $scope.threatTypeNum = [];
            angular.forEach(data.data, function (key, value) {
                $scope.threatTypeName.push(key.type);
                $scope.threatTypeNum.push(key.total_count);
            });
            var myChart = echarts.init(document.getElementById('threattype'));
            var option = {
                tooltip: {
                    trigger: 'axis',
                    formatter: "{b}:{c}",
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                grid: {
                    left: '3%',
                    right: '6%',
                    bottom: '3%',
                    top: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'value',
                    boundaryGap: [0, 0.01],
                    axisTick: {
                        show: false
                    }
                },
                yAxis: {
                    type: 'category',
                    data: $scope.threatTypeName,
                    axisTick: {
                        show: false
                    }
                },
                series: [{
                    name: '2012年',
                    type: 'bar',
                    barWidth: 20, //柱图宽度
                    itemStyle: {
                        normal: {
                            barBorderRadius: [4, 4, 4, 4], //柱形图圆角，初始化效果
                            color: $scope.colorType.high,
                            padding: [0, 0, 5, 0]
                        }
                    },
                    data: $scope.threatTypeNum
                }]
            };

            myChart.setOption(option);

            // 当相应准备就绪时调用
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };

    //风险资产Top5 
    $scope.top5 = function (params) {
        $scope.top5Data = {};
        $http({
            method: 'GET',
            url: '/alert/risk-assets-sort'
        }).then(function (data, status, headers, config) {
            angular.forEach(data.data, function (item, index) {
                item.style = {
                    width: item.indicator + '%',
                    borderRadius: '5px',
                };
                if (item.indicator >= 90) {
                    item.style.backgroundColor = $scope.colorType.rgbaHigh8
                };
                if (item.indicator >= 70 && item.indicator < 90) {
                    item.style.backgroundColor = 'rgba(254,127,0,.8)'
                };
                if (item.indicator >= 50 && item.indicator < 70) {
                    item.style.backgroundColor = '#FE9B20'
                };
                if (item.indicator >= 30 && item.indicator < 50) {
                    item.style.backgroundColor = '#FEBB11'
                };
                if (item.indicator < 30) {
                    item.style.backgroundColor = '#FECC01'
                };
            });
            $scope.top5Data = data.data;
            // 当相应准备就绪时调用
        }, function (error, status, headers, config) {
            console.log(error);
        });
    };

    //最新告警
    $scope.newAlert = function (params) {
        $scope.getTime = new Date();
        var postData = {
            client_ip: "",
            endTime: $scope.getTime.valueOf().toString().substring(0, 10),
            page: 1,
            startTime: ($scope.getTime.valueOf() - 86400000 * 300).toString().substring(0, 10)
        };
        $http.post('/alert/page', postData).then(function success(rsp) {
            // angular.forEach(rsp.data.data, function (item, index) {
            //     $scope.hoohoolab_false = false;
            //     angular.forEach(JSON.parse(item.data).attr.sources, function (key, value) {
            //         if (key.split('_')[0] == 'hoohoolab') {
            //             $scope.hoohoolab_false = true;
            //             switch (key.split('_')[1]) {
            //                 case 'BotnetCAndCURL':
            //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
            //                     break;
            //                 case 'IPReputation':
            //                     // 多种判断
            //                     if (JSON.parse(item.data).attr.hoohoolab_category.indexOf(',') != -1) {
            //                         var arrayCategory = JSON.parse(item.data).attr.hoohoolab_category.split(',');
            //                         angular.forEach(arrayCategory, function (gx, dx) {
            //                             gx = $.trim(gx);
            //                             if (gx == 'malware') {
            //                                 arrayCategory[dx] = '恶意地址';
            //                             } else if (gx == 'spam') {
            //                                 arrayCategory[dx] = '垃圾邮件';
            //                             } else if (gx == 'botnet_cnc') {
            //                                 arrayCategory[dx] = '僵尸网络';
            //                             } else if (gx == 'proxy') {
            //                                 arrayCategory[dx] = '网络代理';
            //                             } else if (gx == 'tor_node') {
            //                                 arrayCategory[dx] = 'tor入口节点';
            //                             } else if (gx == 'tor_exit_node') {
            //                                 arrayCategory[dx] = 'tor出口节点';
            //                             } else if (gx == 'phishing') {
            //                                 arrayCategory[dx] = '钓鱼网站';
            //                             }
            //                         });
            //                         item.category = arrayCategory.join(',');
            //                     } else {
            //                         if (JSON.parse(item.data).attr.hoohoolab_category == 'malware') {
            //                             item.category = '恶意地址';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'spam') {
            //                             item.category = '垃圾邮件';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'tor_exit_node') {
            //                             item.category = '加密通道';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'botnet_cnc') {
            //                             item.category = '僵尸网络';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'proxy') {
            //                             item.category = '网络代理';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'tor_node') {
            //                             item.category = 'tor入口节点';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'tor_exit_node') {
            //                             item.category = 'tor出口节点';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'phishing') {
            //                             item.category = '钓鱼网站';
            //                         } else {
            //                             item.category = JSON.parse(item.data).attr.hoohoolab_category;
            //                         }
            //                     }
            //                     break;
            //                 case 'MaliciousHash':
            //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
            //                     break;
            //                 case 'MaliciousURL':
            //                     // 多种判断
            //                     if (JSON.parse(item.data).attr.hoohoolab_category.indexOf(',') != -1) {
            //                         var arrayCategory = JSON.parse(item.data).attr.hoohoolab_category.split(',');
            //                         angular.forEach(arrayCategory, function (gx, dx) {
            //                             gx = $.trim(gx);
            //                             if (gx == 'Malware') {
            //                                 arrayCategory[dx] = '恶意地址';
            //                             } else if (gx == 'Bot C&C') {
            //                                 arrayCategory[dx] = '僵尸网络';
            //                             } else if (gx == 'Fraud') {
            //                                 arrayCategory[dx] = '网络诈骗';
            //                             } else if (gx == 'MobileMalware or Malicious redirect') {
            //                                 arrayCategory[dx] = '移动恶意软件及恶意重定向';
            //                             }
            //                         });
            //                         item.category = arrayCategory.join(',');
            //                     } else {
            //                         if (JSON.parse(item.data).attr.hoohoolab_category == 'Malware') {
            //                             item.category = '恶意地址';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'Bot C&C') {
            //                             item.category = '僵尸网络';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'Fraud') {
            //                             item.category = '网络诈骗';
            //                         } else if (JSON.parse(item.data).attr.hoohoolab_category == 'MobileMalware or Malicious redirect') {
            //                             item.category = '移动恶意软件及恶意重定向';
            //                         } else {
            //                             item.category = JSON.parse(item.data).attr.hoohoolab_category; // 威胁类型
            //                         };
            //                     }
            //                     break;
            //                 case 'PhishingURL':
            //                     item.category = '钓鱼网站'; // 威胁类型
            //                     break;
            //                 case 'MobileMaliciousHash':
            //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
            //                     break;
            //                 default:
            //                     break;
            //             }

            //         }
            //     });
            //     // 开源情报匹配
            //     if (!$scope.hoohoolab_false) {
            //         switch (item.category) {
            //             case 'MalwareIP':
            //                 item.category = '恶意地址';
            //                 break;
            //             case 'C&C':
            //                 item.category = '僵尸网络';
            //                 break;
            //             case 'Malicious Host':
            //                 item.category = '恶意地址';
            //                 break;
            //             case 'Spamming':
            //                 item.category = '垃圾邮件';
            //                 break;
            //             default:
            //                 break;
            //         }
            //     };
            //     switch (item.degree) {
            //         case 'low':
            //             item.degree_cn = '低';
            //             break;
            //         case 'medium':
            //             item.degree_cn = '中';
            //             break;
            //         case 'high':
            //             item.degree_cn = '高';
            //             break;
            //         default:
            //             break;
            //     }

            // });
            $scope.newAlertData = rsp.data.data.slice(0, 5);
        }, function err(rsp) {
            console.log(rsp);
        });
    };
    $scope.goAlarm = function(){
        window.location.href = '/alert/index';
    }
    $scope.showLength = function (str, length) {
        if (!length) {
            length = 60;
        }
        return str.substr(0, length) + '...';
    };
    $scope.showState = function (item) {
        if (item.num != 0) {
            $scope.showpop = true; //  显示弹窗
            $scope.sysEchart($scope.sysEchartData);
        };
    };
    $scope.popfasle = function (event) {
        $scope.showpop = false; //隐藏弹窗
    };
    //弹窗系统状态图表
    $scope.sysEchart = function (params) {
        setTimeout(function (paramsdata) {
            // cpu
            var myChartCpu = echarts.init(document.getElementById(params.cpu.id));
            var optionCpu = {
                legend: {
                    data: ['Cpu'],
                    x: 20
                },
                grid: {
                    left: 30,
                    right: 30,
                    top: 30,
                    bottom: 25
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#7588E4',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                xAxis: {
                    type: 'category',
                    data: params.cpu.time,
                    boundaryGap: false,
                    splitLine: {
                        show: true,
                        interval: 0, //0：表示全部显示不间隔；auto:表示自动根据刻度个数和宽度自动设置间隔个数
                        maxInterval: 3600 * 24 * 1000,
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                visualMap: {
                    show: false,
                    type: 'piecewise',
                    pieces: [{
                        gt: 85,
                        color: $scope.colorType.rgbaHigh10
                    }, {
                        gt: 0,
                        lte: 85,
                        color: $scope.colorType.rgbaLow10
                    }]
                },
                series: [{
                    name: 'Cpu',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: params.cpu.value,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: $scope.colorType.rgbaLow8
                            }, {
                                offset: 1,
                                color: $scope.colorType.rgbaLow2
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: $scope.colorType.rgbaLow10
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 3
                        }
                    }
                }]
            };
            myChartCpu.setOption(optionCpu);

            // mem
            var myChartMem = echarts.init(document.getElementById(params.mem.id));
            var optionMem = {
                legend: {
                    data: ['Mem'],
                    x: 20
                },
                grid: {
                    left: 30,
                    right: 30,
                    top: 30,
                    bottom: 25
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#7588E4',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                xAxis: {
                    type: 'category',
                    data: params.mem.time,
                    boundaryGap: false,
                    splitLine: {
                        show: true,
                        interval: 0, //0：表示全部显示不间隔；auto:表示自动根据刻度个数和宽度自动设置间隔个数
                        maxInterval: 3600 * 24 * 1000,
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                visualMap: {
                    show: false,
                    type: 'piecewise',
                    pieces: [{
                        gt: 85,
                        color: $scope.colorType.rgbaHigh10
                    }, {
                        gt: 0,
                        lte: 85,
                        color: $scope.colorType.rgbaLow10
                    }]
                },
                series: [{
                    name: 'Mem',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: params.mem.value,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: $scope.colorType.rgbaLow8
                            }, {
                                offset: 1,
                                color: $scope.colorType.rgbaLow2
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: $scope.colorType.rgbaLow10
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 3
                        }
                    }
                }]
            };
            myChartMem.setOption(optionMem);

            // disk
            var myChartDisk = echarts.init(document.getElementById(params.disk.id));
            var optionDisk = {
                legend: {
                    data: ['Disk'],
                    x: 20
                },
                grid: {
                    left: 30,
                    right: 30,
                    top: 30,
                    bottom: 25
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        lineStyle: {
                            color: '#ddd'
                        }
                    },
                    backgroundColor: 'rgba(255,255,255,1)',
                    padding: [5, 10],
                    textStyle: {
                        color: '#7588E4',
                    },
                    extraCssText: 'box-shadow: 0 0 5px rgba(0,0,0,0.3)'
                },
                visualMap: {
                    show: false,
                    type: 'piecewise',
                    pieces: [{
                        gt: 90,
                        color: $scope.colorType.rgbaHigh10
                    }, {
                        gt: 0,
                        lte: 90,
                        color: $scope.colorType.rgbaLow10
                    }]
                },
                xAxis: {
                    type: 'category',
                    data: params.disk.time,
                    boundaryGap: false,
                    splitLine: {
                        show: true,
                        interval: 0, //0：表示全部显示不间隔；auto:表示自动根据刻度个数和宽度自动设置间隔个数
                        maxInterval: 3600 * 24 * 1000,
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                yAxis: {
                    type: 'value',
                    splitLine: {
                        lineStyle: {
                            // color: ['#D4DFF5']
                        }
                    },
                    axisTick: {
                        show: false
                    },
                    axisLine: {
                        lineStyle: {
                            // color: '#609ee9'
                        }
                    },
                    axisLabel: {
                        margin: 5,
                        textStyle: {
                            fontSize: 14
                        }
                    }
                },
                series: [{
                    name: 'Disk',
                    type: 'line',
                    smooth: true,
                    showSymbol: false,
                    symbol: 'circle',
                    symbolSize: 6,
                    data: params.disk.value,
                    areaStyle: {
                        normal: {
                            color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                offset: 0,
                                color: $scope.colorType.rgbaLow8
                            }, {
                                offset: 1,
                                color: $scope.colorType.rgbaLow2
                            }], false)
                        }
                    },
                    itemStyle: {
                        normal: {
                            color: $scope.colorType.rgbaLow10
                        }
                    },
                    lineStyle: {
                        normal: {
                            width: 3
                        }
                    }
                }]
            };
            myChartDisk.setOption(optionDisk);

        }, 100);
    };
    $scope.init();
});