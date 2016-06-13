package dkt.student.net;

import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;

import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.NameValuePair;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.protocol.HTTP;
import org.apache.http.util.EntityUtils;

import dkt.student.MyContants;
import android.os.Message;

/**
 * 用户http请求 用户http请求 java有两种简单地实现方式：appache http和java http 这里用appache http get
 * 
 * @author Administrator
 * 
 */
public class HttpApacheMapPostThread {
	private String str;
	private int errorCode;
	private HttpHandler handler;
	private HashMap<String, Object> map;
	private MyThread thread;

	/**
	 * 构造函数
	 * 
	 * @param str
	 *            url(不含http://127.0.0.1)
	 * @param handler
	 *            (处理请求的函数）
	 * @param map
	 *            (请求参数名称)
	 */
	public HttpApacheMapPostThread(String str, HttpHandler handler,
			HashMap<String, Object> map) {
		this.str = str;
		this.handler = handler;
		this.map = map;
		doThread();

	}

	public void doThread() {
		thread = new MyThread();
		thread.start();

	}

	class MyThread extends Thread {

		@Override
		public void run() {
			String resultData = doHttpPost(str);

			Message msg = handler.obtainMessage();
			msg.what = errorCode;
			msg.obj = resultData;
			handler.sendMessage(msg);

		}

	}

	private String doHttpPost(String str) {
		String resultData = "";
		GetIp getip = new GetIp(handler.context);
		String service_ip = getip.servise_ip;
		// String service_ip ="192.168.7.47";
		if (service_ip.equals("")) {
			errorCode = MyContants.HTTP_URL_NULL_WRONG;
			return resultData;
		} else {
			str = MyContants.HTTP_PREFIX + service_ip + str;
		}
		// set http timeout
		BasicHttpParams httpParameters = new BasicHttpParams();
		HttpConnectionParams.setConnectionTimeout(httpParameters, 30000);
		HttpConnectionParams.setSoTimeout(httpParameters, 30000);
		DefaultHttpClient httpClient = new DefaultHttpClient(httpParameters);

		HttpPost httpRequest = new HttpPost(str);
		httpRequest.addHeader("ilc-client-version", "201207271933");

		// 得到参数   
		Iterator it = map.entrySet().iterator();
		List<NameValuePair> loginParams = new ArrayList<NameValuePair>();
		// test
		StringBuffer sb = new StringBuffer();
		int index = 0;
		
		while (it.hasNext()) {
			Map.Entry m = (Entry) it.next();
			loginParams.add(new BasicNameValuePair(m.getKey() + "", m
					.getValue() + ""));
			if (index != 0) {
				sb.append("&");
			}
			sb.append(m.getKey()+"="+m.getValue());
			index++;

		}
		//test
		System.out.println("_______参数开始_____________");
    	System.out.println(sb.toString());
    	System.out.println("________参数结束____________");

		try {        
			httpRequest.setEntity(new UrlEncodedFormEntity(loginParams,
					HTTP.UTF_8));

			HttpResponse httpResponse = httpClient.execute(httpRequest);
			
			if (httpResponse.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
				resultData = EntityUtils.toString(httpResponse.getEntity());
				errorCode = HttpStatus.SC_OK;
				if (resultData == null ||resultData.equals("null")|| resultData.equals("")) {
					errorCode = MyContants.HTTP_NULL_WRONG;
					return resultData;
				}
				System.out.println("======================="+resultData);
				return resultData;
			} else {
				errorCode = httpResponse.getStatusLine().getStatusCode();
				return resultData;
			}
		} catch (UnsupportedEncodingException e) {
			e.printStackTrace();
			errorCode = MyContants.HTTP_CONNECT_TIMOUT_WRONG;
			return resultData;
		} catch (ClientProtocolException e) {
			e.printStackTrace();
			errorCode = MyContants.HTTP_CONNECT_TIMOUT_WRONG;
			return resultData;
		} catch (IOException e) {
			e.printStackTrace();
			errorCode = MyContants.HTTP_OTHRE_WRONG;
			return resultData;
		}

	}
}
