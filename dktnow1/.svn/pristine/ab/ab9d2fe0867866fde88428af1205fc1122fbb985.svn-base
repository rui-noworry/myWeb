package dkt.teacher.net;

import java.io.DataOutputStream;
import java.io.FileInputStream;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.Map.Entry;
import org.apache.http.HttpStatus;
import dkt.teacher.MyContants;
import android.os.Message;
/**
 * 用户上传文件的功能类
 * @author Administrator
 *
 */
public class UploadFile {
	private HashMap<String,Object> map;
	private String dbname;
	private MyThread thread;
	private  UploadHandler handler;
	private String strurl;
	/**
	 * 构造函数
	 * @param strurl 用户请求url 如 /server/upload (不含http://127.0.0.1)
	 * @param handler
	 * @param params 请求参数名 
	 * @param values 请求参数值
	 * @param dbname 上传文件名 如 12345.db
	 */
	public UploadFile(String strurl, UploadHandler handler, HashMap<String,Object> map,String dbname){
		this.map = map;
		this.dbname = dbname;
		this.handler = handler;
		this.strurl = strurl;
		doThread();
		
		
	}
	
	private void doThread() {
		thread = new MyThread();
		thread.start();

	}

	private void uploadFile() {
		GetIp getip = new GetIp(handler.context);
		String service_ip = getip.servise_ip;
		if(service_ip.equals("")){
			sendMsg(MyContants.HTTP_URL_NULL_WRONG, "");
			return;
		}
		final String BOUNDARY = "---------------------------7da2137580612"; // 数据分隔线
		String end = "\r\n";
		String twoHyphens = "--";

		String srcPath = "/sdcard/Dkt/" + dbname;
		System.out.println("======"+srcPath);
		try {
			URL url = new URL(MyContants.HTTP_PREFIX+ service_ip
					+ strurl);
			HttpURLConnection con = (HttpURLConnection) url.openConnection();
			con.setDoInput(true);
			con.setDoOutput(true);
			con.setUseCaches(false);
			con.setRequestMethod("POST");
			con.setRequestProperty("Connection", "Keep-Alive");
			con.setRequestProperty("Charset", "UTF-8");
			con.setRequestProperty("Content-Type",
					"multipart/form-data;boundary=" + BOUNDARY);

			DataOutputStream dos = new DataOutputStream(con.getOutputStream());

			//得到参数
			Iterator it = map.entrySet().iterator();
			List<String> list= new ArrayList<String>();
			while(it.hasNext()){
				Map.Entry m = (Entry) it.next();
				System.out.println("map="+m.getKey()+":"+m.getValue());
				list.add(m.getKey()+"");
			}
			int count = list.size();
			//取值
			String[] params = new String[count];
			list.toArray(params);
			//添加参数
			for (int i = 0; i < params.length; i++) {
				String key = params[i];
				dos.writeBytes(twoHyphens + BOUNDARY + end);
				dos.writeBytes("Content-Disposition: form-data;name="
						+ key + end + end + map.get(key));
				dos.writeBytes(end);
			}
			

			//update url
			dos.writeBytes(twoHyphens + BOUNDARY + end);
			dos.writeBytes("Content-Disposition: form-data; name=\"cpa_file\"; filename=\""
					+ srcPath.substring(srcPath.lastIndexOf("/") + 1)
					+ "\""
					+ end);
			dos.writeBytes(end);

			//uploading
			FileInputStream fstream = new FileInputStream(srcPath);
			int bufferSize = 1024;
			byte[] buffer = new byte[bufferSize];
			int length = -1;
			while ((length = fstream.read(buffer)) != -1) {
				dos.write(buffer, 0, length);
//				sendMsg(MyContants.HTTP_UOLOAD, "");
			}
			dos.writeBytes(end);
			dos.writeBytes(twoHyphens + BOUNDARY + end);
			fstream.close();
			dos.flush();
			InputStream is = con.getInputStream();
			int ch;
			StringBuffer b = new StringBuffer();
			while ((ch = is.read()) != -1) {
				b.append((char) ch);
			}
			System.out.println("==================================="+b);
			
			dos.close();
			is.close();
			sendMsg(HttpStatus.SC_OK, "");
		} catch (Exception e) {
			sendMsg(MyContants.HTTP_UOLOAD_FAIL, "");
			System.out.println(e);
		}
	}

	class MyThread extends Thread {

		@Override
		public void run() {
			uploadFile();
		}

	}
	
	private void sendMsg(int errorCode,String str){

		Message msg = handler.obtainMessage();
		msg.what = errorCode;
		msg.obj = str;
		handler.sendMessage(msg);
		
	}
}
