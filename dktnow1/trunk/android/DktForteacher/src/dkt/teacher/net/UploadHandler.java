package dkt.teacher.net;

import dkt.teacher.MyContants;
import android.content.Context;
import android.os.Handler;
import android.os.Message;
/**
 * 用户上传文件的辅助类-根据不同情况弹出相应的提示
 * @author Administrator
 *
 */
public class UploadHandler extends Handler {
	Context context;

	public UploadHandler(Context context,String tag) {
		this.context = context;
	}

	@Override
	public void handleMessage(Message msg) {
		super.handleMessage(msg);
		if (msg.what != MyContants.HTTP_UOLOAD) {

			String errorMsg = msg.what +"";
			switch (msg.what) {
			case MyContants.HTTP_URL_NULL_WRONG:
				errorMsg = "请设置ip地址";
				break;
			case MyContants.HTTP_UOLOAD_SUCESS:
				errorMsg = "上传成功";
				break;
			case MyContants.HTTP_UOLOAD_FAIL:
				errorMsg = "上传失败";
				break;
			case MyContants.HTTP_UPLOAD_SUCESS:
				errorMsg = "新增成功";
				break;
			}
			
		}

	}

}
