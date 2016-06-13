package dkt.teacher.view.popu;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.sql.Date;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.graphics.drawable.BitmapDrawable;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.TextView;
import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.net.HttpApacheMapPostThread;
import dkt.teacher.net.HttpHandler;
import dkt.teacher.util.Md5Util;
import dkt.teacher.util.bitmap.FinalBitmap;

public class DiscussPopu {

	Context context;
	PopupWindow popup;
	Dialog dialog;
	private String actId;
	private String apId;
	private FinalBitmap fb;
	private ListView discussListView;
	View view;
	
	public DiscussPopu(Context context, View view, String actId, String apId) {
		this.context = context;
		this.view = view;
		this.actId = actId;
		this.apId = apId;
		fb = new FinalBitmap(context).init();
	}
	
	public void showPopup() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.classhour_discuss_popu, null);
		popup = new PopupWindow(view1, 800,
				600);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		discussListView = (ListView) view1.findViewById(R.id.discuss_listview);
		view1.findViewById(R.id.discuss_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				showDiscussDialog();
			}
		});
		
		popup.showAsDropDown(view);
		doDiscussDetailHttp();
	}
	
	/**
	 * 打开发表讨论弹框
	 * */
	private void showDiscussDialog() {
		LayoutInflater inflater = (LayoutInflater) context
		.getSystemService(context.LAYOUT_INFLATER_SERVICE);
		View layout = inflater.inflate(R.layout.discussdialog, null);
		dialog = new Dialog(context,R.style.dialog);
		dialog.setContentView(layout);
		dialog.setCancelable(true);
		dialog.show();
		final EditText myEditText = (EditText)layout.findViewById(R.id.correct_popu_edit);
		
		layout.findViewById(R.id.discuss_close).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				dialog.dismiss();
			}
		});
		
		layout.findViewById(R.id.discuss_msg).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				String at_content = myEditText.getText().toString().trim();
				if(at_content.equals("")) {
					
				}else{
					doDiscussHttp(at_content);
				}
			}
		});
	}
	
	/**
	 * 发表讨论
	 * */
	private void doDiscussHttp(String at_content) {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Activity.activityTalk");
		map.put("args[ap_id]", apId);
//		map.put("args[at_content]", at_content);
		try {
			map.put("args[at_content]", URLEncoder.encode(at_content, "utf-8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_ACTIVITY_TAIK), map);
	}
	
	/**
	 * 获取讨论详细
	 * */
	private void doDiscussDetailHttp() {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Activity.talks");
		map.put("args[act_id]", actId);
		map.put("args[ap_id]", apId);
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
	 * 解析讨论数据
	 * */
	private void doGetDiscussDetailSucces(String result) {
		/**
		 * {"info":{"list":[
		 * {"at_id":"3","s_id":"3","l_id":"147","co_id":"6",
		 * "cl_id":"81","a_id":"50","ap_id":"208","act_id":"368",
		 * "at_content":"hello","at_is_top":"0","at_created":"1378102025",
		 * "a_nickname":"\u5173\u8001\u5e08",
		 * "a_avatar":"http:\/\/192.168.7.53:81\/AuthAvatar\/48\/default.jpg"},
		 * {"at_id":"4","s_id":"3","l_id":"147","co_id":"6",
		 * "cl_id":"81","a_id":"50","ap_id":"208","act_id":"368",
		 * "at_content":"hello","at_is_top":"0","at_created":"1378102040",
		 * "a_nickname":"\u5173\u8001\u5e08",
		 * "a_avatar":"http:\/\/192.168.7.53:81\/AuthAvatar\/48\/default.jpg"},
		 * */
		
		List<HashMap<String, Object>> classhourDiscussList = 
			new ArrayList<HashMap<String, Object>>();
		try {
			JSONObject jsonObject = new JSONObject(result);
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONArray jesonArry = new JSONArray(jsonObject1.get("list").toString());
			int count = jesonArry.length();
			System.out.println("==================="+count);
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("ap_id", object.get("ap_id").toString());
				map.put("a_avatar", object.get("a_avatar").toString());
				map.put("at_content", object.get("at_content").toString());
				map.put("at_created", object.get("at_created").toString());
				map.put("act_id", object.get("act_id").toString());
				map.put("a_nickname", object.get("a_nickname").toString());
				classhourDiscussList.add(map);
			}
			setDiscussListview(classhourDiscussList);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	/**
	 * 
	 * */
	private void doDiscussSucces(String result) {
		dialog.dismiss();
		popup.dismiss();
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
					doGetDiscussDetailSucces((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_TEACH_ACTIVITY_TAIK)) {
					doDiscussSucces((String) msg.obj);
				}
			}
		}
	}
	
	/**
	 * 设置讨论listview
	 * */
	private void setDiscussListview(List<HashMap<String, Object>> classhourDiscussList) {
		MyAdapter myAdapter = new MyAdapter(context, classhourDiscussList, 0, 0);
		discussListView.setAdapter(myAdapter);
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
			if(0 == tag){
				view = addDiscussView(position, convertView, parent);
			}

			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成讨论列表视图
		private View addDiscussView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_discuss_listview_item, null);
				holder.classworkImg = (ImageView) convertView
						.findViewById(R.id.classhour_discuss_img);
				holder.name = (TextView) convertView
						.findViewById(R.id.classhour_discuss_name);
				holder.time = (TextView) convertView
						.findViewById(R.id.classhour_discuss_time);
				holder.content = (TextView) convertView
						.findViewById(R.id.discuss_content);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			fb.display(holder.classworkImg, list.get(position).get("a_avatar").toString());
			holder.name.setText(list.get(position).get("a_nickname").toString());
			
			long myTime = Long.parseLong(list.get(position).get("at_created").toString());
			SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd hh:mm:ss");
			String date = sdf.format(new Date(myTime*1000));
			
			holder.time.setText(date);
			holder.content.setText(list.get(position).get("at_content").toString());
			return convertView;

		}
		
		
		class Holder {
			TextView name, time, content;
			ImageView classworkImg, readImg;
			
		}
		
	}
}
