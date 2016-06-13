package dkt.student.socket;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.HashMap;

import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.net.UploadHandler;
import dkt.student.net.UploadPicture;
import dkt.student.socket.IConnectionListener;
import dkt.student.socket.IHeader;
import dkt.student.socket.SocketConnection;
import dkt.student.util.Md5Util;
import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.os.IBinder;
import android.os.Looper;
import android.os.Message;
import android.view.View;
import android.widget.Toast;

public class SocketPushService extends Service{
	SocketConnection conn;
	CommandReceiver cmdReceiver;
	String filePath;
	String teacherName;
	
	@Override
	public void onCreate() {
		// TODO Auto-generated method stub
		super.onCreate();
		
		cmdReceiver = new CommandReceiver();
		
		SharedPreferences share = this.getSharedPreferences(
				MyContants.PREFERENCE_NAME, 0);
		String host = share.getString("push_ip", "");        
		System.out.println("start service.....");
		MyApp app = (MyApp)getApplication();
		int userId = app.getUserId();
		filePath = "/sdcard/Dkt/Resource/"+userId+".png";
		
		conn= new SocketConnection(new IConnectionListener(){

				@Override
				public void messageReceived(IHeader header, byte[] body) {
					// TODO Auto-generated method stub
					//System.out.println(header.getResponseCode());
					System.out.println("reciver======"+header.getResponseCode());
					if(202 == header.getResponseCode()) {
						conn.close();
						stopSelf();
					}else if(201 == header.getResponseCode()){
						conn.close();
						stopSelf();
					}else if(505 == header.getResponseCode()){
						conn.close();
						stopSelf();
					}
					if(null!=body){
						System.out.println(new String(body));
						
						try {
							JSONObject jsonObject = new JSONObject(new String(body));
							String type = jsonObject.get("type").toString();
							
							System.out.println("==接收的消息类型==="+type);
							if(type.equals("4")) {
								String content = jsonObject.get("content").toString();
								Intent intent1 = new Intent("exerciseactivity");
				                intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				    			intent1.putExtra("ap_id", content);
				    			startActivity(intent1);
							}else if(type.equals("5")){
								Intent intent1 = new Intent("lockscreen");
				                intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				    			startActivity(intent1);
							}else if(type.equals("6")){
								doCloseLockScreenJob();
							}else if(type.equals("11")) {
								String teacher = jsonObject.get("teacher").toString();
								String filePath = jsonObject.get("content").toString();
								Intent intent1 = new Intent("explain");
				                intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				    			intent1.putExtra("teacher", teacher);
				    			intent1.putExtra("url", filePath);
				    			startActivity(intent1);
							}else if(type.equals("12")){
								doCloseExplainJob();
							}else if(type.equals("13")){
								teacherName = jsonObject.get("teacher").toString();
								MyThread thread = new MyThread();
								thread.start();
							}
						} catch (JSONException e) {
							// TODO Auto-generated catch block
							e.printStackTrace();
						}
					}
					
				}

				@Override
				public void onClose() {
					// TODO Auto-generated method stub
					
				}
   
				@Override
				public void onConnection(SocketConnection conn) {
					// TODO Auto-generated method stub
					
				}}, 15000, app.getAccount(), app.getPassWord(), host, 8889);  
		new Thread(myConnection).start();
		try {
			System.in.read();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
	}
	
	
	
	class MyThread extends Thread {

		@Override
		public void run() {

			try {
				Looper.prepare();  
				Thread.sleep(1000);
				getScreenForShow();
				Looper.loop();  
			} catch (InterruptedException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}

		}
	}
	
	/**
	 * 截屏
	 * */
	private void getScreenForShow() {
		
		MyApp app = (MyApp)getApplication();
		String fname = filePath;
		
		// 如果该图片存在则删除
		File f = new File(fname);
		if (f.exists()) {
			f.delete();
		}
		View v = app.getJiePView();
		
		View view = v.getRootView();
		view.setDrawingCacheEnabled(true); 
        view.buildDrawingCache(); 
        Bitmap bitmap = view.getDrawingCache();
        
        if (bitmap != null) { 

            try { 
            	
            	FileOutputStream out = new FileOutputStream(fname); 
            	bitmap.compress(Bitmap.CompressFormat.PNG, 100, out);  
            	doSaveCoursePackageHttp(fname); 
            } catch (Exception e) { 
            	e.printStackTrace(); 
            } 
            
        }
	}
	
	/**
	 * 用户上传图片
	 */
	private void doSaveCoursePackageHttp(String filePath) {
		MyApp app = (MyApp)getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Auth.picture");
		map.put("skey", skey);
		map.put("args[a_id]", userId);	
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new UploadPicture(temp, new BlackboardUploadHandler(this,
				MyContants.DO_HTTP_ISERT_PICTURE), map, filePath);
	}
	
	/**
	 * 解析图片上传后数据
	 * */
	private void doUploadPictureSucces(String result) {
		JSONObject jo;
		try {
			jo = new JSONObject(result);
			final String fileUrl = jo.get("url").toString();
			
			Runnable access = new Runnable() {
				
				private PushWorker pushWorker;

				public void run() {
					// TODO Auto-generated method stub
					try {
						String receiver = teacherName;
						String body = "{\"type\":\"14\",\"content\":\""+fileUrl+"\"}";
			            System.out.println(receiver+"==111==="+body.length()+"===111=="+body);
			            if(null!=this.pushWorker){
							this.pushWorker.stopWorker();
						}
			            pushWorker = new PushWorker(conn, receiver, body);
			            pushWorker.startWorker();
					} 
					catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
					}
				}
			}; 
        
	        new Thread(access).start();
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	class BlackboardUploadHandler extends UploadHandler {
		String tag;

		public BlackboardUploadHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
			
		}

		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);
			// popupWindowPackageView
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				doUploadPictureSucces((String) msg.obj);
			}
		}

	}
	
	Runnable myConnection = new Runnable() {
		
		public void run() {
			// TODO Auto-generated method stub
			try {
				conn.connection();
			} 
			catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
			}
		}
	}; 
	
	public void doCloseExplainJob(){
        new Thread(){
                public void run(){
      
                    Intent intent = new Intent();//创建Intent对象
                    intent.setAction("zhujj.closeexplain");
                    sendBroadcast(intent);//发送广播                              
                }
                
        }.start();
	} 
	
	public void doCloseLockScreenJob(){
        new Thread(){
                public void run(){
      
                    Intent intent = new Intent();//创建Intent对象
                    intent.setAction("zhujj.closelockscreen");
                    sendBroadcast(intent);//发送广播                              
                }
                
        }.start();
	} 
	
	private class CommandReceiver extends BroadcastReceiver{//继承自BroadcastReceiver的子类
		
		@Override
        public void onReceive(Context context,final Intent intent) {//重写onReceive方法
		
			Runnable access = new Runnable() {
				
				private PushWorker pushWorker;

				public void run() {
					// TODO Auto-generated method stub
					try {
						String receiver = intent.getStringExtra("receiver");//获取Extra信息
						String body = intent.getStringExtra("body");
			            System.out.println(receiver+"==111==="+body.length()+"===111=="+body);
			            if(null!=this.pushWorker){
							this.pushWorker.stopWorker();
						}
			            pushWorker = new PushWorker(conn, receiver, body);
			            pushWorker.startWorker();
					} 
					catch (Exception e) {
						// TODO: handle exception
						e.printStackTrace();
					}
				}
			}; 
        
	        new Thread(access).start();
		}    
        
    	
	}

	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		// TODO Auto-generated method stub
		IntentFilter filter = new IntentFilter();//创建IntentFilter对象
        filter.addAction("zhujj.MyPushService");
        registerReceiver(cmdReceiver, filter);
		return super.onStartCommand(intent, flags, startId);
	}
	
	@Override
	public void onDestroy() {
		// TODO Auto-generated method stub
		System.out.println("stop the service...");
		conn.close();
		super.onDestroy();
	}
	
	@Override
	public void onStart(Intent intent, int startId) {
		// TODO Auto-generated method stub
		super.onStart(intent, startId);
	}
	
	@Override
	public boolean onUnbind(Intent intent) {
		// TODO Auto-generated method stub
		return true;
	}
	
	@Override
	public IBinder onBind(Intent intent) {
		// TODO Auto-generated method stub
		return null;
	}

}
