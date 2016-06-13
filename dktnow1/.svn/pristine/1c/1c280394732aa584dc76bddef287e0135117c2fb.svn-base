package dkt.teacher.view.popu;

import java.io.File;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.database.ResourceServer;
import dkt.teacher.model.Resource;
import dkt.teacher.net.HttpApacheMapPostThread;
import dkt.teacher.net.HttpHandler;
import dkt.teacher.util.Md5Util;
import dkt.teacher.util.ViewUtil;
import dkt.teacher.util.bitmap.FinalBitmap;


public class classhourActivityPopu {

	Context context;
	PopupWindow popup;
	private String actId;
	private String activityType;
	private RelativeLayout blackboardRelativeLayout;
	private FinalBitmap fb;
	View view;

	
	public classhourActivityPopu(Context context, View view, String actId, String activityType) {
		this.context = context;
		this.view = view;
		this.actId = actId;
		this.activityType = activityType;
		fb = new FinalBitmap(context).init();
	}
	
	public void clearPopup() {
		if (popup != null && popup.isShowing()) {
			popup.dismiss();
			popup = null; 
		}
	}
	
	public void showPopup() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.classhour_activity_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		blackboardRelativeLayout = (RelativeLayout) view1.findViewById(R.id.blackboard_change_re);
		
		popup.showAsDropDown(view);
		doActivityDetailHttp();
	}
	
	/**
	 * 活动详细
	 */
	private void doActivityDetailHttp() {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Activity.detail");
		map.put("args[act_id]", actId);
		map.put("args[c_id]", app.getcId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL), map);
	}
	
	/**
	 * 解析课堂练习数据
	 * 
	 * */
	private void doGetClassworkDetailSucces(String result) {
		/**
		 * {"status":1,"info":{"list":
		 * {"act_id":"1","act_rel":"1,2","act_type":"1","c_id":",1,",
		 * "cro_id":"","act_is_published":"1","co_id":"1",
		 * "act_note":"\u8981\u5199\u6ce8\u91ca\u6389\u6570\u636e\u6253\u7b97\u5927\u5bb6\u5927\u52ab\u6848\u7684 \u8428\u90fd\u524c\u6536\u5230\u5361\u6b7b\u4e86\u6253\u5f00\u5c71\u4e1c\u9f99\u53e3  \u6492\u65e6\u7231\u8bf4\u6253\u7b97\u5927\u5bb6\u5b89\u8fbe\u5927\u5bb6\u6492\u65e6\u5c31\u554a\u5927\u5bb6\u554a\u89e3\u653e\u519b\u653e\u5047 \u79ef\u5206\u5361\u5c31\u51cf\u51cf\u80a5\u5c31\u5c31 \u65b9\u6cd5\u98de\u53d1\u53d1\u662ffks\u5c31fks\u5c31\u798f\u5efa\u7701\u53d1\u770b\u5065\u8eab\u623f\u770b\u624b\u673a\u8d39 \u6c34\u7535\u8d39\u5c31\u4e0a\u5c9b\u5496\u5561\u51e0\u5341\u5757\u7684\u798f\u5efa\u7701\u5feb\u9012\u8d39\u5c31\u5f00\u59cb\u53d1\u5065\u8eab\u5361\u6253\u98de\u673akdj\u653e\u6c34\u7535\u8d39\u5c31",
		 * "attachment":[],
		 * "topic":[{"to_id":"1","a_id":"2","s_id":"1",
		 * "to_title":"%26lt%3Bp%26gt%3B%E6%92%92%E6%97%A6%E6%92%92%E6%97%A6%E7%9A%84%E8%AF%AD%E6%96%87%E5%A5%BD%E4%B8%8D%EF%BC%9F%26lt%3B%2Fp%26gt%3B%26lt%3Bp%26gt%3BA%E8%A1%8C+B+%E4%B8%8D%E7%9F%A5%E9%81%93%26lt%3B%2Fp%26gt%3B",
		 * "to_type":"1","to_option":"0,1,2,3","to_answer":"[\"0\"]",
		 * "to_note":"","to_peoples":"0","to_created":"1372933157",
		 * "to_updated":"0","to_deleted":"0",
		 * "path":"http:\/\/192.168.7.53:81\/GenerationTopic\/Image\/1.png"}
		 * */
		
		try {
			List<HashMap<String, Object>> classhourClassWorkList = 
				new ArrayList<HashMap<String, Object>>();
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无课堂练习数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONObject jsonObject2 = new JSONObject(jsonObject1.get("list").toString());
			JSONArray jesonArry = new JSONArray(jsonObject2.getString("topic").toString());
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("to_type", object.get("to_type").toString());
				map.put("to_id", object.get("to_id").toString());
				map.put("path", object.get("path").toString());
				map.put("to_answer", object.get("to_answer").toString());
				map.put("answer", "-1");
				classhourClassWorkList.add(map);
			}
			addClassWorkListRe(classhourClassWorkList);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析拓展阅读数据
	 * */
	private void doGetResourceDetailSucces(String result) {
		/**
		 * {"status":1,"info":{"list":
		 * {"act_id":"30","act_rel":"19,18,17,16,15,14,20",
		 * "act_type":"5","c_id":"","cro_id":"",
		 * "act_is_published":"0","co_id":"1","act_note":"",
		 * "resource":[{"ar_id":"14","a_id":"2","s_id":"1",
		 * "rta_id":",15,39,1,5,12,","ar_title":"Children",
		 * "ar_savename":"http:\/\/192.168.7.53:81\/AuthResource\/transform\/audio\/201307\/51d943010b15c.m4v",
		 * "ar_is_transform":"1","ar_ext":"m4v",
		 * "m_id":"3","ar_created":"1373193082",
		 * "img_path":"http:\/\/192.168.7.53:81\/ResourceImg\/default.jpg",
		 * "filePath":"..\/Uploads\/AuthResource\/transform\/audio\/201307\/51d943010b15c.m4v"}
		 * */
		try {
			List<HashMap<String, Object>> classhourReadList = 
				new ArrayList<HashMap<String, Object>>();
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无拓展阅读数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONObject jsonObject2 = new JSONObject(jsonObject1.get("list").toString());
			JSONArray jesonArry = new JSONArray(jsonObject2.getString("resource").toString());
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("ar_id", object.get("ar_id").toString());
				map.put("ar_title", object.get("ar_title").toString());
				map.put("m_id", object.get("m_id").toString());
				classhourReadList.add(map);
			}
			addReadListRe(classhourReadList);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	/**
	 * 解析文本数据
	 * */
	private void doGetTextDetailSucces(String result) {
		/**
		 * 	{"status":1,"info":{"list":
		 * {"act_id":"4","act_rel":"","act_type":"3","c_id":",1,",
		 * "cro_id":"","act_is_published":"1","co_id":"1",
		 * "act_note":"http:\/\/192.168.7.53:81\/GenerationActivity\/Image\/4.png"}}}
		 * */
		
		try {
			
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无文本数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONObject object = new JSONObject(jsonObject1.get("list").toString());
			
			String imgUrl = object.get("act_note").toString();
			System.out.println("==================="+imgUrl);
			addTextListRe(imgUrl);
			
		} catch (Exception e) {
			// TODO: handle exception
			
		}
	}
	/**
	 * 解析连接数据
	 * */
	private void doGetLinkDetailSucces(String result) {
		
		try {
			List<HashMap<String, Object>> classhourLinkList = 
				new ArrayList<HashMap<String, Object>>();
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无连接数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONObject jsonObject2 = new JSONObject(jsonObject1.get("list").toString());
			JSONArray jesonArry = new JSONArray(jsonObject2.getString("link").toString());
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("li_title", object.get("li_title").toString());
				map.put("li_url", object.get("li_url").toString());
				classhourLinkList.add(map);
			}
			addLinkListRe(classhourLinkList);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	class blackboardHandler extends HttpHandler {
		
		String tag;
		
		public blackboardHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}
		
		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);
			((Activity) context).dismissDialog(MyContants.HTTP_WAITING);
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				if(tag.equals(MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL)){
					if(activityType.equals("1")) {
						doGetClassworkDetailSucces((String) msg.obj);
					}else if(activityType.equals("2")) {
						doGetClassworkDetailSucces((String) msg.obj);
					}else if(activityType.equals("3")) {
						doGetTextDetailSucces((String) msg.obj);
					}else if(activityType.equals("4")) {
						doGetLinkDetailSucces((String) msg.obj);
					}else if(activityType.equals("5")) {
						doGetResourceDetailSucces((String) msg.obj);
					}
				}
			}
		}
	}
	
	/**
	 * 添加课堂练习列表
	 * */
	private void addClassWorkListRe(List<HashMap<String, Object>> classhourClassWorkList) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(context).inflate(
				R.layout.classhour_homework_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_classwork_listview);
		packageListView.setDividerHeight(20);
		MyAdapter packageAdapter = new MyAdapter(context, classhourClassWorkList, 4, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
	}
	
	/**
	 * 添加拓展阅读列表
	 * */
	private void addReadListRe(final List<HashMap<String, Object>> classhourReadList) {
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(context).inflate(
				R.layout.blackboard_link_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_link_listview);
		packageListView.setDividerHeight(20);
		
		packageListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				ResourceServer resourceServer = new ResourceServer();
				Resource myResource = new Resource();
				myResource = resourceServer.getMyNetData(Integer.parseInt(classhourReadList.get(arg2).get("ar_id").toString()));
				if(myResource == null) {
					System.out.println(classhourReadList.get(arg2).get("ar_savename").toString());
					ViewUtil.myToast(context, "该资源正在下载中，请稍候。。。");
				}else{
					String filePath = myResource.getResourcePath();
					String fileName = filePath.substring(filePath.lastIndexOf(".") + 1);
					File f = new File(filePath);
					if(!f.exists()) {
						Toast.makeText(context, context.getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();
						resourceServer.delete(myResource.getResourceId());
					}else{
						ViewUtil.openResource(fileName, filePath, context);
					}
				}
			}
		});
		MyAdapter packageAdapter = new MyAdapter(context, classhourReadList, 5, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
	}
	/**
	 * 添加文本列表
	 * */
	private void addTextListRe(final String imgUrl) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(context).inflate(
				R.layout.blackboard_text_re, null);
		ImageView textImageView = (ImageView)view.findViewById(R.id.blackboard_text_imgview);
		fb.display(textImageView, imgUrl);
		
		blackboardRelativeLayout.addView(view, packageListRela);
		
	}
	
	/**
	 * 添加链接列表
	 * */
	private void addLinkListRe(final List<HashMap<String, Object>> classhourLinkList) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(context).inflate(
				R.layout.blackboard_link_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_link_listview);
		packageListView.setDividerHeight(20);
		
		packageListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				openUrl(classhourLinkList.get(arg2).get("li_url").toString());
			}
		});
		MyAdapter packageAdapter = new MyAdapter(context, classhourLinkList, 3, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
		
	}
	
	class MyAdapter extends BaseAdapter {

		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;

		public MyAdapter(Context context,
				List<HashMap<String, Object>> list, int tag, int i) {
			this.list = list;
			this.context = context;
			this.tag = tag;
			this.i = i;
		}

		@Override
		public int getCount() {
			// TODO Auto-generated method stub
			return list.size();
		}

		@Override
		public Object getItem(int position) {
			// TODO Auto-generated method stub
			return null;
		}

		@Override
		public long getItemId(int position) {
			// TODO Auto-generated method stub
			return 0;
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			// TODO Auto-generated method stub
			View view = null;
			if(3 == tag){
				view = addLinkgeView(position, convertView, parent);
			}else if(4 == tag){
				view = addClassWorkView(position, convertView, parent);
			}else if(5 == tag){
				view = addReadView(position, convertView, parent);
			}
			
			
			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成课堂练习列表视图
		private View addClassWorkView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_classwork_listview_item, null);
				holder.classworkImg = (ImageView) convertView
						.findViewById(R.id.classwork_img);
				holder.answer = (TextView) convertView
						.findViewById(R.id.classwork_answer);
				holder.studentAnswer = (TextView) convertView
						.findViewById(R.id.classwork_stu_answer);
				holder.toType = (TextView) convertView
						.findViewById(R.id.classwork_type);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
//			String biaotiString = list.get(position).get("li_title").toString();
//			if(biaotiString.length() > 20) {
//				biaotiString = biaotiString.substring(0, 20) + "...";
//			}
//			holder.linkName.setText(biaotiString);
			
			String answer = list.get(position).get("answer").toString();
			String type = list.get(position).get("to_type").toString();
			if(answer.equals("-1")) {
				holder.studentAnswer.setVisibility(View.GONE);
				holder.answer.setVisibility(View.GONE);
			}
			
			if(type.equals("1")) {
				holder.toType.setText((position+1)+"、单项选择");
			}else if(type.equals("2")) {
				holder.toType.setText((position+1)+"、多项选择");
			}else if(type.equals("3")) {
				holder.toType.setText((position+1)+"、填空");
			}else if(type.equals("4")) {
				holder.toType.setText((position+1)+"、判断");
			}else if(type.equals("5")) {
				holder.toType.setText((position+1)+"、简答");
			}
			
			
			final String imgUrl = list.get(position).get("path").toString();
			System.out.println(list.size()+"====="+list.get(position).get("path").toString());
			fb.display(holder.classworkImg, imgUrl);
			
			
			return convertView;

		}
		
		// 生成拓展阅读列表视图
		private View addReadView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.blackboard_read_listview_item, null);
				
				holder.readName = (TextView) convertView
						.findViewById(R.id.read_name);
				holder.readImg = (ImageView) convertView
						.findViewById(R.id.read_type);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("ar_title").toString();
			if(biaotiString.length() > 20) {
				biaotiString = biaotiString.substring(0, 20) + "...";
			}
			holder.readName.setText(biaotiString);	
			
			if(list.get(position).get("m_id").toString().equals("1")) {
				holder.readImg.setBackgroundResource(R.drawable.resource_icon_image_bg);
			}else if(list.get(position).get("m_id").toString().equals("2")){
				holder.readImg.setBackgroundResource(R.drawable.resource_icon_shipin_bg);

			}else if(list.get(position).get("m_id").toString().equals("3")){
				holder.readImg.setBackgroundResource(R.drawable.rsource_icon_yinpin_bg);

			}else if(list.get(position).get("m_id").toString().equals("4")){
				holder.readImg.setBackgroundResource(R.drawable.resource_icon_doc_bg);

			}
			return convertView;

		}
		
		// 生成链接列表视图
		private View addLinkgeView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.blackboard_link_listview_item, null);
				
				holder.linkName = (TextView) convertView
						.findViewById(R.id.link_name);
				holder.linkUrl = (TextView) convertView
						.findViewById(R.id.link_url);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("li_title").toString();
			if(biaotiString.length() > 20) {
				biaotiString = biaotiString.substring(0, 20) + "...";
			}
			holder.linkName.setText(biaotiString);			
			holder.linkUrl.setText(list.get(position).get("li_url").toString());
			
			return convertView;

		}
		
		class Holder {
			TextView activityName, readName, packageName, linkName, linkUrl, answer, studentAnswer, toType;
			ImageView classworkImg, readImg;
			RelativeLayout activityRela;
			Button packageLoad, packageSten, packageDelete;
		}
		
	}
	
	/**
	 * 浏览器调用
	 * */
	private void openUrl(String url) {
		
		Intent intent = new Intent(Intent.ACTION_VIEW);
		intent.setData(Uri.parse(url));
		context.startActivity(intent);

	}
}
