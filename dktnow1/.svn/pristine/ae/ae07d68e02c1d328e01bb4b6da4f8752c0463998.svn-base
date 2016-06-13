package dkt.student.net;

import org.apache.http.HttpStatus;

import dkt.student.MyContants;
import android.content.Context;
import android.os.Handler;
import android.os.Message;
import android.widget.Toast;

/**
 * 用户下载文件的辅助类-根据不同情况弹出相应的提示
 * @author Administrator
 *
 */
public class DownLoadHandler extends Handler {
	Context context;

	public DownLoadHandler(Context context,String tag) {
		this.context = context;
	}

	@Override
	public void handleMessage(Message msg) {
		super.handleMessage(msg);
		if (msg.what != MyContants.HTTP_DOWNLOAD) {

			String errorMsg = msg.what +"";
			switch (msg.what) {
			case MyContants.HTTP_URL_NULL_WRONG:
				errorMsg = "请设置ip地址";
				break;
			case MyContants.HTTP_DOWNLOAD_SUCESS:
				errorMsg = "下载成功";
				break;
			case MyContants.HTTP_DOWNLOAD_FAIL:
				errorMsg = "下载失败";
				break;
			}
			
			Toast.makeText(context, errorMsg, Toast.LENGTH_SHORT).show();
		}

	}

}
