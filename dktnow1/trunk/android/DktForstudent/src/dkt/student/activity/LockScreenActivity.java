package dkt.student.activity;

import dkt.student.MyApp;
import dkt.student.R;
import android.app.Activity;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.view.Window;

public class LockScreenActivity extends Activity{
	
	DataReceiver dataReceiver;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.lockscreen);
		
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.lockscreen_img));
		
	}
	
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.lockscreen_img));
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
        filter.addAction("zhujj.closelockscreen");
        registerReceiver(dataReceiver, filter);  // 注册Broadcast Receiver
        super.onStart();
    }
    @Override
    protected void onStop() {  // 重写onStop方法
        unregisterReceiver(dataReceiver);  // 取消注册Broadcast Receiver
        super.onStop();
    }
}
