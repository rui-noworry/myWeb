package dkt.student.activity;


import java.io.File;
import java.io.InputStream;
import java.util.ArrayList;
import java.util.List;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import dkt.student.MyApp;
import dkt.student.R;
import dkt.student.database.MyNoteHandWritingServer;
import dkt.student.listener.HandwritingListener;
import dkt.student.model.IsDrawBg;
import dkt.student.model.NoteData;
import dkt.student.model.NoteForHan;
import dkt.student.view.MainView;
import dkt.student.view.MyImageView;
import dkt.student.view.MyImageViewOne;
import dkt.student.view.popu.HuanbiPopu;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.ActivityNotFoundException;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.View.OnClickListener;
import android.view.View.OnLongClickListener;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ImageView.ScaleType;

public class NoteShowActivity extends Activity implements HandwritingListener{
	
	private final int REQUEST_CODE_INSERT_IMAGE_OBJECT = 100;
	private Button notePenBtn;
	private Button mEraserBtn;
	
	// 写字板
	private RelativeLayout topRelative, handwritingRelative, mediaRelative;
	private int handIndex = 1, mediaIndex = 2;
	private MainView myView;
	private boolean is = true;
	private int imgNum = 3000;
	private int pageNum = 0;
	private int pageNums = 1;
	private IsDrawBg isDrawBg = new IsDrawBg();
	private List<NoteForHan> imageviewList = new ArrayList<NoteForHan>();
	private MyNoteHandWritingServer myHandWritingServer;;
	private List<Bitmap> allBitmap = new ArrayList<Bitmap>();
	
	private Bitmap myBitmap = null;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {  
		super.onCreate(savedInstanceState);
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		setContentView(R.layout.note_show_modefy);
		
		initView();
		addFun();

	}
	@Override
	protected void onRestart() {
		// TODO Auto-generated method stub
		super.onRestart();
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.note_book_back_btn));
	}
	
	@Override
	protected void onDestroy() {
		// TODO Auto-generated method stub
		super.onDestroy();
		saveMsg();
	}
	@Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);

		if(resultCode==RESULT_OK){
			if(data == null)
				return;
			
			if(requestCode == REQUEST_CODE_INSERT_IMAGE_OBJECT) {    			
				Uri imageFilePath = data.getData();
				String [] proj={MediaStore.Images.Media.DATA}; 
				Cursor cursor = managedQuery(imageFilePath,  proj, null, null, null);   
				int column_index = cursor.getColumnIndexOrThrow(MediaStore.Images.Media.DATA); 
				cursor.moveToFirst(); 
				
				String path = cursor.getString(column_index); 
				System.out.println(path);
				
				File f=new File(path);//检查文件是否存在
				if(f.exists()) {
					
					NoteForHan imgNote = new NoteForHan();
					imgNote.setId(imgNum);
					imgNote.setImgWidth(300);
					imgNote.setImgHeight(200);
					imgNote.setImgX(0);
					imgNote.setImgY(0);
					imgNote.setImgPath(path);

					addImageView(imgNote);
					imgNum = imgNum + 1;

				}
			}
		}
	}
	private Bitmap Bytes2Bimap(byte[] b){
	    if(b.length!=0){
	    	return BitmapFactory.decodeByteArray(b, 0, b.length);
	    }
	    else {
	    	return null;
	    }
	}
	private void initView(){
		Intent intent = getIntent();  
		String notepath = intent.getStringExtra("notepath");
		
		MyApp app = (MyApp) getApplication();
		app.setJiePView(findViewById(R.id.note_book_back_btn));
		
		topRelative = (RelativeLayout) findViewById(R.id.view_top);
		mediaRelative = (RelativeLayout) findViewById(R.id.view_media);
		handwritingRelative = (RelativeLayout) findViewById(R.id.note_canvas);
		
		myView = (MainView) findViewById(R.id.note_canvas_view);
		myView.setHandwritingListener(NoteShowActivity.this);
		myView.setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub					
				changeLayout(handIndex);
				return true;
			}
		});
		Log.v("sasdasaas", notepath);
		myHandWritingServer = new MyNoteHandWritingServer(notepath);
		int chechExistsNum = myHandWritingServer.checkExists();
        
        NoteData firstNoteData = new NoteData();
        if(2 == chechExistsNum) {
        	firstNoteData.setPageNum(pageNum);
        	firstNoteData.setBitmapData(null);
        	firstNoteData.setJesonData(null);
        	myHandWritingServer.savePage(firstNoteData);
        }else if(1 == chechExistsNum) {
        	setNoteData();
        }
        
        setPageNums();
        changeLayout(mediaIndex);
	}
	
	private void setPageNums() {
		pageNums = myHandWritingServer.getPageTotalNum();
	    TextView pageNumText = (TextView) findViewById(R.id.note_book_page_txt);
		pageNumText.setText("第" + (pageNum + 1) + "/" + pageNums + "页");
	}
	
	/**
	 * 在视图层添加本地图片
	 * 
	 * */
	private void addImageView(NoteForHan imgNote) {
		
		MyImageViewOne myImageView;
		Resources r = this.getResources();
		InputStream is = r.openRawResource(R.drawable.cancel);
		BitmapDrawable  bmpDraw = new BitmapDrawable(is);
		Bitmap bmp = bmpDraw.getBitmap();
		
		myImageView = new MyImageViewOne(NoteShowActivity.this, imgNote, bmp, isDrawBg);
		
		myImageView.setScaleType(ScaleType.FIT_XY);
        RelativeLayout.LayoutParams layoutParams = new RelativeLayout.LayoutParams(
			    imgNote.getImgWidth(), imgNote.getImgHeight());
		layoutParams.setMargins(imgNote.getImgX(), imgNote.getImgY(), 0, 0);
		myImageView.setLayoutParams(layoutParams);
		myImageView.setHandwritingListener(NoteShowActivity.this);
		
		Bitmap myBitmap=BitmapFactory.decodeFile(imgNote.getImgPath());
		myImageView.setImageBitmap(myBitmap);
		myImageView.setId(imgNote.getId());
	
		myImageView.setOnLongClickListener(new OnLongClickListener() {
			
			@Override
			public boolean onLongClick(View v) {
				// TODO Auto-generated method stub
				for(int i=0;i<imageviewList.size();i++) {
					if(imageviewList.get(i).getId() == v.getId()) {
						imageviewList.remove(imageviewList.get(i));
						Log.v("zhujj", "========"+imageviewList.size());
					}
				}
				mediaRelative.removeView(v);
				return true;
			}
		});
		mediaRelative.addView(myImageView);
		imageviewList.add(imgNote);
	}
	
	private void addFun() {
		notePenBtn = (Button)findViewById(R.id.note_book_change_btn);
		notePenBtn.setOnClickListener(noteBtnClickListener);
		notePenBtn.setOnLongClickListener(noteBtLongClickListener);
		
		mEraserBtn = (Button) findViewById(R.id.note_book_clear_btn);
		mEraserBtn.setOnClickListener(noteBtnClickListener);
		mEraserBtn.setOnLongClickListener(noteBtLongClickListener);
		

		//插图
		findViewById(R.id.note_book_insert_btn).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					callGalleryForInputImage(REQUEST_CODE_INSERT_IMAGE_OBJECT);
				}
		});

		//上一页
		findViewById(R.id.note_book_pre_page).setOnClickListener(
			new OnClickListener() {
	
				@Override
				public void onClick(View v) {
					saveMsg();
					if(0 == pageNum) {
						
						Toast.makeText(NoteShowActivity.this, "已经是第一页", Toast.LENGTH_SHORT).show();	
					}else{
						pageNum = pageNum - 1;
						myView.clear();
			        	mediaRelative.removeAllViews();
			        	imageviewList.clear();
			        	setNoteData();
			        	setPageNums();
					}
				}
		});
		//下一页
		findViewById(R.id.note_book_next_page).setOnClickListener(
			new OnClickListener() {

				@Override
				public void onClick(View v) {
					saveMsg();
					myView.clear();
		        	mediaRelative.removeAllViews();
		        	imageviewList.clear();
					if(pageNums == pageNum + 1) {
						pageNum = pageNum + 1;
						NoteData firstNoteData = new NoteData();
						firstNoteData.setPageNum(pageNum);
			        	byte[] inkData = myView.getBitmapForByte();
			        	firstNoteData.setBitmapData(inkData);
			        	firstNoteData.setJesonData("");
			        	myHandWritingServer.savePage(firstNoteData);
			        	
					}else{
						pageNum = pageNum + 1;
						setNoteData();
					}
					setPageNums();
				}
		});
		//返回
		findViewById(R.id.note_book_back_btn).setOnClickListener(
			new OnClickListener() {
	
				@Override
				public void onClick(View v) {
					//updateNotePageData();
					finish();
				}
		});
	}
	private NoteData setNoteData() {
		NoteData noteData = new NoteData();
		noteData = myHandWritingServer.loadPage(pageNum);
		Log.v("imgNumssssss", "====imgNum======"+imgNum);
		if(null != noteData.getJesonData()) {
	    	Log.v("zhujj", noteData.getJesonData());
			JSONArray jsonArray;
			try {
				jsonArray = new JSONArray(noteData.getJesonData());
				int count = jsonArray.length();
				for (int i = 0; i < count; i++) {
					JSONObject jo = jsonArray.getJSONObject(i);
					NoteForHan imgNote = new NoteForHan();
					imgNote.setId(Integer.parseInt(jo.get("tag").toString()));
					imgNote.setImgWidth(Integer.parseInt(jo.get("width").toString()));
					imgNote.setImgHeight(Integer.parseInt(jo.get("height").toString()));
					imgNote.setImgX(Integer.parseInt(jo.get("x").toString()));
					imgNote.setImgY(Integer.parseInt(jo.get("y").toString()));
					imgNote.setImgPath(jo.get("re_savepath").toString());
					if(i == count - 1) {
						imgNum = Integer.parseInt(jo.get("tag").toString()) + 1;
					}
					addImageView(imgNote);
					Log.v("imgNumssssss", "====imgNum======"+imgNum);
				}
			} catch (JSONException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}
		if(null != noteData.getBitmapData()) {
			myView.insertImg(Bytes2Bimap(noteData.getBitmapData()));
		}
		return noteData;
	}
	private OnLongClickListener noteBtLongClickListener = new OnLongClickListener() {
		
		@Override
		public boolean onLongClick(View v) {
			// TODO Auto-generated method stub
			int noteBtnID = v.getId();
			if(noteBtnID == notePenBtn.getId()){				
				HuanbiPopu myHuanbiPopu = new HuanbiPopu(NoteShowActivity.this, findViewById(R.id.main_focus), myView);
				myHuanbiPopu.showPopu();
				return true;
			}else if(noteBtnID == mEraserBtn.getId()){
				new AlertDialog.Builder(NoteShowActivity.this)   
				.setTitle("确认清屏")  
				.setMessage("确定清除所有数据吗？")  
				.setPositiveButton("确定", new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						myView.clear();
			        	mediaRelative.removeAllViews();
			        	imageviewList.clear();
					}})  
				.setNegativeButton("取消", new DialogInterface.OnClickListener() {
					@Override
					public void onClick(DialogInterface dialog, int which) {
						dialog.dismiss(); 

					}}) 
				.show();	
				return true;
			}
			return false;
		}
	};
	private OnClickListener noteBtnClickListener = new OnClickListener() {
		@Override
		public void onClick(View v) {
			int noteBtnID = v.getId();
			// If the mode is not changed, open the setting view. If the mode is same, close the setting view. 
			if(noteBtnID == notePenBtn.getId()){	
				myView.isClean(false);
				is = true;
//				NoteHuanBiPopu myHuanbiPopu = new NoteHuanBiPopu(NoteShowActivity.this, v, myView);
//				myHuanbiPopu.showPopu();
			}else if(noteBtnID == mEraserBtn.getId()){
				if(is) {
					myView.isClean(true);
					is = false;
				}else{
					myView.isClean(false);
					is = true;
				}
			}
		}
	};
	
	
	private void callGalleryForInputImage(int nRequestCode){
		try {
			Intent galleryIntent;
			galleryIntent = new Intent(); 
			galleryIntent.setAction(Intent.ACTION_GET_CONTENT);				
			galleryIntent.setType("image/*");
			galleryIntent.setClassName("com.cooliris.media", "com.cooliris.media.Gallery");
			startActivityForResult(galleryIntent, nRequestCode);
		} catch(ActivityNotFoundException e) {
			Intent galleryIntent;
			galleryIntent = new Intent();
			galleryIntent.setAction(Intent.ACTION_GET_CONTENT);
			galleryIntent.setType("image/*");
			startActivityForResult(galleryIntent, nRequestCode);
			e.printStackTrace();
		}		
	}
	/**
	 * 保存信息
	 * 
	 * */
	private void saveMsg() {
		JSONArray ja = new JSONArray();
		NoteData pageNotedata = new NoteData();
		if(imageviewList.size() == 0) {
			byte[] pageByte = myView.getBitmapForByte();
			pageNotedata.setBitmapData(pageByte);
			pageNotedata.setPageNum(pageNum);
			pageNotedata.setJesonData(null);
		}else{
			for(int i=0;i<imageviewList.size();i++) {
				JSONObject jo = new JSONObject();
				NoteForHan s = imageviewList.get(i);
				Log.v("zhujj", s.getImgPath()+"==="+s.getId()+"==="+s.getImgHeight()+"==="+s.getImgWidth()+"===="+s.getImgX()+"===="+s.getImgY());
				
				try {
					jo.put("re_savepath", s.getImgPath());
					jo.put("tag", s.getId());
					jo.put("type", 0);
					jo.put("height", s.getImgHeight());
					jo.put("width", s.getImgWidth());
					jo.put("x", s.getImgX());
					jo.put("y", s.getImgY());
				} catch (JSONException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
				ja.put(jo);
			}
			byte[] pageByte = myView.getBitmapForByte();
			pageNotedata.setBitmapData(pageByte);
			pageNotedata.setPageNum(pageNum);
			pageNotedata.setJesonData(ja.toString().replaceAll("\\\\", ""));
		}
		myHandWritingServer.updatePage(pageNotedata);
		Log.v("zhujj",ja.toString());

	}
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
	@Override
	public void closeImageView(View v) {
		// TODO Auto-generated method stub
		for(int i=0;i<imageviewList.size();i++) {
			if(imageviewList.get(i).getId() == v.getId()) {
				imageviewList.remove(imageviewList.get(i));
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
						imageviewList.get(i).getImgWidth(), imageviewList.get(i).getImgHeight());
				
				layoutParams.setMargins(imageviewList.get(i).getImgX(), imageviewList.get(i).getImgY(), 0, 0);
				mediaRelative.getChildAt(i).setLayoutParams(layoutParams);
			}
		}
		changeLayout(mediaIndex);
	}
	@Override
	public void closeActivity() {
		// TODO Auto-generated method stub
		
	}

}
