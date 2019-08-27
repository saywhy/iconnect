<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Alert;
use common\models\Config;
use common\models\DeviceLog;
use common\models\SafetyScore;
use common\models\Report;
use common\models\UserLog;
use common\models\DeviceHoursLogCount;

/**
 * Group controller
 */
class ReportController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        if (Config::getLicense()['validLicenseCount'] == 0) {
            $rules = [];
        } else {
            $rules = [
                    [
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['?'],
                ],
                    [
                    'actions' => [''],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                    [
                    'actions' => ['index', 'model', 'create-report-form', 'page', 'delete'],
                    'allow' => true,
                    'roles' => ['user'],
                ],
                    [
                    'actions' => [],
                    'allow' => true,
                    'roles' => ['admin'],
                ],
            ];
        }
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'model', 'create-report-form', 'page', 'delete'],
                'rules' => $rules,
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                // 'logout' => ['post'],
                // 'test' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public $enableCsrfValidation = false;

    private function isAPI() {
        $headers = Yii::$app->request->headers;
        if (stristr($headers['accept'], 'application/json') !== false) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        }
    }

    //跳转到首页
    public function actionIndex() {
        return $this->render('index');
    }

    //获取所有的报表
    public function actionPage($page = 1, $rows = 15) {
        $this->isAPI();
        if (Yii::$app->request->isPost) {
            $post = json_decode(Yii::$app->request->getRawBody(), true);
            $page = empty($post['page']) ? $page : $post['page'];
            $rows = empty($post['rows']) ? $rows : $post['rows'];
        }
        $page = (int) $page;
        $rows = (int) $rows;
        $query = Report::find()->orderBy('id DESC');
        $page = (int) $page;
        $rows = (int) $rows;
        $count = (int) $query->count();
        $maxPage = ceil($count / $rows);
        $page = $page > $maxPage ? $maxPage : $page;
        $report = $query->offSet(($page - 1) * $rows)->limit($rows)->asArray()->select('id,report_name,report_type,create_time,stime,etime')->all();
        foreach ($report as $k => $v) {
            $report[$k]['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
            $report[$k]['stime'] = date('Y-m-d', $v['stime']);
            $report[$k]['etime'] = date('Y-m-d', $v['etime']);
        }
        $data = [
            'data' => $report,
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'rows' => $rows,
            'status' => 'success',
        ];
        return $data;
    }

    //删除报表
    public function actionDelete() {
        $this->isAPI();
        if (Yii::$app->request->isPost) {
            $report_id = Yii::$app->request->post('id');
        }
        Report::deleteAll(['id' => $report_id]);
        return 0;
    }

    //下载报表
    public function actionModel() {
        $this->isAPI();
        //获取开始时间和结束时间
        $parames = Yii::$app->request->get();
        if (empty($parames['id'])) {
            return '请选择需要下载的报表';
        }
        $report_id = $parames['id'];
        //获取当前服务器地址
        $server_ip = Yii::$app->request->hostInfo;
        $report = new Report();
        $report_info = $report->find()->where(['=', 'id', $report_id])->asArray()->one();
        if (empty($report_info)) {
            return '该报告不存在';
        }
        //判断报表类型是docx还是csv
        if ($report_info['report_type'] == 'docx') {
            $report_info['stime'] = date("Y.m.d", $report_info['stime']);
            $report_info['etime'] = date("Y.m.d", $report_info['etime']);
            $report_info['risk_assets'] = json_decode($report_info['risk_assets'], true);
            $report_info['threat_level'] = json_decode($report_info['threat_level'], true);
            $report_info['threat_type'] = json_decode($report_info['threat_type'], true);
            $report_info['last_alert'] = json_decode($report_info['last_alert'], true);
            $report_info['device_logs'] = json_decode($report_info['device_logs'], true);
            $report_info['device_send_logs'] = json_decode($report_info['device_send_logs'], true);
            $report_info['risk_device'] = json_decode($report_info['risk_device'], true);
            $report_info['server_ip'] = $server_ip;
            return $this->render('model', $report_info);
        } else if ($report_info['report_type'] == 'csv') {
            $preData = Alert::find()->where(['AND', ['>=', 'time', $report_info['stime']], ['<=', 'time', $report_info['etime']]])->select('id,time,category,indicator,degree,client_ip,status,processing_person,updated_at,session,data')->orderBy('id DESC')->asArray()->all();
            $downloadData = Alert::changeCategory($preData);
            $EXCEL_OUT = iconv('UTF-8', 'GBK', "风险资产,威胁类型,威胁指标,威胁等级,告警状态,处理人,处理时间,告警时间,告警信息\n");
            foreach ($downloadData as $key => $item) {
                $status = '';
                $processing_time = '';
                $processing_person = '';
                if ($item['status'] == 0) {
                    $status = '新告警';
                } else if ($item['status'] == 1) {
                    $status = '未解决';
                } else if ($item['status'] == 2) {
                    $status = '已解决';
                    $processing_time = date('Y-m-d H:i:s', $item['updated_at']);
                    $processing_person = $item['processing_person'];
                }
                try {
                    $line = iconv('UTF-8', 'GBK//IGNORE', $item['client_ip'] . ',' .
                        "\"" . $item['category_plus'] . "\"" . ',' .
                        $item['indicator'] . ',' .
                        $item['degree'] . ',' .
                        $status . ',' .
                        "\"" . $processing_person . "\"" . ',' .
                        $processing_time . ',' .
                        date('Y-m-d H:i:s', $item['time']) . ',' .
                        "\"" . str_replace("\"", "'", str_replace(PHP_EOL, '', $item['session'])) . "\"" .
                        "\n"
                    );
                } catch (Exception $e) {
                    break;
                }
                $EXCEL_OUT .= $line;
            }
            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=告警列表_" . date("Y.m.d", $report_info['stime']) . "-" . date("Y.m.d", $report_info['etime']) . ".csv");
            echo $EXCEL_OUT;
            exit();
        }
    }

    //生成报表
    public function actionCreateReportForm() {
        $this->isAPI();
        //获取开始时间和结束时间,及报表类型
        $parames = Yii::$app->request->get();
        if (empty($parames['stime']) || empty($parames['etime']) || empty($parames['report_name'])) {
            return '请准确选择开始、结束时间、报表名称及报表类型';
        }
        if (!in_array($parames['report_type'], ['docx', 'csv'])) {
            return '报表类型选择错误';
        }
        $stime = $parames['stime'];
        $etime = $parames['etime'];
        $report_name = $parames['report_name'];
        $report_type = $parames['report_type'];
        //实例化报表类
        $report = new Report();
        if ($report_type == 'docx') {
            //获取告警数量、风险资产、安全评分
            $alarm_count = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->count('id');
            $risk_dev_count = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->groupBy('client_ip')->count('id');
            $safety_score = SafetyScore::getSafetyScore();
            //获取风险资产top10
            $risk_assets = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->groupBy('client_ip')->select('count(id) as risk_assets_count,client_ip')->orderBy('risk_assets_count DESC')->limit(10)->asArray()->all();
            //威胁等级及数量
            $threat_level = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->groupBy('degree')->select(['COUNT(id) as total_count,degree'])->asArray()->orderBy('total_count DESC')->all();
            //威胁类型及数量
            $threat_type = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->groupBy('degree')->groupBy('category')->select(['COUNT(id) as total_count,category'])->asArray()->orderBy('total_count DESC')->all();
            //最新告警
            $last_alert = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->select(['degree', 'type', 'device_ip', 'time'])->asArray()->limit(10)->orderBy('id DESC')->all();

            foreach ($last_alert as $k => $v) {
                $last_alert[$k]['time'] = date("Y-m-d H:i:s", $v['time']);
                //转换威胁类型
                $last_alert[$k]['type'] = Alert::changeType($v['type']);
            }
            //获取所有的安全设备，给以下查询做准备
            $safety_equipment = json_decode(Alert::bottomCommunication('iconnect/host'), true);
            //如果通信失败
            if (empty($safety_equipment['_items'])) {
                $device_logs = [];
                $risk_device = [];
                $per_safety_equipment_logs = [];
            } else {
                $safety_equipment_ids = [];
                foreach ($safety_equipment['_items'] as $value) {
                    $safety_equipment_ids[] = $value['_id'];
                }
                //安全设备运行状况
                $device_logs = DeviceLog::find()->where(['and', ['<', 'created_at', $etime], ['>', 'created_at', $stime]])->andWhere(['IN', 'device_id', $safety_equipment_ids])->select('host,info,device_id,created_at')->orderBy('id DESC')->asArray()->all();
                foreach ($device_logs as $key => $value) {
                    $device_logs[$key]['created_at'] = date('Y-m-d H:i:s', $value['created_at']);
                    foreach ($safety_equipment['_items'] as $kk => $vv) {
                        if ($value['device_id'] == $vv['_id']) {
                            $device_logs[$key]['device_name'] = $vv['name'];
                            break;
                        }
                    }
                }
                //安全设备发送日志量统计
                $per_safety_equipment_logs = [];
                foreach ($safety_equipment['_items'] as $kkk => $vvv) {
                    $log_count = DeviceHoursLogCount::find()->asArray()->where(['and', ['<', 'statistics_date', date('Y-m-d H:i', $etime)], ['>', 'statistics_date', date('Y-m-d H:i', $stime)]])->andWhere(['=', 'device_id', $vvv['_id']])->sum('log_count');
                    $per_safety_equipment_logs[$kkk]['dev_name'] = $vvv['name'];
                    $per_safety_equipment_logs[$kkk]['logs_count'] = $log_count ? $log_count : 0;
                }
//                foreach ($safety_equipment['_items'] as $kk => $value) {
//                    //获取每个设备的日志数
//                    try {
//                        $log_count = 0;
//                        $log_count_arr = json_decode(Alert::bottomCommunication('/iconnect/utils/log/statistics/' . $value['_id']), true)['result'];
//                        foreach ($log_count_arr as $v) {
//                            if ($v['_id'] <= date('Y-m-d H:i:s', $etime) && $v['_id'] >= date('Y-m-d H:i:s', $stime)) {
//                                $log_count += $v['number'];
//                            }
//                        }
//                        $per_safety_equipment_logs[$kk]['dev_name'] = $value['name'];
//                        $per_safety_equipment_logs[$kk]['logs_count'] = $log_count;
//                    } catch (Exception $e) {
//                        $per_safety_equipment_logs[$kk]['dev_name'] = $value['name'];
//                        $per_safety_equipment_logs[$kk]['logs_count'] = 0;
//                    }
//                }
                //产生告警安全设备及告警数量统计
                $risk_device_temp = Alert::find()->where(['and', ['<', 'time', $etime], ['>', 'time', $stime]])->andWhere(['IN', 'device_id', $safety_equipment_ids])->groupBy('device_id')->select('count(id) as risk_assets_count,device_id,device_ip')->asArray()->all();
                $risk_device = [];
                foreach ($safety_equipment['_items'] as $k => $v) {
                    $risk_device[$k]['dev_name'] = $v['name'];
                    $risk_device[$k]['risk_assets_count'] = 0;
                    foreach ($risk_device_temp as $kk => $vv) {
                        if ($v['_id'] == $vv['device_id']) {
                            $risk_device[$k]['risk_assets_count'] = $vv['risk_assets_count'];
                        }
                    }
                }
                //排序
                $arrSort = array();
                foreach ($risk_device AS $uniqid => $row) {
                    foreach ($row AS $key => $value) {
                        $arrSort[$key][$uniqid] = $value;
                    }
                }
                array_multisort($arrSort['risk_assets_count'], constant('SORT_DESC'), $risk_device);
            }
            $report->report_name = $report_name;
            $report->create_time = time();
            $report->stime = $stime;
            $report->etime = $etime;
            $report->report_type = 'docx';
            $report->alarm_count = $alarm_count;
            $report->risk_dev_count = $risk_dev_count;
            $report->safety_score = $safety_score;
            $report->risk_assets = json_encode($risk_assets);
            $report->threat_level = json_encode($threat_level);
            $report->threat_type = json_encode($threat_type);
            $report->last_alert = json_encode($last_alert);
            $report->device_logs = json_encode($device_logs);
            $report->device_send_logs = json_encode($per_safety_equipment_logs);
            $report->risk_device = json_encode($risk_device);
            $report->save();
        } else {
            $report->report_name = $report_name;
            $report->create_time = time();
            $report->stime = $stime;
            $report->etime = $etime;
            $report->report_type = 'csv';
            $report->save();
        }
        return 0;
    }

    //每天生成每个安全设备的每小时的日志
    public function actionSaveDeviceLogCount() {
        DeviceHoursLogCount::saveDeviceLogCount();
        return 0;
    }

}
