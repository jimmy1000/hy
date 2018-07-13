<?php
/**
1）merchant_private_key，商户私钥;merchant_public_key,商户公钥；商户需要按照《密钥对获取工具说明》操作并获取商户私钥，商户公钥。
2）demo提供的merchant_private_key、merchant_public_key是测试商户号108001002002的商户私钥和商户公钥，请商家自行获取并且替换；
3）使用商户私钥加密时需要调用到openssl_sign函数,需要在php_ini文件里打开php_openssl插件
4）php的商户私钥在格式上要求换行，如下所示；
*/
	$merchant_private_key='-----BEGIN PRIVATE KEY-----
MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKzONp1SibLaFZ2+
TeDnqslHhvLoQpKo7U040Tj/HZfMAYawoBJrnYcyOureMIy7zbGL00otkiD7XTOf
TDweVFAdALOYs7lagCGpkkkISdC3byHf5u0934w0QOQsuriaRZLlMxjo81/J1Uxj
H7gCNQVb1L5KY1kOzURPBFiFBHElAgMBAAECgYBB71TrZkjgE1JYI/q3K+4AauhU
2sY8C3SwGFPMeZsjBlY2vEH3hVRP95x/bVP8/tOXQRDXptew+fgv8EI4ViI45q7D
u2ophenVccb0hS+p39mJUP7q+Z4YC/alSERs487tSuQbaZqxlQ5C/++ypGH1pcvl
okdjTET/Fuk6DW/4wQJBANZqME+BjLxGvNAfHkgUKPfaR97FmxYgxvxtHatXunVC
pODKEWkQyMfFsG6xncPjdBNXm1SXSx8qLEAIBRglcc0CQQDOUhnwlLYWRrWWL2Qz
1JzsCfUGrS/Aar57LuWzOd3vU+CBarSEWPwy0FH0sGpXVUCyGp3ElcJDgAGZKzQ2
5gS5AkAocOTlyhCKXmk0c/oZLDxB61jM6saCmPIPIGGNNMHFZimFAHfiMjk8fMv3
ROb10IvPLiHtBZA0s5afCSQ01rMtAkBfFXFE8ZQVIhMvmrmoNVT4ZwJYSFpYaBlO
9ecAOSvxzwsJOZ7l24im9mC+zsrDYtPFAQUygv4bU5Po/gM7RbhpAkASlkVqE8ov
eMEtRPndEj/CMgPgj5rnxIncW7+X+b/qG37Yq4i320ZX5hg6VV4LW+BOs5a+QFaF
vxwf+e4F0ILs
-----END PRIVATE KEY-----';

	//merchant_public_key,商户公钥，按照说明文档上传此密钥到亿宝通商家后台，位置为"支付设置"->"公钥管理"->"设置商户公钥"，代码中不使用到此变量
	$merchant_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCszjadUomy2hWdvk3g56rJR4by
6EKSqO1NONE4/x2XzAGGsKASa52HMjrq3jCMu82xi9NKLZIg+10zn0w8HlRQHQCz
mLO5WoAhqZJJCEnQt28h3+btPd+MNEDkLLq4mkWS5TMY6PNfydVMYx+4AjUFW9S+
SmNZDs1ETwRYhQRxJQIDAQAB
-----END PUBLIC KEY-----';
	
/**
1)dinpay_public_key，亿宝通公钥，每个商家对应一个固定的亿宝通公钥（不是使用工具生成的密钥merchant_public_key，不要混淆），
即为亿宝通商家后台"公钥管理"->"亿宝通公钥"里的绿色字符串内容,复制出来之后调成4行（换行位置任意，前面三行对齐），
并加上注释"-----BEGIN PUBLIC KEY-----"和"-----END PUBLIC KEY-----"
2)demo提供的dinpay_public_key是测试商户号108001002002的亿宝通公钥，请自行复制对应商户号的亿宝通公钥进行调整和替换。
3）使用亿宝通公钥验证时需要调用openssl_verify函数进行验证,需要在php_ini文件里打开php_openssl插件
*/
		$dinpay_public_key ='-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDQ8GBZUyIkhZt1mXZrNjZuHQXd
N9Y/c91sRz//VJWw3U9Zb+/7EtnC4bOYc6PXNp90EalQaaJw7TUMVE8Iex5SBnvb
lt/6nsrtQUXopd6T0DjGxT8tZ0rpyHfl3736pYULoRUO5rZLreyuDIa2X01CIEzA
ml6uetOJwDMYWbVlRQIDAQAB
-----END PUBLIC KEY-----'; 	
	



?>