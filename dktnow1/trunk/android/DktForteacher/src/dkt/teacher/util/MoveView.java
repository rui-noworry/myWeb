package dkt.teacher.util;

import android.view.View;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.view.animation.Animation.AnimationListener;

public class MoveView {

	/**
	 * 组件水平移动
	 * @param view移动组建的 view
	 * @param from开始位置
	 * @param to 结束位置
	 */
	public static void doHorizontalMove(final View view,int from,final int to){
		TranslateAnimation move = new TranslateAnimation(from, to,
				0, 0);
		move.setFillEnabled(true);
		move.setDuration(300);
		move.setAnimationListener(new AnimationListener() {

			public void onAnimationStart(Animation animation) {

			}

			public void onAnimationRepeat(Animation animation) {

			}   

			public void onAnimationEnd(Animation animation) {
				view.offsetLeftAndRight(to);

			}
		});
		view.startAnimation(move);
		
		move =null;
	}
	
	/**
	 * 组件竖直移动
	 * @param view移动组建的 view
	 * @param from开始位置
	 * @param to 结束位置
	 */
	public static void doOrvlMove(final View view,int from,final int to){
		TranslateAnimation move = new TranslateAnimation(0, 0,
				from, to);
		move.setFillEnabled(true);
		move.setDuration(300);
		move.setAnimationListener(new AnimationListener() {

			public void onAnimationStart(Animation animation) {

			}

			public void onAnimationRepeat(Animation animation) {

			}   

			public void onAnimationEnd(Animation animation) {
				view.offsetTopAndBottom(to);

			}
		});
		view.startAnimation(move);
		
		move =null;
	}

}
