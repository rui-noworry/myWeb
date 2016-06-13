package dkt.student.view.dialog;

import java.io.File;

import dkt.student.R;
import dkt.student.util.DateUtil;
import dkt.student.util.ViewUtil;
import android.app.Dialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;


public class ResourceDialog {

	public static void createResourceDialog(final Context context,final String filePath ,
			final String fileName, long fileCreatTime, int fileType){
		LayoutInflater inflater = (LayoutInflater) context
				.getSystemService(context.LAYOUT_INFLATER_SERVICE);
		View layout = inflater.inflate(R.layout.resource_msg_xml, null);
		final Dialog dialog = new Dialog(context,R.style.dialog);
		dialog.setContentView(layout);
		dialog.setCancelable(true);
		dialog.show();
		
		String name = filePath.substring(filePath.lastIndexOf("/")+1, filePath.length());
		TextView nameText = (TextView) layout.findViewById(R.id.resource_name);
		nameText.setText(fileName);
		
		final String suff = name.substring(name.lastIndexOf(".")+1, name.length());
		TextView suffText = (TextView) layout.findViewById(R.id.resource_style);
		suffText.setText(context.getString(R.string.file_type) + suff);
		
		File file = new File(filePath);
		String time = DateUtil.longToStr(file.lastModified());
		TextView timeText  = (TextView) layout.findViewById(R.id.resource_time);
		timeText.setText(context.getString(R.string.file_time) + time);
		
		ImageView image = (ImageView) layout.findViewById(R.id.ItemImage);
		ViewUtil.setImageView(image, fileType);
		//resourceClicks
		layout.findViewById(R.id.resource_scan).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				
//				ViewUtil.openResource(suff, filePath, context);
				File f = new File(filePath);
				if(!f.exists()) {
					Toast.makeText(context, context.getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();

				}else{
					ViewUtil.openResource(suff, filePath, context);
				}
				dialog.dismiss();
			}
		});
		
		
	}
}
