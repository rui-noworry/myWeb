package dkt.teacher.view;

import dkt.teacher.listener.HandwritingListener;
import dkt.teacher.model.IsDrawBg;
import dkt.teacher.model.BlackBoardImg;
import dkt.teacher.model.SuData;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.graphics.Rect;
import android.graphics.PorterDuff.Mode;
import android.util.AttributeSet;
import android.util.FloatMath;
import android.view.MotionEvent;
import android.view.ViewConfiguration;
import android.view.animation.TranslateAnimation;
import android.widget.ImageView;

public class MyImageView extends ImageView{
	
	static final int NONE = 0;     
    static final int DRAG = 1;     // 拖动    
    static final int ZOOM = 2;     // 缩放     
    static final int BIGGER = 3;   // 放大      
    static final int SMALLER = 4;  // 缩小 
    static final int ONEZOOM = 5;  // 单指缩放
    private int mode = NONE;       // 当前的事件       
     
    private float beforeLenght;   // 两触点距离      
    private float afterLenght;    // 两触点距离      
    private float scale = 0.03f;  // 缩放的比例 X Y方向都是这个值 越大缩放的越快      
    private SuData note;    
    private int screenW;     
    private int screenH;     
         
    /*处理拖动 变量 */     
    private int start_x;     
    private int start_y;     
    private int stop_x ;     
    private int stop_y ;     
    private Runnable mLongPressRunnable;  
    Bitmap bitmap;
    Bitmap bitmap1;
    Canvas canvas;
    IsDrawBg isDrawBg;
    private TranslateAnimation trans; //处理超出边界的动画   
    HandwritingListener myHandwritingListener;
    boolean isDrawb = true;
	
	public HandwritingListener getHandwritingListener() {
		return myHandwritingListener;
	}

	public void setHandwritingListener(HandwritingListener myHandwritingListener) {
		this.myHandwritingListener = myHandwritingListener;
	}
	public MyImageView(Context context, SuData note, Bitmap bm, IsDrawBg isDrawBg) {
		super(context);
		this.note = note;
		this.bitmap = bm;
		this.isDrawBg = isDrawBg;
//		mLongPressRunnable = new Runnable() {  
//            
//	        @Override  
//	        public void run() {               
//	            performLongClick();  
//	        }  
//	    };
	}
	
	public MyImageView(Context context,AttributeSet attr) {
		super(context, attr);
		
	}
	
	@Override
	public void setImageBitmap(Bitmap bm) {
		// TODO Auto-generated method stub
		super.setImageBitmap(bm);
		this.bitmap1 = bm;
	}
	@Override
	protected void onDraw(Canvas canvas) {
		// TODO Auto-generated method stub
		super.onDraw(canvas);

		this.canvas = canvas;
		if(isDrawBg.isDrawBg()) {
			 Paint paint = new Paint();  
			 canvas.drawBitmap(bitmap, 0, 0, paint);
			
			 Rect rec = canvas.getClipBounds();
			 rec.bottom--;
			 rec.right--;
			 Paint paint1 = new Paint();
			 paint1.setColor(Color.BLUE);
			 paint1.setStyle(Paint.Style.STROKE);

			 canvas.drawRect(rec, paint1);
			 invalidate();
		}

	}
	
	@Override
	protected void onLayout(boolean changed, int left, int top, int right,
			int bottom) {
		// TODO Auto-generated method stub
		super.onLayout(changed, left, top, right, bottom);
	}
	/**
	 * 该构造函数在动态创建时，指定图片的初始高宽
	 * @param context
	 * @param w
	 * @param h
	 */
	public void setMyImageView(int w,int h) {   
	   
	    screenW = w;   
	    screenH = h;   
	}   
	   
	/**  
	 * 就算两点间的距离  
	 */   
	private float spacing(MotionEvent event) {   
	    float x = event.getX(0) - event.getX(1);   
	    float y = event.getY(0) - event.getY(1);   
	    return FloatMath.sqrt(x * x + y * y);   
	}   
	   
	/**  
	 * 处理触碰..  
	 */   
	@Override   
	public boolean onTouchEvent(MotionEvent event)   
	{   
		int x = (int) event.getX();
		int y = (int) event.getY();
	    switch (event.getAction() & MotionEvent.ACTION_MASK) {   
	    case MotionEvent.ACTION_DOWN:   
//	    		postDelayed(mLongPressRunnable, ViewConfiguration.getLongPressTimeout());
	            mode = DRAG;   
	            stop_x = (int) event.getRawX();   
	            stop_y = (int) event.getRawY();   
	            start_x = (int) event.getX();   
	            start_y = stop_y - this.getTop();   
	            System.out.println("===start_x=========="+start_x+"====start_y="+start_y);
	            System.out.println("hei==="+this.getHeight()+"===w===="+this.getWidth());
	            if(start_x + start_y < 60) {
		            myHandwritingListener.closeImageView(this);
	            }else if(start_x > this.getWidth() * 0.8 || start_y > this.getHeight() * 0.7) {
	            	
	            	mode = ONEZOOM;
	            }
	            if(event.getPointerCount()==2)   
	                beforeLenght = spacing(event);   
	            break;   
	    case MotionEvent.ACTION_POINTER_DOWN:   
	            if (spacing(event) > 10f) {   
	                    mode = ZOOM;   
	                    beforeLenght = spacing(event);   
	            }   
	            break;   
	    case MotionEvent.ACTION_UP:   
	        /*判断是否超出范围     并处理*/   
//	    		removeCallbacks(mLongPressRunnable);  
	    	
	            mode = NONE;   
	            break;   
	    case MotionEvent.ACTION_POINTER_UP: 
	            mode = NONE;   
	            break;   
	    case MotionEvent.ACTION_MOVE:   
	            /*处理拖动*/   
//	    		removeCallbacks(mLongPressRunnable); 
	            if (mode == DRAG) {   
	                if(Math.abs(stop_x-start_x-getLeft())<88 && Math.abs(stop_y - start_y-getTop())<85)   
	                {   
	                    this.setPosition(stop_x - start_x, 
	                    		stop_y - start_y, stop_x + this.getWidth() 
	                    		- start_x, stop_y - start_y + this.getHeight());                 
	                    stop_x = (int) event.getRawX();   
	                    stop_y = (int) event.getRawY();   
	                }   
	            	
	            }    
	            /*处理缩放*/   
	            else if (mode == ZOOM) {   
	                if(spacing(event)>10f)   
	                {   
	                    afterLenght = spacing(event);   
	                    float gapLenght = afterLenght - beforeLenght;                        
	                    if(gapLenght == 0) {     
	                       break;   
	                    }   
	                    else if(Math.abs(gapLenght)>5f)   
	                    {   
	                        if(gapLenght>0) {  
	                        	
	                            this.setScale(scale,BIGGER);      
	                        }else {     
	                            this.setScale(scale,SMALLER);      
	                        }                                
	                        beforeLenght = afterLenght;    
	                    }   
	                }   
	            }   
	            else if (mode == ONEZOOM) {
	            	System.out.println("==单指缩放。。。。。。。。。。");
	 
	            	if(x - stop_x > 2) {
	            		setScaleForOneRight(0.009f,BIGGER);
	            	}else if(x - stop_x < -2){
	            		setScaleForOneRight(0.009f,SMALLER);
	            	}
            		if(y - stop_y > 2) {
	            		setScaleForOneBottom(0.018f,BIGGER);
	            	}else if(y - stop_y < -2){
	            		setScaleForOneBottom(0.018f,SMALLER);
	            	}
	            	
	            	stop_x = x;
	            	stop_y = y;
	            }
	            break;   
	    }   

	    return true;       
	} 
	/**  
	 * 实现处理缩放  
	 */   
	private void setScaleForOneBottom(float temp,int flag) {      
		invalidate();   
		
	    if(flag==BIGGER) {      
	        this.setFrame(this.getLeft(),       
                    this.getTop(),       
                    this.getRight(),       
                    this.getBottom()+(int)(temp*this.getHeight())); 
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
                    this.getTop()+(int)(temp*this.getHeight()),       
                    this.getRight()-(int)(temp*this.getWidth()),       
                    this.getBottom()-(int)(temp*this.getHeight()));

	    }else if(flag==SMALLER){      
	        this.setFrame(this.getLeft(),       
	                      this.getTop(),       
	                      this.getRight(),       
	                      this.getBottom()-(int)(temp*this.getHeight()));   
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
	                      this.getTop()+(int)(temp*this.getHeight()),       
	                      this.getRight()-(int)(temp*this.getWidth()),       
	                      this.getBottom()-(int)(temp*this.getHeight()));
	    }    
	    note.setSuX(getLeft());
		note.setSuY(getTop());
		note.setSuHeight(getHeight());
		note.setSuWidth(getWidth());
	}   
	/**  
	 * 实现处理缩放  
	 */   
	private void setScaleForOneRight(float temp,int flag) {      
		invalidate();   
		
	    if(flag==BIGGER) {      
	        this.setFrame(this.getLeft(),       
                    this.getTop(),       
                    this.getRight()+(int)(temp*this.getWidth()),       
                    this.getBottom()); 
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
                    this.getTop()+(int)(temp*this.getHeight()),       
                    this.getRight()-(int)(temp*this.getWidth()),       
                    this.getBottom()-(int)(temp*this.getHeight()));

	    }else if(flag==SMALLER){      
	        this.setFrame(this.getLeft(),       
	                      this.getTop(),       
	                      this.getRight()-(int)(temp*this.getWidth()),       
	                      this.getBottom());   
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
	                      this.getTop()+(int)(temp*this.getHeight()),       
	                      this.getRight()-(int)(temp*this.getWidth()),       
	                      this.getBottom()-(int)(temp*this.getHeight()));
	    }    
	    note.setSuX(getLeft());
		note.setSuY(getTop());
		note.setSuHeight(getHeight());
		note.setSuWidth(getWidth());
	}   

	/**  
	 * 实现处理缩放  
	 */   
	private void setScale(float temp,int flag) {      
		invalidate();   
		
	    if(flag==BIGGER) {      
	        this.setFrame(this.getLeft()-(int)(temp*this.getWidth()),       
                    this.getTop()-(int)(temp*this.getHeight()),       
                    this.getRight()+(int)(temp*this.getWidth()),       
                    this.getBottom()+(int)(temp*this.getHeight())); 
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
                    this.getTop()+(int)(temp*this.getHeight()),       
                    this.getRight()-(int)(temp*this.getWidth()),       
                    this.getBottom()-(int)(temp*this.getHeight()));

	    }else if(flag==SMALLER){      
	        this.setFrame(this.getLeft()+(int)(temp*this.getWidth()),       
	                      this.getTop()+(int)(temp*this.getHeight()),       
	                      this.getRight()-(int)(temp*this.getWidth()),       
	                      this.getBottom()-(int)(temp*this.getHeight()));   
	        this.onLayout(true, this.getLeft()+(int)(temp*this.getWidth()),       
	                      this.getTop()+(int)(temp*this.getHeight()),       
	                      this.getRight()-(int)(temp*this.getWidth()),       
	                      this.getBottom()-(int)(temp*this.getHeight()));
	    }    
	    note.setSuX(getLeft());
		note.setSuY(getTop());
		note.setSuHeight(getHeight());
		note.setSuWidth(getWidth());
	}   
	   
	/**  
	 * 实现处理拖动  
	 */   
	private void setPosition(int left,int top,int right,int bottom) {     
	    this.layout(left,top,right,bottom); 
	    note.setSuX(left);
	    note.setSuY(top);
	    invalidate();
	}   
}
