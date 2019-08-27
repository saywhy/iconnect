<?php
/* @var $this yii\web\View */

$this->title = '告警';
// $this->params['chartVersion'] = '1.1.1';
?>
<link rel="stylesheet" href="/css/alert.css">
<!-- Main content -->
<section class="content" ng-app="myApp" ng-controller="myCtrl">
    <div class="alert-echart">
        <div class="alert-echart-content">
            <div id="alertEchart"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom" style="margin-bottom: 0px">
                <ul class="nav nav-tabs" style="margin-bottom:-1px;">
                    <li class="active">
                        <a href="#protect" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-bell-o"></i> 告警列表</a>
                    </li>
                </ul>
                <div class="tab-content" style="padding-top:0px;border-bottom:0px; ">
                    <!-- protect -->
                    <div class="tab-pane active" id="protect">
                        <div class="row margin">
                        <div class="form-group col-md-3"style="width: 280px;padding: 0 5px;">
                                <label>告警时间</label>
                                <input type="text" class="form-control timerange" readonly style="background-color: #fff;">
                            </div>
                            <div class="form-group col-md-2" style="width:180px;padding: 0 5px;">
                                <label>风险资产</label>
                                <input type="text" class="form-control" ng-model="searchData.client_ip" ng-keyup="myKeyup($event)">
                            </div>
                            <div class="form-group col-md-2" style="width:180px;padding: 0 5px;">
                                <label>来源设备</label>
                                <input type="text" class="form-control" ng-model="searchData.device_name" ng-keyup="myKeyup($event)">
                            </div>
                            <div class="form-group col-md-1" style="width:180px;padding: 0 5px;">
                                <label>告警类型</label>
                                <input type="text" class="form-control" ng-model="searchData.category" ng-keyup="myKeyup($event)">
                            </div>
                            <div class="form-group col-md-2" style="width: 180px;padding: 0 5px;">
                                <label>威胁指标</label>
                                <input type="text" class="form-control" ng-model="searchData.indicator" ng-keyup="myKeyup($event)">
                            </div>
                            <div class="form-group col-md-1" style="width: 120px;padding: 0 5px;">
                                <label>告警等级</label>
                                <select class="form-control input_radius"  style="background-color: #fff;" ng-model="selectedDegree"
                                    ng-options="x.num as x.type for x in degreeData"></select>
                            </div>
                            <div class="form-group col-md-1" style="width: 120px;padding: 0 5px;">
                                <label>处理状态</label>
                                <select class="form-control" ng-init="selectedName=0 " style="background-color: #fff;"
                                    ng-model="selectedName" ng-options="x.num as x.type for x in statusData"></select>
                            </div>
                            <div class="form-group col-md-1" style="width: 100px;padding: 0 5px;">
                                <label style="width: 100%;">&nbsp;</label>
                                <button class="form-control btn btn-primary" style="max-width: 80px;" ng-click="search()">搜&nbsp;&nbsp;索</button>
                            </div>
                        </div>

                        <div class="row margin" >
                            <table class="table table-hover  ng-cloak">
                                <tr style="text-align:center">
                                    <th style="width:10%">风险资产</th>
                                    <th style="width:10%">来源设备</th>
                                    <th style="width:10%">告警类型</th>
                                    <th style="width:10%">威胁指标</th>
                                    <th >告警日志</th>
                                    <th style="width:10%">威胁等级</th>
                                    <th style="width:15%">告警时间</th>
                                    <th style="width:10%" ng-if="rsqType==SensorVersion">解决人员</th>
                                    <th style="width:10%"> 状态</th>
                                </tr>
                                <tr style="cursor: pointer;" ng-repeat="item in pages.data" ng-click="detail(item)">
                                    <td ng-bind="item.client_ip"></td>
                                    <td ng-bind="item.device_name"></td>
                                    <td ng-bind="item.category" title="{{item.category}}"></td>
                                    <td ng-bind="item.indicator"  title="{{item.indicator}}"></td>
                                    <td ng-bind="showLength(item.session)" title="{{item.session}}"></td>
                                    <td ng-bind="item.degree"></td>
                                    <td  ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'"></td>
                                    <td ng-bind="item.processing_person" ng-if="rsqType==SensorVersion"></td>
                                    <td>
                                        <div class="btn-group {{(ariaID == item.id)?'open':''}}">
                                            <button type="button" class="btn btn-{{status_str[item.status].css}} btn-xs dropdown-toggle"
                                                data-toggle="dropdown" aria-expanded="false" ng-click="setAriaID(item,$event);"
                                                ng-blur="delAriaID($event);" set-focus>
                                                <span ng-if="item.status == 2">&nbsp;</span>
                                                <span ng-bind="status_str[item.status].label"></span>
                                                <span ng-if="item.status == 2">&nbsp;</span>
                                                <span class="caret" ng-if="item.status != 2"></span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu" style="min-width:70px" ng-if="item.status != 2">
                                                <li>
                                                    <a href="javascript:void(0);" style="padding: 3px 7px" ng-click="update(item);$event.stopPropagation();">已解决</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <!-- angularjs分页 -->
                            <div style="border-top: 1px solid #f4f4f4;padding: 10px;">
                                <em>共有
                                    <span ng-bind="pages.count"></span>条告警</em>
                                <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-if="pages.pageNow>1">上一页</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)" ng-bind="pages.pageNow-2"
                                            ng-if="pages.pageNow>3"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)" ng-bind="pages.pageNow-1"
                                            ng-if="pages.pageNow>2"></a>
                                    </li>
                                    <li class="active">
                                        <a href="javascript:void(0);" ng-bind="pages.pageNow"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-bind="pages.pageNow+1"
                                            ng-if="pages.pageNow<pages.maxPage-1"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)" ng-bind="pages.pageNow+2"
                                            ng-if="pages.pageNow<pages.maxPage-2"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.maxPage)" ng-bind="pages.maxPage"
                                            ng-if="pages.pageNow<pages.maxPage"></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)" ng-if="pages.pageNow<pages.maxPage">下一页</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script src="/js/controllers/alert.js"></script>