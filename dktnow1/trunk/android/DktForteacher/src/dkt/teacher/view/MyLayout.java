package dkt.teacher.view;

import dkt.teacher.listener.MyListener;
import android.content.Context;
import android.graphics.Canvas;
import android.support.v4.view.ViewPager;
import android.util.AttributeSet;
import android.view.MotionEvent;
import android.widget.LinearLayout;

public class MyLayout extends LinearLayout{

	int position;
	float startX;
	int count;
	MyListener layoutListener;
	
	public void setMyListener(MyListener layoutListener) {
		this.layoutListener = layoutListener;
	}
	
	public MyLayout(Context context, AttributeSet attrs) {
		super(context, attrs);
		// TODO Auto-generated constructor stub
	}


	@Override
	public boolean onInterceptTouchEvent(MotionEvent event) {
		// TODO Auto-generated method stub
		int action = event.getAction();
		switch (action) {
		case MotionEvent.ACTION_DOWN:
			startX = event.getX();
			getParent().requestDisallowInterceptTouchEvent(true);
			System.out.println(count+"=========="+position);
			break;


		case MotionEvent.ACTION_MOVE:
//			if (startX == event.getX()) {
//				if (0 == child_viewpager.getCurrentItem()
//						|| child_viewpager.getCurrentItem() == child_viewpager
//								.getAdapter().getCount() - 1) {
//					getParent().requestDisallowInterceptTouchEvent(false);
//				}
//			}
//
//			else if (startX > event.getX()) {
//				if (child_viewpager.getCurrentItem() == child_viewpager
//						.getAdapter().getCount() - 1) {
//					getParent().requestDisallowInterceptTouchEvent(false);
//					layoutListener.showPopuView();
//				}
//			}
//
//			else if (startX < event.getX()) {
//				if (child_viewpager.getCurrentItem() == 0) {
//					getParent().requestDisallowInterceptTouchEvent(false);
//				}
//			} else {
//				getParent().requestDisallowInterceptTouchEvent(true);
//			}
			break;
		case MotionEvent.ACTION_UP:
			if (startX > event.getX()) {
				if (position == count) {
					getParent().requestDisallowInterceptTouchEvent(false);
					layoutListener.showPopuView(position);
				}
			}
			break;
		case MotionEvent.ACTION_CANCEL:
			getParent().requestDisallowInterceptTouchEvent(false);
			break;
		}
		return false;
	}

	public void setChild_position(int position) {
		this.position = position + 1;
	}
	
	public void set_count(int count) {
		this.count = count;
	}
}

