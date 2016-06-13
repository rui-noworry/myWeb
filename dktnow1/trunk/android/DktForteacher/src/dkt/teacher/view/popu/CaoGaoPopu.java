package dkt.teacher.view.popu;

import dkt.teacher.R;
import dkt.teacher.view.DeputyView;
import android.content.Context;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.widget.PopupWindow;
import android.widget.RelativeLayout;

public class CaoGaoPopu {
	PopupWindow popup;
	int currentX;
	int currentY;
	Context context;
	
	public CaoGaoPopu(Context context) {
		this.context = context;
	}
	
	public void clearPopup() {
		if (popup != null && popup.isShowing()) {
			popup.dismiss();
			popup = null; 
		}
	}
	
	public void showPopup(View v) {
		
		View view = LayoutInflater.from(context).inflate(R.layout.caogao_popu,
				null);
		RelativeLayout iv_icon = (RelativeLayout) view
				.findViewById(R.id.move_re);
//		myDeputyView = (DeputyView) view.findViewById(R.id.homework_deputyview);
		popup = new PopupWindow(view, 914, 514);
		
		
		
		currentX = view.getWidth();
		currentY =  view.getHeight();
		
		view.findViewById(R.id.move_popu_close).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				clearPopup();
			}
		});
		
		iv_icon.setOnTouchListener(new OnTouchListener() { 

			private int lastX, lastY;
			
			@Override
			public boolean onTouch(View v, MotionEvent event) {
				// TODO Auto-generated method stub
				switch (event.getAction()) {
				case MotionEvent.ACTION_DOWN:

					lastX = (int)event.getRawX();
                    lastY = (int)event.getRawY();
					break;
				case MotionEvent.ACTION_MOVE:
					
					int dx = (int)event.getRawX() - lastX;
					int dy = (int)event.getRawY() - lastY;
					
					currentX += dx;
					currentY += dy;
					popup.update(currentX, currentY, -1, -1);
					lastX = (int) event.getRawX();
		            lastY = (int) event.getRawY();					
					break;
				case MotionEvent.ACTION_UP:
					lastX = 0;
                    lastY = 0;
					break;
				}
				return true;
			}
		});
		
		popup.showAtLocation(v, Gravity.CENTER, 0, 0);
		popup.update();
	}
}
