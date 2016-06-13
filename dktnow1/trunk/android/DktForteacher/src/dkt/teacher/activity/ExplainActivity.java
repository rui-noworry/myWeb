package dkt.teacher.activity;

import dkt.teacher.MyApp;
import dkt.teacher.R;
import dkt.teacher.view.ExplainView;
import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;

public class ExplainActivity  extends Activity{

	private String student;
	private DataReceiver dataReceiver;
	private ExplainView myView;
	
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
		
		MyApp app = (MyApp) getApplication();
		student = app.getStudentName();
		int userId = app.getUserId();
		
		myView = (ExplainView) findViewById(R.id.explain_canvas_view);
		String fname = "/sdcard/Dkt/Resource/"+userId+".png";
		Bitmap bmp = BitmapFactory.decodeFile(fname);
		myView.insertImg(bmp);
		
		findViewById(R.id.explain_close_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					Intent myIntent = new Intent();//创建Intent对象
			        myIntent.setAction("zhujj.MyPushService");
			        myIntent.putExtra("receiver", student);
			        myIntent.putExtra("body", "{\"type\":\"12\",\"content\":\"\"}");
			        getApplication().sendBroadcast(myIntent);//发送广播

					finish();
				}
		});
	}
	
	
	private class DataReceiver extends BroadcastReceiver{  // 继承自BroadcastReceiver的子类

		@Override
		public void onReceive(Context context, Intent intent) {
			// TODO Auto-generated method stub
			String type = intent.getStringExtra("type");
			float x = Float.parseFloat(intent.getStringExtra("x"));
			float y = Float.parseFloat(intent.getStringExtra("y"));
			String pen_type = intent.getStringExtra("pen_type");
			if(pen_type.equals("1")) {
				myView.isClean(false);
			}else if(pen_type.equals("2")) {
				myView.isClean(true);
			}
			if(type.equals("1")) {
				myView.beginDrow(x, y);
			}else if(type.equals("2")) {
				myView.moveDrow(x, y);
			}else if(type.equals("3")) {
				myView.endDrow();
			}
			
			System.out.println(x+"====x==="+pen_type+"====y======"+y);
		}                
    }
	
	@Override
    protected void onStart() {      // 重写onStart方法
        dataReceiver = new DataReceiver();
        IntentFilter filter = new IntentFilter();  // 创建IntentFilter对象
        filter.addAction("zhujj.explaindrow");
        registerReceiver(dataReceiver, filter);  // 注册Broadcast Receiver
        super.onStart();
    }
    @Override
    protected void onStop() {  // 重写onStop方法
        unregisterReceiver(dataReceiver);  // 取消注册Broadcast Receiver
        super.onStop();
    }
}
