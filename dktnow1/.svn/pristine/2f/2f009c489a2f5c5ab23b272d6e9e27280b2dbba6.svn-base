package dkt.student.view.popu;

import dkt.student.MyContants;
import dkt.student.R;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.graphics.drawable.BitmapDrawable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.EditText;
import android.widget.PopupWindow;
import android.widget.Toast;

public class SetIpPopu {
	
	Context context;
	PopupWindow popup;
	View view;
	EditText serverEdit;
	EditText pushIpEdit;
	
	public SetIpPopu(Context context, View view) {
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
				R.layout.set_ip_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		
		serverEdit = (EditText) view1.findViewById(R.id.set_server_ip_edit);
		pushIpEdit = (EditText) view1.findViewById(R.id.set_push_ip_edit);
		
		SharedPreferences  share = context.getSharedPreferences(MyContants.PREFERENCE_NAME,0);
		Editor editor = share.edit();
		String getPush_ip = share.getString("push_ip", "");
		String getService_ip = share.getString("servise_ip", "");
		
		editor.commit();
		serverEdit.setText(getService_ip);
		pushIpEdit.setText(getPush_ip);
		
		view1.findViewById(R.id.set_ip_save_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				String ipString = serverEdit.getText().toString().trim();
				String pushIpString = pushIpEdit.getText().toString().trim();
				if ("".equals(ipString)) {
					
					Toast.makeText(context, "请输入服务器IP地址", Toast.LENGTH_SHORT).show();

				} else {
					if("".equals(pushIpString)){
						
						Toast.makeText(context, "请输入推送服务器IP地址", Toast.LENGTH_SHORT).show();

					}else{
						
						SharedPreferences share = context.getSharedPreferences(
								MyContants.PREFERENCE_NAME, 0);
						Editor editor = share.edit();
						editor.putString("servise_ip", ipString);
						editor.putString("push_ip", pushIpString);
						editor.commit();
						Toast.makeText(context, "IP地址设置成功", Toast.LENGTH_SHORT).show();
						popup.dismiss();
						
					}
				}
			}
		});
		view1.findViewById(R.id.set_ip_cancel_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				clearPopup();
			}
		});
		popup.showAsDropDown(view);
	}
}
