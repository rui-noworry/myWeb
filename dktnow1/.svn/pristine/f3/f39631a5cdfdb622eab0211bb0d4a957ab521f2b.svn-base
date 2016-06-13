package dkt.student.base;

import dkt.student.MyContants;
import android.content.Context;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;

public class UserMsg {

	public static String USER_NAME = "userName";
	public static String USER_PASSWORD = "passWord";
	
	/**
	 *  保存用户信息
	 * */
	public static void setConfigMsg(Context context, String[] params,
			String[] values) {
		SharedPreferences share = context.getSharedPreferences(
				MyContants.PREFERENCE_NAME, 0);
		Editor editor = share.edit();
		int count = params.length;
		for (int i = 0; i < count; i++) {
			editor.putString(params[i], values[i]);
		}
		editor.commit();
	}
	
	/**
	 *  获取用户信息
	 * */
	public static String getConfigMsg(Context context, String param) {
		SharedPreferences share = context.getSharedPreferences(
				MyContants.PREFERENCE_NAME, 0);
		String value = share.getString(param, "");
		return value;
	}
	
	/**
	 *  清除用户信息
	 * */
	public static void clearConfigMsg(Context context,String[] params){
		SharedPreferences share = context.getSharedPreferences(
				MyContants.PREFERENCE_NAME, 0);
		Editor editor = share.edit();
		
		int count = params.length;
		for (int i = 0; i < count; i++) {
			editor.remove(params[i]);
		}
		editor.commit();
	}
}
