package dkt.teacher.net;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.SocketTimeoutException;
import java.net.URL;

import org.apache.http.HttpResponse;
import org.apache.http.HttpStatus;
import org.apache.http.ParseException;
import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.BasicHttpParams;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.util.EntityUtils;

import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.net.GetIp;



import android.content.Context;
import android.os.Message;

/**
 * 用户http请求
 * 用户http请求 java有两种简单地实现方式：appache http和java http
 * 这里用appache http get
 * 
 * @author Administrator
 * 
 */
public class HttpApacheGetThread {
	private String str;
	private int errorCode;
	private HttpHandler handler;
	private String[] params;
	private String[] values;
	private MyThread thread;
	Context context;

	/**
	 * 构造函数
	 * 
	 * @param str
	 *            url(不含http://127.0.0.1)
	 * @param handler
	 *            (处理请求的函数）
	 * @param params
	 *            (请求参数名称)
	 * @param values
	 *            (请求参数的值)
	 */
	public HttpApacheGetThread(Context context, String str, HttpHandler handler, String[] params,
			String[] values) {
		this.str = str;
		this.handler = handler;
		this.params = params;
		this.values = values;
		this.context = context;
		doThread();
	}

	/**
	 * 异步线程处理http 请求
	 */
	public void doThread() {
		thread = new MyThread();
		thread.start();

	}

	class MyThread extends Thread {

		@Override
		public void run() {
			MyApp app = (MyApp) context.getApplicationContext();

//			while(app.isChangeOnline()) {  //循环处
				String resultData = doHttpGet(str);

				Message msg = handler.obtainMessage();
				msg.what = errorCode;
				msg.obj = resultData;
				handler.sendMessage(msg);
				try {
					Thread.sleep(6000);
				} catch (InterruptedException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
//			}
			
		}

	}

	/**
	 * http url和参数组合
	 * 
	 * @param str
	 * @return
	 */
	private String installUrl(String str) {
		int count = params.length;
		StringBuffer sb = new StringBuffer();
		for (int i = 0; i < count; i++) {
			if (i == 0) {
				sb.append("?" + params[i] + "=" + values[i]);
			} else {
				sb.append("&" + params[i] + "=" + values[i]);
			}

		}
		return str + sb.toString();

	}

	/**
	 * http 请求内容
	 * 
	 * @param str
	 * @return
	 */
	private String doHttpGet(String str) {
		String resultData = "";
		GetIp getip = new GetIp(handler.context);
		String service_ip = getip.push_ip;
		if(service_ip.equals("")){
			errorCode = MyContants.HTTP_URL_NULL_WRONG;
			return resultData;
		}else{
			str = MyContants.HTTP_PREFIX +service_ip+str;
		}
		
		str = installUrl(str);
		// set timeout params
		BasicHttpParams httpParameters = new BasicHttpParams();
		HttpConnectionParams.setConnectionTimeout(httpParameters, 30000);
		HttpConnectionParams.setSoTimeout(httpParameters, 30000);

		HttpGet request = new HttpGet(str);
		HttpClient client = new DefaultHttpClient(httpParameters);
		HttpResponse httpResponse;
//		request.addHeader("ilc-client-version", "201207271933");   

		try {
			httpResponse = client.execute(request);
			
			if (httpResponse.getStatusLine().getStatusCode() == HttpStatus.SC_OK) {
				resultData = EntityUtils.toString(httpResponse.getEntity());
				errorCode = HttpStatus.SC_OK;
				if (resultData == null || resultData.equals("")) {
					errorCode = MyContants.HTTP_NULL_WRONG;
					return resultData;
				}
				return resultData;
			} else {
				// test
				errorCode = httpResponse.getStatusLine().getStatusCode();
				return resultData;
			}
		} catch (ClientProtocolException e) {
			e.printStackTrace();
			errorCode = MyContants.HTTP_CONNECT_TIMOUT_WRONG;
			return resultData;
		} catch (ParseException e) {
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
