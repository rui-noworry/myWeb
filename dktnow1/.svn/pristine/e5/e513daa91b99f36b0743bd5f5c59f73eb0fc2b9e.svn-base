package dkt.student.net;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Message;
import dkt.student.MyContants;
import dkt.student.database.MyHomeWorkServer;
import dkt.student.model.Homework;
import dkt.student.net.DownLoadFileModefy.MyThread;

public class DownLoadHomeworkP {

	private MyThread thread;
	private  DownLoadHandler handler;
	private String strurl;
	public boolean interceptFlag = false;
	private int i;
	private String toid;
	private String homeworkDbName;
	
	/**
	 * 构造函数
	 * @param strurl 用户请求url 如 /server/download(不含http://127.0.0.1)
	 * @param handler
	 * @param params 请求参数名 
	 * @param values 请求参数值
	 * @param dbname 上传文件名 如 12345.db
	 */
	public DownLoadHomeworkP(String toid, String strurl, DownLoadHandler handler, int num, String homeworkDbName){
		
		this.handler = handler;
		this.strurl = strurl;
		this.i = num;
		this.toid = toid;
		this.homeworkDbName = homeworkDbName;
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
			downFile();
		}

	}
	
	private void downFile(){
		
		GetIp getip = new GetIp(handler.context);
		String service_ip = getip.servise_ip;
		if(service_ip.equals("")){
			sendMsg(MyContants.HTTP_URL_NULL_WRONG, 0);
			return;
		}
		System.out.println(i + "==in=="+MyContants.HTTP_PREFIX + service_ip + strurl);
		InputStream is = null;
		try {
			URL url = new URL(MyContants.HTTP_PREFIX + service_ip + strurl);

			HttpURLConnection conn = (HttpURLConnection) url
					.openConnection();
			conn.connect();
			int length = conn.getContentLength();
			is = conn.getInputStream();;

			int count = 0;
			byte buf[] = new byte[1024];
			Bitmap bitmap = BitmapFactory.decodeStream(is);
			MyHomeWorkServer imgHomeWorkServer = new MyHomeWorkServer(homeworkDbName);
			if(imgHomeWorkServer.searchSubjectiveForPage(toid, i) != null){
				
				Homework mHomework = new Homework();
				mHomework.setSubId(""+(i+1));
				mHomework.setToId(toid);
				mHomework.setToAnswerBitmap(Bitmap2Bytes(bitmap));
				imgHomeWorkServer.updateSubjective(mHomework);
				System.out.println(i + "==up=="+strurl);
				
			}else{
				Homework mHomework = new Homework();
				mHomework.setSubId(""+(i+1));
				mHomework.setToId(toid);
				mHomework.setToAnswerBitmap(Bitmap2Bytes(bitmap));
				imgHomeWorkServer.insertSubjective(mHomework);
				System.out.println(i + "==in=="+strurl);
			}
//			do {
//				int numread = is.read(buf);
//				count += numread;
//				int  progress = (int) (((float) count / length) * 100) ;
//				// 更新进度
//				sendMsg(MyContants.HTTP_DOWNLOAD,progress);
//				if (numread <= 0) {
//					// 下载完成通知安装
//					sendMsg(MyContants.HTTP_DOWNLOAD_SUCESS,i);
//					
//					break;
//				}
//			} while (!interceptFlag);// 点击取消就停止下载.

			is.close();
		} catch (MalformedURLException e) {
			System.out.println("dbFile======2=============");
			e.printStackTrace();
			sendMsg(MyContants.HTTP_DOWNLOAD_FAIL,0);
		} catch (IOException e) {
			System.out.println("dbFile======3=============");
			e.printStackTrace();
			sendMsg(MyContants.HTTP_DOWNLOAD_FAIL,0);
		} finally {
			
			if(null != is) {
				try {
					is.close();
				} catch (IOException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		}
	}
	
	/**
	 * bitmap转byte[]
	 * */
	private byte[] Bitmap2Bytes(Bitmap bm){
		
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		bm.compress(Bitmap.CompressFormat.PNG, 100, baos);
		return baos.toByteArray();
		
	}
	
	private void sendMsg(int errorCode,int str){

		Message msg = handler.obtainMessage();
		msg.what = errorCode;
		msg.obj = str;
		handler.sendMessage(msg);
		
	}
	

}
