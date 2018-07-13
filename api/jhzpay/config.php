<?php
include 'common.php';

/**
 * 配置文件定义
* User: Administrator
* Date: 2018/1/2 0002
* Time: 12:48
*/

// 商户在支付系统的平台号
$merchant_id = '500000724657' ;
//秘钥
$private_key=<<<EOD
-----BEGIN PRIVATE KEY-----
MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAJCmqo1jfhT80NvqA3Wjyo0uCNk4LrVj7O9aqQJfvDsKGBCDsMZceQDaMZJE6dZ7DkDLYw11wbIgXYH0jVHUc1AUFx/qJv2o5fj9bMt98YVEEHa9dcna2AdktIkZqhN/dGtCcNwMjZadV3bQJFEpDRhe4zdqrBfl3biKlcdvYIW9AgMBAAECgYBK7KlG1xQFlvYwEsR7+Lz/56pZqUo4VmvxhsooGqKLCi1w0GWOOXPLxzkKBHwCxnaZhT/nRulvdGg62gshKaQYPRL0T1eGkA48Le1IKJ7glQPuZ4v2yI9n3UBnywD5ksfTB4L08l7b0mT8LLwE+jf8Gr4iX7NKLYsKP/xYrk0lAQJBAMJ5iQ3IlE5+uzlkYKjhUcIyQ0QieKjHcDjaqhyurFXQKDddMSl1FKHXr5Uw0IelhL4hg5ldZnMLUDTe+QBy210CQQC+aeub1LD/3tSyqV1wg3/+LSYRu/GkZ+qmRqPiHSOIiC3fPxKCx1DRdRSL9ZYH+a5dPoyah37uFZKeg6Af0g3hAkEApxj0aRJ6U8PSrOqRnyoTLPAxGf8ge6z5wPApkIGJdCZqF8AMONnvw2vm4yLRWmwe1ZtITuOQ3rLO1M7tVrRZxQJAaCd4AR0uVEeHANMzkT/c2yPHFxw+6TcOWzV4n05hCWWz8dGGRpLP2kK4onYLQwGIJuj37+79ty2Frb9B7yXvAQJAcAwfwoaaVdXlES78j6Yj4XYRQjI6Y1wQ+nEe8zeKT55vFzJqo234AdYwWXINwoGHIhmRnJLXT9IpEtjqRonBgg==
-----END PRIVATE KEY-----
EOD;
$public_key = <<<EOD
-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCL4nMv6qK7Lt1MzfK20LrVd/0g0pXIvV281sT16s4xIWEg/Hfv0su0MHdbTobZfHcziyO/xdmItCzkcJOIIskuC3QukNrWnt7kf1wZ1OmIMWAcS5s9wnMd0QcpDpcyfZfJvlZgFDtgJtApXvCBBVIEX65W1FnmlZ7wccO3Ca+J8QIDAQAB
-----END PUBLIC KEY-----
EOD;



//支付请求
$payRequestUrl = 'http://zf.szjhzxxkj.com/ownPay/pay ';



//网银支付请求
//$bankPayRequestUrl = 'https://gateway.ioo8.com/b2cPay/initPay';


//异步回调地址
$pay_callbackurl = 'http://payhy.8889s.com/api/jhzpay/callback.php';

//同步回调地址
$hrefbackurl     = 'http://payhy.8889s.com';