<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号123001002003的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN RSA PRIVATE KEY-----
MIICXgIBAAKBgQC2wZrhqTld/8X9kgrftXRzLDLvOK4yJC+LDNdGja2ZdyxH3i1Y
ScffqXLPVoYzi3Ng8VOK3wykv34wVlL7R72hTqT+SAe5EWbFg8n5LwGiXXQIfROf
8mDHvXJ0GVNIaSToL5f433TRvdpjfVXszqGeEgKuNqTpgFD1DDca4ZB5GwIDAQAB
AoGBAIvv9y5vq3Okk/Az0Yu2n7JI17+BQTE3sAfjDzwA5DqsnAVzxHjkFd3XHAhT
EnOJhhFm+DdPz5ie4HsWvneWWUZMmC4QFwCrLYrOJhnWdpYnkpfh3E9TxPQ0fDKi
0LNw7VDf2+zXBaQzhyKAhNxrlD1cnG610y1ZWsg6pycP9FVxAkEA6JqonfjY8qSB
e3z2drxTwwcm8iSiIemVto51ZIsI86XNEvJChoZCAw06sLu+LhUzHnz/YtBuQMEx
VSUVhvH8XQJBAMkjaO+led28JOUWDEGw32bDsJOtKq+kPb4fKnNtEuUPmY9nr38W
j4UJJE/N2tbIluad22HNq45eKQrpFApbM9cCQQCPw5dYHAgq4FZPNss2U+wJbJA7
tTyobTDlZmNUQ0LDJMT9YtKPRsfiDvkpZsCCxwOTYnqOXnjmeQG+uG73uvTZAkEA
qTw4ANt0bFLvoCkq6uLNNYQVwEuFjP9eS+ehKjluGnlDtVuCWCY9X6xPdy43oVxp
S2Uqv2HzpPgpBFsUV5phRQJAKQ6an0m7ziB5uOiifa7DaWtzTWLbBxhxzIr2Sw+b
CsIAw2xhK8xbMm2q7vSRed32Roc166pdz3ciO9fOLR+UtA==
-----END RSA PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到智通宝商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC2wZrhqTld/8X9kgrftXRzLDLv
OK4yJC+LDNdGja2ZdyxH3i1YScffqXLPVoYzi3Ng8VOK3wykv34wVlL7R72hTqT+
SAe5EWbFg8n5LwGiXXQIfROf8mDHvXJ0GVNIaSToL5f433TRvdpjfVXszqGeEgKu
NqTpgFD1DDca4ZB5GwIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，智通宝公钥，每个商家对应一个固定的智通宝公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为智通宝商家后台"公钥管理"->"智通宝公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号1118004517的智通宝公钥，请自行复制对应商户号的智通宝公钥进行调整和替换。
3）使用智通宝公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC6lcJGLowQMJ7wjHENt
lN68S7nw54ovuml+Xo1nVj2uZNnPa1TvDP4M39yokahZGn7IXE+4yELdW
2QYE/UZPiaQVhNrz9hHrJMQb+gh3RtDkaQhyXHHVBtjKmnium+nSvJrO2
gQvx6oFPyCOK8d2FEZTVgzN6aRWCLVhUoLIJUFQIDAQAB
-----END PUBLIC KEY-----'; 	
	



?>