/* Controllers */
// 初始化
var rootScope;
var myApp = angular.module("myApp", []);
myApp.controller("myCtrl", function ($scope, $http, $filter) {
    $scope.init = function (params) {
        $scope.stateParamsData = JSON.parse(decodeURI(window.location.pathname.split('/')[3]))
        if ($scope.stateParamsData.degree == '') {
            $scope.risk_title = '告警总数'
        }
        if ($scope.stateParamsData.degree == '低') {
            $scope.risk_title = '低危告警'
        }
        if ($scope.stateParamsData.degree == '中') {
            $scope.risk_title = '中危告警'
        }
        if ($scope.stateParamsData.degree == '高') {
            $scope.risk_title = '高危告警'
        }
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
        $scope.pages = {
            data: [],
            count: 0,
            maxPage: "...",
            pageNow: 1,
        };
        $scope.getPage(); // 获取用户列表
    };
    // 获取风险资产告警列表
    $scope.getPage = function (pageNow) {
        var loading = zeroModal.loading(4);
        pageNow = pageNow ? pageNow : 1;
        $scope.index_num = (pageNow - 1) * 10;
        $scope.params_data = {
            page: pageNow,
            rows: 10,
            asset_ip: $scope.stateParamsData.asset_ip,
            degree: $scope.stateParamsData.degree
        };
        console.log($scope.params_data);
        
        $http({
            method: 'get',
            url: '/risk/detail-list',
            params: $scope.params_data,
        }).then(function (rsp) {
            console.log(rsp);
            // angular.forEach(rsp.data.data, function (item, index) {
            //     $scope.hoohoolab_false = false;
            //     angular.forEach(JSON.parse(item.data).attr.sources, function (key, value) {
            //         // console.log(item.category);
            //         if (key.split('_')[0] == 'hoohoolab') {
            //             $scope.hoohoolab_false = true;
            //             switch (key.split('_')[1]) {
            //                 case 'BotnetCAndCURL':
            //                     // item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
            //                     item.category = '僵尸网络C&C' // 威胁类型
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
            // });
            zeroModal.close(loading);
            $scope.setPage(rsp.data);
        })
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
            // switch (item.degree) {
            //     case 'low':
            //         item.degree_cn = '低';
            //         break;
            //     case 'medium':
            //         item.degree_cn = '中';
            //         break;
            //     case 'high':
            //         item.degree_cn = '高';
            //         break;
            //     default:
            //         break;
            // }
        })
        $scope.pages = data;
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
    // 默认是未解决
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
    // 操作 已解决
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
                console.log(data);
                if (data.status== 'success') {
                    $scope.getPage();
                }
            }
        })
    };
    // 跳转详情页面
    $scope.detail = function (item) {
        console.log(item);
        window.location.href = '/alert/' + item.id;
    };
    $scope.init();
});