### 1:创建授权请求

#### api地址:

### **https://authorize.365xuet.com/authorize/create**
#####基础设置
类型  | 值 |
------------- | -------------
请求类型 | POST
编码  | x-www-form-urlencoded

###### 请求参数
参数  | 说明 |类型| 
------------- | ------------- | ------------- 
app_id  | 微信公众号APPID  |post
authorize_id  | 授权身份凭据 |post
scope | 授权模式 0=静默授权 1=跳出授权  |post
redirect_url  | 授权后的回跳url |post
sign  | 授权身份凭据 |post


###### 响应参数 (请求成功)
```json
{
 "code": 200, #接口响应正常
 "wx_state":"OwgiVc2HVMeLwJ0ZLyxm8YLnpQY3lf7zbNhxpKEKXjVTtmiElgrTFCGvT5YFKvEmImjywoIT84c1RV6CCXVH02Ckx0pIWtu5fe3c"
}
```

### 2:获取微信授权code

#### api地址:

### **https://authorize.365xuet.com/authorize/redirect/$state**

#### 说明
授权成功后跳转至此url地址进行微信授权 成功后后 会自动返回到请求创建时的redirect_url

#####基础设置
类型  | 值 |
------------- | -------------
Request Method  | header重定向


###### 请求参数
参数  | 说明 |	类型| 
------------- | ------------- |------------- 
state  | 创建授权请求是获取的wx_state | get


### 3:获取全局共享AccessToke

#### api地址:

### **https://authorize.365xuet.com/authorize/getAccessToken**

#### 说明
请求成功后自行保存令牌，在未过期之前无需请求接口

#####基础设置
类型  | 值 |
------------- | -------------
请求类型 | POST
编码  | x-www-form-urlencoded

###### 请求参数
参数  | 说明 |类型| 
------------- | ------------- | ------------- 
app_id  | 微信公众号APPID  |post
authorize_id  | 授权身份凭据 |post
sign  | 授权身份凭据 |post

###### 响应参数 (请求成功)
```json
{
"code":"200",
"data":{
	"access_token":"6_5OEgX2YpaJVH2UDmXqQpaf1EPreM6HcSPKTfDM0-qfrH9cP5lmF7-6vLv9FVWGo6jv5VZ33MlYgWyd7EmnmWo5Tvab91y8K9imR9xo3kq-uyBXh20l_Og2cs5M3HySQcj3Yz4lwqJG26X4AAMNZcAAAABN",
	"access_token_expire_time":1517567215 //过期时间
}}
```


### 4:批量发送短信

#### api地址:

### **https://authorize.365xuet.com/smsSend**

#### 说明
批量发送短信,最大不超过100条

#####基础设置
类型  | 值 |
------------- | -------------
请求类型 | POST
编码  | x-www-form-urlencoded

###### 请求参数
参数  | 说明 |类型|
------------- | ------------- | -------------
sms  | 短信列表  |post
authorize_id  | 授权身份凭据 |post
sign  | 授权身份凭据 |post

###### 响应参数 (请求成功)
```json
{
    "code": "200",
    "data": {
        "20180308123": {
            "code": "200",//发送请求创建成功
            "msg_id": "13"//发送状态查询id 成功时有此参数
        },
        "20180308124": {
            "code": "200",//发送请求创建成功
            "msg_id": "14"//发送状态查询id 成功时有此参数
        },
        "20180308125": {
            "code": "400",//发送请求创建成功
            "msg": "短信接收者错误"//发送请求创建失败 失败时有此参数
        },
        "20180308126": {
            "code": "200",//发送请求创建成功
            "msg_id": "15"//发送状态查询id 成功时有此参数
        }
    }
}
```

```json
[
  {
      "custom_id":"20180308123",//自定义编辑id 返回信息将会存放在此id中
      "accept":"17612163856",//消息接收者
      "content":"\u3010365\u5b66\u5802\u3011\u8fd9\u662f\u53e6\u4e00\u6761\u6d4b\u8bd5\u77ed\u4fe1"
  },
  {
      "custom_id":"20180308124",//自定义编辑id 返回信息将会存放在此id中
      "accept":"17612163856",//消息接收者
      "content":"\u3010365\u5b66\u5802\u3011\u8fd9\u662f\u53e6\u4e00\u6761\u6d4b\u8bd5\u77ed\u4fe1"
  }
]
```


### 5:批量查询推送状态

#### api地址:

### **https://authorize.365xuet.com/getMsgState**

#### 说明
批量查询推送ID

#####基础设置
类型  | 值 |
------------- | -------------
请求类型 | POST
编码  | x-www-form-urlencoded

###### 请求参数
参数  | 说明 |类型|
------------- | ------------- | -------------
msg_id  | 消息ID列表以逗号分隔  |post
authorize_id  | 授权身份凭据 |post
sign  | 授权身份凭据 |post

###### 响应参数 (请求成功)
```json
{
    "code": "200",
    "data": [
        {
            "msg_id": 1,//查询的消息ID
            "state": 1,//送达状态 0=待发送 1=已发送 2=已送达 3=发送失败 4=未送达
            "errmsg": "116",//错误报告
            "receipt_time": null//送达时间
        },
        {
            "msg_id": 2,
            "state": 2,
            "errmsg": "",
            "receipt_time": 1520493708
        },
        {
            "msg_id": 3,
            "state": 1,
            "errmsg": "",
            "receipt_time": null
        },
        {
            "msg_id": 4,
            "state": 0,
            "errmsg": "",
            "receipt_time": null
        },
        {
            "msg_id": 5,
            "state": 0,
            "errmsg": "",
            "receipt_time": null
        }
    ]
}
```



