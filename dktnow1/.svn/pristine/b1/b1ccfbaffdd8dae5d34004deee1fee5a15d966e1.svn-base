package dkt.teacher.net;

import java.io.DataOutputStream;
import java.io.File;
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
import android.os.Message;
import dkt.teacher.MyContants;
import dkt.teacher.database.MyHomeWorkServer;
import dkt.teacher.model.Homework;

public class UploadHomework {

	private HashMap<String,Object> map;
	private MyThread thread;
	private  UploadHandler handler;
	private String strurl;
	private List<HashMap<String, Object>> homeWorkList;
	private String student_id;
	/**
	 * 构造函数
	 * @param strurl 用户请求url 如 /server/upload (不含http://127.0.0.1)
	 * @param handler
	 * @param params 请求参数名 
	 * @param values 请求参数值
	 * @param dbname 上传文件名 如 12345.db
	 */
	public UploadHomework(String strurl, UploadHandler handler, HashMap<String,Object> map,
			List<HashMap<String, Object>> homeWorkList, String student_id){
		this.map = map;
		this.handler = handler;
		this.strurl = strurl;
		this.homeWorkList = homeWorkList;
		this.student_id = student_id;
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

			// 得到参数
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
			
			for(int j=0;j<homeWorkList.size();j++) {
				String homeworkDbName = "homework" + student_id + homeWorkList.get(j).get("to_id") + ".db";
				File f = new File(MyContants.HOMEWORK_PATH + "/" + homeworkDbName);
				if (f.exists()) {
					MyHomeWorkServer myHomeWorkServer = new MyHomeWorkServer(homeworkDbName);
					List<Homework> myHomeworks = myHomeWorkServer.getAllAnswer();
					System.out.println("myHomeworks.size()=========="+myHomeworks.size());
					for(int k=0;k<myHomeworks.size();k++) {
						if(null != myHomeworks.get(k).getToTeacherBitmap()) {
							dos.writeBytes(twoHyphens + BOUNDARY + end);
							dos.writeBytes("Content-Disposition: form-data; name=\"picture_answer[]\"; filename=\""
									+ myHomeworks.get(k).getToId() + "-" + (k+1) + ".png"
									+ "\""
									+ end);
							dos.writeBytes(end);
							dos.write(myHomeworks.get(k).getToTeacherBitmap());
							dos.writeBytes(end);
						}
					}
				}else{
					System.out.println("=========不是主观题========="+homeworkDbName);

				}
			}

			dos.writeBytes(twoHyphens + BOUNDARY + end);
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
			sendMsg(HttpStatus.SC_OK, b.toString());
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
