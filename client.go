package main

import (
	"crypto/tls"
	"crypto/x509"
	"encoding/json"
	"encoding/pem"
	"fmt"
	"io/ioutil"
)

var keyPem = "/Users/a/sslcrt/yyhealth/client.pem"
var certPem = "/Users/a/sslcrt/yyhealth/client.crt"
var pemPass = "xiaobai"
var param map[string]string

func main() {

	// 读取私钥文件
	keyBytes, err := ioutil.ReadFile(keyPem)
	if err != nil {
		panic("Unable to read keyPem")
	}
	// 把字节流转成PEM结构
	block, rest := pem.Decode(keyBytes)
	if len(rest) > 0 {
		panic("Unable to decode keyBytes")
	}
	// 解密PEM,生成der编码的字节切片
	der, err := x509.DecryptPEMBlock(block, []byte(pemPass))
	if err != nil {
		panic("Unable to decrypt pem block")
	}
	// 解析出其中的RSA 私钥
	key, err := x509.ParsePKCS1PrivateKey(der)
	if err != nil {
		panic("Unable to parse pem block")
	}
	// 编码成新的PEM 结构
	keyPEMBlock := pem.EncodeToMemory(
		&pem.Block{
			Type:  "RSA PRIVATE KEY",
			Bytes: x509.MarshalPKCS1PrivateKey(key), //将rsa私钥序列化为ASN.1 PKCS#1 DER编码
		},
	)
	// 读取证书文件
	certPEMBlock, err := ioutil.ReadFile(certPem)
	if err != nil {
		panic("Unable to read certPem")
	}
	// 生成密钥对
	cert, err := tls.X509KeyPair(certPEMBlock, keyPEMBlock)
	if err != nil {
		panic("Unable to read privateKey")
	}
	conn, err := tls.Dial("tcp", "127.0.0.1:9502", &tls.Config{InsecureSkipVerify: true, Certificates: []tls.Certificate{cert}})
	if err != nil {
		panic(err)
	}
	arr := make(map[string]interface{})
	param := make(map[string]string)
	x := make(map[string]interface{})
	param["phone"] = "17896013097"
	param["field"] = "name"
	arr["serv"] = "\\KouBeiService"
	arr["func"] = "getAppMerchatList"
	x["param"] = param
	// x["token"] = "201904BBba28f70a42a8467397850dd375044X93"
	arr["arg"] = x
	y, _ := json.Marshal(arr)
	fmt.Println(string(y))
	conn.Write(y)
	buf := make([]byte, 1024) //声明读取返回数据容器
	msg, err := conn.Read(buf)
	fmt.Println(string(buf[:msg]))

}
