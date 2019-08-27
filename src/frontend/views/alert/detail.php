<?php
/* @var $this yii\web\View */

$this->title = '告警详情';
?>
<style>
    .tab-title {
            display: inline-block;
            width: 100px;
            cursor: pointer;
            border-right: 1px solid #ddd;
            border-top: 2px solid #3c8dbc;
        }

        .box_centent {
            display: inline-block;
            float: left;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .box_centent_p {
            width: 1000px;
            margin: 5px 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        td,
        th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }

        .li_border,
        .border-right {
            border: 0 !important;
        }

        .box_centent_right {
            margin-left: 10px;
        }

        .li_border,
        .border-right {
            border: 0 !important;
        }
    </style>
<section class="content" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-bell-o"></i>
                        <span ng-bind="detail.indicator"></span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <!-- 下一个版本添加 -->
                    <div class="row" ng-if="!hoohoolabInfo">
                        <div class="col-md-6 border-right">
                            <ul class="nav nav-stacked sensor-detail">
                                <li class="li_border">
                                    <span class="sensor-detail-title">威胁指标</span>
                                    <span ng-bind="detail.indicator"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">风险资产</span>
                                    <span ng-bind="alert.client_ip"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警设备IP</span>
                                    <span ng-bind="detail.device_ip"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">信心指数</span>
                                    <span ng-bind="detail.attr.confidence"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">威胁程度</span>
                                    <span class="text-yellow">
                                        <i class="fa {{item}}" ng-repeat="item in detail.attr.threat_arr track by $index"></i>
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border-right">
                            <ul class="nav nav-stacked sensor-detail">
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警类型</span>
                                    <span ng-bind="alert.category"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">指标类型</span>
                                    <span ng-bind="detail.type"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警时间</span>
                                    <span ng-bind="detail.time*1000 | date : 'yyyy-MM-dd HH:mm'"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">首次出现</span>
                                    <span ng-bind="detail.attr.first_seen | date : 'yyyy-MM-dd HH:mm'"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">最近出现</span>
                                    <span ng-bind="detail.attr.last_seen | date : 'yyyy-MM-dd HH:mm'"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- 新增/ -->
                    <div class="row" ng-if="hoohoolabInfo">
                        <div class="col-md-6 border-right">
                            <ul class="nav nav-stacked sensor-detail">
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警类型</span>
                                    <span ng-bind="hoohoolabType.threatType"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警设备</span>
                                    <span ng-bind="alert.device_ip"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">全球首次发现时间</span>
                                    <span ng-bind="hoohoolabType.first_seen"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">主要受影响地区</span>
                                    <span ng-bind="hoohoolabType.geo"></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6 border-right">
                            <ul class="nav nav-stacked sensor-detail">
                                <li class="li_border">
                                    <span class="sensor-detail-title">风险资产</span>
                                    <span ng-bind="alert.client_ip"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">告警时间</span>
                                    <span ng-bind="detail.time*1000 | date : 'yyyy-MM-dd HH:mm'"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">流行度</span>
                                    <span ng-bind="hoohoolabType.popularity"></span>
                                </li>
                                <li class="li_border">
                                    <span class="sensor-detail-title">威胁详情</span>
                                    <span ng-bind="hoohoolabType.hoohoolabThreat"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- now/ -->
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <ul class="nav nav-stacked sensor-detail" style="border-top: 1px solid #f4f4f4;">
                                <div>
                                    <span class="sensor-detail-title">情报来源</span>

                                    <span>

                                        <div class="alert alert-info alert-dismissible group-lable ng-cloak" ng-repeat="item in detail.attr.sources">
                                            <span ng-bind="item"></span>
                                        </div>
                                    </span>
                                </div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row" style="display: none;">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-bell-o"></i>
                        <span>威胁情报详情</span>
                    </h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <pre class="code" ng-bind-html="json"></pre>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <ul id="myTab" class="nav nav-tabs">
                        <li class="active">
                            <a href="#home" data-toggle="tab">
                                <i class="fa fa-bell-o "></i>
                                <span>当前受威胁的资产</span>
                            </a>
                        </li>

                        <li>
                            <a href="#ios" data-toggle="tab">
                                <i class="fa fa-history "></i>
                                <span>历史受威胁的资产</span>
                            </a>
                        </li>
                        <li>
                            <a href="#detail" data-toggle="tab">
                                <i class="fa fa-info-circle "></i>
                                <span>告警日志信息</span>
                            </a>
                        </li>
                        <li ng-if="hoohoolabInfo" ng-repeat="item in hoohoolabTag">
                            <a href="{{item.href}}" data-toggle="tab">
                                <i class="fa fa-cubes "></i>
                                <span ng-bind="item.name"></span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div id="myTabContent" class="tab-content">
                    <!-- home -->
                    <div class="tab-pane fade in active" id="home">
                        <div class="box-body">
                            <table class="table table-hover ng-cloak">
                                <tr>
                                    <th style="width:12%">风险资产</th>
                                    <th style="width:10%">来源设备</th>
                                    <th style="width:10%">告警类型</th>
                                    <th style="width:15%">威胁指标</th>
                                    <th>告警日志</th>
                                    <th style="width:9%">威胁等级</th>
                                    <th style="width:18%">告警时间</th>
                                    <th style="border-top: 0px;">操作</th>
                                </tr>

                                <tr style="cursor: pointer;" ng-repeat="item in pages0.data">
                                    <td ng-bind="item.client_ip" title="{{item.client_ip}}"></td>
                                    <td ng-bind="item.device_name" title="{{item.device_name}}"></td>
                                    <td ng-bind="item.category" title="{{item.category}}"></td>
                                    <td ng-bind="item.indicator" title="{{item.indicator}}"></td>
                                    <td ng-bind="showLength(item.session)" title="{{item.session}}"></td>
                                    <td ng-bind="item.degree" title="{{item.degree}}"></td>
                                    <td ng-bind="item.time" title="{{item.time}}"></td>
                                    <td>
                                        <button class="btn btn-xs btn-default" ng-click="showDetail(item)">
                                            <i class="fa fa-eye"></i> 查看</button>
                                    </td>
                                </tr>
                            </table>
                            <div style="border-top: 1px solid #f4f4f4;padding: 10px;">
                                <em>共有
                                    <span ng-bind="pages0.count"></span>条告警</em>
                                <!-- angularjs分页 -->
                                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow-1)" ng-if="pages0.pageNow>1">上一页</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(1)" ng-if="pages0.pageNow>1">1</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages0.pageNow>4">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow-2)" ng-bind="pages0.pageNow-2"
                                            ng-if="pages0.pageNow>3"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow-1)" ng-bind="pages0.pageNow-1"
                                            ng-if="pages0.pageNow>2"></a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0);" ng-bind="pages0.pageNow"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow+1)" ng-bind="pages0.pageNow+1"
                                            ng-if="pages0.pageNow<pages0.maxPage-1"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow+2)" ng-bind="pages0.pageNow+2"
                                            ng-if="pages0.pageNow<pages0.maxPage-2"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages0.pageNow<pages0.maxPage-3">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.maxPage)" ng-bind="pages0.maxPage"
                                            ng-if="pages0.pageNow<pages0.maxPage"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage0(pages0.pageNow+1)" ng-if="pages0.pageNow<pages0.maxPage">下一页</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- ios -->
                    <div class="tab-pane fade" id="ios">
                        <div class="box-body">
                            <table class="table table-hover ng-cloak">
                                <tr>
                                <th style="width:12%">风险资产</th>
                                    <th style="width:10%">来源设备</th>
                                    <th style="width:10%">告警类型</th>
                                    <th style="width:15%">威胁指标</th>
                                    <th>告警日志</th>
                                    <th style="width:9%">威胁等级</th>
                                    <th style="width:18%">告警时间</th>
                                    <th style="border-top: 0px;">操作</th>
                                </tr>
                                <tr style="cursor: pointer;" ng-repeat="item in pages2.data">
                                <td ng-bind="item.client_ip" title="{{item.client_ip}}"></td>
                                    <td ng-bind="item.device_name" title="{{item.device_name}}"></td>
                                    <td ng-bind="item.category" title="{{item.category}}"></td>
                                    <td ng-bind="item.indicator" title="{{item.indicator}}"></td>
                                    <td ng-bind="showLength(item.session)" title="{{item.session}}"></td>
                                    <td ng-bind="item.degree_cn" title="{{item.degree_cn}}"></td>
                                    <td ng-bind="item.time" title="{{item.time}}"></td>
                                    <td>
                                        <button class="btn btn-xs btn-default" ng-click="showDetail(item)">
                                            <i class="fa fa-eye"></i> 查看</button>
                                    </td>
                                </tr>
                            </table>
                            <!-- angularjs分页 -->
                            <div style="border-top: 1px solid #f4f4f4;padding: 10px;">
                                <em>共有
                                    <span ng-bind="pages2.count"></span>条告警</em>
                                <!-- angularjs分页 -->
                                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.pageNow-1)" ng-if="pages2.pageNow>1">上一页</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(1)" ng-if="pages2.pageNow>1">1</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages2.pageNow>4">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.pageNow-2)" ng-bind="pages2.pageNow-2"
                                            ng-if="pages2.pageNow>3"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.pageNow-1)" ng-bind="pages2.pageNow-1"
                                            ng-if="pages2.pageNow>2"></a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0);" ng-bind="pages2.pageNow"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages.pageNow+1)" ng-bind="pages2.pageNow+1"
                                            ng-if="pages.pageNow<pages.maxPage-1"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.pageNow+2)" ng-bind="pages2.pageNow+2"
                                            ng-if="pages2.pageNow<pages2.maxPage-2"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages2.pageNow<pages2.maxPage-3">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.maxPage)" ng-bind="pages2.maxPage"
                                            ng-if="pages2.pageNow<pages2.maxPage"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage2(pages2.pageNow+1)" ng-if="pages2.pageNow<pages2.maxPage">下一页</a>
                                    </li>
                                </ul>
                            </div>
                            <!-- /.angularjs分页 -->
                        </div>
                    </div>
                    <!-- detail -->
                    <div class="tab-pane fade" id="detail">
                        <div class="box-body">
                            <div class="box-body" ng-bind-html="logHtml">
                            </div>
                        </div>
                    </div>
                    <!-- hoohoolab 信息 -->
                    <div class="tab-pane fade" ng-if="hoohoolabInfo" ng-repeat="(index,item) in hoohoolabTag" id="{{item.id}}">
                        <div class="box-body">
                            <div style="width:150px;margin-left: 20px;" class="box_centent">
                                <p ng-repeat="key in item.tagName  track by $index" style="height:20px" class="box_centent_p"
                                    ng-bind="key">
                            </div>
                            <div class="box_centent box_centent_right">
                                <p ng-repeat="key in item.tagValue  track by $index" style="height:20px" class="box_centent_p"
                                    ng-bind="key"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<script type="text/javascript" src="/plugins/angular/angular-sanitize.min.js"></script>
<script>
    var alert = <?=json_encode($alert)?>;
    if (typeof alert.data.geo == 'string') {
        try {
            alert.data.geo = JSON.parse(alert.data.geo);
        } catch (e) {}
    }
    var json = JSON.stringify(alert.data, 1, '\t');
    if (alert.data.attr && alert.data.attr.threat > -1) {
        alert.data.attr.threat_arr = [];
        for (var i = 0; i < 5; i++) {
            if (alert.data.attr.threat > i) {
                if (alert.data.attr.threat < (i + 1)) {
                    alert.data.attr.threat_arr.push('fa-star-half-o');
                } else {
                    alert.data.attr.threat_arr.push('fa-star');
                }
            } else {
                alert.data.attr.threat_arr.push('fa-star-o');
            }
        }
    }

console.log(111);
console.log(alert);

    function json_highLight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(
            /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
            function (match) {
                var cls = 'number';
                if (/^"/.test(match)) {
                    if (/:$/.test(match)) {
                        cls = 'key';
                    } else {
                        cls = 'string';
                    }
                } else if (/true|false/.test(match)) {
                    cls = 'boolean';
                } else if (/null/.test(match)) {
                    cls = 'null';
                }
                return '<span class="' + cls + '">' + match + '</span>';
            });
    }
    var app = angular.module('myApp', ['ngSanitize']);
    app.controller('myCtrl', function ($scope, $http, $filter) {
        $scope.loading = zeroModal.loading(4);
        $scope.data = {
            current: "1"
        };
        $scope.actions = {
            setCurrent: function (param) {
                $scope.data.current = param;
            }
        };
        // 国家地区后缀，匹配中文
        $scope.country = function (params) {
            params = params.replace(/[ ]/g, "");
            $scope.country_cn = '';
            switch (params) {
                case 'ac':
                    $scope.country_cn = '亚森松岛';
                    break;
                case 'ad':
                    $scope.country_cn = '安道尔';
                    break;
                case 'ae':
                    $scope.country_cn = '阿拉伯联合酋长国';
                    break;
                case 'af':
                    $scope.country_cn = '阿富汗';
                    break;
                case 'ag':
                    $scope.country_cn = '安提瓜和巴布达';
                    break;
                case 'ai':
                    $scope.country_cn = '安圭拉';
                    break;
                case 'al':
                    $scope.country_cn = '阿尔巴尼亚';
                    break;
                case 'am':
                    $scope.country_cn = '亚美尼亚';
                    break;
                case 'an':
                    $scope.country_cn = '荷属安地列斯群岛';
                    break;
                case 'ao':
                    $scope.country_cn = '安哥拉';
                    break;
                case 'aq':
                    $scope.country_cn = '南极洲';
                    break;
                case 'ar':
                    $scope.country_cn = '阿根廷';
                    break;
                case 'as':
                    $scope.country_cn = '美属萨摩亚';
                    break;
                case 'at':
                    $scope.country_cn = '奥地利';
                    break;
                case 'au':
                    $scope.country_cn = '澳大利亚';
                    break;
                case 'aw':
                    $scope.country_cn = '阿鲁巴';
                    break;
                case 'az':
                    $scope.country_cn = '阿塞拜疆';
                    break;

                case 'ba':
                    $scope.country_cn = '波斯尼亚和黑塞哥维那';
                    break;
                case 'bb':
                    $scope.country_cn = '巴巴多斯';
                    break;
                case 'bd':
                    $scope.country_cn = '孟加拉国';
                    break;
                case 'be':
                    $scope.country_cn = '比利时';
                    break;
                case 'bf':
                    $scope.country_cn = '布基纳法索';
                    break;
                case 'bg':
                    $scope.country_cn = '保加利亚';
                    break;
                case 'bh':
                    $scope.country_cn = '巴林';
                    break;
                case 'bi':
                    $scope.country_cn = '布隆迪';
                    break;
                case 'bj':
                    $scope.country_cn = '贝宁';
                    break;
                case 'bm':
                    $scope.country_cn = '百慕大';
                    break;
                case 'bn':
                    $scope.country_cn = '文莱';
                    break;
                case 'bo':
                    $scope.country_cn = '玻利维亚';
                    break;
                case 'br':
                    $scope.country_cn = '巴西';
                    break;
                case 'bs':
                    $scope.country_cn = '巴哈马';
                    break;
                case 'bt':
                    $scope.country_cn = '不丹';
                    break;
                case 'bv':
                    $scope.country_cn = '布维岛';
                    break;
                case 'bw':
                    $scope.country_cn = '博茨瓦纳';
                    break;
                case 'by':
                    $scope.country_cn = '白俄罗斯';
                    break;
                case 'bz':
                    $scope.country_cn = '伯利兹';
                    break;

                case 'ca':
                    $scope.country_cn = '加拿大';
                    break;
                case 'cc':
                    $scope.country_cn = '可可群岛';
                    break;
                case 'cd':
                    $scope.country_cn = '刚果民主共和国';
                    break;
                case 'cf':
                    $scope.country_cn = '中非共和国';
                    break;
                case 'cg':
                    $scope.country_cn = '刚果';
                    break;
                case 'ch':
                    $scope.country_cn = '瑞士';
                    break;
                case 'ci':
                    $scope.country_cn = '科特迪瓦';
                    break;
                case 'ck':
                    $scope.country_cn = '库克群岛';
                    break;
                case 'cl':
                    $scope.country_cn = '智利';
                    break;
                case 'cm':
                    $scope.country_cn = '喀麦隆';
                    break;
                case 'cn':
                    $scope.country_cn = '中国';
                    break;
                case 'co':
                    $scope.country_cn = '哥伦比亚';
                    break;
                case 'cr':
                    $scope.country_cn = '哥斯达黎加';
                    break;
                case 'cu':
                    $scope.country_cn = '古巴';
                    break;
                case 'cv':
                    $scope.country_cn = '佛得角';
                    break;
                case 'cx':
                    $scope.country_cn = '圣诞岛';
                    break;
                case 'cy':
                    $scope.country_cn = '塞浦路斯';
                    break;
                case 'cz':
                    $scope.country_cn = '捷克共和国';
                    break;

                case 'de':
                    $scope.country_cn = '德国';
                    break;
                case 'dj':
                    $scope.country_cn = '吉布提';
                    break;
                case 'dk':
                    $scope.country_cn = '丹麦';
                    break;
                case 'dm':
                    $scope.country_cn = '多米尼克';
                    break;
                case 'do':
                    $scope.country_cn = '多米尼加共和国';
                    break;
                case 'dz':
                    $scope.country_cn = '阿尔及利亚';
                    break;

                case 'ec':
                    $scope.country_cn = '厄瓜多尔';
                    break;
                case 'ee':
                    $scope.country_cn = '爱沙尼亚';
                    break;
                case 'eg':
                    $scope.country_cn = '埃及';
                    break;
                case 'eh':
                    $scope.country_cn = '西撒哈拉';
                    break;
                case 'er':
                    $scope.country_cn = '厄立特里亚';
                    break;
                case 'es':
                    $scope.country_cn = '西班牙';
                    break;
                case 'et':
                    $scope.country_cn = '埃塞俄比亚';
                    break;
                case 'eu':
                    $scope.country_cn = '欧洲联盟';
                    break;

                case 'fi':
                    $scope.country_cn = '芬兰';
                    break;
                case 'fj':
                    $scope.country_cn = '斐济';
                    break;
                case 'fk':
                    $scope.country_cn = '福克兰群岛';
                    break;
                case 'fm':
                    $scope.country_cn = '密克罗尼西亚联邦';
                    break;
                case 'fo':
                    $scope.country_cn = '法罗群岛';
                    break;
                case 'fr':
                    $scope.country_cn = '法国';
                    break;

                case 'ga':
                    $scope.country_cn = '加蓬';
                    break;
                case 'gd':
                    $scope.country_cn = '格林纳达';
                    break;
                case 'ge':
                    $scope.country_cn = '格鲁吉亚';
                    break;
                case 'gf':
                    $scope.country_cn = '法属圭亚那';
                    break;
                case 'gg':
                    $scope.country_cn = '格恩西岛';
                    break;
                case 'gh':
                    $scope.country_cn = '加纳';
                    break;
                case 'gi':
                    $scope.country_cn = '直布罗陀';
                    break;
                case 'gl':
                    $scope.country_cn = '格陵兰';
                    break;
                case 'gm':
                    $scope.country_cn = '冈比亚';
                    break;
                case 'gn':
                    $scope.country_cn = '几内亚';
                    break;
                case 'gp':
                    $scope.country_cn = '瓜德罗普';
                    break;
                case 'gq':
                    $scope.country_cn = '赤道几内亚';
                    break;
                case 'gr':
                    $scope.country_cn = '希腊';
                    break;
                case 'gs':
                    $scope.country_cn = '南乔治亚岛和南桑德韦奇岛';
                    break;
                case 'gt':
                    $scope.country_cn = '危地马拉';
                    break;
                case 'gu':
                    $scope.country_cn = '关岛';
                    break;
                case 'gw':
                    $scope.country_cn = '几内亚比绍';
                    break;
                case 'gy':
                    $scope.country_cn = '圭亚那';
                    break;

                case 'hk':
                    $scope.country_cn = '香港';
                    break;
                case 'hm':
                    $scope.country_cn = '赫德和麦克唐纳群岛';
                    break;
                case 'hn':
                    $scope.country_cn = '洪都拉斯';
                    break;
                case 'hr':
                    $scope.country_cn = '克罗地亚';
                    break;
                case 'ht':
                    $scope.country_cn = '海地';
                    break;
                case 'hu':
                    $scope.country_cn = '匈牙利';
                    break;

                case 'id':
                    $scope.country_cn = '印度尼西亚';
                    break;
                case 'ie':
                    $scope.country_cn = '爱尔兰';
                    break;
                case 'il':
                    $scope.country_cn = '以色列';
                    break;
                case 'im':
                    $scope.country_cn = '马恩岛';
                    break;
                case 'in':
                    $scope.country_cn = '印度';
                    break;
                case 'io':
                    $scope.country_cn = '英属印度洋地区';
                    break;
                case 'iq':
                    $scope.country_cn = '伊拉克';
                    break;
                case 'ir':
                    $scope.country_cn = '伊朗';
                    break;
                case 'is':
                    $scope.country_cn = '冰岛';
                    break;
                case 'it':
                    $scope.country_cn = '意大利';
                    break;

                case 'je':
                    $scope.country_cn = '泽西岛';
                    break;
                case 'jm':
                    $scope.country_cn = '牙买加';
                    break;
                case 'jo':
                    $scope.country_cn = '约旦';
                    break;
                case 'jp':
                    $scope.country_cn = '日本';
                    break;

                case 'ke':
                    $scope.country_cn = '肯尼亚';
                    break;
                case 'kg':
                    $scope.country_cn = '吉尔吉斯斯坦';
                    break;
                case 'kh':
                    $scope.country_cn = '柬埔寨';
                    break;
                case 'ki':
                    $scope.country_cn = '基里巴斯';
                    break;
                case 'km':
                    $scope.country_cn = '科摩罗';
                    break;
                case 'kn':
                    $scope.country_cn = '圣基茨和尼维斯';
                    break;
                case 'kp':
                    $scope.country_cn = '朝鲜';
                    break;
                case 'kr':
                    $scope.country_cn = '韩国';
                    break;
                case 'kw':
                    $scope.country_cn = '科威特';
                    break;
                case 'ky':
                    $scope.country_cn = '开曼群岛';
                    break;
                case 'kz':
                    $scope.country_cn = '哈萨克斯坦';
                    break;

                case 'la':
                    $scope.country_cn = '老挝';
                    break;
                case 'lb':
                    $scope.country_cn = '黎巴嫩';
                    break;
                case 'lc':
                    $scope.country_cn = '圣卢西亚';
                    break;
                case 'li':
                    $scope.country_cn = '列支敦士登';
                    break;
                case 'lk':
                    $scope.country_cn = '斯里兰卡';
                    break;
                case 'lr':
                    $scope.country_cn = '利比里亚';
                    break;
                case 'ls':
                    $scope.country_cn = '莱索托';
                    break;
                case 'lt':
                    $scope.country_cn = '立陶宛';
                    break;
                case 'lu':
                    $scope.country_cn = '卢森堡';
                    break;
                case 'lv':
                    $scope.country_cn = '拉脱维亚';
                    break;
                case 'ly':
                    $scope.country_cn = '利比亚';
                    break;

                case 'ma':
                    $scope.country_cn = '摩洛哥';
                    break;
                case 'mc':
                    $scope.country_cn = '摩纳哥';
                    break;
                case 'md':
                    $scope.country_cn = '摩尔多瓦';
                    break;
                case 'mg':
                    $scope.country_cn = '马达加斯加';
                    break;
                case 'mh':
                    $scope.country_cn = '马绍尔群岛';
                    break;
                case 'mk':
                    $scope.country_cn = '马其顿';
                    break;
                case 'ml':
                    $scope.country_cn = '马里';
                    break;
                case 'mm':
                    $scope.country_cn = '缅甸';
                    break;
                case 'mn':
                    $scope.country_cn = '蒙古';
                    break;
                case 'mo':
                    $scope.country_cn = '中国澳门';
                    break;
                case 'mp':
                    $scope.country_cn = '北马里亚纳群岛';
                    break;
                case 'mq':
                    $scope.country_cn = '马提尼克岛';
                    break;
                case 'mr':
                    $scope.country_cn = '毛里塔尼亚';
                    break;
                case 'ms':
                    $scope.country_cn = '蒙特塞拉特岛';
                    break;
                case 'mt':
                    $scope.country_cn = '马耳他';
                    break;
                case 'mu':
                    $scope.country_cn = '毛里求斯';
                    break;
                case 'mv':
                    $scope.country_cn = '马尔代夫';
                    break;
                case 'mw':
                    $scope.country_cn = '马拉维';
                    break;
                case 'mx':
                    $scope.country_cn = '墨西哥';
                    break;
                case 'my':
                    $scope.country_cn = '马来西亚';
                    break;
                case 'mz':
                    $scope.country_cn = '莫桑比克';
                    break;

                case 'na':
                    $scope.country_cn = '纳米比亚';
                    break;
                case 'nc':
                    $scope.country_cn = '新喀里多尼亚';
                    break;
                case 'ne':
                    $scope.country_cn = '尼日尔';
                    break;
                case 'nf':
                    $scope.country_cn = '诺福克岛';
                    break;
                case 'ng':
                    $scope.country_cn = '尼日利亚';
                    break;
                case 'ni':
                    $scope.country_cn = '尼加拉瓜';
                    break;
                case 'nl':
                    $scope.country_cn = '荷兰';
                    break;
                case 'no':
                    $scope.country_cn = '挪威';
                    break;
                case 'np':
                    $scope.country_cn = '尼泊尔';
                    break;
                case 'nr':
                    $scope.country_cn = '瑙鲁';
                    break;
                case 'nu':
                    $scope.country_cn = '纽埃岛';
                    break;
                case 'nz':
                    $scope.country_cn = '新西兰';
                    break;

                case 'om':
                    $scope.country_cn = '阿曼';
                    break;

                case 'pa':
                    $scope.country_cn = '巴拿马';
                    break;
                case 'pe':
                    $scope.country_cn = '秘鲁';
                    break;
                case 'pf':
                    $scope.country_cn = '法属波利尼西亚';
                    break;
                case 'pg':
                    $scope.country_cn = '巴布亚新几内亚';
                    break;
                case 'ph':
                    $scope.country_cn = '菲律宾';
                    break;
                case 'pk':
                    $scope.country_cn = '巴基斯坦';
                    break;
                case 'pl':
                    $scope.country_cn = '波兰';
                    break;
                case 'pm':
                    $scope.country_cn = '圣皮埃尔岛及密客隆岛';
                    break;
                case 'pn':
                    $scope.country_cn = '皮特凯恩群岛';
                    break;
                case 'pr':
                    $scope.country_cn = '波多黎各';
                    break;
                case 'ps':
                    $scope.country_cn = '巴勒斯坦';
                    break;
                case 'pt':
                    $scope.country_cn = '葡萄牙';
                    break;
                case 'pw':
                    $scope.country_cn = '帕劳';
                    break;
                case 'py':
                    $scope.country_cn = '巴拉圭';
                    break;

                case 'qa':
                    $scope.country_cn = '卡塔尔';
                    break;

                case 're':
                    $scope.country_cn = '留尼汪';
                    break;
                case 'ro':
                    $scope.country_cn = '罗马尼亚';
                    break;
                case 'ru':
                    $scope.country_cn = '俄罗斯';
                    break;
                case 'rw':
                    $scope.country_cn = '卢旺达';
                    break;

                case 'sa':
                    $scope.country_cn = '沙特阿拉伯';
                    break;
                case 'sb':
                    $scope.country_cn = '所罗门群岛';
                    break;
                case 'sc':
                    $scope.country_cn = '塞舌尔';
                    break;
                case 'sd':
                    $scope.country_cn = '苏丹';
                    break;
                case 'se':
                    $scope.country_cn = '瑞典';
                    break;
                case 'sg':
                    $scope.country_cn = '新加坡';
                    break;
                case 'sh':
                    $scope.country_cn = '圣赫勒拿岛';
                    break;
                case 'si':
                    $scope.country_cn = '斯洛文尼亚';
                    break;
                case 'sj':
                    $scope.country_cn = '斯瓦尔巴岛和扬马延岛';
                    break;
                case 'sk':
                    $scope.country_cn = '斯洛伐克';
                    break;
                case 'sl':
                    $scope.country_cn = '塞拉利昂';
                    break;
                case 'sm':
                    $scope.country_cn = '圣马力诺';
                    break;
                case 'sn':
                    $scope.country_cn = '塞内加尔';
                    break;
                case 'so':
                    $scope.country_cn = '索马里';
                    break;
                case 'sr':
                    $scope.country_cn = '苏里南';
                    break;
                case 'st':
                    $scope.country_cn = '圣多美和普林西比';
                    break;
                case 'sv':
                    $scope.country_cn = '萨尔瓦多';
                    break;
                case 'sy':
                    $scope.country_cn = '叙利亚';
                    break;
                case 'sz':
                    $scope.country_cn = '斯威士兰';
                    break;

                case 'tc':
                    $scope.country_cn = '特克斯和凯科斯群岛';
                    break;
                case 'td':
                    $scope.country_cn = '乍得';
                    break;
                case 'tf':
                    $scope.country_cn = '法属南部领土';
                    break;
                case 'tg':
                    $scope.country_cn = '多哥';
                    break;
                case 'th':
                    $scope.country_cn = '泰国';
                    break;
                case 'tj':
                    $scope.country_cn = '塔吉克斯坦';
                    break;
                case 'tk':
                    $scope.country_cn = '托克劳';
                    break;
                case 'tl':
                    $scope.country_cn = '东帝汶（新域名)';
                    break;
                case 'tm':
                    $scope.country_cn = '土库曼斯坦';
                    break;
                case 'tn':
                    $scope.country_cn = '突尼斯';
                    break;
                case 'to':
                    $scope.country_cn = '汤加';
                    break;
                case 'tp':
                    $scope.country_cn = '东帝汶（旧域名)';
                    break;
                case 'tr':
                    $scope.country_cn = '土耳其';
                    break;
                case 'tt':
                    $scope.country_cn = '特立尼达和多巴哥';
                    break;
                case 'tv':
                    $scope.country_cn = '图瓦卢';
                    break;
                case 'tw':
                    $scope.country_cn = '台湾';
                    break;
                case 'tz':
                    $scope.country_cn = '坦桑尼亚';
                    break;

                case 'ua':
                    $scope.country_cn = '乌克兰';
                    break;
                case 'ug':
                    $scope.country_cn = '乌干达';
                    break;
                case 'uk':
                    $scope.country_cn = '英国';
                    break;
                case 'um':
                    $scope.country_cn = '美国本土外小岛屿';
                    break;
                case 'us':
                    $scope.country_cn = '美国';
                    break;
                case 'uy':
                    $scope.country_cn = '乌拉圭';
                    break;
                case 'uz':
                    $scope.country_cn = '乌兹别克斯坦';
                    break;

                case 'va':
                    $scope.country_cn = '梵蒂冈';
                    break;
                case 'vc':
                    $scope.country_cn = '圣文森特和格林纳丁斯';
                    break;
                case 've':
                    $scope.country_cn = '委内瑞拉';
                    break;
                case 'vg':
                    $scope.country_cn = '英属维尔京群岛';
                    break;
                case 'vi':
                    $scope.country_cn = '美属维尔京群岛';
                    break;
                case 'vn':
                    $scope.country_cn = '越南';
                    break;
                case 'vu':
                    $scope.country_cn = '瓦努阿图';
                    break;

                case 'wf':
                    $scope.country_cn = '瓦利斯和富图纳群岛';
                    break;
                case 'ws':
                    $scope.country_cn = '萨摩亚';
                    break;

                case 'ye':
                    $scope.country_cn = '也门';
                    break;
                case 'yt':
                    $scope.country_cn = '马约特岛';
                    break;
                case 'yu':
                    $scope.country_cn = '塞尔维亚和黑山';
                    break;
                case 'yr':
                    $scope.country_cn = '耶纽';
                    break;

                case 'za':
                    $scope.country_cn = '南非';
                    break;
                case 'zm':
                    $scope.country_cn = '赞比亚';
                    break;
                case 'zw':
                    $scope.country_cn = '津巴布韦';
                    break;

                default:
                    break;
            }
        };
        $scope.detail = alert.data;
        $scope.alert = alert;
        if ($scope.detail) {
            zeroModal.close($scope.loading);
        };
        console.log($scope.detail);

        $scope.json = json_highLight(json);
        $scope.hoohoolabInfo = false;
        $scope.hoohoolabSpan = [];
        $scope.hoohoolabTag = [];
        $scope.hoohoolab_sources_data = []; // 情报来源同时为 MaliciousHash MobileMaliciousHash
        $scope.hoohoolabType = {
            threatType: '', // 威胁类型
            popularity: '', // 流行度
            first_seen: '', // 首次发现时间
            geo: '', // 主要受影响地区
            // hoohoolabThreat:'' //威胁详情
        };
        // console.log($scope.detail);
        angular.forEach($scope.detail.attr.sources, function (key, value) {
            if (key.split('_')[0] == 'hoohoolab') {
                // BotnetCAndCURL
                $scope.BotnetCAndCURLfilesName = [];
                $scope.BotnetCAndCURLfilesValue = [];
                $scope.BotnetCAndCURLurlsName = [];
                $scope.BotnetCAndCURLurlsValue = [];
                $scope.BotnetCAndCURLinfoName = [];
                $scope.BotnetCAndCURLinfoValue = [];
                // IPReputation
                $scope.IPReputationwhoisName = [];
                $scope.IPReputationwhoisValue = [];
                $scope.IPReputation_domains_Name = [];
                $scope.IPReputation_domains_Value = [];
                $scope.IPReputation_files_Name = [];
                $scope.IPReputation_files_Value = [];
                //MaliciousHash
                $scope.MaliciousHash_MD5_name = [];
                $scope.MaliciousHash_MD5_value = [];
                // 文本信息
                $scope.MaliciousHash_file__total_name = [];
                $scope.MaliciousHash_file__total_value = [];

                $scope.MaliciousHash_file_size_name = [];
                $scope.MaliciousHash_file_size_value = [];
                $scope.MaliciousHash_file_type_name = [];
                $scope.MaliciousHash_file_type_value = [];
                $scope.MaliciousHash_file_names_name = [];
                $scope.MaliciousHash_file_names_value = [];
                // 恶意文件下载ip
                $scope.MaliciousHash_ip_name = [];
                $scope.MaliciousHash_ip_value = [];
                // 恶意文件下载URL
                $scope.MaliciousHash_URLS_name = [];
                $scope.MaliciousHash_URLS_value = [];
                // MaliciousURL
                $scope.MaliciousURL_Files_name = [];
                $scope.MaliciousURL_Files_value = [];
                $scope.MaliciousURL_whois_name = [];
                $scope.MaliciousURL_whois_value = [];
                // PhishingURL
                $scope.PhishingURL_Ip_name = [];
                $scope.PhishingURL_Ip_value = [];
                $scope.PhishingURL_whois_name = [];
                $scope.PhishingURL_whois_value = [];
                // MobileMaliciousHash
                $scope.MobileMaliciousHash_file_size_name = [];
                $scope.MobileMaliciousHash_file_size_value = [];
                switch (key.split('_')[1]) {
                    case 'BotnetCAndCURL':
                        // files
                        if ($scope.detail.attr.hoohoolab_files) {
                            $scope.BotnetCAndCURL_files_MD5_value = [];
                            $scope.BotnetCAndCURL_files_MD5_value_length = 0;
                            $scope.BotnetCAndCURL_files_SHA1_value = [];
                            $scope.BotnetCAndCURL_files_SHA1_value_length = 0;
                            $scope.BotnetCAndCURL_files_SHA256_value = [];
                            $scope.BotnetCAndCURL_files_SHA256_value_length = 0;
                            $scope.BotnetCAndCURL_files_threat_value = [];
                            $scope.BotnetCAndCURL_files_threat_value_length = 0;
                            angular.forEach($scope.detail.attr.hoohoolab_files, function (each,
                                mark) {
                                if (each.MD5) {
                                    $scope.BotnetCAndCURL_files_MD5_value.push(each.MD5);
                                    $scope.BotnetCAndCURL_files_MD5_value_length++;
                                }
                                if (each.SHA1) {
                                    $scope.BotnetCAndCURL_files_SHA1_value.push(each.SHA1);
                                    $scope.BotnetCAndCURL_files_SHA1_value_length++;
                                }
                                if (each.SHA256) {
                                    $scope.BotnetCAndCURL_files_SHA256_value.push(each.SHA256);
                                    $scope.BotnetCAndCURL_files_SHA256_value_length++;
                                }
                                if (each.threat) {
                                    $scope.BotnetCAndCURL_files_threat_value.push(each.threat);
                                    $scope.BotnetCAndCURL_files_threat_value_length++;
                                }
                            });
                            $scope.BotnetCAndCURL_files_Name = [];
                            $scope.BotnetCAndCURL_files_Name.length = $scope.BotnetCAndCURL_files_MD5_value_length +
                                $scope.BotnetCAndCURL_files_SHA1_value_length + $scope.BotnetCAndCURL_files_SHA256_value_length +
                                $scope.BotnetCAndCURL_files_threat_value_length;

                            if ($scope.BotnetCAndCURL_files_MD5_value_length != 0) {
                                $scope.BotnetCAndCURL_files_Name[0] = 'MD5';
                            }
                            if ($scope.BotnetCAndCURL_files_SHA1_value_length != 0) {
                                $scope.BotnetCAndCURL_files_Name[$scope.BotnetCAndCURL_files_MD5_value_length] =
                                    'SHA1';
                            }
                            if ($scope.BotnetCAndCURL_files_SHA256_value_length != 0) {
                                $scope.BotnetCAndCURL_files_Name[$scope.BotnetCAndCURL_files_MD5_value_length +
                                    $scope.BotnetCAndCURL_files_SHA1_value_length] = 'SHA256';
                            }
                            if ($scope.BotnetCAndCURL_files_threat_value_length != 0) {
                                $scope.BotnetCAndCURL_files_Name[$scope.BotnetCAndCURL_files_MD5_value_length +
                                    $scope.BotnetCAndCURL_files_SHA1_value_length + $scope.BotnetCAndCURL_files_SHA256_value_length
                                ] = 'THREAT';
                            }
                            $scope.BotnetCAndCURL_files_Value = $scope.BotnetCAndCURL_files_MD5_value
                                .concat($scope.BotnetCAndCURL_files_SHA1_value, $scope.BotnetCAndCURL_files_SHA256_value,
                                    $scope.BotnetCAndCURL_files_threat_value);
                        }

                        // urls
                        if ($scope.detail.attr.hoohoolab_urls) {
                            angular.forEach($scope.detail.attr.hoohoolab_urls, function (each, mark) {
                                $scope.BotnetCAndCURLurlsValue.push(each.url);
                            });
                            $scope.BotnetCAndCURLurlsName = ['urls'];

                        }
                        for (var key in $scope.detail.attr.hoohoolab_whois) {
                            $scope.BotnetCAndCURLinfoName.push(key);
                            $scope.BotnetCAndCURLinfoValue.push($scope.detail.attr.hoohoolab_whois[
                                key]);
                        };
                        $scope.hoohoolabTag = [{
                            name: '与服务器通信样本',
                            id: 'BotnetCAndCURL_files',
                            href: '#BotnetCAndCURL_files',
                            tagName: $scope.BotnetCAndCURLfilesName,
                            tagValue: $scope.BotnetCAndCURLfilesValue
                        }, {
                            name: '样本下载URL',
                            id: 'BotnetCAndCURL_URL',
                            href: '#BotnetCAndCURL_URL',
                            tagName: $scope.BotnetCAndCURLurlsName,
                            tagValue: $scope.BotnetCAndCURLurlsValue
                        }, {
                            name: 'whois信息',
                            id: 'BotnetCAndCURL_info',
                            href: '#BotnetCAndCURL_info',
                            tagName: $scope.BotnetCAndCURLinfoName,
                            tagValue: $scope.BotnetCAndCURLinfoValue
                        }];
                        // 获取 威胁等级 影响的国家 首次时间
                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_geo) {
                            if ($scope.detail.attr.hoohoolab_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                            hoohoolabThreat: $scope.detail.attr.hoohoolab_threat
                        };
                        break;
                    case 'IPReputation':
                        if ($scope.detail.attr.hoohoolab_ip_whois) {
                            for (var key in $scope.detail.attr.hoohoolab_ip_whois) {
                                $scope.IPReputationwhoisName.push(key);
                                $scope.IPReputationwhoisValue.push($scope.detail.attr.hoohoolab_ip_whois[
                                    key]);
                            };
                        }

                        // 相关联域名
                        if ($scope.detail.attr.hoohoolab_domains) {
                            $scope.IPReputation_domains_Name = ['domains'];
                            $scope.IPReputation_domains_Value.push($scope.detail.attr.hoohoolab_domains);
                        }
                        // 相关联恶意文件：hoohoolab_ files
                        if ($scope.detail.attr.hoohoolab_files) {
                            $scope.IPReputation_files_MD5_value = [];
                            $scope.IPReputation_files_MD5_value_length = 0;
                            $scope.IPReputation_files_SHA1_value = [];
                            $scope.IPReputation_files_SHA1_value_length = 0;
                            $scope.IPReputation_files_SHA256_value = [];
                            $scope.IPReputation_files_SHA256_value_length = 0;
                            $scope.IPReputation_files_threat_value = [];
                            $scope.IPReputation_files_threat_value_length = 0;
                            angular.forEach($scope.detail.attr.hoohoolab_files, function (each,
                                mark) {
                                if (each.MD5) {
                                    $scope.IPReputation_files_MD5_value.push(each.MD5);
                                    $scope.IPReputation_files_MD5_value_length++;
                                }
                                if (each.SHA1) {
                                    $scope.IPReputation_files_SHA1_value.push(each.SHA1);
                                    $scope.IPReputation_files_SHA1_value_length++;
                                }
                                if (each.SHA256) {
                                    $scope.IPReputation_files_SHA256_value.push(each.SHA256);
                                    $scope.IPReputation_files_SHA256_value_length++;
                                }
                                if (each.threat) {
                                    $scope.IPReputation_files_threat_value.push(each.threat);
                                    $scope.IPReputation_files_threat_value_length++;
                                }
                            });
                            $scope.IPReputation_files_Name = [];
                            $scope.IPReputation_files_Name.length = $scope.IPReputation_files_MD5_value_length +
                                $scope.IPReputation_files_SHA1_value_length + $scope.IPReputation_files_SHA256_value_length +
                                $scope.IPReputation_files_threat_value_length;

                            if ($scope.IPReputation_files_MD5_value_length != 0) {
                                $scope.IPReputation_files_Name[0] = 'MD5';
                            }
                            if ($scope.IPReputation_files_SHA1_value_length != 0) {
                                $scope.IPReputation_files_Name[$scope.IPReputation_files_MD5_value_length] =
                                    'SHA1';
                            }
                            if ($scope.IPReputation_files_SHA256_value_length != 0) {
                                $scope.IPReputation_files_Name[$scope.IPReputation_files_MD5_value_length +
                                    $scope.IPReputation_files_SHA1_value_length] = 'SHA256';
                            }
                            if ($scope.IPReputation_files_threat_value_length != 0) {
                                $scope.IPReputation_files_Name[$scope.IPReputation_files_MD5_value_length +
                                    $scope.IPReputation_files_SHA1_value_length + $scope.IPReputation_files_SHA256_value_length
                                ] = 'THREAT';
                            }
                            $scope.IPReputation_files_Value = $scope.IPReputation_files_MD5_value.concat(
                                $scope.IPReputation_files_SHA1_value, $scope.IPReputation_files_SHA256_value,
                                $scope.IPReputation_files_threat_value);
                        }
                        // 增加两个
                        // 相关联域名：hoohoolab_ domains
                        // "domains": "twobytes.com, two-bytes.my",
                        // 相关联恶意文件：hoohoolab_ files
                        $scope.hoohoolabTag = [{
                            name: '相关联域名',
                            id: 'IPReputation_domains',
                            href: '#IPReputation_domains',
                            tagName: $scope.IPReputation_domains_Name,
                            tagValue: $scope.IPReputation_domains_Value
                        }, {
                            name: '相关联恶意文件',
                            id: 'IPReputation_files',
                            href: '#IPReputation_files',
                            tagName: $scope.IPReputation_files_Name,
                            tagValue: $scope.IPReputation_files_Value
                        }, {
                            name: 'IP_whois信息',
                            id: 'IPReputation_ip_whois',
                            href: '#IPReputation_ip_whois',
                            tagName: $scope.IPReputationwhoisName,
                            tagValue: $scope.IPReputationwhoisValue
                        }];
                        // 多个威胁类型
                        if ($scope.detail.attr.hoohoolab_category.indexOf(',') != -1) {
                            $scope.arrayCategory = $scope.detail.attr.hoohoolab_category.split(',');
                            angular.forEach($scope.arrayCategory, function (gx, dx) {
                                gx = $.trim(gx);
                                if (gx == 'malware') {
                                    $scope.arrayCategory[dx] = '恶意地址';
                                } else if (gx == 'spam') {
                                    $scope.arrayCategory[dx] = '垃圾邮件';
                                } else if (gx == 'botnet_cnc') {
                                    $scope.arrayCategory[dx] = '僵尸网络';
                                } else if (gx == 'proxy') {
                                    $scope.arrayCategory[dx] = '网络代理';
                                } else if (gx == 'tor_node') {
                                    $scope.arrayCategory[dx] = 'tor入口节点';
                                } else if (gx == 'tor_exit_node') {
                                    $scope.arrayCategory[dx] = 'tor出口节点';
                                } else if (gx == 'phishing') {
                                    $scope.arrayCategory[dx] = '钓鱼网站';
                                }
                            });
                            $scope.hoohoolab_category_cn = $scope.arrayCategory.join(',');
                        } else {
                            if ($scope.detail.attr.hoohoolab_category == 'malware') {
                                $scope.hoohoolab_category_cn = '恶意地址';
                            } else if ($scope.detail.attr.hoohoolab_category == 'spam') {
                                $scope.hoohoolab_category_cn = '垃圾邮件';
                            } else if ($scope.detail.attr.hoohoolab_category == 'botnet_cnc') {
                                $scope.hoohoolab_category_cn = '僵尸网络';
                            } else if ($scope.detail.attr.hoohoolab_category == 'proxy') {
                                $scope.hoohoolab_category_cn = '网络代理';
                            } else if ($scope.detail.attr.hoohoolab_category == 'tor_node') {
                                $scope.hoohoolab_category_cn = 'tor入口节点';
                            } else if ($scope.detail.attr.hoohoolab_category == 'tor_exit_node') {
                                $scope.hoohoolab_category_cn = 'tor出口节点';
                            } else if ($scope.detail.attr.hoohoolab_category == 'phishing') {
                                $scope.hoohoolab_category_cn = '钓鱼网站';
                            } else {
                                $scope.hoohoolab_category_cn = $scope.detail.attr.hoohoolab_category;
                            }
                        }
                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_ip_geo) {
                            if ($scope.detail.attr.hoohoolab_ip_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_ip_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_ip_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                        };
                        break;
                    case 'MaliciousHash':
                        // 样本信息
                        if ($scope.detail.attr.hoohoolab_file_size) {
                            $scope.MaliciousHash_file__total_name.push('文件尺寸');
                            $scope.MaliciousHash_file__total_value.push($scope.detail.attr.hoohoolab_file_size +
                                ' ' + 'Byte');
                        }
                        if ($scope.detail.attr.hoohoolab_file_type) {
                            $scope.MaliciousHash_file__total_name.push('文件类型');
                            $scope.MaliciousHash_file__total_value.push($scope.detail.attr.hoohoolab_file_type);
                        }
                        if ($scope.detail.attr.hoohoolab_file_names) {
                            $scope.MaliciousHash_file__total_name.push('文件名称');
                            $scope.MaliciousHash_file__total_value.push($scope.detail.attr.hoohoolab_file_names);
                        }
                        // $scope.MaliciousHash_file__total_name = ['文件尺寸', '文件类型', '文件名称'];
                        if ($scope.detail.attr.hoohoolab_ip) {
                            $scope.MaliciousHash_ip_name = ["IP"];
                            $scope.MaliciousHash_ip_value.push($scope.detail.attr.hoohoolab_ip);
                        }
                        if ($scope.detail.attr.hoohoolab_urls) {
                            $scope.MaliciousHash_URLS_value1 = [];
                            angular.forEach($scope.detail.attr.hoohoolab_urls, function (each, mark) {
                                $scope.MaliciousHash_URLS_value.push(each.url);
                            });
                            $scope.MaliciousHash_URLS_name = ['urls'];
                        }
                        $scope.hoohoolabTag = [{
                            name: '恶意样本信息',
                            id: 'MaliciousHash_file',
                            href: '#MaliciousHash_file',
                            tagName: $scope.MaliciousHash_file__total_name,
                            tagValue: $scope.MaliciousHash_file__total_value
                        }, {
                            name: '恶意文件下载IP',
                            id: 'MaliciousHash_ip',
                            href: '#MaliciousHash_ip',
                            tagName: $scope.MaliciousHash_ip_name,
                            tagValue: $scope.MaliciousHash_ip_value
                        }, {
                            name: '恶意文件下载URL',
                            id: 'MaliciousHash_URLS',
                            href: '#MaliciousHash_URLS',
                            tagName: $scope.MaliciousHash_URLS_name,
                            tagValue: $scope.MaliciousHash_URLS_value
                        }];
                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_geo) {
                            if ($scope.detail.attr.hoohoolab_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                            hoohoolabThreat: $scope.detail.attr.hoohoolab_threat, // 主要受影响地区
                        };
                        break;
                    case 'MaliciousURL':
                        // 相关联恶意文件
                        if ($scope.detail.attr.hoohoolab_files) {
                            $scope.MaliciousURL_files_MD5_value = [];
                            $scope.MaliciousURL_files_MD5_value_length = 0;
                            $scope.MaliciousURL_files_SHA1_value = [];
                            $scope.MaliciousURL_files_SHA1_value_length = 0;
                            $scope.MaliciousURL_files_SHA256_value = [];
                            $scope.MaliciousURL_files_SHA256_value_length = 0;
                            $scope.MaliciousURL_files_threat_value = [];
                            $scope.MaliciousURL_files_threat_value_length = 0;
                            angular.forEach($scope.detail.attr.hoohoolab_files, function (each,
                                mark) {
                                if (each.MD5) {
                                    $scope.MaliciousURL_files_MD5_value.push(each.MD5);
                                    $scope.MaliciousURL_files_MD5_value_length++;
                                }
                                if (each.SHA1) {
                                    $scope.MaliciousURL_files_SHA1_value.push(each.SHA1);
                                    $scope.MaliciousURL_files_SHA1_value_length++;
                                }
                                if (each.SHA256) {
                                    $scope.MaliciousURL_files_SHA256_value.push(each.SHA256);
                                    $scope.MaliciousURL_files_SHA256_value_length++;
                                }
                                if (each.threat) {
                                    $scope.MaliciousURL_files_threat_value.push(each.threat);
                                    $scope.MaliciousURL_files_threat_value_length++;
                                }
                            });

                            $scope.MaliciousURL_files_Name = [];
                            $scope.MaliciousURL_files_Name.length = $scope.MaliciousURL_files_MD5_value_length +
                                $scope.MaliciousURL_files_SHA1_value_length + $scope.MaliciousURL_files_SHA256_value_length +
                                $scope.MaliciousURL_files_threat_value_length;

                            if ($scope.MaliciousURL_files_MD5_value_length != 0) {
                                $scope.MaliciousURL_files_Name[0] = 'MD5';
                            }
                            if ($scope.MaliciousURL_files_SHA1_value_length != 0) {
                                $scope.MaliciousURL_files_Name[$scope.MaliciousURL_files_MD5_value_length] =
                                    'SHA1';
                            }
                            if ($scope.MaliciousURL_files_SHA256_value_length != 0) {
                                $scope.MaliciousURL_files_Name[$scope.MaliciousURL_files_MD5_value_length +
                                    $scope.MaliciousURL_files_SHA1_value_length] = 'SHA256';
                            }
                            if ($scope.MaliciousURL_files_threat_value_length != 0) {
                                $scope.MaliciousURL_files_Name[$scope.MaliciousURL_files_MD5_value_length +
                                    $scope.MaliciousURL_files_SHA1_value_length + $scope.MaliciousURL_files_SHA256_value_length
                                ] = 'THREAT';
                            }
                            $scope.MaliciousURL_files_Value = $scope.MaliciousURL_files_MD5_value.concat(
                                $scope.MaliciousURL_files_SHA1_value, $scope.MaliciousURL_files_SHA256_value,
                                $scope.MaliciousURL_files_threat_value);
                        };
                        //whios 信息
                        if ($scope.detail.attr.hoohoolab_whois) {
                            for (var key in $scope.detail.attr.hoohoolab_whois) {
                                $scope.MaliciousURL_whois_name.push(key);
                                $scope.MaliciousURL_whois_value.push($scope.detail.attr.hoohoolab_whois[
                                    key]);
                            };
                        };
                        // console.log($scope.MaliciousURL_whois_value);
                        $scope.hoohoolabTag = [{
                            name: '相关联恶意文件',
                            id: 'MaliciousURL_Files',
                            href: '#MaliciousURL_Files',
                            tagName: $scope.MaliciousURL_Files_name,
                            tagValue: $scope.MaliciousURL_Files_value
                        }, {
                            name: 'whios信息',
                            id: 'MaliciousURL_whois',
                            href: '#MaliciousURL_whois',
                            tagName: $scope.MaliciousURL_whois_name,
                            tagValue: $scope.MaliciousURL_whois_value
                        }];
                        // 多种判断
                        if ($scope.detail.attr.hoohoolab_category.indexOf(',') != -1) {
                            var arrayCategory = $scope.detail.attr.hoohoolab_category.split(',');
                            angular.forEach(arrayCategory, function (gx, dx) {
                                gx = $.trim(gx);
                                if (gx == 'Malware') {
                                    arrayCategory[dx] = '恶意地址';
                                } else if (gx == 'Bot C&C') {
                                    arrayCategory[dx] = '僵尸网络C&C';
                                } else if (gx == 'Fraud') {
                                    arrayCategory[dx] = '网络诈骗';
                                } else if (gx == 'MobileMalware or Malicious redirect') {
                                    arrayCategory[dx] = '移动恶意软件及恶意重定向';
                                }
                            });
                            $scope.hoohoolab_category_cn = arrayCategory.join(',');
                        } else {

                            if ($scope.detail.attr.hoohoolab_category == 'Malware') {
                                $scope.hoohoolab_category_cn = '恶意地址';
                            } else if ($scope.detail.attr.hoohoolab_category == 'Bot C&C') {
                                $scope.hoohoolab_category_cn = '僵尸网络C&C';
                            } else if ($scope.detail.attr.hoohoolab_category == 'Fraud') {
                                $scope.hoohoolab_category_cn = '网络诈骗';
                            } else if ($scope.detail.attr.hoohoolab_category ==
                                'MobileMalware or Malicious redirect') {
                                $scope.hoohoolab_category_cn = '移动恶意软件及恶意重定向';
                            } else {
                                $scope.hoohoolab_category_cn = $scope.detail.attr.hoohoolab_category;
                            };
                        }

                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_geo) {
                            if ($scope.detail.attr.hoohoolab_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        // console.log( $scope.detail.attr.hoohoolab_first_seen);
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                        };
                        break;
                    case 'PhishingURL':
                        // 被钓鱼IP
                        if ($scope.detail.attr.hoohoolab_ip) {
                            $scope.PhishingURL_Ip_name = ['IP'];
                            $scope.PhishingURL_Ip_value.push($scope.detail.attr.hoohoolab_ip);
                        }
                        //whios信息
                        if ($scope.detail.attr.hoohoolab_whois) {
                            for (var key in $scope.detail.attr.hoohoolab_whois) {
                                $scope.PhishingURL_whois_name.push(key);
                                $scope.PhishingURL_whois_value.push($scope.detail.attr.hoohoolab_whois[
                                    key]);
                            };
                        }

                        $scope.hoohoolabTag = [{
                            name: '被钓鱼IP',
                            id: 'PhishingURL_Ip',
                            href: '#PhishingURL_Ip',
                            tagName: $scope.PhishingURL_Ip_name,
                            tagValue: $scope.PhishingURL_Ip_value
                        }, {
                            name: 'whios信息',
                            id: 'MaliciousHash_file',
                            href: '#MaliciousHash_file',
                            tagName: $scope.PhishingURL_whois_name,
                            tagValue: $scope.PhishingURL_whois_value
                        }];
                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_geo) {
                            if ($scope.detail.attr.hoohoolab_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                        };
                        break;
                    case 'MobileMaliciousHash':
                        // 样本信息
                        if ($scope.detail.attr.hoohoolab_file_size) {
                            $scope.MobileMaliciousHash_file_size_name.push('文件尺寸');
                            $scope.MobileMaliciousHash_file_size_value.push($scope.detail.attr.hoohoolab_file_size +
                                ' ' + 'Byte');
                        }
                        $scope.hoohoolabTag = [{
                            name: '恶意样本信息',
                            id: 'MobileMaliciousHash_file_size',
                            href: '#MobileMaliciousHash_file_size',
                            tagName: $scope.MobileMaliciousHash_file_size_name,
                            tagValue: $scope.MobileMaliciousHash_file_size_value
                        }];
                        $scope.geo_cn_array = [];
                        $scope.geo_cn_data = [];
                        if ($scope.detail.attr.hoohoolab_geo) {
                            if ($scope.detail.attr.hoohoolab_geo.indexOf(",") != -1) {
                                $scope.geo_cn_data = $scope.detail.attr.hoohoolab_geo.split(',');
                            } else {
                                $scope.geo_cn_data.push($scope.detail.attr.hoohoolab_geo);
                            }
                            angular.forEach($scope.geo_cn_data, function (s, g) {
                                $scope.country(s);
                                $scope.geo_cn_array.push($scope.country_cn);
                            });
                            $scope.geo_cn_string = $scope.geo_cn_array.join(',');
                        };
                        $scope.hoohoolabType = {
                            threatType: $scope.alert.category, // 威胁类型
                            popularity: $scope.detail.attr.hoohoolab_popularity, // 流行度
                            first_seen: $scope.detail.attr.hoohoolab_first_seen, // 首次发现时间
                            geo: $scope.geo_cn_string, // 主要受影响地区
                        };
                        break;
                    default:
                        break;
                }
                $scope.hoohoolabInfo = true;
                //   console.log($scope.hoohoolabSpan);
            } else {
                switch ($scope.detail.attr.category) {
                    case 'MalwareIP':
                        $scope.detail.attr.category = '恶意地址';
                        // $scope.alert.category = '恶意地址';
                        break;
                    case 'C&C':
                        $scope.detail.attr.category = '僵尸网络';
                        // $scope.alert.category = '僵尸网络';
                        break;
                    case 'Malicious Host':
                        $scope.detail.attr.category = '恶意地址';
                        // $scope.alert.category = '恶意地址';
                        break;
                    case 'Spamming':
                        $scope.detail.attr.category = '垃圾邮件';
                        // $scope.alert.category = '垃圾邮件';
                        break;
                    default:
                        break;
                }
            }
        });
        if (alert.data.matched) {
            var re = new RegExp(alert.data.matched, 'g');
            var span = '<span class="highlight">' + alert.data.matched + '</span>';
        };
        // console.log(alert.data.session);
        $scope.logHtml = alert.data.session.raw.replace(re, span);
        $scope.showLength = function (str, length) {
            if (!length) {
                length = 60;
            }
            return str.substr(0, length) + '...';
        };
        $scope.showDetail = function (item) {
            window.location.href = '/alert/' + item.id;
        };
        $scope.getPage0 = function (pageNow) {
            pageNow = pageNow ? pageNow : 1;
            $scope.pageGeting = true;
            //    当前受到威胁的资产 未处理的  0
            var postData0 = {
                indicator: $scope.detail.indicator,
                is_deal: 0,
                rows: '10'
            };
            postData0['page'] = pageNow;
            $http({
                method: 'GET',
                url: '/alert/get-same-indicator-alert',
                params: postData0
            }).then(function (rsp, status, headers, config) {
                // angular.forEach(rsp.data.data, function (item, index) {
                //     $scope.hoohoolab_false = false;
                //     angular.forEach(JSON.parse(item.data).attr.sources, function (key,
                //         value) {
                //         if (key.split('_')[0] == 'hoohoolab') {
                //             $scope.hoohoolab_false = true;
                //             switch (key.split('_')[1]) {
                //                 case 'BotnetCAndCURL':
                //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
                //                     break;
                //                 case 'IPReputation':
                //                     // 多个威胁类型
                //                     if (JSON.parse(item.data).attr.hoohoolab_category
                //                         .indexOf(',') != -1) {
                //                         var arrayCategory = JSON.parse(item.data)
                //                             .attr.hoohoolab_category.split(',');
                //                         angular.forEach(arrayCategory, function (
                //                             gx, dx) {
                //                             gx = $.trim(gx);
                //                             if (gx == 'malware') {
                //                                 arrayCategory[dx] =
                //                                     '恶意地址';
                //                             } else if (gx == 'spam') {
                //                                 arrayCategory[dx] =
                //                                     '垃圾邮件';
                //                             } else if (gx ==
                //                                 'botnet_cnc') {
                //                                 arrayCategory[dx] =
                //                                     '僵尸网络';
                //                             } else if (gx == 'proxy') {
                //                                 arrayCategory[dx] =
                //                                     '网络代理';
                //                             } else if (gx == 'tor_node') {
                //                                 arrayCategory[dx] =
                //                                     'tor入口节点';
                //                             } else if (gx ==
                //                                 'tor_exit_node') {
                //                                 arrayCategory[dx] =
                //                                     'tor出口节点';
                //                             } else if (gx == 'phishing') {
                //                                 arrayCategory[dx] =
                //                                     '钓鱼网站';
                //                             }
                //                         });
                //                         item.category = arrayCategory.join(',');
                //                     } else {
                //                         if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'malware') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '恶意地址';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'spam') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '垃圾邮件';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'tor_exit_node') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '加密通道';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'botnet_cnc') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '僵尸网络';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'proxy') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '网络代理';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'tor_node') {
                //                             $scope.hoohoolab_category_cn =
                //                                 'tor入口节点';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'tor_exit_node') {
                //                             $scope.hoohoolab_category_cn =
                //                                 'tor出口节点';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'phishing') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '钓鱼网站';
                //                         } else {
                //                             $scope.hoohoolab_category_cn = JSON
                //                                 .parse(item.data).attr.hoohoolab_category;
                //                         };
                //                         item.category = $scope.hoohoolab_category_cn; // 威胁类型
                //                     };
                //                     break;
                //                 case 'MaliciousHash':
                //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
                //                     break;
                //                 case 'MaliciousURL':
                //                     // 多个威胁类型
                //                     if (JSON.parse(item.data).attr.hoohoolab_category
                //                         .indexOf(',') != -1) {
                //                         var arrayCategory = JSON.parse(item.data)
                //                             .attr.hoohoolab_category.split(',');
                //                         angular.forEach(arrayCategory, function (
                //                             gx, dx) {
                //                             gx = $.trim(gx);
                //                             if (gx == 'Malware') {
                //                                 arrayCategory[dx] =
                //                                     '恶意地址';
                //                             } else if (gx == 'Bot C&C') {
                //                                 arrayCategory[dx] =
                //                                     '僵尸网络';
                //                             } else if (gx == 'Fraud') {
                //                                 arrayCategory[dx] =
                //                                     '网络诈骗';
                //                             } else if (gx ==
                //                                 'MobileMalware or Malicious redirect'
                //                             ) {
                //                                 arrayCategory[dx] =
                //                                     '移动恶意软件及恶意重定向';
                //                             };
                //                         });
                //                         item.category = arrayCategory.join(',');
                //                     } else {
                //                         if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Malware') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '恶意地址';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Bot C&C') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '僵尸网络';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Fraud') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '网络诈骗';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'MobileMalware or Malicious redirect'
                //                         ) {
                //                             $scope.hoohoolab_category_cn =
                //                                 '移动恶意软件及恶意重定向';
                //                         } else {
                //                             $scope.hoohoolab_category_cn = JSON
                //                                 .parse(item.data).attr.hoohoolab_category;
                //                         };
                //                         item.category = $scope.hoohoolab_category_cn; // 威胁类型
                //                     };
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
                $scope.setPage0(rsp.data);
                // 当相应准备就绪时调用
            }, function (error, status, headers, config) {
                console.log(error);
            })
        };
        $scope.getPage2 = function (pageNow) {
            pageNow = pageNow ? pageNow : 1;
            $scope.pageGeting = true;
            //    历史受到威胁的资产 已处理的  2
            var postData2 = {
                indicator: $scope.detail.indicator,
                is_deal: 2,
                rows: 10
            };
            postData2['page'] = pageNow;
            $http({
                method: 'GET',
                url: '/alert/get-same-indicator-alert',
                params: postData2
            }).then(function (rsp, status, headers, config) {
                // angular.forEach(rsp.data.data, function (item, index) {
                //     $scope.hoohoolab_false2 = false;
                //     angular.forEach(JSON.parse(item.data).attr.sources, function (key,
                //         value) {
                //         if (key.split('_')[0] == 'hoohoolab') {
                //             $scope.hoohoolab_false2 = true;
                //             switch (key.split('_')[1]) {
                //                 case 'BotnetCAndCURL':
                //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
                //                     break;
                //                 case 'IPReputation':
                //                     // 多个威胁类型
                //                     if (JSON.parse(item.data).attr.hoohoolab_category
                //                         .indexOf(',') != -1) {
                //                         $scope.arrayCategory = JSON.parse(item.data)
                //                             .attr.hoohoolab_category.split(',');
                //                         angular.forEach($scope.arrayCategory,
                //                             function (gx, dx) {
                //                                 gx = $.trim(gx);
                //                                 if (gx == 'malware') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '恶意地址';
                //                                 } else if (gx == 'spam') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '垃圾邮件';
                //                                 } else if (gx ==
                //                                     'tor_exit_node') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '加密通道';
                //                                 } else if (gx ==
                //                                     'botnet_cnc') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '僵尸网络';
                //                                 } else if (gx == 'proxy') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '网络代理';
                //                                 } else if (gx == 'tor_node') {
                //                                     $scope.arrayCategory[dx] =
                //                                         'tor入口节点';
                //                                 } else if (gx ==
                //                                     'tor_exit_node') {
                //                                     $scope.arrayCategory[dx] =
                //                                         'tor出口节点';
                //                                 } else if (gx == 'phishing') {
                //                                     $scope.arrayCategory[dx] =
                //                                         '钓鱼网站';
                //                                 }
                //                             });
                //                         item.category = $scope.arrayCategory.join(
                //                             ',');
                //                     } else {
                //                         if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'malware') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '恶意地址';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'spam') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '垃圾邮件';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'botnet_cnc') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '僵尸网络';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'proxy') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '网络代理';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'tor_node') {
                //                             $scope.hoohoolab_category_cn =
                //                                 'tor入口节点';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'tor_exit_node') {
                //                             $scope.hoohoolab_category_cn =
                //                                 'tor出口节点';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'phishing') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '钓鱼网站';
                //                         } else {
                //                             $scope.hoohoolab_category_cn = JSON
                //                                 .parse(item.data).attr.hoohoolab_category;
                //                         };
                //                         item.category = $scope.hoohoolab_category_cn; // 威胁类型
                //                     }
                //                     break;
                //                 case 'MaliciousHash':
                //                     item.category = JSON.parse(item.data).attr.hoohoolab_threat; // 威胁类型
                //                     break;
                //                 case 'MaliciousURL':
                //                     if (JSON.parse(item.data).attr.hoohoolab_category
                //                         .indexOf(',') != -1) {
                //                         var arrayCategory = JSON.parse(item.data)
                //                             .attr.hoohoolab_category.split(',');
                //                         angular.forEach(arrayCategory, function (
                //                             gx, dx) {
                //                             gx = $.trim(gx);
                //                             if (gx == 'Malware') {
                //                                 arrayCategory[dx] =
                //                                     '恶意地址';
                //                             } else if (gx == 'Bot C&C') {
                //                                 arrayCategory[dx] =
                //                                     '僵尸网络';
                //                             } else if (gx == 'Fraud') {
                //                                 arrayCategory[dx] =
                //                                     '网络诈骗';
                //                             } else if (gx ==
                //                                 'MobileMalware or Malicious redirect'
                //                             ) {
                //                                 arrayCategory[dx] =
                //                                     '移动恶意软件及恶意重定向';
                //                             }
                //                         });
                //                         item.category = arrayCategory.join(',');
                //                     } else {
                //                         if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Malware') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '恶意地址';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Bot C&C') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '僵尸网络';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'Fraud') {
                //                             $scope.hoohoolab_category_cn =
                //                                 '网络诈骗';
                //                         } else if (JSON.parse(item.data).attr.hoohoolab_category ==
                //                             'MobileMalware or Malicious redirect'
                //                         ) {
                //                             $scope.hoohoolab_category_cn =
                //                                 '移动恶意软件及恶意重定向';
                //                         } else {
                //                             $scope.hoohoolab_category_cn = JSON
                //                                 .parse(item.data).attr.hoohoolab_category;
                //                         };
                //                         item.category = $scope.hoohoolab_category_cn; // 威胁类型
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
                //     if (!$scope.hoohoolab_false2) {
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
                $scope.setPage2(rsp.data);
                // 当相应准备就绪时调用
            }, function (error, status, headers, config) {
                console.log(error);
            })
        };
        $scope.setPage0 = function (data) {
            $scope.pages0 = data;
            console.log($scope.pages0);
            sessionStorage.setItem('alertPage', $scope.pages0.pageNow);
        };
        $scope.setPage2 = function (data) {
            $scope.pages2 = data;
            sessionStorage.setItem('alertPage', $scope.pages2.pageNow);
        };
        $scope.getPage0();
        $scope.getPage2();
    });
</script>