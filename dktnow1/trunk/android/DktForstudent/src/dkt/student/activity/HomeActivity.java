package dkt.student.activity;

import java.io.File;
import java.util.HashMap;

import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.database.ResourceServer;
import dkt.student.listener.FormListener;
import dkt.student.model.Resource;
import dkt.student.view.FormForClassView;
import dkt.student.view.popu.ToolPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.KeyEvent;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.Toast;

public class HomeActivity extends Activity implements FormListener{

	private FormForClassView myFormForClassView;
	private HashMap<String, String> list = new HashMap<String, String>();
	private Context context;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		 // 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.home);

		initView(); // 初始化页面全局控件
        addFun();  // 组装页面点击事件
	}
		 
    /**
     *  初始化页面全局控件
     * 
     * */
	private void initView() {
		context = HomeActivity.this;
		
		myFormForClassView = (FormForClassView) findViewById(R.id.form_class);
        list.put("3", "数学初一(2)班");
		list.put("12", "数学初一(1)班");
		list.put("18", "数学初二(1)班");
		list.put("21", "数学初二(2)班");
		list.put("33", "数学初一(2)班");
		list.put("46", "数学初一(2)班");
		myFormForClassView.setList(list);
		myFormForClassView.setFormListener(HomeActivity.this);
		
		RadioButton homeRadio = (RadioButton)findViewById(R.id.home_home_btn);
		homeRadio.setChecked(true);
	}
	
	 /**
     *  页面点击事件
     * 
     * */
	private void addFun() {
		
		// 返回
		findViewById(R.id.home_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				finish();
			}
		});	
		
		// 首页
		findViewById(R.id.home_home_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
//				Intent intent  = new Intent(HomeActivity.this, HomeActivity.class);
//				intent.setFlags(intent.FLAG_ACTIVITY_CLEAR_TOP);
//				startActivity(intent);
			}
		});	
		
		// 课程中心
		findViewById(R.id.home_class_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				Intent intent  = new Intent(HomeActivity.this, CourseActivity.class);
//				intent.setFlags(intent.FLAG_ACTIVITY_NO_HISTORY);
				startActivity(intent);
			}
		});	
		
		// 小工具
		findViewById(R.id.home_tool_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				ToolPopu myToolPopu = new ToolPopu(context, v);
				myToolPopu.showPopup();
			}
		});
	}
	
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		return false;
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
    
	@Override
	public void showNum(String num) {
		// TODO Auto-generated method stub
		if(list.containsKey(num)) {
			Toast.makeText(HomeActivity.this, list.get(num), Toast.LENGTH_SHORT).show();

		}else{
//			Toast.makeText(HomeActivity.this, num, Toast.LENGTH_SHORT).show();

		}
	}
}
