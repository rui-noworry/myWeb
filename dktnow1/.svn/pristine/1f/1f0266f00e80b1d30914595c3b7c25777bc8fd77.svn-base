package dkt.student;

import java.io.File;
import java.io.InputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.List;

import org.apache.http.HttpStatus;
import org.json.JSONException;
import org.json.JSONObject;

import dkt.student.activity.CourseActivity;
import dkt.student.activity.GraffitiActivity;
import dkt.student.activity.NoteListActivity;
import dkt.student.activity.ResourceActivity;
import dkt.student.base.UserMsg;
import dkt.student.database.ResourceServer;
import dkt.student.model.Resource;
import dkt.student.net.GetIp;
import dkt.student.net.HttpApacheMapPostThread;
import dkt.student.net.HttpHandler;
import dkt.student.util.Md5Util;
import dkt.student.util.StringUtil;
import dkt.student.util.UpdateApp;
import dkt.student.util.ViewUtil;
import dkt.student.view.popu.SetIpPopu;
import dkt.student.view.popu.VesionPopu;
import android.app.Activity;
import android.app.ActivityManager;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.provider.MediaStore;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.Toast;

public class DktForstudentActivity extends Activity {
	private Context context;
	private EditText nameEdit, passwordEdit;
	private Button saveMsgBtn;
	private boolean isSaveUserMsg = false;
	private View focusView;
	private SetIpPopu popSetIp;
	private VesionPopu popVesion;
	Intent intent = new Intent("dkt.student.socket.SocketPushService");
	
	private Bitmap bitmap;
	private static final int DOWN_IMAGE_OK = 3;
	private Thread downLoadThread;
	private String imageUrlString;
	private RelativeLayout imgRe;
	private Handler mHandler = new Handler() {
		public void handleMessage(Message msg) {
			switch (msg.what) {
			
			case DOWN_IMAGE_OK:				
				imgRe.setBackgroundDrawable(new BitmapDrawable(bitmap));
				break;
			default:
				break;
			}
		};
	};
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.main);
       
        initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
    }
    @Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.main_set_ip_btn));
	}
    /**
     *  初始化页面全局控件
     * 
     * */
    private void initView() {
    	context = DktForstudentActivity.this;
    	
    	doGetIndexImg();
    	
    	MyApp app = (MyApp) this.getApplication();
		app.setJiePView(findViewById(R.id.main_set_ip_btn));
    	
    	nameEdit = (EditText) findViewById(R.id.user_login_name_edit); // 用户名输入框
    	passwordEdit = (EditText) findViewById(R.id.user_login_pwd_edit); // 密码输入框
    	saveMsgBtn = (Button) findViewById(R.id.main_save_ms_btn); // 保存用户信息
    	focusView = findViewById(R.id.main_focus);
    	imgRe = (RelativeLayout) findViewById(R.id.main_blackboard_layout);
    	// 获取系统保存文件中为用户名和密码
    	String userName = UserMsg.getConfigMsg(context, UserMsg.USER_NAME);
    	String pwd = UserMsg.getConfigMsg(context, UserMsg.USER_PASSWORD);
    	String[] configParams = {userName, pwd};
    	
    	if (StringUtil.checkIsNull(configParams)) {
    		nameEdit.setText(userName);
		} else {
			isSaveUserMsg = true;
			saveMsgBtn.setBackgroundResource(R.drawable.main_user_ms_btn_click_bg);
			nameEdit.setText(userName);
			passwordEdit.setText(pwd);
		}
    }
    
    private Runnable mdownImageRunnable = new Runnable() {
		@Override
		public void run() {
			URL imageUrl = null;
			try {
				imageUrl = new URL(imageUrlString);
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
	
    /**
     * 获取首页图片
     * */
    private void doGetIndexImg() {
    	
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
		map.put("method", "Public.clientInit");
		map.put("args[num]", "12");
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);

		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new IndexHandler(DktForstudentActivity.this,
				MyContants.DO_HTTP_COURSE_LIST), map);
    }
    
    /**
     * 解析返回数据
     * */
    private void doGetImgUrlSucces(String result) {
    	
    	try {
    		GetIp getip = new GetIp(context);
    		String service_ip = getip.servise_ip;
			JSONObject jsonObject = new JSONObject(result);
			imageUrlString = MyContants.HTTP_PREFIX + service_ip +jsonObject.getString("client_index_pic");
			System.out.println(imageUrlString);
			downLoadThread = new Thread(mdownImageRunnable);
			downLoadThread.start();
		} catch (JSONException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
    }
    
    class IndexHandler extends HttpHandler {
		String tag;

		public IndexHandler(Context context, String tag) {
			super(context, tag);
			// TODO Auto-generated constructor stub
			this.tag = tag;
		}

		@Override
		public void handleMessage(Message msg) {
			super.handleMessage(msg);
//			dismissDialog(MyContants.HTTP_WAITING);

			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				if(tag.equals(MyContants.DO_HTTP_COURSE_LIST)) {
					doGetImgUrlSucces((String) msg.obj);
				}
			}

		}

	}
    
    /**
     *  页面点击事件
     * 
     * */
    private void addFun() {
    	
    	// 保存用户名和密码
    	saveMsgBtn.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				
				if(isSaveUserMsg) { //判断是否已经记录用户登录信息
					
					String[] configParams = { UserMsg.USER_PASSWORD };
					UserMsg.clearConfigMsg(context, configParams); // 只清除用户记录的密码
					saveMsgBtn.setBackgroundResource(R.drawable.main_user_ms_btn_bg);
					passwordEdit.setText("");
					isSaveUserMsg = false;
					  
				}else{
					
					String name = nameEdit.getText().toString().trim();
					String password = passwordEdit.getText().toString().trim();
					
					if(checkUserNameEditNull(name) && 
							checkPwdEditNull(password)) { // 用户名密码框都不为空时可以保存用户信息
						
						String[] UserValues = { name, password };
						String[] configParams = { UserMsg.USER_NAME, UserMsg.USER_PASSWORD };
						UserMsg.setConfigMsg(context, configParams, UserValues);
						saveMsgBtn.setBackgroundResource(R.drawable.main_user_ms_btn_click_bg);
						isSaveUserMsg = true;
					}
					  
				}
			}
		});
    	
    	// ip地址设置框
    	findViewById(R.id.main_set_ip_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					showSetPop();
					
				}
		});
    	
    	// 版本信息
    	findViewById(R.id.main_vesion_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					showVesionPop();
					
				}
		});
    	
    	// 本地资源
    	findViewById(R.id.main_myresourse_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					Intent intent  = new Intent(DktForstudentActivity.this,ResourceActivity.class);
					startActivity(intent);
					
				}
		});
    	
    	// 涂鸦
    	findViewById(R.id.main_graffiti_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					Intent intent  = new Intent(DktForstudentActivity.this, GraffitiActivity.class);
					startActivity(intent);
				}
		});
    	
    	// 记事本
    	findViewById(R.id.main_note_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					Intent intent = new Intent(DktForstudentActivity.this,
							NoteListActivity.class);
					startActivity(intent);
					
				}
		});
    	
    	// 拍照
    	findViewById(R.id.main_photograph_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					Intent intent = new Intent(android.provider.MediaStore.ACTION_IMAGE_CAPTURE);
					startActivityForResult(intent, MyContants.RESOURCE_IMG);
					
				}
		});
    	
    	// 录音
    	findViewById(R.id.main_recorder_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					Intent intent = new Intent(Intent.ACTION_GET_CONTENT);    
					intent.setType("audio/amr");    
					intent.setClassName("com.android.soundrecorder",
					"com.android.soundrecorder.SoundRecorder");
					startActivityForResult(intent, MyContants.RESOURCE_AUDIO);
				}
		});
    	
    	// 登录
    	findViewById(R.id.main_user_login_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					String name = nameEdit.getText().toString().trim();
					String password = passwordEdit.getText().toString().trim();
					
					if(checkUserNameEditNull(name) && 
							checkPwdEditNull(password)) { // 用户名密码框都不为空时可以登录
						
						doLoginHttp(name, password);
					}
					
					
				}
		});
    	
    	// 退出程序
    	findViewById(R.id.main_user_out_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					
					AlertDialog.Builder dialog=new AlertDialog.Builder(DktForstudentActivity.this);
					   dialog.setTitle(R.string.make_out).setIcon(android.R.drawable.ic_dialog_info)
					   .setMessage(R.string.make_out_true)
					   .setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {
						
						@Override
						public void onClick(DialogInterface dialog, int which) {
							
							System.exit(0); // 执行系统程序退出方法
							
						}
					}).setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
						
						
						public void onClick(DialogInterface dialog, int which) {
							// TODO Auto-generated method stub
							dialog.cancel();  // 取消弹出框
						}
					}).create().show();
					
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
				
				saveResource(MyContants.RESOURCE_IMG, getAbsoluteImagePath(data.getData()),
						getString(R.string.default_img_name), getString(R.string.photograph_name));
	
			}
			// 录音保存完成后
			else if(requestCode == MyContants.RESOURCE_AUDIO) {
				
				saveResource(MyContants.RESOURCE_AUDIO, getAbsoluteImagePath(data.getData()),
						getString(R.string.default_recording_name), getString(R.string.recording_name));
			
			}

		}
    	
    }
    
    private void doLoginHttp(String name, String password) {
    	((Activity) context).showDialog(MyContants.HTTP_WAITING);
    	if(isSaveUserMsg){
    		String[] UserValues = { name, password };
			String[] configParams = { UserMsg.USER_NAME, UserMsg.USER_PASSWORD };
			UserMsg.setConfigMsg(context, configParams, UserValues);
    	}
    	MyApp app = (MyApp) this.getApplication();
    	app.setAccount(name);
    	app.setPassWord(password);
    	
		long time = System.currentTimeMillis();
		HashMap<String, Object> map = new HashMap<String, Object>();
	
		map.put("method", "Public.login");
		map.put("args[username]", name);
		map.put("args[password]", password);
		map.put("args[version]", 20130809);
		map.put("args[clientType]", 3); // 1为全Android  2为ios  3为三星
		
		map.put("args[type]", 1); // 2为教师   1为学生 
		map.put("format", "JSON");
		map.put("ts", "" + time);
		map = Md5Util.testMD5Map(map);
		String temp = MyContants.APPURL;
		new HttpApacheMapPostThread(temp, new LoginHandler(context, ""), map);

	}
    
    /**
     * 登录返回信息验证
     * */
    private void doLoginSucess(String result) {
    	if(result.indexOf("errCode") > 0) {
    		try {
    			JSONObject jsonObject = new JSONObject(result);
    			String errCode1 = jsonObject.get("errCode").toString();
    			if(errCode1.equals("4")) {
    				ViewUtil.myToast(context, "非学生帐号登录");
    				return;
    			}else if(errCode1.equals("5")) {
    				ViewUtil.myToast(context, "帐号或密码错误，请重新输入");
    				return;
    			}
			} catch (Exception e) {
				// TODO: handle exception
			}
    	}
    	try {
    		JSONObject jsonObject = new JSONObject(result);
			String userId = jsonObject.get("auth_id").toString();
			String userName = jsonObject.get("auth_name").toString();
			String skey = jsonObject.get("skey").toString();
			String userType = jsonObject.get("auth_type").toString();
			String sId = jsonObject.get("s_id").toString();
			String userImgUrl = jsonObject.getString("auth_img").toString();
			System.out.println(userName + ":" + userId + ":" + skey + ":"
					+ userType);   
			String[] params = { userId, userName, skey, userType };
			
			if (StringUtil.checkIsNull(params)) {
				ViewUtil.myToast(context, "登录异常");
				return;
			}
			
			// 版本判断
			String version = jsonObject.get("version").toString();
			if(!version.equals("0")){
				new UpdateApp(context, version).checkUpdateInfo();
				return;
			}
			
			MyApp app = (MyApp) context.getApplicationContext();
			app.setUserId(Integer.valueOf(userId));
			app.setSkey(skey);
			app.setUserName(userName);
			app.setsId(sId);
			app.setUserImgUrl(userImgUrl);
			
			if(isServiceRunning("dkt.student.socket.SocketPushService")) {
				System.out.println("服务已经启动");
				context.stopService(intent);
				new Thread(startService).start();

			}else{
				System.out.println("服务未启动");
				new Thread(startService).start();

			}
			
			// 跳转到课程页面
			Intent intent  = new Intent(DktForstudentActivity.this, CourseActivity.class);
			startActivity(intent);
			
			/**
			 * 
			 * {"auth_name":"\u5f20\u6653","auth_id":"50",
			 * "auth_type":"2","skey":"%B4%88uq",
			 * "auth_img":"http:\/\/pic.dkt.com\/AuthAvatar\/48\/default.jpg",
			 * "s_id":"1","version":0}
			 * */
    	} catch (JSONException e) {
			ViewUtil.myToast(context, "登录异常");
		}
    }
    
    /**
	 * 服务开启线程
	 */
	Runnable startService = new Runnable(){
	
		@Override
		public void run() {
			// TODO Auto-generated method stub
            context.startService(intent);
		}
	};
	
    /**
     * 用来判断推送服务是否运行.
     * @param className 服务名称
     * @return true 在运行, false 不在运行
     */
      
    public boolean isServiceRunning(String className) {        
      
        boolean isRunning = false;   
        ActivityManager activityManager =      
        (ActivityManager)context.getSystemService(Context.ACTIVITY_SERVICE);    
        List<ActivityManager.RunningServiceInfo> serviceList      
        = activityManager.getRunningServices(Integer.MAX_VALUE);     
        if (!(serviceList.size()>0)) {     
            return false;      
        }   
        for (int i=0; i<serviceList.size(); i++) {     
            if (serviceList.get(i).service.getClassName().equals(className) == true) {     
                isRunning = true; 
                break;
            }      
        }      
        return isRunning;
      
    }
    
    class LoginHandler extends HttpHandler {

		public LoginHandler(Context context, String tag) {
			super(context, tag);
		}

		@Override
		public void handleMessage(Message msg) {

			super.handleMessage(msg);
			((Activity) context).dismissDialog(MyContants.HTTP_WAITING);
			if (msg.what == HttpStatus.SC_OK) {
				System.out.println("______返回值开始_____________");
				System.out.println(msg.obj);
				System.out.println("________返回值结束____________");
				
				doLoginSucess((String) msg.obj);
			}

		}

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
     * 文件保存完成后将资源信息新增到资源数据库
     * @param resourceType  资源类型
     * @param resourcePath  资源路径
     * @param defaultName   资源默认名称
     * @param title         弹出框title
     * */
    private void saveResource(final int resourceType, final String resourcePath,
    		final String defaultName, String title) {
    	
    	final EditText resourceNameEdit = new EditText(this);
    	resourceNameEdit.setTextColor(R.color.black);
    	resourceNameEdit.setBackgroundResource(R.drawable.system_set_edit_bg);
		AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(title).setIcon(android.R.drawable.ic_dialog_info).setView(resourceNameEdit);
        builder.setNegativeButton(R.string.cancel, new DialogInterface.OnClickListener() {
					
			public void onClick(DialogInterface dialog, int which) {
				
				// 删除当前完成保存的文件
				File delFile=new File(resourcePath);
				delFile.delete();
				
			}
			
		});
        
        builder.setPositiveButton(R.string.ok, new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
            	String resourceName = "";
            	
            	// 如果输入框为空则使用默认名称
            	if("".equals(resourceNameEdit.getText().toString())) {
					resourceName = defaultName;
            	}else{
					resourceName = resourceNameEdit.getText().toString();
            	}
            	long currentTimestamp = System.currentTimeMillis();
            	
            	Resource resource = new Resource();
    			resource.setResourceName(resourceName);
    			resource.setResourceType(resourceType);
    			resource.setResourcePath(resourcePath);
    			resource.setResourceCreatTime(currentTimestamp);
    			
    			ResourceServer resourceServer = new ResourceServer();
    			resourceServer.insertResource(resource);
    			
				Toast.makeText(context, resourceName+getString(R.string.save_ok), Toast.LENGTH_SHORT).show();

             }
        });
        builder.show();
    }
    
    /**
	 * 判断用户名输入框是否为空
	 * 
	 * @param username
	 * @return boolean
	 */
	private boolean checkUserNameEditNull(String UserName) {

		if (UserName.equals("")) {
			Toast.makeText(context, getString(R.string.enter_username), Toast.LENGTH_SHORT).show();
			return false;
		}
		return true;
	}
	
    /**
	 * 判断密码输入框是否为空
	 * 
	 * @param password
	 * @return
	 */
	private boolean checkPwdEditNull(String password) {

		if (password.equals("")) {
			Toast.makeText(context, getString(R.string.enter_pwd), Toast.LENGTH_SHORT).show();
			return false;
		}
		return true;
	}
	
	/**
	 *  弹出系统设置框
	 */
	private void showSetPop(){
		if(popSetIp == null){
			popSetIp= new SetIpPopu(context, focusView);
		}else{
			popSetIp.clearPopup();
		}
		
		popSetIp.showPopup();
	}
	
	/**
	 *  弹出系统版本框
	 */
	private void showVesionPop(){
		if(popVesion == null){
			popVesion= new VesionPopu(context, focusView);
		}else{
			popVesion.clearPopup();
		}
		
		popVesion.showPopup();
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
}