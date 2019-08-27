<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
//use yii\helpers\ArrayHelper;
use common\models\Config;
use common\models\Alert;

/**
 * Host controller
 */
class HostController extends Controller {

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
                    'actions' => ['index'],
                    'allow' => true,
                    'roles' => ['@'],
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
                'only' => ['index', 'get-snmp-walk', 'safety-equipment-state', 'index-curd'],
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

    //获取snmpwalk信息的方法
    public function actionGetSnmpWalk($path) {
        $path = $path . '?' . $this->getRueryString();
        $ResultClient = Yii::$app->HostClient;
        $postdata = Yii::$app->request->getRawBody();
        $response = $ResultClient->post($path, $postdata, $_FILES);
        $this->setHeader($response);
        return $response->getContent();
    }

    //获取安全设备运行状态的方法
    public function actionSafetyEquipmentState() {
        $this->isAPI();
        if (Yii::$app->request->isGet) {
            $host_id = Yii::$app->request->get('host_id');
            $system_state = Alert::bottomCommunication('iconnect/metrics/' . $host_id);
            $systeminfo = json_decode($system_state, true)['result'];
            $mem_info = 0;
            $cpu_info = 0;
            $disk_info = 0;
            //获取men
            if (!empty($systeminfo)) {
                foreach ($systeminfo[0]['values'] as $v) {
                    if ($v[1] > 0) {
                        $mem_info = round($v[1], 2);
                    }
                }
                //获取cpu
                foreach ($systeminfo[2]['values'] as $v) {
                    if ($v[1] > 0) {
                        $cpu_info = round($v[1], 2);
                    }
                }
                //获取disk
                foreach ($systeminfo[1]['values'] as $v) {
                    if ($v[1] > 0) {
                        $disk_info = round($v[1], 2);
                    }
                }
            }
            return json_encode(['mem' => $mem_info, 'cpu' => $cpu_info, 'disk' => $disk_info]);
        }
        return false;
    }

///iconnect/metrics/5b023296e1382370bfa0b84c
    //安全设备的增删改查的接口
    public function actionIndexCurd($path) {
        $path = $path . '?' . $this->getRueryString();
        $ResultClient = Yii::$app->HostClient;
        if (Yii::$app->request->isPost) {
            $postdata = Yii::$app->request->getRawBody();
            $response = $ResultClient->post($path, $postdata, $_FILES);
        } elseif (Yii::$app->request->isPut) {
            $putdata = Yii::$app->request->getRawBody();
            $response = $ResultClient->put($path, $putdata);
        } elseif (Yii::$app->request->isDelete) {
            $response = $ResultClient->delete($path);
            $this->setHeader($response);
            //如果$response返回空，则说明删除成功
            if (empty($a = $response->getContent())) {
                return json_encode(array('_status' => 'OK'));
            } else {
                return $a;
            }
        } else {
            $response = $ResultClient->get($path);
        }
        $this->setHeader($response);
        return $response->getContent();
    }

    //被actionProxyServer调用的方法
    private function getRueryString() {
        parse_str($_SERVER['QUERY_STRING'], $query);
        foreach ($query as $key => &$value) {
            $value = urlencode($value);
        }
        return http_build_query($query);
    }

    //被actionProxyServer调用的方法
    private function setHeader($response) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'application/json');
    }

    public function actionIndex() {
        return $this->render('index');
    }

}
