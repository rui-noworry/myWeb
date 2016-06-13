package dkt.teacher.activity;

import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;

import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.net.GetIp;
import android.app.Activity;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.widget.ImageView;

public class StudentScreenActivity extends Activity{

	private String url;
	private Bitmap myBitmap;
	private ImageView studentImg;
	
	private Handler handler = new Handler(){
	    @Override
	    public void handleMessage(Message msg) {
	        super.handleMessage(msg);
	        Bundle data = msg.getData();
	        String val = data.getString("value");
	        if(val.equals("1") && myBitmap != null) {
	        	studentImg.setImageBitmap(myBitmap);
	        }
	    }
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.studentscreen);//
		
		
		// 返回
		findViewById(R.id.student_screen_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				finish();
			}
		});
		GetIp getip = new GetIp(StudentScreenActivity.this);
		String service_ip = getip.servise_ip;
		url = MyContants.HTTP_PREFIX + service_ip + this.getIntent().getStringExtra("url");
		studentImg = (ImageView) findViewById(R.id.studentscreen_img);
		new Thread(runnable).start();
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
			
			imageUrl = new URL(url);
			
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
}
