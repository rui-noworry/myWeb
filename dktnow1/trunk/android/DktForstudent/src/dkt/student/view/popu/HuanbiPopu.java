package dkt.student.view.popu;

import dkt.student.R;
import dkt.student.base.UserMsg;
import dkt.student.util.StringUtil;
import dkt.student.view.MainView;
import android.content.Context;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.drawable.BitmapDrawable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.ImageView;
import android.widget.PopupWindow;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class HuanbiPopu{
	private PopupWindow popup;
	private Context context;
	private View view;
	TextView  text ;
	MainView myView;
	int paintB = 4;
	int color;
	public HuanbiPopu(Context context, View view, MainView myView) {
		this.context = context;
		this.view = view;
		this.myView = myView;
	}

	public void clearPopu() {
		popup.dismiss();
	}

	public void showPopu() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.z_popu_huanbi, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setOutsideTouchable(true);
		popup.showAsDropDown(view, 100, -200);
		
		
		final ImageView image = (ImageView) view1.findViewById(R.id.eraser_width_image);

		// 橡皮进度条
		SeekBar seekBar = (SeekBar) view1
				.findViewById(R.id.eraser_width_progress);
		String pait = UserMsg.getConfigMsg(context, "paintb");

		if (!pait.equals("")) {
			int mypro = Integer.parseInt(pait) * 4;
			seekBar.setProgress(mypro);
			scaleImageMiddleView(image,(float)((float)mypro)/100);
		}
		
		
		seekBar.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
			//结束拖动
			public void onStopTrackingTouch(SeekBar seekBar) {
				
			}
			//开始拖动
			public void onStartTrackingTouch(SeekBar seekBar) {
				
			}
			//拖动中
			public void onProgressChanged(SeekBar seekBar, int progress,
					boolean fromUser) {
			    if(fromUser){
			    	scaleImageMiddleView(image,(float)((float)progress)/100);
			    	
			    	if((progress/4) < 4) {
			    		paintB = 4;			    		
			    	}else{
			    		paintB = progress/4;
			    	}
			    	myView.setPaintB(paintB);
			    }
				
			}
		});
		
		
		view1.findViewById(R.id.black_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.black);
				myView.setClr_fg(Color.BLACK);
			}
		});
		view1.findViewById(R.id.red_btn).setOnClickListener(new OnClickListener() {
			
			@Override 
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.red);
				myView.setClr_fg(color);
			}
		});
		view1.findViewById(R.id.blue_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.blue);
				myView.setClr_fg(color);			
			}
		});
		view1.findViewById(R.id.green_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.green);
				myView.setClr_fg(color);			
			}
		});
		
		view1.findViewById(R.id.yellow_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.yellow);
				myView.setClr_fg(color);		
			}
		});
		view1.findViewById(R.id.gray_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.gray);
				myView.setClr_fg(color);			
			}
		});
		view1.findViewById(R.id.orange_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.orange);
				myView.setClr_fg(color);		
			}
		});
		view1.findViewById(R.id.purple_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				color = context.getResources().getColor(R.color.purple);
				myView.setClr_fg(color);
			}
		});
	}

	private void scaleImageMiddleView(ImageView imageView,float scale ){
		Bitmap bp = BitmapFactory.decodeResource(context.getResources(), R.drawable.eraser_circle_bg);
		int width = bp.getWidth();
		int height = bp.getHeight();
		Matrix matrix = new Matrix();
		if(scale < 0.1f){
			scale = 0.1f;
		}
		matrix.postScale(scale, scale);
		bp = Bitmap.createBitmap(bp,0,0,width,height,matrix,true);
		//将上面创建的Bitmap转换成Drawable对象，使得其可以使用在ImageView, ImageButton中
        BitmapDrawable bmd = new BitmapDrawable(bp);
        imageView.setImageDrawable(bmd);
		
	}

}