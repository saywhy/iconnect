<?php
/* @var $this yii\web\View */

$this->title = '风险资产详情';
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
        <div class="col-md-12">
            <div class="nav-tabs-custom" style="margin-bottom: 0px">
                <ul class="nav nav-tabs" style="margin-bottom:-1px;">
                    <li class="active">
                        <a href="#protect" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-bell-o"></i> <span ng-bind="risk_title"></span></a>
                    </li>
                </ul>

                <div class="tab-content" style="padding-top:0px;border-bottom:0px; ">
                    <!-- protect -->
                    <div class="tab-pane active" id="protect">
                        <div class="row margin" >
                            <table class="table table-hover  ng-cloak">
                                <tr style="text-align:center">
                                    <th style="width:10%">风险资产</th>
                                    <th style="width:10%">来源设备</th>
                                    <th style="width:10%">告警类型</th>
                                    <th style="width:10%">威胁指标</th>
                                    <th >告警日志</th>
                                    <th style="width:10%">威胁等级</th>
                                    <th style="padding-left: 30px;width:15%">告警时间</th>
                                    <th style="width:10%"> 状态</th>
                                </tr>
                                <tr style="cursor: pointer;" ng-repeat="item in pages.data" ng-click="detail(item)">
                                    <td ng-bind="item.client_ip"></td>
                                    <td ng-bind="item.device_name"></td>
                                    <td ng-bind="item.category" title="{{item.category}}"></td>
                                    <td ng-bind="item.indicator"  title="{{item.indicator}}"></td>
                                    <td ng-bind="showLength(item.session)" title="{{item.session}}"></td>
                                    <td ng-bind="item.degree"></td>
                                    <td style="padding-left: 30px;" ng-bind="item.time*1000 | date:'yyyy-MM-dd HH:mm'"></td>
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



<script src="/js/controllers/risk_detail.js"></script>