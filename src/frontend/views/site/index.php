<?php
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */

$this->title = '';
?>

<link rel="stylesheet" href="/css/site.css">
<!-- Main content -->
<section class="container-fluid" ng-app="myApp" ng-controller="myCtrl" ng-cloak>
    <!-- 第一排 -->
    <div class="box-top row" ng-cloak>
        <div class="head-content">
            <div class="item-top col-md-3" ng-class="{'cursor':item.type == 0}" ng-repeat="item in topinfo" ng-click="goto(item)">
                <div class="box-inside col-md-12">
                    <p class="box-inside-p1">
                        <span class="box-inside-num" ng-mouseover="mouserover(item)" ng-mouseleave='mouseleave(item)'
                            ng-class="{'alarm':item.colorType}" ng-bind="item.num" id="{{item.id}}"></span>
                    </p>
                    <p class="box-inside-p2">
                        <i ng-class="item.head"></i>
                        <span ng-bind="item.name" > </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- 第二排 -->
    <div class="row sed">
        <!-- 左边-监控系统状态 -->
        <div class="container col-md-4">
            <div class="system">
                <div class="box-header">
                    <p>
                        <i class="fa fa-windows"></i>
                        系统状态监控
                    </p>
                </div>
                <div class="row" style="position:relative">
                    <div class="box-sed-left-left col-md-12">
                        <p class="box-sed-left-left-info col-md-4" ng-repeat="item in system">
                            <span class="{{item.color}}"></span>
                            <span ng-bind="item.name"></span><a href="javascript:;" style="text-decoration:underline"
                                class="box-sed-left-span" ng-click="showState(item)"> <span ng-bind="item.num"> </span></a>
                        </p>
                    </div>
                    <div class="box-sed-left-right col-md-12">
                        <!-- 图表 -->
                        <div id="sys"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 中间-流量 -->
        <div class="container col-md-4">
            <div class="flow">
                <div class="box-header">
                    <p>
                        <i class="fa fa-line-chart"></i>
                        日志监控
                    </p>
                </div>
                <div class="row flow-content">
                    <div class="flow-left col-md-12">
                        <p class="flow-item col-md-6">
                            <span class="flow-color2"></span>
                            <span>实时日志</span>
                        </p>
                        <p class="flow-item col-md-6">
                            <span class="flow-color3"></span>
                            <span>告警日志</span>
                        </p>
                    </div>
                    <div class="flow-right col-md-12">
                        <div id="flowtotal"></div>
                        <div id="flowinfo"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 右边- 安全设备 -->
        <div class="col-md-4 container">
            <div class="equipment">
                <div class="box-header">
                    <p>
                        <i class="fa fa-desktop"></i>
                        安全设备
                    </p>
                </div>
                <!-- /.box-header -->
                <div class="row equipment-content">
                    <div class="equipment-left col-md-12">
                        <p class="equipment-left-info col-md-4">
                            <span> 设备总数</span>
                            <span ng-bind="safetyNum" class="box-sed-left-span"> </span>
                        </p>
                        <p class="equipment-left-info col-md-4">
                            <span> 告警总数</span>
                            <span ng-bind="logsNum2" class="box-sed-left-span"> </span>
                        </p>
                        <p class="equipment-left-info col-md-4">
                            <span> 离线设备</span>
                            <span ng-bind="offlineNum" class="box-sed-left-span"> </span>
                        </p>
                    </div>
                    <div class="equipment-right col-md-12">
                        <div id="safetyequipment"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 第三排 -->
    <div class="row third">
        <!-- 左边-风险资产 -->
        <div class="container col-md-5">
            <div class="risk">
                <div class="box-header">
                    <p>
                        <i class="fa fa-database"></i>
                        告警统计
                    </p>
                </div>
                <div class="row">
                    <div class="box-sed-left-right">
                        <!-- 图表 -->
                        <div id="riskassets"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- 中间-未处理告警 -->
        <div class="container col-md-3">
            <div class="untreated">
                <div class="box-header">
                    <p>
                        <i class="fa fa-cubes"></i>
                        未处理告警
                    </p>
                </div>
                <div class="row untreated-content">
                    <div id="untreatedalarm"></div>
                </div>
            </div>

        </div>
        <!-- 右边-威胁类型-->
        <div class="col-md-4 container">
            <div class="threat">
                <div class="box-header">
                    <p>
                        <i class="fa fa-life-ring"></i>
                        威胁类型
                    </p>
                </div>
                <!-- /.box-header -->
                <div class="row threat-content">
                    <div id="threattype">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 第四排 -->
    <div class="row fourth">
        <!-- 左边-风险资产TOP5 -->
        <div class="container col-md-4">
            <div class="top">
                <div class="box-header">
                    <p>
                        <i class="glyphicon glyphicon-sort-by-attributes-alt"></i>
                        Top5风险资产
                    </p>
                </div>
                <div class="row">
                    <div id="top10">
                        <table class="table table-striped">
                            <tr>
                                <th style="width:15%">排名</th>
                                <th style="width:30%">资产</th>
                                <th style="width:55%">风险度</th>
                            </tr>
                            <tr ng-repeat="(index,item) in top5Data">
                                <td ng-bind="index+1">1</td>
                                <td ng-bind="item.asset_ip"></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" ng-style="item.style"></div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右边-最新告警-->
        <div class="col-md-8 container">
            <div class="top">
                <div class="box-header">
                    <p>
                        <i class="fa fa-bell"></i>
                        最新告警
                    </p>
                    <a class="more" href="/alert/index">
                        <span>...</span>
                    </a>
                </div>
                <!-- /.box-header -->
                <div class="row ">
                    <div id="new">
                        <table class="table table-striped">
                            <tr>
                                <th style="width:12%">风险资产</th>
                                <th style="width:10%">来源设备</th>
                                <th style="width:10%">告警类型</th>
                                <th style="width:15%">威胁指标</th>
                                <th>告警日志</th>
                                <th style="width:9%">威胁等级</th>
                                <th style="width:18%">告警时间</th>
                                <!-- <th style="width:10%" ng-if="rsqType==SensorVersion">解决人员</th> -->
                            </tr>
                            <tr ng-repeat="item in newAlertData" ng-click="goAlarm()" style="cursor: pointer;">
                                <td ng-bind="item.client_ip" title="{{item.client_ip}}"></td>
                                <td ng-bind="item.device_name" title="{{item.device_name}}"></td>
                                <td ng-bind="item.category" title="{{item.category}}"></td>
                                <td ng-bind="item.indicator" title="{{item.indicator}}"></td>
                                <td ng-bind="showLength(item.session)" title="{{item.session}}"></td>
                                <td ng-bind="item.degree" title="{{item.degree}}"></td>
                                <td ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'" title="{{item.time*1000 | date:'yyyy-MM-dd HH:mm'}}"></td>
                                <!-- <td ng-bind="item.processing_person" ng-if="rsqType==SensorVersion"></td> -->
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 弹窗 -->
    <div class="pop" ng-show="showpop" ng-click="popfasle(event)">
        <div class="pop-content">
            <p class="pop-content-head">
                <i class="fa fa-windows"></i> 系统状态监控 </p>
            <div class="pop-content-info">
                <div class="sys-chart">
                    <div id="sysEchartCpu"></div>
                </div>
                <div class="sys-chart">
                    <div id="sysEchartMem"></div>
                </div>
                <div class="sys-chart">
                    <div id="sysEchartDisk"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/js/controllers/index.js"></script>