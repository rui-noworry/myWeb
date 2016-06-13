package dkt.teacher.net;

import dkt.teacher.MyContants;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class GetIp {

	public final String push_ip;
	public final String servise_ip;
	
	public GetIp(Context context){
		SharedPreferences  share = context.getSharedPreferences(MyContants.PREFERENCE_NAME,0);
		Editor editor = share.edit();
		String getPush_ip = share.getString("push_ip", "");
		String getService_ip = share.getString("servise_ip", "");
		//测试ip
		//getService_ip= MyContants.TEMP_IP;
		editor.commit();
		push_ip = getPush_ip;
		servise_ip = getService_ip;
		System.out.println("===服务器ip"+servise_ip);
	}
	
}
