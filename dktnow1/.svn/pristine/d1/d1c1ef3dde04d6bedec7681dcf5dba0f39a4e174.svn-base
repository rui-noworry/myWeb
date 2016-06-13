package dkt.teacher.view.popu;

import dkt.teacher.R;
import android.content.Context;
import android.graphics.drawable.BitmapDrawable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup.LayoutParams;
import android.widget.EditText;
import android.widget.PopupWindow;

public class VesionPopu {

	Context context;
	PopupWindow popup;
	View view;
	EditText serverEdit;
	EditText pushIpEdit;
	
	public VesionPopu(Context context, View view) {
		this.context = context;
		this.view = view;
	}
	
	public void clearPopup() {
		if (popup != null && popup.isShowing()) {
			popup.dismiss();
			popup = null; 
		}
	}
	
	public void showPopup() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.vesion_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		popup.showAsDropDown(view);
	}
}
