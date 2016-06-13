package dkt.student.activity;

import java.io.File;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import dkt.student.MyApp;
import dkt.student.MyContants;
import dkt.student.R;
import dkt.student.database.ResourceServer;
import dkt.student.model.Resource;
import dkt.student.util.StringUtil;
import dkt.student.util.ViewUtil;
import dkt.student.view.dialog.ResourceDialog;
import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.view.View.OnClickListener;
import android.widget.BaseAdapter;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

public class ResourceActivity extends Activity{

	private GridView resourceGridview;
	private gridviewAdapter resourceAdapter;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		// 设置无标题
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		// 设置全屏
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.resource);
        initView();
        addFun();
        initGridView();
	}
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.resource_back_btn));
	}
	 /**
     *  初始化页面全局控件
     * 
     * */
	private void initView() {
		resourceGridview = (GridView) findViewById(R.id.resource_gridView);
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.resource_back_btn));
	}
	
	 /**
     *  页面点击事件
     * 
     * */
	private void addFun() {
		
		// 返回
		findViewById(R.id.resource_back_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				finish();
			}
		});	
		// 图片
		findViewById(R.id.resource_show_image_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				changeGridview(MyContants.RESOURCE_IMG);
			}
		});
		// 视频
		findViewById(R.id.resource_show_video_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				changeGridview(MyContants.RESOURCE_VIDEO);
			}
		});
		// 文档
		findViewById(R.id.resource_show_doc_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				changeGridview(MyContants.RESOURCE_DOC);
			}
		});
		// 音频
		findViewById(R.id.resource_show_redio_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				changeGridview(MyContants.RESOURCE_AUDIO);
			}
		});
		// 搜索
		findViewById(R.id.resource_search_btn).setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				EditText editText = (EditText) findViewById(R.id.resource_search_edit);
				String searchString = editText.getText().toString().trim();
				String[] params = { searchString };
				if (StringUtil.checkIsNull(params)) {
					ViewUtil.myToast(ResourceActivity.this, getString(R.string.resource_search));
				} else {
					List<Resource> myResourceList = new ArrayList<Resource>();
					ResourceServer resourceServer = new ResourceServer();
					myResourceList = resourceServer.getSearchData(searchString);
					resourceAdapter.list = myResourceList;
					resourceAdapter.notifyDataSetChanged();
				}
			}
		});
	}
	
	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		// TODO Auto-generated method stub
		return false;
	}
	
	/**
	 * 初始化 resourceGridview
	 */
	private void initGridView() {
		List<Resource> myResourceList = new ArrayList<Resource>();
		
		ResourceServer resourceServer = new ResourceServer();
		myResourceList = resourceServer.getMyData(MyContants.RESOURCE_IMG);
		
		resourceAdapter = new gridviewAdapter(myResourceList, ResourceActivity.this);
		resourceGridview.setAdapter(resourceAdapter);
		
	}
	
	/**
	 *  刷新resourceGridview
	 *  @param resourceType   资源类型
	 * 
	 * */
	private void changeGridview(int resourceType) {
		
		List<Resource> myResourceList = new ArrayList<Resource>();
		ResourceServer resourceServer = new ResourceServer();
		myResourceList = resourceServer.getMyData(resourceType);
		resourceAdapter.list = myResourceList;
		resourceAdapter.notifyDataSetChanged();
		
	}
	
	
	public class gridviewAdapter extends BaseAdapter {
		List<Resource> list;
		Context context;

		public gridviewAdapter(List<Resource> myResourceList,
				Context context) {
			this.list = myResourceList;
			this.context = context;
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
		public View getView(final int position, View convertView,
				ViewGroup parent) {
			// TODO Auto-generated method stub
			Holder holder = new Holder();
			convertView = LayoutInflater.from(context).inflate(
					R.layout.resource_gridview_item, null);
			holder.name = (TextView) convertView.findViewById(R.id.ImageString);
			holder.icon = (ImageView) convertView.findViewById(R.id.ItemImage);
			holder.imageMsg = (ImageView) convertView
					.findViewById(R.id.item_msg);
			holder.name.setText(list.get(position).getResourceName());
			holder.relative = (RelativeLayout) convertView
					.findViewById(R.id.resource_head);
			
			int suff = list.get(position).getResourceType();
			// 设置背景图
			ViewUtil.setImageView(holder.icon, suff);
			
			holder.imageMsg.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					// 弹出框
					String filePath = list.get(position).getResourcePath();
					String fileName = list.get(position).getResourceName();
					long fileCreatTime = list.get(position).getResourceCreatTime();
					int fileType = list.get(position).getResourceType();
					ResourceDialog.createResourceDialog(context, filePath, fileName, 
							fileCreatTime, fileType);
				}
			});
			holder.relative.setOnClickListener(new OnClickListener() {

				@Override
				public void onClick(View v) {
					// TODO Auto-generated method stub
					
					String filePath = list.get(position).getResourcePath();
					String fileName = filePath.substring(filePath.lastIndexOf(".") + 1);
					File f = new File(filePath);
					if(!f.exists()) {
						Toast.makeText(context, getString(R.string.file_not_find), Toast.LENGTH_SHORT).show();
						ResourceServer resourceServer = new ResourceServer();
						resourceServer.delete(list.get(position).getResourceId());
						changeGridview(list.get(position).getResourceType());
					}else{
						ViewUtil.openResource(fileName, filePath, context);
					}
				}
			});

			return convertView;
		}

	}

	class Holder {
		TextView name;
		ImageView icon, imageMsg;
		RelativeLayout relative;
	}
}
