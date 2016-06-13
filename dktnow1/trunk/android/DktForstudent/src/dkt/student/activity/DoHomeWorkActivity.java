package dkt.student.activity;

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
import org.apache.http.HttpStatus;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.activity.ClassHourActivity.DownHandler;
import dkt.student.database.MyHomeWorkServer;
import dkt.student.database.ResourceServer;
import dkt.student.listener.MyListener;
import dkt.student.model.Homework;
import dkt.student.model.Resource;
import dkt.student.net.DownLoadHandler;
import dkt.student.net.DownLoadHomeworkP;
import dkt.student.net.HttpApacheMapPostThread;
import dkt.student.net.HttpHandler;
import dkt.student.net.UploadHandler;
import dkt.student.net.UploadHomework;
import dkt.student.util.Md5Util;
import dkt.student.util.ViewUtil;
import dkt.student.view.DeputyView;
import dkt.student.view.HomeworkAddView;
import dkt.student.view.HorizontalListView;
import dkt.student.view.MyLayout;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.net.Uri;
import android.os.Bundle;
import android.os.Message;
import android.os.Parcelable;
import android.provider.MediaStore;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v4.view.ViewPager.OnPageChangeListener;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.View.OnTouchListener;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.PopupWindow;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.LinearLayout.LayoutParams;

public class DoHomeWorkActivity extends Activity implements MyListener{

	private String actId;
	private Context context;
	
	List<HashMap<String, Object>> homeWorkList = 
		new ArrayList<HashMap<String, Object>>();
	MyLayout mylayout;
	private GridView homeworkListView;
	private ViewPager viewPager;
	private LinkedList<View> views = new LinkedList<View>(); // viewpage的view集合
	private HomeworkAdapter listViewAdapter;
	private String ap_id;
	private HomeworkAddView myHomeworkAddView;
	private int isTheSam = 0; // 前一页的页码
	private int isN = 0; //当前页的页码
	private String homeworkDbName;
	private String fToid = "";
	
	MyHomeWorkServer myHomeWorkServer;
	
	
	private DeputyView myDeputyView;
	private int pageNum = 0;
	private int pageNums = 0;
	private String toids;
	private String subId;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.dohomework);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.homework_back_btn));
	}
	/**
	 * 页面初始化
	 * */
	private void initView() {
		// TODO Auto-generated method stub
		context = DoHomeWorkActivity.this;
		Intent intent = this.getIntent();
		String title = intent.getStringExtra("biaoti");
		actId = intent.getStringExtra("act_id");
		ap_id = intent.getStringExtra("ap_id");
		
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.homework_back_btn));
		
		homeworkDbName = "homework" + actId + ".db";
		myHomeWorkServer = new MyHomeWorkServer(homeworkDbName);
				
		TextView titleTextView = (TextView) findViewById(R.id.homework_title_text);
		titleTextView.setText(title);
		
		myHomeworkAddView = new HomeworkAddView(context);
		
		viewPager = (ViewPager) findViewById(R.id.homework_view_pager);
		homeworkListView = (GridView) findViewById(R.id.homework_listview);
		
		myDeputyView = (DeputyView) findViewById(R.id.homework_deputyview);
		
		
		doActivityDetailHttp();
	}
	
	/**
	 * 页面点击事件
	 * */
	private void addFun() {
		// TODO Auto-generated method stub
		// 返回
		findViewById(R.id.homework_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				finish();
			}
		});	
		
		// 完成提交
		findViewById(R.id.homework_submit_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				new AlertDialog.Builder(context)
				.setTitle("确定提交")
				.setMessage("确定完成提交该作业吗？")
				.setPositiveButton("确定",
				new DialogInterface.OnClickListener() {
					@Override
					public void onClick(
							DialogInterface dialog,
							int which) {
						saveMsg();
						doUploadHomework();
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
		
		// 相机
		findViewById(R.id.homework_camera).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent intent = new Intent(android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
				startActivityForResult(intent, MyContants.RESOURCE_IMG);
			}
		});
		findViewById(R.id.note_book_pre_page).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				updateSubPage();
				if(pageNum == 0){
					
				}else{
					myDeputyView.clear();
					pageNum = pageNum - 1;
					loadSubPage(toids);
				}
			}
		});
		findViewById(R.id.note_book_next_page).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				updateSubPage();
				pageNum = pageNum + 1;
				myDeputyView.clear();
				if(pageNum == pageNums) {
					creatNewSubAnswer(toids);
					pageNums = pageNums + 1;
				}
				loadSubPage(toids);
			}
		});
	}
	
	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		// TODO Auto-generated method stub
		super.onActivityResult(requestCode, resultCode, data);
		
		if (resultCode == RESULT_OK) {
			
			// 拍照保存完成后
			if(requestCode == MyContants.RESOURCE_IMG) {
				String imgPath = getAbsoluteImagePath(data.getData());
				inserImg(getSmallBitmap(imgPath));
			}
		}
	}
	
	/**
	 * 计算图片的缩放值
	 * */
	public static int calculateInSampleSize(BitmapFactory.Options options,int reqWidth, int reqHeight) {
	    final int height = options.outHeight;
	    final int width = options.outWidth;
	    int inSampleSize = 1;

	    if (height > reqHeight || width > reqWidth) {
	             final int heightRatio = Math.round((float) height/ (float) reqHeight);
	             final int widthRatio = Math.round((float) width / (float) reqWidth);
	             inSampleSize = heightRatio < widthRatio ? heightRatio : widthRatio;
	    }
	    
	    return inSampleSize;
	}
	
	/**
	 * 根据路径获得图片并压缩，返回旋转90度的bitmap用于显示
	 * */ 
	public static Bitmap getSmallBitmap(String filePath) {
		
        final BitmapFactory.Options options = new BitmapFactory.Options();
        options.inJustDecodeBounds = true;
        BitmapFactory.decodeFile(filePath, options);

	    options.inSampleSize = 4;
	    options.inJustDecodeBounds = false;
	    
	    int scaleHeight=576;
        int scaleWidth=768;

	    Matrix matrix = new Matrix();
	    matrix.reset();  
	    matrix.setRotate(90);  
	    

	    Bitmap myBitmap = BitmapFactory.decodeFile(filePath, options);
	    float mWidth = (float)scaleWidth / (float)myBitmap.getWidth(); // target缩放到的宽度   src原始宽度，这样算出来是个比率
        float mHeight = (float)scaleHeight / (float)myBitmap.getHeight();
        matrix.postScale(mWidth, mHeight);
	    System.out.println(myBitmap.getWidth()+"====myBitmap.getWidth()="+myBitmap.getHeight());
	    myBitmap = Bitmap.createBitmap(myBitmap, 0, 0, myBitmap.getWidth(),
	    		myBitmap.getHeight(),matrix, true);  
	    
	    return myBitmap;
	}

	/**
     *  获取uri的绝对路径
     *  @param uri
	 *  @return String
     * */
    protected String getAbsoluteImagePath(Uri uri) 
    {
        
         String [] proj={MediaStore.Images.Media.DATA};
         Cursor cursor = managedQuery( uri, proj, null, null, null);                
         int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA);
         cursor.moveToFirst();
         return cursor.getString(column_index);
         
     }
    
	/**
	 * 活动详细
	 */
	private void doActivityDetailHttp() {
		
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.doHomework");
		map.put("args[ap_id]", ap_id);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new homeworkHandler(context,
				MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL), map);
		
	}
	
	/**
	 * 学生提交作业
	 * */
	private void doUploadHomework() {
		
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Homework.insert");
		map.put("args[h_id]", ap_id);
		try {
			map.put("args[hd_answer]", URLEncoder.encode(getAllAnswer(), "utf-8"));
		} catch (UnsupportedEncodingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("args[hd_use_time]", "");
		map.put("args[hd_persent]", "");
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		List<Homework> myHomeworks = myHomeWorkServer.searchSubjectiveForAll();
		new UploadHomework(temp, new UploadHomeworkHandler(context,
				MyContants.DO_HTTP_ISERT_PACKAGR), map, myHomeworks);
	}
	
	/**
	 * 学生获取客观题答案
	 * */
	private String getAllAnswer() {
		String myAnswer = "";
		List<Homework> mHomeworks = myHomeWorkServer.getToAnswerForAll();
		
		JSONObject jo = new JSONObject();
		try {
			if(mHomeworks.size() > 0) {
				for(int i=0;i<mHomeworks.size();i++) {
					String toid = mHomeworks.get(i).getToId();
					String answer = mHomeworks.get(i).getAnswer();
					jo.put(toid + "", answer + "");
				}
				myAnswer = jo.toString();
				System.out.println("==========myAnswer==========="+myAnswer);
			}
			
		} catch (Exception e) {
			// TODO: handle exception
		}

		return myAnswer;
	}
	
	/**
	 * 保存当前页面上的答案
	 * */
	private void saveMsg() {
		
		if(!homeWorkList.get(isN).get("to_type").toString().equals("5")) {
			String toid = homeWorkList.get(isN).get("to_id").toString();
			Homework mHomework = new Homework();
			mHomework.setToId(toid);
			mHomework.setToType(homeWorkList.get(isN).get("to_type").toString());
			LinearLayout linear = (LinearLayout) views.get(isN).findViewById(R.id.exercise_linear);
			
			HashMap<String, Object> map = myHomeworkAddView.getExerciseViewValue(
					linear, mHomework, isN);
			mHomework.setAnswer(map.get("answer").toString());
			myHomeWorkServer.updateHomework(mHomework);
		}else{
			updateSubPage();
		}
		
	}
	
	/**
	 * 解析作业数据
	 * 
	 * */
	private void doGetClassworkDetailSucces(String result) {
		/**
		 * {"status":1,"info":{"list":
		 * {"act_id":"358","act_title":"sdfsdfsdfsdfsdfsdfdsf",
		 * "act_rel":"229,230,231","act_type":"1",
		 * "c_id":",4,","cro_id":"","act_is_published":"1",
		 * "co_id":"6","act_note":"","attachment":[],
		 * "topic":[
		 * {"to_id":"229","a_id":"50","s_id":"3",
		 * "to_title":"%26lt%3Bp%26gt%3Ba%26lt%3B%2Fp%26gt%3B",
		 * "to_type":"1","to_option":"0,1,2,3","to_answer":"[\"0\"]",
		 * "to_note":"","to_right_peoples":"0","to_peoples":"49",
		 * "to_created":"1377157487","to_updated":"0","to_deleted":"0",
		 * "path":"http:\/\/211.103.210.25\/apps\/Uploads\/GenerationTopic\/Image\/229.png"},
		 * {"to_id":"230","a_id":"50","s_id":"3","to_title":"%26lt%3Bp%26gt%3Badfadf%26lt%3B%2Fp%26gt%3B","to_type":"2","to_option":"0,1,2,3","to_answer":"[\"0,1,2\"]","to_note":"","to_right_peoples":"0","to_peoples":"50","to_created":"1377157497","to_updated":"0","to_deleted":"0","path":"http:\/\/211.103.210.25\/apps\/Uploads\/GenerationTopic\/Image\/230.png"},{"to_id":"231","a_id":"50","s_id":"3","to_title":"%26lt%3Bp%26gt%3Bdfsdfsdf%26lt%3B%2Fp%26gt%3B","to_type":"4","to_option":"0,1","to_answer":"[\"0\"]","to_note":"","to_right_peoples":"0","to_peoples":"48","to_created":"1377157504","to_updated":"0","to_deleted":"0","path":"http:\/\/211.103.210.25\/apps\/Uploads\/GenerationTopic\/Image\/231.png"}]}},"picture_answer":[],"ad_id":"53","ad_answer":"{\"229\":\"1\",\"231\":\"1\",\"230\":\"0,1,2\"}","ad_status":"2"}		 * */
		
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
				homeWorkList.add(map);
				if(!object.get("to_type").toString().equals("5") && myHomeWorkServer.getToAnswerForId(object.get("to_id").toString()) == null) {
					Homework mHomework = new Homework();
					mHomework.setToId(object.get("to_id").toString());
					mHomework.setActId(jsonObject2.getString("act_id").toString());
					mHomework.setToType(object.get("to_type").toString());
					mHomework.setToPath(object.get("path").toString());
					mHomework.setToBitmap(null);
					mHomework.setAnswer("-1");
					mHomework.setToAnswer(object.get("to_answer").toString());
					myHomeWorkServer.insertHomework(mHomework);
				}
			}
			
			if(jsonObject.isNull("ad_id")) {
				System.out.println("学生还没做");
//				isDid = true;
			}else{
				
				String adId = jsonObject.get("ad_id").toString();
				String ad_answer =  jsonObject.get("ad_answer").toString();
				String picture_answer =  jsonObject.get("picture_answer").toString();
				
				JSONObject jsonObject3 = new JSONObject(ad_answer);
				for(int j=0;j<jsonObject3.length();j++) {
					if(jsonObject3.getString(jsonObject3.names().getString(j)).equals("")) {
						System.out.println("没有答案");
					}else{
						String toid = jsonObject3.names().getString(j);
						Homework mHomework = new Homework();
						mHomework.setToId(toid);
						
						mHomework.setAnswer(jsonObject3.getString(jsonObject3.names().getString(j)));
						myHomeWorkServer.updateHomework(mHomework);
					}
				}
				
				if(!picture_answer.equals("") && picture_answer.length()>5) {
					JSONObject jsonObject4 = new JSONObject(picture_answer);
					System.out.println("==="+picture_answer);
					for(int k=0;k<jsonObject4.length();k++) {
						HashMap<String, Object> map = new HashMap<String, Object>();
						map.put("to_id", jsonObject4.names().get(k));
						map.put("img_paths", jsonObject4.getString(jsonObject4.names().get(k).toString()));
//						System.out.println(k+"简答题图片"
//								+jsonObject4.getString(jsonObject4.names().get(k).toString()));
//						//						downloadImg(map);
						JSONArray jesonArry1 = new JSONArray(jsonObject4.getString(jsonObject4.names().get(k).toString()));
						for(int a=0;a<jesonArry1.length();a++) {
							downloadImg(jsonObject4.names().get(k).toString(), 
									jesonArry1.getString(a), a);
//							System.out.println(a + "====" +jesonArry1.getString(a));
						}
						
					}
					/**
					 * {"236":["http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-3.png","http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-2.png","http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-1.png"],
					 * "232":["http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-2.png","http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-1.png","http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-3.png"]}
					
					 * 0简答题图片["http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-2.png",
					 * "http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-1.png",
					 * "http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/232\/232\/55\/55\/232-3.png"]
						1简答题图片["http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-3.png",
						"http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-2.png",
						"http:\/\/211.103.210.25\/apps\/Uploads\/PictureAnswer\/236\/236\/55\/55\/236-1.png"]

					 * */
				}
				
			}
			addHomeworkListView();
		} catch (Exception e) {
			// TODO: handle exception
		}
	}
	
	/**
	 * 下载主观题图片
	 * */
	private void downloadImg(String toid, String img_paths, int i) {
		
		new DownLoadHomeworkP(toid, img_paths, new DownHandler(context,
		"0"), i, homeworkDbName);
	}
	
	/**
	 * 下载文件的控制类
	 * 
	 * @author Administrator
	 * 
	 */
	class DownHandler extends DownLoadHandler {
		String tag;

		public DownHandler(Context context, String tag) {
			super(context, tag);
		}

		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);

			if (msg.what == MyContants.HTTP_DOWNLOAD) {
				// 正在下载
			} else if (msg.what == MyContants.HTTP_DOWNLOAD_SUCESS) {
				// 下载成功
				
			} else {
				// 下载失败
				
			}

		}

	}
	
	/**
	 * 解析提交的返回数据
	 * */
	private void doUploadSucess(String result) {
		try {
			JSONObject jsonObject = new JSONObject(result);
			String status = jsonObject.get("status").toString();
			if(status.equals("0")) {
				String errString = jsonObject.get("message").toString();
			
				ViewUtil.myToast(context, errString);
			}else if(status.equals("1")){
				ViewUtil.myToast(context, "提交成功");
			}
			finish();
		} catch (Exception e) {
			// TODO: handle exception
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
				doUploadSucess((String) msg.obj);
			}
		}

	}
	
	class homeworkHandler extends HttpHandler {
		
		String tag;
		
		public homeworkHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}
		
		@Override
		public void handleMessage(Message msg) {
			// TODO Auto-generated method stub
			super.handleMessage(msg);
			dismissDialog(MyContants.HTTP_WAITING);
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				if(tag.equals(MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL)){
					doGetClassworkDetailSucces((String) msg.obj);
				}
			}
		}
	}

	private void addHomeworkListView() {
		System.out.println("homeWorkList==="+homeWorkList.size());
		listViewAdapter = new HomeworkAdapter(context, homeWorkList, 0, 0);
		homeworkListView.setAdapter(listViewAdapter);
		
		homeworkListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				
				// 如果点击的是已经选中的则不操作
				if(isTheSam == arg2) {
					
				}else {
					
					viewPager.setCurrentItem(arg2);

				}
				
			}
		});
		
		for(int i=0;i<homeWorkList.size();i++) {
			
			View view = getLayoutInflater().inflate(R.layout.homework_item, null);
			
			LinearLayout linearTemp = (LinearLayout) view.findViewById(R.id.exercise_linear);
			
			Homework myHomework = new Homework();
			Homework mHomework = new Homework();
			
			myHomework.setToId(homeWorkList.get(i).get("to_id").toString());
			myHomework.setToOption(homeWorkList.get(i).get("to_option").toString());
			myHomework.setToTitle(homeWorkList.get(i).get("to_title").toString());
			myHomework.setToPath(homeWorkList.get(i).get("path").toString());
			myHomework.setToType(homeWorkList.get(i).get("to_type").toString());
			
			mHomework = myHomeWorkServer.getToAnswerForId(homeWorkList.get(i).get("to_id").toString());
			
			if(null != mHomework && !mHomework.getAnswer().equals("-1")) {
				myHomework.setAnswer(mHomework.getAnswer());
				myHomeworkAddView.addExerciseView(linearTemp, myHomework);
				myHomeworkAddView.setExerciseViewValue(linearTemp, myHomework, 1);
			}else{
				myHomeworkAddView.addExerciseView(linearTemp, myHomework);
			}
			views.add(view);
		}
		mylayout = (MyLayout) findViewById(R.id.homework_change_layout_my);
		mylayout.setMyListener(this);
		mylayout.set_count(homeWorkList.size());	
		viewPager.setAdapter(new MyAdapter(homeWorkList));
		viewPager.setOnPageChangeListener(new MyPageChangeListener());
		if(homeWorkList.size() > 0) {
			if(homeWorkList.get(0).get("to_type").equals("5")) {
				findViewById(R.id.myview_change_re)
				.setVisibility(View.VISIBLE);
				showZhuGuan(homeWorkList.get(0).get("to_id").toString());
			}
		}
		
	}
	
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

			isN = position;
			mylayout.setChild_position(position);
			
			// 如果 前一个视图的类型不是主观题,将答案数据保存到客观题表中
			if(!homeWorkList.get(isTheSam).get("to_type").toString().equals("5")) {
				String toid = homeWorkList.get(isTheSam).get("to_id").toString();
				Homework mHomework = new Homework();
				mHomework.setToId(toid);
				mHomework.setToType(homeWorkList.get(isTheSam).get("to_type").toString());
				LinearLayout linear = (LinearLayout) views.get(isTheSam).findViewById(R.id.exercise_linear);
				
				HashMap<String, Object> map = myHomeworkAddView.getExerciseViewValue(
						linear, mHomework, position);
				mHomework.setAnswer(map.get("answer").toString());
				myHomeWorkServer.updateHomework(mHomework);
			}else{
				updateSubPage();
				myDeputyView.clear();
			}
			// 题目类型为5 且  不是同一个题目
			if(homeWorkList.get(position).get("to_type").equals("5")) {
				
				findViewById(R.id.myview_change_re)
				.setVisibility(View.VISIBLE);
				showZhuGuan(homeWorkList.get(position).get("to_id").toString());
				
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
//				RelativeLayout.LayoutParams reP = (RelativeLayout.LayoutParams)
//					holder.homeworkRela.getLayoutParams();
//				reP.height = 100;
//				reP.width = 100;
//				holder.homeworkRela.setLayoutParams(reP);
				
				
				
				holder.homeworkRela.setBackgroundResource(R.color.beige);
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.green));
				homeworkListView.smoothScrollToPositionFromTop(position, 20);
				fToid = list.get(position).get("to_id").toString();
			}else{
				holder.homeworkRela.setBackgroundResource(R.drawable.course_listview_item_bg_click);
				holder.homeworkName.setTextColor(context.getResources().getColor(R.color.white));
			}
			return convertView;

		}
		
		class Holder {
			TextView homeworkName, classHourName, lessonName;
			ImageView courseImg;
			RelativeLayout homeworkRela;
		}

	}
	
	
	private void showZhuGuan(String toid) {
		myDeputyView.clear();
		toids = toid;
		pageNum = 0;
		pageNums = myHomeWorkServer.searchSubjectiveForNum(toid);
		if(0 == pageNums) {
			creatNewSubAnswer(toid);
			pageNums = 1;
		}
		loadSubPage(toid);
	}
	
	public void inserImg(Bitmap bitmap) {
		if(null != bitmap) {
			myDeputyView.clear();
			myDeputyView.insertImg(bitmap);
		}
	}
	/**
	 * 创建主观题答案的新页
	 * */
	private void creatNewSubAnswer(String toid) {
		Homework mHomework = new Homework();
		mHomework.setToId(toid);
		mHomework.setToAnswerBitmap(null);
		myHomeWorkServer.insertSubjective(mHomework);
	}
	
	/**
	 * 载入指定页面的主观题答案
	 * */
	private void loadSubPage(String toid) {
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
		mHomework.setToId(toids);
		mHomework.setToAnswerBitmap(myDeputyView.getBitmapForByte());
		myHomeWorkServer.updateSubjective(mHomework);
		
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
	public void showPopuView() {
		// TODO Auto-generated method stub
		
	}
}
