package dkt.teacher.socket;

import java.io.IOException;

import org.json.JSONException;
import org.json.JSONObject;

import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.socket.IConnectionListener;
import dkt.teacher.socket.IHeader;
import dkt.teacher.socket.SocketConnection;

import android.app.Service;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.os.IBinder;
import android.os.StrictMode;

public class SocketPushService extends Service{
	SocketConnection conn;
	CommandReceiver cmdReceiver;
	@Override
	public void onCreate() {
		// TODO Auto-generated method stub
		super.onCreate();
		cmdReceiver = new CommandReceiver();
		
		SharedPreferences share = this.getSharedPreferences(
				MyContants.PREFERENCE_NAME, 0);
		String host = share.getString("push_ip", "");
		MyApp app = (MyApp)getApplication();
		conn= new SocketConnection(new IConnectionListener(){

				@Override
				public void messageReceived(IHeader header, byte[] body) {
					// TODO Auto-generated method stub
					//System.out.println(header.getResponseCode());
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
							
							System.out.println("====="+type);
							if(type.equals("1") || type.equals("2") || type.equals("3")) {
								String content = jsonObject.get("content").toString();
								JSONObject jsonObject1 = new JSONObject(content);
								String x = jsonObject1.get("start_x").toString();
								String y = jsonObject1.get("start_y").toString();
								String pen_type = jsonObject1.get("type").toString();
								System.out.println(x+"====="+y);
								doPushXYJob(type, x, y, pen_type);
							}else if(type.equals("14")){
								String content = jsonObject.get("content").toString();
								System.out.println(content);
								Intent intent1 = new Intent("studentscreen");
				                intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
				                intent1.putExtra("url", content);
				    			startActivity(intent1);
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
	
	public void doPushXYJob(final String type, final String x, final String y, final String pen_type){
        new Thread(){
                public void run(){
      
                    Intent intent = new Intent();//创建Intent对象
                    intent.putExtra("type", type);
                    intent.putExtra("x", x);
                    intent.putExtra("y", y);
                    intent.putExtra("pen_type", pen_type);
                    intent.setAction("zhujj.explaindrow");
                    sendBroadcast(intent);//发送广播                              
                }
                
        }.start();
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
		this.unregisterReceiver(cmdReceiver);
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
