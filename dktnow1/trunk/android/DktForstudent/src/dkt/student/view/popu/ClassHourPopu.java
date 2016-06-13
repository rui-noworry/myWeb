package dkt.student.view.popu;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONObject;

import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.activity.ClassHourActivity;
import dkt.student.net.HttpApacheMapPostThread;
import dkt.student.net.HttpHandler;
import dkt.student.util.Md5Util;
import dkt.student.util.ViewUtil;
import android.content.Context;
import android.content.Intent;
import android.graphics.drawable.BitmapDrawable;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;


public class ClassHourPopu {

	Context context;
	PopupWindow popup;
	View view;
	ListView classhourListView;
	List<HashMap<String, Object>> classhourList = 
		new ArrayList<HashMap<String, Object>>();
	
	public ClassHourPopu(Context context, View view) {
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
				R.layout.classhour_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		MyApp app = (MyApp) context.getApplicationContext();
		String lId = app.getlId();
		classhourListView = (ListView) view1.findViewById(R.id.classhour_listview);
		classhourListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				
				MyApp app = (MyApp) context.getApplicationContext();
				app.setClId(classhourList.get(arg2).get("cl_id").toString());
				app.setClName(classhourList.get(arg2).get("cl_title").toString());
				
				Intent intent  = new Intent(context, ClassHourActivity.class);
				context.startActivity(intent);	
			}
		});
		doCourseListHttp(lId);
		
	}
	
	/**
	 * 得到该课文下的课时
	 */
	private void doCourseListHttp(String lId) {
		MyApp app = (MyApp) context.getApplicationContext();
		int userId = app.getUserId();
		String skey = app.getSkey();
		String coId = app.getCoId();
		String cId = app.getcId();
		
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Classhour.lists");
		map.put("args[a_id]", userId);
		map.put("args[l_id]", lId);
		map.put("args[co_id]", coId);
		map.put("args[c_id]", cId);
		map.put("skey", skey);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new ClassHourHandler(context,
				MyContants.DO_HTTP_CLASSHOUR_LIST), map);
	}
	
	private void doClassHourSucess(String result) {
		/**
	     * {"status":1,"info":{"list":[
	     * {"cl_id":"1","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u7b2c\u4e00\u8bfe\u65f6",
	     * "cl_sort":"1","c_id":"","cro_id":"","cl_is_published":"0",
	     * "cl_status":"1"},
	     * {"cl_id":"2","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"2","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"3","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"3","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"4","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"4","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"},
	     * {"cl_id":"5","co_id":"1","l_id":"2","a_id":"2",
	     * "s_id":"1","cl_title":"\u6d4b\u8bd5\u8bfe\u65f6",
	     * "cl_sort":"5","c_id":",1,","cro_id":"","cl_is_published":"1",
	     * "cl_status":"1"}]}}

	     * */
		
		classhourList.clear();
		try {
			 
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无课时数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONArray jesonArry = new JSONArray(jsonObject1.get("list").toString());
			
			int count = jesonArry.length();
			System.out.println("==================="+count);
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("cl_title", object.get("cl_title").toString());
				map.put("cl_id", object.get("cl_id").toString());
				map.put("cl_is_published", object.get("cl_is_published").toString());
				classhourList.add(map);
			}
			ClassHourAdapter classhourAdapter = new ClassHourAdapter(context,
					classhourList, 0, 0);
			classhourListView.setAdapter(classhourAdapter);
			popup.showAsDropDown(view);
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	class ClassHourHandler extends HttpHandler {
		String tag;

		public ClassHourHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}

		@Override
		public void handleMessage(Message msg) {
			super.handleMessage(msg);

			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				doClassHourSucess((String) msg.obj);
			}

		}

	}
	
	class ClassHourAdapter extends BaseAdapter {
		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;

		public ClassHourAdapter(Context context,
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
			
			view = addClassHourView(position, convertView, parent);
	
			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成课时列表视图
		private View addClassHourView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.classhour_listview_item, null);
				holder.classHourName = (TextView) convertView
						.findViewById(R.id.classhour_name);
				holder.classHourNum = (TextView) convertView
						.findViewById(R.id.classhour_num);
				
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			holder.classHourName.setText(list.get(position).get("cl_title").toString());
			holder.classHourNum.setText("第"+(position+1)+"课时");

			
			return convertView;

		}
		
		class Holder {
			TextView classHourName, classHourNum;
			
		}

	}
}
