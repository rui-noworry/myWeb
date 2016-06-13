package dkt.student.activity;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
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
import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.base.UserMsg;
import dkt.student.database.MyHandWritingServer;
import dkt.student.database.ResourceServer;
import dkt.student.listener.HandwritingListener;
import dkt.student.model.IsDrawBg;
import dkt.student.model.NoteData;
import dkt.student.model.Resource;
import dkt.student.model.SuData;
import dkt.student.net.GetIp;
import dkt.student.net.HttpApacheMapPostThread;
import dkt.student.net.HttpHandler;
import dkt.student.net.StudentUploadFile;
import dkt.student.net.UploadFile;
import dkt.student.net.UploadHandler;
import dkt.student.util.Md5Util;
import dkt.student.util.MoveView;
import dkt.student.util.ViewUtil;
import dkt.student.util.bitmap.FinalBitmap;
import dkt.student.view.HorizontalListView;
import dkt.student.view.MainView;
import dkt.student.view.MyImageView;
import dkt.student.view.popu.CaoGaoPopu;
import dkt.student.view.popu.ClasshourPackagePopu;
import dkt.student.view.popu.HuanbiPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.AlertDialog.Builder;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.view.View.OnLongClickListener;
import android.view.ViewGroup.LayoutParams;
import android.widget.AdapterView;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.PopupWindow;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ImageView.ScaleType;
import android.widget.SeekBar.OnSeekBarChangeListener;

public class BlackboardActivity extends Activity implements HandwritingListener{

	private Context context;
	private boolean isBottom;
	private boolean isPopu = true;
	private boolean checkActivity = true;
	private int leftWidth;
	private int bottomHeight;
	private String activityType;
	private FinalBitmap fb;
	private Bitmap bitmap;
	private String imageUrlString;
	
	private String hourId, notepath;
	private MyHandWritingServer myHandWritingServer;
	private int pageNum = 1;
	private int pageNums = 1;
	List<SuData> suDataList = new ArrayList<SuData>();
	
	private boolean isBlackboardActivity = true;
	
	// 板书上传
	private View focusView;
	private ClasshourPackagePopu popClassHourPackage;
	
	private HorizontalListView activityListView;
	private HorizontalListView resourceListView;
	List<HashMap<String, Object>> activityList = 
		new ArrayList<HashMap<String, Object>>();
	List<HashMap<String, Object>> resourceList = 
		new ArrayList<HashMap<String, Object>>();
	//板书列表
	private RelativeLayout blackboardRelativeLayout;
	private int suNum = 3000;
	// 板书下载
	private ProgressBar mProgress;
	private Dialog downloadDialog;
	private int progress;
	private boolean interceptFlag = false;
	private Thread downLoadThread;
	private Thread downImageThread;
	private String downloadUrl;
	private static final int DOWN_UPDATE = 1;
	private static final int DOWN_OVER = 2;
	private static final int DOWN_IMAGE_OK = 3;
	private static final int DOWN_IMAGE_NO = 4;
	private String dbUrl;
	private Handler mHandler = new Handler() {
		public void handleMessage(Message msg) {
			switch (msg.what) {
			case DOWN_UPDATE:
				mProgress.setProgress(progress);
				break;
			case DOWN_OVER:
				downloadDialog.dismiss();
				// db数据库操作
				//setNoteSCanvaView();
				//setPageNumText();
				pageNum = 1;
				myView.clear();
	        	mediaRelative.removeAllViews();
	        	suDataList.clear();
	        	setNoteData();
	        	setSuData();
	        	setPageNums();
	        	closeB();
				break;
			case DOWN_IMAGE_OK:				
				setSuView();
				closeB();
				break;
			default:
				break;
			}
		};
	};
	
	// 写字板
	private MainView myView;
	private int handIndex = 1, mediaIndex = 2;
	private RelativeLayout topRelative, handwritingRelative, mediaRelative;
	private IsDrawBg isDrawBg = new IsDrawBg();
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.blackboard);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}
	
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.note_canvas_view));
	}
	/**
     *  页面点击事件
     * 
     * */
	private void initView() {
		// TODO Auto-generated method stub
		context = BlackboardActivity.this;
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.note_canvas_view));
		
		hourId = app.getClId();
		focusView = findViewById(R.id.main_focus);
		fb = new FinalBitmap(context).init();
		
		RelativeLayout relativeL = (RelativeLayout) findViewById(R.id.class_popu_re);
		relativeL.setVisibility(View.GONE);
		
		blackboardRelativeLayout = (RelativeLayout) findViewById(R.id.blackboard_change_re);
		activityListView = (HorizontalListView) findViewById(R.id.blackboard_listview);
		resourceListView = (HorizontalListView) findViewById(R.id.blackboard_resource_listview);

		topRelative = (RelativeLayout) findViewById(R.id.view_top);
		mediaRelative = (RelativeLayout) findViewById(R.id.view_media);
		handwritingRelative = (RelativeLayout) findViewById(R.id.note_canvas);
		
		myView = (MainView) findViewById(R.id.note_canvas_view);
		myView.setHandwritingListener(BlackboardActivity.this);
		myView.isNoWrite(false);
		
		String pait = UserMsg.getConfigMsg(context, "paintb");

		if (!pait.equals("")) {
			
			myView.setPaintB(Integer.parseInt(pait));
		}
		
		// 长按写字板切换出视图层
		myView.setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub					
				changeLayout(handIndex);
				return true;
			}
		});
		
		
		notepath = "classhandwriting" + hourId + ".db";
		
		File f = new File("/sdcard/Dkt/" + notepath);
		if (f.exists()) {
			f.delete();
		}
		myHandWritingServer = new MyHandWritingServer(notepath);
		int chechExistsNum = myHandWritingServer.checkExists();
		
		NoteData firstNoteData = new NoteData();
		if(2 == chechExistsNum) {
			firstNoteData.setPageNum(pageNum);
        	firstNoteData.setBitmapData(null);
        	myHandWritingServer.savePage(firstNoteData);
		}else if(1 == chechExistsNum) {
        	setNoteData();
        	setSuData();
        }
		setPageNums();
        changeLayout(mediaIndex);
        
		// 设置活动列表数据
		activityList = app.getActivityList();
		resourceList = app.getResourceList();
		
		activityListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
					
				activityType = activityList.get(arg2).get("act_type").toString();
				RelativeLayout relativeL = (RelativeLayout) findViewById(R.id.class_popu_re);
				int poW = relativeL.getWidth();
				TextView Pt = (TextView) findViewById(R.id.class_popu_text);
				
				if (!isPopu) {
					
				} else {
//						doPackageListHttp();
					relativeL.setVisibility(View.VISIBLE);
					MoveView.doHorizontalMove(relativeL, poW, 0);
					isPopu = false;
				}
//					if(activityType.equals("1")) {
//						Pt.setText("作业");
//					}else if(activityType.equals("2")) {// 课堂练习
//						Pt.setText("课堂练习");
//					}else if(activityType.equals("3")) {// 课堂练习
//						Pt.setText("文本");
//					}else if(activityType.equals("4")) {// 课堂练习
//						Pt.setText("链接");
//					}else if(activityType.equals("5")) {// 课堂练习
//						Pt.setText("拓展阅读");
//					}
				Pt.setText(activityList.get(arg2).get("biaoti").toString());
				blackboardRelativeLayout.removeAllViews();
				if(activityType.equals("6")) {
					
					doDiscussDetailHttp(activityList.get(arg2).get("act_id").toString(),
							activityList.get(arg2).get("ap_id").toString());
				}else{
					doActivityDetailHttp(activityList.get(arg2).get("act_id").toString());
				}
			
			}
		});
		resourceListView.setOnItemClickListener(new OnItemClickListener() {

			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int arg2,
					long arg3) {
				// TODO Auto-generated method stub
				ResourceServer resourceServer = new ResourceServer();
				Resource myResource = new Resource();
				myResource = resourceServer.getMyNetData(Integer.parseInt(resourceList.get(arg2).get("ar_id").toString()));
				if(myResource == null) {
					System.out.println(resourceList.get(arg2).get("ar_savename").toString());
					ViewUtil.myToast(context, "该资源正在下载中，请稍候。。。");
				}else if(1 == myResource.getResourceType()){
					try {
						FileInputStream file = new FileInputStream(myResource.getResourcePath());
						bitmap  = BitmapFactory.decodeStream(file);
						setSuView();

					} catch (FileNotFoundException e) {
						// TODO Auto-generated catch block
						e.printStackTrace();
					}
				
				}
				else{
					String filePath = myResource.getResourcePath();
					String fileName = filePath.substring(filePath.lastIndexOf(".") + 1);
					File f = new File(filePath);
					if(!f.exists()) {
						Toast.makeText(context, getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();
						resourceServer.delete(myResource.getResourceId());
					}else{
						ViewUtil.openResource(fileName, filePath, context);
					}
				}
			}
		});
		
		if(activityList != null) {
			BlackboardAdapter listViewAdapter = new BlackboardAdapter(context, activityList, 0, 0);
			activityListView.setAdapter(listViewAdapter);
		}
		if(resourceList != null) {
			BlackboardAdapter resourceViewAdapter = new BlackboardAdapter(context, resourceList, 1, 0);
			resourceListView.setAdapter(resourceViewAdapter);
		}

	}
	
	/**
	 * 组件视图层插入数据结构
	 * */
	private void setSuView() {
		SuData suData = new SuData();
		suData.setSuId(suNum);
		suData.setHaId(pageNum);
		suData.setSuType(1);
		suData.setSuTran("");
		suData.setSuPath("");
		suData.setSuTitle("");
		suData.setSuUrl("");
		suData.setSuX(400);
		suData.setSuY(100);
		suData.setSuWidth(bitmap.getWidth());
		suData.setSuHeight(bitmap.getHeight());
		suData.setSuTag(""+suNum);
		suData.setSuBitmap(Bitmap2Bytes(bitmap));
		
		myHandWritingServer.savePageSu(suData);
		suDataList.add(suData);
		addSuView(suData);
		suNum = suNum + 1;
	}
	/**
	 * 板书页码
	 * */
	private void setPageNums() {
		pageNums = myHandWritingServer.getPageTotalNum();
	    TextView pageNumText = (TextView) findViewById(R.id.note_book_page_txt);
		pageNumText.setText("第" + pageNum + "/" + pageNums + "页");
	}
	
	/**
	 * 保存信息
	 * 
	 * */
	private void saveMsg() {
		
		byte[] pageByte = myView.getBitmapForByte();
		NoteData pageNotedata = new NoteData();
		pageNotedata.setBitmapData(pageByte);
		pageNotedata.setPageNum(pageNum);
		myHandWritingServer.updatePage(pageNotedata, suDataList);
		
	}
	
	/**
	 * 插入原笔迹层数据
	 * */
	private void setNoteData() {
		// TODO Auto-generated method stub
		NoteData noteData = new NoteData();
		noteData = myHandWritingServer.loadPage(pageNum);
		if(null != noteData.getBitmapData()) {
			myView.insertImg(Bytes2Bimap(noteData.getBitmapData()));
		}
		
	}
	
	/**
	 * 插入视图层数据
	 * */
	private void setSuData() {
		suDataList = myHandWritingServer.getPageSuData(pageNum);
		for(int i=0;i<suDataList.size();i++) {
			SuData suData = suDataList.get(i);	
			addSuView(suData);
			suNum = suNum + 1;
		} 
	}
	
	/**
	 * 视图层插入图片
	 * */
	private void addSuView(SuData suData){
		MyImageView myImageView;
		Resources r = this.getResources();
		InputStream is = r.openRawResource(R.drawable.cancel);
		BitmapDrawable  bmpDraw = new BitmapDrawable(is);
		Bitmap bmp = bmpDraw.getBitmap();
		myImageView = new MyImageView(context, suData, bmp, isDrawBg);

		myImageView.setScaleType(ScaleType.FIT_XY);
        RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(
        		suData.getSuWidth(), suData.getSuHeight());
		layoutParams.setMargins(suData.getSuX(), suData.getSuY(), 0, 0);
		myImageView.setLayoutParams(layoutParams);
		myImageView.setHandwritingListener(BlackboardActivity.this);
		
		myImageView.setImageBitmap(Bytes2Bimap(suData.getSuBitmap()));
		myImageView.setId(suData.getSuId());
	
		mediaRelative.addView(myImageView);
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
	/**
     *  初始化页面全局控件
     * 
     * */
	private void addFun() {
		// TODO Auto-generated method stub
		// 返回
		findViewById(R.id.blackboard_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				saveMsg();
				finish();
			}
		});
		
		// 上一页
		findViewById(R.id.note_book_pre_page).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					saveMsg();
					if(1 == pageNum) {
						
						Toast.makeText(context, "已经是第一页", Toast.LENGTH_SHORT).show();	
					}else{
						myView.isNoWrite(false);
						pageNum = pageNum - 1;
						myView.clear();
			        	mediaRelative.removeAllViews();
			        	suDataList.clear();
			        	setNoteData();
			        	setSuData();
			        	setPageNums();
					}
				}
		});
		
		// 下一页
		findViewById(R.id.note_book_next_page).setOnClickListener(
			new OnClickListener() {
	
				@Override
				public void onClick(View v) {
					saveMsg();
					myView.clear();
		        	mediaRelative.removeAllViews();
		        	suDataList.clear();
		        	myView.isNoWrite(false);
					if(pageNums == pageNum) {
						pageNum = pageNum + 1;
						NoteData firstNoteData = new NoteData();
						firstNoteData.setPageNum(pageNum);
			        	byte[] inkData = myView.getBitmapForByte();
			        	firstNoteData.setBitmapData(inkData);
			        	myHandWritingServer.savePage(firstNoteData);
			        	
					}else{
						pageNum = pageNum + 1;
						setNoteData();
						setSuData();
					}
					setPageNums();
				}
		});
		
		// 活动
		findViewById(R.id.blackboard_activity).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				if(isBlackboardActivity){
					isBlackboardActivity = false;
//					checkActivity = true;
					v.setBackgroundResource(R.drawable.blackboard_resource_bg);
//					BlackboardAdapter listViewAdapter = new BlackboardAdapter(context, resourceList, 1, 0);
//					activityListView.setAdapter(listViewAdapter);
					activityListView.setVisibility(View.GONE);
					resourceListView.setVisibility(View.VISIBLE);
				}else{

					isBlackboardActivity = true;
//					checkActivity = false;
					v.setBackgroundResource(R.drawable.blackboard_activity_bg);
//					BlackboardAdapter listViewAdapter = new BlackboardAdapter(context, activityList, 0, 0);
//					activityListView.setAdapter(listViewAdapter);
					activityListView.setVisibility(View.VISIBLE);
					resourceListView.setVisibility(View.GONE);
				}
				
			}
		});
//		// 资源
//		findViewById(R.id.blackboard_resource).setOnClickListener(new OnClickListener() {
//			
//			@Override
//			public void onClick(View v) {
//				// TODO Auto-generated method stub
//				isBlackboardActivity = false;
//				BlackboardAdapter listViewAdapter = new BlackboardAdapter(context, resourceList, 1, 0);
//				activityListView.setAdapter(listViewAdapter);
//			}
//		});
		// 工具栏的收放
		findViewById(R.id.blackboard_retractable_btn).setOnClickListener(
				new OnClickListener() {

			@Override
			public void onClick(View v) {
				RelativeLayout relativeL = (RelativeLayout) findViewById(R.id.class_left_activity_re);
				RelativeLayout relativeB = (RelativeLayout) findViewById(R.id.class_tool_relative);
				if (!isBottom) {
					MoveView.doOrvlMove(relativeB, 0, bottomHeight);
					relativeB.setVisibility(View.GONE);
					isBottom = true;

					MoveView.doHorizontalMove(relativeL, 0, -leftWidth);
					relativeL.setVisibility(View.GONE);
					v.setBackgroundResource(R.drawable.retractable_ok);
				} else {
					relativeB.setVisibility(View.VISIBLE);
					MoveView.doOrvlMove(relativeB, bottomHeight, 0);
					isBottom = false;

					relativeL.setVisibility(View.VISIBLE);
					MoveView.doHorizontalMove(relativeL, -leftWidth, 0);
					v.setBackgroundResource(R.drawable.retractable_no);
				}

			}
		});
			
		// 画笔
		findViewById(R.id.blackboard_pen_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(false);
				myView.isNoWrite(true);
			}
		});
		findViewById(R.id.blackboard_pen_btn).setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub
				HuanbiPopu myHuanbiPopu = new HuanbiPopu(context, findViewById(R.id.blackboard_eraser_btn), myView);
				myHuanbiPopu.showPopu();
				return true;
			}
		});
		
		// 橡皮
		findViewById(R.id.blackboard_eraser_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				myView.isClean(true);
				myView.isNoWrite(true);
			}
		});
		findViewById(R.id.blackboard_eraser_btn).setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub
				EraserPopu myEraserPopu = new EraserPopu(context, v, myView);
				myEraserPopu.showPopu();
				return true;
			}
		});
		// 载入板书
		findViewById(R.id.blackboard_handwriting_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				blackboardRelativeLayout.removeAllViews();
				RelativeLayout relativeL = (RelativeLayout) findViewById(R.id.class_popu_re);
				int poW = relativeL.getWidth();
				TextView Pt = (TextView) findViewById(R.id.class_popu_text);
				Pt.setText("板书");
				if (!isPopu) {
				} else {
					
					relativeL.setVisibility(View.VISIBLE);
					MoveView.doHorizontalMove(relativeL, poW, 0);
					isPopu = false;
				}
				doPackageListHttp();
			}
		});
		
		// 右框关闭
		findViewById(R.id.class_popu_close).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				closeB();
			}
		});
		
		// 草稿
		findViewById(R.id.blackboard_draft_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				CaoGaoPopu mCaoGaoPopu = new CaoGaoPopu(context);
				mCaoGaoPopu.showPopup(v);
			}
		});
		
		// 下载课堂笔记
		findViewById(R.id.blackboard_student_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				new AlertDialog.Builder(context)
				.setTitle("确认载入")
				.setMessage("确定载入该课堂笔记吗？")
				.setPositiveButton(
						"确定",
						new DialogInterface.OnClickListener() {
							@Override
							public void onClick(
									DialogInterface dialog,
									int which) {
								doGetClassNoteHttp();
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
			}
		});
		
		// 保存
		findViewById(R.id.blackboard_save_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub

				new AlertDialog.Builder(context)
				.setTitle("确认保存")
				.setMessage("确定保存该课堂笔记吗？")
				.setPositiveButton(
						"确定",
						new DialogInterface.OnClickListener() {
							@Override
							public void onClick(
									DialogInterface dialog,
									int which) {
								doSaveCoursePackageHttp();
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
			}
		});
	}
	
	private void closeB() {
		RelativeLayout relativeL = (RelativeLayout) findViewById(R.id.class_popu_re);
		int poW = relativeL.getWidth();
		MoveView.doHorizontalMove(relativeL, 0, poW);
		relativeL.setVisibility(View.GONE);
		isPopu = true;
	}
	
	/**
	 * 获取讨论详细
	 * */
	private void doDiscussDetailHttp(String actId, String apId) {
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
				MyContants.DO_HTTP_TEACH_ACTIVITY_DISCUSS_DETAIL), map);
	}
	
	/**
	 * 学生保存课堂笔记
	 */
	private void doSaveCoursePackageHttp() {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) getApplication();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "StudentNotes.upload");
		map.put("args[cl_id]", app.getClId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("ts", "" + time);
		map.put("format", "JSON");
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new StudentUploadFile(temp, new PackageUploadHandler(context,
				MyContants.DO_HTTP_ISERT_PACKAGR), map, notepath);
	}
	
	/**
	 * 学生获取课堂笔记
	 */
	private void doGetClassNoteHttp() {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "StudentNotes.get");
		map.put("args[cl_id]", app.getClId());
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("ts", "" + time);
		map.put("format", "JSON");
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_CLASSNOTE), map);
	}
	
	/**
	 * 活动详细
	 */
	private void doActivityDetailHttp(String actId) {
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
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL), map);
	}
	
	/**
	 * ======= 得到课程包的信息
	 */
	private void doPackageListHttp() {
		showDialog(MyContants.HTTP_WAITING);
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
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_PACKAGE_LIST), map);
	}
	
	/**
	 * 课程包模版设置取消
	 */
	private void doStenPackageHttp(String cpaId, String cpaStatus) {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "ClasshourPackage.update");
		map.put("args[cpa_id]", cpaId);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("args[cpa_status]", cpaStatus);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_PACKAGE_STEN), map);
	}
	
	/**
	 * 删除课程包
	 */
	private void doDeletePackageHttp(String cpaId) {
		showDialog(MyContants.HTTP_WAITING);
		MyApp app = (MyApp) context.getApplicationContext();
		String skey = app.getSkey();
		int userId = app.getUserId();
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "ClasshourPackage.remove");
		map.put("args[cpa_id]", cpaId);
		map.put("skey", skey);
		map.put("args[a_id]", userId);
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new blackboardHandler(context,
				MyContants.DO_HTTP_TEACH_PACKAGE_DELETE), map);
	}
	
	/**
	 * 解析课程包数据
	 * */
	private void doPackageListSucces(String result) {
		List<HashMap<String, Object>> classhourPackageList = 
			new ArrayList<HashMap<String, Object>>();
		try {
			JSONArray packageList = new JSONArray(result);
			for (int i = 0; i < packageList.length(); i++) {
				JSONObject jo = packageList.getJSONObject(i);
				HashMap<String, Object> map = new HashMap<String, Object>();
				map.put("cpa_id", jo.get("cpa_id").toString());
				map.put("cpa_title", jo.get("cpa_title").toString());
				map.put("cpa_status", jo.get("cpa_status").toString());
				map.put("cpa_path", jo.get("cpa_path").toString());
				classhourPackageList.add(map);
			}
			if(classhourPackageList.size() > 0){
				addPackageListRe(classhourPackageList);
			}
		} catch (JSONException e) {
			e.printStackTrace();
		}
	}
	/**
	 * 学生课堂笔记
	 * */
	private void doClassNoteHttp(String result) {
		/**
		 * {"stu_notes":"http:\/\/192.168.7.53:81\/StudentNodes\/4\/4-1.db"}

		 * */
		JSONObject jsonObject;
		try {
			jsonObject = new JSONObject(result);
			dbUrl = jsonObject.get("stu_notes").toString();
			showDownloadDialog();
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		
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
	 * 解析讨论数据
	 * */
	private void doGetDiscussListSucces(String result) {
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
			addDiscussListRe(classhourDiscussList);
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
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
				map.put("img_path", object.get("img_path").toString());
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
	
	/**
	 * 添加讨论
	 * */
	private void addDiscussListRe(List<HashMap<String, Object>> discussList) {
		blackboardRelativeLayout.removeAllViews();
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.blackboard_link_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_link_listview);
		packageListView.setDividerHeight(20);
		
		BlackboardAdapter packageAdapter = new BlackboardAdapter(context, discussList, 9, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
	}
	
	/**
	 * 添加板书列表
	 * */
	private void addPackageListRe(List<HashMap<String, Object>> classhourPackageList) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.blackboard_package_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_package_listview);
		BlackboardAdapter packageAdapter = new BlackboardAdapter(context, classhourPackageList, 2, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
	}
	
	/**
	 * 添加课堂练习列表
	 * */
	private void addClassWorkListRe(List<HashMap<String, Object>> classhourClassWorkList) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
				R.layout.blackboard_classwork_re, null);
		ListView packageListView = (ListView) view.findViewById(R.id.blackboard_classwork_listview);
		packageListView.setDividerHeight(20);
		BlackboardAdapter packageAdapter = new BlackboardAdapter(context, classhourClassWorkList, 4, 0);
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
		View view = LayoutInflater.from(this).inflate(
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
						Toast.makeText(context, getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();
						resourceServer.delete(myResource.getResourceId());
					}else{
						ViewUtil.openResource(fileName, filePath, context);
					}
				}
			}
		});
		BlackboardAdapter packageAdapter = new BlackboardAdapter(context, classhourReadList, 5, 0);
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
		View view = LayoutInflater.from(this).inflate(
				R.layout.blackboard_text_re, null);
		ImageView textImageView = (ImageView)view.findViewById(R.id.blackboard_text_imgview);
		fb.display(textImageView, imgUrl);
		textImageView.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				pasteImageToBoard(imgUrl);
			}
		});
		blackboardRelativeLayout.addView(view, packageListRela);
		
	}
	
	/**
	 * 添加链接列表
	 * */
	private void addLinkListRe(final List<HashMap<String, Object>> classhourLinkList) {
		
		blackboardRelativeLayout.removeAllViews();
		
		RelativeLayout.LayoutParams packageListRela = new RelativeLayout.LayoutParams(
				RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
		View view = LayoutInflater.from(this).inflate(
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
		BlackboardAdapter packageAdapter = new BlackboardAdapter(context, classhourLinkList, 3, 0);
		packageListView.setAdapter(packageAdapter);
		
		blackboardRelativeLayout.addView(view, packageListRela);
		
	}
	/**
	 * 把图片 贴在画板上
	 * 
	 * @param imageUrl
	 */
	private void pasteImageToBoard(String imageUrl) {
		imageUrlString = imageUrl;
		downLoadThread = new Thread(mdownImageRunnable);
		downLoadThread.start();
	}
	private Runnable mdownImageRunnable = new Runnable() {
		@Override
		public void run() {
			URL imageUrl = null;
			GetIp getip = new GetIp(context);
			String service_ip = getip.servise_ip;
			try {
				imageUrl = new URL(MyContants.HTTP_PREFIX + service_ip + imageUrlString);
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
				mHandler.sendEmptyMessage(DOWN_IMAGE_NO);
			}
		}
	};
	
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
			}
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
				if (tag.equals(MyContants.DO_HTTP_TEACH_PACKAGE_LIST)) {
					doPackageListSucces((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_TEACH_PACKAGE_DELETE)){
					doPackageListHttp();
					Toast.makeText(context, "板书删除成功", Toast.LENGTH_SHORT).show();
				}else if(tag.equals(MyContants.DO_HTTP_TEACH_PACKAGE_STEN)){
					doPackageListHttp();
					Toast.makeText(context, "板书修改成功", Toast.LENGTH_SHORT).show();
				}else if(tag.equals(MyContants.DO_HTTP_TEACH_ACTIVITY_DETAIL)){
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
				}else if(tag.equals(MyContants.DO_HTTP_CLASSNOTE)){
					doClassNoteHttp((String) msg.obj);
				}else if(tag.equals(MyContants.DO_HTTP_TEACH_ACTIVITY_DISCUSS_DETAIL)) {
					doGetDiscussListSucces((String) msg.obj);
				}
			}
		}
	}
	class BlackboardAdapter extends BaseAdapter {

		List<HashMap<String, Object>> list;
		Context context;
		int tag;
		Holder holder;
		int i;

		public BlackboardAdapter(Context context,
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
				view = addActivityView(position, convertView, parent);
			}else if(1 == tag){
				view = addResourceView(position, convertView, parent);
			}else if(2 == tag){
				view = addPackageView(position, convertView, parent);
			}else if(3 == tag){
				view = addLinkgeView(position, convertView, parent);
			}else if(4 == tag){
				view = addClassWorkView(position, convertView, parent);
			}else if(5 == tag){
				view = addReadView(position, convertView, parent);
			}else if(9 == tag){
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
		
		// 生成活动列表视图
		private View addActivityView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.blackboard_listview_item, null);
				
				holder.activityName = (TextView) convertView
						.findViewById(R.id.blackboard_activity_name);
				holder.activityRela = (RelativeLayout) convertView
						.findViewById(R.id.blackboard_listview_re);	
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("biaoti").toString();
			if(biaotiString.length() > 9) {
				biaotiString = biaotiString.substring(0, 9) + "...";
			}
			holder.activityName.setText(biaotiString);
			if(list.get(position).get("act_type").equals("1")) {
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_work_bg);

			}else if(list.get(position).get("act_type").equals("2")) {
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_classwork_bg);
				
			}else if(list.get(position).get("act_type").equals("3")){
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_text_bg);

			}else if(list.get(position).get("act_type").equals("4")){
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_link_bg);

			}else if(list.get(position).get("act_type").equals("5")){
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_read_bg);
			}else if(list.get(position).get("act_type").equals("6")){
				holder.activityRela.setBackgroundResource(R.drawable.blackboard_discuss_bg);
			}
			return convertView;

		}
		
		// 生成资源列表视图
		private View addResourceView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.blackboard_resource_listview_item, null);
				
				holder.activityName = (TextView) convertView
						.findViewById(R.id.blackboard_activity_name);
				holder.activityRelaImg = (ImageView) convertView
						.findViewById(R.id.blackboard_listview_img);	
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("ar_title").toString();
			if(biaotiString.length() > 9) {
				biaotiString = biaotiString.substring(0, 9) + "...";
			}
			holder.activityName.setText(biaotiString);			
			
			if(list.get(position).get("m_id").toString().equals("1")) {
				holder.activityRelaImg.setBackgroundResource(R.drawable.png);
			}else if(list.get(position).get("m_id").toString().equals("2")){
				holder.activityRelaImg.setBackgroundResource(R.drawable.mp4);

			}else if(list.get(position).get("m_id").toString().equals("3")){
				holder.activityRelaImg.setBackgroundResource(R.drawable.mp3);

			}else if(list.get(position).get("m_id").toString().equals("4")){
				holder.activityRelaImg.setBackgroundResource(R.drawable.doc);

			}
			return convertView;

		}

		// 生成课堂练习列表视图
		private View addClassWorkView(int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.blackboard_classwork_listview_item, null);
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
			holder.classworkImg.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					pasteImageToBoard(imgUrl);
				}
			});
			
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
			
//			if(list.get(position).get("m_id").toString().equals("1")) {
//				holder.readImg.setBackgroundResource(R.drawable.resource_icon_image_bg);
//			}else if(list.get(position).get("m_id").toString().equals("2")){
//				holder.readImg.setBackgroundResource(R.drawable.resource_icon_shipin_bg);
//
//			}else if(list.get(position).get("m_id").toString().equals("3")){
//				holder.readImg.setBackgroundResource(R.drawable.rsource_icon_yinpin_bg);
//
//			}else if(list.get(position).get("m_id").toString().equals("4")){
//				holder.readImg.setBackgroundResource(R.drawable.resource_icon_doc_bg);
//
//			}
			fb.display(holder.readImg, list.get(position).get("img_path").toString());

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
		
		// 生成课程包列表视图
		private View addPackageView(final int position, View convertView,
				ViewGroup parent) {
			if (convertView == null) {
				holder = new Holder();
				convertView = LayoutInflater.from(context).inflate(
						R.layout.package_change_listview_item, null);
				
				holder.packageName = (TextView) convertView
						.findViewById(R.id.package_name);
				holder.packageLoad = (Button) convertView
						.findViewById(R.id.pachage_load);
				
				convertView.setTag(holder);

			} else {
				holder = (Holder) convertView.getTag();
			}
			String biaotiString = list.get(position).get("cpa_title").toString();
			if(biaotiString.length() > 20) {
				biaotiString = biaotiString.substring(0, 20) + "...";
			}
			if(list.get(position).get("cpa_status").toString().equals("1")) {
				holder.packageName.setText(biaotiString);
			}else{
				holder.packageName.setTextColor(context.getResources().getColor(R.color.green));
				holder.packageName.setText(biaotiString+"\t(模版)");
			}
				
			holder.packageLoad.setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认载入")
					.setMessage("确定载入该板书吗？")
					.setPositiveButton(
							"确定",
					new DialogInterface.OnClickListener() {
						@Override
						public void onClick(
								DialogInterface dialog,
								int which) {
							dbUrl = list.get(position).get("cpa_path").toString();
							showDownloadDialog();
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
				}
			});
			
			return convertView;

		}
		class Holder {
			TextView activityName, readName, packageName, linkName, linkUrl, answer, studentAnswer, toType;
			ImageView classworkImg, readImg, activityRelaImg;
			RelativeLayout activityRela;
			Button packageLoad, packageSten, packageDelete;
			TextView name, time, content;
		}
		
	}
	
	/**
	 * 浏览器调用
	 * */
	private void openUrl(String url) {
		
		Intent intent = new Intent(Intent.ACTION_VIEW);
		intent.setData(Uri.parse(url));
		startActivity(intent);

	}
	/**
	 * 下载课程包
	 * 
	 * **/
	private void showDownloadDialog() {

		AlertDialog.Builder builder = new Builder(context);
		builder.setTitle("下载中");
		final LayoutInflater inflater = LayoutInflater.from(context);
		View v = inflater.inflate(R.layout.updateprogress, null);
		mProgress = (ProgressBar) v.findViewById(R.id.progress);

		builder.setView(v);
		builder.setNegativeButton("取消",
				new android.content.DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						dialog.dismiss();
						interceptFlag = true;
					}
				});

		downloadDialog = builder.create();
		downloadDialog.show();
		downloadDbFile();
	}
	
	private void downloadDbFile() {
		downLoadThread = new Thread(mdownPackageRunnable);
		downLoadThread.start();
	}
	
	private Runnable mdownPackageRunnable = new Runnable() {
		@Override
		public void run() {
			
			try {
				GetIp getip = new GetIp(context);
				String service_ip = getip.servise_ip;
				String fileDownUrl = MyContants.HTTP_PREFIX + service_ip + dbUrl;
				
				URL url = new URL(fileDownUrl);

				HttpURLConnection conn = (HttpURLConnection) url
						.openConnection();
				conn.connect();
				int length = conn.getContentLength();
				InputStream is = conn.getInputStream();

				File file = new File("/sdcard/Dkt");
				if (!file.exists()) {
					file.mkdir();
				}

				String dbFile = "/sdcard/Dkt/" + notepath;
				File DbFile = new File(dbFile);
				FileOutputStream fos = new FileOutputStream(DbFile);

				int count = 0;
				byte buf[] = new byte[1024];

				do {
					int numread = is.read(buf);
					count += numread;
					progress = (int) (((float) count / length) * 100);
					// 更新进度
					mHandler.sendEmptyMessage(DOWN_UPDATE);
					if (numread <= 0) {
						// 下载完成通知安装
						mHandler.sendEmptyMessage(DOWN_OVER);
						break;
					}
					fos.write(buf, 0, numread);
				} while (!interceptFlag);// 点击取消就停止下载.

				fos.close();
				is.close();
			} catch (MalformedURLException e) {
			} catch (IOException e) {
			}
		}
	};
	/**
     *  写字板和视图层的切换
     * 
     * */
	private void changeLayout(int index) {
		
		topRelative.removeAllViews();
		if(index == mediaIndex){
			isDrawBg.setDrawBg(false);
			topRelative.addView(mediaRelative);
			topRelative.addView(handwritingRelative);
			
		}else if (index == handIndex){
			isDrawBg.setDrawBg(true);
			topRelative.addView(handwritingRelative);
			topRelative.addView(mediaRelative);
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
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		return false;
	}
	
	@Override
	public void onWindowFocusChanged(boolean hasFocus) {
		super.onWindowFocusChanged(hasFocus);

		RelativeLayout left = (RelativeLayout) findViewById(R.id.class_left_activity_re);
		RelativeLayout bottom = (RelativeLayout) findViewById(R.id.class_tool_relative);
		leftWidth = left.getWidth();
		bottomHeight = bottom.getHeight();
	}
	
	@Override
	public void closeActivity() {
		// TODO Auto-generated method stub
		
	}

	@Override
	public void closeImageView(View v) {
		// TODO Auto-generated method stub
		for(int i=0;i<suDataList.size();i++) {
			if(suDataList.get(i).getSuId() == v.getId()) {
				myHandWritingServer.deleteSu(suDataList.get(i).getSuId());
				suDataList.remove(suDataList.get(i));
			}
		}
		mediaRelative.removeView(v);
	}

	@Override
	public void showHandwritingView() {
		// TODO Auto-generated method stub
		if(mediaRelative.getChildCount() > 0) {
			
			for(int i=0;i<mediaRelative.getChildCount();i++) {
				RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(
						suDataList.get(i).getSuWidth(), suDataList.get(i).getSuHeight());
				
				layoutParams.setMargins(suDataList.get(i).getSuX(), suDataList.get(i).getSuY(), 0, 0);
				mediaRelative.getChildAt(i).setLayoutParams(layoutParams);
			}
		}
		changeLayout(mediaIndex);
	}
	
	public class EraserPopu {
		
		private PopupWindow popup;
		private Context context;
		private View view;
		TextView  text ;
		MainView myView;
		int paintA = 20;
		int color;
		public EraserPopu(Context context, View view, MainView myView) {
			this.context = context;
			this.view = view;
			this.myView = myView;
		}

		public void clearPopu() {
			popup.dismiss();
		}

		public void showPopu() {
			View view1 = LayoutInflater.from(context).inflate(
					R.layout.z_popu_eraser, null);
			popup = new PopupWindow(view1, LayoutParams.WRAP_CONTENT,
					LayoutParams.WRAP_CONTENT);
			popup.setBackgroundDrawable(new BitmapDrawable());
			popup.setOutsideTouchable(true);
			popup.showAsDropDown(view, 100, -200);
			
			
			final ImageView image = (ImageView) view1.findViewById(R.id.eraser_width_image);

			// 橡皮进度条
			SeekBar seekBar = (SeekBar) view1
					.findViewById(R.id.eraser_width_progress);
			
			String pait = UserMsg.getConfigMsg(context, "painta");

			if (!pait.equals("")) {
				int mypro = Integer.parseInt(pait) * 3;
				seekBar.setProgress(mypro);
				scaleImageMiddleView(image,(float)((float)mypro)/100);
			}else{
				int mypro = 60;
				seekBar.setProgress(mypro);
				scaleImageMiddleView(image,(float)((float)mypro)/100);
			}
			
			seekBar.setOnSeekBarChangeListener(new OnSeekBarChangeListener() {
				//结束拖动
				public void onStopTrackingTouch(SeekBar seekBar) {
					
				}
				//开始拖动
				public void onStartTrackingTouch(SeekBar seekBar) {
					
				}
				//拖动中
				public void onProgressChanged(SeekBar seekBar, int progress,
						boolean fromUser) {
				    if(fromUser){
				    	scaleImageMiddleView(image,(float)((float)progress)/100);
				    	
				    	if((progress/3) < 3) {
				    		paintA = 3;			    		
				    	}else{
				    		paintA = progress/3;
				    	}
				    	myView.setPaintA(paintA);
				    }
					
				}
			});
			
			
			view1.findViewById(R.id.black_btn).setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									myHandWritingServer.deleteAllSu(pageNum);
									myView.clear();
						        	mediaRelative.removeAllViews();
						        	suDataList.clear();
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();
					
				}
			});
			view1.findViewById(R.id.red_btn).setOnClickListener(new OnClickListener() {
				
				@Override 
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有原笔迹数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									myView.clear();						   
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();
				}
			});
			view1.findViewById(R.id.blue_btn).setOnClickListener(new OnClickListener() {
				
				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					new AlertDialog.Builder(context)
					.setTitle("确认清屏")
					.setMessage("确定清除所有视图数据吗？")
					.setPositiveButton("确定",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									myHandWritingServer.deleteAllSu(pageNum);
						        	mediaRelative.removeAllViews();
						        	suDataList.clear();
								}
							})
					.setNegativeButton("取消",
							new DialogInterface.OnClickListener() {
								@Override
								public void onClick(DialogInterface dialog,
										int which) {
									dialog.dismiss();

								}
					}).show();

				}
			});
			
		}

		private void scaleImageMiddleView(ImageView imageView,float scale ){
			Bitmap bp = BitmapFactory.decodeResource(context.getResources(), R.drawable.eraser_circle_bg);
			int width = bp.getWidth();
			int height = bp.getHeight();
			Matrix matrix = new Matrix();
			if(scale < 0.1f){
				scale = 0.1f;
			}
			matrix.postScale(scale, scale);
			bp = Bitmap.createBitmap(bp,0,0,width,height,matrix,true);
			//将上面创建的Bitmap转换成Drawable对象，使得其可以使用在ImageView, ImageButton中
	        BitmapDrawable bmd = new BitmapDrawable(bp);
	        imageView.setImageDrawable(bmd);
			
		}

	}
}
