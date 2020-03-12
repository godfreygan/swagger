## swagger
1） swagger放在与项目代码同一级别位置

2） 在 swagger/swagger-cli ,仿照 swagger.ini.example 新建 .ini文件。文件名与项目文件夹名字一致（否则要修改build.php）。
在文件中只需填写代码扫描目录即可，相对路径、绝对路径都可。

3） 此时，已经可以手动生成 yml文件了，执行格式：php /swagger/swagger-cli/build.php -f 模板文件名 -c 项目模块 -m 接口模块
例1：php /swagger/swagger-cli/build.php -f order-service.ini -c order
例2：php /swagger/swagger-cli/build.php -f order-service.ini -c order -m manage

4） 执行了第三步后，在 swagger/swagger-ui 会生成 yml文件。说明已经成功大半了。
order-service_order.yml
order-service_order_manage.yml

5） 配置站点，指向 swagger/swagger-ui 。具体配置不做赘述。

