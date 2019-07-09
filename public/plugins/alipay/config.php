<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016081600258432",

		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEAznnqTCqsue365Ibbu/cifqS7gNjgumNeGYlfuppi5m/G90aME+dktX9o4Fv1Xb/GVAmAmcP34ZVjwCIAbP+cSgKPa+JRJNauYFqwuDLmFJW9RJ0LTsBPuuaznjhITwLJXBz1lTh6awr4bFVQ62DxXJgKubsaDYm7IMwL6Aj1U3/3AwoXBqAP/JDeWlKniAwCvx4TW0YqDbmUUPgs2Cgxpw0NjNQc+XfOjOv8nlBdaNOnZZPCRpgheS1A0MyFnRgXBnvMbMG5URgtBzSvAA8GsajSAIGq822pQsQDZ5s5mwnx5bsohqf+CtSxFWvTHNVwhFDQU7gswfTcYx+RhFZemQIDAQABAoIBAQCodKUogUSs6TFiE3jg8S9ufb4Q0GEBEKMsWH0GxIDeGuIMyrzsXEQ5I/sP2VblptoLpDkMuurZXEWm8tbAFwePBmm3Dk0/rftU14kIr1YyAz0I5svb82DZTvHKq4arF83q3iGrHgoanTezQyQi9dPVxKIziCgoVW+bwX4RUIW/Gdei9/3H3XB2cQSNtrrlv6a9/PDYIUn+Apwlorc3xJD9asx5WTy05uefW18AYjQIC9V8iXiRpniq5Gd9JHD8NDiAKcS9bW9oTzQ7eYIu3CYMuUOeriqdbQBVw+7Q0c7E+Ic1YT/cZ8eL5EwhYnq9zL+XoWgIRiD97lbrLIdU8O7RAoGBAO7r6KK+SC5zVt7yj62RxnZV/XM4LO1wr7natYKYEjUd+fVcDpgUDQ39wgzH4QdfuI247M1HjxA0MaaJtIipAWsoC1e+q/fULI/peQ3+yVOT2T0k8z568qBU6/P6Bwik1QcjUBs2rRjB7U62LEWpMGgGNEHMdrrPfjiBhmF5VZW1AoGBAN08R/ptrrThb2CKuwLnQly7AC3lAm1QT4752rUxZxVv3iMNqfIf9aT0yQx+cX+JA11Qop+fv33SbdfQHd8pGmXPyYnXXCWraDz1hqrtqOYi1im/43fmrwghcOiegKxb/BqYUksDxO10Ug/E8YUhEReom7ab1TrAudj3VTyKrvPVAoGAXMBUhaldGDtKhC6iPH2VECd9xxcNyfevIHWZdWNX3isO+IOOXteZ4c6bYJ7P6y7r1Ijv1RlsnpvEkqRu44bIuDLJhHzPAdzql8vJZPd+bOW+tg/8JktQmZGeMA7mYCKtWKIK3SWyuSO/3oi2UyzFT/zomIqppQgcNg+CfaC+6ukCgYEAjqDNWrMZnf10e44U8NM84mGgxPXOccED+Y41JPl7lsNvN7QYa0OVb0psz0Q5Udp4M9HHdhdcMfbW3/qJN5TdMuQZ72bLPnHaGurDbjEX4X52CaZvNJ6hGyHME/wTworu5gpri3cbn2aNfSMf/g8KYPFPqhOfDWiwqCMUbZqPHh0CgYEAwbPucWpCgH4+cZ/BUb7wP1+/yPPctrhdtsDSZfxCXhGXDIZ+866qzdXYmjHaWCQkdYnvqySyIJU6F5cxK3Whv/h2p/xJ814cJmoU2i4mMpG45x/5Y6vMuoCfVBDZdJZedI/Amz/qvHGH5h71poJb74DnIxXQ3dmS1DyFrIthB4Q=",
		
		//异步通知地址
		'notify_url' => "http://local.shop.com/home/order/notify",
		
		//同步跳转
		'return_url' => "http://local.shop.com/home/order/callback",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0vMsUP8kRqYehGR58NDHcOm5Eu1+N8oDBDP7+TP/CUU5Nh9erwlf0INTV5rPswPvkBsw14eqQOuPp/mxtLFfo8hXBpaxQMQe/pbP8H8m0UzBEK2muqZ5kyk+5anKhzYGwi8HMuNwUuvVsRIbqaZUidj+J36Z5N6ENPdZo1HPv3HpdjEgk92Y/IJIAfRSjpCgrdGtYp5c+4f3kcletYEVqAkfAS7LxT3z3XpshFxAo31GBV2ItEMcz7KWyB6pImxwjhdB14Y5RxC5AqxkIQfYmm9zTtL1E0yqolN5z0PVUAQxWYUtktcIvEsVXMpylZ7yX+d3irZIUxhSv8NnlsIHDwIDAQAB",
);