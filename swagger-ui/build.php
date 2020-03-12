<?php
/**
 * 请求格式：http://swagger.ganqixin.dev.tgs.com/build.php?f=yml文件
 * 请求示例：（yml文件名格式：按_分隔，第一个参数是 swagger的ini文件，第二个是项目模块，第三个是控制器模块）
 * http://swagger.ganqixin.dev.gas.com/build.php?f=order-service_order_manage.yml
 *
 */
$path = __DIR__ . "/../swagger-cli/";
require($path . "/vendor/autoload.php");

// 对外输出公共方法
function response($code, $msg = '', $data = '')
{
    $result =  [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    echo json_encode($result);
    die;
}

$file = empty($_GET['f']) ? $_POST['f'] : $_GET['f'];
if(empty($file)){
    response(1, 'get或post参数不存在');
}
$options = explode('.', $file);
$options = explode('_', $options[0]);
$swaggerFileName = $options[0];
if(empty($swaggerFileName)) {
    $swaggerFileName = 'order-service';
}

$cloudName = $options[1];
if(empty($cloudName)) {
    $cloudName = 'order';
}

$moduleName = $options[2];
if(empty($moduleName)) {
    $moduleName = '';
}

$iniFile = $path . $swaggerFileName . '.ini';
if(!file_exists($iniFile)) {
    response(2, 'swagger file \''. $swaggerFileName .'\' not exists');
}
$swaggerIniData = parse_ini_file($iniFile);
//代码扫描目录
$scanDir = (isset($swaggerIniData['scandir']) && $swaggerIniData['scandir'])?$swaggerIniData['scandir']:__DIR__ . '/app';
if(!$scanDir) {
    response(3, 'scan dir not exists');
}
$scanDir .= '/app/'. ucfirst($cloudName) .'/'. ucfirst($moduleName). 'Controllers/';
$distFile = (isset($file) && $file)?$file:'swagger.yml';
#echo "scandir:" . $scanDir . PHP_EOL;
#echo "distFile:" . $distFile . PHP_EOL;

// 第1个参数：扫描要生成文档的代码目录，一般配置问控制器目录
// 第2个参数配置选型，其中exclude配置要忽略的目录
$openapi = \OpenApi\scan($scanDir, ['exclude'=>['/app/views', '/app/vendor']]);

//header('Content-Type: application/x-yaml');
$content = $openapi->toYaml(); //生成yaml内容
//echo $content . PHP_EOL;

$path = dirname($distFile);
if(!is_dir($path)) {
    mkdir($path, 0755, true);
}
file_put_contents($distFile, $content);

#echo 'finish' . PHP_EOL;
response(0, 'Finish', ['scandir' => $scanDir, 'distfile' => $distFile]);
