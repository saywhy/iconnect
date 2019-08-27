<?php

namespace frontend\controllers;

use Yii;
use yii\base\Controller;
use common\models\DeviceLog;
use common\models\Config;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class DevicelogController extends Controller {

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
                'only' => ['index', 'page', 'log-file-download', 'log-download'],
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

    private function isAPI() {
        $headers = Yii::$app->request->headers;
        if (stristr($headers['accept'], 'application/json') !== false) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        } else {
            Yii::$app->response->format = \yii\web\Response::FORMAT_XML;
        }
    }

    //获取设备日志
    public function actionPage($page = 1, $rows = 15) {
        $this->isAPI();
        $host = Yii::$app->request->post('host');
        $stime = Yii::$app->request->post('stime');
        $etime = Yii::$app->request->post('etime');
        $page = (int) $page;
        $rows = (int) $rows;
        $where = ['=', 'host', $host];
        $andwhere = ['and', ['>', 'created_at', $stime], ['<', 'created_at', $etime]];
        $query = DeviceLog::find()->where($where)->andwhere($andwhere)->orderBy(['id' => SORT_DESC,]);
        $page = (int) $page;
        $rows = (int) $rows;
        $count = (int) $query->count();
        $maxPage = ceil($count / $rows);
        $page = $page > $maxPage ? $maxPage : $page;
        $logList = $query->offSet(($page - 1) * $rows)->limit($rows)->asArray()->all();
        foreach ($logList as $k => $v) {
            $logList[$k]['created_at'] = date('Y-m-d H:i:s', $v['created_at']);
        }
        $data = [
            'data' => $logList,
            'count' => $count,
            'maxPage' => $maxPage,
            'pageNow' => $page,
            'rows' => $rows,
            'status' => 'success',
        ];
        return $data;
    }

    //下载资产日志的方法
    private function getAllLogs($host, $stime, $etime) {
        $this->isAPI();
        $where = ['=', 'host', $host];
        $andwhere = ['and', ['>', 'created_at', $stime], ['<', 'created_at', $etime]];
        $query = DeviceLog::find()->where($where)->andWhere($andwhere)->orderBy(['id' => SORT_DESC,]);
        $logList = $query->asArray()->all();
//        $data = [
//            'data' => $sensorList,
//            'status' => 'success',
//        ];
        return $logList;
    }

    //添加资产日志的接口
    public function actionAddlog() {
        $this->isAPI();
        if (Yii::$app->request->isPost) {
            $model = new DeviceLog();
            $add_data = Yii::$app->request->post();
            $model->info = $add_data['info'];
            $model->host = $add_data['host'];
            $model->device_id = $add_data['device_id'];
            $model->status = DeviceLog::STATUS;
            return $model->save();
        }
        return 0;
    }

    //下载日志文件的方法 
    public function actionLogFileDownload() {
        //获取当前的资产id
//        $this->isAPI();
        //获取请求参数
        $device_id = Yii::$app->request->get('device_id');
        //以当前时间戳当文件名
        list($t1, $t2) = explode(' ', microtime());
        $file_name = (float) sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        $filepath = Yii::$app->params['downDeviceLogUrl'] . $device_id;
        //定义错误等级
        error_reporting(E_ERROR);
        $fp = fopen($filepath, "r");
        //如果没有文件，则提示，有则直接下载
        if (empty($fp)) {
            return '未发现日志';
        }
        Header("Content-type: application/octet-stream");
        Header("Accept-Ranges: bytes");
        Header("Content-Disposition: attachment; filename=devicelog_" . $file_name . ".log");
        $buffer = 10240;
        while (!feof($fp)) {
            $file_con = fread($fp, $buffer);
            print_r($file_con);
//            p($file_con);die;
//            file_put_contents("/web/src/frontend/runtime/" . $file_name . '.log', $file_con . PHP_EOL, FILE_APPEND);
        }
        fclose($fp);
    }

    //excel导入函数
    public function actionLogDownload() {
        $this->isAPI();
        //获取请求参数
        $request = Yii::$app->request->get();
        $host_ip = long2ip($request['host']);
        $stime = $request['stime'] ? $request['stime'] : time() - 2592000;
        $etime = $request['etime'] ? $request['etime'] : time();
        //获取目标主机的日志数据
        $log_data = self::getAllLogs($request['host'], $stime, $etime);
        //创建excel对象
        $objectPHPExcel = new \PHPExcel();
        $objectPHPExcel->setActiveSheetIndex(0);
//        $page_size = 52;
//        $model = new NewsSearch();
//        $dataProvider = $model->search();
//        $dataProvider->setPagination(false);
//        $data = $dataProvider->getData();
//        $count = $dataProvider->getTotalItemCount();
//        $page_count = (int) ($count / $page_size) + 1;
//        $current_page = 0;
//        $n = 0;
//        foreach ($data as $product) {
//            if ($n % $page_size === 0) {
//            $current_page = $current_page + 1;
        //报表头的输出
        $objectPHPExcel->getActiveSheet()->mergeCells('A1:C1');
        $objectPHPExcel->getActiveSheet()->setCellValue('A1', '资产日志信息（资产：' . $host_ip . '）');
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '产品信息表');
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '产品信息表');
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getFont()->setSize(18);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
//
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '日期：' . date("Y年m月j日"));
//            $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('G2', '第' . $current_page . '/' . $page_count . '页');
//            $objectPHPExcel->setActiveSheetIndex(0)->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        //表格头的输出
        $objectPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(8);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('A2')->getFont()->setSize(14);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '编号');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(120);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('B2')->getFont()->setSize(14);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('B2', '日志信息');
        $objectPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
        $objectPHPExcel->setActiveSheetIndex(0)->getStyle('C2')->getFont()->setSize(14);
        $objectPHPExcel->setActiveSheetIndex(0)->setCellValue('C2', '时间');
        //设置居中
        $objectPHPExcel->getActiveSheet()->getStyle('A2:C2')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置边框
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        //设置颜色
//            $objectPHPExcel->getActiveSheet()->getStyle('B3:G3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FF66CCCC');
//            }
        //明细的输出
        $n = 3;
        foreach ($log_data as $key => $value) {
            $objectPHPExcel->getActiveSheet()->setCellValue('A' . ($n + $key), $key + 1);
            $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + $key), $value['info']);
            $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + $key), date("Y-m-d H:i:s", $value['created_at']));
        }
//            $objectPHPExcel->getActiveSheet()->setCellValue('B' . ($n + 4), $product->id);
//            $objectPHPExcel->getActiveSheet()->setCellValue('C' . ($n + 4), $product->product_name);
//            $objectPHPExcel->getActiveSheet()->setCellValue('D' . ($n + 4), $product->product_agent->name);
//            $objectPHPExcel->getActiveSheet()->setCellValue('E' . ($n + 4), $product->unit);
//            $objectPHPExcel->getActiveSheet()->setCellValue('F' . ($n + 4), $product->unit_price);
//            $objectPHPExcel->getActiveSheet()->setCellValue('G' . ($n + 4), $product->library_count);
        //设置边框
//            $currentRowNum = $n + 4;
//            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':G' . $currentRowNum)->getBorders()->getTop()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':G' . $currentRowNum)->getBorders()->getLeft()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':G' . $currentRowNum)->getBorders()->getRight()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':G' . $currentRowNum)->getBorders()->getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $objectPHPExcel->getActiveSheet()->getStyle('B' . ($n + 4) . ':G' . $currentRowNum)->getBorders()->getVertical()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
//            $n = $n + 1;
//        }
        //设置分页显示
//$objectPHPExcel->getActiveSheet()->setBreak( 'I55' , PHPExcel_Worksheet::BREAK_ROW );
//$objectPHPExcel->getActiveSheet()->setBreak( 'I10' , PHPExcel_Worksheet::BREAK_COLUMN );
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setHorizontalCentered(true);
        $objectPHPExcel->getActiveSheet()->getPageSetup()->setVerticalCentered(false);
        ob_end_clean();
        ob_start();
        header('Content-Type : application/vnd.ms-excel');
        header('Content-Disposition:attachment;filename="' . '资产日志表-' . $host_ip . '.xls"');
        $objWriter = \PHPExcel_IOFactory::createWriter($objectPHPExcel, 'Excel5');
        $objWriter->save('php://output');
//        }
    }

}
