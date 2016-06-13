package dkt.teacher.activity;

import java.io.File;
import java.io.FileNotFoundException;

import dkt.teacher.R;
import dkt.teacher.view.DragImageView;
import android.app.Activity;
import android.content.ContentResolver;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Rect;
import android.net.Uri;
import android.os.Bundle;
import android.view.ViewTreeObserver;
import android.view.Window;
import android.view.WindowManager;
import android.view.ViewTreeObserver.OnGlobalLayoutListener;

public class ShowImageActivity extends Activity{

	private Context context;
	private DragImageView dragImageView;
	private int window_width, window_height;
	private ViewTreeObserver viewTreeObserver;
	private int state_height;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.showiamge);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}

	private void initView() {
		// TODO Auto-generated method stub
		context = ShowImageActivity.this;
		Intent intent = this.getIntent();
		String filePath = intent.getStringExtra("path");
		
		WindowManager manager = getWindowManager();
		window_width = manager.getDefaultDisplay().getWidth();
		window_height = manager.getDefaultDisplay().getHeight();

		dragImageView = (DragImageView) findViewById(R.id.div_main);
		
		Uri uri = Uri.fromFile(new File(filePath));
		ContentResolver cr = this.getContentResolver();
		try {
			Bitmap bitmap = BitmapFactory.decodeStream(cr.openInputStream(uri));
			dragImageView.setImageBitmap(bitmap);
			dragImageView.setmActivity(this);
			viewTreeObserver = dragImageView.getViewTreeObserver();
			viewTreeObserver.addOnGlobalLayoutListener(new OnGlobalLayoutListener() {

				@Override
				public void onGlobalLayout() {
					if (state_height == 0) {

						Rect frame = new Rect();
						getWindow().getDecorView()
								.getWindowVisibleDisplayFrame(frame);
						state_height = frame.top;
						dragImageView.setScreen_H(window_height-state_height);
						dragImageView.setScreen_W(window_width);
					}

				}
			});
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}  
	}

	private void addFun() {
		// TODO Auto-generated method stub
		
	}
}
