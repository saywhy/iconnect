<?php
/* @var $this yii\web\View */

$this->title = '风险资产';
?>
<style>
td, th {
    white-space: nowrap;
    /* overflow: hidden; */
    text-overflow: ellipsis;
    text-align: center;
}
</style>
<section class="content" ng-app="myApp" ng-controller="myCtrl">
    <div class="row">
        <div class="col-md-12 ">
            <div class="set_content">
                <cust-breadcrumb options="{{crumbOptions}}"></cust-breadcrumb>
                <div class="row">   
                    <div class="col-md-12">
                        <div class="box box-solid" >
                            <div class="row margin search_box" style="padding-top:20px;">
                                <div class="form-group col-md-2 " style="width: 180px;padding: 0 5px;margin-left:10px;">
                                    <label>风险资产</label>
                                    <input type="text" class="form-control input_radius" ng-model="searchData.asset_ip"
                                        ng-keyup="myKeyup($event)">
                                </div>
                                <div class="form-group col-md-1">
                                    <label style="width: 100%;">&nbsp;</label>
                                    <button class=" btn btn-primary btn_style" style="max-width: 80px;" ng-click="search()">搜索</button>
                                </div>
                                <div class="download_position ">
                                    <span>
                                        <img src="../src/images/icos/export.png" ng-click="export_alarm()" title="导出"
                                            width="18" height="18" alt="">
                                    </span>
                                </div>
                            </div>
                            <div id="myTabContent" class="tab-content">
                                <div class="tab-pane fade in active">
                                    <div class="box-body">
                                        <table class="table table-hover  ng-cloak" ng-show="pages.data.length>0">
                                            <tr>
                                                <th>风险资产</th>
                                                <th style="cursor: pointer;" ng-click="sort('risk')">
                                                     <span>
                                                         风险指数 <i class="fa fa-sort-down"></i>
                                                    </span>
                                                </th>
                                                <th style="cursor: pointer;" ng-click="sort('count')">
                                                    <span  style="line-height: 18px;">
                                                        告警总数 <i class="fa fa-sort-down"></i>
                                                    </span> </th>
                                                <th>高危告警</th>
                                                <th>中危告警</th>
                                                <th>低危告警</th>
                                            </tr>
                                            <tr style="cursor: pointer;" ng-repeat="item in pages.data">
                                                <td ng-bind="item.asset_ip"></td>
                                                <!-- <td ng-bind="item.indicator"> -->
                                                <td>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-success" ng-style="item.style"></div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a class="a_risk" ng-click="countGo(item)" >
                                                        <span ng-bind="item.alert_count"> </span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="a_risk" ng-click="highGo(item)" ng-if="item.high_count!='0'">
                                                        <span ng-bind="item.high_count"> </span>
                                                    </a>
                                                    <span ng-if="item.high_count=='0'">0</span>
                                                </td>
                                                <td>
                                                    <a class="a_risk" ng-click="midGo(item)" ng-if="item.medium_count!='0'">
                                                        <span ng-bind="item.medium_count"> </span>
                                                    </a>
                                                    <span ng-if="item.medium_count=='0'">0</span>
                                                </td>
                                                <td>
                                                    <a class="a_risk" ng-click="lowGO(item)" ng-if="item.low_count!='0'">
                                                        <span ng-bind="item.low_count"> </span>
                                                    </a>
                                                    <span ng-if="item.low_count=='0'">0</span>
                                                </td>
                                            </tr>
                                        </table>
                                        <!-- angularjs分页 -->
                                        <div style="border-top: 1px solid #f4f4f4;padding: 10px;min-height: 20px;">
                                            <em>共有
                                                <span ng-bind="pages.count"></span>条</em>
                                            <!-- angularjs分页 -->
                                            <ul class="pagination pagination-sm no-margin pull-right ng-cloak">
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)"
                                                        ng-if="pages.pageNow>1">上一页</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(1)" ng-if="pages.pageNow>1">1</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-if="pages.pageNow>4">...</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-2)"
                                                        ng-bind="pages.pageNow-2" ng-if="pages.pageNow>3"></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow-1)"
                                                        ng-bind="pages.pageNow-1" ng-if="pages.pageNow>2"></a>
                                                </li>
                                                <li class="active">
                                                    <a href="javascript:void(0);" ng-bind="pages.pageNow"></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)"
                                                        ng-bind="pages.pageNow+1" ng-if="pages.pageNow<pages.maxPage-1"></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+2)"
                                                        ng-bind="pages.pageNow+2" ng-if="pages.pageNow<pages.maxPage-2"></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-if="pages.pageNow<pages.maxPage-3">...</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.maxPage)"
                                                        ng-bind="pages.maxPage" ng-if="pages.pageNow<pages.maxPage"></a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" ng-click="getPage(pages.pageNow+1)"
                                                        ng-if="pages.pageNow<pages.maxPage">下一页</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script src="/js/controllers/risk.js"></script>