package dkt.teacher.activity;

import java.io.ByteArrayOutputStream;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.LinkedList;
import java.util.List;
import java.util.Random;

import org.achartengine.ChartFactory;
import org.achartengine.chart.BarChart.Type;
import org.achartengine.model.CategorySeries;
import org.achartengine.model.XYMultipleSeriesDataset;
import org.achartengine.renderer.SimpleSeriesRenderer;
import org.achartengine.renderer.XYMultipleSeriesRenderer;
import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import dkt.teacher.net.GetIp;
import dkt.teacher.net.UploadHandler;
import dkt.teacher.net.UploadHomework;
import dkt.teacher.view.DeputyView;
import dkt.teacher.view.HomeworkAddView;
import dkt.teacher.MyApp;
import dkt.teacher.MyContants;
import dkt.teacher.R;
import dkt.teacher.database.MyHomeWorkServer;
import dkt.teacher.listener.MyListener;
import dkt.teacher.model.Homework;
import dkt.teacher.net.HttpApacheMapPostThread;
import dkt.teacher.net.HttpHandler;
import dkt.teacher.util.Md5Util;
import dkt.teacher.util.MoveView;
import dkt.teacher.util.ViewUtil;
import dkt.teacher.util.bitmap.FinalBitmap;
import dkt.teacher.view.MyLayout;
import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.drawable.BitmapDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.os.Parcelable;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v4.view.ViewPager.OnPageChangeListener;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.PopupWindow;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class CorrectHomeworkActivity extends Activity implements MyListener{

	private Context context;
	private String actId;
	private String ap_id;
	private String to_id;
	private String student_id;
	private String h_id;
	private boolean isVisi = true;
	private String biaoti;
	private String hd_id;
	private int StudentNums = 50;
	
	MyLayout mylayout;
	private GridView homeworkListView;
	private ViewPager viewPager;
	private DeputyView myDeputyView;
	private HomeworkAddView myHomeworkAddView;
	private HomeworkAdapter listViewAdapter;
	private int isTheSam = 0; // 前一页的页码
	private LinkedList<View> views = new LinkedList<View>();
	private MyAdapter viewPagerAdapter;
	private boolean isCheckStudent = false;
	
	private int pageNum = 0;
	private int pageNums = 1;
	MyHomeWorkServer myHomeWorkServer; // 简答题数据库
	String homeworkDbName = "1";
	private boolean isBegin = false; // 是否开始检查作业
	private String subId; // 数据库页码
		
	List<HashMap<String, Object>> homeWorkList = 
		new ArrayList<HashMap<String, Object>>();
	List<String> imgList = new ArrayList<String>();
	
	private FinalBitmap fb;
	
	private Bitmap bitmap;
	private String imageUrlString;
	private String imageUrlStrings;
	
	private Thread downLoadThread;
	private static final int DOWN_IMAGE_OK = 3;
	private Handler mHandler = new Handler() {
		public void handleMessage(Message msg) {
			switch (msg.what) {
			
			case DOWN_IMAGE_OK:				
				myDeputyView.setBackgroundDrawable(new BitmapDrawable(bitmap));
				break;
			default:
				break;
			}
		};
	};
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.correcthomework);
        
        initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}

	private void initView() {
		// TODO Auto-generated method stub
		context = CorrectHomeworkActivity.this;
		fb = new FinalBitmap(context).init();
		myHomeworkAddView = new HomeworkAddView(context);
		
		actId = this.getIntent().getStringExtra("act_id");
		ap_id = this.getIntent().getStringExtra("ap_id");
		biaoti = this.getIntent().getStringExtra("biaoti");
		
		TextView title = (TextView) findViewById(R.id.homework_title_text);
		title.setText(biaoti);
		
		viewPager = (ViewPager) findViewById(R.id.homework_view_pager);
		homeworkListView = (GridView) findViewById(R.id.homework_listview);
		myDeputyView = (DeputyView) findViewById(R.id.homework_deputyview);
		
		mylayout = (MyLayout) findViewById(R.id.homework_change_layout_my);
		mylayout.setMyListener(this);
		
		
		doActivityDetailHttp();
		doHomeworkStudentHttp();
	}

	private void addFun() {
		// TODO Auto-generated method stub
		
		// 返回
		findViewById(R.id.homework_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				finish();
			}
		});	
		
		// 学生列表的收缩
		findViewById(R.id.homework_students_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				RelativeLayout relativeB = (RelativeLayout) findViewById(R.id.homework_students_re);
				if(isVisi) {
					relativeB.setVisibility(View.VISIBLE);
					MoveView.doHorizontalMove(relativeB, -(relativeB.getWidth()), 0);
					isVisi = false;
				}else {
					MoveView.doHorizontalMove(relativeB, 0, -(relativeB.getWidth()));
					relativeB.setVisibility(View.GONE);
					isVisi = true;
				}
			}
		});
		
		// 提交
		findViewById(R.id.homework_submit_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if(isCheckStudent) {
					MyPopupWindow correctPopu = new MyPopupWindow();
					View v1 = findViewById(R.id.main_focus);
					correctPopu.showPopup(v1);
				}  
			}
		});
		
		// 统计
		findViewById(R.id.homework_tongji_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				doHomeworkStatsHttp();
			}
		});
		
		// 画笔
		findViewById(R.id.homework_pen).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myDeputyView.isClean(false);   
				
			}
		});	
		
		// 橡皮
		findViewById(R.id.homework_eraser).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myDeputyView.isClean(true);   
			}
		});	
		
		// 上一页
		findViewById(R.id.note_book_pre_page).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if(isBegin) {
					updateSubPage();
					if(pageNum == 0){
						
					}else{
						pageNum = pageNum - 1;
						uploadImg(pageNum);
						loadSubPage(to_id);
					}
				}
				
			}
		});
		
		// 下一页
		findViewById(R.id.note_book_next_page).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if(isBegin) {
					updateSubPage();
					pageNum = pageNum + 1;
					uploadImg(pageNum);
					if(pageNum == pageNums) {
						creatNewSubAnswer(to_id);
						pageNums = pageNums + 1;
					}
					loadSubPage(to_id);
				}
				
			}
		});
	}
	
	/**
	 * 获取客观题对错
	 * */
	private String getHStat() {
		String stat = "";
		JSONObject jo = new JSONObject();
		for(int i=0;i<homeWorkList.size();i++) {
			String toAnswer = homeWorkList.get(i).get("to_answer").toString();
			if(!homeWorkList.get(i).get("to_type").toString().equals("3") 
					&& !homeWorkList.get(i).get("to_type").toString().equals("5") 
					&& isCheckStudent) {
				try {
					JSONArray jesonArry = new JSONArray(toAnswer);
					int count = jesonArry.length();
					if(count > 0) {
						toAnswer = jesonArry.getString(0);
					}
					String co_id = homeWorkList.get(i).get("to_id").toString();
					int result = 0;
					if(toAnswer.equals(
							homeWorkList.get(i).get("student_answer").toString())) {
						
						result = 1;
					}else{
						result = 0;
					}
					jo.put(co_id,  ""+result);
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
		}
		stat = jo.toString();
		return stat;
		
	}
	
	public XYMultipleSeriesRenderer getBarDemoRenderer(List<HashMap<String, Object>> homeworkStatsList) {
		XYMultipleSeriesRenderer renderer = new XYMultipleSeriesRenderer();
		SimpleSeriesRenderer r = new SimpleSeriesRenderer();
		
		r.setColor(Color.GREEN);
		
		renderer.addSeriesRenderer(r);
		
		setChartSettings(renderer, homeworkStatsList);
		return renderer;
	}
	
	private void setChartSettings(XYMultipleSeriesRenderer renderer,List<HashMap<String, Object>> homeworkStatsList) {
//		renderer.setApplyBackgroundColor(true);
//		renderer.setBackgroundColor(Color.WHITE);
		
		renderer.setChartTitle(biaoti+"(统计图)");
		renderer.setXTitle("题目");
		renderer.setYTitle("人数");
		renderer.setAxisTitleTextSize(18);
		renderer.setChartTitleTextSize(20);
		renderer.setLabelsTextSize(15);
		renderer.setLegendTextSize(15);
		
		renderer.setXAxisMin(0.5);
		renderer.setXAxisMax(10.5);
		renderer.setYAxisMin(0);
		renderer.setYAxisMax(StudentNums);
		renderer.setMargins(new int[] {100, 30, 50, 30});
		renderer.setDisplayChartValues(true);
		renderer.setBarSpacing(2);
		renderer.setShowGrid(true);
		renderer.setXLabels(0);
		for (int i = 0; i < homeworkStatsList.size(); i++) {
			renderer.addTextLabel(i+1, "第"+homeworkStatsList.get(i).get("num").toString()+"题");
		}
	}
	
	private XYMultipleSeriesDataset getBarDemoDataset(List<HashMap<String, Object>> homeworkStatsList) {
		XYMultipleSeriesDataset dataset = new XYMultipleSeriesDataset();
		final int nr = homeworkStatsList.size();
		Random r = new Random();
		for (int i = 0; i < 1; i++) {
			CategorySeries series = new CategorySeries("回答正确人数");
			for (int k = 0; k < nr; k++) {
				series.add(Integer.valueOf(homeworkStatsList.get(k).get("peoples").toString()));
			}
			dataset.addSeries(series.toXYSeries());
		}
		return dataset;
	}
	
	/**
	 * 统计数据
	 */
	private void doHomeworkStatsHttp() {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.stats");
		map.put("args[act_id]", actId);
		map.put("args[c_id]", app.getcId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new corretcHandler(context,
				MyContants.DO_HTTP_HOMEWORK_STATS), map);
	}
	
	/**
	 * 学生列表
	 */
	private void doHomeworkStudentHttp() {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.listsAuth");
		map.put("args[ap_id]", ap_id);
		map.put("args[c_id]", app.getcId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new corretcHandler(context,
				MyContants.DO_HTTP_HOMEWORK_STUDENTS), map);
	}
	
	/**
	 * 活动详细
	 */
	private void doActivityDetailHttp() {
		showDialog(MyContants.HTTP_WAITING);
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
		new HttpApacheMapPostThread(temp, new corretcHandler(context,
				MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL), map);
	}
	
	/**
	 * 获取学生答案
	 */
	private void doGetStudentAnswerHttp(String stu_id, String h_id) {
		this.student_id = stu_id;
		this.h_id = h_id;
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.correct");
		map.put("args[stu_id]", stu_id);
		map.put("args[h_id]", h_id);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new corretcHandler(context,
				MyContants.DO_HTTP_STUDENT_HOMEOWRK), map);
	}
	
	/**
	 * 提交该学生作业批改情况
	 * 
	 * @param hd_status 作业答案状态2：重做4：完成
	 * */
	private void doUploadCorrectHomework(String hd_status, String hd_remark, 
			String hd_score, String hd_stat) {
		
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.setStatus");
		map.put("args[hd_id]", hd_id);
		map.put("args[stu_id]", student_id);
		map.put("args[hd_status]", hd_status);
		map.put("args[hd_persent]", "");
		map.put("args[hd_score]", hd_score);
		
		try {
			map.put("args[hd_remark]", URLEncoder.encode(hd_remark, "utf-8"));
//			map.put("args[hd_stat]", URLEncoder.encode(hd_stat, "utf-8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		map.put("skey", skey);
		map.put("args[hd_stat]", "");
		map.put("args[a_id]", userId);
		map.put("args[hd_shortanswer]", "");
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new UploadHomework(temp, new UploadHomeworkHandler(context,
				MyContants.DO_HTTP_ISERT_PACKAGR), map, homeWorkList, student_id);
	}
	
	/**
	 * 解析统计数据
	 * */
	private void doGetStatsSucces(String result) {
		/**
		 *  {"234":{"to_id":234,"peoples":0,"num":1},
		 *  "235":{"to_id":235,"peoples":1,"num":2},
		 *  "232":{"to_id":232,"peoples":1,"num":3},
		 *  "236":{"to_id":236,"peoples":0,"num":4},
		 *  "233":{"to_id":233,"peoples":1,"num":5}}
		 * */
		try {
			JSONObject jsonObject = new JSONObject(result);
			List<HashMap<String, Object>> homeworkStatsList = 
				new ArrayList<HashMap<String, Object>>();
			
			JSONArray jesonArry = jsonObject.names();
			int count = jesonArry.length();
			
			for (int i = 0; i < count; i++) {
				System.out.println(jsonObject.getString(jesonArry.get(i).toString()));
				String name = jesonArry.getString(i);
				JSONObject object = jsonObject.getJSONObject(name);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("to_id", object.get("to_id").toString());
				map.put("peoples", object.get("peoples").toString());
				map.put("num", object.get("num").toString());
				homeworkStatsList.add(map);
			}
			XYMultipleSeriesRenderer renderer = getBarDemoRenderer(homeworkStatsList);
			Intent intent = ChartFactory.getBarChartIntent(this,
					getBarDemoDataset(homeworkStatsList), renderer, Type.DEFAULT);
			startActivity(intent);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	/**
	 * 解析学生列表数据
	 * */
	private void doStudentListSucces(String result) {
		/**
		 * [{"a_id":"4","a_nickname":"\u5f20\u4e09","a_type":"1",
		 * "a_sex":"1","hd_id":"29","hd_status":"1",
		 * "a_avatar":"http:\/\/192.168.7.53:81\/AuthAvatar\/48\/default.jpg",
		 * "h_id":"68"},
		 * {"a_id":"5","a_nickname":"\u674e\u56db","a_type":"1",
		 * "a_sex":"1","a_avatar":"http:\/\/192.168.7.53:81\/AuthAvatar\/48\/default.jpg",
		 * "h_id":"68","hd_status":0}]
		 * */
		try {
			List<HashMap<String, Object>> homeworkStudentList = 
				new ArrayList<HashMap<String, Object>>();
			
			JSONArray jesonArry = new JSONArray(result);
			int count = jesonArry.length();
			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("a_id", object.get("a_id").toString());
				map.put("a_nickname", object.get("a_nickname").toString());
				map.put("hd_status", object.get("hd_status").toString());
				map.put("a_avatar", object.get("a_avatar").toString());
				map.put("h_id", object.get("h_id").toString());
				if(object.isNull("hd_id")){
				}else{
					map.put("hd_id", object.get("hd_id").toString());
					System.out.println("=====已经提交===="+object.get("hd_id").toString());
				}
				homeworkStudentList.add(map);
			}
			if(homeworkStudentList.size() > 0) {
				addStudentsView(homeworkStudentList);
				StudentNums = homeworkStudentList.size();
			}
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	/**
	 * 解析家庭作业数据
	 * 
	 * */
	private void doGetHomeworkDetailSucces(String result) {
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
			
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				ViewUtil.myToast(context, "无作业数据");
				return;
			}
			String courseListString = jsonObject.get("info").toString();
			JSONObject jsonObject1 = new JSONObject(courseListString);
			JSONObject jsonObject2 = new JSONObject(jsonObject1.get("list").toString());
			JSONArray jesonArry = new JSONArray(jsonObject2.getString("topic").toString());
			int count = jesonArry.length();
//			System.out.println("==================="+count);
			
			for (int i = 0; i < count; i++) {
				JSONObject object = jesonArry.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("to_type", object.get("to_type").toString());
				map.put("to_id", object.get("to_id").toString());
				map.put("to_title", object.get("to_title").toString());
				map.put("to_option", object.get("to_option").toString());
				map.put("path", object.get("path").toString());
				map.put("to_answer", object.get("to_answer").toString());
				map.put("student_answer", "-1");
				homeWorkList.add(map);
			}
			if(homeWorkList.size() > 0) {
				addHomeworkListView();
			}
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 解析学生作业答案
	 * */
	private void doGetStudentAnswerSucces(String result) {
		/**
		 * {"h_id":"68","a_id":"4","picture_answer":
		 * {"123":["http:\/\/192.168.7.53:81\/PictureAnswer\/123\/123\/4\/4\/123-2.png",
		 * "http:\/\/192.168.7.53:81\/PictureAnswer\/123\/123\/4\/4\/123-1.png"]},
		 * "hd_answer":"{\"125\":\"1\",\"120\":\"0\",\"121\":\"1,2,\",\"122\":\"1\",\"124\":\"0,1,\"}",
		 * "hd_persent":"0","hd_created":1373595686,"hd_updated":1377496202}
		 * */
		try {
			JSONObject jsonObject = new JSONObject(result);
			String picture_answer = jsonObject.getString("picture_answer");
			String hd_answer = jsonObject.getString("hd_answer");
			
			JSONObject picturejsonObject = new JSONObject(picture_answer);
			JSONObject hdjsonObject = new JSONObject(hd_answer);
			
			for(int i=0;i<homeWorkList.size();i++) {
				if(homeWorkList.get(i).get("to_type").toString().equals("5")) {
					homeWorkList.get(i).put("student_answer", 
							picturejsonObject.get(homeWorkList.get(i).get("to_id").toString()));
				}else{
					homeWorkList.get(i).put("student_answer", 
							hdjsonObject.get(homeWorkList.get(i).get("to_id").toString()));
				}
			}
			for(int j=0;j<homeWorkList.size();j++) {
				System.out.println("===="+homeWorkList.get(j).get("student_answer").toString());
			}
			updateHomeworkListView();
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	class UploadHomeworkHandler extends UploadHandler {
		String tag;

		public UploadHomeworkHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
			
		}

		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);
			// popupWindowPackageView
			dismissDialog(MyContants.HTTP_WAITING);
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
//				doUploadSucess((String) msg.obj);
			}
		}

	}
	
	class corretcHandler extends HttpHandler {
		
		String tag;
		
		public corretcHandler(Context context, String tag) {
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
					doGetHomeworkDetailSucces((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_HOMEWORK_STUDENTS)) {
					doStudentListSucces((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_STUDENT_HOMEOWRK)) {
					doGetStudentAnswerSucces((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_HOMEWORK_STATS)) {
					doGetStatsSucces((String) msg.obj);
				}
			}
		}
	}
	
	/**
	 * 学生列表视图
	 * */
	private void addStudentsView(final List<HashMap<String, Object>> homeworkStudentList) {
		/**
		 * map.put("a_id", object.get("a_id").toString());
				map.put("a_nickname", object.get("a_nickname").toString());
				map.put("hd_status", object.get("hd_status").toString());
				map.put("a_avatar", object.get("a_avatar").toString());
				map.put("h_id", object.get("h_id").toString());
		 * */
		GridView studentsGridView = (GridView) findViewById(R.id.homework_student_listview);
		
		studentsGridView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub //a_nickname
				// 切换学生的时候如果正好当时是主观题则保存
				if(homeWorkList.get(isTheSam).get("to_type").equals("5")) {
					updateSubPage();
				}
				if(homeworkStudentList.get(arg2).get("hd_status").toString().equals("0")) {
					ViewUtil.myToast(context, "该学生还未提交作业");
				}else if(homeworkStudentList.get(arg2).get("hd_status").toString().equals("2")) {
					ViewUtil.myToast(context, "该学生正在重做中");
				}else if(homeworkStudentList.get(arg2).get("hd_status").toString().equals("4")) {
					ViewUtil.myToast(context, "该学生作业已经批改完成");
					isBegin = true;
					TextView title = (TextView) findViewById(R.id.homework_title_text);
					title.setText(biaoti + "(" +
							homeworkStudentList.get(arg2).get("a_nickname").toString()+")");
					hd_id = homeworkStudentList.get(arg2).get("hd_id").toString();
					doGetStudentAnswerHttp(homeworkStudentList.get(arg2).get("a_id").toString(),
							homeworkStudentList.get(arg2).get("h_id").toString());
				}else{
					isBegin = true;
					TextView title = (TextView) findViewById(R.id.homework_title_text);
					title.setText(biaoti + "(" +
							homeworkStudentList.get(arg2).get("a_nickname").toString()+")");
					hd_id = homeworkStudentList.get(arg2).get("hd_id").toString();
//					homeworkDbName = "homework" + homeworkStudentList.get(arg2).get("a_id").toString()
//						+ homeworkStudentList.get(arg2).get("h_id").toString()+ ".db";
					doGetStudentAnswerHttp(homeworkStudentList.get(arg2).get("a_id").toString(),
							homeworkStudentList.get(arg2).get("h_id").toString());
				}
			}
		});
		
		HomeworkAdapter studentAdapter = new HomeworkAdapter(context, homeworkStudentList, 0, 0);
		studentsGridView.setAdapter(studentAdapter);
		
	}
	
	private void updateHomeworkListView () {
		isCheckStudent = true;
		views.clear();
		for(int i=0;i<homeWorkList.size();i++) {
			
			View view = getLayoutInflater().inflate(R.layout.homework_item, null);
			
			LinearLayout linearTemp = (LinearLayout) view.findViewById(R.id.exercise_linear);
			
			Homework myHomework = new Homework();
			
			myHomework.setToId(homeWorkList.get(i).get("to_id").toString());
			myHomework.setToOption(homeWorkList.get(i).get("to_option").toString());
			myHomework.setToTitle(homeWorkList.get(i).get("to_title").toString());
			myHomework.setToPath(homeWorkList.get(i).get("path").toString());
			myHomework.setToType(homeWorkList.get(i).get("to_type").toString());
			myHomework.setAnswer(homeWorkList.get(i).get("student_answer").toString());		
			myHomework.setToAnswer(homeWorkList.get(i).get("to_answer").toString());
			
			myHomeworkAddView.addExerciseView(linearTemp, myHomework);
			  
			views.add(view);
		}
		viewPagerAdapter.notifyDataSetChanged();
		if(homeWorkList.get(isTheSam).get("to_type").equals("5")) {
			findViewById(R.id.myview_change_re)
				.setVisibility(View.VISIBLE);
			showZhuGuan(isTheSam);
		}else{
			findViewById(R.id.myview_change_re)
				.setVisibility(View.INVISIBLE);
		}
		listViewAdapter.refresh(homeWorkList, isTheSam);
	}
	
	private void addHomeworkListView() {

		listViewAdapter = new HomeworkAdapter(context, homeWorkList, 1, 0);
		homeworkListView.setAdapter(listViewAdapter);
		
		homeworkListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				
				// 如果点击的是已经选中的则不操作
				if(isTheSam == arg2) {
				}else {
					if(homeWorkList.get(isTheSam).get("to_type").equals("5")) {
						updateSubPage();
					}
					viewPager.setCurrentItem(arg2);
				}
				
			}
		});
		
		for(int i=0;i<homeWorkList.size();i++) {
			
			View view = getLayoutInflater().inflate(R.layout.homework_item, null);
			
			LinearLayout linearTemp = (LinearLayout) view.findViewById(R.id.exercise_linear);
			
			Homework myHomework = new Homework();
			
			myHomework.setToId(homeWorkList.get(i).get("to_id").toString());
			myHomework.setToOption(homeWorkList.get(i).get("to_option").toString());
			myHomework.setToTitle(homeWorkList.get(i).get("to_title").toString());
			myHomework.setToPath(homeWorkList.get(i).get("path").toString());
			myHomework.setToType(homeWorkList.get(i).get("to_type").toString());
			myHomework.setAnswer(homeWorkList.get(i).get("student_answer").toString());		
			myHomework.setToAnswer(homeWorkList.get(i).get("to_answer").toString());
			
			myHomeworkAddView.addExerciseView(linearTemp, myHomework);
			  
			views.add(view);
		}
		
		mylayout.set_count(homeWorkList.size());
		mylayout.setChild_position(0);
		viewPagerAdapter = new MyAdapter(homeWorkList);
		viewPager.setAdapter(viewPagerAdapter);
		viewPager.setOnPageChangeListener(new MyPageChangeListener());
		viewPager.setCurrentItem(0);
		
		if(homeWorkList.size() > 0) {
			// 如果第一道题是简答题
			if(homeWorkList.get(0).get("to_type").equals("5")) {
				findViewById(R.id.myview_change_re)
					.setVisibility(View.VISIBLE);
				showZhuGuan(0);
			}else{
				findViewById(R.id.myview_change_re)
					.setVisibility(View.INVISIBLE);
			}
		}
		
	}
	
	/**
	 * 
	 * */
	private void uploadImg(int num) {
		if(num < imgList.size()) {
			imageUrlString = imgList.get(num);
			downLoadThread = new Thread(mdownImageRunnable);
			downLoadThread.start();
		}else{
			myDeputyView.setBackgroundDrawable(null);
		}
	}
	
	/**
	 * 加载写字板内容
	 * */
	private void showZhuGuan(int position) {
		
		// 每个学生的每个简答题都是一个新的的数据库
		to_id = homeWorkList.get(position).get("to_id").toString();
		homeworkDbName = "homework" + student_id + to_id + ".db";
		System.out.println(homeworkDbName);
		myHomeWorkServer = null;
		myHomeWorkServer = new MyHomeWorkServer(homeworkDbName);
		imageUrlStrings = homeWorkList.get(position).get("student_answer").toString();
		
		try {
			System.out.println(homeWorkList.get(position).get("student_answer").toString());
			JSONArray jesonArry = new JSONArray(imageUrlStrings);
			if(jesonArry.length() > 0) {
				
				imgList.clear();
				imageUrlString = jesonArry.getString(0);
				downLoadThread = new Thread(mdownImageRunnable);
				downLoadThread.start();
				
				List<Homework> myHomeworkList = new ArrayList<Homework>();
				for(int i=0;i<jesonArry.length();i++) {
					imgList.add(jesonArry.getString(i));
					Homework myHomework = new Homework();
					myHomework.setToId(to_id);
					myHomework.setToAnswerBitmap(null);
					myHomeworkList.add(myHomework);
				}
				
				// 如果数据库中没有数据或者和原数据数量不相等则更新数据库
				if(0 == myHomeWorkServer.getNumForTable(to_id)) {
					
					myHomeWorkServer.insertHomeworkList(myHomeworkList);
					
				}else if(myHomeWorkServer.getNumForTable(to_id) != jesonArry.length()) {
					
					myHomeWorkServer.clearFeedTable();
					myHomeWorkServer.insertHomeworkList(myHomeworkList);
					
				}
				
				pageNums = myHomeWorkServer.getNumForTable(to_id);
				pageNum = 0;
				loadSubPage(to_id);
			}
			
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
		
	}
	
	/**
	 * 创建主观题答案的新页
	 * */
	private void creatNewSubAnswer(String toid) {
		Homework mHomework = new Homework();
		mHomework.setToId(toid);
		mHomework.setToAnswerBitmap(null);
		myHomeWorkServer.insertHomework(mHomework);
	}
	
	/**
	 * 载入指定页面的主观题答案
	 * */
	private void loadSubPage(String toid) {
		myDeputyView.clear();
		Homework mHomework = new Homework();
		mHomework = myHomeWorkServer.searchSubjectiveForPage(toid, pageNum);
		if(null != mHomework.getToAnswerBitmap()) {
			myDeputyView.insertImg(Bytes2Bimap(mHomework.getToAnswerBitmap()));
		}
		subId = mHomework.getSubId();
		setPageNum();
	}
	
	/**
	 * 更新指定的主观题答案
	 * */
	private void updateSubPage() {

		Homework mHomework = new Homework();
		mHomework.setSubId(subId);
		
		mHomework.setToAnswerBitmap(myDeputyView.getBitmapForByte());
		mHomework.setToTeacherBitmap(Bitmap2Bytes(getViewBitmap(myDeputyView)));
//		Bitmap myBitmap = getViewBitmap(myDeputyView);
//		FileOutputStream out;
//		try {
//			
//			out = new FileOutputStream("/sdcard/Dkt/"+pageNum+".png");
//			myBitmap.compress(Bitmap.CompressFormat.PNG, 100, out);
//			
//		} catch (FileNotFoundException e) {
//			// TODO Auto-generated catch block
//			e.printStackTrace();
//		}
		myHomeWorkServer.updateSubjective(mHomework);
		  
	}
	
	private Bitmap getViewBitmap(View v) {
		v.clearFocus(); // 清除视图焦点
		v.setPressed(false);// 将视图设为不可点击

		boolean willNotCache = v.willNotCacheDrawing(); // 返回视图是否可以保存他的画图缓存
		v.setWillNotCacheDrawing(false);

		v.setDrawingCacheBackgroundColor(context.getResources().getColor(R.color.white)); // 设置绘图背景颜色

		v.buildDrawingCache(); 
		Bitmap cacheBitmap = v.getDrawingCache(); // 将绘图缓存得到的,注意这里得到的只是一个图像的引用
		if (cacheBitmap == null) {
			return null;
		}
		int scaleHeight=693;  
		int scaleWidth=573;
		
		Matrix matrix = new Matrix();
        matrix.reset();  
        float mWidth = (float)scaleWidth / (float)cacheBitmap.getWidth(); // target缩放到的宽度   src原始宽度，这样算出来是个比率
        float mHeight = (float)scaleHeight / (float)cacheBitmap.getHeight();
        matrix.postScale(mWidth, mHeight);
        
		Bitmap bitmap = Bitmap.createBitmap(cacheBitmap, 0, 0, cacheBitmap.getWidth(),
				cacheBitmap.getHeight(),matrix, true);  // 将位图实例化
		v.destroyDrawingCache();// 释放位图内存
		v.setWillNotCacheDrawing(willNotCache);// 返回以前缓存设置

		return bitmap;
	}
	
	/**
	 * 页码设置
	 * */
	private void setPageNum() {
		TextView mTextView = (TextView) findViewById(R.id.note_book_page_txt);
		String mtext = "第" + (pageNum+1) +"/" + pageNums + "页";
		mTextView.setText(mtext);
	}
	
	/**
	 * 二进制转图片
	 * */
	private Bitmap Bytes2Bimap(byte[] b){
	    if(b.length!=0){
	    	return BitmapFactory.decodeByteArray(b, 0, b.length);
	    }
	    else {
	    	return null;
	    }
	}
	
	/**
	 * bitmap转byte[]
	 * */
	private byte[] Bitmap2Bytes(Bitmap bm){
		
		ByteArrayOutputStream baos = new ByteArrayOutputStream();
		bm.compress(Bitmap.CompressFormat.PNG, 100, baos);
		return baos.toByteArray();
		
	}
	
	private Runnable mdownImageRunnable = new Runnable() {
		@Override
		public void run() {
			URL imageUrl = null;
			GetIp getip = new GetIp(context);
			String service_ip = getip.servise_ip;
			try {
				imageUrl = new URL(MyContants.HTTP_PREFIX + service_ip + imageUrlString);
				System.out.println(MyContants.HTTP_PREFIX + service_ip + imageUrlString);
			} catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
			}
			try {
				bitmap = null;
				HttpURLConnection conn = (HttpURLConnection) imageUrl
						.openConnection();
				conn.connect();
				InputStream is = conn.getInputStream();
				bitmap = BitmapFactory.decodeStream(is);
				System.out.println(bitmap.getWidth());
				is.close();
				mHandler.sendEmptyMessage(DOWN_IMAGE_OK);
			} catch (Exception e) {
				// TODO: handle exception
				e.printStackTrace();
//				mHandler.sendEmptyMessage(DOWN_IMAGE_NO);
			}
		}
	};
	
	private class MyAdapter extends PagerAdapter {
		
		List<HashMap<String, Object>> list;
		public MyAdapter(List<HashMap<String, Object>> list) {
			this.list = list;
		}
		
		@Override
		public int getCount() {
			return list.size();
		}

		@Override
		public Object instantiateItem(View arg0, int arg1) {
			((ViewPager) arg0).addView(views.get(arg1),0);
			return views.get(arg1);
		}

		@Override
		public void destroyItem(View arg0, int arg1, Object arg2) {
			View view = (View)arg2;
		     ((ViewPager) arg0).removeView(view);
		     view =null;
		}

		@Override
		public boolean isViewFromObject(View arg0, Object arg1) {
			return arg0 == arg1;
		}

		@Override
		public void restoreState(Parcelable arg0, ClassLoader arg1) {

		}
		
		@Override
		public int getItemPosition(Object object) {
			// TODO Auto-generated method stub
			return POSITION_NONE;
		}
		
		@Override
		public Parcelable saveState() {
			return null;
		}

		@Override
		public void startUpdate(View arg0) {

		}

		@Override
		public void finishUpdate(View arg0) {

		}
	}
	
	private class MyPageChangeListener implements OnPageChangeListener {

		public void onPageSelected(int position) {

//			isN = position;
			mylayout.setChild_position(position);
			
			if(!homeWorkList.get(isTheSam).get("to_type").toString().equals("5")) {
				String toid = homeWorkList.get(isTheSam).get("to_id").toString();
				Homework mHomework = new Homework();
				mHomework.setToId(toid);
				mHomework.setToType(homeWorkList.get(isTheSam).get("to_type").toString());
				
			}else{
				
				myDeputyView.clear();
			}
			
			// 题目类型为5 且  不是同一个题目
			if(homeWorkList.get(position).get("to_type").equals("5")) {
				
				findViewById(R.id.myview_change_re)
				.setVisibility(View.VISIBLE);
				showZhuGuan(position);
				
			}else{
				findViewById(R.id.myview_change_re)
				.setVisibility(View.INVISIBLE);
			}
			listViewAdapter.refresh(homeWorkList, position); // 在这里通过position获取views里面的view  再通过view获取view中的答案
			isTheSam = position;
		}

		public void onPageScrollStateChanged(int arg0) {
			
		}

		public void onPageScrolled(int arg0, float arg1, int arg2) {
			
		}
	}
	
	class HomeworkAdapter extends BaseAdapter {
		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;
		
		public HomeworkAdapter(Context context,
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
				view = addStudentView(position, convertView, parent);
			}else if(1 == tag) {
				view = addHomeworkView(position, convertView, parent);
			}
			
			return view;
		}
		
		// 刷新适配器
		public void refresh(List<HashMap<String, Object>> list, int i) {
			this.list = list;
			this.i = i;
			this.notifyDataSetChanged();
		}
		
		// 生成学生列表视图
		private View addStudentView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {//     android:background="@drawable/yuwen"
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.homework_student_item, null);
				holder.homeworkName = (TextView) convertView
						.findViewById(R.id.student_name);
				holder.studentImg = (ImageView) convertView
						.findViewById(R.id.student_img);
				holder.studentReadImg = (ImageView) convertView
						.findViewById(R.id.student_read_img);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			fb.display(holder.studentImg, list.get(position).get("a_avatar").toString());
			holder.homeworkName.setText(list.get(position).get("a_nickname").toString());

			if(!"0".equals(list.get(position).get("hd_status").toString())) {
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.green));
			}else{
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.black));
			}
			
			if("4".equals(list.get(position).get("hd_status").toString())) {
				holder.studentReadImg.setVisibility(View.VISIBLE);
			}else{
				holder.studentReadImg.setVisibility(View.INVISIBLE);
			}
			return convertView;

		}
		
		// 生成课程列表视图
		private View addHomeworkView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {//     android:background="@drawable/yuwen"
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.homework_listview_item, null);
				holder.homeworkName = (TextView) convertView
						.findViewById(R.id.homework_text);
				holder.homeworkRela = (RelativeLayout) convertView
						.findViewById(R.id.homework_change_re);	
				holder.rwImg = (ImageView) convertView
						.findViewById(R.id.rw_img);
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String homeworkNameString = (position+1)+"、";
			String homeworkType = list.get(position).get("to_type").toString();
			if(homeworkType.equals("1")) {
				homeworkNameString = homeworkNameString + "单选";
			}else if(homeworkType.equals("2")) {
				homeworkNameString = homeworkNameString + "多选";
			}else if(homeworkType.equals("3")) {
				homeworkNameString = homeworkNameString + "填空";
			}else if(homeworkType.equals("4")) {
				homeworkNameString = homeworkNameString + "判断";
			}else if(homeworkType.equals("5")) {
				homeworkNameString = homeworkNameString + "简答";
			}
			
			holder.homeworkName.setText(homeworkNameString);
			
			if(position == i) {
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.green));
				holder.homeworkRela.setBackgroundResource(R.color.beige);
				homeworkListView.smoothScrollToPositionFromTop(position, 20);
//				fToid = list.get(position).get("to_id").toString();
			}else{
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.white));
				holder.homeworkRela.setBackgroundResource(R.drawable.course_listview_item_bg_click);
			}
			
			// 单选 多选 判断题的对错图标显示
			String toAnswer = list.get(position).get("to_answer").toString();
			if(!list.get(position).get("to_type").toString().equals("3") 
					&& !list.get(position).get("to_type").toString().equals("5") 
					&& isCheckStudent) {
				try {
					JSONArray jesonArry = new JSONArray(toAnswer);
					int count = jesonArry.length();
					if(count > 0) {
						toAnswer = jesonArry.getString(0);
					}
					if(toAnswer.equals(
							list.get(position).get("student_answer").toString())) {
						holder.rwImg.setBackgroundResource(R.drawable.work_r);
					}else{
						holder.rwImg.setBackgroundResource(R.drawable.work_w);
					}
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
			}
			return convertView;

		}
		
		class Holder {
			TextView homeworkName, classHourName, lessonName;
			ImageView studentImg, studentReadImg, rwImg;
			RelativeLayout homeworkRela;
		}

	}
	
	class MyPopupWindow{
		
		PopupWindow popup;
		TextView fenshuTextView;
		String scro = "90";
		
		public MyPopupWindow() {
			
		}
		
		public void clearPopup() {
			if (popup != null && popup.isShowing()) {
				popup.dismiss();
				popup = null; 
			}
		}
		public void showPopup(View v) {
			
			View view = LayoutInflater.from(context).inflate(
					R.layout.correctpopu, null);
			popup = new PopupWindow(view, LayoutParams.WRAP_CONTENT,
					LayoutParams.WRAP_CONTENT);
			popup.setBackgroundDrawable(new BitmapDrawable());
			popup.setOutsideTouchable(true);
			popup.setTouchable(true);
			popup.setFocusable(true);
			popup.showAtLocation(v, Gravity.CENTER, 0, 0);
			fenshuTextView = (TextView) view.findViewById(R.id.seekbar_text);
			SeekBar seekBar = (SeekBar) view
				.findViewById(R.id.correct_popu_seekbar);
			final RadioGroup radioGroup = (RadioGroup) view
				.findViewById(R.id.correct_popu_radiogroup);
			final EditText correctEt = (EditText) view.findViewById(R.id.correct_popu_edit);
			seekBar.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
				
				@Override
				public void onStopTrackingTouch(SeekBar seekBar) {
					// TODO Auto-generated method stub
					
				}
				
				@Override
				public void onStartTrackingTouch(SeekBar seekBar) {
					// TODO Auto-generated method stub
					
				}
				
				@Override
				public void onProgressChanged(SeekBar seekBar, int progress,
						boolean fromUser) {
					// TODO Auto-generated method stub
					if(progress >= 60) {
						fenshuTextView.setTextColor(context.getResources().getColor(R.color.green));
					}else{
						fenshuTextView.setTextColor(context.getResources().getColor(R.color.red));
						
					}
					scro = ""+progress;
					fenshuTextView.setText(progress+"分");
				}
			});
			
			// 提交
			view.findViewById(R.id.correct_popu_tijiao).setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					if(homeWorkList.get(isTheSam).get("to_type").equals("5")) {
						updateSubPage();
					}
					
					String correctString = correctEt.getText().toString().trim();
					
					int selectIndex = -1;
					int count = radioGroup.getChildCount();
					for (int i = 0; i < count; i++) {
						RadioButton radioBtn = (RadioButton) radioGroup.getChildAt(i);
						if (radioBtn.isChecked()) {
							selectIndex = i;
							break;
						}

					}
					if(selectIndex == -1) {
						Toast.makeText(context, "请确定该学生作业是否通过或者重做", Toast.LENGTH_SHORT).show();
					}else{
						String hd_status = "";
						if(selectIndex == 0){
							hd_status = "4";
						}else if(selectIndex == 1) {
							hd_status = "2";
						}
						doUploadCorrectHomework(hd_status, correctString, 
								scro, getHStat());
						popup.dismiss();
					}
					System.out.println("correctString===="+correctString);					
					System.out.println("getHStat===="+getHStat());
					System.out.println("scro========"+scro);
					System.out.println("selectIndex========"+selectIndex);
				}
			});
			
		}
	}
	
	/**
	 * 等待框
	 */
	protected Dialog onCreateDialog(int id) {
		ProgressDialog dialog = null;
		switch (id) {
		case MyContants.HTTP_WAITING:
			dialog = new ProgressDialog(this);
			dialog.setMessage("系统正在加载，请稍等....");
			break;
		}

		return dialog;
	}

	@Override
	public void showPopuView(int position) {
		// TODO Auto-generated method stub
		if(isCheckStudent) {
			MyPopupWindow correctPopu = new MyPopupWindow();
			View v = findViewById(R.id.main_focus);
			correctPopu.showPopup(v);
		}
		
	}
}
