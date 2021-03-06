/* WiFi Example
 * Copyright (c) 2018 ARM Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

#include "mbed.h"
#include "TCPSocket.h"
#include "XNucleoIKS01A2.h"
#define WIFI_IDW0XX1    2
DigitalOut LED(D4);

static XNucleoIKS01A2 *mems_expansion_board = XNucleoIKS01A2::instance(D14, D15, NC, NC);

/* Retrieve the composing elements of the expansion board */
//static LSM303AGRMagSensor *magnetometer = mems_expansion_board->magnetometer;
static HTS221Sensor *hum_temp = mems_expansion_board->ht_sensor;
//static LPS22HBSensor *press_temp = mems_expansion_board->pt_sensor;
static LSM6DSLSensor *acc_gyro = mems_expansion_board->acc_gyro;
//static LSM303AGRAccSensor *accelerometer = mems_expansion_board->accelerometer;

static char *print_double(char* str, double v, int decimalDigits=2)
{
  int i = 1;
  int intPart, fractPart;
  int len;
  char *ptr;

  /* prepare decimal digits multiplicator */
  for (;decimalDigits!=0; i*=10, decimalDigits--);

  /* calculate integer & fractinal parts */
  intPart = (int)v;
  fractPart = (int)((v-(double)(int)v)*i);

  /* fill in integer part */
  sprintf(str, "%i.", intPart);

  /* prepare fill in of fractional part */
  len = strlen(str);
  ptr = &str[len];

  /* fill in leading fractional zeros */
  for (i/=10;i>1; i/=10, ptr++) {
      if (fractPart >= i) {
        break;
      }
      *ptr = '0';
  }

  /* fill in (rest of) fractional part */
  sprintf(ptr, "%i", fractPart);

  return str;
}


#if (defined(TARGET_DISCO_L475VG_IOT01A) || defined(TARGET_DISCO_F413ZH))
#include "ISM43362Interface.h"
ISM43362Interface wifi(MBED_CONF_APP_WIFI_SPI_MOSI, MBED_CONF_APP_WIFI_SPI_MISO, MBED_CONF_APP_WIFI_SPI_SCLK, MBED_CONF_APP_WIFI_SPI_NSS, MBED_CONF_APP_WIFI_RESET, MBED_CONF_APP_WIFI_DATAREADY, MBED_CONF_APP_WIFI_WAKEUP, false);

#else // External WiFi modules

#if MBED_CONF_APP_WIFI_SHIELD == WIFI_IDW0XX1
#include "SpwfSAInterface.h"
SpwfSAInterface wifi(MBED_CONF_APP_WIFI_TX, MBED_CONF_APP_WIFI_RX);
#endif // MBED_CONF_APP_WIFI_SHIELD == WIFI_IDW0XX1

#endif

const char *sec2str(nsapi_security_t sec)
{
    switch (sec) {
        case NSAPI_SECURITY_NONE:
            return "None";
        case NSAPI_SECURITY_WEP:
            return "WEP";
        case NSAPI_SECURITY_WPA:
            return "WPA";
        case NSAPI_SECURITY_WPA2:
            return "WPA2";
        case NSAPI_SECURITY_WPA_WPA2:
            return "WPA/WPA2";
        case NSAPI_SECURITY_UNKNOWN:
        default:
            return "Unknown";
    }
}

int scan_demo(WiFiInterface *wifi)
{
    WiFiAccessPoint *ap;

    printf("Scan:\n");

    int count = wifi->scan(NULL,0);
    printf("%d networks available.\n", count);

    /* Limit number of network arbitrary to 15 */
    count = count < 15 ? count : 15;

    ap = new WiFiAccessPoint[count];
    count = wifi->scan(ap, count);
    for (int i = 0; i < count; i++)
    {
        printf("Network: %s secured: %s BSSID: %hhX:%hhX:%hhX:%hhx:%hhx:%hhx RSSI: %hhd Ch: %hhd\n", ap[i].get_ssid(),
               sec2str(ap[i].get_security()), ap[i].get_bssid()[0], ap[i].get_bssid()[1], ap[i].get_bssid()[2],
               ap[i].get_bssid()[3], ap[i].get_bssid()[4], ap[i].get_bssid()[5], ap[i].get_rssi(), ap[i].get_channel());
    }

    delete[] ap;
    return count;
}

void http_demo(NetworkInterface *net,int tmf,int huf,int of)
{
    TCPSocket socket;
    nsapi_error_t response;

    printf("Sending HTTP request to www.arm.com...\n");

    // Open a socket on the network interface, and create a TCP connection to www.arm.com
    socket.open(net);
    //response = socket.connect("www.arm.com", 80);
    //response = socket.connect("david2012.duckdns.org", 80);
    response = socket.connect("220.126.16.152", 80);
    //response = socket.connect("www.daum.net", 80);
    if(0 != response) {
        printf("Error connecting: %d\n", response);
        socket.close();
        return;
    }

    // Send a simple http request
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: www.arm.com\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org:80/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: 220.126.16.152\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: 220.126.16.15211\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org"="inputtemp1humi1\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org/process.php?temp=1&humi=1\r\n\r\n";
  // sucess char sbuffer[] = "GET /wordpress/process.php?temp=2&&humi=3&om=4 HTTP/1.0\r\n\r\n";
// char sbuffer[] = "GET /wordpress/process.php?temp=2&&humi=3&om=4 HTTP/1.0\r\n\r\n";
  
 
#define _CRT_SECURE_NO_WARNINGS
#include <stdio.h>
#include <string.h>    // strcat 함수가 선언된 헤더 파일

/*  char s1[5] = "temp";
  char s2[28] = "GET /wordpress/process.php?"; // s2 뒤에 붙일 것이므로 배열 크기를 크게 만듦

  strcat(s2, s1);        // s2 뒤에 s1를 붙임

  printf("%s\n", s2);    // Helloworld

*/

char ur[28]="GET /wordpress/process.php?" ;

char temp_h[6]="temp=" ;
int tm=tmf;		
char temp[6];
sprintf(temp,"%d",tm);

char humi_h[8]="&&humi=" ;
int hu=huf;		
char humi[6];
sprintf(humi,"%d",hu);

char om_h[5]="&om=" ;
int o=of;		
char om[6];
sprintf(om,"%d",o);

char tail[17]=" HTTP/1.0\r\n\r\n" ;

char sbuffer[73];
strcat(sbuffer,ur);
strcat(sbuffer,temp_h);
strcat(sbuffer,temp);
strcat(sbuffer,humi_h);
strcat(sbuffer,humi);
strcat(sbuffer,om_h);
strcat(sbuffer,om);
strcat(sbuffer,tail);




/*	
  char ur[28]="GET /wordpress/process.php?" ;

  char temp_h[6]="temp=" ;
  float tm=10.01f;		
  char temp[6];
       sprintf(temp,"%f",tm);

  char humi_h[8]="&&humi=" ;
  float hu=10.02f;		
  char humi[6];
       sprintf(humi,"%f",hu);

  char om_h[5]="&om=" ;
  float o=10.03f;		
  char om[6];
       sprintf(om,"%f",o);

  char tail[17]=" HTTP/1.0\r\n\r\n" ;

  char sbuffer[73];
	 strcat(sbuffer,ur);
	 strcat(sbuffer,temp_h);
	 strcat(sbuffer,temp);
	 strcat(sbuffer,humi_h);
	 strcat(sbuffer,humi);
	 strcat(sbuffer,om_h);
	 strcat(sbuffer,om);
	 strcat(sbuffer,tail);
*/
	 //printf("sbuffer:%s",sbuffer);

  /*char url[]="GET /wordpress/process.php?" ;
  char temp_h[]="temp=" ;
  char temp[]="2" ;
  char humi_h[]="&humi=" ;
  char humi[]="3" ;
  char om_h[]="&om=" ;
  char om[]="3" ;
  char tail[]="HTTP/1.0\r\n\r\n" ;
  char aa[70];
	 aa[0]=url[0] ;
	 aa[27]=temp_h[0];
	 aa[28]=temp[0];
	 aa[33]=humi_h[0];
	 aa[40]=humi[0] ;
	 aa[45]=om_h[0] ;
	 aa[49]=om[0];
	 aa[54]=tail[0] ;
	 
	 
// char cc[]=printf("%s",aa);
//char sbuffer[]={aa[0],aa[1],aa[2]};
//
 char sbuffer[70];
 for (int i=0;i<=70;i++) {

 sbuffer[i]=aa[i];
 
 printf("sbuffer:%s",sbuffer[i]);
 } 
*/
// printf("sbuffer:%s",sbuffer);
// printf("tmp:%s,tmp2:%s",tmp(),tmp3());
 //char sbuffer[] = *tmp;
 // char sbuffer[] = "GET /wordpress/process.php?temp=2&&humi=3&om=4 HTTP/1.0\r\n\r\n";

//char mynumbers3[] = "123, 102, 255";
//char mynumbers1[] = "254"
//char mynumbers4[] = mynumbers3+mynumbers1;


  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\ninput.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / david2012.duckdns.org:80/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / david2012.duckdns.org/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "david2012.duckdns.org/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "david2012.duckdns.org:80/input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "input.php?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: david2012.duckdns.org\r\n\r\n";
  //char sbuffer[] = "http://david2012.duckdns.org/page-input.php?temp=1&humi=1\r\n\r\n";
  //
  //char sbuffer[] = "GET / HTTP/1.1\r\nhttp://david2012.duckdns.org/input/?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / http://david2012.duckdns.org/input/?temp=1&humi=1\r\n\r\n";
  //char sbuffer[] = "GET / http://david2012.duckdns.org/input/?temp=1&humi=1";
  //char sbuffer[] = "GET / HTTP/1.1\r\oHost: david2012.duckdns.org/input.php?temp=10&humi=10\r\n\r\n";
  //char sbuffer[] = "GET / HTTP/1.1\r\nHost: www.daum.net\r\n\r\n";
    nsapi_size_t size = strlen(sbuffer);
    response = 0;
    while(size)
    {
        //response = socket.send(sbuffer+response, size);
        response = socket.send(sbuffer+response, size);
        if (response < 0) {
            printf("Error sending data: %d\n", response);
            socket.close();
            return;
        } else {
            size -= response;
            // Check if entire message was sent or not
            printf("sent %d [%.*s]\n", response, strstr(sbuffer, "\r\n")-sbuffer, sbuffer);
        }
    }

    // Recieve a simple http response and print out the response line
    char rbuffer[64];
    response = socket.recv(rbuffer, sizeof rbuffer);
    if (response < 0) {
        printf("Error receiving data: %d\n", response);
    } else {
        //printf("recv %d [%.*s]\n", response, strstr(rbuffer, "\r\n")-rbuffer, rbuffer);
		
        printf("recv %d [%.*s]\n", response, strstr(rbuffer, "\r\n")-rbuffer, rbuffer);
    }

    // Close the socket to return its memory and bring down the network interface
    socket.close();
}

int main()
{
	uint8_t id;
	float value1, value2;
	char buffer1[32], buffer2[32];
	int32_t axes[3];  
	int count = 0;


//	Enable all sensors 
    hum_temp->enable();
//  press_temp->enable();
//  magnetometer->enable();
//  accelerometer->enable();
    acc_gyro->enable_x();
    acc_gyro->enable_g();

	printf("\r\n--- Starting new run ---\r\n");

    hum_temp->read_id(&id);
    printf("HTS221  humidity & temperature    = 0x%X\r\n", id);
    /*press_temp->read_id(&id);
	printf("LPS22HB  pressure & temperature   = 0x%X\r\n", id);
	magnetometer->read_id(&id);
	printf("LSM303AGR magnetometer            = 0x%X\r\n", id);
	accelerometer->read_id(&id);
	printf("LSM303AGR accelerometer           = 0x%X\r\n", id);
	*/
	acc_gyro->read_id(&id);
	printf("LSM6DSL accelerometer & gyroscope = 0x%X\r\n", id);

// wifi	
	printf("WiFi example\n\n");

    count = scan_demo(&wifi);
    if (count == 0) {
        printf("No WIFI APNs found - can't continue further.\n");
        return -1;
    }

    printf("\nConnecting to %s...\n", MBED_CONF_APP_WIFI_SSID);
    int ret = wifi.connect(MBED_CONF_APP_WIFI_SSID, MBED_CONF_APP_WIFI_PASSWORD, NSAPI_SECURITY_WPA_WPA2);
    if (ret != 0) {
        printf("\nConnection error\n");
        return -1;
    }

    printf("Success\n\n");
    printf("MAC: %s\n", wifi.get_mac_address());
    printf("IP: %s\n", wifi.get_ip_address());
    printf("Netmask: %s\n", wifi.get_netmask());
    printf("Gateway: %s\n", wifi.get_gateway());
    printf("RSSI: %d\n\n", wifi.get_rssi());

	int no=10 ;
    while(no) {
	no=no-1;
	printf("\r\n");
	

	LED=!LED;
	
	hum_temp->get_temperature(&value1);
	hum_temp->get_humidity(&value2);
	printf("HTS221: [temp] %7s C,   [hum] %s%%\r\n", print_double(buffer1, value1), print_double(buffer2, value2));
    
    // char temp[]=print_double(buffer1, value1);
	// char humi[]=print_double(buffer2, value2);
    
	int tmf=atoi(print_double(buffer1, value1));
	int huf=atoi(print_double(buffer2, value2));


	/* press_temp->get_temperature(&value1);
	press_temp->get_pressure(&value2);
	printf("LPS22HB: [temp] %7s C, [press] %s mbar\r\n", print_double(buffer1, value1), print_double(buffer2, value2));

	printf("---\r\n");

    magnetometer->get_m_axes(axes);
    printf("LSM303AGR [mag/mgauss]:  %6ld, %6ld, %6ld\r\n", axes[0], axes[1], axes[2]);
											    
    accelerometer->get_x_axes(axes);
    printf("LSM303AGR [acc/mg]:  %6ld, %6ld, %6ld\r\n", axes[0], axes[1], axes[2]);
	*/
    acc_gyro->get_x_axes(axes);
    printf("LSM6DSL [acc/mg]:      %6ld, %6ld, %6ld\r\n", axes[0], axes[1], axes[2]);

    acc_gyro->get_g_axes(axes);
    printf("LSM6DSL [gyro/mdps]:   %6ld, %6ld, %6ld\r\n", axes[0], axes[1], axes[2]);

	int of=100;
    wait(1.5);
    http_demo(&wifi,tmf,huf,of);



    printf("\nDone\n");
  }


    wifi.disconnect();
}

