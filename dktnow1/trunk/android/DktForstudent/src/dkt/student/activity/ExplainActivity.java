package dkt.student.activity;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.net.GetIp;
import dkt.student.view.ExplainView;
import dkt.student.view.MainView;
import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;

public class ExplainActivity extends Activity{

	private String teacher;
	private String url;
	private ExplainView myView;
	private DataReceiver dataReceiver;
	private Bitmap myBitmap;
	
	private Handler handler = new Handler(){
	    @Override
	    public void handleMessage(Message msg) {
	        super.handleMessage(msg);
	        Bundle data = msg.getData();
	        String val = data.getString("value");
	        if(val.equals("1") && myBitmap != null) {
	        	myView.insertImg(myBitmap);
	        }
	    }
	};

	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
		setContentView(R.layout.explain);
		
		teacher = this.getIntent().getStringExtra("teacher");
		url = this.getIntent().getStringExtra("url");
		new Thread(runnable).start();

		myView = (ExplainView) findViewById(R.id.explain_canvas_view);
		myView.setTeacher(teacher);
		
		findViewById(R.id.ex_pen).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(false);
			}
		});
		
		findViewById(R.id.ex_ea).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(true);
			}
		});
	}
	
	Runnable runnable = new Runnable(){
	    @Override
	    public void run() {
	        
	    	getImage(url);
	    }
	};

	
	/**
	 * 获取网络图片
	 * */
	private void getImage(String url) {
		URL imageUrl = null;
		
		try {
			GetIp getip = new GetIp(ExplainActivity.this);
			String service_ip = getip.servise_ip;
			imageUrl = new URL(MyContants.HTTP_PREFIX + service_ip + url);
			
			HttpURLConnection conn = (HttpURLConnection) imageUrl
					.openConnection();
			conn.connect();
			InputStream is = conn.getInputStream();
			myBitmap = BitmapFactory.decodeStream(is);
			
			is.close();
				   
			Message msg = new Message();
	        Bundle data = new Bundle();
	        data.putString("value","1");
	        msg.setData(data);
	        handler.sendMessage(msg);
	        
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
		}
	}
	
	
	private class DataReceiver extends BroadcastReceiver{  // 继承自BroadcastReceiver的子类

		@Override
		public void onReceive(Context context, Intent intent) {
			// TODO Auto-generated method stub
			finish();
		}                
    }
	
	@Override
    protected void onStart() {      // 重写onStart方法
        dataReceiver = new DataReceiver();
        IntentFilter filter = new IntentFilter();  // 创建IntentFilter对象
        filter.addAction("zhujj.closeexplain");
        registerReceiver(dataReceiver, filter);  // 注册Broadcast Receiver
        super.onStart();
    }
    @Override
    protected void onStop() {  // 重写onStop方法
        unregisterReceiver(dataReceiver);  // 取消注册Broadcast Receiver
        super.onStop();
    }
}
