package dkt.student.util;

import java.io.File;

import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.net.GetIp;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

public class ViewUtil {
	public static void myToast(Context context, String text) {
		Toast.makeText(context, text, Toast.LENGTH_SHORT).show();
	}

	public static void setImageView(ImageView icon, int suff) {
		int[] resourceImageIcon = { R.drawable.resource_icon_doc_bg,
				R.drawable.resource_icon_image_bg,
				R.drawable.resource_icon_shipin_bg,
				R.drawable.rsource_icon_yinpin_bg,
				R.drawable.rsource_icon_note_bg };
		icon.setImageResource(resourceImageIcon[suff]);

	}

	public static void openResource(String type, String filePath,
			Context context) {
		Intent intent = new Intent("android.intent.action.VIEW");
		intent.addCategory("android.intent.category.DEFAULT");
		intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		Uri uri = Uri.fromFile(new File(filePath));
		if ("png".equals(type) || "jpg".equals(type) || "jpeg".equals(type)
				|| "gif".equals(type) || "bmp".equals(type)) {
//			intent.setDataAndType(uri, "image/*");
			Intent intent1 = new Intent("showimage");
            intent1.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
            intent1.putExtra("path", filePath);
            context.startActivity(intent1);
		}  else if ("doc".equals(type) || "docx".equals(type)
				|| "ppt".equals(type) || "pptx".equals(type)
				|| "xls".equals(type) || "xlsx".equals(type)) {
			intent.setDataAndType(uri, "application/msword");
			context.startActivity(intent);
		} else if ("txt".equals(type)) {
			intent.setDataAndType(uri, "text/plain");
			context.startActivity(intent);
		} else if ("mp3".equals(type) || ("amr").equals(type) || ("MP3").equals(type)) {
			intent.setDataAndType(uri, "audio/*");
			context.startActivity(intent);
		} else if ("mp4".equals(type) || "rmvb".equals(type) || ("MP4").equals(type) || ("m4v").equals(type)) {
			intent.setDataAndType(uri, "video/*");
			context.startActivity(intent);
		} else if ("pdf".equals(type)) {
			intent.setDataAndType(uri, "application/pdf");
			context.startActivity(intent);
		}
	}

	public static void openResource(String type, String filePath,
			Context context, boolean isNetWork) {
		Intent intent = new Intent("android.intent.action.VIEW");
		intent.addCategory("android.intent.category.DEFAULT");
		intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
		Uri uri = Uri.fromFile(new File(filePath));
		MediaFileUtil util = new MediaFileUtil();
		if (util.getMineType(type) == 1) {
			intent.setDataAndType(uri, "image/*");
		} else if ("doc".equals(type) || "docx".equals(type)
				|| "ppt".equals(type) || "pptx".equals(type)
				|| "xls".equals(type) || "xlsx".equals(type)) {
			intent.setDataAndType(uri, "application/msword");
		} else if ("txt".equals(type)) {
			intent.setDataAndType(uri, "text/plain");
		} else if (util.getMineType(type) == 3) {
			if (isNetWork) {
				GetIp getip = new GetIp(context);
				String service_ip = getip.servise_ip;
				uri = Uri.parse(MyContants.HTTP_PREFIX + service_ip + filePath);
				intent.setDataAndType(uri, "audio/*");
			} else {
				intent.setDataAndType(uri, "audio/*");
			}
		} else if (util.getMineType(type) == 2) {
			if (isNetWork) {
				GetIp getip = new GetIp(context);
				String service_ip = getip.servise_ip;
				uri = Uri.parse(MyContants.HTTP_PREFIX + service_ip + filePath);
				intent.setDataAndType(uri, "video/*");
			} else {
				intent.setDataAndType(uri, "video/*");
			}
		} else if ("pdf".equals(type)) {
			intent.setDataAndType(uri, "application/pdf");
		} else if ("db".equals(type)) {
			
		}
		context.startActivity(intent);
	}
	
}
