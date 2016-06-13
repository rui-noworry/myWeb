package dkt.teacher.view.popu;

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
import android.graphics.drawable.BitmapDrawable;
import android.os.Message;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.RadioButton;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.net.HttpApacheMapPostThread;
import dkt.teacher.net.HttpHandler;
import dkt.teacher.net.UploadFile;
import dkt.teacher.net.UploadHandler;
import dkt.teacher.util.Md5Util;



public class ClasshourPackagePopu {

	Context context;
	PopupWindow popup;
	private boolean isNewPackage = true;
	private EditText packageNameEdit;
	private String dbName;
	
	View view;
	ListView classhourPackageListView;
	List<HashMap<String, Object>> classhourPackageList = 
		new ArrayList<HashMap<String, Object>>();
	
	public ClasshourPackagePopu(Context context, View view, String notepath) {
		this.context = context;
		this.view = view;
		this.dbName = notepath;
	}
	
	public void clearPopup() {
		if (popup != null && popup.isShowing()) {
			popup.dismiss();
			popup = null; 
		}
	}
	
	public void showPopup() {
		View view1 = LayoutInflater.from(context).inflate(
				R.layout.classhour_package_popu, null);
		popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
				LayoutParams.WRAP_CONTENT);
		popup.setBackgroundDrawable(new BitmapDrawable());
		popup.setTouchable(true);
		popup.setFocusable(true);
//		popup.setOutsideTouchable(true);
		MyApp app = (MyApp) context.getApplicationContext();
		RadioButton packageNewRab = (RadioButton) view1.findViewById(R.id.package_new);
		packageNewRab.setChecked(true);
		packageNameEdit = (EditText) view1.findViewById(R.id.package_name_edit);
		view1.findViewById(R.id.package_new).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				isNewPackage = true;
				classhourPackageListView.setVisibility(View.INVISIBLE);
			}
		});
		view1.findViewById(R.id.package_cover).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				isNewPackage = false;
				if(classhourPackageList.size() > 0) {
					classhourPackageListView.setVisibility(View.VISIBLE);
					PackageAdapter packageAdapter = new PackageAdapter(context, classhourPackageList, 0, 0);
					classhourPackageListView.setAdapter(packageAdapter);
				}else{
					doPackageListHttp();
				}
			}
		});

		view1.findViewById(R.id.package_save).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				final String packageName = packageNameEdit.getText().toString().trim();
				if(isNewPackage) {
					if(checkUserNameEditNull(packageName)) {
						new AlertDialog.Builder(context)
						.setTitle("确认新增")
						.setMessage("确定将该数据保存为新的课程包吗？")
						.setPositiveButton(
								"确定",
								new DialogInterface.OnClickListener() {
									@Override
									public void onClick(
											DialogInterface dialog,
											int which) {
										doSaveCoursePackageHttp(packageName);
									}
								})
						.setNegativeButton(
								"取消",
								new DialogInterface.OnClickListener() {
									@Override
									public void onClick(
											DialogInterface dialog,
											int which) {
										dialog.dismiss();
									}
								}).show();
					}else{
						Toast.makeText(context, "请输入板书名称", Toast.LENGTH_SHORT).show();

					}
				}else{
					Toast.makeText(context, "请选择需要替换的板书", Toast.LENGTH_SHORT).show();

				}
				
			}
		});
		
		classhourPackageListView = (ListView) view1.findViewById(R.id.classhour_package_listview);
		classhourPackageListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, final int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				new AlertDialog.Builder(context)
				.setTitle("确定覆盖")
				.setMessage("确定覆盖该板书吗？")
				.setPositiveButton("确定",
				new DialogInterface.OnClickListener() {
					@Override
					public void onClick(
							DialogInterface dialog,
							int which) {
						String packageName = packageNameEdit.getText().toString().trim();
						if(checkUserNameEditNull(packageName)) {
							doUpdatePackageHttp(packageName, 
								classhourPackageList.get(arg2).get("cpa_id").toString(), 
								classhourPackageList.get(arg2).get("cpa_status").toString());
						}else{
							doUpdatePackageHttp(classhourPackageList.get(arg2).get("cpa_title").toString(), 
								classhourPackageList.get(arg2).get("cpa_id").toString(), 
								classhourPackageList.get(arg2).get("cpa_status").toString());
						}
					}
				})
				.setNegativeButton("取消",
				new DialogInterface.OnClickListener() {
					@Override
					public void onClick(
							DialogInterface dialog,
							int which) {
						dialog.dismiss();
					}
				}).show();
			}
		});
		popup.showAsDropDown(view);
	}
	
	/**
	 * 教师新增课程包
	 */
	private void doSaveCoursePackageHttp(String coursePackageName) {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "ClasshourPackage.insert");
		map.put("args[cl_id]", app.getClId());
		map.put("args[co_id]", app.getCoId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		try {
			map.put("args[cpa_title]",
					URLEncoder.encode(coursePackageName, "utf-8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		map.put("args[cpa_status]", 1);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new UploadFile(temp, new PackageUploadHandler(context,
				MyContants.DO_HTTP_ISERT_PACKAGR), map, dbName);
	}
	
	/**
	 * ======= 得到课程包的信息
	 */
	private void doPackageListHttp() {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "ClasshourPackage.lists");
		map.put("args[cl_id]", app.getClId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new PackageHandler(context,
				MyContants.DO_HTTP_TEACH_PACKAGE_LIST), map);
	}
	
	/**
	 * 教师更新课程包
	 */
	private void doUpdatePackageHttp(String coursePackageName,
			String cpa_id, String cpa_status) {
		((Activity) context).showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "ClasshourPackage.update");
		map.put("args[cpa_id]", cpa_id);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("args[cpa_status]", cpa_status);
		try {
			map.put("args[cpa_title]",
					URLEncoder.encode(coursePackageName, "utf-8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new UploadFile(temp, new PackageUploadHandler(context,
				MyContants.DO_HTTP_TEACH_UPDATE_PACKAGE), map, dbName);
	}
	
	/**
	 * 解析课程包数据
	 * */
	private void doPackageListSucces(String result) {
		try {
			JSONArray packageList = new JSONArray(result);
			for (int i = 0; i < packageList.length(); i++) {
				JSONObject jo = packageList.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("cpa_id", jo.get("cpa_id").toString());
				map.put("cpa_title", jo.get("cpa_title").toString());
				map.put("cpa_status", jo.get("cpa_status").toString());
				classhourPackageList.add(map);
			}
			if(classhourPackageList.size() > 0){
				PackageAdapter packageAdapter = new PackageAdapter(context, classhourPackageList, 0, 0);
				classhourPackageListView.setAdapter(packageAdapter);
			}
		} catch (JSONException e) {
			e.printStackTrace();
		}
	}
	
	class PackageHandler extends HttpHandler {
		
		String tag;
		
		public PackageHandler(Context context, String tag) {
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
				if (tag.equals(MyContants.DO_HTTP_TEACH_PACKAGE_LIST)) {
					doPackageListSucces((String) msg.obj);
				}
			}
		}
	}
	class PackageUploadHandler extends UploadHandler {
		String tag;

		public PackageUploadHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
			
		}

		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);
			// popupWindowPackageView
			((Activity) context).dismissDialog(MyContants.HTTP_WAITING);
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				clearPopup();
			}
		}

	}
	
	class PackageAdapter extends BaseAdapter {

		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;

		public PackageAdapter(Context context,
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
			if(0 == tag) {
				view = addPackageView(position, convertView, parent);
			}
			
			
			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成课程包列表视图
		private View addPackageView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.package_listview_item, null);
				
				holder.packageName = (TextView) convertView
						.findViewById(R.id.package_name);
				
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("cpa_title").toString();
			if(biaotiString.length() > 15) {
				biaotiString = biaotiString.substring(0, 15) + "...";
			}
			holder.packageName.setText(biaotiString);			
			
			return convertView;

		}
		
		class Holder {
			TextView packageName;
		}
		
	}
	
	/**
	 * 判断输入框是否为空
	 * 
	 * @param name
	 * @return boolean
	 */
	private boolean checkUserNameEditNull(String name) {

		if (name.equals("")) {
			return false;
		}
		return true;
	}
	
	
}
