package dkt.student.net;

import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import dkt.student.MyContants;
import android.content.Context;
import android.os.Handler;
import android.os.Message;
import android.widget.Toast;

/**
 * 用户http请求的辅助类-根据不同情况弹出相应的提示
 * 
 * @author Administrator
 * 
 */
public class HttpHandler extends Handler {
	Context context;
	String tag;

	public HttpHandler(Context context, String tag) {
		this.context = context;
		this.tag = tag;
		
	}

	@Override
	public void handleMessage(Message msg) {
		super.handleMessage(msg);
		if (msg.what != HttpStatus.SC_OK) {

			String errorMsg = msg.what + "";
			switch (msg.what) {
			case MyContants.HTTP_URL_NULL_WRONG:
				errorMsg = "请设置ip地址";
				break;
			case MyContants.HTTP_CONNECT_TIMOUT_WRONG:
				errorMsg = "连接超时";
				break;
			case MyContants.HTTP_OTHRE_WRONG:
				errorMsg = "网络异常";
				break;
			case MyContants.HTTP_NULL_WRONG:
				errorMsg = "没有相关数据";
				break;
			}

			Toast.makeText(context, errorMsg, Toast.LENGTH_SHORT).show();
		} else {
			String result = (String) msg.obj;
			
			try {
				JSONObject jsonObject = new JSONObject(result);
				if (jsonObject.has("errCode")) {
					if(!tag.equals(MyContants.DO_HTTP_TEACH_HOUR_RESOURCES)){
//						Toast.makeText(context,
//								jsonObject.get("errMessage").toString(),
//								Toast.LENGTH_SHORT).show();
					}
					msg.what = MyContants.HTTP_RETURE_CODE_WRONG;
				}
			} catch (JSONException e1) {
				e1.printStackTrace();
			}

		}

	}

}
