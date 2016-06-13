package dkt.teacher.view.popu;

import java.util.HashMap;
import java.util.List;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.drawable.BitmapDrawable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.PopupWindow;
import dkt.teacher.MyContants;
import dkt.teacher.R;

public class ToolPopu {
	Context context;
	PopupWindow popup;
	View view;
	
	
	public ToolPopu(Context context, View view) {
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
				R.layout.tool_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		
		view1.findViewById(R.id.tool_photograph_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent intent = new Intent(android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
				((Activity) context).startActivityForResult(intent, MyContants.RESOURCE_IMG);
				clearPopup();
			}
		});
		view1.findViewById(R.id.tool_recorder_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent intent = new Intent(Intent.ACTION_GET_CONTENT);    
				intent.setType("audio/amr");    
				intent.setClassName("com.android.soundrecorder",
				"com.android.soundrecorder.SoundRecorder");
				((Activity) context).startActivityForResult(intent, MyContants.RESOURCE_AUDIO);
				clearPopup();
			}
		});
		popup.showAsDropDown(view, 60, -140);
	}
	
	
	
}
