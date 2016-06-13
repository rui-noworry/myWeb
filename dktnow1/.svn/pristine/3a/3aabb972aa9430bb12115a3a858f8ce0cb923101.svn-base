package dkt.teacher.view;

import java.io.ByteArrayOutputStream;

import dkt.teacher.base.UserMsg;
import dkt.teacher.listener.HandwritingListener;
import dkt.teacher.R;
import dkt.teacher.listener.IImageColorListener;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Bitmap.Config;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.util.AttributeSet;
import android.util.Log;
import android.view.Display;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewConfiguration;
import android.view.WindowManager;

public class MainView extends View {
	
	private Paint paint;
	private Canvas cacheCanvas;
	private Bitmap cachebBitmap;
	private Path path;
	private int clr_bg, clr_fg;
	private static final float TOUCH_TOLERANCE = 4;
	private boolean iscla = false;
	private boolean isNoWrite = true;
	
	private Canvas canvas;
	IImageColorListener colorListener;
	int paintB = 4;
	int paintA = 20;
	Context context;
	
	private Runnable mLongPressRunnable;  
	HandwritingListener myHandwritingListener;
	
	public HandwritingListener getHandwritingListener() {
		return myHandwritingListener;
	}

	public void setHandwritingListener(HandwritingListener myHandwritingListener) {
		this.myHandwritingListener = myHandwritingListener;
	}
	
	public MainView(Context context, AttributeSet attrs) {
		super(context, attrs);
		this.context = context;
		mLongPressRunnable = new Runnable() {  
             
	        @Override  
	        public void run() {               
	            performLongClick();  
	        }  
	    };
	    
		clr_bg = Color.TRANSPARENT;
		clr_fg = Color.BLACK;
		paint = new Paint();
		
		paint.setAntiAlias(true); // 抗锯齿
		paint.setStrokeWidth(paintB); // 笔的粗细
		paint.setStyle(Paint.Style.STROKE); // 画轮廓
		//paint.setColor(clr_fg); // 笔的颜色
		
		path = new Path();
		// 创建全屏位图
		cachebBitmap = Bitmap.createBitmap(getScreenWidth(context), 
				getScreenHeight(context), Config.ARGB_8888);
		
		cacheCanvas = new Canvas(cachebBitmap);
		cacheCanvas.drawColor(clr_bg);
	}

	public MainView(Context context) {
		super(context);
	}
	
	public void setClr_fg(int clr_fg) {
		this.clr_fg = clr_fg;
	}
	
	public void setPaintB(int paintB) {
		this.paintB = paintB;
		String a = "" + paintB;
		String[] UserValues = { a };
		String[] configParams = { "paintb" };
		UserMsg.setConfigMsg(context, configParams, UserValues);
	}
	
	public void setPaintA(int paintA) {
		this.paintA = paintA;
		String a = "" + paintA;
		String[] UserValues = { a };
		String[] configParams = { "painta" };
		UserMsg.setConfigMsg(context, configParams, UserValues);
	}
	
	@Override
	protected void onDraw(Canvas canvas) {
		this.canvas = canvas;
		canvas.drawColor(clr_bg);

		// 绘制上一次的，否则不连贯
		canvas.drawBitmap(cachebBitmap, 0, 0, null);
		if(iscla) {
			Paint paint1=new Paint();
			paint1.setAlpha(0);  
			 paint1.setColor(context.getResources().getColor(R.color.text_background_color));
			 paint1.setAntiAlias(true);
			 paint1.setStrokeWidth(paintA);    
			 paint1.setDither(true);
			 paint1.setStyle(Paint.Style.STROKE);
			 paint1.setStrokeJoin(Paint.Join.ROUND);  
//			 paint1.setStrokeCap(Paint.Cap.ROUND);
			 PorterDuffXfermode avoid = new PorterDuffXfermode(PorterDuff.Mode.DST_OUT);    
		        paint.setXfermode(avoid);
			canvas.drawPath(path, paint1);	
		}else{
			Paint paint1=new Paint();
			 paint1.setColor(clr_fg);
			 paint1.setAntiAlias(true);
			 paint1.setStrokeWidth(paintB);
			 paint1.setStyle(Paint.Style.STROKE);
			 paint1.setStrokeJoin(Paint.Join.ROUND);
			 paint1.setXfermode(null);
			 canvas.drawPath(path, paint1);	
		}

	}
	 
	public void isClean(boolean is){
		if(is) {
			iscla = true;
		}else{
			iscla = false;
		}
	}
	
	public void isNoWrite(boolean is) {
		
		if(is) {
			isNoWrite = true;
		}else{
			isNoWrite = false;
		}
	}
	/**
	 * 插入图片
	 * */
	public void insertImg(Bitmap bitmap){
		cacheCanvas.drawBitmap(bitmap, 0, 0, null);
	}
	
	/**
	 * 清空画布
	 */
	public void clear() {
		Log.v("zhujj", "清空画布");
		cachebBitmap.recycle();
		cachebBitmap = null;
		paint = new Paint();
		
		paint.setAntiAlias(true); // 抗锯齿
		paint.setStrokeWidth(paintB); // 笔的粗细
		paint.setStyle(Paint.Style.STROKE); // 画轮廓
		//paint.setColor(clr_fg); // 笔的颜色
		
		path = new Path();
		// 创建全屏位图
		cachebBitmap = Bitmap.createBitmap(1280, 
				800, Config.ARGB_8888);
		
		cacheCanvas = new Canvas(cachebBitmap);
		cacheCanvas.drawColor(clr_bg);
		invalidate();
	}
	
	/**
	 * 将画布的内容bitmap转为byte[]返回
	 * 
	 */
	public byte[] getBitmapForByte() {
		
		return Bitmap2Bytes(cachebBitmap);
		
	}
	
	/**
	 * bitmap转byte[]
	 * */
	private byte[] Bitmap2Bytes(Bitmap bm){
		
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		bm.compress(Bitmap.CompressFormat.PNG, 100, baos);
		return baos.toByteArray();
		
	}
	
	
	private float cur_x, cur_y;
	private boolean isMoving;
	@Override
	public boolean onTouchEvent(MotionEvent event) {
		// TODO Auto-generated method stub
		float x = event.getX();
		float y = event.getY();

		switch (event.getAction() & MotionEvent.ACTION_MASK) {
			case MotionEvent.ACTION_DOWN : {
				cur_x = x;
				cur_y = y;

				postDelayed(mLongPressRunnable, ViewConfiguration.getLongPressTimeout());
				myHandwritingListener.showHandwritingView();
				path.moveTo(cur_x, cur_y);
				if(iscla) {	        
			        paint.setStrokeWidth(paintA);
			        paint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.DST_OUT));
					
				}else{
					
					paint.setColor(clr_fg); // 笔的颜色
					paint.setStrokeWidth(paintB); // 笔的粗细
					paint.setXfermode(null);   
				}
				if(isNoWrite) {
					isMoving = true;
				}else{
					isMoving = false;
				}
				
				break;
			}
			case MotionEvent.ACTION_POINTER_DOWN:   
				System.out.println("=======event.getPointerCount()=========="+event.getPointerCount());

				if(event.getPointerCount() == 5) {
					myHandwritingListener.closeActivity();
				}
	            break;   
	            
			case MotionEvent.ACTION_MOVE : {
				if (!isMoving)
					break;
				
				float dx = Math.abs(x - cur_x);
				float dy = Math.abs(cur_y - y);
				
				if (dx >= TOUCH_TOLERANCE || dy >= TOUCH_TOLERANCE) {
					 // 从x1,y1到x2,y2画一条贝塞尔曲线
					path.quadTo(cur_x, cur_y, (x + cur_x) / 2, (y + cur_y) / 2);
					cur_x = x;
					cur_y = y;
					removeCallbacks(mLongPressRunnable);  
				}
				
				break;
			}

			case MotionEvent.ACTION_UP : {
				// 鼠标弹起保存最后状态
				path.lineTo(cur_x, cur_y);
				removeCallbacks(mLongPressRunnable);  
				cacheCanvas.drawPath(path, paint);
				path.reset();
				isMoving = false;
				break;
			}
		}

		// 通知刷新界面
		invalidate();

		return true;
	}
	
	/**
	 * 获取屏幕宽度
	 * @param context
	 * @return
	 */
	public int getScreenWidth(Context context) {
		WindowManager manager = (WindowManager) context
				.getSystemService(Context.WINDOW_SERVICE);
		Display display = manager.getDefaultDisplay();
		return display.getWidth();
	}

	/**
	 * 获取屏幕高度
	 * @param context
	 * @return
	 */
	public int getScreenHeight(Context context) {
		WindowManager manager = (WindowManager) context
				.getSystemService(Context.WINDOW_SERVICE);
		Display display = manager.getDefaultDisplay();
		return display.getHeight();
	}
	
	public IImageColorListener getColorListener() {
		return colorListener;
	}

	public void setColorListener(IImageColorListener colorListener) {
		this.colorListener = colorListener;
	}
}
